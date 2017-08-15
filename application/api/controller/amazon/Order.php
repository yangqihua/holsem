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
use amazon\mail\POP3;
use amazon\mail\MimeParser;
use amazon\mail\Rfc822Address;

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
                    // 只有订单状态改变了才更新
                    if ($order['order_status'] != $oldOrder['order_status']) {
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

    public function getMail()
    {
        $host = "pop.exmail.qq.com";
        $user = "yangqihua@dowish.net";
        $pass = "M5dE8kJipkkwSyat";

        stream_wrapper_register('pop3', '\amazon\mail\POP3Stream');

        $pop3 = new POP3();
        $pop3->hostname = "pop.exmail.qq.com";             /* POP 3 server host name                      */
        $pop3->port = 995;                         /* POP 3 server host port, usually 110 but some servers use other ports Gmail uses 995 */
        $pop3->tls = 1;                            /* Establish secure connections using TLS      */
        $user = "yangqihua@dowish.net";                        /* Authentication user name                    */
        $password = "M5dE8kJipkkwSyat";                    /* Authentication password                     */
        $pop3->realm = "";                         /* Authentication realm or domain              */
        $pop3->workstation = "";                   /* Workstation for NTLM authentication         */
        $apop = 0;                                 /* Use APOP authentication                     */
        $pop3->authentication_mechanism = "USER";  /* SASL authentication mechanism               */
        $pop3->debug = 0;                          /* Output debug information                    */
        $pop3->html_debug = 0;                     /* Debug information is in HTML                */
        $pop3->join_continuation_header_lines = 1; /* Concatenate headers split in multiple lines */

        $mailList = [];
        if (($error = $pop3->Open()) == "") {
            if (($error = $pop3->Login($user, $password, $apop)) == "") {
                if (($error = $pop3->Statistics($messages, $size)) == "") {
                    $count = $messages - 3;
                    for ($i = $messages; $i >= $count; $i--) // grabs last 3 mails
                    {
                        if ($messages > 0) {
                            $pop3->GetConnectionName($connection_name);
                            $message = $i;
                            $message_file = 'pop3://' . $connection_name . '/' . $message;
                            $mime = new MimeParser();
                            $mime->decode_bodies = 1;
                            $parameters = array(
                                'File' => $message_file,
                                'SkipBody' => 0,
                            );
                            $success = $mime->Decode($parameters, $decoded);
                            if (!$success)
                                $error .= 'MIME message decoding error: ' . HtmlSpecialChars($mime->error) . "\n";
                            else {
                                if ($mime->Analyze($decoded[0], $results)) {
                                    $mail = [];
                                    $mail['subject'] = iconv($results['Encoding'], "UTF-8", $results['Subject']);
                                    $mail['data'] = iconv($results['Encoding'], "UTF-8", $results['Data']);
                                    $mailList[] = $mail;
                                } else {
                                    $error .= 'MIME message analyse error: ' . $mime->error . "\n";
                                }
                            }
                        }
                    }
                    $error .= $pop3->Close();
                }
            }
        }
        if ($error != "") {
            return json(['time' => date("Y-m-d H:i:s"), 'title' => 'getMail', 'code' => 500, 'message' => 'error', 'content' => $error]);
        }
        return json(['time' => date("Y-m-d H:i:s"), 'title' => 'getMail', 'code' => 200, 'message' => 'success', 'content' => $mailList]);

    }

}