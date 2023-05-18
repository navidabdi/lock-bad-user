<?php

declare(strict_types=1);

namespace Inpsyde\UserTableShowcase;

use Exception;

interface ContainerInterface
{
  /**
   * Resolves and returns an instance of the given class or interface.
   *
   * @param string $abstract The class or interface name to resolve.
   * @return mixed The resolved instance.
   * @throws Exception If the class or interface cannot be resolved.
   */
    public function get(string $abstract): mixed;

  /**
   * Binds an implementation to a given abstract.
   *
   * @param string $abstract The abstract (class or interface) to bind.
   * @param mixed $concrete The concrete implementation.
   * @return void
   */
    public function bind(string $abstract, mixed $concrete): void;
}
