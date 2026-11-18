<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Modules\DynamicVendors\Models\VendorCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorCategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Category Admin',
            'email' => 'category-admin@example.com',
            'password' => 'password',
        ]);
    }

    public function test_admin_can_manage_vendor_categories_and_their_form_fields(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.vendor-categories.store'), [
            'name' => 'Live Music',
            'description' => 'Bands and live performers.',
            'is_active' => '1',
            'sort_order' => '4',
            'fields' => [[
                'label' => 'Music styles',
                'type' => 'multi_select',
                'help' => 'Styles performed by the band',
                'options' => 'Bollywood, Jazz, Classical',
                'unit' => 'fixed',
            ]],
        ]);

        $response->assertRedirect(route('admin.vendor-categories.index'));
        $category = VendorCategory::where('name', 'Live Music')->firstOrFail();
        $this->assertSame(['Bollywood', 'Jazz', 'Classical'], $category->fields[0]['options']);

        $this->get(route('admin.dynamic-vendors.create'))
            ->assertOk()
            ->assertSee('Live Music')
            ->assertSee('Music styles');

        $this->put(route('admin.vendor-categories.update', $category), [
            'name' => 'Live Entertainment',
            'is_active' => '0',
            'sort_order' => '5',
            'fields' => [[
                'label' => 'Performance type',
                'type' => 'text',
                'help' => 'Main performance type',
                'options' => '',
                'unit' => 'fixed',
            ]],
        ])->assertRedirect(route('admin.vendor-categories.index'));

        $this->assertDatabaseHas('vendor_categories', [
            'name' => 'Live Entertainment',
            'is_active' => false,
        ]);

        $this->delete(route('admin.vendor-categories.destroy', $category->fresh()))->assertRedirect();
        $this->assertDatabaseMissing('vendor_categories', ['id' => $category->id]);
    }

    public function test_category_routes_require_admin_authentication(): void
    {
        auth('admin')->logout();

        $this->get(route('admin.vendor-categories.index'))->assertRedirect(route('admin.login'));
    }
}
