<?php

namespace Smart\ParameterBundle\Tests\App;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 *
 * vendor/bin/phpunit tests/App/AppKernelTest.php
 */
class AppKernelTest extends WebTestCase
{
    protected static function getKernelClass()
    {
        return AppKernel::class;
    }

    public function testBootingKernel(): void
    {
        self::bootKernel();

        $this->assertInstanceOf(KernelInterface::class, self::$kernel);
    }
}
