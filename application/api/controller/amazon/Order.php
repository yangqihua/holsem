<?php
/**
 * Created by PhpStorm.
 * User: yangqihua
 * Date: 2017/8/12
 * Time: 下午12:32
 */

namespace app\api\controller\amazon;

use app\common\controller\Api;
use app\common\model\amazon\Order as OrderModel;
use app\common\model\amazon\OrderItem as OrderItemModel;
use Sauladam\ShipmentTracker\ShipmentTracker;

use SSilence\ImapClient\ImapClientException;
use SSilence\ImapClient\ImapClient as Imap;

use app\admin\model\Config;

class Order extends Api
{
    protected $orderModel = null;
    protected $orderItemModel = null;

//    protected $noNeedRight = ['check','emailtest'];

    function __construct()
    {
        parent::__construct();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }

    public function index()
    {
        return json(['title' => '执行order测试', 'time' => date("Y-m-d H:i:s"), 'code' => 0]);
    }

    public function listOrders()
    {
        $orderListResult = getOrderList();
        if ($orderListResult['code'] == 200) {
            $lastOrders = $this->orderModel->select();
            foreach ($orderListResult['orderList'] as $key => $order) {
                $oldOrder = false;
                foreach ($lastOrders as $k => $v) {
                    if ($v['amazon_order_id'] == $order['amazon_order_id']) {
                        $oldOrder = $v;
                        break;
                    }
                }
                if (!$oldOrder) {
                    $order['has_items'] = 0;
                    $this->orderModel->data($order, true)->isUpdate(false)->save();
                } else {
                    // 只有订单状态改变了才更新 或 已经抓取到了其快递信息了。
                    if ($order['order_status'] != $oldOrder['order_status'] || $oldOrder['ship_by'] != null) {
                        $order['has_items'] = 0;
                        $this->orderModel->save($order, ['amazon_order_id' => $order['amazon_order_id']]);
                    }
                }
            }
        } else {
            // TODO: 请求失败的处理
        }
        return json(['time' => date("Y-m-d H:i:s"), 'title' => 'listOrders', 'code' => $orderListResult['code'], 'message' => $orderListResult['message'], 'content' => $orderListResult]);
    }

    public function listOrderItems()
    {

        $order = $this->orderModel->where('has_items', 0)->find();
        if ($order != null) {
            $orderItemListResult = getOrderItemList($order['amazon_order_id']);
            if ($orderItemListResult['code'] == 200) {
                $this->orderItemModel->where('order_id', $order['id'])->delete();
                foreach ($orderItemListResult['orderItemList'] as $key => $value) {
                    $orderItemListResult['orderItemList'][$key]['order_id'] = $order['id'];
                }
                $this->orderItemModel->saveAll($orderItemListResult['orderItemList']);
                $this->orderModel->where('id', $order['id'])->update(['has_items' => 1]);
            } else {
                // TODO: 请求失败的处理
            }
            return json(['time' => date("Y-m-d H:i:s"), 'title' => 'listOrderItems', 'code' => $orderItemListResult['code'], 'message' => $orderItemListResult['message'], 'content' => $orderItemListResult]);
        }
        return json(['time' => date("Y-m-d H:i:s"), 'title' => 'listOrderItems', 'code' => 200, 'message' => '暂无需要抓取的商品', 'content' => '暂无需要抓取的商品']);

    }

    public function getMailList()
    {
        $config = new Config();
        // 注意这里是页面，不是从哪条开始

        $limit = 10;
        $config_mail_index = $config->where("name", "mail_index")->find();
        $mail_index = $config_mail_index['value'] / $limit;

        $mailbox = 'imap-mail.outlook.com';
        $username = 'sandy.williams2013@outlook.com';
        $password = 'Sandy2017#';
        $encryption = Imap::ENCRYPT_SSL;

        try {
            $imap = new Imap($mailbox, $username, $password, $encryption);

        } catch (ImapClientException $error) {
            return json(['time' => date("Y-m-d H:i:s"), 'title' => 'getMailList', 'code' => 500, 'message' => $error->getMessage() . PHP_EOL, 'content' => $error->getMessage() . PHP_EOL]);
        }
        $imap->selectFolder('FBA Shipments');
//        $overallMessages = $imap->countMessages();
//        $unreadMessages = $imap->countUnreadMessages();

        $emails = $imap->getMessages($limit, $mail_index);
        $packages = [];
        foreach ($emails as $email) {
            $html = $email->message->text->jsonSerialize()['body'];
            $package = [];
            $findCount = preg_match('/Fulfillment Order \(([^\)]*)\)*./', $html, $matches);
            if ($findCount && count($matches) == 2) {
                $package['amazonOrderId'] = trim($matches[1]);
                $findCount = preg_match('/Shipped By:(.*)Tracking/', $html, $matches);
                if ($findCount && count($matches) == 2) {
                    $package['shippedBy'] = trim($matches[1]);
                    $findCount = preg_match('/Tracking No: ([^---]*)-------------/', $html, $matches);
                    if ($findCount && count($matches) == 2) {
                        $package['tracking'] = trim($matches[1]);
                    }
                }
            }
            $packages[] = $package;
        }
        $config->where("id", $config_mail_index['id'])->update(['value' => ($config_mail_index['value'] + $limit)]);
        return json(['time' => date("Y-m-d H:i:s"), 'title' => 'getMailList', 'code' => 200, 'message' => 'success', 'content' => $packages]);
    }


    public function getPackageStatus()
    {
        $packageNumber = '1Z300VW20343361574';
        $USPStracker = ShipmentTracker::get('UPS');
        $track = $USPStracker->track($packageNumber);
        if ($track->delivered()) {
            echo "Delivered to " . $track->getRecipient();
        }
        $currentStatus = $track->currentStatus();
//        $latestEvent = $track->latestEvent();
//
//        echo "The parcel was last seen in " . $latestEvent->getLocation() . " on " . $latestEvent->getDate()->format('Y-m-d');
//        echo "What they did: " . $latestEvent->getDescription();
//        echo "The status was " . $latestEvent->getStatus();

        $events = $track->events();
        $trackData = [];
        foreach ($events as $event){
            $trackData['package_number'] = $packageNumber;
            $trackData['status'] = $event->getStatus();
            $trackData['date'] = $event->getDate();
            $trackData['location'] = $event->getLocation();
            $trackData['description'] = $event->getDescription();
        }



    }


}