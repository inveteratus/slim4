<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('controllers')]
class HomeControllerTest extends TestCase
{
    #[Test]
    public function guestRedirectsToLogin(): void
    {
        $app = $this->getAppInstance();
        $routeParser = $app->getRouteCollector()->getRouteParser();
        $response = $app->handle($this->get($routeParser->urlFor('home')));

        $this->assertEquals(StatusCodeInterface::STATUS_FOUND, $response->getStatusCode());
        $this->assertEquals([$routeParser->urlFor('login')], $response->getHeader('Location'));
    }
}
