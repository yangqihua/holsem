<?php
/**
 * Created by PhpStorm.
 * User: yangqihua
 * Date: 2017/7/30
 * Time: 下午11:00
 */
function getMailText()
{
    $mailText['text_subject'] = '%s - Thank you for registering';

    $mailText['text_welcome'] = 'Dear %s,';

    $mailText['has_order_subject'] = 'Congratulations that you now have one extra year of warranty!';
    $mailText['has_order_text'] = '

Thank you for your purchase! 
 
Your order information and the warranty registration have been well received.

Congratulations that you now have one extra year of warranty!
 
If you are satisfied with our product and service, please leave your valuable review at Amazon.com. Your positive review will be not only a great encouragement but a boost for our business. 
 
We hope our surge protector can meet your various charging needs both at home and at your office.

How to submit a review:
1. Go to the product detail page for the item on Amazon.com.%s
2. Click Write a customer review in the Customer Reviews section.
3. Click Submit.';

    $mailText['error_order_subject'] = 'Wrong Order ID for HOLSEM Power Strip';
    $mailText['error_order_text'] = '
 
Thank you for your registration at our website www.holsem.com !
 
Sorry that we couldn’t find your order # %s in our store. Could you please go to your order list and send us the right order ID of our HOLSEM Power Strip?
';

    $mailText['no_order_subject'] = 'Warranty Extension-Order ID of HOLSEM Power Strip';
    $mailText['no_order_text'] = '

Thank you for your registration at our website www.holsem.com !

If you have purchased our product, please email us your Order ID(example:123-1234567-1234567), then your standard warranty will be extended for an additional year free of charge!

If you haven’t, we think you will be interested in our HOLSEM power strip. It features thorough sturdiness and safety. A stylish design further distinguishes our multifunctional product.
';

    $mailText['text_thanks'] = 'Thank you very much for your precious time! If you have any further questions, please do not hesitate to contact us.
 
We stand firmly behind our products and service.

Have a wonderful day!

Best regards,
Customer Service Team

HOLSEM｜Energize Your Life';

    return $mailText;

}