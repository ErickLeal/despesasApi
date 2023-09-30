<?php

//namespace
namespace App\Repositorys;

use App\Models\User;

class UserRepository
{

    public function getUserByEmail(String $email): User
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
