<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Slim\App;
use Tests\TestCase;

#[Group('controllers')]
class RegisterControllerTest extends TestCase
{
    #[Test]
    public function pageLoads(): void
    {
        $app = $this->getAppInstance();
        $routeParser = $app->getRouteCollector()->getRouteParser();
        $response = $app->handle($this->get($routeParser->urlFor('register')));

        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }
}
