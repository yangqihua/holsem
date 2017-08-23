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
use app\common\model\amazon\Track as TrackmModel;
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

    public function getOrder()
    {
        return getOrder("114-3438471-2729820");
    }

    public function listOrders()
    {
        $orderListResult = getOrderList();
        if ($orderListResult['code'] == 200) {
            $lastOrders = $this->orderModel->select();
            foreach ($orderListResult['orderList'] as $key => $order) {
                // 是否已经存在该 amazon_order_id
                $oldOrder = false;
                foreach ($lastOrders as $k => $v) {
                    if ($v['amazon_order_id'] == $order['amazon_order_id']) {
                        $oldOrder = $v;
                        break;
                    }
                }
                // 不存在
                if (!$oldOrder) {
                    $order['has_items'] = 0;
                    $this->orderModel->data($order, true)->isUpdate(false)->save();
                    $this->listOrderItems($order['amazon_order_id']);
                    sleep(8);
                } else {
                    // 只有订单状态改变了才更新 或 已经抓取到了其快递信息了。
                    if ($order['order_status'] != $oldOrder['order_status'] || $oldOrder['ship_by'] != null) {
                        $order['has_items'] = 0;
                        $this->orderModel->save($order, ['amazon_order_id' => $order['amazon_order_id']]);
                        $this->listOrderItems($order['amazon_order_id']);
                        sleep(8);
                    }
                }
            }
        } else {
            // TODO: 请求失败的处理
        }
        return json(['time' => date("Y-m-d H:i:s"), 'title' => 'listOrders', 'code' => $orderListResult['code'], 'message' => $orderListResult['message'], 'content' => '获取订单成功']);
    }

    public function listOrderItems($amazon_order_id)
    {
        $order = $this->orderModel->where("amazon_order_id", $amazon_order_id)->find();
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

    /*
     *  每次去读指定封数邮件，如果该orderId对应的记录没有，
     *  则去获取对应orderId的order详情和对应的orderItem
     */
    public function getMailList()
    {
        $config = new Config();
        // 注意这里是页面，不是从哪条开始

        $config_mail_index = $config->where("name", "mail_index")->find();
        $config_mail_limit = $config->where("name", "mail_limit")->find();
        $config_mail_count = $config->where("name", "mail_count")->find();

        $mail_index = $config_mail_index['value'] / $config_mail_limit['value'];
        $mailbox = config('mail.host');
        $username = config('mail.username');
        $password = config('mail.password');
        $encryption = Imap::ENCRYPT_SSL;

        try {
            $imap = new Imap($mailbox, $username, $password, $encryption);

        } catch (ImapClientException $error) {
            return json(['time' => date("Y-m-d H:i:s"), 'title' => 'getMailList', 'code' => 500, 'message' => $error->getMessage() . PHP_EOL, 'content' => $error->getMessage() . PHP_EOL]);
        }
        $imap->selectFolder('FBA Shipments');
        $mailCount = $imap->countMessages();
        $config->where("id", $config_mail_count['id'])->update(['value' => $mailCount]);
        if ($mailCount - $config_mail_limit['value'] < $config_mail_index['value']) {
            return json(['time' => date("Y-m-d H:i:s"), 'title' => 'getMailList', 'code' => 200, 'message' => 'success', 'content' => '最后一页邮件已经读完']);
        }

        $emails = $imap->getMessages($config_mail_limit['value'], $mail_index,'ASC');
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

            $order = $this->orderModel->where("amazon_order_id", $package['amazonOrderId'])->find();
            // 如果已经存在该订单
            if ($order) {
                $this->orderModel->save(['ship_by' => $package['shippedBy'], 'package_number' => $package['tracking']], ['id' => $order['id']]);
            } else { // 不存在则访问 aws api 获取订单详情 和 订单的商品
                if ($package['amazonOrderId']) {
                    $awsOrderResult = getOrder($package['amazonOrderId']);
                    $awsOrder = $awsOrderResult['order'];
                    $awsOrder['has_items'] = 0;
                    $awsOrder['ship_by'] = $package['shippedBy'];
                    $awsOrder['package_number'] = $package['tracking'];
                    $awsOrder['has_items'] = 0;
                    $this->orderModel->data($awsOrder, true)->isUpdate(false)->save();
                    $this->listOrderItems($package['amazonOrderId']);
                    sleep(5);
                }
            }

        }
        $config->where("id", $config_mail_index['id'])->update(['value' => ($config_mail_index['value'] + $config_mail_limit['value'])]);
        return json(['time' => date("Y-m-d H:i:s"), 'title' => 'getMailList', 'code' => 200, 'message' => 'success', 'content' => $packages]);
    }

    /*
     * 轮询 order 里面没有 deliver 的订单 ， 每次查询一个
     */
    public function getPackageStatus()
    {
        // $packageNumber = '1Z300VW20343361574';
        // $packageNumber = '9361289683090216690666';
        $config = new Config();
        $order_usps_index = $config->where("name", "order_usps_index")->find();
        $order_usps_count = $this->orderModel
            ->where(" deliver_status not like 'delivered%' or deliver_status is null ")->where("package_number", "<>", "null")->where("ship_by", "USPS")
            ->count();
        $config->save(['value' => $order_usps_count], ['name' => 'order_usps_count']);
        $usps_index = intval($order_usps_index['value']);
        // 重新轮询
        if ($usps_index >= $order_usps_count) {
            $usps_index = 0;
        }
        $orders = $this->orderModel
            ->where(" deliver_status not like 'delivered%' or deliver_status is null ")->where("package_number", "<>", "null")->where("ship_by", "USPS")
            ->limit($usps_index, 1)
            ->select();
        $order = false;
        if ($orders && count($orders) == 1) {
            $order = $orders[0];
        }

        if (!$order) {
            return json(['time' => date("Y-m-d H:i:s"), 'title' => 'getPackageStatus', 'code' => 500, 'message' => 'error', 'content' => '暂无需要查询的订单']);
        }
        $USPStracker = ShipmentTracker::get('USPS');
        $track = $USPStracker->track($order['package_number']);

        $events = $track->events();
        $trackModel = new TrackmModel();
        $oldData = $trackModel->where('package_number', $order['package_number'])->select();
        $trackData = [];
        foreach ($events as $event) {
            $data = [];
            $data['package_number'] = $order['package_number'];
            $data['status'] = $event->getStatus();
            $data['date'] = $event->getDate();
            $data['location'] = $event->getLocation();
            $data['description'] = $event->getDescription();

            $flag = false;
            foreach ($oldData as $key => $value) {
                if ($value['date'] == $data['date']) {
                    $flag = true;
                }
            }
            if (!$flag) {
                $trackModel->data($data, true)->isUpdate(false)->save();
            }
            $trackData[] = $data;
        }
        $deliver_status_arr = explode("_", $order['deliver_status']);
        // 更新 order 中的 deliver_status 属性
        $order['deliver_status'] = $track->currentStatus();
        $deliver_status = $order['deliver_status'];
        if (count($deliver_status_arr) == 2) {
            $track_count = $deliver_status_arr[1] + 1;
            $deliver_status = $deliver_status . "_" . $track_count;
        } else {
            $deliver_status = $deliver_status . "_1";
        }

        $this->orderModel->where('id', $order['id'])->update(['deliver_status' => $deliver_status]);
        // TODO：在这里执行发送邮件的操作
        if ($order['deliver_status'] == 'delivered') {
            // 1.发送邮件

            // 2.更新order的has_send_mail 字段
        } else { // 只有在非 delivered的情况下才往后移动
            $config->update(['id' => $order_usps_index['id'], 'value' => ($usps_index + 1)]);
        }
        return json(['time' => date("Y-m-d H:i:s"), 'title' => 'getPackageStatus', 'code' => 200, 'message' => 'success', 'content' => $trackData]);


    }


}