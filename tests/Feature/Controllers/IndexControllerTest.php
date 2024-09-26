<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('controllers'), RunTestsInSeparateProcesses]
class IndexControllerTest extends TestCase
{
    #[Test]
    public function pageLoads(): void
    {
        $response = $this->get('index');

        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertStringContainsString('Login', (string) $response->getBody());
        $this->assertStringContainsString('Register', (string) $response->getBody());
    }
}
