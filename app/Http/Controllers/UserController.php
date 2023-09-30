<?php

namespace App\Http\Controllers;

use App\Http\Requests\SingInUserRequest;
use App\Http\Requests\SingUpUserRequest;
use App\Services\UserService;
use Exception;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\UniqueConstraintViolationException;

class UserController extends Controller
{
    public $userService;

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
        } catch (ValidationException $e) {

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
}
