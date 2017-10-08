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
        for ($i = 0; $i < 7; $i++) {
            $day = date("Y-m-d", $seventtime + ($i * 86400));
            $createlist[$day] = mt_rand(20, 200);
            $paylist[$day] = mt_rand(1, mt_rand(1, $createlist[$day]));
        }


        $totalOrderItem = Db::query('SELECT seller_sku sku,count(*) num FROM fastadmin.order_item group by seller_sku');
        $bgClass = ['st-green', 'st-green', 'st-green', 'st-green', 'st-violet', 'st-violet', 'st-violet', 'st-violet', 'st-blue', 'st-blue', 'st-blue', 'st-blue'];
        for ($i = 0; $i < count($totalOrderItem); $i++) {
            $totalOrderItem[$i]['bgClass'] = $bgClass[$i];
            $totalOrderItem[$i]['sku'] = preg_replace('/HOLSEM[-| ]/', '', $totalOrderItem[$i]['sku']);
        }

        $firstDay = strtotime(date('Y-m-d',strtotime("-2 month")));
        $sql = 'SELECT from_unixtime(create_time,\'%m月%d日\') createTime,seller_sku,count(*) num from fastadmin.order_item 
WHERE create_time>:firstDay group by createTime,seller_sku;';
        $orderItems = Db::query($sql,['firstDay'=>$firstDay]);
        $ctTimeItems = [];
        for ($i = 0; $i < count($orderItems); $i++) {
            $item = $orderItems[$i];
            if (array_key_exists($item['createTime'], $ctTimeItems)) {
                $ctTimeItems[$item['createTime']][preg_replace('/HOLSEM[-| ]/', '', $item['seller_sku'])] = $item['num'];
            } else {
                $ctTimeItems[$item['createTime']] = [];
                $ctTimeItems[$item['createTime']][preg_replace('/HOLSEM[-| ]/', '', $item['seller_sku'])] = $item['num'];
//                $ctTimeItems[$item['createTime']]['createTime'] = $item['createTime'];
            }
        }
        $allKeys = [];
        foreach ($ctTimeItems as $value) {
            foreach ($value as $k=>$v) {
                if(!(in_array($k,$allKeys))){
                    $allKeys[] = $k;
                }
            }
        }
        sort($allKeys);

        foreach ($ctTimeItems as $k=>$value) {
            foreach ($allKeys as $key) {
                if(!array_key_exists($key,$value)){
                    $ctTimeItems[$k][$key] = 0;
                }
            }
            ksort($ctTimeItems[$k]);
        }

        $resultList = [];
        foreach ($ctTimeItems as $value) {
            $resultList[] = array_values($value);
        }
        $result = [array()];
        for ($i = 0; $i < count($resultList); $i++) {
            for ($j = 0; $j < count($allKeys); $j++) {
                if (array_key_exists($i, $resultList) && array_key_exists($j, $resultList[$i])) {
                    $result[$j][$i] = $resultList[$i][$j];
                } else {
                    $result[$j][$i] = 0;
                }
            }
        }

        $date = array_keys($ctTimeItems);

        $this->view->assign([
            'totalOrderItem' => $totalOrderItem,
            'date' => $date,
            'keys' => $allKeys,
            'data' => $result,

        ]);


        return $this->view->fetch();
    }

}