<?php

namespace Smart\ParameterBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Smart\ParameterBundle\SmartParameterBundle;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\FileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 *
 * vendor/bin/phpunit tests/DependencyInjection/DependencyInjectionTest.php
 */
class DependencyInjectionTest extends TestCase
{
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $bundle = new SmartParameterBundle();
        $this->container = new ContainerBuilder();

        $this->container->setParameter('kernel.debug', true);
        $this->container->setParameter('kernel.bundles', [
            'FrameworkBundle' => \Symfony\Bundle\FrameworkBundle\FrameworkBundle::class,
        ]);
        $this->container->setParameter('kernel.environment', 'test');

        $this->container->registerExtension($bundle->getContainerExtension());
        $bundle->build($this->container);
    }

    /**
     * @dataProvider invalidConfigurationProvider
     */
    public function testInvalidConfigurationParsing(string $resource, string $message): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage($message);

        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ . '/../fixtures/config/'));
        $loader->load($resource . ".yml");
        $this->container->compile();
    }

    public function invalidConfigurationProvider(): array
    {
        return [
            'invalid_no_parameter_defined' => [
                'ressource' => 'invalid_no_parameter_defined',
                'message' => 'The path "smart_parameter.parameters" should have at least 1 element(s) defined.',
            ],
            'invalid_missing_parameter_value' => [
                'ressource' => 'invalid_missing_parameter_value',
                'message' => 'The child node "value" at path "smart_parameter.parameters.parameter_without_value" must be configured.',
            ],
        ];
    }

    /**
     * @dataProvider configurationProvider
     */
    public function testValidConfigurationParsing(string $resource): void
    {
        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ . '/../fixtures/config/'));
        $loader->load($resource . ".yml");
        $this->container->compile();
        // This assertion is already true with the setUp but it valid the fact that the container did compile with the given configuration
        $this->assertNotNull($this->container->getExtension("smart_parameter"));
    }

    public function configurationProvider(): array
    {
        return [
            'full' => ['full'],
            'minimal' => ['minimal'],
            'none' => ['none'],
        ];
    }
}
