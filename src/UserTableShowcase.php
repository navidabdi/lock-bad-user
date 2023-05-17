<?php

declare(strict_types=1);

namespace Inpsyde\UserTableShowcase;

/**
 * Class UserTableShowcase
 *
 * @package Inpsyde\UserTableShowcase
 */
final class UserTableShowcase
{
    private static $instance;
    private $fetchApi;

    private function __construct(FetchAPI $fetchApi)
    {
        $this->fetchApi = $fetchApi;
    }

    public function init(): void
    {
        $this->renderUserTable();
    }

    public static function instance(): self
    {
        if (!isset(self::$instance)) {
            $userData = new FetchUsersData();
            self::$instance = new self($userData);
        }
        return self::$instance;
    }

    private function renderUserTable(): void
    {
        $users = $this->fetchApi->data();
        if (empty($users)) {
            echo 'No users found.';
            return;
        }

        $html = '<table>';
        $html .= '<thead><tr><th>ID</th><th>Name</th><th>Email</th></tr></thead>';
        $html .= '<tbody>';
        foreach ($users as $user) {
            $html .= '<tr>';
            $html .= '<td>' . esc_html($user['id']) . '</td>';
            $html .= '<td>' . esc_html($user['name']) . '</td>';
            $html .= '<td>' . esc_html($user['email']) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';

        echo $html;
    }
}
