<?php

namespace LockBadUser\Setup;


class LockUser
{
    public function register()
    {
        $this->lock_bad_user_initialize();
    }

    public function lock_bad_user_initialize()
    {
        add_action('edit_user_profile', array($this, 'lock_bad_user_output_lock_status_options'));
        add_action('edit_user_profile_update', array($this, 'lock_bad_user_save_lock_status_options'));
        add_filter('authenticate', array($this, 'lock_bad_user_user_authentication'), 9999);
        add_filter('allow_password_reset', array($this, 'lock_bad_user_allow_password_reset'), 9999, 2);
    }

    private function lock_bad_user_fetch_lock_status($user_id)
    {
        return get_user_meta($user_id, 'account_locked', TRUE);
    }

    public function lock_bad_user_output_lock_status_options($user)
    {
        $locking_data = $this->lock_bad_user_fetch_lock_status($user->data->ID);
        require_once LOCK_BAD_USER_PLUGIN_BASE_PATH . 'templates/output_lock_status_options.php';
    }

    public function lock_bad_user_save_lock_status_options($user_id)
    {
        if (isset($_POST['account_status'])) {
            if ($_POST['account_status'] == 'locked') {
                $this->lock_bad_user_lock_account($user_id);
            } else {
                $this->lock_bad_user_unlock_account($user_id);
            }
        }
    }

    public function lock_bad_user_lock_account($user_id)
    {
        update_user_meta($user_id, 'account_locked', TRUE);

        self::lock_bad_user_get_out_bad_user($user_id);

        do_action('lock_bad_user_lock_account', $user_id);
    }

    public function lock_bad_user_unlock_account($user_id)
    {
        delete_user_meta($user_id, 'account_locked');
        do_action('lock_bad_user_unlock_account', $user_id);
    }

    public function lock_bad_user_user_authentication($user)
    {
        $status = get_class($user);
        if ($status == 'WP_User') {
            $locking_data = $this->lock_bad_user_fetch_lock_status($user->data->ID);

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

    public function lock_bad_user_allow_password_reset($status, $user_id)
    {
        $locking_data = $this->lock_bad_user_fetch_lock_status($user_id);
        if ($locking_data) {
            return false;
        } else {
            return $status;
        }
    }

    public static function lock_bad_user_get_out_bad_user($user_id)
    {
        $sessions = \WP_Session_Tokens::get_instance($user_id);

        $sessions->destroy_all();
    }
}