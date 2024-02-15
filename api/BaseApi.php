<?php

namespace netzlodern\pwr\api;

class BaseApi
{
    protected function getData(string $apiEndpoint): array
    {
        $tenantUrl = get_option('offer_tenant_url');
        $accessToken = get_option('offer_access_token');

        if (empty($tenantUrl) || empty($accessToken)) {
            return [];
        }

        $response = wp_remote_get(esc_url_raw($tenantUrl . $apiEndpoint), [
            'timeout' => 40,
            'headers' => [
                'Authorization' => 'Bearer ' . sanitize_text_field($accessToken)
            ],
        ]);

        if (is_wp_error($response)) {
            error_log('Error fetching data: ' . $response->get_error_message());
            throw new \Exception('Error fetching data: ' . $response->get_error_message());
        }

        if (wp_remote_retrieve_response_code($response) !== 200) {
            error_log('Error fetching data: ' . wp_remote_retrieve_response_message($response));
            throw new \Exception('Error fetching data: ' . $response->get_error_message());
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }
}
