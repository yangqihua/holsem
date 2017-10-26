<?php

use amazon\order\model\ListOrderRequest;
use amazon\order\model\GetOrderRequest;
use amazon\order\model\ListOrderItemsRequest;
use amazon\order\OrderClient;
use amazon\order\OrderException;

use amazon\inventory\InventoryClient;
use amazon\inventory\InventoryException;
use amazon\inventory\model\ListInventorySupplyRequest;
use amazon\inventory\model\SellerSkuList;

use app\common\library\Email;


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

        // 去前一个小时的订单
        $createAfter = date('c', time() - 10 * 60 - 1 * 60 * 60);
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

if (!function_exists('getOrder($orderId)')) {

    function getOrder($orderId)
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

        $request = new GetOrderRequest();
        $request->setAmazonOrderId($orderId);
        $request->setSellerId(config('amazon.merchant_id'));

        try {
            $response = $service->GetOrder($request);
            $orderResult = $response->getGetOrderResult();
            $orders = $orderResult->getOrders();
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
            $order = [];
            if (count($orderList) == 1) {
                $order = $orderList[0];
            }
            return ['order' => $order, 'message' => 'ok', 'code' => 200];
        } catch (OrderException $ex) {
            return ['order' => [], 'message' => $ex->getMessage(), 'code' => $ex->getStatusCode()];
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

if (!function_exists('getInventoryList()')) {

    function getInventoryList()
    {
        $config = array(
            'ServiceURL' => config('amazon.inventory_service_url'),
            'ProxyHost' => null,
            'ProxyPort' => -1,
            'ProxyUsername' => null,
            'ProxyPassword' => null,
            'MaxErrorRetry' => 3,
        );

        $service = new InventoryClient(
            config('amazon.aws_access_key_id'),
            config('amazon.aws_secret_access_key'),
            $config,
            config('amazon.application_name'),
            config('amazon.application_version')
        );

        $request = new ListInventorySupplyRequest();
        $request->setSellerId(config('amazon.merchant_id'));
        $request->setMarketplaceId(config('amazon.marketplace_id'));
        $request->setResponseGroup('Basic');
        $sellerSkuList = new SellerSkuList();
        $sellerSkuList->setmember('HOLSEM-X12B');
        $request->setSellerSkus($sellerSkuList);
        try {
            $response = $service->ListInventorySupply($request);
            return ['inventoryList' => $response, 'message' => 'ok', 'code' => 200];
        } catch (InventoryException $ex) {
            return ["message" => $ex->getMessage(), "code" => $ex->getStatusCode()];
        }
    }

}


if (!function_exists('sendCustomersMail($receiver_address, $name, $orderCategoryList)')) {
    function sendCustomersMail($receiver_address, $name, $orderCategoryList)
    {
        $name = $name ? $name : 'customer';
        $holsems = [];
        foreach ($orderCategoryList as $key => $value) {
            $v = '';
            if ($value == 'HOLSEM U3') {
                $v = "\n    https://www.amazon.com/dp/B01LD06EQ4";
            } else if ($value == 'HOLSEM-X5') {
                $v = "\n    https://www.amazon.com/dp/B01EV3DW5Q";
            } else if ($value == 'HOLSEM-X5B') {
                $v = "\n    https://www.amazon.com/dp/B01LXIDES4";
            } else if ($value == 'HOLSEM-X8') {
                $v = "\n    https://www.amazon.com/dp/B01ASWH5KW";
            } else if ($value == 'HOLSEM-X8B') {
                $v = "\n    https://www.amazon.com/dp/B01LYHJMTD";
            } else if ($value == 'HOLSEM-X12' || $value == 'HOLSEM-S12') {
                $v = "\n    https://www.amazon.com/dp/B01H14SFRM";
            } else if ($value == 'HOLSEM-X12B') {
                $v = "\n    https://www.amazon.com/dp/B01LYHEC16";
            } else if ($value == 'HOLSEM-D7') {  // 刀的求好评邮件 todo:有没有既有刀又有其他商品的订单
                $subject = config('mail_text.subject');
                $message = sprintf(config('mail_text.knife_content'), $name);
                $email = new Email;
                $result = $email
                    ->to($receiver_address)
                    ->subject($subject)
                    ->message($message, false)
                    ->send();
                if ($result) {
                    return ['code' => 200, 'message' => 'success'];
                } else {
                    return ['code' => 500, 'message' => $email->getError()];
                }
            } else if ($value == 'HOLSEM-A1' || $value == 'HOLSEM-A2') {
                // todo: 发送炸锅的邮件
                $a_link = '';
                if ($value == 'HOLSEM-A1') {
                    $a_link = "\n    https://www.amazon.com/dp/B072JJBZ37";
                } else {
                    $a_link = "\n    https://www.amazon.com/dp/B071W83YND";
                }
                $subject = config('mail_text.subject');
                $message = sprintf(config('mail_text.zg_content'), $name, $a_link);
                $email = new Email;
                $result = $email
                    ->to($receiver_address)
                    ->subject($subject)
                    ->message($message, false)
                    ->send();
                if ($result) {
                    return ['code' => 200, 'message' => 'success'];
                } else {
                    return ['code' => 500, 'message' => $email->getError()];
                }
            }
            $holsems[] = $v;
        }
        $unU3 = '';
        foreach ($orderCategoryList as $key => $value) {
            if ($value != 'HOLSEM U3') {
                $unU3 = ' and our surge protector can help to manage your various charging needs both at home and in office';
                break;
            }
        }
        $holsemString = join("", $holsems);

        if ($holsemString == '') {
            return ['code' => 500, 'message' => '不能匹配商品列表，不发送邮件给 ' . $receiver_address];
        }

        $subject = config('mail_text.subject');
        $message = sprintf(config('mail_text.content'), $name, $unU3, $holsemString);
        $email = new Email;
        $result = $email
            ->to($receiver_address)
            ->subject($subject)
            ->message($message, false)
            ->send();
        if ($result) {
            return ['code' => 200, 'message' => 'success'];
        } else {
            return ['code' => 500, 'message' => $email->getError()];
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

if (!function_exists('array_utf8_encode')) {
    function array_utf8_encode($dat)
    {
        if (is_string($dat))
            return iconv("ISO-8859-1", "UTF-8", $dat);
        if (!is_array($dat))
            return $dat;
        $ret = array();
        foreach ($dat as $i => $d)
            $ret[$i] = array_utf8_encode($d);
        return $ret;
    }
}