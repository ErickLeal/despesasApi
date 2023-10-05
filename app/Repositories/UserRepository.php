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

    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById(int $id)
    {
        return User::where('id', $id)->first();
    }

    public function deleteUser(User $user)
    {
       $user->delete();
    }

    public function updateUser(User $user, array $data)
    {
        if(isset($data['password'])){
            $user->password = $data['password'];
        }
        if(isset($data['name'])){
            $user->name = $data['name'];
        }

        $user->save();

        return $user;
    }


}
