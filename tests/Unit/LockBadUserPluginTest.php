<?php

declare(strict_types=1);

namespace Webkima\LockBadUser\Tests\Unit;

use Brain\Monkey\Functions;
use Webkima\LockBadUser\LockUser;

class LockBadUserPluginTest extends AbstractUnitTestcase
{
  protected function setUp(): void
  {
    parent::setUp();

    if (!function_exists('load_plugin_textdomain')) {
      Functions\when('load_plugin_textdomain')->justReturn(true);
      Functions\when('plugin_basename')->justReturn('inpsyde-plugin.php');
    }
  }

  /**
   * Tests if the plugin is instantiable as singleton.
   */
  public function testPluginIfInstantiableAsSingleton(): void
  {
    $expected = LockUser::instance();

    $this->assertInstanceOf(LockUser::class, $expected);
    $this->assertEquals($expected, LockUser::instance());
  }

  /**
   * Tests if the plugin is hooked to the plugins_loaded action.
   */
  public function testRegisterIfItAddsHooks()
  {
    (LockUser::instance())->register();

    self::assertNotFalse(
      has_action(
        'plugins_loaded',
        [LockUser::instance(), 'init']
      )
    );
  }
}