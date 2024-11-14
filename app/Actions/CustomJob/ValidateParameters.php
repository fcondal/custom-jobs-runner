<?php

namespace App\Actions\CustomJob;

use App\Models\CustomJob;
use Exception;
use Illuminate\Support\Str;
use ReflectionException;
use ReflectionMethod;

class ValidateParameters
{
    private const VALID_NAMESPACE = 'App\CustomJobs';
    private string $className;
    private string $method;
    private array  $parameters;
    private int    $delay;
    private string $priority;

    public function __construct(CustomJob $customJob)
    {
        $this->className  = $customJob->class_name;
        $this->method     = $customJob->method;
        $this->parameters = $customJob->parameters;
        $this->delay      = $customJob->delay;
        $this->priority   = $customJob->priority;
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        if (!$this->hasAValidClassName()) {
            throw new Exception("Class $this->className is invalid.");
        }

        if (!$this->hasAValidMethod()) {
            throw new Exception("Method $this->method does not exist in class $this->className.");
        }

        if (!$this->hasValidParameters()) {
            throw new Exception("Invalid parameters for method $this->method.");
        }
    }

    private function hasAValidClassName(): bool
    {
        if (!class_exists($this->className)) {
            return false;
        }

        return $this->hasAValidNamespace();
    }

    private function hasAValidNamespace(): bool
    {
        $delimiter = '\\';
        $className = Str::of($this->className)->explode($delimiter);
        $className->pop();
        $namespace = $className->implode($delimiter);

        return $namespace === self::VALID_NAMESPACE;
    }

    private function hasAValidMethod(): bool
    {
        return method_exists($this->className, $this->method);
    }

    /**
     * @throws ReflectionException
     */
    private function hasValidParameters(): bool
    {
        $object = new $this->className();

        $reflection = new ReflectionMethod($object, $this->method);
        $parameters = $reflection->getParameters();

        if (count($parameters) !== count($this->parameters)) {
            return false;
        }

        foreach ($parameters as $index => $parameter) {
            if (!$parameter->isOptional() && !array_key_exists($index, $this->parameters)) {
                return false;
            }
        }

        return true;
    }
}