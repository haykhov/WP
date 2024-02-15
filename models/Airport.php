<?php

namespace netzlodern\pwr\models;

class Airport extends BaseModel
{
    protected static function get(): array
    {
        global $wpdb;

        $tableName = $wpdb->prefix . 'pwr_airport';

        $query = "SELECT airport, airport_code FROM {$tableName}";

        return $wpdb->get_results($query);
    }

    public static function getAirportsOptions(): string
    {
        $airportsOptions = '';

        $airports = self::get();

        if (!empty($airports)) {
            foreach ($airports as $airport) {
                $airportsOptions .= sprintf(
                    '<option value="%s">%s [%s]</option>',
                    esc_attr($airport->airport_code),
                    esc_html($airport->airport),
                    esc_html($airport->airport_code)
                );
            }
        }

        return $airportsOptions;
    }

    public static function batchReplace(array $data): void
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'pwr_airport';

        $operationTimestamp = time();
        $values = [];
        $placeholders = [];

        foreach ($data as $airport) {
            $airportCode = $airport['airport_code'];
            $airportName = $airport['airport'];
            $categoryName = $airport['category'];

            $values = array_merge($values, [$airportCode, $airportName, $categoryName, $operationTimestamp]);
            $placeholders[] = "(%s, %s, %s, %d)";
        }

        $query = "INSERT INTO " . $tableName . " (airport_code, airport, category, lastRevisionTime) VALUES " . implode(', ', $placeholders) .
            " ON DUPLICATE KEY UPDATE airport_code = VALUES(airport_code), airport = VALUES(airport), category = VALUES(category), lastRevisionTime = VALUES(lastRevisionTime)";

        $wpdb->query($wpdb->prepare($query, $values));

        self::deleteByTimeStamp($tableName, $operationTimestamp);
    }
}

