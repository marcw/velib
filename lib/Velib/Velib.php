<?php

namespace Velib;

use Buzz\Browser;

/**
 * Velib
 *
 * @author Marc Weistroff <marc.weistroff@gmail.com>
 */
class Velib
{
    protected $stationDetailEntryPoint = 'http://www.velib.paris.fr/service/stationdetails';
    protected $stationListEntryPoint = 'http://www.velib.paris.fr/service/carto';
    private $browser;

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function getBrowser()
    {
        return $browser;
    }

    /**
     * Call Velib webservice and returns details about a station.
     *
     * @param integer $id
     * @return null|array
     */
    public function stationDetail($id)
    {
        $url = sprintf('%s/%d', $this->stationDetailEntryPoint, $id);
        $xml = simplexml_load_string($this->browser->get($url)->getContent());
        if (!$xml) {
            return null;
        }

        $values = array(
            'available' => (int) $xml->available,
            'free'      => (int) $xml->free,
            'total'     => (int) $xml->total,
            'ticket'    => (int) $xml->ticket,
        );

        if ($values['available'] === 0 && $values['free'] === 0 && $values['total'] === 0 && $values['ticket'] === 0) {
            return null;
        }

        return $values;
    }

    /**
     * stationList
     *
     * @return
     */
    public function stationList()
    {
        $xml = $this->browser->get($this->stationListEntryPoint)->getContent();
        $xml = simplexml_load_string($xml);
        if (!$xml) {
            return null;
        }

        $stations = array();
        foreach ($xml->markers->marker as $marker) {
            $attributes = $marker->attributes();
            $station = array(
                'address'     => (string) $attributes['address'],
                'bonus'       => (int) $attributes['bonus'],
                'fullAddress' => (string) $attributes['fullAddress'],
                'lat'         => (float) $attributes['lat'],
                'lng'         => (float) $attributes['lng'],
                'name'        => (string) $attributes['name'],
                'open'        => (int) $attributes['open'],
            );
            $id = (int) $attributes['number'];
            $stations[$id] = $station;
        }

        return $stations;
    }
}
