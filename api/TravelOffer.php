<?php

namespace netzlodern\pwr\api;

use Exception;

class TravelOffer extends BaseApi
{
    private $apiEndpoint = "/api/v1/tourism-travel-offers?expand=tourism-travel-offer-pools";

    public function fetchTravelOffers(): void
    {
        $data = $this->getData($this->apiEndpoint);
        $this->storeTravelOffers($data);
    }

    private function storeTravelOffers(array $offers): void
    {
        global $wpdb;

        if (!isset($offers['tourism-travel-offers'])) {
            return;
        }

        $wpdb->query('START TRANSACTION');

        try {
            $this->deleteData();

            foreach ($offers['tourism-travel-offers'] as $offer) {
                $this->insertData($offer);
            }

            $wpdb->query('COMMIT');
        } catch (Exception $e) {
            $wpdb->query('ROLLBACK');
            error_log($e->getMessage());
        }
    }

    protected function deleteData(): void
    {
        $existingOffers = get_posts([
                                        'post_type' => 'travel_offers',
                                        'numberposts' => -1,
                                        'fields' => 'ids'
                                    ]);

        foreach ($existingOffers as $offerId) {
            wp_delete_post($offerId, true);
        }
    }

    protected function insertData(array $data): void
    {
        // Create a new post for the offer.
        $postId = wp_insert_post([
                                     'post_type' => 'travel_offers',
                                     'post_title' => wp_strip_all_tags($data['title']),
                                     'post_content' => '',
                                     'post_status' => 'publish',
                                 ]);

        if (is_wp_error($postId)) {
            error_log('Error creating travel offer post: ' . $postId->get_error_message());
            throw new Exception('Error creating travel offer post: ' . $postId->get_error_message());
        }

        // Store offer data as post meta, except the '_expanded' data.
        foreach ($data as $key => $value) {
            if ($key !== '_expanded') {
                update_post_meta($postId, $key, $value);
            }
        }

        // Concatenate deeplink_url with amadeus_url_query and store in post meta.
        $deeplinkBaseUrl = get_option('offer_deeplink_url'); // Get the deeplink URL from the options.
        if (!empty($deeplinkBaseUrl) && isset($data['amadeus_url_query'])) {
            $deeplinkFullUrl = $deeplinkBaseUrl . (strpos($deeplinkBaseUrl, '?') === false ? '?' : '&') . $data['amadeus_url_query'];
            update_post_meta($postId, 'deeplink_url', esc_url_raw($deeplinkFullUrl));
        }

        // Handle the '_expanded' data - link with travel pools.
        if (isset($data['_expanded']['tourism-travel-offer-pools'])) {
            $this->setOfferPools($postId, $data['_expanded']['tourism-travel-offer-pools']);
        }
    }

    private function setOfferPools($postId, $pools)
    {
        $poolTerms = [];

        foreach ($pools as $pool) {
            // Ensure the UUID is consistent with the re-created pool terms.
            $poolUuid = $pool['uuid'] ?? null;
            if (!$poolUuid) {
                continue;
            }

            // Find the term by its UUID.
            $terms = get_terms([
                                   'taxonomy' => 'travel_pool',
                                   'hide_empty' => false,
                                   'meta_query' => [
                                       [
                                           'key' => 'uuid',
                                           'value' => $poolUuid,
                                           'compare' => '='
                                       ]
                                   ]
                               ]);

            if (!empty($terms) && !is_wp_error($terms)) {
                $poolTerms[] = $terms[0]->term_id;
            }
        }

        // Re-associate the offer with the pool terms.
        wp_set_object_terms($postId, $poolTerms, 'travel_pool');
    }
}
