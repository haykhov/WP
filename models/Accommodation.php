<?php

namespace netzlodern\pwr\models;

class Accommodation extends BaseModel
{
    public static function search(string $term, int $limit = 5): array
    {
        global $wpdb;

        $tableName = $wpdb->prefix . 'pwr_accommodation';

        return $wpdb->get_results($wpdb->prepare(
            "SELECT accommodation_id, accommodation as name, country_code as country_id, city_id, region_group_id, region_id FROM {$tableName} WHERE accommodation LIKE %s LIMIT %d",
            '%' . $wpdb->esc_like($term) . '%',
            $limit
        ));
    }

    public static function batchReplace(array $data): void
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'pwr_accommodation';

        $operationTimestamp = time();
        $values = [];
        $placeholders = [];

        foreach ($data as $accommodation) {
            $accommodationId = $accommodation['accommodation_id'];
            $accommodationName = $accommodation['accommodation'];
            $regionGroupId = $accommodation['region_group_id'];
            $regionId = $accommodation['region_id'];
            $cityId = $accommodation['city_id'];

            $values = array_merge($values, [$accommodationId, $accommodationName, $regionGroupId, $regionId, $cityId, $operationTimestamp]);
            $placeholders[] = "(%s, %s, %s, %s, %s, %d)";
        }

        $query = "INSERT INTO " . $tableName . " (accommodation_id, accommodation, region_group_id, region_id, city_id, lastRevisionTime) VALUES " . implode(', ', $placeholders) .
            " ON DUPLICATE KEY UPDATE accommodation_id = VALUES(accommodation_id),
                accommodation = VALUES(accommodation),
                region_group_id = VALUES(region_group_id),
                region_id = VALUES(region_id),
                city_id = VALUES(city_id ),
                lastRevisionTime = VALUES(lastRevisionTime)";

        $wpdb->query($wpdb->prepare($query, $values));

        self::deleteByTimeStamp($tableName, $operationTimestamp);
    }
}
