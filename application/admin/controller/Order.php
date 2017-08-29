<?php

namespace app\admin\controller;

use amazon\order\model\OrderItem;
use app\common\controller\Backend;

use app\common\model\amazon\OrderItem as ItemModel;
use app\common\model\amazon\Track as Track;
use app\admin\model\Order as OrderModel;
use think\db;

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
     * 查看 Order
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();


            $itemModel = new ItemModel();
            foreach ($list as $key => $value) {
                $items = $itemModel->where('order_id', $value['id'])->column('seller_sku');
                $skus = implode(',',$items);
                $list[$key]['skus'] = $skus;
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 查看order items
     */
    public function items()
    {
        $itemModel = new ItemModel();
        $orderId = input("id", "0");
        $rows = $itemModel->where('order_id', $orderId)->select();
        $this->view->assign("items", $rows);
        return $this->view->fetch();
    }

    /**
     * 查看order tracker
     */
    public function tracker()
    {
        $tracker = new Track();
        $id = input("id", "0");
        $packageNumber = $this->model->where('id', $id)->value('package_number');
        $rows = $tracker->where('package_number', $packageNumber)->order('date', 'desc')->select();
        $this->view->assign("items", $rows);
        return $this->view->fetch();
    }


}
