<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('controllers'), RunTestsInSeparateProcesses]
class LoginControllerTest extends TestCase
{
    #[Test]
    public function pageLoads(): void
    {
        $response = $this->get('login');

        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertStringContainsString('Email', (string) $response->getBody());
        $this->assertStringContainsString('Password', (string) $response->getBody());
    }

    #[Test]
    public function cannotLoginWithInvalidCredentials(): void
    {
        $response = $this->post('login', [
            'email' => 'bad@example.com',
            'password' => 'password',
        ]);

        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertStringContainsString('Invalid Credentials', (string) $response->getBody());
    }

    #[Test]
    public function canLoginWithValidCredentials(): void
    {
        $this->withTransactions(function () {
            $user = $this->createUser();
            $response = $this->post('login', [
                'email' => $user->email,
                'password' => 'password',       // can't use $user->password here as it's hashed
            ]);

            $this->assertEquals(StatusCodeInterface::STATUS_FOUND, $response->getStatusCode());
            $this->assertEquals([$this->urlFor('home')], $response->getHeader('Location'));
        });
    }
}
