<?php

declare(strict_types=1);

namespace Webkima\LockBadUser;

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

    private function isUserLocked($user_id): bool
    {
        return (bool) get_user_meta($user_id, 'account_locked', true);
    }

    public function outputLockStatusOptions($user): void
    {
        $locking_data = $this->isUserLocked($user->data->ID);
        require_once LOCK_BAD_USER_PATH . 'templates/output_lock_status_options.php';
    }

    public function saveLockStatusOption($user_id): void
    {
        if (isset($_POST['account_status'])) {
            if ($_POST['account_status'] == 'locked') {
                $this->lockAccount($user_id);
            } else {
                $this->unlockAccount($user_id);
            }
        }
    }

    public function lockAccount($user_id): void
    {
        update_user_meta($user_id, 'account_locked', true);

        self::kickOutBadUser($user_id);

        do_action('lock_bad_user_lock_account', $user_id);
    }

    public function unlockAccount($user_id): void
    {
        delete_user_meta($user_id, 'account_locked');
        do_action('lock_bad_user_unlock_account', $user_id);
    }

    public function userAuthentication($user)
    {
        $status = get_class($user);
        if ($status == 'WP_User') {
            $locking_data = $this->isUserLocked($user->data->ID);

            if ($locking_data) {
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
            } else {
                return $user;
            }
        }
        return $user;
    }

    public function allowPasswordReset($status, $user_id)
    {
        $locking_data = $this->isUserLocked($user_id);
        if ($locking_data) {
            return false;
        } else {
            return $status;
        }
    }

    public static function kickOutBadUser($user_id): void
    {
        $sessions = \WP_Session_Tokens::get_instance($user_id);

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
