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
     * Force output and not return exception ?
     * @var
     */
    public $forceOutput;

    /**
     * Worker constructor.
     */
    public function __construct()
    {
        $this->forceOutput = config('envoy.force_output');
    }

    /**
     * Execute the command and get output
     *
     * @return null|string|string[]
     * @throws \Exception
     */
    public function run()
    {
        $command = [config('envoy.path'), "run", $this->task];

        foreach($this->arguments as $argument){
            array_push($command, $argument);
        }

        $process = new Process($command);
        $process->setTimeout(config('envoy.timeout'));
        $process->setIdleTimeout(config('envoy.idle_timeout'));
        $process->setWorkingDirectory(config('envoy.directory'));

        // todo: refactor this
        if($this->forceOutput == false) {
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
                $args[] = '--' . $key . '="' . $value . '" ';
            }

            $this->arguments = $args;
        }

        return $this;
    }

    /**
     * Set value for forcing output
     *
     * @param bool $value
     * @return $this
     */
    public function forceOutput( $value = true)
    {
        if($value == true){
            $this->forceOutput = true;
        } else {
            $this->forceOutput = false;
        }

        return $this;
    }

}
