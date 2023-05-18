<?php

declare(strict_types=1);

namespace Inpsyde\UserTableShowcase;

use WP_Error;

class FetchUserData
{
  /**
   * Send a GET request to the specified URL and return parsed JSON response.
   *
   * @param string $url The URL to send the request to.
   * @return array|WP_Error The parsed JSON response as an array or a WP_Error object on failure.
   */
    public function get(string $url): array|WP_Error
    {
        $cacheKey = 'http_request_' . md5($url);
        $cachedResponse = wp_cache_get($cacheKey, 'http_requests');

        if ($cachedResponse !== false) {
            return $cachedResponse;
        }

        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (is_null($data)) {
            return new \WP_Error('json_decode_error', 'Failed to parse JSON response.');
        }

        wp_cache_set($cacheKey, $data, 'http_requests', 600); // Cache for 10 minutes

        return $data;
    }
}
