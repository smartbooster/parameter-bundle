<?php

namespace Smart\ParameterBundle\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Smart\ParameterBundle\Entity\Parameter;
use Smart\ParameterBundle\Repository\ParameterRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 */
abstract class AbstractWebTestCase extends WebTestCase
{
    use FixturesTrait;

    private ?EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->entityManager = self::$container->get(EntityManagerInterface::class);

        // Empty load to guarantee that the base will always be available
        $this->loadFixtureFiles([]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // avoid memory leaks
        if ($this->entityManager != null) {
            $this->entityManager->close();
            $this->entityManager = null;
        }
    }

    protected function getFixtureDir(): string
    {
        return __DIR__ . '/fixtures';
    }

    protected function getParameterRepository(): ParameterRepository
    {
        // @phpstan-ignore-next-line
        return $this->entityManager->getRepository(Parameter::class);
    }
}
