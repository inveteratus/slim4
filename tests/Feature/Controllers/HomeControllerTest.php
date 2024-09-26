<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('controllers'), RunTestsInSeparateProcesses]
class HomeControllerTest extends TestCase
{
    #[Test]
    public function guestRedirectsToLogin(): void
    {
        $response = $this->get('home');

        $this->assertEquals(StatusCodeInterface::STATUS_FOUND, $response->getStatusCode());
        $this->assertEquals([$this->urlFor('login')], $response->getHeader('Location'));
    }
}
