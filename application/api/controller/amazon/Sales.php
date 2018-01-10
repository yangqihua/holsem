<?php
/**
 * Created by PhpStorm.
 * User: yangqihua
 * Date: 2018/1/10
 * Time: 下午6:34
 */

namespace app\api\controller\amazon;

use app\common\controller\Api;
use think\Db;
use app\admin\model\Sales as SalesModel;
use app\common\model\amazon\Order as OrderModel;
use app\common\model\amazon\OrderItem as OrderItemModel;
use app\common\model\amazon\Track as TrackmModel;
use Sauladam\ShipmentTracker\ShipmentTracker;

use SSilence\ImapClient\ImapClientException;
use SSilence\ImapClient\ImapClient as Imap;

use app\admin\model\Config;

use fast\Http;

class Sales extends Api
{
    private $salesModel;

    function __construct()
    {
        parent::__construct();
        $this->salesModel = new SalesModel();
    }

    public function asyc()
    {

        $sql = 'select max(order_item_id) as result from sales;';
        $maxOrderItemIds = Db::query($sql);
        $maxOrderItemId = $maxOrderItemIds[0]['result'] ? $maxOrderItemIds[0]['result'] : 0;

        $sql = 'SELECT oi.id as order_item_id ,seller_sku as sku,item_price,promotion_discount as item_promotion,amazon_order_id,
from_unixtime(oi.create_time,\'%Y-%m-%d:%H:%m:%s\') as purchase_date
 FROM `order_item` as oi left join `order` as o on oi.order_id=o.id 
 where oi.id>:maxOrderItemId and oi.create_time>1514736000 and purchase_date is not null  order by oi.id asc limit 100;';
        $orderItems = Db::query($sql, ['maxOrderItemId' => $maxOrderItemId]);

        $finalResults = [];
        foreach ($orderItems as $item){
            $item_price_result = json_decode($item['item_price'],true);
            $item_price = '无';
            if($item_price_result){
                $item_price = $item_price_result['Amount'];
            }

            $item_promotion_result = json_decode($item['item_promotion'],true);
            $item_promotion = '无';
            if($item_promotion_result){
                $item_promotion = $item_promotion_result['Amount'];
            }
            $finalResult = ["order_item_id"=>$item['order_item_id'],'sku'=>$item['sku'],'amazon_order_id'=>$item['amazon_order_id'],
                'item_price'=>$item_price,'item_promotion'=>$item_promotion,'purchase_date'=>$item['purchase_date']];
            $finalResults[] = $finalResult;
        }
        $this->salesModel->saveAll($finalResults);
        return json(['time' => date("Y-m-d H:i:s"), 'title' => 'asyc', 'code' => 200, 'message' => 'ok']);
    }
}