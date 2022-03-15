<?php

namespace Tests\Feature;

use App\Mail\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvitationTest extends TestCase
{

    /**@test */
    public function test_it_should_be_able_to_invite_someone_to_the_plataform()
    {

        //Arrange
        Mail::fake();

        /**@var User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        // Act

        $this->post('invite', ['email' => 'example@example.com']);


        //Assert
        $this->withoutExceptionHandling();
        Mail::assertSent(Invitation::class, function ($mail) {
            return $mail->hasTo('example@example.com');
        });

        $this->assertDatabaseHas('invites', ['email' => 'example@example.com']);
    }
}
