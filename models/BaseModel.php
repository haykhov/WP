<?php

namespace netzlodern\pwr\models;

abstract class BaseModel
{
    abstract public static function batchReplace(array $data): void;

    public static function deleteByTimeStamp(string $tableName, int $lastRevisionTime): void
    {
        global $wpdb;

        $delete_query = $wpdb->prepare(
            "DELETE FROM " . $tableName . " WHERE lastRevisionTime <> %d",
            $lastRevisionTime
        );

        $wpdb->query($delete_query);
    }
}
