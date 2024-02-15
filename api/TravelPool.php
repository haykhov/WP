<?php

namespace netzlodern\pwr\api;

use Exception;

defined('ABSPATH') || exit();

class TravelPool extends BaseApi
{
    private $apiEndpoint = "/api/v1/tourism-travel-offer-pools";

    public function fetchTravelPools(): void
    {
        $data = $this->getData($this->apiEndpoint);
        $this->storeTravelPools($data);
    }

    private function storeTravelPools(array $pools): void
    {
        global $wpdb;

        if (!isset($pools['tourism-travel-offer-pools'])) {
            return;
        }

        $wpdb->query('START TRANSACTION');

        try {
            $this->deleteData();

            foreach ($pools['tourism-travel-offer-pools'] as $pool) {
                $this->insertData($pool);
            }

            $wpdb->query('COMMIT');
        } catch (Exception $e) {
            $wpdb->query('ROLLBACK');
            error_log($e->getMessage());
        }
    }

    protected function deleteData(): void
    {
        $terms = get_terms([
                               'taxonomy' => 'travel_pool',
                               'hide_empty' => false,
                           ]);

        foreach ($terms as $term) {
            wp_delete_term($term->term_id, 'travel_pool');
        }
    }

    protected function insertData(array $data): void
    {
        if (empty($data['name']) || empty($data['uuid'])) {
            return;
        }

        $term = wp_insert_term($data['name'], 'travel_pool', [
            'description' => $data['title'] ?? '',
        ]);

        if (is_wp_error($term)) {
            error_log('Error creating travel pool term: ' . $term->get_error_message());
            throw new Exception('Error creating travel pool term: ' . $term->get_error_message());
        }

        // Update term meta with uuid.
        update_term_meta($term['term_id'], 'uuid', sanitize_text_field($data['uuid']));
    }
}

