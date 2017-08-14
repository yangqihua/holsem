<?php
/*******************************************************************************
 * Copyright 2009-2017 Amazon Services. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 *
 * You may not use this file except in compliance with the License.
 * You may obtain a copy of the License at: http://aws.amazon.com/apache2.0
 * This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 *******************************************************************************
 * PHP Version 5
 * @category Amazon
 * @package  Marketplace Web Service Orders
 * @version  2013-09-01
 * Library Version: 2017-02-22
 * Generated: Thu Mar 02 12:41:08 UTC 2017
 */

namespace amazon\order;

use Exception;

use amazon\order\model\ListOrderRequest;
use amazon\order\model\ListOrdersResponse;
use amazon\order\model\GetOrderRequest;
use amazon\order\model\GetOrderResponse;
use amazon\order\model\GetServiceStatusRequest;
use amazon\order\model\GetServiceStatusResponse;
use amazon\order\model\ListOrderItemsRequest;
use amazon\order\model\ListOrderItemsResponse;
use amazon\order\model\ListOrderItemsByNextTokenRequest;
use amazon\order\model\ListOrderItemsByNextTokenResponse;
use amazon\order\model\ListOrdersByNextTokenRequest;
use amazon\order\model\ListOrdersByNextTokenResponse;
use amazon\order\model\ResponseHeaderMetadata;

class OrderClient implements OrderInterface
{

    const SERVICE_VERSION = '2013-09-01';
    const MWS_CLIENT_VERSION = '2017-02-22';

    /** @var string */
    private $_awsAccessKeyId = null;

    /** @var string */
    private $_awsSecretAccessKey = null;

    /** @var array */
    private $_config = array('ServiceURL' => null,
        'UserAgent' => 'order PHP5 Library',
        'SignatureVersion' => 2,
        'SignatureMethod' => 'HmacSHA256',
        'ProxyHost' => null,
        'ProxyPort' => -1,
        'ProxyUsername' => null,
        'ProxyPassword' => null,
        'MaxErrorRetry' => 3,
        'Headers' => array()
    );


    /**
     * Get Order
     * This operation takes up to 50 order ids and returns the corresponding orders.
     *
     * @param mixed $request array of parameters for GetOrder request or GetOrder object itself
     * @see GetOrderRequest
     * @return GetOrderResponse
     *
     * @throws OrderException
     */
    public function getOrder($request)
    {
        if (!($request instanceof GetOrderRequest)) {
            require_once(dirname(__FILE__) . '/model/GetOrderRequest.php');
            $request = new GetOrderRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetOrder';
        $httpResponse = $this->_invoke($parameters);

        require_once(dirname(__FILE__) . '/model/GetOrderResponse.php');
        $response = GetOrderResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetOrderRequest to name value pairs
     */
    private function _convertGetOrder($request)
    {

        $parameters = array();
        $parameters['Action'] = 'GetOrder';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] = $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }
        if ($request->isSetAmazonOrderId()) {
            $parameters['AmazonOrderId'] = $request->getAmazonOrderId();
        }

        return $parameters;
    }


    /**
     * Get Service Status
     * Returns the service status of a particular MWS API section. The operation
     *        takes no input.
     *
     * @param mixed $request array of parameters for GetServiceStatus request or GetServiceStatus object itself
     * @see GetServiceStatusRequest
     * @return GetServiceStatusResponse
     *
     * @throws OrderException
     */
    public function getServiceStatus($request)
    {
        if (!($request instanceof GetServiceStatusRequest)) {
            require_once(dirname(__FILE__) . '/model/GetServiceStatusRequest.php');
            $request = new GetServiceStatusRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetServiceStatus';
        $httpResponse = $this->_invoke($parameters);

        require_once(dirname(__FILE__) . '/model/GetServiceStatusResponse.php');
        $response = GetServiceStatusResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetServiceStatusRequest to name value pairs
     */
    private function _convertGetServiceStatus($request)
    {

        $parameters = array();
        $parameters['Action'] = 'GetServiceStatus';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] = $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }

        return $parameters;
    }


    /**
     * List Order Items
     * This operation can be used to list the items of the order indicated by the
     *         given order id (only a single Amazon order id is allowed).
     *
     * @param mixed $request array of parameters for ListOrderItems request or ListOrderItems object itself
     * @see ListOrderItemsRequest
     * @return ListOrderItemsResponse
     *
     * @throws OrderException
     */
    public function listOrderItems($request)
    {
        if (!($request instanceof ListOrderItemsRequest)) {
            require_once(dirname(__FILE__) . '/model/ListOrderItemsRequest.php');
            $request = new ListOrderItemsRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'ListOrderItems';
        $httpResponse = $this->_invoke($parameters);

        require_once(dirname(__FILE__) . '/model/ListOrderItemsResponse.php');
        $response = ListOrderItemsResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert ListOrderItemsRequest to name value pairs
     */
    private function _convertListOrderItems($request)
    {

        $parameters = array();
        $parameters['Action'] = 'ListOrderItems';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] = $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }
        if ($request->isSetAmazonOrderId()) {
            $parameters['AmazonOrderId'] = $request->getAmazonOrderId();
        }

        return $parameters;
    }


