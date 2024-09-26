<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Slim\App;
use Tests\TestCase;

#[Group('controllers'), RunTestsInSeparateProcesses]
class RegisterControllerTest extends TestCase
{
    #[Test]
    public function pageLoads(): void
    {
        $response = $this->get('register');

        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertStringContainsString('Name', (string) $response->getBody());
        $this->assertStringContainsString('Email', (string) $response->getBody());
        $this->assertStringContainsString('Password', (string) $response->getBody());
    }

    #[Test]
    public function canRegisterNewUser(): void
    {
        $this->withTransactions(function () {
            $response = $this->post('register', [
                'name' => 'test',
                'email' => 'test@example.com',
                'password' => 'password',
            ]);

            $this->assertEquals(StatusCodeInterface::STATUS_FOUND, $response->getStatusCode());
            $this->assertEquals([$this->urlFor('home')], $response->getHeader('Location'));
        });
    }

    #[Test]
    public function cannotRegisterExistingUser(): void
    {
        $this->withTransactions(function () {
            $user = $this->createUser();
            $response = $this->post('register', [
                'name' => $user->name,
                'email' => $user->email,
                'password' => 'password',
            ]);

            $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
            $this->assertStringContainsString('Name must not exist.', (string) $response->getBody());
            $this->assertStringContainsString('Email must not exist.', (string) $response->getBody());
        });
    }
}
