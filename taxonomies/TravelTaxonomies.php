<?php

namespace netzlodern\pwr\taxonomies;

class TravelTaxonomies
{
    public function __construct()
    {
        add_action('init', [$this, 'registerTaxonomies']);
    }

    public function registerTaxonomies(): void
    {
        $this->registerTravelPools();
    }

    private function registerTravelPools(): void
    {
        $labels = [
            'name' => _x('Travel Pools', 'taxonomy general name', 'textdomain'),
            'singular_name' => _x('Travel Pool', 'taxonomy singular name', 'textdomain'),
            'search_items' => __('Search Travel Pools', 'textdomain'),
            'all_items' => __('All Travel Pools', 'textdomain'),
            'parent_item' => __('Parent Travel Pool', 'textdomain'),
            'parent_item_colon' => __('Parent Travel Pool:', 'textdomain'),
            'edit_item' => __('Edit Travel Pool', 'textdomain'),
            'update_item' => __('Update Travel Pool', 'textdomain'),
            'add_new_item' => __('Add New Travel Pool', 'textdomain'),
            'new_item_name' => __('New Travel Pool Name', 'textdomain'),
            'menu_name' => __('Travel Pool', 'textdomain'),
        ];

        $args = [
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => [
                'slug' => 'travel-pool'
            ],
            'capabilities' => [
                'manage_terms' => 'manage_categories', // Using 'manage_categories' capability as an example
                'edit_terms' => 'do_not_allow', //read
                'delete_terms' => 'do_not_allow',
                'assign_terms' => 'edit_posts',
            ],
            'show_in_nav_menus' => false,
            'show_tagcloud' => false,
        ];

        register_taxonomy('travel_pool', 'travel_offers', $args);
    }
}

new TravelTaxonomies();

