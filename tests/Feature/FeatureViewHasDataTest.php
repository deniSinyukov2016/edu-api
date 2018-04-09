<?php


namespace Tests\Feature;

use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeatureViewHasDataTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function categories_list_blade_has_all_parent_categories()
    {
       $this->call('GET', route('site.categories.show'))
            ->assertStatus(200)
            ->assertViewHas('selectedCategories');
    }
}
