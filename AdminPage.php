<?php

namespace netzlodern\pwr;

use netzlodern\pwr\api\TravelMeta;

class AdminPage
{
    const MENU_SLUG = 'panama-web-reiseangebote-settings';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'createMenu']);

        add_action('admin_post_save_pwr_settings', [$this, 'handlePwrSettingsSubmission']);

        add_action('admin_post_sync_pwr_meta_data', [$this, 'handlePwrSyncMetaData']);
    }

    public function createMenu(): void
    {
        add_menu_page(
            __('Travel Offer Settings', 'panama-web-reiseangebote'),
            __('Travel Offer Settings', 'panama-web-reiseangebote'),
            'manage_options',
            self::MENU_SLUG,
            [$this, 'settingsPage'],
            'dashicons-admin-generic'
        );
    }

    public function settingsPage(): void
    {
        include plugin_dir_path(__FILE__) . 'pages/SettingsForm.php';
    }

    public function handlePwrSettingsSubmission(): void
    {
        // Check nonce for security
        check_admin_referer('pwr_options_action', 'pwr_options_nonce');

        $tenantUrl = sanitize_text_field($_POST['offer_tenant_url'] ?? '');
        $accessToken = sanitize_text_field($_POST['offer_access_token'] ?? '');
        $taId = sanitize_text_field($_POST['offer_ta_id'] ?? '');
        $deeplinkUrl = esc_url_raw($_POST['offer_deeplink_url'] ?? '');
        $searchColor = sanitize_hex_color($_POST['travel_search_color'] ?? '');
        $hoverColor = sanitize_hex_color($_POST['travel_search_hover_color'] ?? '');

        if (empty($tenantUrl) || empty($accessToken) || empty($taId) || empty($deeplinkUrl)) {
            add_settings_error('pwr_options', 'pwr_required_fields', 'All required fields must be filled.');
        } else {

            // Save settings
            update_option('offer_tenant_url', $tenantUrl);
            update_option('offer_access_token', $accessToken);
            update_option('offer_ta_id', $taId);
            update_option('offer_deeplink_url', $deeplinkUrl);
            update_option('travel_search_color', $searchColor);
            update_option('travel_search_hover_color', $hoverColor);
            add_settings_error('pwr_options', 'pwr_settings_saved', 'Settings saved successfully.', 'updated');
        }

        // Redirect back to the settings page
        set_transient('settings_errors', get_settings_errors(), 30);
        wp_redirect(html_entity_decode(wp_get_referer()) . '&settings-updated=true');
        exit;
    }

    public function handlePwrSyncMetaData(): void
    {
        check_admin_referer('sync_pwr_meta_data', 'pwr_sync_meta_nonce');

        $travelMeta = new TravelMeta();
        $travelMeta->fetchTravelMeta();
        add_settings_error('pwr_options', 'sync_meta_data_success', 'Meta data synchronized successfully.', 'updated');

        set_transient('settings_errors', get_settings_errors(), 30);
        wp_redirect(html_entity_decode(wp_get_referer()) . '&settings-updated=true');
        exit;
    }

}

new AdminPage();
