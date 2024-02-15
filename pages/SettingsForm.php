<?php
settings_fields('pwr_options');
do_settings_sections('pwr_options');

// Retrieve existing values from database
$url = get_option('offer_tenant_url');
$token = get_option('offer_access_token');
$deeplink = get_option('offer_deeplink_url');
$taId = get_option('offer_ta_id');
?>

<div class="wrap">
    <h1>Travel Offer Settings</h1>
    <?php settings_errors(); ?>

    <br>
    <h2>Sync Amadeus Leisure IBE Meta data</h2>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="sync_pwr_meta_data">
        <?php wp_nonce_field('sync_pwr_meta_data', 'pwr_sync_meta_nonce'); ?>
        <button type="submit" name="action" value="sync_pwr_meta_data" class="button button-primary">
            Sync Meta Data
        </button>
    </form>

    <br>
    <hr>
    <h2>Set Settings</h2>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="save_pwr_settings">
        <?php wp_nonce_field('pwr_options_action', 'pwr_options_nonce'); ?>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="url">Tenant URL <span class="pwr-label-required">*</span></label>
                </th>
                <td>
                    <input name="offer_tenant_url" type="text" id="url" value="<?= esc_attr($url); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="token">Access Token <span class="pwr-label-required">*</span></label>
                </th>
                <td>
                    <input name="offer_access_token" type="text" id="token" value="<?= esc_attr($token); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="deeplink">Deeplink URL <span class="pwr-label-required">*</span></label>
                </th>
                <td>
                    <input name="offer_deeplink_url" type="url" id="deeplink" value="<?php echo esc_attr($deeplink); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="offer_ta_id">taID (Bistro Bürokürzel) <span class="pwr-label-required">*</span></label>
                </th>
                <td>
                    <input name="offer_ta_id" type="text" id="offer_ta_id" value="<?= esc_attr($taId); ?>" class="regular-text" >
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="travel_search_color">Akzentfarbe</label>
                </th>
                <td>
                    <input name="travel_search_color" type="text" id="travel_search_color" value="<?= esc_attr(get_option('travel_search_color')); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="travel_search_hover_color">Hover Akzentfarbe</label>
                </th>
                <td>
                    <input name="travel_search_hover_color" type="text" id="travel_search_hover_color" value="<?= esc_attr(get_option('travel_search_hover_color')); ?>" class="regular-text">
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
    <hr>
</div>
