<?php

use amazon\order\model\ListOrderRequest;
use amazon\order\model\ListOrderItemsRequest;
use amazon\order\OrderClient;
use amazon\order\OrderException;


if (!function_exists('getOrderList')) {

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

        $createAfter = date('c', time() - 10 * 60 - 3 * 60 * 60);
        $createBefore = date('c', time() - 10 * 60);

        $request->setLastUpdatedAfter($createAfter);
        $request->setLastUpdatedBefore($createBefore);
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
            return ['nextToken' => $nextToken, 'orderList' => $orderList, 'message' => 'ok', 'code' => 200];
        } catch (OrderException $ex) {
            return ["message" => $ex->getMessage(), "code" => $ex->getStatusCode()];
        }
    }

}

if (!function_exists('getOrderItemList($orderId)')) {

    function getOrderItemList($orderId)
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

        $request = new ListOrderItemsRequest();
        $request->setSellerId(config('amazon.merchant_id'));
        $request->setAmazonOrderId($orderId);
        try {
            $response = $service->ListOrderItems($request);

            $orderItemResultList = $response->getListOrderItemsResult()->getOrderItems();

            $orderItemList = [];
            foreach ($orderItemResultList as $orderItemResult) {
                $orderItem = [];
                $orderItem['quantity_ordered'] = $orderItemResult->getQuantityOrdered();
                $orderItem['title'] = $orderItemResult->getTitle();
                $orderItem['asin'] = $orderItemResult->getASIN();
                $orderItem['seller_sku'] = $orderItemResult->getSellerSKU();
                $orderItem['order_item_id'] = $orderItemResult->getOrderItemId();
                $orderItem['quantity_shipped'] = $orderItemResult->getQuantityShipped();
                $promotionDiscount = $orderItemResult->getPromotionDiscount();
                if (null != $promotionDiscount) {
                    $orderItem['promotion_discount'] = json_encode(['CurrencyCode' => $promotionDiscount->getCurrencyCode(), 'Amount' => $promotionDiscount->getAmount()]);
                }
                $itemPrice = $orderItemResult->getItemPrice();
                if (null != $itemPrice) {
                    $orderItem['item_price'] = json_encode(['CurrencyCode' => $itemPrice->getCurrencyCode(), 'Amount' => $itemPrice->getAmount()]);
                }
                $itemTax = $orderItemResult->getItemTax();
                if (null != $itemTax) {
                    $orderItem['item_tax'] = json_encode(['CurrencyCode' => $itemTax->getCurrencyCode(), 'Amount' => $itemTax->getAmount()]);
                }
                $orderItem['quantity_ordered'] = $orderItemResult->getQuantityOrdered();
                $orderItemList[] = $orderItem;
            }
            return ['orderItemList' => $orderItemList, 'message' => 'ok', 'code' => 200];
        } catch (OrderException $ex) {
            return ["message" => $ex->getMessage(), "code" => $ex->getStatusCode()];
        }
    }

}

if (!function_exists('object_array')) {
    function object_array($array)
    {
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = object_array($value);
            }
        }
        return $array;
    }
}