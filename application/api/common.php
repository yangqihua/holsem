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
        $request->setMaxResultsPerPage(100);

        $createAfter = date('c',time()-10*60-4*60*60);
        $createBefore = date('c',time()-10*60);

        $request->setCreatedAfter($createAfter);
        $request->setCreatedBefore($createBefore);
        try {
            $response = $service->ListOrders($request);
            $ordersResult = $response->getListOrdersResult();
            $orders = $ordersResult->getOrders();
            $orderList = [];
            foreach ($orders as $order) {
                $orderResult = [];
                $orderResult["latest_ship_date"] = datetime(strtotime($order->getLatestShipDate()));
                $orderResult["order_type"] = $order->getOrderType();
                $orderResult["purchase_date"] = datetime(strtotime($order->getPurchaseDate()));
                $orderResult["amazon_order_id"] = $order->getAmazonOrderId();
                $orderResult["buyer_email"] = $order->getBuyerEmail();
                $orderResult["is_replacement_order"] = $order->getIsReplacementOrder();
                $orderResult["last_update_date"] = datetime(strtotime($order->getLastUpdateDate()));
                $orderResult["number_of_items_shipped"] = $order->getNumberOfItemsShipped();
                $orderResult["ship_service_level"] = $order->getShipServiceLevel();
                $orderResult["order_status"] = $order->getOrderStatus();
                $orderResult["sales_channel"] = $order->getSalesChannel();
                $orderResult["is_business_order"] = $order->getIsBusinessOrder();
                $orderResult["number_of_items_unshipped"] = $order->getNumberOfItemsUnshipped();
                $orderResult["buyer_name"] = $order->getBuyerName();
                $orderResult["fulfillment_channel"] = $order->getFulfillmentChannel();
                $orderList[] = $orderResult;
            }
            $nextToken = $ordersResult->getNextToken();
            return ['nextToken'=>$nextToken,'orderList'=>$orderList,'message'=>'ok','code'=>200];
        } catch (OrderException $ex) {
            return ["message" => $ex->getMessage(), "code" => $ex->getStatusCode()];
        }
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