<?php

namespace netzlodern\pwr\models;

class City extends BaseModel
{
    public static function search(string $term, int $limit = 5): array
    {
        global $wpdb;

        $tableName = $wpdb->prefix . 'pwr_city';

        return $wpdb->get_results($wpdb->prepare(
            "SELECT city_id, city as name, country_code as country_id, region_group_id FROM {$tableName} WHERE city LIKE %s LIMIT %d",
            '%' . $wpdb->esc_like($term) . '%',
            $limit
        ));
    }

    public static function batchReplace(array $data): void
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'pwr_city';

        $operationTimestamp = time();
        $values = [];
        $placeholders = [];

        foreach ($data as $city) {
            $cityId = $city['city_id'];
            $cityName = $city['city'];
            $regionGroupId = $city['region_group_id'];
            $regionId = $city['region_id'];

            $values = array_merge($values, [$cityId, $cityName, $regionGroupId, $regionId,  $operationTimestamp]);
            $placeholders[] = "(%s, %s, %s, %s, %d)";
        }

        $query = "INSERT INTO " . $tableName . " (city_id, city, region_group_id, region_id, lastRevisionTime) VALUES " . implode(', ', $placeholders) .
            " ON DUPLICATE KEY UPDATE city_id = VALUES(city_id), city = VALUES(city), region_group_id = VALUES(region_group_id), region_id = VALUES(region_id), lastRevisionTime = VALUES(lastRevisionTime)";

        $wpdb->query($wpdb->prepare($query, $values));

        self::deleteByTimeStamp($tableName, $operationTimestamp);
    }
}
