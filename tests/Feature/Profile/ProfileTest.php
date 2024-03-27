<?php

namespace Tests\Feature\Profile;

use App\Models\Administrator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a guest can not store a profile.
     */
    public function test_guest_can_not_store_profile()
    {
        $response = $this->postJson(route('api.profiles.store'));

        $response->assertUnauthorized();
    }

    /**
     * Test an administrator can store a profile.
     */
    public function test_administrator_can_store_profile()
    {
        Storage::fake('local');

        $administrator = Administrator::factory()->create();

        $response = $this->actingAs($administrator)
            ->postJson(route('api.profiles.store'), [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'image' => UploadedFile::fake()->image('image.jpg')->size(100),
                'status' => 'active',
            ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            'id',
            'administrator_id',
            'last_name',
            'first_name',
            'image',
            'status',
            'created_at',
        ]);

        Storage::disk('local')->assertExists('public/images/'.$response['image']);
    }

    /**
     * Test an administrator can not store a profile with invalid data.
     */
    public function test_administrator_can_not_store_profile_with_invalid_data()
    {
        $administrator = Administrator::factory()->create();

        $response = $this->actingAs($administrator)
            ->postJson(route('api.profiles.store'), [
                'first_name' => '',
                'last_name' => '',
                'image' => 'invalid-image',
                'status' => 'invalid-status',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'first_name',
            'last_name',
            'image',
            'status',
        ]);
    }

    /**
     * Test an administrator can update a profile.
     */
    public function test_administrator_can_update_profile()
    {
        Storage::fake('local');

        $administrator = Administrator::factory()->create();

        $profile = $administrator->profiles()->create([
            'last_name' => 'Doe',
            'first_name' => 'John',
            'image' => UploadedFile::fake()->image('image.jpg')->size(100),
            'status' => 'active',
        ]);

        $response = $this->actingAs($administrator)
            ->putJson(route('api.profiles.update', $profile), [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'image' => UploadedFile::fake()->image('image.jpg')->size(100),
                'status' => 'inactive',
            ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'administrator_id',
            'last_name',
            'first_name',
            'image',
            'status',
            'created_at',
        ]);

        Storage::disk('local')->assertMissing('public/images/'.$profile->image);
        Storage::disk('local')->assertExists('public/images/'.$response['image']);
    }

    /**
     * Test an administrator can delete a profile.
     */
    public function test_administrator_can_delete_profile()
    {
        Storage::fake('local');

        $administrator = Administrator::factory()->create();

        $profile = $administrator->profiles()->create([
            'last_name' => 'Doe',
            'first_name' => 'John',
            'image' => UploadedFile::fake()->image('image.jpg')->size(100),
            'status' => 'active',
        ]);

        $response = $this->actingAs($administrator)
            ->deleteJson(route('api.profiles.destroy', $profile));

        $response->assertNoContent();
        $this->assertModelMissing($profile);
        Storage::disk('local')->assertMissing('public/images/'.$profile->image);
    }
}
