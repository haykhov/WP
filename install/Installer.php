<?php

namespace netzlodern\pwr\install;

class Installer
{
    public function __construct()
    {
        include_once PWR_PATH . 'install/CreateTable.php';
    }

    public function install(): void
    {
        $createTableObject = new CreateTable();
        $createTableObject->pwrCreateDatabaseTables();
    }
}
