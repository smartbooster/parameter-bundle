<?php

namespace Smart\ParameterBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 */
class SmartParameterExtension extends Extension
{
    /**
     * @param array<array> $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');

        $parameterLoader = $container->getDefinition('smart_parameter.parameter_loader');
        if (isset($config['parameters']) && is_array($config['parameters'])) {
            foreach ($config['parameters'] as $code => $data) {
                $parameterLoader->addMethodCall('addParameter', [$code, $data]);
            }
        }
    }
}
