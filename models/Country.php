<?php

namespace netzlodern\pwr\models;

class Country extends BaseModel
{
    public static function search(string $term, int $limit = 5): array
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'pwr_country';

        $query = $wpdb->prepare(
            "SELECT country_code as country_id, country as name FROM " . $tableName . " WHERE country LIKE %s LIMIT %d",
            '%' . $wpdb->esc_like($term) . '%',
            $limit
        );

        return $wpdb->get_results($query);
    }

    public static function batchReplace(array $data): void
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'pwr_country';

        $operationTimestamp = time();
        $values = [];
        $placeholders = [];

        foreach ($data as $country) {
            $countryCode = $country['country_code'];
            $countryName = $country['country'];
            $values = array_merge($values, [$countryCode, $countryName, $operationTimestamp]);
            $placeholders[] = "(%s, %s, %d)";
        }

        $query = "INSERT INTO " . $tableName . " (country_code, country, lastRevisionTime) VALUES " . implode(', ', $placeholders) .
            " ON DUPLICATE KEY UPDATE country = VALUES(country), lastRevisionTime = VALUES(lastRevisionTime)";

        $wpdb->query($wpdb->prepare($query, $values));

        self::deleteByTimeStamp($tableName, $operationTimestamp);
    }
}
