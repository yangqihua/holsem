<?php
/**
 * Created by PhpStorm.
 * User: yangqihua
 * Date: 2017/10/12
 * Time: 下午12:32
 */

namespace app\api\controller\amazon;

use app\common\controller\Api;
use app\common\model\amazon\Order as OrderModel;
use app\common\model\amazon\OrderItem as OrderItemModel;
use app\common\model\amazon\Track as TrackmModel;
use Sauladam\ShipmentTracker\ShipmentTracker;

use SSilence\ImapClient\ImapClientException;
use SSilence\ImapClient\ImapClient as Imap;

use app\admin\model\Config;

use fast\Http;

class Inventory extends Api
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

    public function getInventoryList()
    {
        return json(['title' => '执行order测试', 'time' => date("Y-m-d H:i:s"), 'code' => 0]);
    }

}