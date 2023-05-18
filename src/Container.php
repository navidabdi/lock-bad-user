<?php

declare(strict_types=1);

namespace Inpsyde\UserTableShowcase;

use Exception;
use ReflectionParameter;

class Container implements ContainerInterface
{
  /**
   * Holds the registered bindings.
   */
    private array $bindings = [];

  /**
   * Resolves and returns an instance of the given class or interface.
   *
   * @param string $abstract The class or interface name to resolve.
   * @return mixed The resolved instance.
   * @throws Exception If the class or interface cannot be resolved.
   */
    public function get(string $abstract): mixed
    {
        if ($this->hasBinding($abstract)) {
            $concrete = $this->bindings[$abstract];

            if (is_callable($concrete)) {
                return $concrete($this);
            }

            return $this->resolve($concrete);
        }

        throw new Exception("No binding found for $abstract");
    }

  /**
   * Binds an implementation to a given abstract.
   *
   * @param string $abstract The abstract (class or interface) to bind.
   * @param mixed $concrete The concrete implementation.
   * @return void
   */
    public function bind(string $abstract, mixed $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

  /**
   * Resolves an instance of the given class or interface.
   *
   * @param string $concrete The class or interface to resolve.
   * @return mixed The resolved instance.
   * @throws Exception If the class or interface cannot be resolved.
   */
    private function resolve(string $concrete): mixed
    {
        $reflector = new \ReflectionClass($concrete);

        if (!$reflector->isInstantiable()) {
            throw new Exception("Cannot instantiate $concrete");
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return new $concrete();
        }

        $dependencies = $this->resolveDependencies($constructor->getParameters());

        return $reflector->newInstanceArgs($dependencies);
    }

  /**
   * Resolves the dependencies of a constructor.
   *
   * @param array $parameters The parameters of the constructor.
   * @return array The resolved dependencies.
   * @throws Exception If a dependency cannot be resolved.
   */
    private function resolveDependencies(array $parameters): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            if ($parameter->isOptional()) {
                $dependencies[] = $this->resolveOptionalDependency($parameter);
                continue;
            }

            $dependencies[] = $this->get($parameter->getType()->getName());
        }

        return $dependencies;
    }

  /**
   * Resolves an optional dependency.
   *
   * @param ReflectionParameter $parameter The parameter.
   * @return mixed The resolved dependency, or the default value if provided.
   * @throws Exception If the dependency cannot be resolved and has no default value.
   */
    private function resolveOptionalDependency(ReflectionParameter $parameter): mixed
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        if ($parameter->allowsNull()) {
            return null;
        }

        throw new Exception("Cannot resolve optional dependency: {$parameter->getName()}");
    }

  /**
   * Checks if a binding exists for the given abstract.
   *
   * @param string $abstract The abstract (class or interface) to check.
   * @return bool True if a binding exists, false otherwise.
   */
    private function hasBinding(string $abstract): bool
    {
        return isset($this->bindings[$abstract]);
    }
}
