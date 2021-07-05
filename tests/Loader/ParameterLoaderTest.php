<?php

namespace Smart\ParameterBundle\Tests\Loader;

use Smart\ParameterBundle\Entity\Parameter;
use Smart\ParameterBundle\Loader\ParameterLoader;
use Smart\ParameterBundle\Tests\AbstractWebTestCase;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 *
 * vendor/bin/phpunit tests/Loader/ParameterLoaderTest.php
 */
class ParameterLoaderTest extends AbstractWebTestCase
{
    private ?ParameterLoader $loader;

    public function setUp(): void
    {
        parent::setUp();

        $this->loader = self::$container->get(ParameterLoader::class);
    }

    public function testUpdate(): void
    {
        $this->loadFixtureFiles([$this->getFixtureDir() . "/Loader/ParameterLoader/parameter_to_update.yaml"]);

        // Assert data before loading
        $parameterWithHelp = $this->getParameterRepository()->findOneBy(['code' => "dummy_support_emails"]);
        $this->assertSame("Help value before load", $parameterWithHelp->getHelp());
        $this->assertSame("email-value-wont-change-after-load@test.com", $parameterWithHelp->getValue());
        $parameterWithoutHelp = $this->getParameterRepository()->findOneBy(['code' => "dummy_homepage_cache_duration"]);
        $this->assertNull($parameterWithoutHelp->getHelp());

        $this->loader->load();

        // Test that only the help attribut has been updated by the loader
        $this->assertSame(
            "This parameter is used by the mailer for support recipients. Format : Separate email by comma.",
            $parameterWithHelp->getHelp()
        );
        $this->assertSame("email-value-wont-change-after-load@test.com", $parameterWithHelp->getValue());
        $this->assertSame(
            "This parameter is used by the backend to set the duration of cache applied to the Homepage. Set the duration in second.",
            $parameterWithoutHelp->getHelp()
        );
    }

    public function testInsert(): void
    {
        // check parameter number before loading
        $this->assertSame(0, $this->getParameterRepository()->count([]));

        $this->loader->load();

        // check setting data on insert
        $parameter = $this->getParameterRepository()->findOneBy(['code' => "dummy_support_emails"]);
        $this->assertSame(
            "This parameter is used by the mailer for support recipients. Format : Separate email by comma.",
            $parameter->getHelp()
        );
        $this->assertSame("support@example.com, technical-support@example.com", $parameter->getValue());
        $this->assertSame("dummy_support_emails", $parameter->getCode());

        // check increase parameter number after loading
        $this->assertSame(2, $this->getParameterRepository()->count([]));
    }

    public function testDelete(): void
    {
        $this->loadFixtureFiles([$this->getFixtureDir() . "/Loader/ParameterLoader/parameter_to_delete.yaml"]);

        // check that the parameter exist in database before loading
        $this->assertInstanceOf(Parameter::class, $this->getParameterRepository()->findOneBy(['code' => 'param_to_delete']));

        $this->loader->load();

        // check that has been deleted
        $this->assertNull($this->getParameterRepository()->findOneBy(['code' => 'param_to_delete']));
    }
}
