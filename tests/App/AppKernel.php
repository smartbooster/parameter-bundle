<?php

namespace Smart\ParameterBundle\Tests\App;

use Smart\ParameterBundle\SmartParameterBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 */
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new SmartParameterBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/../fixtures/config/full.yml');
    }
}
