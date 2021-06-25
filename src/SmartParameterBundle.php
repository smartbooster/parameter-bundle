<?php

namespace Smart\ParameterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 */
class SmartParameterBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
