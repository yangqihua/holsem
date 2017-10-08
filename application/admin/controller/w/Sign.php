<?php

namespace app\admin\controller\w;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 签到列管理
 *
 * @icon fa fa-circle-o
 */
class Sign extends Backend
{

    /**
     * WSign模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('WSign');

    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function upload(){
        $file = $this->request->file('file');
        if (empty($file))
        {
            $this->msg = "未上传文件或超出服务器上传限制";
            return;
        }
        $fileInfo = $file->getInfo();
        $tmp_name = $fileInfo['tmp_name'];
        $content = '';
        if(file_exists($tmp_name)) {
            $file_arr = file($tmp_name);
            //逐行读取文件内容
            for ($i = 0; $i < count($file_arr); $i++) {
                $content.= $file_arr[$i] . "\n";
            }
        }
        if($fileInfo['error']){
            $this->msg = $file->getError();
            return;
        }

        $data = [
            'content' => $content
        ];

        $this->data = $data;
    }


}
