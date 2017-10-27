<?php
/**
 * Created by PhpStorm.
 * User: yangqihua
 * Date: 2017/10/12
 * Time: 下午12:32
 */

namespace app\api\controller\amazon;

use app\common\controller\Api;
use app\admin\model\Inventory as InventoryModel;

class Inventory extends Api
{
    protected $orderModel = null;
    protected $orderItemModel = null;
    private $delay = 1;

//    protected $noNeedLogin = ['inventorylist'];

    function __construct()
    {
        parent::__construct();
    }

    public function inventoryList()
    {
        $result = $this->getInventoryList();
        $inventoryList = $result['inventoryList'];
        $dbDataList = [];
        foreach ($inventoryList as $key => $inventory) {
            $dbData = [substr(strtolower($inventory['seller_sku']),7) => $inventory['count']];
            $dbDataList = array_merge($dbDataList, $dbData);
        }
        $inventoryModel = new InventoryModel($dbDataList);
        // 过滤post数组中的非数据表字段数据
        $records = $inventoryModel->allowField(true)->save();
        return json(['time' => date("Y-m-d H:i:s"), 'title' => 'inventoryList', 'code' => 200, 'message' => '插入记录数：' . $records, 'content' => $dbDataList]);
    }

    private function getInventoryList()
    {
        sleep($this->delay);
        $result = getInventoryList();
        if ($result['code'] != 200) {
            $this->delay = $this->delay * 2;
            trace('获取库存列表失败，原因： ' . $result['message'] . '，' . $this->delay . '后继续获取', 'error');
            $result = $this->getInventoryList();
        }
        return $result;
    }

}