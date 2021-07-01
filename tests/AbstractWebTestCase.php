<?php

namespace Smart\ParameterBundle\Tests;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 */
abstract class AbstractWebTestCase extends WebTestCase
{
    use FixturesTrait;

    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        // Empty load to guarantee that the base will always be available
        $this->loadFixtureFiles([]);
    }

    protected function getFixtureDir(): string
    {
        return __DIR__ . '/fixtures';
    }
}
