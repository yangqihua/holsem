<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use app\admin\model\Config as ConfigModel;
use app\admin\model\Inventory as InventoryModel;
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
        $firstDay = strtotime(date('Y-m-d', strtotime("-2 month")));
        $sql = 'SELECT from_unixtime(create_time,\'%m月%d日\') createTime,seller_sku,count(*) num from fastadmin.order_item 
WHERE create_time>:firstDay group by createTime,seller_sku;';
        $orderItems = Db::query($sql, ['firstDay' => $firstDay]);
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
            foreach ($value as $k => $v) {
                if (!(in_array($k, $allKeys))) {
                    $allKeys[] = $k;
                }
            }
        }
        sort($allKeys);
        foreach ($ctTimeItems as $k => $value) {
            foreach ($allKeys as $key) {
                if (!array_key_exists($key, $value)) {
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

        // 1.首页的当前库存量
        $sql = 'SELECT * from inventory where id=(select max(id) from inventory);';
        $latestInventories = Db::query($sql);
        $latestInventory = $latestInventories[0];

        $configModel = new ConfigModel();
        $configs = $configModel->where("group", "inventory")->select();

        // 1.首页的当前库存量
        $latestInventory7 = $latestInventory;
        $sql = 'SELECT * from inventory order by id limit 7;';
        $latestInventories7 = Db::query($sql);
        if (count($latestInventories7) == 7) {
            $latestInventory7 = $latestInventories7[6];
        }

        $inventoryDataList = [];
        foreach ($latestInventory as $key => $latestValue) {
            if ($key == 'id' || $key == 'createtime' || $key == 'updatetime') {
                continue;
            }

            $inventoryData['average'] = ($latestValue - $latestInventory7[$key]) / 7;

            $inventoryData['sku'] = $key;
            $inventoryData['inventory'] = $latestValue;

            foreach ($configs as $k => $config) {
                $name = $config['name'];
                $configValue = $config['value'];
                $cs = explode("_", $name);
                $c_sku = $cs[0];
                $kind = $cs[1];
                if ($c_sku == $key) {
                    if ($kind == 'inventory') {
                        $inventoryData['security_inventory'] = $configValue;
                    } else {
                        $inventoryData['inventory_duration'] = $configValue;
                    }
                    if (array_key_exists('security_inventory', $inventoryData)
                        && array_key_exists('inventory_duration', $inventoryData)
                    ) {
                        break;
                    }
                }
            }
            $p = $inventoryData['average'] * $inventoryData['inventory_duration'] + $inventoryData['security_inventory'];
            $statusClass = 'success';
            $status = '安全';
            $remark = '当前库存' . $latestValue . ' > ' . $p . '=(' . $inventoryData['average'] . '*' . $inventoryData['inventory_duration'] . '+' . $inventoryData['security_inventory'].')';
            if ($latestValue < $p && $latestValue > $inventoryData['security_inventory']) {
                $status = '紧张';
                $statusClass = 'warning';
                $remark = '安全库存' . $inventoryData['security_inventory'] . ' < 当前库存' . $latestValue . ' < ' . $p . '=(' . $inventoryData['average'] . '*' . $inventoryData['inventory_duration'] . '+' . $inventoryData['security_inventory'].')';
            } else if ($latestValue < $inventoryData['security_inventory']) {
                $status = '危险';
                $statusClass = 'danger';
                $remark = '当前库存' . $latestValue . ' < 安全库存' . $inventoryData['security_inventory'];
            }
            $inventoryData['status'] = $status;
            $inventoryData['remark'] = $remark;
            $inventoryData['statusClass'] = $statusClass;
            $inventoryDataList[] = $inventoryData;
        }

        // 2.首页的库存折线图
        $inventoryModel = new InventoryModel();
        $inventoryChartDataListDb = $inventoryModel->select();
        $inventoryChartDataList = [];
        foreach ($inventoryChartDataListDb as $key=>$value){
            $inventoryChartDataList['time'][] = datetime($value['createtime'],'Y-m-d');
            $inventoryChartDataList['s12'][] = $value['s12'];
            $inventoryChartDataList['x12b'][] = $value['x12b'];
            $inventoryChartDataList['x8b'][] = $value['x8b'];
            $inventoryChartDataList['x8'][] = $value['x8'];
            $inventoryChartDataList['x5b'][] = $value['x5b'];
            $inventoryChartDataList['x5'][] = $value['x5'];
            $inventoryChartDataList['a1'][] = $value['a1'];
        }

        $this->view->assign([
            'date' => $date,
            'keys' => $allKeys,
            'data' => $result,

            'inventoryDataList' => $inventoryDataList,
            'inventoryChartDataList'=>$inventoryChartDataList,
        ]);

        return $this->view->fetch();
    }

}