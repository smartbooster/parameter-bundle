<?php

namespace Smart\ParameterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 *
 * @ORM\Table(name="smart_parameter")
 * @ORM\Entity(repositoryClass="Smart\ParameterBundle\Repository\ParameterRepository")
 */
class Parameter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(name="code", type="string", nullable=false, unique=true)
     */
    private string $code;

    /**
     * @ORM\Column(name="value", type="text", nullable=false)
     */
    private string $value;

    /**
     * @ORM\Column(name="help", type="text", nullable=true)
     */
    private ?string $help;

    public function __toString()
    {
        return (string) $this->getCode();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string|null
     */
    public function getHelp(): ?string
    {
        return $this->help;
    }

    /**
     * @param string|null $help
     */
    public function setHelp(?string $help): void
    {
        $this->help = $help;
    }
}