    /**
     * List Order Items By Next Token
     * If ListOrderItems cannot return all the order items in one go, it will
     *         provide a nextToken. That nextToken can be used with this operation to
     *         retrive the next batch of items for that order.
     *
     * @param mixed $request array of parameters for ListOrderItemsByNextToken request or ListOrderItemsByNextToken object itself
     * @see ListOrderItemsByNextTokenRequest
     * @return ListOrderItemsByNextTokenResponse
     *
     * @throws OrderException
     */
    public function listOrderItemsByNextToken($request)
    {
        if (!($request instanceof ListOrderItemsByNextTokenRequest)) {
            require_once(dirname(__FILE__) . '/model/ListOrderItemsByNextTokenRequest.php');
            $request = new ListOrderItemsByNextTokenRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'ListOrderItemsByNextToken';
        $httpResponse = $this->_invoke($parameters);

        require_once(dirname(__FILE__) . '/model/ListOrderItemsByNextTokenResponse.php');
        $response = ListOrderItemsByNextTokenResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert ListOrderItemsByNextTokenRequest to name value pairs
     */
    private function _convertListOrderItemsByNextToken($request)
    {

        $parameters = array();
        $parameters['Action'] = 'ListOrderItemsByNextToken';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] = $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }
        if ($request->isSetNextToken()) {
            $parameters['NextToken'] = $request->getNextToken();
        }

        return $parameters;
    }


    /**
     * List Orders
     * ListOrders can be used to find orders that meet the specified criteria.
     *
     * @param mixed $request array of parameters for ListOrders request or ListOrders object itself
     * @see ListOrderRequest
     * @return ListOrdersResponse
     *
     * @throws OrderException
     */
    public function listOrders($request)
    {
        if (!($request instanceof ListOrderRequest)) {
            $request = new ListOrderRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'ListOrders';
        $httpResponse = $this->_invoke($parameters);

        $response = ListOrdersResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert ListOrderRequest to name value pairs
     */
    private function _convertListOrders($request)
    {

        $parameters = array();
        $parameters['Action'] = 'ListOrders';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] = $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }
        if ($request->isSetCreatedAfter()) {
            $parameters['CreatedAfter'] = $request->getCreatedAfter();
        }
        if ($request->isSetCreatedBefore()) {
            $parameters['CreatedBefore'] = $request->getCreatedBefore();
        }
        if ($request->isSetLastUpdatedAfter()) {
            $parameters['LastUpdatedAfter'] = $request->getLastUpdatedAfter();
        }
        if ($request->isSetLastUpdatedBefore()) {
            $parameters['LastUpdatedBefore'] = $request->getLastUpdatedBefore();
        }
        if ($request->isSetOrderStatus()) {
            $parameters['OrderStatus'] = $request->getOrderStatus();
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] = $request->getMarketplaceId();
        }
        if ($request->isSetFulfillmentChannel()) {
            $parameters['FulfillmentChannel'] = $request->getFulfillmentChannel();
        }
        if ($request->isSetPaymentMethod()) {
            $parameters['PaymentMethod'] = $request->getPaymentMethod();
        }
        if ($request->isSetBuyerEmail()) {
            $parameters['BuyerEmail'] = $request->getBuyerEmail();
        }
        if ($request->isSetSellerOrderId()) {
            $parameters['SellerOrderId'] = $request->getSellerOrderId();
        }
        if ($request->isSetMaxResultsPerPage()) {
            $parameters['MaxResultsPerPage'] = $request->getMaxResultsPerPage();
        }
        if ($request->isSetTFMShipmentStatus()) {
            $parameters['TFMShipmentStatus'] = $request->getTFMShipmentStatus();
        }

