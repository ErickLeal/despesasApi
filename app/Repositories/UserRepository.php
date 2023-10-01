<?php

//namespace
namespace App\Repositories;

use App\Models\User;

class UserRepository
{

    public function getUserByEmail(String $email)
    {
        return User::where('email', $email)->first();
    }

    public function createUser(String $email, String $password, string $name): User
    {
        return User::create([
            'name' => $name,
            'password' => $password,
            'email' => $email,
        ]);
    }

}
