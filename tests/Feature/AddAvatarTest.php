<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function only_members_can_add_avatars()
    {
        $this->json('POST','/api/users/{user}/avatar')
            ->assertStatus(401);
    }

    /** @test */
    function a_valid_avatar_must_be_provided()
    {
        $this->signIn();

        $this->json('POST', 'api/users/' . auth()->id() . '/avatar', [
            'avatar' => 'not_an_image'
        ])->assertStatus(422);
    }

    /** @test */
    function a_user_may_add_an_avatar_to_their_profile()
    {
        $this->signIn();

        Storage::fake('public');

        $this->json('POST', 'api/users/' . auth()->id() . '/avatar', [
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ]);

        Storage::disk('public')->assertExists('avatars/avatar.jpg');
    }
}
