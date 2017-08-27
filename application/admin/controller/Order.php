<?php

namespace app\admin\controller;

use app\common\controller\Backend;

use app\common\model\amazon\OrderItem as ItemModel;
use app\common\model\amazon\Track as Track;
/**
 * 订单
 *
 * @icon fa fa-circle-o
 */
class Order extends Backend
{
    
    /**
     * Order模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Order');

    }

    /**
     * 查看order items
     */
    public function items()
    {
        $itemModel = new ItemModel();
        $orderId = input("id","0");
        $rows = $itemModel->where('order_id',$orderId)->select();
        $this->view->assign("items", $rows);
        return $this->view->fetch();
    }

    /**
     * 查看order tracker
     */
    public function tracker()
    {
        $tracker = new Track();
        $id = input("id","0");
        $packageNumber = $this->model->where('id',$id)->value('package_number');
        $rows = $tracker->where('package_number',$packageNumber)->select();
        $this->view->assign("items", $rows);
        return $this->view->fetch();
    }
    

}
