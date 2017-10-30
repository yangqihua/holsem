<?php

namespace app\api\controller;

use app\common\controller\Api;

class WelcomeMail extends Api
{

    public function send()
    {
        $data = input('post.');
        return json(['code' => 0,'data'=>$data]);
    }

}