        return $parameters;
    }


    /**
     * List Orders By Next Token
     * If ListOrders returns a nextToken, thus indicating that there are more orders
     *         than returned that matched the given filter criteria, ListOrdersByNextToken
     *         can be used to retrieve those other orders using that nextToken.
     *
     * @param mixed $request array of parameters for ListOrdersByNextToken request or ListOrdersByNextToken object itself
     * @see ListOrdersByNextTokenRequest
     * @return ListOrdersByNextTokenResponse
     *
     * @throws OrderException
     */
    public function listOrdersByNextToken($request)
    {
        if (!($request instanceof ListOrdersByNextTokenRequest)) {
            require_once(dirname(__FILE__) . '/model/ListOrdersByNextTokenRequest.php');
            $request = new ListOrdersByNextTokenRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'ListOrdersByNextToken';
        $httpResponse = $this->_invoke($parameters);

        require_once(dirname(__FILE__) . '/model/ListOrdersByNextTokenResponse.php');
        $response = ListOrdersByNextTokenResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert ListOrdersByNextTokenRequest to name value pairs
     */
    private function _convertListOrdersByNextToken($request)
    {

        $parameters = array();
        $parameters['Action'] = 'ListOrdersByNextToken';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] = $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
        }
        if ($request->isSetNextToken()) {
            $parameters['NextToken'] = $request->getNextToken();
        }

        return $parameters;
    }


    /**
     * Construct new Client
     *
     * @param string $awsAccessKeyId AWS Access Key ID
     * @param string $awsSecretAccessKey AWS Secret Access Key
     * @param $applicationName
     * @param $applicationVersion
     * @param array $config configuration options.
     * Valid configuration options are:
     * <ul>
     * <li>ServiceURL</li>
     * <li>UserAgent</li>
     * <li>SignatureVersion</li>
     * <li>TimesRetryOnError</li>
     * <li>ProxyHost</li>
     * <li>ProxyPort</li>
     * <li>ProxyUsername<li>
     * <li>ProxyPassword<li>
     * <li>MaxErrorRetry</li>
     * </ul>
     */
    public function __construct($awsAccessKeyId, $awsSecretAccessKey, $applicationName, $applicationVersion, $config = null)
    {
        if (PHP_VERSION_ID < 50600) {
            iconv_set_encoding('input_encoding', 'UTF-8');
            iconv_set_encoding('output_encoding', 'UTF-8');
            iconv_set_encoding('internal_encoding', 'UTF-8');
        } else {
            ini_set('default_charset', 'UTF-8');
        }

        $this->_awsAccessKeyId = $awsAccessKeyId;
        $this->_awsSecretAccessKey = $awsSecretAccessKey;
        if (!is_null($config)) $this->_config = array_merge($this->_config, $config);
        $this->setUserAgentHeader($applicationName, $applicationVersion);
    }

    private function setUserAgentHeader(
        $applicationName,
        $applicationVersion,
        $attributes = null)
    {

        if (is_null($attributes)) {
            $attributes = array();
        }

        $this->_config['UserAgent'] =
            $this->constructUserAgentHeader($applicationName, $applicationVersion, $attributes);
    }

    private function constructUserAgentHeader($applicationName, $applicationVersion, $attributes = null)
    {
        if (is_null($applicationName) || $applicationName === "") {
            throw new \InvalidArgumentException('$applicationName cannot be null');
        }

        if (is_null($applicationVersion) || $applicationVersion === "") {
            throw new \InvalidArgumentException('$applicationVersion cannot be null');
        }

        $userAgent =
            $this->quoteApplicationName($applicationName)
            . '/'
            . $this->quoteApplicationVersion($applicationVersion);

        $userAgent .= ' (';
        $userAgent .= 'Language=PHP/' . phpversion();
        $userAgent .= '; ';
        $userAgent .= 'Platform=' . php_uname('s') . '/' . php_uname('m') . '/' . php_uname('r');
        $userAgent .= '; ';
        $userAgent .= 'MWSClientVersion=' . self::MWS_CLIENT_VERSION;

        foreach ($attributes as $key => $value) {
            if (empty($value)) {
                throw new \InvalidArgumentException("Value for $key cannot be null or empty.");
            }

            $userAgent .= '; '
                . $this->quoteAttributeName($key)
                . '='
                . $this->quoteAttributeValue($value);
        }

        $userAgent .= ')';

        return $userAgent;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' character.
     * @param $s
     * @return string
     */
    private function collapseWhitespace($s)
    {
        return preg_replace('/ {2,}|\s/', ' ', $s);
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '/' characters from a string.
     * @param $s
     * @return string
     */
    private function quoteApplicationName($s)
    {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\//', '\\/', $quotedString);

        return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '(' characters from a string.
     *
     * @param $s
     * @return string
     */
    private function quoteApplicationVersion($s)
    {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\\(/', '\\(', $quotedString);

        return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '=' characters from a string.
     *
     * @param $s
     * @return unknown_type
     */
    private function quoteAttributeName($s)
    {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\\=/', '\\=', $quotedString);

        return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape ';', '\',
     * and ')' characters from a string.
     *
     * @param $s
     * @return unknown_type
     */
    private function quoteAttributeValue($s)
    {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\\;/', '\\;', $quotedString);
        $quotedString = preg_replace('/\\)/', '\\)', $quotedString);

        return $quotedString;
    }


    // Private API ------------------------------------------------------------//

    /**
     * Invoke request and return response
     * @param array $parameters
     * @return array
     * @throws OrderException
     */
    private function _invoke(array $parameters)
    {
        try {
            if (empty($this->_config['ServiceURL'])) {
//                require_once(dirname(__FILE__) . '/OrderException.php');
                throw new OrderException(
                    array('ErrorCode' => 'InvalidServiceURL',
                        'Message' => "Missing serviceUrl configuration value. You may obtain a list of valid MWS URLs by consulting the MWS Developer's Guide, or reviewing the sample code published along side this library."));
            }
            $parameters = $this->_addRequiredParameters($parameters);
            $retries = 0;
            for (; ;) {
                $response = $this->_httpPost($parameters);
                $status = $response['Status'];
                if ($status == 200) {
                    return array('ResponseBody' => $response['ResponseBody'],
                        'ResponseHeaderMetadata' => $response['ResponseHeaderMetadata']);
                }
                if ($status == 500 && $this->_pauseOnRetry(++$retries)) {
                    continue;
                }
                throw $this->_reportAnyErrors($response['ResponseBody'],
                    $status, $response['ResponseHeaderMetadata']);
            }
        } catch (OrderException $se) {
            throw $se;
        } catch (\Exception $t) {
            require_once(dirname(__FILE__) . '/OrderException.php');
            throw new OrderException(array('Exception' => $t, 'Message' => $t->getMessage()));
        }
    }

    /**
     * Look for additional error strings in the response and return formatted exception
     */
    private function _reportAnyErrors($responseBody, $status, $responseHeaderMetadata, Exception $e = null)
    {
        $exProps = array();
        $exProps["StatusCode"] = $status;
        $exProps["ResponseHeaderMetadata"] = $responseHeaderMetadata;

        libxml_use_internal_errors(true);  // Silence XML parsing errors
        $xmlBody = simplexml_load_string($responseBody);

        if ($xmlBody !== false) {  // Check XML loaded without errors
            $exProps["XML"] = $responseBody;
            $exProps["ErrorCode"] = $xmlBody->Error->Code;
            $exProps["Message"] = $xmlBody->Error->Message;
            $exProps["ErrorType"] = !empty($xmlBody->Error->Type) ? $xmlBody->Error->Type : "Unknown";
            $exProps["RequestId"] = !empty($xmlBody->RequestID) ? $xmlBody->RequestID : $xmlBody->RequestId; // 'd' in RequestId is sometimes capitalized
        } else { // We got bad XML in response, just throw a generic exception
            $exProps["Message"] = "Internal Error";
        }

        require_once(dirname(__FILE__) . '/OrderException.php');
        return new OrderException($exProps);
    }


    /**
     * Perform HTTP post with exponential retries on error 500 and 503
     *
     */
    private function _httpPost(array $parameters)
    {
        $config = $this->_config;
        $query = $this->_getParametersAsString($parameters);
        $url = parse_url($config['ServiceURL']);
        $uri = array_key_exists('path', $url) ? $url['path'] : null;
        if (!isset ($uri)) {
            $uri = "/";
        }

        switch ($url['scheme']) {
            case 'https':
                $scheme = 'https://';
                $port = isset($url['port']) ? $url['port'] : 443;
                break;
            default:
                $scheme = 'http://';
                $port = isset($url['port']) ? $url['port'] : 80;
        }

        $allHeaders = $config['Headers'];
        $allHeaders['Content-Type'] = "application/x-www-form-urlencoded; charset=utf-8"; // We need to make sure to set utf-8 encoding here
        $allHeaders['Expect'] = null; // Don't expect 100 Continue
        $allHeadersStr = array();
        foreach ($allHeaders as $name => $val) {
            $str = $name . ": ";
            if (isset($val)) {
                $str = $str . $val;
            }
            $allHeadersStr[] = $str;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $scheme . $url['host'] . $uri);
        curl_setopt($ch, CURLOPT_PORT, $port);
        $this->setSSLCurlOptions($ch);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->_config['UserAgent']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeadersStr);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($config['ProxyHost'] != null && $config['ProxyPort'] != -1) {
            curl_setopt($ch, CURLOPT_PROXY, $config['ProxyHost'] . ':' . $config['ProxyPort']);
        }
        if ($config['ProxyUsername'] != null && $config['ProxyPassword'] != null) {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $config['ProxyUsername'] . ':' . $config['ProxyPassword']);
        }

//        $response = "";
        $response = curl_exec($ch);
        // TODO : 设置模拟数据

/*
         $response = 'HTTP/1.1 200 OK
Server: Server
Date: Sat, 12 Aug 2017 10:38:27 GMT
Content-Type: text/xml
Content-Length: 18708
Connection: keep-alive
X-Amz-Date: Sat, 12 Aug 2017 10:38:27 GMT
x-amzn-Authorization: AAA SignedHeaders=X-Amz-Date, identity=com.amazon.aaa.MarketplaceWebServiceOrders.AndromedaControlService.amzn1.aaa.id.qhz3ylg755gkejyk5sh44qm3wy.Default/1, Signed=true, Encrypted=false, Signature=t97ZYu71iH95h+c/2K7CWuxxe05+JAQT+Mgf0UchfP4=, Algorithm=HmacSHA256
x-mws-request-id: 0a3b9953-8336-497b-bee9-bc60ef7a74ac
x-mws-timestamp: 2017-08-12T10:38:27.496Z
x-mws-response-context: crpcFGnpfMrC09vyozG64rpn2gS0H4me9p1FFT47NRbxcEVL9n/nu6RsRK+EFvUVyV2+p9a25IQ=
Vary: Accept-Encoding,User-Agent

<?xml version="1.0"?>
<ListOrdersResponse xmlns="https://mws.amazonservices.com/Orders/2013-09-01">
  <ListOrdersResult>
    <Orders>
      <Order>
        <LatestShipDate>2017-06-26T15:14:10Z</LatestShipDate>
        <OrderType>StandardOrder</OrderType>
        <PurchaseDate>2017-06-25T16:19:35Z</PurchaseDate>
        <AmazonOrderId>119-2285529-6164230</AmazonOrderId>
        <BuyerEmail>2jfy2jw1bj0x0zm2@marketplace.amazon.com</BuyerEmail>
        <IsReplacementOrder>false</IsReplacementOrder>
        <LastUpdateDate>2017-06-26T15:25:46Z</LastUpdateDate>
        <NumberOfItemsShipped>2</NumberOfItemsShipped>
        <ShipServiceLevel>SecondDay</ShipServiceLevel>
        <OrderStatus>Shipped</OrderStatus>
        <SalesChannel>Amazon.com</SalesChannel>
        <IsBusinessOrder>false</IsBusinessOrder>
        <NumberOfItemsUnshipped>0</NumberOfItemsUnshipped>
        <PaymentMethodDetails>
          <PaymentMethodDetail>Standard</PaymentMethodDetail>
        </PaymentMethodDetails>
        <BuyerName>Giselle Morris</BuyerName>
        <OrderTotal>
          <CurrencyCode>USD</CurrencyCode>
          <Amount>49.90</Amount>
        </OrderTotal>
        <IsPremiumOrder>false</IsPremiumOrder>
        <EarliestShipDate>2017-06-26T15:14:10Z</EarliestShipDate>
        <MarketplaceId>ATVPDKIKX0DER</MarketplaceId>
        <FulfillmentChannel>AFN</FulfillmentChannel>
        <PaymentMethod>Other</PaymentMethod>
        <ShippingAddress>
          <StateOrRegion>VA</StateOrRegion>
          <City>Ashburn</City>
          <CountryCode>US</CountryCode>
          <PostalCode>20147-4170</PostalCode>
          <Name>Giselle Morris</Name>
          <AddressLine1>43905 Hickory Corner Ter Unit 111</AddressLine1>
        </ShippingAddress>
        <IsPrime>false</IsPrime>
        <ShipmentServiceLevelCategory>SecondDay</ShipmentServiceLevelCategory>
        <SellerOrderId>113-2285529-6164230</SellerOrderId>
      </Order>
      <Order>
        <LatestShipDate>2017-06-25T19:35:09Z</LatestShipDate>
        <OrderType>StandardOrder</OrderType>
        <PurchaseDate>2017-06-25T16:20:41Z</PurchaseDate>
        <AmazonOrderId>112-0757259-2304252</AmazonOrderId>
        <BuyerEmail>2cdgsgrpx9w9vfh4@marketplace.amazon.com</BuyerEmail>
        <IsReplacementOrder>false</IsReplacementOrder>
        <LastUpdateDate>2017-06-25T19:42:26Z</LastUpdateDate>
        <NumberOfItemsShipped>1</NumberOfItemsShipped>
        <ShipServiceLevel>Expedited</ShipServiceLevel>
        <OrderStatus>Shipped</OrderStatus>
        <SalesChannel>Amazon.com</SalesChannel>
        <IsBusinessOrder>false</IsBusinessOrder>
        <NumberOfItemsUnshipped>0</NumberOfItemsUnshipped>
        <PaymentMethodDetails>
          <PaymentMethodDetail>Standard</PaymentMethodDetail>
        </PaymentMethodDetails>
        <BuyerName>Joi Powell</BuyerName>
        <OrderTotal>
          <CurrencyCode>USD</CurrencyCode>
          <Amount>28.89</Amount>
        </OrderTotal>
        <IsPremiumOrder>false</IsPremiumOrder>
        <EarliestShipDate>2017-06-25T19:35:09Z</EarliestShipDate>
        <MarketplaceId>ATVPDKIKX0DER</MarketplaceId>
        <FulfillmentChannel>AFN</FulfillmentChannel>
        <PaymentMethod>Other</PaymentMethod>
        <ShippingAddress>
          <StateOrRegion>TX</StateOrRegion>
          <City>LEANDER</City>
          <CountryCode>US</CountryCode>
          <PostalCode>78641-7779</PostalCode>
          <Name>Joi powell</Name>
          <AddressLine1>1104 BARCLAY DR</AddressLine1>
        </ShippingAddress>
        <IsPrime>false</IsPrime>
        <ShipmentServiceLevelCategory>Expedited</ShipmentServiceLevelCategory>
        <SellerOrderId>112-0757259-2304252</SellerOrderId>
      </Order>
      <Order>
        <LatestShipDate>2017-06-25T21:16:01Z</LatestShipDate>
        <OrderType>StandardOrder</OrderType>
        <PurchaseDate>2017-06-25T16:21:30Z</PurchaseDate>
        <AmazonOrderId>111-2813631-7376259</AmazonOrderId>
        <BuyerEmail>ppj8hqt2lxxzpym@marketplace.amazon.com</BuyerEmail>
        <IsReplacementOrder>false</IsReplacementOrder>
        <LastUpdateDate>2017-06-25T21:29:31Z</LastUpdateDate>
        <NumberOfItemsShipped>1</NumberOfItemsShipped>
        <ShipServiceLevel>SecondDay</ShipServiceLevel>
        <OrderStatus>Shipped</OrderStatus>
        <SalesChannel>Amazon.com</SalesChannel>
        <IsBusinessOrder>false</IsBusinessOrder>
        <NumberOfItemsUnshipped>0</NumberOfItemsUnshipped>
        <PaymentMethodDetails>
          <PaymentMethodDetail>Standard</PaymentMethodDetail>
        </PaymentMethodDetails>
        <BuyerName>carole van Ness</BuyerName>
        <OrderTotal>
          <CurrencyCode>USD</CurrencyCode>
          <Amount>27.99</Amount>
        </OrderTotal>
        <IsPremiumOrder>false</IsPremiumOrder>
        <EarliestShipDate>2017-06-25T21:16:01Z</EarliestShipDate>
        <MarketplaceId>ATVPDKIKX0DER</MarketplaceId>
        <FulfillmentChannel>AFN</FulfillmentChannel>
        <PaymentMethod>Other</PaymentMethod>
        <ShippingAddress>
          <StateOrRegion>TN</StateOrRegion>
          <City>CROSSVILLE</City>
          <CountryCode>US</CountryCode>
          <PostalCode>38571</PostalCode>
          <Name>CAROLE VAN NESS</Name>
          <AddressLine1>884 POPLAR DR</AddressLine1>
        </ShippingAddress>
        <IsPrime>false</IsPrime>
        <ShipmentServiceLevelCategory>SecondDay</ShipmentServiceLevelCategory>
        <SellerOrderId>111-2813631-7376259</SellerOrderId>
      </Order>
      <Order>
        <LatestShipDate>2017-06-27T14:55:34Z</LatestShipDate>
        <OrderType>StandardOrder</OrderType>
        <PurchaseDate>2017-06-25T16:52:14Z</PurchaseDate>
        <AmazonOrderId>114-0125758-7766616</AmazonOrderId>
        <BuyerEmail>tw13999ymwpm507@marketplace.amazon.com</BuyerEmail>
        <IsReplacementOrder>false</IsReplacementOrder>
        <LastUpdateDate>2017-06-27T14:56:46Z</LastUpdateDate>
        <NumberOfItemsShipped>1</NumberOfItemsShipped>
        <ShipServiceLevel>Standard</ShipServiceLevel>
        <OrderStatus>Shipped</OrderStatus>
        <SalesChannel>Amazon.com</SalesChannel>
        <IsBusinessOrder>false</IsBusinessOrder>
        <NumberOfItemsUnshipped>0</NumberOfItemsUnshipped>
        <PaymentMethodDetails>
          <PaymentMethodDetail>Standard</PaymentMethodDetail>
        </PaymentMethodDetails>
        <BuyerName>Wilfredo Valdez</BuyerName>
        <OrderTotal>
          <CurrencyCode>USD</CurrencyCode>
          <Amount>28.89</Amount>
        </OrderTotal>
        <IsPremiumOrder>false</IsPremiumOrder>
        <EarliestShipDate>2017-06-27T14:55:34Z</EarliestShipDate>
        <MarketplaceId>ATVPDKIKX0DER</MarketplaceId>
        <FulfillmentChannel>AFN</FulfillmentChannel>
        <PaymentMethod>Other</PaymentMethod>
        <ShippingAddress>
          <StateOrRegion>NJ</StateOrRegion>
          <City>EDISON</City>
          <CountryCode>US</CountryCode>
          <PostalCode>08837-4001</PostalCode>
          <Name>Wilfredo Valdez</Name>
          <AddressLine1>18 REDFIELD VLG APT B2</AddressLine1>
        </ShippingAddress>
        <IsPrime>false</IsPrime>
        <ShipmentServiceLevelCategory>Standard</ShipmentServiceLevelCategory>
        <SellerOrderId>114-0125758-7766616</SellerOrderId>
      </Order>
      <Order>
        <LatestShipDate>2017-06-27T03:55:54Z</LatestShipDate>
        <OrderType>StandardOrder</OrderType>
        <PurchaseDate>2017-06-25T17:02:16Z</PurchaseDate>
        <AmazonOrderId>114-7261164-8084237</AmazonOrderId>
        <BuyerEmail>dhw86qycql8t2hq@marketplace.amazon.com</BuyerEmail>
        <IsReplacementOrder>false</IsReplacementOrder>
        <LastUpdateDate>2017-06-27T03:58:14Z</LastUpdateDate>
        <NumberOfItemsShipped>1</NumberOfItemsShipped>
        <ShipServiceLevel>SecondDay</ShipServiceLevel>
        <OrderStatus>Shipped</OrderStatus>
        <SalesChannel>Amazon.com</SalesChannel>
        <IsBusinessOrder>false</IsBusinessOrder>
        <NumberOfItemsUnshipped>0</NumberOfItemsUnshipped>
        <PaymentMethodDetails>
          <PaymentMethodDetail>Standard</PaymentMethodDetail>
        </PaymentMethodDetails>
        <BuyerName>fatima durrani</BuyerName>
        <OrderTotal>
          <CurrencyCode>USD</CurrencyCode>
          <Amount>24.95</Amount>
        </OrderTotal>
        <IsPremiumOrder>false</IsPremiumOrder>
        <EarliestShipDate>2017-06-27T03:55:54Z</EarliestShipDate>
        <MarketplaceId>ATVPDKIKX0DER</MarketplaceId>
        <FulfillmentChannel>AFN</FulfillmentChannel>
        <PaymentMethod>Other</PaymentMethod>
        <ShippingAddress>
          <StateOrRegion>CA</StateOrRegion>
          <City>TRABUCO CANYON</City>
          <CountryCode>US</CountryCode>
          <PostalCode>92679-3316</PostalCode>
          <Name>Saud Khan</Name>
          <AddressLine1>32163 FALL RIVER RD</AddressLine1>
        </ShippingAddress>
        <IsPrime>false</IsPrime>
        <ShipmentServiceLevelCategory>SecondDay</ShipmentServiceLevelCategory>
        <SellerOrderId>114-7261164-8084237</SellerOrderId>
      </Order>
    </Orders>
    <CreatedBefore>2017-06-27T02:15:47Z</CreatedBefore>
    <NextToken>uRjB0Lv5TPiaJqJYLDm0ZIfVkJJPpovRjDF/FEEoYzlUYoV2Fj3SPOIXexrPtu8DqBXdLk4iogwM3B3rhUPcucj0Nx3CIFZQ8HjECx1NBejbPhza5zRJeiJ0wMvlylZkWQWPqGlbsnM84qdTrqNK46Ql7+GKWDK+1Dlb+kWstRY1ZUssXEbCmLuZIF9n45mtnrZ4AbBdBTeicp5jJPQPcgCy5/GuGI4OLzyB960RsbIZEWUDFvtT5/+/30YnINCicSXxQlCMygTWI2wUZuwM9bCXSaG7w7Y22ZC4fGUFmXZk2azcFx+lU0cPwmPe3XRJAYi8wcR8BAOmFwvrdvaYOQPazVAPprUVmcoi1HxBtXhm+zLRbZiM4iXat5EZIVetmXRP1ZmfVN+pIz64eh4KdwG5+NbfkhTuZLQdmpJw+SHu4zytwGpfkKyKPUWM6vqZxhlcDuLt9h1Qb+e1jKKmZOHMrteUOuF/hMUkPY9nj5g=</NextToken>
  </ListOrdersResult>
  <ResponseMetadata>
    <RequestId>0a3b9953-8336-497b-bee9-bc60ef7a74ac</RequestId>
  </ResponseMetadata>
</ListOrdersResponse>';
*/

        if ($response === false) {
            require_once(dirname(__FILE__) . '/OrderException.php');
            $exProps["Message"] = curl_error($ch);
            $exProps["ErrorType"] = "HTTP";
            curl_close($ch);
            throw new OrderException($exProps);
        }

        curl_close($ch);
        return $this->_extractHeadersAndBody($response);
    }

    /**
     * This method will attempt to extract the headers and body of our response.
     * We need to split the raw response string by 2 'CRLF's.  2 'CRLF's should indicate the separation of the response header
     * from the response body.  However in our case we have some circumstances (certain client proxies) that result in
     * multiple responses concatenated.  We could encounter a response like
     *
     * HTTP/1.1 100 Continue
     *
     * HTTP/1.1 200 OK
     * Date: Tue, 01 Apr 2014 13:02:51 GMT
     * Content-Type: text/html; charset=iso-8859-1
     * Content-Length: 12605
     *
     * ... body ..
     *
     * This method will throw away extra response status lines and attempt to find the first full response headers and body
     *
     * return [status, body, ResponseHeaderMetadata]
     */
    private function _extractHeadersAndBody($response)
    {
        //First split by 2 'CRLF'
        $responseComponents = preg_split("/(?:\r?\n){2}/", $response, 2);
        $body = null;
        for ($count = 0;
             $count < count($responseComponents) && $body == null;
             $count++) {

            $headers = $responseComponents[$count];
            $responseStatus = $this->_extractHttpStatusCode($headers);

            if ($responseStatus != null &&
                $this->_httpHeadersHaveContent($headers)
            ) {

                $responseHeaderMetadata = $this->_extractResponseHeaderMetadata($headers);
                //The body will be the next item in the responseComponents array
                $body = $responseComponents[++$count];
            }
        }

        //If the body is null here then we were unable to parse the response and will throw an exception
        if ($body == null) {
            require_once(dirname(__FILE__) . '/OrderException.php');
            $exProps["Message"] = "Failed to parse valid HTTP response (" . $response . ")";
            $exProps["ErrorType"] = "HTTP";
            throw new OrderException($exProps);
        }

        return array(
            'Status' => $responseStatus,
            'ResponseBody' => $body,
            'ResponseHeaderMetadata' => $responseHeaderMetadata);
    }

    /**
     * parse the status line of a header string for the proper format and
     * return the status code
     *
     * Example: HTTP/1.1 200 OK
     * ...
     * returns String statusCode or null if the status line can't be parsed
     */
    private function _extractHttpStatusCode($headers)
    {
        $statusCode = null;
        if (1 === preg_match("/(\\S+) +(\\d+) +([^\n\r]+)(?:\r?\n|\r)/", $headers, $matches)) {
            //The matches array [entireMatchString, protocol, statusCode, the rest]
            $statusCode = $matches[2];
        }
        return $statusCode;
    }

    /**
     * Tries to determine some valid headers indicating this response
     * has content.  In this case
     * return true if there is a valid "Content-Length" or "Transfer-Encoding" header
     */
    private function _httpHeadersHaveContent($headers)
    {
        return (1 === preg_match("/[cC]ontent-[lL]ength: +(?:\\d+)(?:\\r?\\n|\\r|$)/", $headers) ||
            1 === preg_match("/Transfer-Encoding: +(?!identity[\r\n;= ])(?:[^\r\n]+)(?:\r?\n|\r|$)/i", $headers));
    }

    /**
     *  extract a ResponseHeaderMetadata object from the raw headers
     */
    private function _extractResponseHeaderMetadata($rawHeaders)
    {
        $inputHeaders = preg_split("/\r\n|\n|\r/", $rawHeaders);
        $headers = array();
        $headers['x-mws-request-id'] = null;
        $headers['x-mws-response-context'] = null;
        $headers['x-mws-timestamp'] = null;
        $headers['x-mws-quota-max'] = null;
        $headers['x-mws-quota-remaining'] = null;
        $headers['x-mws-quota-resetsOn'] = null;

        foreach ($inputHeaders as $currentHeader) {
            $keyValue = explode(': ', $currentHeader);
            if (isset($keyValue[1])) {
                list ($key, $value) = $keyValue;
                if (isset($headers[$key]) && $headers[$key] !== null) {
                    $headers[$key] = $headers[$key] . "," . $value;
                } else {
                    $headers[$key] = $value;
                }
            }
        }

        require_once(dirname(__FILE__) . '/model/ResponseHeaderMetadata.php');
        return new ResponseHeaderMetadata(
            $headers['x-mws-request-id'],
            $headers['x-mws-response-context'],
            $headers['x-mws-timestamp'],
            $headers['x-mws-quota-max'],
            $headers['x-mws-quota-remaining'],
            $headers['x-mws-quota-resetsOn']);
    }

    /**
     * Set curl options relating to SSL. Protected to allow overriding.
     * @param $ch curl handle
     */
    protected function setSSLCurlOptions($ch)
    {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    }

    /**
     * Exponential sleep on failed request
     *
     * @param retries current retry
     * @return bool
     */
    private function _pauseOnRetry($retries)
    {
        if ($retries <= $this->_config['MaxErrorRetry']) {
            $delay = (int)(pow(4, $retries) * 100000);
            usleep($delay);
            return true;
        }
        return false;
    }

    /**
     * Add authentication related and version parameters
     */
    private function _addRequiredParameters(array $parameters)
    {
        $parameters['AWSAccessKeyId'] = $this->_awsAccessKeyId;
        $parameters['Timestamp'] = $this->_getFormattedTimestamp();
        $parameters['Version'] = self::SERVICE_VERSION;
        $parameters['SignatureVersion'] = $this->_config['SignatureVersion'];
        if ($parameters['SignatureVersion'] > 1) {
            $parameters['SignatureMethod'] = $this->_config['SignatureMethod'];
        }
        $parameters['Signature'] = $this->_signParameters($parameters, $this->_awsSecretAccessKey);

        return $parameters;
    }

    /**
     * Convert paremeters to Url encoded query string
     */
    private function _getParametersAsString(array $parameters)
    {
        $queryParameters = array();
        foreach ($parameters as $key => $value) {
            $queryParameters[] = $key . '=' . $this->_urlencode($value);
        }
        return implode('&', $queryParameters);
    }


    /**
     * Computes RFC 2104-compliant HMAC signature for request parameters
     * Implements AWS Signature, as per following spec:
     *
     * If Signature Version is 0, it signs concatenated Action and Timestamp
     *
     * If Signature Version is 1, it performs the following:
     *
     * Sorts all  parameters (including SignatureVersion and excluding Signature,
     * the value of which is being created), ignoring case.
     *
     * Iterate over the sorted list and append the parameter name (in original case)
     * and then its value. It will not URL-encode the parameter values before
     * constructing this string. There are no separators.
     *
     * If Signature Version is 2, string to sign is based on following:
     *
     *    1. The HTTP Request Method followed by an ASCII newline (%0A)
     *    2. The HTTP Host header in the form of lowercase host, followed by an ASCII newline.
     *    3. The URL encoded HTTP absolute path component of the URI
     *       (up to but not including the query string parameters);
     *       if this is empty use a forward '/'. This parameter is followed by an ASCII newline.
     *    4. The concatenation of all query string components (names and values)
     *       as UTF-8 characters which are URL encoded as per RFC 3986
     *       (hex characters MUST be uppercase), sorted using lexicographic byte ordering.
     *       Parameter names are separated from their values by the '=' character
     *       (ASCII character 61), even if the value is empty.
     *       Pairs of parameter and values are separated by the '&' character (ASCII code 38).
     *
     */
    private function _signParameters(array $parameters, $key)
    {
        $signatureVersion = $parameters['SignatureVersion'];
        $algorithm = "HmacSHA1";
        $stringToSign = null;
        if (2 == $signatureVersion) {
            $algorithm = $this->_config['SignatureMethod'];
            $parameters['SignatureMethod'] = $algorithm;
            $stringToSign = $this->_calculateStringToSignV2($parameters);
        } else {
            throw new \Exception("Invalid Signature Version specified");
        }
        return $this->_sign($stringToSign, $key, $algorithm);
    }

    /**
     * Calculate String to Sign for SignatureVersion 2
     * @param array $parameters request parameters
     * @return String to Sign
     */
    private function _calculateStringToSignV2(array $parameters)
    {
        $data = 'POST';
        $data .= "\n";
        $endpoint = parse_url($this->_config['ServiceURL']);
        $data .= $endpoint['host'];
        $data .= "\n";
        $uri = array_key_exists('path', $endpoint) ? $endpoint['path'] : null;
        if (!isset ($uri)) {
            $uri = "/";
        }
        $uriencoded = implode("/", array_map(array($this, "_urlencode"), explode("/", $uri)));
        $data .= $uriencoded;
        $data .= "\n";
        uksort($parameters, 'strcmp');
        $data .= $this->_getParametersAsString($parameters);
        return $data;
    }

    private function _urlencode($value)
    {
        return str_replace('%7E', '~', rawurlencode($value));
    }


    /**
     * Computes RFC 2104-compliant HMAC signature.
     */
    private function _sign($data, $key, $algorithm)
    {
        if ($algorithm === 'HmacSHA1') {
            $hash = 'sha1';
        } else if ($algorithm === 'HmacSHA256') {
            $hash = 'sha256';
        } else {
            throw new \Exception ("Non-supported signing method specified");
        }
        return base64_encode(
            hash_hmac($hash, $data, $key, true)
        );
    }


    /**
     * Formats date as ISO 8601 timestamp
     */
    private function _getFormattedTimestamp()
    {
        return gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
    }

    /**
     * Formats date as ISO 8601 timestamp
     */
    private function getFormattedTimestamp($dateTime)
    {
        return $dateTime->format(DATE_ISO8601);
    }

}
