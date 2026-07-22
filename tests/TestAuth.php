<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;
    private $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->authService = new AuthService($this->authRepository);

        $this->connection->method('connect')->willReturn($this->connection);
        $this->connection->method('fetchAll')->willReturn([
            ['id' => 1, 'username' => 'test', 'password' => 'hashed_password'],
        ]);

        $this->authRepository->method('getUserByUsername')->willReturn(new User(1, 'test', 'hashed_password'));
    }

    public function testLoginSuccess()
    {
        $username = 'test';
        $password = 'hashed_password';

        $this->authRepository->method('getUserByUsername')->with($username)->willReturn(new User(1, $username, $password));

        $result = $this->authService->login($username, $password);

        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $username = 'test';
        $password = 'wrong_password';

        $this->authRepository->method('getUserByUsername')->with($username)->willReturn(null);

        $result = $this->authService->login($username, $password);

        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $username = 'new_user';
        $password = 'hashed_password';

        $this->connection->method('insert')->with('users', ['username' => $username, 'password' => $password])->willReturn(1);

        $result = $this->authService->register($username, $password);

        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $username = 'new_user';
        $password = 'wrong_password';

        $this->connection->method('insert')->with('users', ['username' => $username, 'password' => $password])->willReturn(0);

        $result = $this->authService->register($username, $password);

        $this->assertFalse($result);
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that the `login` method returns `true` when the provided username and password match a user in the database.
- `testLoginFailure`: Tests that the `login` method returns `false` when the provided username and password do not match a user in the database.
- `testRegisterSuccess`: Tests that the `register` method returns `true` when a new user is successfully created in the database.
- `testRegisterFailure`: Tests that the `register` method returns `false` when a new user cannot be created in the database.