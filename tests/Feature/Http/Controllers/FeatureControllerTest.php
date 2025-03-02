<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\FeatureController
 */
final class FeatureControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $features = Feature::factory()->count(3)->create();

        $response = $this->get(route('features.index'));

        $response->assertOk();
        $response->assertViewIs('feature.index');
        $response->assertViewHas('features');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('features.create'));

        $response->assertOk();
        $response->assertViewIs('feature.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\FeatureController::class,
            'store',
            \App\Http\Requests\FeatureStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = fake()->name();

        $response = $this->post(route('features.store'), [
            'name' => $name,
        ]);

        $features = Feature::query()
            ->where('name', $name)
            ->get();
        $this->assertCount(1, $features);
        $feature = $features->first();

        $response->assertRedirect(route('features.index'));
        $response->assertSessionHas('feature.id', $feature->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $feature = Feature::factory()->create();

        $response = $this->get(route('features.show', $feature));

        $response->assertOk();
        $response->assertViewIs('feature.show');
        $response->assertViewHas('feature');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $feature = Feature::factory()->create();

        $response = $this->get(route('features.edit', $feature));

        $response->assertOk();
        $response->assertViewIs('feature.edit');
        $response->assertViewHas('feature');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\FeatureController::class,
            'update',
            \App\Http\Requests\FeatureUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $feature = Feature::factory()->create();
        $name = fake()->name();

        $response = $this->put(route('features.update', $feature), [
            'name' => $name,
        ]);

        $feature->refresh();

        $response->assertRedirect(route('features.index'));
        $response->assertSessionHas('feature.id', $feature->id);

        $this->assertEquals($name, $feature->name);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $feature = Feature::factory()->create();

        $response = $this->delete(route('features.destroy', $feature));

        $response->assertRedirect(route('features.index'));

        $this->assertModelMissing($feature);
    }
}
