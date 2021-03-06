<?php

namespace app\api\controller;

use app\admin\model\VipUser;
use app\common\controller\Api;
use app\common\library\Email;

class Welcomemail extends Api
{

    // 1. 获取订单
    // 2. 发送邮件
    // 3. 保存结果信息
    public function send()
    {
        $data = $_POST;
        $welcomeTextConfig = config('welcome_mail.text');
        $welcome = sprintf($welcomeTextConfig['welcome'], input('firstname', ''));
        $mailText = $welcome;
        $to = input('email', '');
        $subject = '';
        $remark = '';

        $order_id = input('order_id', '');
        // 无orderid发对应的邮件
        if (!$order_id) {
            $subject = $welcomeTextConfig['no_order']['subject'];
            $mailText .= $welcomeTextConfig['no_order']['content'];

        } else {
            $orderItemResult = getOrderItemList($order_id);
            // 获取订单成功
            if ($orderItemResult['code'] == 200) {
                $orderItemList = $orderItemResult['orderItemList'];
                $skus = [];
                foreach ($orderItemList as $k => $orderItem) {
                    $skus[] = $orderItem['seller_sku'];
                }
                $data['skus'] = implode(",", $skus);
                $asins = [];
                foreach ($orderItemList as $k => $orderItem) {
                    $asins[] = $orderItem['asin'];
                }
                $data['asin'] = implode(",", $asins);
                $links = $this->getLinkBySkus($order_id, $skus);

                $subject = $welcomeTextConfig['has_order']['subject'];
                $mailText .= sprintf($welcomeTextConfig['has_order']['content'], $links);
            } else {
                $remark .= '订单获取失败，原因：' . $orderItemResult['message'] . '，错误码：' . $orderItemResult['code'] . '。';
                trace($remark, 'error');
                $subject = $welcomeTextConfig['error_order']['subject'];
                $mailText .= sprintf($welcomeTextConfig['error_order']['content'], $order_id);
            }
            $orderResult = getOrder($order_id);
            if ($orderResult['code'] == 200) {
                $data['order_date'] = $orderResult['order']['purchase_date'];
            }
        }
        $mailText .= $welcomeTextConfig['thanks'];
        // 如果有炸锅，则发对应的邮件内容
        if (array_key_exists('skus', $data) && (strpos($data['skus'], 'A1') > 0 || strpos($data['skus'], 'A2') > 0 || strpos($data['skus'], 'A3') > 0)) {
            $aConfig = config('welcome_mail.a');
            $subject = $aConfig['subject'];
            if (strpos($data['skus'], 'A1') > 0) {
                $link = "\n" . 'https://www.amazon.com/dp/B072JJBZ37 ';
            } else if (strpos($data['skus'], 'A2') > 0) {
                $link = "\n" . 'https://www.amazon.com/dp/B071W83YND ';
            }else{  // A3
                $link = "\n" . 'https://www.amazon.com/dp/B076HHRFFB ';
            }
            $mailText = sprintf($aConfig['content'], input('firstname', ''), $link);
        }
        $sendResult = $this->sendMail($to, $subject, $mailText);
        $remark .= $sendResult['message'];
        $data['mail_msg'] = $mailText;
        $data['remark'] = $remark;
        $vipUserModel = new VipUser($data);
        $vipUserModel->allowField(true)->save();
        return json(['code' => 200, 'message' => 'success']);
    }

    private function getLinkBySkus($orderId, $skus)
    {
        $linkString = '';
        if (strpos($orderId, "WM")) {
            foreach ($skus as $item) {
                if ($item == "HOLSEM-X5") {
                    $linkString .= "\n" . 'https://www.walmart.com/ip/822532289';
                } else if ($item == "HOLSEM-X5B") {
                    $linkString .= "\n" . 'https://www.walmart.com/ip/458742879';
                } else if ($item == "HOLSEM-X8") {
                    $linkString .= "\n" . 'https://www.walmart.com/ip/880628079';
                } else if ($item == "HOLSEM-X8B") {
                    $linkString .= "\n" . 'https://www.walmart.com/ip/693128121';
                } else if ($item == "HOLSEM-X12" || $item == "HOLSEM-S12") {
                    $linkString .= "\n" . 'https://www.walmart.com/ip/920640622';
                }
            }
        } else {
            foreach ($skus as $item) {
                if ($item == "HOLSEM-U3") {
                    $linkString .= "\n" . 'https://www.amazon.com/dp/B01LD06EQ4';
                } else if ($item == "HOLSEM-X5") {
                    $linkString .= "\n" . 'https://www.amazon.com/dp/B01EV3DW5Q';
                } else if ($item == "HOLSEM-X5B") {
                    $linkString .= "\n" . 'https://www.amazon.com/dp/B01LXIDES4';
                } else if ($item == "HOLSEM-X8") {
                    $linkString .= "\n" . 'https://www.amazon.com/dp/B01ASWH5KW';
                } else if ($item == "HOLSEM-X8B") {
                    $linkString .= "\n" . 'https://www.amazon.com/dp/B01LYHJMTD';
                } else if ($item == "HOLSEM-X12" || $item == "HOLSEM-S12") {
                    $linkString .= "\n" . 'https://www.amazon.com/dp/B01H14SFRM';
                } else if ($item == "HOLSEM-X12B") {
                    $linkString .= "\n" . 'https://www.amazon.com/dp/B01LYHEC16';
                }
            }
        }
        return $linkString;
    }

    private function sendMail($to, $subject, $message)
    {
        $mailConfig = config('welcome_mail.mail');
        $options = [
            'mail_smtp_host' => $mailConfig['host'],
            'mail_smtp_user' => $mailConfig['username'],
            'mail_smtp_pass' => $mailConfig['password'],
            'mail_from' => $mailConfig['username'],
            'mail_smtp_port' => '465',
            'mail_verify_type' => 2
        ];
        $email = new Email($options);
        $result = $email
            ->to($to)
            ->subject($subject)
            ->message($message, false)
            ->send();
        if ($result) {
            return ['code' => 200, 'message' => '欢迎邮件发送成功。'];
        } else {
            return ['code' => 500, 'message' => '欢迎邮件发送失败，原因：' . $email->getError() . '。'];
        }
    }

    public function test()
    {
        $mailConfig = config('welcome_mail.mail');
        $options = [
//            'mail_smtp_host' => $mailConfig['host'],
//            'mail_smtp_user' => $mailConfig['username'],
//            'mail_smtp_pass' => $mailConfig['password'],
//            'mail_from' => $mailConfig['username'],
            'mail_smtp_port' => '587',
            'mail_verify_type' => '1'
        ];
        $email = new Email($options);
        $result = $email
            ->to("904693433@qq.com")
            ->subject("主题")
            ->message("this is test", false)
            ->send();
        if ($result) {
            return json(['code' => 200, 'message' => '欢迎邮件发送成功。']);
        } else {
            return json(['code' => 500, 'message' => '欢迎邮件发送失败，原因：' . $email->getError() . '。', 'options' => $options]);
        }
    }

}
