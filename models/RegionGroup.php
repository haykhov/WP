<?php

namespace netzlodern\pwr\models;

class RegionGroup extends BaseModel
{
    public static function search(string $term, int $limit = 5): array
    {
        global $wpdb;

        $tableName = $wpdb->prefix . 'pwr_region_group';

        return $wpdb->get_results($wpdb->prepare(
            "SELECT region_group_id, region_group as name FROM {$tableName} WHERE region_group LIKE %s LIMIT %d",
            '%' . $wpdb->esc_like($term) . '%',
            $limit
        ));
    }

    public static function batchReplace(array $data): void
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'pwr_region_group';

        $operationTimestamp = time();
        $values = [];
        $placeholders = [];

        foreach ($data as $regionGroup) {
            $regionGroupId = $regionGroup['region_group_id'];
            $regionGroupName = $regionGroup['region_group'];
            $values = array_merge($values, [$regionGroupId, $regionGroupName, $operationTimestamp]);
            $placeholders[] = "(%s, %s, %d)";
        }

        $query = "INSERT INTO " . $tableName . " (region_group_id, region_group, lastRevisionTime) VALUES " . implode(', ', $placeholders) .
            " ON DUPLICATE KEY UPDATE region_group_id = VALUES(region_group_id), region_group = VALUES(region_group), lastRevisionTime = VALUES(lastRevisionTime)";

        $wpdb->query($wpdb->prepare($query, $values));

        self::deleteByTimeStamp($tableName, $operationTimestamp);
    }
}

