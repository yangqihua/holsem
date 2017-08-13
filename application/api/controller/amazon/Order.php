<?php
/**
 * Created by PhpStorm.
 * User: yangqihua
 * Date: 2017/8/12
 * Time: 下午12:32
 */

namespace app\api\controller\amazon;

use app\common\controller\Api;
use app\api\model\amazon\Order as OrderModel;

class Order extends Api
{
    protected $model = null;

//    protected $noNeedRight = ['check','emailtest'];

    function __construct()
    {
        parent::__construct();
        $this->model = new OrderModel();
    }

    public function index()
    {
        return json(['title' => '执行order测试', 'time' => date("Y-m-d H:i:s"), 'code' => 0]);
    }

    public function listOrders()
    {
        $orderListResult = getOrderList();
        if ($orderListResult['code'] == 200) {

            $maxGroupIds = $this->model->column('group_id');
            $maxGroupId = 0;
            if (!is_null($maxGroupIds) && count($maxGroupIds) > 0) {
                $maxGroupId = $maxGroupIds[count($maxGroupIds) - 1];
            }
            $lastOrders = $this->model
//                ->where('group_id',$maxGroupId)
                ->column('amazon_order_id');
            foreach ($orderListResult['orderList'] as $key => $order) {
                $order['group_id'] = $maxGroupId + 1;
                $isExist = false;
                foreach ($lastOrders as $k => $v) {
                    if ($v == $order['amazon_order_id']) {
                        $isExist = true;
                        break;
                    }
                }
                if (!$isExist) {
                    $this->model->data($order, true)->isUpdate(false)->save();
                }
            }
        } else {
            // TODO: 请求失败的处理
        }
        return json(['time' => date("Y-m-d H:i:s"),'title' => 'listOrders', 'code' => $orderListResult['code'], 'message' => $orderListResult['message'], 'content' => $orderListResult]);
    }

}