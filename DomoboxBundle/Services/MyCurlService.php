<?php

namespace Domotique\DomoboxBundle\Services;


class MyCurlService {

    public function getToUrl($restUrl)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $restUrl);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        curl_close($ch);

            return $response;
    }
}
