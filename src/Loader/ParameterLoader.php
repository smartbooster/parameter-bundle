<?php

namespace Smart\ParameterBundle\Loader;

use Doctrine\ORM\EntityManagerInterface;
use Smart\ParameterBundle\Entity\Parameter;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 */
class ParameterLoader
{
    const BATCH_SIZE = 20;

    private EntityManagerInterface $entityManager;
    private array $parameters = [];
    private array $logs = [
        "nb_updated" => 0,
        "nb_skipped" => 0,
        "nb_deleted" => 0,
        "nb_inserted" => 0,
    ];
    private bool $dryRun;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addParameter(string $code, array $data): void
    {
        $this->parameters[$code] = $data;
    }

    /**
     * if dryRun mode is set to true the changes are not flush in the database
     */
    public function load(bool $dryRun = false): array
    {
        $this->entityManager->getConnection()->beginTransaction();
        $this->dryRun = $dryRun;

        try {
            // @phpstan-ignore-next-line
            $existingParameters = $this->entityManager->getRepository(Parameter::class)->findExisting();
            $i = 1;
            foreach ($existingParameters as $code => $parameter) {
                $this->handleExistingParameters($code, $parameter, $i);
                ++$i;
            }

            $parameterToInsert = array_diff_key($this->parameters, $existingParameters);
            foreach ($parameterToInsert as $code => $data) {
                $this->handleParametersToInsert($code, $data, $i);
                ++$i;
            }

            if (!$this->dryRun) {
                $this->entityManager->flush();
                $this->entityManager->clear();
                $this->entityManager->getConnection()->commit();
            }
        // @codeCoverageIgnoreStart
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
        }
        // @codeCoverageIgnoreEnd

        return $this->logs;
    }

    protected function handleExistingParameters(string $code, Parameter $parameter, int $i): void
    {
        if (isset($this->parameters[$code])) {
            // update help for existing parameters
            if (isset($this->parameters[$code]['help']) && $parameter->getHelp() !== $this->parameters[$code]['help']) {
                $parameter->setHelp($this->parameters[$code]['help']);
                $this->entityManager->persist($parameter);
                $this->logs["nb_updated"]++;
            } else {
                $this->logs["nb_skipped"]++;
            }
        } else {
            $this->entityManager->remove($parameter);
            $this->logs["nb_deleted"]++;
        }

        if (($i % self::BATCH_SIZE) === 0 && !$this->dryRun) {
            $this->entityManager->flush();
            $this->entityManager->clear();
        }
    }

    protected function handleParametersToInsert(string $code, array $data, int $i): void
    {
        $parameter = new Parameter();
        $parameter->setCode($code);
        $parameter->setValue($data['value']);
        if (isset($data['help'])) {
            $parameter->setHelp($data['help']);
        }

        $this->entityManager->persist($parameter);
        $this->logs["nb_inserted"]++;

        if (($i % self::BATCH_SIZE) === 0 && !$this->dryRun) {
            $this->entityManager->flush();
            $this->entityManager->clear();
        }
    }
}
