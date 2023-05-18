<?php

declare(strict_types=1);

namespace Inpsyde\UserTableShowcase;

class UserTableShowcase
{
  /**
   * Plugin constructor.
   *
   * @param ContainerInterface $container The container instance.
   */
    public function __construct(private ContainerInterface $container)
    {
        $this->container = $container;
    }

  /**
   * Initialize the plugin.
   */
    public function init(): void
    {
      // Register plugin hooks and actions here
    }
}
