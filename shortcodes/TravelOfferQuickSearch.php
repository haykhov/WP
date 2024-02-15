<?php

namespace netzlodern\pwr\shortcodes;

use netzlodern\pwr\actions\AjaxHandler;
use netzlodern\pwr\models\Airport;
use netzlodern\pwr\models\Duration;

class TravelOfferQuickSearch
{
    public function __construct()
    {
        $this->addIncludes();

        add_shortcode('traveloffer_quicksearch', [$this, 'renderShortcode']);
        add_action('wp_footer', [$this, 'enqueueAssets']);

    }

    public function addIncludes(): void
    {
        include_once plugin_dir_path(dirname(__FILE__)) . 'models/Airport.php';
        include_once plugin_dir_path(dirname(__FILE__)) . 'models/Country.php';
        include_once plugin_dir_path(dirname(__FILE__)) . 'models/Region.php';
        include_once plugin_dir_path(dirname(__FILE__)) . 'models/RegionGroup.php';
        include_once plugin_dir_path(dirname(__FILE__)) . 'models/City.php';
        include_once plugin_dir_path(dirname(__FILE__)) . 'models/Accommodation.php';
        include_once plugin_dir_path(dirname(__FILE__)) . 'models/Duration.php';
        include_once plugin_dir_path(dirname(__FILE__)) . 'actions/AjaxHandler.php';

        $ajax_handlers = new AjaxHandler();
    }

    public function renderShortcode(): string
    {
        $airportsDropdownHtml = Airport::getAirportsOptions();
        $durationOptions = Duration::getDurationOptions();

        ob_start();
        include plugin_dir_path(__FILE__) . 'TravelOfferQuickSearchOutput.php';
        return ob_get_clean();
    }

    public function enqueueAssets(): void
    {
        if (!isset($GLOBALS['add_travel_quick_search_assets']) || !$GLOBALS['add_travel_quick_search_assets']) {
            return;
        }

        wp_enqueue_style('travel-quicksearch-css');
        wp_enqueue_script('travel-quicksearch-js');
    }
}

new TravelOfferQuickSearch();
