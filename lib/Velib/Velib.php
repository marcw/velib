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

    public function stationDetail($id)
    {
        $url = sprintf('%s/%d', $this->stationDetailEntryPoint, $id);

        return $this->browser->get($url)->getContent();
    }

    public function stationList()
    {
        return $this->browser->get($this->stationListEntryPoint)->getContent();
    }
}
