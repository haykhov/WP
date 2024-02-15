<?php

namespace netzlodern\pwr\postTypes;

use WP_Post;

class TravelOffersPostType
{
    public function __construct()
    {
        add_action('init', [$this, 'registerTravelOffersPostType']);
        add_filter('manage_travel_offers_posts_columns', [$this, 'setCustomTravelOffersColumns']);
        add_action('manage_travel_offers_posts_custom_column', [$this, 'customTravelOffersColumn'], 10, 2);
        add_filter('manage_edit-travel_offers_sortable_columns', [$this, 'setCustomTravelOffersSortableColumns']);
        add_filter('post_row_actions', [$this, 'panamaWebTravelOffersPostRowActions'], 10, 2);
    }

    public function registerTravelOffersPostType(): void
    {
        $args = [
            'labels' => ['name' => __('Travel Offers'), 'singular_name' => __('Travel Offer')],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => [
                'slug' => 'travel_offers'
            ],
            'capability_type' => 'post',
            'capabilities' => [
                'create_posts' => 'do_not_allow', // Disables the ability to create new posts from the admin
            ],
            'map_meta_cap' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => [
                'title', 'editor'
            ],
            'show_in_rest' => true
        ];

        register_post_type('travel_offers', $args);
    }

    public function setCustomTravelOffersColumns(array $columns): array
    {
        return [
            'cb' => $columns['cb'],
            'id' => __('Id'),
            'title' => __('Title'),
            'type' => __('Type'),
            'deeplink_url' => __('Deeplink URL'),
            'amadeus_url_query' => __('Amadeus URL Query'),
            'state' => __('State'),
            'date' => __('Date')
        ];
    }

    public function customTravelOffersColumn(string $column, int $postId): void
    {
        switch ($column) {
            case 'id':
                echo esc_html(get_post_meta($postId, 'id', true));
                break;
            case 'title':
                echo esc_html(get_post_meta($postId, 'title', true));
                break;
            case 'type':
                echo esc_html(get_post_meta($postId, 'type', true));
                break;
            case 'deeplink_url':
                echo esc_url(get_post_meta($postId, 'deeplink_url', true));
                break;
            case 'amadeus_url_query':
                echo esc_html(get_post_meta($postId, 'amadeus_url_query', true));
                break;
            case 'state':
                echo esc_html(get_post_meta($postId, 'state', true));
                break;
            case 'date':
                echo esc_html(get_post_meta($postId, 'date', true));
                break;
        }
    }

    public function setCustomTravelOffersSortableColumns(array $columns): array
    {
        $columns['id'] = 'id';
        $columns['uuid'] = 'uuid';

        return $columns;
    }

    public function panamaWebTravelOffersPostRowActions(array $actions, WP_Post $post): array
    {
        if ($post->post_type === 'travel_offers') {
            $deeplinkUrl = get_post_meta($post->ID, 'deeplink_url', true);
            if (!empty($deeplinkUrl)) {
                // Change the 'View' action to point to the deeplink URL.
                $actions['view'] = '<a href="' . ($deeplinkUrl) . '" target="_blank" rel="noopener noreferrer">' . __('View') . '</a>';

                // Add a script to change the title link.
                add_action('admin_footer', function () use ($deeplinkUrl, $post) {
                    ?>
                    <script
                        type="text/javascript">
                        document.addEventListener('DOMContentLoaded', function () {
                            var titleLink = document.querySelector('.row-title[href*="post=<?= $post->ID; ?>"]');
                            if (titleLink) {
                                titleLink.href = '<?= $deeplinkUrl; ?>';
                                titleLink.target = '_blank';
                                titleLink.rel = 'noopener noreferrer';
                            }
                        });
                    </script>
                    <?php
                });
            }

            // Remove other actions.
            unset($actions['edit']);
            unset($actions['inline hide-if-no-js']);
            unset($actions['trash']);
        }
        return $actions;
    }
}

new TravelOffersPostType();

