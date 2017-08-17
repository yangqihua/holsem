<?php

/**
 * Created by PhpStorm.
 * User: yangqihua
 * Date: 2017/8/17
 * Time: 下午10:32
 */

namespace amazon\track;

class USPS
{
    public function getPackageStatus($userId, $trackingNumber)
    {
        $url = "http://production.shippingapis.com/shippingAPI.dll";
        $service = "TrackV2";
        $xml = rawurlencode("<TrackRequest USERID=".$userId."><TrackID ID='" . $trackingNumber . "'></TrackID></TrackRequest>");
        $request = $url . "?API=" . $service . "&XML=" . $xml;
        // send the POST values to USPS
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // parameters to post

        $result = curl_exec($ch);
        curl_close($ch);

        $response = new \SimpleXMLElement($result);
        print_r($result);
        $deliveryStatus = $response->TrackResponse->TrackSummary->Status;
        echo $deliveryStatus;
    }
}