<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Classes\Database;
use App\Repositories\UserRepository;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Slim\App;
use Tests\TestCase;

#[Group('repositories')]
class UserRepositoryTest extends TestCase
{
    #[Test]
    public function canCreateUser(): void
    {
        $this->expectNotToPerformAssertions();

        $app = $this->getAppInstance();
        $db = $app->getContainer()->get(Database::class);
        $userRepository = $app->getContainer()->get(UserRepository::class);

        $db->beginTransaction();
        $userRepository->createUser(name: 'test', email: 'test@example.com', password: 'password');
        $db->rollBack();
    }

    #[Test]
    public function canGetUserById(): void
    {
        $app = $this->getAppInstance();
        $db = $app->getContainer()->get(Database::class);
        $userRepository = $app->getContainer()->get(UserRepository::class);

        $db->beginTransaction();
        $id = $userRepository->createUser(name: 'test', email: 'test@example.com', password: 'password');
        $user = $userRepository->getById($id);
        $db->rollBack();

        $this->assertIsObject($user);
        $this->assertEquals($id, $user->id);
    }

    #[Test]
    public function canGetUserByEmail(): void
    {
        $app = $this->getAppInstance();
        $db = $app->getContainer()->get(Database::class);
        $userRepository = $app->getContainer()->get(UserRepository::class);

        $db->beginTransaction();
        $id = $userRepository->createUser(name: 'test', email: 'test@example.com', password: 'password');
        $user = $userRepository->getByEmail('test@example.com');
        $db->rollBack();

        $this->assertIsObject($user);
        $this->assertEquals($id, $user->id);
    }
}
