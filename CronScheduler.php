<?php

namespace netzlodern\pwr;

use netzlodern\pwr\actions\SyncData;
use netzlodern\pwr\install\Installer;

class CronScheduler
{
    public function __construct() {
        add_filter('cron_schedules', [$this, 'addCronInterval']);
        add_action('init', [$this, 'scheduleFetch']);
        add_action('panama_web_sync_data_hook', [$this, 'executeSync']);
    }

    public function addCronInterval($schedules): array
    {
        $schedules['every_minute'] = [
            'interval' => 60,
            'display' => esc_html__('Every Minute'),
        ];
        return $schedules;
    }

    public function scheduleFetch(): void
    {
        if (!wp_next_scheduled('panama_web_sync_data_hook')) {
            wp_schedule_event(time(), 'every_minute', 'panama_web_sync_data_hook');
        }
    }

    public function clearScheduledFetch(): void
    {
        $timestamp = wp_next_scheduled('panama_web_sync_data_hook');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'panama_web_sync_data_hook');
        }
    }

    public function executeSync(): void
    {

        $syncData = new SyncData();
        $syncData->syncData();
    }
}
