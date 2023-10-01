<?php

//namespace
namespace App\Services;

use App\Http\Resources\UserCollection;
use App\Repositorys\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\user\CredentialsIncorrectException;

class UserService
{

    public $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function create(String $email, String $password, string $name): UserCollection
    {
        $user = $this->userRepository->createUser($email, $password, $name);

        return new UserCollection($user);
    }

    public function login(String $email, String $password): UserCollection
    {
        $user = $this->userRepository->getUserByEmail($email);

        $incorrectCredentials = !$user || !Hash::check($password, $user->password);
     
        if ($incorrectCredentials) {
            throw new CredentialsIncorrectException('The provided credentials are incorrect.');
        }

        return new UserCollection($user);
    }
}
