<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_should_be_able_create_a_new_user()
    {
        //Arrange

        $response = $this->post(route('register'), [
            'name' => 'Matheus',
            'email' => 'matheus@gmail.com',
            'email_confirmation' => 'matheus@gmail.com',
            'password' => '1223'
        ]);

        //Assert

        $this->withoutExceptionHandling();

        $this->assertDatabaseHas('users', [
            'name' => 'Matheus',
            'email' => 'matheus@gmail.com',
        ]);


        /**@var User $user */

        $user = User::where('email', 'matheus@gmail.com')->first();

        $this->assertTrue(
            Hash::check('1223', $user->password),
            'Checking if password was saved'
        );
    }

    public function test_name_should_be_required()
    {
        $this->post(route('register'), [])->assertSessionHasErrors(['name' => __('validation.required', ['attribute' => 'name'])]);
    }
    public function test_name_should_have_a_max_of_255_characters()
    {
        $this->post(route('register'), [
            'name' => str_repeat('a', 256)
        ])->assertSessionHasErrors(['name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255])]);
    }

    public function test_email_should_be_required()
    {
        $this->post(route('register'), [])->assertSessionHasErrors(['email' => __('validation.required', ['attribute' => 'email'])]);
    }

    public function test_should_be_a_valid_email()
    {
        $this->post(route('register'), ['email' => 'lalala'])->assertSessionHasErrors(['email' => __('validation.email', ['attribute' => 'email'])]);
    }

    public function test_email_should_be_unique()
    {
        //Arrange

        User::factory()->create(['email' => 'johndoe@gmail.com']);

        //Act
        $this->post(route('register'), [
            'email' => 'johndoe@gmail.com'
        ])->assertSessionHasErrors([ //assert
            'email' => __('validation.unique', ['attribute' => 'email'])
        ]);
    }

    public function test_password_should_be_required()
    {
        $this->post(route('register'), [])->assertSessionHasErrors(['password' => __('validation.required', ['attribute' => 'password'])]);
    }

    public function test_should_have_at_least_1_uppercase()
    {
        $this->post(route('register'), ['password' => 'password_without-uppercase'])->assertSessionHasErrors(['password' => 'The password must contain at least one uppercase and one lowercase letter.']);
    }
}
