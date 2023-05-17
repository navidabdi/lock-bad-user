<?php

declare(strict_types=1);

namespace Inpsyde\UserTableShowcase;

use Exception;

class FetchUsersData implements FetchAPI
{
    private $apiUrl = 'https://jsonplaceholder.typicode.com/users';

    /**
     * @throws Exception
     */
    public function data(): array
    {
        $users = $this->cache();
        if (!$users) {
            $users = $this->fetchData();
            $this->cache($users);
        }

        return $users;
    }

    private function fetchData(): array
    {
        $response = wp_remote_get($this->apiUrl);
        if (is_wp_error($response)) {
            throw new Exception('Error fetching data from API.' . $response->get_error_message());
        }

        $body = wp_remote_retrieve_body($response);
        $users = json_decode($body, true);

        return $users;
    }

    public function cache(array $users = null): array|bool
    {
        $cacheKey = 'user-table-showcase';
        $cacheGroup = 'user-table-showcase';
        $cacheTime = 60 * 60 * 24;

        if (null === $users) {
            return wp_cache_get($cacheKey, $cacheGroup);
        }

        wp_cache_set($cacheKey, $users, $cacheGroup, $cacheTime);

        return $users;
    }
}
