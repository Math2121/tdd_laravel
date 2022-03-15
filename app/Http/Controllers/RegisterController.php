<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    //
    public function __invoke()
    {
        request()->validate([
            'name' => ['required','max:255','string'],
            'email' => ['required','email','unique:users'],
            'password' => ['required','string','min:8', Password::min(8)->mixedCase()]
        ]);
        /**@var User $user */
        $user = User::query()->create([
            'name' => request()->name,
            'email' => request()->email,
            'password' => bcrypt(request()->password)
        ]);
        auth()->login($user);
        return redirect('dashboard', 201);
    }
}
