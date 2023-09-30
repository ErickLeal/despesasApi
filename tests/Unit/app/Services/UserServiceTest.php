<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositorys\UserRepository;
use App\Services\UserService;

use PHPUnit\Framework\TestCase;
use Illuminate\Validation\ValidationException;

class UserServiceTest extends TestCase
{
    public function test_singin_user_should_throw_exception_with_incorrect_email()
    {
        $this->expectException(ValidationException::class);

         $mockUserRepository = $this->getMockBuilder(UserRepository::class)
         ->disableOriginalConstructor()
         ->getMock();

        $mockUserRepository->expects($this->once()) 
         ->method('getUserByEmail') 
         ->willReturn(new User());

        $userService = new UserService($mockUserRepository);

        $userService->login('email@email.com','123');
        dd("teste");
    }

   
}
