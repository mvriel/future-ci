<?php

namespace F500\CI\Process;

use Symfony\Component\Process\ProcessBuilder;

class ProcessFactory
{

    /**
     * @var string
     */
    protected $builderClass;

    /**
     * @param string $builderClass
     */
    public function __construct($builderClass)
    {
        $this->builderClass = $builderClass;
    }

    /**
     * @param array  $args
     * @param string $cwd
     * @param array  $env
     * @param string $input
     * @param float  $timeout
     * @param array  $options
     * @return \Symfony\Component\Process\Process
     * @throws \RuntimeException
     */
    public function create(
        array $args,
        $cwd = null,
        $env = null,
        $input = null,
        $timeout = null,
        $options = null
    ) {
        $class = $this->builderClass;

        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf('Class "%s" does not exist.', $class));
        }

        $builder = new $class($args);

        if (!$builder instanceof ProcessBuilder) {
            throw new \RuntimeException(sprintf(
                'Class "%s" should be an instance of Symfony\Component\Process\ProcessBuilder.',
                $class
            ));
        }

        if ($cwd !== null) {
            $builder->setWorkingDirectory($cwd);
        }

        if ($env !== null) {
            foreach ($env as $name => $value) {
                $builder->setEnv($name, $value);
            }
        }

        if ($input !== null) {
            $builder->setInput($input);
        }

        if ($timeout !== null) {
            $builder->setTimeout($timeout);
        }

        if ($options !== null) {
            foreach ($options as $name => $value) {
                $builder->setOption($name, $value);
            }
        }

        return $builder->getProcess();
    }
}
