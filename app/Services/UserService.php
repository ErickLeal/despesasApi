<?php

//namespace
namespace App\Services;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\user\CredentialsIncorrectException;
use App\Exceptions\user\UserNotFoundException;

class UserService
{

    public $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function create(String $email, String $password, string $name): UserResource
    {
        $user = $this->userRepository->createUser($email, $password, $name);

        return new UserResource($user);
    }

    public function login(String $email, String $password): UserResource
    {
        $user = $this->userRepository->getUserByEmail($email);

        $incorrectCredentials = !$user || !Hash::check($password, $user->password);
     
        if ($incorrectCredentials) {
            throw new CredentialsIncorrectException('The provided credentials are incorrect.');
        }

        return new UserResource($user);
    }

    public function getAllUsers(): UserCollection
    {
        $users = $this->userRepository->getAllUsers();
       
        return new UserCollection($users);
    }

    public function getOneUser(int $id): UserResource
    {

        $user = $this->getUser($id);

        return new UserResource($user);
    }

    public function delete(int $id)
    {
        $user = $this->getUser($id);

        $this->userRepository->deleteUser($user);
    }

    public function update(array $data, int $id): UserResource
    {
        $user = $this->getUser($id);

        $expense = $this->userRepository->updateUser($user, $data);
        
        return new UserResource($expense);
    }

    private function getUser(int $id)
    {
     
        $user = $this->userRepository->getUserById($id);
        
        if(!$user){
            throw new UserNotFoundException();
        }

        return $user;
    }


}
