<?php

namespace App\Modules\DynamicVendors\Repositories;

use App\Modules\DynamicVendors\Models\DynamicVendor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DynamicVendorRepository implements DynamicVendorRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = DynamicVendor::query();

        $query->when($filters['search'] ?? null, function (Builder $query, string $search): void {
            $query->where('vendor_json', 'like', '%'.$this->escapeLike($search).'%');
        });
        $query->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status));
        $query->when($filters['category'] ?? null, function (Builder $query, string $category): void {
            $query->whereRaw($this->jsonValueExpression('identity.category').' = ?', [$category]);
        });

        $sort = $filters['sort'] ?? 'created_at';
        $direction = ($filters['direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        if (in_array($sort, ['name', 'category'], true)) {
            $query->orderByRaw($this->jsonValueExpression('identity.'.$sort).' '.$direction);
        } else {
            $query->orderBy(in_array($sort, ['status', 'created_at'], true) ? $sort : 'created_at', $direction);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function categories(): Collection
    {
        return DynamicVendor::query()
            ->toBase()
            ->selectRaw($this->jsonValueExpression('identity.category').' as category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter()
            ->values();
    }

    public function create(array $data): DynamicVendor
    {
        return DynamicVendor::create($data);
    }

    public function update(DynamicVendor $vendor, array $data): DynamicVendor
    {
        $vendor->update($data);

        return $vendor->refresh();
    }

    public function delete(DynamicVendor $vendor): void
    {
        $vendor->delete();
    }

    private function jsonValueExpression(string $path): string
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return "json_extract(vendor_json, '$.".$path."')";
        }

        return "JSON_UNQUOTE(JSON_EXTRACT(vendor_json, '$.".$path."'))";
    }

    private function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }
}
