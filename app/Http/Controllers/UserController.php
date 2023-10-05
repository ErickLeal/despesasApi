<?php

namespace App\Http\Controllers;

use App\Http\Requests\SingInUserRequest;
use App\Http\Requests\SingUpUserRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;

use App\Exceptions\user\CredentialsIncorrectException;
use App\Exceptions\user\UserNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function singUp(SingUpUserRequest $request)
    {

        $data = $request->validated();
        try {

            $user = $this->userService->create(
                $data['email'],
                $data['password'],
                $data['name']
            );
        } catch (UniqueConstraintViolationException $e) {

            return response()->json([
                'message' => 'User already registered'
            ], 409);
        } catch (Exception $e) {

            return response()->json([
                'message' => 'Unexpected error',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 200);
    }

    public function singIn(SingInUserRequest $request)
    {

        $data = $request->validated();
        try {
            $user = $this->userService->login(
                $data['email'],
                $data['password']
            );
        } catch (CredentialsIncorrectException $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        } catch (Exception $e) {

            return response()->json([
                'message' => 'Unexpected error',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Success',
            'user' => $user,
        ], 200);
    }

    public function index()
    {
        try {
            $users = $this->userService->getAllUsers();
        } catch (Exception $e) {

            return response()->json([
                'message' => 'Unexpected error',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Success',
            'users' => $users,
        ], 200);
    }

    public function show(string $id)
    {
        try {

            $user = $this->userService->getOneUser(intval($id));
        } catch (UserNotFoundException $e) {

            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Success',
            'user' => $user,
        ], 200);
    }

    public function destroy(string $id)
    {
        try {

            $this->userService->delete(intval($id));
        } catch (UserNotFoundException $e) {

            return response()->json([
                'message' => 'user not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Success'
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->all();
        try {
            $user = $this->userService->update($data, intval($id));
        } catch (UserNotFoundException $e) {

            return response()->json([
                'message' => 'User not found'
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'message' => 'Unexpected error',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Successfully updated user',
            'user' => $user,
        ], 200);
    }
}
