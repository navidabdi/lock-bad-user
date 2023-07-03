<?php

namespace Webkima\LockBadUser;

class LockUser
{
    public function register()
    {
        $this->initHook();
    }

    public function initHook()
    {
        add_action('edit_user_profile', array($this, 'outputLockStatusOptions'));
        add_action('edit_user_profile_update', array($this, 'saveLockStatusOption'));
        add_filter('authenticate', array($this, 'userAuthentication'), 9999);
        add_filter('allow_password_reset', array($this, 'allowPasswordReset'), 9999, 2);
    }

    private function isUserLocked($user_id)
    {
        return get_user_meta($user_id, 'account_locked', TRUE);
    }

    public function outputLockStatusOptions($user)
    {
        $locking_data = $this->isUserLocked($user->data->ID);
        require_once LOCK_BAD_USER_PATH . 'templates/output_lock_status_options.php';
    }

    public function saveLockStatusOption($user_id)
    {
        if (isset($_POST['account_status'])) {
            if ($_POST['account_status'] == 'locked') {
                $this->lockAccount($user_id);
            } else {
                $this->unlockAccount($user_id);
            }
        }
    }

    public function lockAccount($user_id)
    {
        update_user_meta($user_id, 'account_locked', TRUE);

        self::kickOutBadUser($user_id);

        do_action('lock_bad_user_lock_account', $user_id);
    }

    public function unlockAccount($user_id)
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
                    sprintf('<strong>%s</strong> %s', 'Error:', 'Your account has been locked. '),
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

    public static function kickOutBadUser($user_id)
    {
        $sessions = \WP_Session_Tokens::get_instance($user_id);

        $sessions->destroy_all();
    }

  /**
   * Gets the singleton instance of the plugin.
   *
   * @return self The singleton instance of the InpsydePlugin class.
   */
  public static function instance()
  {
    static $instance;
    if (!$instance) {
      $instance = new self();
      $instance->register();
    }
    return $instance;
  }
}