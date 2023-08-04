<?php

declare(strict_types=1);

namespace Webkima\LockBadUser;

use WP_Error;
use WP_User;

class LockUser
{
    public function register(): void
    {
        $this->initHook();
    }

    public function initHook(): void
    {
        add_action('edit_user_profile', [$this, 'outputLockStatusOptions']);
        add_action('edit_user_profile_update', [$this, 'saveLockStatusOption']);
        add_filter('authenticate', [$this, 'userAuthentication'], 9999);
        add_filter('allow_password_reset', [$this, 'allowPasswordReset'], 9999, 2);
    }

    private function isUserLocked(string $userId): bool
    {
        return (bool) get_user_meta($userId, 'account_locked', true);
    }

    public function outputLockStatusOptions(WP_User $user): void
    {
        $isUserLocked = $this->isUserLocked($user->data->ID);
        require_once LOCK_BAD_USER_PATH . 'templates/output_lock_status_options.php';
    }

    public function saveLockStatusOption(int $userId): void
    {
        // phpcs:disable WordPress.Security.NonceVerification
        if (isset($_POST['account_status'])) {
            if ($_POST['account_status'] === 'locked') {
                $this->lockAccount($userId);
            }
            if ($_POST['account_status'] === 'unlocked') {
                $this->unlockAccount($userId);
            }
        }
    }

    public function lockAccount(int $userId): void
    {
        update_user_meta($userId, 'account_locked', true);

        self::kickOutBadUser($userId);

        do_action('lock_bad_user_lock_account', $userId);
    }

    public function unlockAccount(int $userId): void
    {
        delete_user_meta($userId, 'account_locked');
        do_action('lock_bad_user_unlock_account', $userId);
    }

    /**
     * Check if user is locked and kicked out.
     *
     * @param $user
     *
     * @return WP_Error|WP_User
     * phpcs:disable
     */
    public function userAuthentication($user)
    {
        $status = get_class($user);
        if ($status === 'WP_User') {
            $isUserLocked = $this->isUserLocked($user->data->ID);
            if ($isUserLocked) {
                $message = apply_filters(
                    'account_lock_message',
                    sprintf(
                        '<strong>%s</strong> %s',
                        __('Error:', 'lock-bad-user'),
                        __('Your account has been locked.', 'lock-bad-user')
                    ),
                    $user
                );
                return new \WP_Error('authentication_failed', $message);
            }
          return $user;
        }
        return $user;
    }

    public function allowPasswordReset(bool $status, int $userId): bool
    {
        $isUserLocked = $this->isUserLocked((string) $userId);
        if ($isUserLocked) {
            return false;
        }
        return $status;
    }

    public static function kickOutBadUser(int $userId): void
    {
        $sessions = \WP_Session_Tokens::get_instance($userId);
        $sessions->destroy_all();
    }

  /**
   * Gets the singleton instance of the plugin.
   *
   * @return self The singleton instance of the InpsydePlugin class.
   */
    public static function instance(): self
    {
        static $instance;
        if (!$instance) {
            $instance = new self();
            $instance->register();
        }
        return $instance;
    }
}
