<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Repository\Contracts\UserRepositoryInterface;

class UserControllerTest extends TestCase
{

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_service_getUserById_returns_array_when_found(): void
    {
        $userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryMock->shouldReceive('getUserById')->once()->with(1)->andReturn([
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.test'
        ]);

        $this->app->instance(UserRepositoryInterface::class, $userRepositoryMock);
        $service = $this->app->make(\App\Services\UserService::class);
        $result = $service->getUserById(1);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('John Doe', $result['name']);
        $this->assertEquals('john@example.test', $result['email']);
    }

    public function test_service_getUserById_throws_when_not_found(): void
    {
        $this->expectException(\App\Exceptions\ResourceNotFoundException::class);

        $userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryMock->shouldReceive('getUserById')->once()->with(1)->andReturn([]);

        $this->app->instance(UserRepositoryInterface::class, $userRepositoryMock);
        $service = $this->app->make(\App\Services\UserService::class);

        $service->getUserById(1); 
    }

    public function test_service_insertUser_returns_array_on_success(): void
    {
        $userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryMock->shouldReceive('getUserByEmail')->once()->with('jane@example.test')->andReturn([]);
        $userRepositoryMock->shouldReceive('insertUser')->once()->with([
            'name' => 'jane doe',
            'email' => 'jane@example.test',
            'password' => 'securepassword'
        ])->andReturn([
            'id' => 1,
            'name' => 'jane doe',
            'email' => 'jane@example.test'
        ]);

        $this->app->instance(UserRepositoryInterface::class, $userRepositoryMock);
        $service = $this->app->make(\App\Services\UserService::class);
        $result = $service->insertUser([
            'name' => 'jane doe',
            'email' => 'jane@example.test',
            'password' => 'securepassword'
        ]);

        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('jane doe', $result['user']);
        $this->assertEquals('jane@example.test', $result['email']);
    }

    public function test_service_insertUser_throws_when_email_exists(): void
    {
        $this->expectException(\App\Exceptions\DuplicatedValueException::class);

        $userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryMock->shouldReceive('getUserByEmail')->once()->with('jane@example.test')->andReturn([
            'id' => 1,
            'name' => 'jane doe',
            'email' => 'jane@example.test'
        ]);

        $this->app->instance(UserRepositoryInterface::class, $userRepositoryMock);
        $service = $this->app->make(\App\Services\UserService::class);

        $service->insertUser([
            'name' => 'jane doe',
            'email' => 'jane@example.test',
            'password' => 'securepassword'
        ]);
    }

    public function test_service_updateUser_returns_array_on_success(): void 
    {
        $userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryMock->shouldReceive('getUserById')->once()->with(1)->andReturn([
            'id' => 1,
            'name' => 'jane',
            'email' => 'jane@example.test'
        ]);
        
        $userRepositoryMock->shouldReceive('updateUser')->once()->with(1, 'jane doe', 'jane@example.test')->andReturn(true);

        $this->app->instance(UserRepositoryInterface::class, $userRepositoryMock);
        $service = $this->app->make(\App\Services\UserService::class);
        $result = $service->updateUser([
            'id' => 1,
            'name' => 'jane doe',
            'email' => 'jane@example.test',
            'password' => 'newpassword'
        ]);

        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('jane doe', $result['name']);
        $this->assertEquals('jane@example.test', $result['email']);
    }

    public function test_service_updateUser_throws_when_not_found(): void
    {
        $this->expectException(\App\Exceptions\ResourceNotFoundException::class);

        $userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryMock->shouldReceive('getUserById')->once()->with(1)->andReturn([]);

        $this->app->instance(UserRepositoryInterface::class, $userRepositoryMock);
        $service = $this->app->make(\App\Services\UserService::class);

        $service->updateUser([
            'id' => 1,
            'name' => 'jane doe',
            'email' => 'jane@example.test',
            'password' => 'newpassword'
        ]);
    }

    public function test_service_updateUser_throws_when_new_email_already_in_use(): void 
    {
        $this->expectException(\App\Exceptions\DuplicatedValueException::class);

        $userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryMock->shouldReceive('getUserById')->once()->with(1)->andReturn([
            'id' => 1,
            'name' => 'jane doe',
            'email' => 'jane999999@example.test'
        ]);
        $userRepositoryMock->shouldReceive('verifyNewEmailIsAvailable')->once()->with('jane999999@example.test', 'jane@example.test', 1)->andReturn([
            'id' => 5,
            'name' => 'jane doe hughes',
            'email' => 'jane@example.test'
        ]);

        $this->app->instance(UserRepositoryInterface::class, $userRepositoryMock);
        $service = $this->app->make(\App\Services\UserService::class);

        $service->updateUser([
            'id' => 1,
            'name' => 'jane doe',
            'email' => 'jane@example.test',
            'password' => 'newpassword'
        ]);
    }

    public function test_deleteUserById_returns_true_on_success(): void
    {
        $userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryMock->shouldReceive('getUserById')->once()->with(1)->andReturn([
            'id' => 1,
            'name' => 'jane doe',
            'email' => 'jane@example.test'
        ]);
        $userRepositoryMock->shouldReceive('deleteUserById')->once()->with(1)->andReturn(true);

        $this->app->instance(UserRepositoryInterface::class, $userRepositoryMock);
        $service = $this->app->make(\App\Services\UserService::class);

        $result = $service->deleteUserById(1);
        $this->assertTrue($result);
    }
}