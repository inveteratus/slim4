<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('controllers')]
class LoginControllerTest extends TestCase
{
    #[Test]
    public function pageLoads(): void
    {
        $app = $this->getAppInstance();
        $routeParser = $app->getRouteCollector()->getRouteParser();
        $response = $app->handle($this->get($routeParser->urlFor('login')));

        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }

    #[Test]
    public function cannotLoginWithInvalidCredentials(): void
    {
        $app = $this->getAppInstance();
        $routeParser = $app->getRouteCollector()->getRouteParser();
        $csrf = $app->getContainer()->get('csrf')->generateToken();
        $response = $app->handle($this->post($routeParser->urlFor('login'), [
            'email' => 'bad@example.com',
            'password' => 'password',
            'csrf_name' => $csrf['csrf_name'],
            'csrf_value' => $csrf['csrf_value'],
        ]));

        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertStringContainsString('Invalid Credentials', (string) $response->getBody());
    }
}
