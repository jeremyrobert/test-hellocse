<?php

namespace Tests\Feature;

use App\Models\Administrator;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a guest can not store a comment.
     */
    public function test_guest_can_not_store_comment()
    {
        $profile = Profile::inRandomOrder()->first();

        $response = $this->postJson(
            route('api.profiles.comments.store', ['profile' => $profile->id]),
            [
                'content' => 'This is a comment.',
            ]);

        $response->assertUnauthorized();
    }

    /**
     * Test an administrator can store a comment.
     */
    public function test_administrator_can_store_comment()
    {
        $administrator = Administrator::factory()->create();
        $profile = Profile::inRandomOrder()->first();

        $response = $this->actingAs($administrator)
            ->postJson(
                route('api.profiles.comments.store', ['profile' => $profile->id]),
                [
                    'content' => 'This is a comment.',
                ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            'id',
            'profile_id',
            'administrator_id',
            'content',
            'created_at',
        ]);
    }

    /**
     * Test an administrator can not store a comment with invalid data.
     */
    public function test_administrator_can_not_store_comment_with_invalid_data()
    {
        $administrator = Administrator::factory()->create();
        $profile = Profile::inRandomOrder()->first();

        $response = $this->actingAs($administrator)
            ->postJson(
                route('api.profiles.comments.store', ['profile' => $profile->id]),
                [
                    'content' => '',
                ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('content');
    }

    /**
     * Test an administrator can not comment twice on the same profile.
     */
    public function test_administrator_can_not_comment_twice_on_the_same_profile()
    {
        $administrator = Administrator::factory()->create();
        $profile = Profile::inRandomOrder()->first();

        $response = $this->actingAs($administrator)
            ->postJson(
                route('api.profiles.comments.store', ['profile' => $profile->id]),
                [
                    'content' => 'This is a comment.',
                ]);

        $response->assertCreated();

        $response = $this->actingAs($administrator)
            ->postJson(
                route('api.profiles.comments.store', ['profile' => $profile->id]),
                [
                    'content' => 'This is another comment.',
                ]);

        $response->assertForbidden();
    }
}
