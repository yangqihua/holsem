<?php

namespace app\admin\controller;

use app\common\controller\Backend;

use think\Db;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
        $seventtime = \fast\Date::unixtime('day', -7);
        $paylist = $createlist = [];
        for ($i = 0; $i < 7; $i++)
        {
            $day = date("Y-m-d", $seventtime + ($i * 86400));
            $createlist[$day] = mt_rand(20, 200);
            $paylist[$day] = mt_rand(1, mt_rand(1, $createlist[$day]));
        }


        $totalOrderItem = Db::query('SELECT seller_sku sku,count(*) num FROM fastadmin.order_item group by seller_sku');
        $bgClass = ['st-green','st-green','st-green','st-green','st-violet','st-violet','st-violet','st-violet','st-blue','st-blue','st-blue','st-blue'];
        for ($i = 0; $i < count($totalOrderItem); $i++){
            $totalOrderItem[$i]['bgClass'] = $bgClass[$i];
            $totalOrderItem[$i]['sku'] = preg_replace('/HOLSEM[-| ]/','',$totalOrderItem[$i]['sku']);
        }

        $orderItems = Db::query('SELECT from_unixtime(create_time,\'%Y年%m月%d日\') createTime,seller_sku,count(*) num from fastadmin.order_item 
group by createTime,seller_sku;');
        $ctTimeItems = [];
        for ($i = 0; $i < count($orderItems); $i++){
            $item = $orderItems[$i];
            if(array_key_exists($item['createTime'],$ctTimeItems)){
                $ctTimeItems[$item['createTime']][$item['seller_sku']] = $item['num'];
            }else{
                $ctTimeItems[$item['createTime']] = [];
                $ctTimeItems[$item['createTime']][$item['seller_sku']] = $item['num'];
            }
        }

        $this->view->assign([
            'totalOrderItem' => $totalOrderItem,
        ]);


        return $this->view->fetch();
    }

}