<?php
/*******************************************************************************
 * Copyright 2009-2017 Amazon Services. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 *
 * You may not use this file except in compliance with the License.
 * You may obtain a copy of the License at: http://aws.amazon.com/apache2.0
 * This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 *******************************************************************************
 * PHP Version 5
 * @category Amazon
 * @package  Marketplace Web Service Orders
 * @version  2013-09-01
 * Library Version: 2017-02-22
 * Generated: Thu Mar 02 12:41:08 UTC 2017
 */

/**
 * List Order Items Sample
 */


require_once('.config.inc.php');
require_once('../mail.php');
require_once('../mail_text.php');
require_once('../class.smtp.php');
require_once('../class.phpmailer.php');


main();

function main()
{

    $mailText = getMailText();
    if(!isset($_GET['to'])){
        return false;
    }
    $message = '';

    $_GET['orderId'] = triMall($_GET['orderId']);
    $_GET['to'] = triMall($_GET['to']);
    if (!isset($_GET['orderId']))//判断是否有Get参数
    {
        $subject = $mailText['no_order_subject'];
        $message .= $mailText['no_order_text'];
    } else {
        $orderId = triMall($_GET['orderId']);
        $sellerSKUs = getSellerSKUs($orderId);

        if(!isset($_GET['name'])){
            $_GET['name'] = 'customer';
        }
        $message = sprintf($mailText['text_welcome'],$_GET['name']);

        if (isset($sellerSKUs['mesg']) && !(strpos($sellerSKUs['mesg'],"Invalid AmazonOrderId")===false)) {   // 订单号错误
            $subject = $mailText['error_order_subject'];
            $message .= sprintf($mailText['error_order_text'], $orderId);
         } else if (isset($sellerSKUs['mesg']) && !(strpos($sellerSKUs['mesg'],"Failed to connect to mws.amazonservices.com port 443: Operation timed out")===false)) {   // 由于网络原因爬不到或代理服务器不可用
            $subject = $mailText['has_order_subject'];
            $message .= sprintf($mailText['has_order_text'], '');
        } else {   // 正确查到订单号
            $subject = $mailText['has_order_subject'];
            $linkString = '';
            if (strpos($orderId, "WM")) {
                foreach ($sellerSKUs as $item) {
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
                $message .= sprintf($mailText['has_order_text'], $linkString);
            } else {
                foreach ($sellerSKUs as $item) {
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
                $message .= sprintf($mailText['has_order_text'], $linkString);
            }

        }
    }
    $message .= $mailText['text_thanks'];
    sendMail($_GET['to'], $subject, $message);
//    smtp_mail ($_GET['to'], $subject, $message, "904693433@qq.com");
}


function smtp_mail ($sendto_email, $subject, $body, $user_name)
{
    $mail = new PHPMailer();
    $mail->SMTPDebug = 1;            // 开启Debug
    $mail->IsSMTP();                // 使用SMTP模式发送新建
    $mail->Host = "smtp.exmail.qq.com"; // QQ企业邮箱SMTP服务器地址
    $mail->Port = 465;  //邮件发送端口
    $mail->SMTPAuth = true;         // 打开SMTP认证，本地搭建的也许不会需要这个参数
    $mail->SMTPSecure = "ssl";        // 打开SSL加密，这里是为了解决QQ企业邮箱的加密认证问题的~~
    $mail->Username = "customerservice@holsem.com";   // SMTP用户名  注意：普通邮件认证不需要加 @域名，我这里是QQ企业邮箱必须使用全部用户名
    $mail->Password = "Holsem2017";        // SMTP 密码
    $mail->From = "customerservice@holsem.com";      // 发件人邮箱
    $mail->FromName = "hhhhhhh";  // 发件人

    $mail->CharSet = "UTF-8";            // 这里指定字符集！
    $mail->Encoding = "base64";
    $mail->AddAddress($sendto_email, $user_name);  // 收件人邮箱和姓名
    //$mail->AddBCC("邮箱", "ff");
    //$mail->AddBCC("邮箱", "ff");这些可以暗送
    //$mail->AddReplyTo("test@jbxue.com","aaa.com");
    //$mail->WordWrap = 50; // set word wrap
    //$mail->AddAttachment("/qita/htestv2.rar"); // 附件
    //$mail->AddAttachment("/tmp/image.jpg", "new.jpg");
    $mail->IsHTML(true);  // send as HTML
    // 邮件主题
    $mail->Subject = $subject;
    // 邮件内容
    $mail->Body = "   
			<html><head>
				<meta http-equiv=\"Content-Language\" content=\"zh-cn\">   
				<meta http-equiv=\"Content-Type\" content=\"text/html; charset=GB2312\">   
			</head>   
			<body>   
				你好，请链接将在24h内过期。请尽快验证您的邮箱~
			</body>   
			</html>   
			";

    $mail->AltBody = "text/html";
    if (!$mail->Send()) {
        $error = $mail->ErrorInfo;
        echo iconv("GB2312","UTF-8",$mail->ErrorInfo);
        /*if($error=="smtpnot")//自定义错误，没有连接到smtp，掉包的情况，出现这种情况可以重新发送
         {
        sleep(2);
        $song=<a href="http://www.jbxue.com/shouce/php5/function.explode.html" target="_blank" class="infotextkey">explode</a>("@",$sendto_email);
        $img="<img height='0' width='0' src='http://www.jbxue.com/email.php?act=img&mail=".$sendto_email."&table=".$mail_table."' />";
        smtp_mail($sendto_email,"发送".$song[0].$biaoti, 'NULL', 'abc',$sendto_email,$host,$mailname,$mailpass,
                $img."发送".$song[0].$con,'$mail_table');//发送邮件
        }*/
        //$sql="insert into error(error_name,error_mail,error_smtp,error_time,error_table) values('$error','$sendto_email','$mailname',now(),'$mail_table')";
        //$query=<a href="http://www.jbxue.com/shouce/php5/function.mysql-query.html" target="_blank" class="infotextkey">mysql_query</a>($sql);//发送失败把错误记录保存下来
        return false;
    } else {
        return true;
    }
}


function sendMail($to, $subject, $text)
{
    $mail = new Mail();
    $mail->protocol = 'smtp';
    $mail->smtp_hostname = 'smtp.exmail.qq.com';
    $mail->smtp_username = 'customerservice@holsem.com';
//    $mail->smtp_username = 'yangqihua@dowish.net';
//    $mail->smtp_password = 'Holsem2017';
    $mail->smtp_password = 'CZE8g9TqziE5zsJ3';
//    $mail->smtp_password = 'Yang199411211';
    $mail->smtp_port = 25;
    $mail->smtp_timeout = 5;

    $mail->setFrom($mail->smtp_username);
    $mail->setSender('HOLSEM');
    $mail->setTo($to);
    $mail->setSubject($subject);
    $mail->setText($text);
    $mail->send();

}

function getSellerSKUs($orderId)
{

    $serviceUrl = "https://mws.amazonservices.com/Orders/2013-09-01";

    $config = array(
        'ServiceURL' => $serviceUrl,
        'ProxyHost' => null,
        'ProxyPort' => -1,
        'ProxyUsername' => null,
        'ProxyPassword' => null,
        'MaxErrorRetry' => 3,
    );

    $service = new MarketplaceWebServiceOrders_Client(
        AWS_ACCESS_KEY_ID,
        AWS_SECRET_ACCESS_KEY,
        APPLICATION_NAME,
        APPLICATION_VERSION,
        $config);
//    $service = new MarketplaceWebServiceOrders_Mock();
    $request = new MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();
    $request->setSellerId(MERCHANT_ID);

    $request->setAmazonOrderId($orderId);
    $sellerSKUs = invokeListOrderItems($service, $request);
    return $sellerSKUs;

}


function invokeListOrderItems(MarketplaceWebServiceOrders_Interface $service, $request)
{
    try {
        $response = $service->ListOrderItems($request);

        $orderItemList = $response->getListOrderItemsResult()->getOrderItems();

        $sellerSKUs = [];
        foreach ($orderItemList as $orderItem) {
            $sellerSKUs[] = $orderItem->getSellerSKU();
        }
        return $sellerSKUs;

    } catch (MarketplaceWebServiceOrders_Exception $ex) {
        return array("mesg" => $ex->getMessage(), "code" => $ex->getStatusCode());
    }
}

function triMall($str)//删除空格
{
    $qian=array(" ","　","\t","\n","\r");
    $hou=array("","","","","");
    return str_replace($qian,$hou,$str);
}

