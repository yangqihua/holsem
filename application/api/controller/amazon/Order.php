<?php
/**
 * Created by PhpStorm.
 * User: yangqihua
 * Date: 2017/8/12
 * Time: 下午12:32
 */

namespace app\api\controller\amazon;

use app\common\controller\Api;

class Order extends Api
{

    public function index()
    {
        return json(['title' => '执行order测试', 'time' => date("Y-m-d H:i:s"), 'code' => 0]);
    }

    public function listOrders()
    {
        $orderList = getOrderList();
        return json(['title' => 'listOrders', 'content' => $orderList, 'time' => date("Y-m-d H:i:s"), 'code' => 0]);
    }

}