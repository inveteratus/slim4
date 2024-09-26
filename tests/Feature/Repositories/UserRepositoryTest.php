<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Classes\Database;
use App\Repositories\UserRepository;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('repositories')]
class UserRepositoryTest extends TestCase
{
    #[Test]
    public function canCreateUser(): void
    {
        $this->withTransactions(function () {
            $this->expectNotToPerformAssertions();

            $userRepository = $this->getAppInstance()->getContainer()->get(UserRepository::class);
            $userRepository->createUser(name: 'test', email: 'test@example.com', password: 'password');
        });
    }

    #[Test]
    public function canGetUserById(): void
    {
        $this->withTransactions(function () {
            $userRepository = $this->getAppInstance()->getContainer()->get(UserRepository::class);
            $id = $this->createUser()->id;
            $user = $userRepository->getById($id);

            $this->assertIsObject($user);
            $this->assertEquals($id, $user->id);
        });
    }

    #[Test]
    public function canGetUserByEmail(): void
    {
        $this->withTransactions(function () {
            $userRepository = $this->getAppInstance()->getContainer()->get(UserRepository::class);
            $email = $this->createUser()->email;
            $user = $userRepository->getByEmail($email);

            $this->assertIsObject($user);
            $this->assertEquals($email, $user->email);
        });
    }
}
