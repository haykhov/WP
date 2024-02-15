<?php

namespace netzlodern\pwr;

use netzlodern\pwr\includes\RegisterAssets;
use netzlodern\pwr\install\Installer;

/**
 * Plugin Name: Panama Web - Reiseangebote WP
 * Plugin URI: https://www.netzlodern.de
 * Description: Offer Generator plugin for travel agencies.
 * Version: 1.0.0
 * Author: Netzlodern GmbH
 * Author URI: https://www.netzlodern.de
 */

defined('ABSPATH') || exit('No direct script access allowed');

class PanamaWebReiseangebote
{
    const VERSION = '1.0.0';

    public function __construct()
    {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
        $this->install_assets();
        $this->setupCronScheduler();
    }

    private function define_constants(): void
    {
        define('PWR_URL', plugin_dir_url(__FILE__));
        define('PWR_PATH', plugin_dir_path(__FILE__));
    }

    private function includes(): void
    {
        include_once PWR_PATH . 'includes/RegisterAssets.php';
        include_once PWR_PATH . 'install/Installer.php';
        include_once PWR_PATH . 'AdminPage.php';
        include_once PWR_PATH . 'models/BaseModel.php';
        include_once PWR_PATH . 'post-types/TravelOffersPostType.php';
        include_once PWR_PATH . 'taxonomies/TravelTaxonomies.php';
        include_once PWR_PATH . 'api/BaseApi.php';
        include_once PWR_PATH . 'api/TravelMeta.php';
        include_once PWR_PATH . 'api/TravelOffer.php';
        include_once PWR_PATH . 'api/TravelPool.php';
        include_once PWR_PATH . 'actions/SyncData.php';
        include_once PWR_PATH . 'CronScheduler.php';
        include_once PWR_PATH . 'shortcodes/TravelOfferQuickSearch.php';
        include_once PWR_PATH . 'shortcodes/TravelIbe.php';
    }

    private function init_hooks(): void
    {
        register_activation_hook(__FILE__, [$this, 'activate']);
    }

    public function activate(): void
    {
        $installer = new Installer();
        $installer->install();
    }

    public function setupCronScheduler(): void
    {
        $cronScheduler = new CronScheduler();
        register_activation_hook(__FILE__, [$cronScheduler, 'scheduleFetch']);
        register_deactivation_hook(__FILE__, [$cronScheduler, 'clearScheduledFetch']);
    }

    private function install_assets(): void
    {
        new RegisterAssets();
    }
}

new PanamaWebReiseangebote();
