<?php

namespace netzlodern\pwr\models;

class Region extends BaseModel
{
    public static function search(string $term, int $limit = 5): array
    {
        global $wpdb;

        $tableName = $wpdb->prefix . 'pwr_region';

        return $wpdb->get_results($wpdb->prepare(
            "SELECT region_id, region as name, region_group_id FROM {$tableName} WHERE region LIKE %s LIMIT %d",
            '%' . $wpdb->esc_like($term) . '%',
            $limit
        ));
    }

    public static function batchReplace(array $data): void
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'pwr_region';

        $operationTimestamp = time();
        $values = [];
        $placeholders = [];

        foreach ($data as $region) {
            $regionId = $region['region_id'];
            $regionName = $region['region'];
            $regionGroupId = $region['region_group_id'];
            $values = array_merge($values, [$regionId, $regionName, $regionGroupId, $operationTimestamp]);
            $placeholders[] = "(%s, %s, %s, %d)";
        }

        $query = "INSERT INTO " . $tableName . " (region_id, region, region_group_id, lastRevisionTime) VALUES " . implode(', ', $placeholders) .
            " ON DUPLICATE KEY UPDATE region_id = VALUES(region_id), region = VALUES(region), region_group_id = VALUES(region_group_id), lastRevisionTime = VALUES(lastRevisionTime)";

        $wpdb->query($wpdb->prepare($query, $values));

        self::deleteByTimeStamp($tableName, $operationTimestamp);
    }
}

