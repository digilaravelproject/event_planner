<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use App\Modules\DynamicVendors\Models\DynamicVendorVersion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DynamicVendorManagementTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Module Admin',
            'email' => 'dynamic-admin@example.com',
            'password' => 'password',
        ]);
    }

    public function test_module_is_isolated_and_requires_admin_authentication(): void
    {
        $this->get(route('admin.dynamic-vendors.index'))
            ->assertRedirect(route('admin.login'));

        $this->assertFalse(Schema::hasTable('vendors'));
        $this->assertDatabaseCount('vendors_dynamic', 0);
    }

    public function test_admin_can_create_a_clean_dynamic_vendor_with_an_image(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.dynamic-vendors.store'), array_merge(
            $this->payload(),
            ['images' => [UploadedFile::fake()->image('garden.jpg')]],
        ));

        $vendor = DynamicVendor::firstOrFail();
        $response->assertRedirect(route('admin.dynamic-vendors.show', $vendor));
        $this->get(route('admin.dynamic-vendors.show', $vendor))
            ->assertOk()
            ->assertSee('Dynamic attributes');
        $this->assertSame('Royal Garden', $vendor->name);
        $this->assertSame('Wedding Hall', $vendor->category);
        $this->assertEquals(250000, data_get($vendor->vendor_json, 'attributes.0.value'));
        $this->assertSame('currency', data_get($vendor->vendor_json, 'attributes.0.type'));
        $this->assertSame(1, data_get($vendor->vendor_json, 'schema_version'));
        $this->assertArrayNotHasKey('ai', data_get($vendor->vendor_json, 'attributes.0'));
        $this->assertArrayNotHasKey('costing', data_get($vendor->vendor_json, 'attributes.0'));
        $this->assertArrayNotHasKey('help_text', data_get($vendor->vendor_json, 'attributes.0.validation'));
        $this->assertArrayNotHasKey('placeholder', data_get($vendor->vendor_json, 'attributes.0.validation'));
        $this->assertSame(['Pune', 'Mumbai'], data_get($vendor->vendor_json, 'attributes.2.validation.allowed_values'));
        $this->assertCount(1, data_get($vendor->vendor_json, 'media.images'));
        Storage::disk('public')->assertExists(data_get($vendor->vendor_json, 'media.images.0'));
        $this->assertDatabaseHas('dynamic_vendor_versions', ['dynamic_vendor_id' => $vendor->id, 'version' => 1]);
        $this->assertFalse(Schema::hasTable('vendors'));
    }

    public function test_update_duplicate_status_and_rollback_each_preserve_version_history(): void
    {
        $this->actingAs($this->admin, 'admin')->post(route('admin.dynamic-vendors.store'), $this->payload());
        $vendor = DynamicVendor::firstOrFail();

        $updated = $this->payload();
        $updated['name'] = 'Royal Garden Premium';
        $updated['attributes'][0]['value'] = '300000';
        $this->put(route('admin.dynamic-vendors.update', $vendor), $updated)
            ->assertRedirect(route('admin.dynamic-vendors.show', $vendor));

        $vendor->refresh();
        $this->assertSame('Royal Garden Premium', $vendor->name);
        $this->assertCount(2, $vendor->versions);

        $this->patch(route('admin.dynamic-vendors.status', $vendor), ['status' => 'inactive'])->assertRedirect();
        $this->assertSame('inactive', $vendor->refresh()->status);
        $this->assertCount(3, $vendor->versions);

        $original = DynamicVendorVersion::where('dynamic_vendor_id', $vendor->id)->where('version', 1)->firstOrFail();
        $this->post(route('admin.dynamic-vendors.rollback', [$vendor, $original]))->assertRedirect();
        $this->assertSame('Royal Garden', $vendor->refresh()->name);
        $this->assertSame('active', $vendor->status);
        $this->assertCount(4, $vendor->versions);

        $this->post(route('admin.dynamic-vendors.duplicate', $vendor))->assertRedirect();
        $copy = DynamicVendor::whereKeyNot($vendor->id)->firstOrFail();
        $this->assertSame('Royal Garden (Copy)', $copy->name);
        $this->assertSame('draft', $copy->status);
        $this->assertSame($vendor->id, data_get($copy->vendor_json, 'source.duplicated_from_id'));
        $this->assertCount(1, $copy->versions);
    }

    public function test_listing_supports_json_search_category_filter_and_sorting(): void
    {
        $this->actingAs($this->admin, 'admin')->post(route('admin.dynamic-vendors.store'), $this->payload());
        $other = $this->payload();
        $other['name'] = 'ABC Photography';
        $other['category'] = 'Photographer';
        $other['attributes'][2]['value'] = 'Nashik';
        $other['attributes'][2]['allowed_values'] = 'Nashik, Mumbai';
        $this->post(route('admin.dynamic-vendors.store'), $other);

        $this->get(route('admin.dynamic-vendors.index', ['search' => 'Royal Garden']))
            ->assertOk()->assertSee('Royal Garden')->assertDontSee('ABC Photography');
        $this->get(route('admin.dynamic-vendors.index', ['category' => 'Photographer', 'sort' => 'name', 'direction' => 'asc']))
            ->assertOk()->assertSee('ABC Photography')->assertDontSee('Royal Garden');
    }

    public function test_dynamic_required_and_typed_validation_is_enforced(): void
    {
        $payload = $this->payload();
        $payload['attributes'][0]['value'] = 'not-money';
        $payload['attributes'][1]['value'] = '';
        $payload['attributes'][1]['required'] = '1';

        $this->actingAs($this->admin, 'admin')->post(route('admin.dynamic-vendors.store'), $payload)
            ->assertSessionHasErrors(['attributes.0.value', 'attributes.1.value']);
        $this->assertDatabaseCount('vendors_dynamic', 0);
    }

    public function test_attribute_editor_stays_simple_without_ai_or_costing_configuration(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.dynamic-vendors.create'))
            ->assertOk()
            ->assertSee('Optional validation', false)
            ->assertDontSee('AI matching & costing configuration', false)
            ->assertDontSee('Semantic role')
            ->assertDontSee('Preference match')
            ->assertDontSee('Include in costing')
            ->assertDontSee('Help text')
            ->assertDontSee('Optional validation & guidance', false);
    }

    public function test_admin_can_download_and_import_the_sample_attribute_sheet(): void
    {
        $sample = base_path('app/Modules/DynamicVendors/resources/samples/sample_attribute.xlsx');

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.dynamic-vendors.attribute-sheet.sample'))
            ->assertOk()
            ->assertDownload('sample_attribute.xlsx');

        $this->post(route('admin.dynamic-vendors.attribute-sheet.import'), [
            'attribute_sheet' => UploadedFile::fake()->createWithContent('attributes.xlsx', file_get_contents($sample)),
        ])->assertOk()->assertJson([
            'attributes' => [[
                'label' => 'Price',
                'value' => '1000',
                'type' => 'number',
            ]],
        ]);
    }

    public function test_attribute_sheet_import_rejects_an_invalid_excel_file(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.dynamic-vendors.attribute-sheet.import'), [
                'attribute_sheet' => UploadedFile::fake()->createWithContent('attributes.xlsx', 'not an Excel workbook'),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('attribute_sheet');
    }

    private function payload(): array
    {
        return [
            'name' => 'Royal Garden',
            'category' => 'Wedding Hall',
            'status' => 'active',
            'attributes' => [
                [
                    'label' => 'Price', 'type' => 'currency', 'value' => '250000',
                    'required' => '1', 'min_value' => '0', 'max_value' => '500000',
                ],
                [
                    'label' => 'Capacity', 'type' => 'number', 'value' => '1000',
                    'required' => '1', 'min_value' => '1',
                ],
                [
                    'label' => 'Area', 'type' => 'dropdown', 'value' => 'Pune',
                    'allowed_values' => 'Pune, Mumbai',
                ],
                [
                    'label' => 'Parking', 'type' => 'boolean', 'value' => '1',
                ],
            ],
            'short_description' => 'Luxury wedding venue',
            'description' => 'A large venue in Pune.',
            'tags' => 'wedding, venue',
            'keywords' => 'hall, Pune, parking',
        ];
    }
}
