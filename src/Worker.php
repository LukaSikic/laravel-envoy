<?php

namespace Luka\Envoy;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Worker
{
    /**
     * Task to execute
     * @var
     */
    public $task;

    /**
     * Task arguments
     * @var
     */
    public $arguments;

    /**
     * Execute the command and get output
     *
     * @return null|string|string[]
     * @throws \Exception
     */
    public function run()
    {
        $command = config('envoy.path') . " run " . $this->task . ' ' . $this->arguments;

        $process = new Process($command);
        $process->setTimeout(config('envoy.timeout'));
        $process->setIdleTimeout(config('envoy.idle_timeout'));
        $process->setWorkingDirectory(config('envoy.directory'));

        if(config('envoy.force_output') == false) {
            try {
                $process->mustRun();
                $output = $process->getOutput();
                return preg_replace('/\[.*?\]\:\ /', '', $output);
            } catch (ProcessFailedException $e) {
                throw new \Exception($e->getMessage());
            }
        } else {
            $result = [];

            $process->run(
                function ($type, $buffer) use (&$result) {
                    foreach(config('envoy.clear_output') as $rule) {
                        $buffer = preg_replace($rule, '', $buffer);
                    }

                    $result[] = $buffer;
                }
            );

            return implode(' ', $result);
        }
    }

    /**
     * Set task name
     *
     * @param $name
     * @return $this
     */
    public function task($name)
    {
        if (empty($name)) return $this;
        $this->task = $name;
        return $this;
    }

    /**
     * Set task arguments
     *
     * @param array $arguments
     * @return $this
     */
    public function arguments($arguments)
    {
        if(count($arguments) > 0) {
            foreach ($arguments as $key => $value) {
                $args[] = ' --' . $key . '="' . $value . '" ';
            }

            $this->arguments = implode(' ', $args);
        }

        return $this;
    }

}
