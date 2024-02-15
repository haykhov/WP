<?php

namespace netzlodern\pwr\api;

use netzlodern\pwr\models\Country;
use netzlodern\pwr\models\Region;
use netzlodern\pwr\models\RegionGroup;
use netzlodern\pwr\models\City;
use netzlodern\pwr\models\Accommodation;
use netzlodern\pwr\models\Airport;

class TravelMeta extends BaseApi
{
    private $apiEndpoint = "/api/v1/tourism/ibe/alibe/package/meta";

    public function fetchTravelMeta(): void
    {
        $data = $this->getData($this->apiEndpoint);
        $this->storeTravelMeta($data);
    }

    private function storeTravelMeta(array $metaObject): void
    {
        foreach ($metaObject as $key => $eachMeta) {
            switch ($key) {
                case 'countries':
                    Country::batchReplace($eachMeta);
                    break;
                case 'region-groups':
                    RegionGroup::batchReplace($eachMeta);
                    break;
                case 'regions':
                    Region::batchReplace($eachMeta);
                    break;
                case 'cities':
                    City::batchReplace($eachMeta);
                    break;
                case 'accommodations':
                    Accommodation::batchReplace($eachMeta);
                    break;
                case 'airports':
                    Airport::batchReplace($eachMeta);
                    break;
            };
        }
    }
}
