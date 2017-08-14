<?php

namespace app\common\model\amazon;

use think\Model;

class Order extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $table = 'order';

    protected $autoWriteTimestamp = 'int';

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }
    
}
