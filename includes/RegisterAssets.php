<?php

namespace netzlodern\pwr\includes;

class RegisterAssets
{
    private string $defaultTravelSearchColor = '#54d6b8';
    private string $defaultTravelSearchHoverColor = '#b4f5e6';

    private string $assetsUrl;
    private string $cssUrl;
    private string $jsUrl;


    public function __construct()
    {
        $this->init();
    }

    private function init(): void
    {
        $this->assetsUrl = PWR_URL . 'assets/';
        $this->cssUrl = $this->assetsUrl . 'css/';
        $this->jsUrl = $this->assetsUrl . 'js/';

        add_action('wp_enqueue_scripts', [$this, 'pwrEnqueueScripts']);
        add_action('admin_enqueue_scripts', [$this, 'pwrEnqueueAdminScripts']);
    }

    public function pwrEnqueueAdminScripts(): void
    {
        wp_enqueue_style('pwr-main-css', $this->cssUrl . 'pwr-main.css', [], '1.0.0');
    }

    public function pwrEnqueueScripts(): void
    {
        // Enqueue jQuery (already included in WordPress)
        wp_enqueue_script('jquery');

        // Enqueue Select2
        wp_enqueue_style('select2', $this->assetsUrl . "select2/dist/css/select2.min.css", [], null);
        wp_enqueue_script('select2', $this->assetsUrl . "select2/dist/js/select2.min.js", ['jquery'], null, true);

        // Enqueue Date Range Picker CSS and JS
        wp_enqueue_style('daterangepicker', $this->assetsUrl . "daterangepicker/daterangepicker.css", [], null);
        wp_enqueue_script('moment', $this->assetsUrl . "daterangepicker/moment.min.js", [], null, true);
        wp_enqueue_script('daterangepicker', $this->assetsUrl . "daterangepicker/daterangepicker.js", ['jquery', 'moment'], null, true);

        //Local includes
        wp_enqueue_script('pwr-autocomplete', $this->jsUrl . 'autocomplete.js', ['jquery'], null, true);
        wp_localize_script('pwr-autocomplete', 'pwrAutocomplete', ['ajaxurl' => admin_url('admin-ajax.php')]);

        wp_register_style('travel-quicksearch-css', $this->cssUrl . 'travel-quicksearch.css');
        wp_enqueue_style('travel-quicksearch-css');

        wp_register_script('travel-quicksearch-js', $this->jsUrl . 'travel-quicksearch.js', array('jquery'), null, true);
        wp_enqueue_script('travel-quicksearch-js');

        wp_register_script('travel-ibe-js', $this->jsUrl . 'travel-ibe.js', array('jquery'), null, true);
        wp_enqueue_script('travel-ibe-js');

        // Include dynamic styles
        add_action('wp_head', [$this, 'pwrCustomStyles']);
    }

    public function pwrCustomStyles(): void
    {
        $travelSearchColor = get_option('travel_search_color') ?: $this->defaultTravelSearchColor;
        $travelSearchHoverColor = get_option('travel_search_hover_color') ?: $this->defaultTravelSearchHoverColor;

        echo "
                <style type='text/css'>
                    :root {
                        --travel-search-color: {$travelSearchColor};
                        --travel-search-hover-color: {$travelSearchHoverColor};
                    }
                </style>
                ";
    }
}

