<?php

namespace app\admin\controller\w;

use app\common\controller\Backend;
use think\Db;

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


    public function upload()
    {
        $file = $this->request->file('file');
        if (empty($file)) {
            $this->msg = "未上传文件或超出服务器上传限制";
            return;
        }
        $fileInfo = $file->getInfo();
        $tmp_name = $fileInfo['tmp_name'];
        $signs = [];

        if (file_exists($tmp_name)) {
            $file_arr = file($tmp_name);
            //逐行读取文件内容
            for ($i = 0; $i < count($file_arr); $i++) {
                $column = preg_split('/[\n\r\t\s]+/i', trim($file_arr[$i]));
                $workIdAndDate = $column[0] . "_" . $column[1];
                if (!array_key_exists($workIdAndDate, $signs)) {
                    $signs[$workIdAndDate] = ['start_time' => $column[2], 'end_time' => $column[2]];
                } else {
                    $time = $signs[$workIdAndDate];
                    if (strtotime(date("2017-10-10 ".$time['start_time'])) - strtotime(date("2017-10-10 ".$column[2]))>0) {
                        $signs[$workIdAndDate]['start_time'] = $column[2];
                    }
                    if (strtotime(date("2017-10-10 ".$time['end_time'])) - strtotime(date("2017-10-10 ".$column[2]))<0) {
                        $signs[$workIdAndDate]['end_time'] = $column[2];
                    }
                }
            }
        }
        $data = [];
        foreach ($signs as $key=>$value){
            $arr = explode("_",$key);
            $name = Db::table('w_user')->where("worker_id",$arr[0])->value('w_name');
            $worker_id = $arr[0];
            $date = $arr[1];
            $start_time = $value['start_time'];
            $end_time = $value['end_time'];
            $status = "";
            if(strtotime(date($date." ".$start_time))>strtotime(date($date." 09:00:00"))){
                $status = "迟到";
            }
            if(strtotime(date($date." ".$end_time))<strtotime(date($date." 18:30:00"))){
                $status .= " 早退";
            }
            $status = $status==""?"正常":$status;
            $data[] = ['name'=>$name,'worker_id'=>$worker_id,'date'=>$date,'start_time'=>$start_time,'end_time'=>$end_time,'status'=>$status];
        }
        if ($fileInfo['error']) {
            $this->msg = $file->getError();
            return;
        }

        $data = [
            'data' => $data
        ];

        $this->data = $data;
        $this->code = 1;
    }


}
