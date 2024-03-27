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
     * Test a guest can't store a profile.
     */
    public function test_guest_cant_store_profile()
    {
        $response = $this->postJson(route('api.profile.store'));

        $response->assertUnauthorized();
    }

    /**
     * Test an administrator can store a profile.
     */
    public function test_administrator_can_store_profile()
    {
        $administrator = Administrator::factory()->create();

        $response = $this->actingAs($administrator)
            ->postJson(route('api.profile.store'), [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'image' => UploadedFile::fake()->image('avatar.jpg')->size(100),
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

        Storage::disk('public')->assertExists($response['image']);
    }

    /**
     * Test an administrator can't store a profile with invalid data.
     */
    public function test_administrator_cant_store_profile_with_invalid_data()
    {
        $administrator = Administrator::factory()->create();

        $response = $this->actingAs($administrator)
            ->postJson(route('api.profile.store'), [
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
}
