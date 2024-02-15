<?php

namespace netzlodern\pwr\actions;

use netzlodern\pwr\api\TravelPool;
use netzlodern\pwr\api\TravelOffer;

class SyncData
{
    public function syncData(): void
    {
        $this->syncTravelPools();
        $this->syncTravelOffers();
    }

    private function syncTravelPools(): void
    {
        $travel_pools = new TravelPool();
        $travel_pools->fetchTravelPools();
    }

    private function syncTravelOffers(): void
    {
        $travel_offers = new TravelOffer();
        $travel_offers->fetchTravelOffers();
    }
}
