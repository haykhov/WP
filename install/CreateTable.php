<?php

namespace netzlodern\pwr\install;

class CreateTable
{
    public function pwrCreateDatabaseTables(): void
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $sql = [];

        $sql[] = $this->createContinentTable("{$wpdb->prefix}pwr_continent", $charsetCollate);
        $sql[] = $this->createCountryTable("{$wpdb->prefix}pwr_country", $charsetCollate);
        $sql[] = $this->createProvinceTable("{$wpdb->prefix}pwr_province", $charsetCollate);
        $sql[] = $this->createCityTable("{$wpdb->prefix}pwr_city", $charsetCollate);
        $sql[] = $this->createRegionGroupTable("{$wpdb->prefix}pwr_region_group", $charsetCollate);
        $sql[] = $this->createRegionTable("{$wpdb->prefix}pwr_region", $charsetCollate);
        $sql[] = $this->createAccommodationTable("{$wpdb->prefix}pwr_accommodation", $charsetCollate);
        $sql[] = $this->createAirportTable("{$wpdb->prefix}pwr_airport", $charsetCollate);
        $sql[] = $this->createTouroperatorTable("{$wpdb->prefix}pwr_touroperator", $charsetCollate);

        // Include the WordPress upgrade library
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Execute the SQL statements
        foreach ($sql as $query) {
            dbDelta($query);
        }
    }

    private function createContinentTable(string $tableName, string $charsetCollate): string
    {
        return "CREATE TABLE IF NOT EXISTS `$tableName` (
                `continent_code` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `continent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `type_for` enum('accommodation','package') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `lastRevisionTime` int unsigned NOT NULL,
                UNIQUE KEY `continent_code` (`continent_code`,`type_for`)
            ) $charsetCollate;";
    }

    private function createCountryTable(string $tableName, string $charsetCollate): string
    {
        return "CREATE TABLE IF NOT EXISTS `$tableName` (
                `country_code` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `continent_code` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `type_for` enum('accommodation','package') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `lastRevisionTime` int unsigned NOT NULL,
                UNIQUE KEY `country_code` (`country_code`,`type_for`)
            ) $charsetCollate;";
    }

    private function createProvinceTable(string $tableName, string $charsetCollate): string
    {
        return "CREATE TABLE IF NOT EXISTS `$tableName` (
                `province_code` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `continent_code` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `country_code` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `type_for` enum('accommodation','package') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `lastRevisionTime` int unsigned NOT NULL,
                UNIQUE KEY `province_code` (`province_code`,`type_for`)
            ) $charsetCollate;";
    }

    private function createCityTable(string $tableName, string $charsetCollate): string
    {
        return "CREATE TABLE IF NOT EXISTS `$tableName` (
                `city_id` int unsigned NOT NULL,
                `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `continent_code` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `country_code` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `province_code` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `region_group_id` int unsigned DEFAULT NULL,
                `region_id` int unsigned DEFAULT NULL,
                `type_for` enum('accommodation','package') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `lastRevisionTime` int unsigned NOT NULL,
                UNIQUE KEY `city_id` (`city_id`,`type_for`)
            ) $charsetCollate;";
    }

    private function createRegionGroupTable(string $tableName, string $charsetCollate): string
    {
        return "CREATE TABLE IF NOT EXISTS `$tableName` (
                `region_group_id` int unsigned NOT NULL,
                `region_group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `type_for` enum('accommodation','package') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `searchable` tinyint unsigned NOT NULL DEFAULT '0',
                `lastRevisionTime` int unsigned NOT NULL,
                UNIQUE KEY `region_group_id` (`region_group_id`,`type_for`)
            ) $charsetCollate;";
    }

    private function createRegionTable(string $tableName, string $charsetCollate): string
    {
        return "CREATE TABLE IF NOT EXISTS `$tableName` (
                `region_id` int unsigned NOT NULL,
                `region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `region_group_id` int unsigned NOT NULL,
                `type_for` enum('accommodation','package') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `lastRevisionTime` int unsigned NOT NULL,
                UNIQUE KEY `region_id` (`region_id`,`type_for`)
            ) $charsetCollate;";
    }

    private function createAccommodationTable(string $tableName, string $charsetCollate): string
    {
        return "CREATE TABLE IF NOT EXISTS `$tableName` (
                `accommodation_id` int unsigned NOT NULL,
                `accommodation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `accommodation_code_giata` int unsigned DEFAULT NULL,
                `accommodation_code_iff` int unsigned DEFAULT NULL,
                `continent_code` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `country_code` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `province_code` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `region_group_id` int unsigned DEFAULT NULL,
                `region_id` int unsigned DEFAULT NULL,
                `city_id` int unsigned DEFAULT NULL,
                `type_for` enum('accommodation','package') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `lastRevisionTime` int unsigned NOT NULL,
                UNIQUE KEY `accommodation_id` (`accommodation_id`,`type_for`)
            ) $charsetCollate;";
    }

    private function createAirportTable(string $tableName, string $charsetCollate): string
    {
        return "CREATE TABLE IF NOT EXISTS `$tableName` (
                `airport_code` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `airport` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `category` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `lastRevisionTime` int unsigned NOT NULL,
                PRIMARY KEY (`airport_code`)
            ) $charsetCollate;";
    }

    private function createTouroperatorTable(string $tableName, string $charsetCollate): string
    {
        return "CREATE TABLE IF NOT EXISTS `$tableName` (
                `touroperator_code` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `touroperator` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `type_for` enum('accommodation','package') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                `lastRevisionTime` int unsigned NOT NULL,
                UNIQUE KEY `touroperator_code` (`touroperator_code`,`type_for`)
            ) $charsetCollate;";
    }
}




