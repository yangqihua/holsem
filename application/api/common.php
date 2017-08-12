<?php

use amazon\order\model\ListOrderRequest;
use amazon\order\OrderClient;
use amazon\order\OrderException;



if (!function_exists('getOrderList'))
{

    function getOrderList()
    {
        $config = array(
            'ServiceURL' => config('amazon.service_url'),
            'ProxyHost' => null,
            'ProxyPort' => -1,
            'ProxyUsername' => null,
            'ProxyPassword' => null,
            'MaxErrorRetry' => 3,
        );

        $service = new OrderClient(
            config('amazon.aws_access_key_id'),
            config('amazon.aws_secret_access_key'),
            config('amazon.application_name'),
            config('amazon.application_version'),
            $config);

        $request = new ListOrderRequest();
        $request->setSellerId(config('amazon.merchant_id'));
        $request->setMarketplaceId(config('amazon.marketplace_id'));
        $request->setMaxResultsPerPage(10);

        $request->setCreatedAfter('2017-06-26T00:15:47+08:00');
        $request->setCreatedBefore('2017-06-27T10:15:47+08:00');
        try {
            $response = $service->ListOrders($request);
            $ordersResult = $response->getListOrdersResult();
//            $arr = object_array($ordersResult);
//            return $arr;
            $orders = $ordersResult->getOrders();
            $orderList = [];
            foreach ($orders as $order) {
                $orderResult = [];
                $orderResult["latestShipDate"] = $order->getLatestShipDate();
                $orderResult["orderType"] = $order->getOrderType();
                $orderResult["purchaseDate"] = $order->getPurchaseDate();
                $orderResult["amazonOrderId"] = $order->getAmazonOrderId();
                $orderResult["buyerEmail"] = $order->getBuyerEmail();
                $orderResult["isReplacementOrder"] = $order->getIsReplacementOrder();
                $orderResult["lastUpdateDate"] = $order->getLastUpdateDate();
                $orderResult["numberOfItemsShipped"] = $order->getNumberOfItemsShipped();
                $orderResult["shipServiceLevel"] = $order->getShipServiceLevel();
                $orderResult["orderStatus"] = $order->getOrderStatus();
                $orderResult["salesChannel"] = $order->getSalesChannel();
                $orderResult["isBusinessOrder"] = $order->getIsBusinessOrder();
                $orderResult["numberOfItemsUnshipped"] = $order->getNumberOfItemsUnshipped();
                $orderResult["buyerName"] = $order->getBuyerName();
                $orderResult["fulfillmentChannel"] = $order->getFulfillmentChannel();
                $orderList[] = $orderResult;
            }
            $nextToken = $ordersResult->getNextToken();
            return ['nextToken'=>$nextToken,'orderList'=>$orderList];
        } catch (OrderException $ex) {
            echo("Caught Exception: " . $ex->getMessage() . "\n");
            echo("Response Status Code: " . $ex->getStatusCode() . "\n");
            echo("Error Code: " . $ex->getErrorCode() . "\n");
            echo("Error Type: " . $ex->getErrorType() . "\n");
            echo("Request ID: " . $ex->getRequestId() . "\n");
            echo("XML: " . $ex->getXML() . "\n");
            echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
        }
        return [];
    }

    function object_array($array) {
        if(is_object($array)) {
            $array = (array)$array;
        } if(is_array($array)) {
            foreach($array as $key=>$value) {
                $array[$key] = object_array($value);
            }
        }
        return $array;
    }


}