<?php

declare(strict_types=1);

namespace Webkima\LockBadUser;

class Schema
{
  /**
   * Activate the plugin schema.
   */
  public static function activate(): void
  {
  }

  /**
   * Deactivate the plugin schema.
   *
   * This method is called when the plugin is deactivated.
   * It flushes the rewrite rules.
   */
  public static function deactivate(): void
  {
  }

  /**
   * Uninstall the plugin schema.
   *
   * This method is called when the plugin is uninstalled.
   * It performs any necessary cleanup or data removal operations.
   */
  public static function uninstall(): void
  {
    // Perform uninstallation tasks, if any.
  }
}