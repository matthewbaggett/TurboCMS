<?php
namespace TurboCMS\Services;

use GeoIp2\Database\Reader;

class GeoIPLookup
{
    protected $reader;

    public function __construct()
    {
        $this->reader = new Reader(TURBO_ROOT . "/assets/geoip/GeoLite2-City.mmdb");
    }

    /**
     * @param $ipAddr
     *
     * @return \GeoIp2\Model\City
     */
    public function lookupCity($ipAddr)
    {
        return $this->reader->city($ipAddr);
    }
}
