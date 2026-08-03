<?php

namespace App\Modules\DynamicVendors\Services;

use App\Modules\DynamicVendors\Models\DynamicVendor;
use App\Modules\DynamicVendors\Models\DynamicVendorVersion;
use App\Modules\DynamicVendors\Repositories\DynamicVendorRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class DynamicVendorService
{
    public function __construct(private readonly DynamicVendorRepositoryInterface $vendors) {}

    public function create(array $input, array $files, ?int $adminId): DynamicVendor
    {
        return DB::transaction(function () use ($input, $files, $adminId): DynamicVendor {
            $vendor = $this->vendors->create([
                'vendor_json' => $this->buildDocument($input, $files),
                'status' => $input['status'],
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ]);
            $this->snapshot($vendor, $adminId);

            return $vendor;
        });
    }

    public function update(DynamicVendor $vendor, array $input, array $files, ?int $adminId): DynamicVendor
    {
        return DB::transaction(function () use ($vendor, $input, $files, $adminId): DynamicVendor {
            $vendor = DynamicVendor::query()->lockForUpdate()->findOrFail($vendor->id);
            $vendor = $this->vendors->update($vendor, [
                'vendor_json' => $this->buildDocument($input, $files, $vendor->vendor_json),
                'status' => $input['status'],
                'updated_by' => $adminId,
            ]);
            $this->snapshot($vendor, $adminId);

            return $vendor;
        });
    }

    public function duplicate(DynamicVendor $source, ?int $adminId): DynamicVendor
    {
        return DB::transaction(function () use ($source, $adminId): DynamicVendor {
            $source = DynamicVendor::query()->lockForUpdate()->findOrFail($source->id);
            $document = $source->vendor_json;
            $document['identity']['name'] = $source->name.' (Copy)';
            $document['source'] = ['duplicated_from_id' => $source->id];
            $vendor = $this->vendors->create([
                'vendor_json' => $document,
                'status' => 'draft',
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ]);
            $this->snapshot($vendor, $adminId);

            return $vendor;
        });
    }

    public function changeStatus(DynamicVendor $vendor, string $status, ?int $adminId): DynamicVendor
    {
        if (! in_array($status, ['active', 'inactive', 'draft', 'archived'], true)) {
            throw new InvalidArgumentException('Unsupported vendor status.');
        }

        return DB::transaction(function () use ($vendor, $status, $adminId): DynamicVendor {
            $vendor = DynamicVendor::query()->lockForUpdate()->findOrFail($vendor->id);
            $vendor = $this->vendors->update($vendor, ['status' => $status, 'updated_by' => $adminId]);
            $this->snapshot($vendor, $adminId);

            return $vendor;
        });
    }

    public function rollback(DynamicVendor $vendor, DynamicVendorVersion $version, ?int $adminId): DynamicVendor
    {
        if ($version->dynamic_vendor_id !== $vendor->id) {
            abort(404);
        }

        return DB::transaction(function () use ($vendor, $version, $adminId): DynamicVendor {
            $vendor = DynamicVendor::query()->lockForUpdate()->findOrFail($vendor->id);
            $vendor = $this->vendors->update($vendor, [
                'vendor_json' => $version->vendor_json,
                'status' => $version->status,
                'updated_by' => $adminId,
            ]);
            $this->snapshot($vendor, $adminId);

            return $vendor;
        });
    }

    public function delete(DynamicVendor $vendor): void
    {
        $this->vendors->delete($vendor);
    }

    private function buildDocument(array $input, array $files, ?array $existing = null): array
    {
        $folder = 'dynamic-vendors/'.Str::uuid();
        $attributes = [];

        foreach ($input['attributes'] ?? [] as $position => $attribute) {
            $type = $attribute['type'];
            $value = $this->normaliseValue($type, $attribute['value'] ?? null);
            if (($files['attribute_uploads'][$position] ?? null) instanceof UploadedFile) {
                $value = $files['attribute_uploads'][$position]->store($folder.'/attributes', 'public');
            }

            $attributes[] = [
                'id' => $attribute['id'] ?? (string) Str::uuid(),
                'key' => $this->uniqueKey((string) $attribute['label'], $attributes),
                'label' => trim((string) $attribute['label']),
                'type' => $type,
                'value' => $value,
                'validation' => array_filter([
                    'required' => filter_var($attribute['required'] ?? false, FILTER_VALIDATE_BOOL),
                    'min_length' => $this->nullableInteger($attribute['min_length'] ?? null),
                    'max_length' => $this->nullableInteger($attribute['max_length'] ?? null),
                    'min_value' => $this->nullableNumber($attribute['min_value'] ?? null),
                    'max_value' => $this->nullableNumber($attribute['max_value'] ?? null),
                    'allowed_values' => $this->csv($attribute['allowed_values'] ?? null),
                    'default_value' => $attribute['default_value'] ?? null,
                ], fn ($value) => $value !== null && $value !== [] && $value !== ''),
                'position' => $position,
            ];
        }

        $allowedExistingImages = collect(data_get($existing, 'media.images', []))->filter(fn ($path) => is_string($path));
        $images = collect($input['existing_images'] ?? [])->intersect($allowedExistingImages)->values()->all();
        foreach ($files['images'] ?? [] as $image) {
            if ($image instanceof UploadedFile) {
                $images[] = $image->store($folder.'/images', 'public');
            }
        }

        return [
            'schema_version' => 1,
            'identity' => [
                'name' => trim((string) $input['name']),
                'category' => trim((string) $input['category']),
            ],
            'attributes' => $attributes,
            'media' => ['images' => array_values($images)],
            'seo' => [
                'short_description' => $input['short_description'] ?? null,
                'description' => $input['description'] ?? null,
                'tags' => $this->csv($input['tags'] ?? null),
                'keywords' => $this->csv($input['keywords'] ?? null),
            ],
        ];
    }

    private function snapshot(DynamicVendor $vendor, ?int $adminId): void
    {
        $latest = DynamicVendorVersion::query()
            ->where('dynamic_vendor_id', $vendor->id)
            ->lockForUpdate()
            ->max('version');

        $vendor->versions()->create([
            'version' => ((int) $latest) + 1,
            'vendor_json' => $vendor->vendor_json,
            'status' => $vendor->status,
            'created_by' => $adminId,
        ]);
    }

    private function normaliseValue(string $type, mixed $value): mixed
    {
        return match ($type) {
            'number', 'currency' => $value === null || $value === '' ? null : (float) $value,
            'boolean', 'checkbox' => filter_var($value, FILTER_VALIDATE_BOOL),
            'multi_select' => $this->csv($value),
            'json' => $value === null || $value === '' ? null : json_decode((string) $value, true, flags: JSON_THROW_ON_ERROR),
            'gps' => $value === null || $value === '' ? null : $this->normaliseGps((string) $value),
            default => is_string($value) ? trim($value) : $value,
        };
    }

    private function uniqueKey(string $label, array $attributes): string
    {
        $base = Str::snake(Str::ascii(trim($label))) ?: 'attribute';
        $keys = Arr::pluck($attributes, 'key');
        $key = $base;
        $suffix = 2;
        while (in_array($key, $keys, true)) {
            $key = $base.'_'.$suffix++;
        }

        return $key;
    }

    private function csv(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map('trim', $value), fn ($item) => $item !== ''));
        }

        return array_values(array_filter(array_map('trim', explode(',', (string) $value)), fn ($item) => $item !== ''));
    }

    private function nullableInteger(mixed $value): ?int
    {
        return $value === null || $value === '' ? null : (int) $value;
    }

    private function nullableNumber(mixed $value): int|float|null
    {
        return $value === null || $value === '' ? null : (float) $value;
    }

    private function normaliseGps(string $value): array
    {
        [$latitude, $longitude] = array_map('trim', explode(',', $value, 2));

        return ['latitude' => (float) $latitude, 'longitude' => (float) $longitude];
    }
}
