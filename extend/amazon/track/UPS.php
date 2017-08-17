<?php

/**
 * Created by PhpStorm.
 * User: yangqihua
 * Date: 2017/8/17
 * Time: 下午10:32
 */

namespace amazon\track;

class UPS
{
    public function getPackageStatus($accessLicenseNumber, $userId, $password, $trackingNumber)
    {
        $data = "<?xml version=\"1.0\"?>
                <AccessRequest xml:lang='en-US'>
                  <AccessLicenseNumber>" . $accessLicenseNumber . "</AccessLicenseNumber>
                  <UserId>" . $userId . "</UserId>
                  <Password>" . $password . "</Password>
                </AccessRequest>
                <?xml version=\"1.0\"?>
                <TrackRequest>
                  <Request>
                    <TransactionReference>
                      <CustomerContext>
                        <InternalKey>blah</InternalKey>
                      </CustomerContext>
                      <XpciVersion>1.0</XpciVersion>
                    </TransactionReference>
                    <RequestAction>Track</RequestAction>
                  </Request>
                  <TrackingNumber>" . $trackingNumber . "</TrackingNumber>
                </TrackRequest>";

        $ch = curl_init("https://www.ups.com/ups.app/xml/Track");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        $data = strstr($result, '<?');


        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser, $data, $vals, $index);
        xml_parser_free($xml_parser);
        $params = array();
        $level = array();
        foreach ($vals as $xml_elem) {
            if ($xml_elem['type'] == 'open') {
                if (array_key_exists('attributes',$xml_elem)) {
                    list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);
                } else {
                    $level[$xml_elem['level']] = $xml_elem['tag'];
                }
            }
            if ($xml_elem['type'] == 'complete') {
                $start_level = 1;
                $php_stmt = '$params';
                while($start_level < $xml_elem['level']) {
                    $php_stmt .= '[$level['.$start_level.']]';
                    $start_level++;
                }
                $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
                eval($php_stmt);
            }
        }
        curl_close($ch);
        return $params;
    }

    function upsTrack($trackingNumber) {
        $data ="<?xml version=\"1.0\"?>
        <AccessRequest xml:lang='en-US'>
                <AccessLicenseNumber>ACCESSNUMBER</AccessLicenseNumber>
                <UserId>USERNAME</UserId>
                <Password>PASSWORD</Password>
        </AccessRequest>
        <?xml version=\"1.0\"?>
        <TrackRequest>
                <Request>
                        <TransactionReference>
                                <CustomerContext>
                                        <InternalKey>blah</InternalKey>
                                </CustomerContext>
                                <XpciVersion>1.0</XpciVersion>
                        </TransactionReference>
                        <RequestAction>Track</RequestAction>
                </Request>
        <TrackingNumber>$trackingNumber</TrackingNumber>
        </TrackRequest>";
        $ch = curl_init("https://www.ups.com/ups.app/xml/Track");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_TIMEOUT, 60);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $result=curl_exec ($ch);
// echo '<!-- '. $result. ' -->';
        $data = strstr($result, '<?');
        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser, $data, $vals, $index);
        xml_parser_free($xml_parser);
        $params = array();
        $level = array();
        foreach ($vals as $xml_elem) {
            if ($xml_elem['type'] == 'open') {
                if (array_key_exists('attributes',$xml_elem)) {
                    list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);
                } else {
                    $level[$xml_elem['level']] = $xml_elem['tag'];
                }
            }
            if ($xml_elem['type'] == 'complete') {
                $start_level = 1;
                $php_stmt = '$params';
                while($start_level < $xml_elem['level']) {
                    $php_stmt .= '[$level['.$start_level.']]';
                    $start_level++;
                }
                $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
                eval($php_stmt);
            }
        }
        curl_close($ch);
        return $params;
    }
}