<?php

namespace App\Modules\DynamicVendors\Repositories;

use App\Modules\DynamicVendors\Models\DynamicVendor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DynamicVendorRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function categories(): Collection;

    public function create(array $data): DynamicVendor;

    public function update(DynamicVendor $vendor, array $data): DynamicVendor;

    public function delete(DynamicVendor $vendor): void;
}
