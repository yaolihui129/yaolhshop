<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
include('../../../../common.php');
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();
//验证成功
if ($verify_result) {	
	//商户订单号
	$out_trade_no = $_POST['out_trade_no'];
	//支付宝交易号
	$trade_no = $_POST['trade_no'];

	$info = $db->pe_select('order', array('order_id'=>$out_trade_no));
	//该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款
	if ($_POST['trade_status'] == 'WAIT_BUYER_PAY') {			
        echo "success";		//请不要修改或删除
    }
	//该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货
	elseif ($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
		if ($info['order_state'] == 'notpay') {
			$order['order_outid'] = $trade_no;
			$order['order_payway'] = 'alipay_db';
			$order['order_state'] = 'paid';
			$order['order_ptime'] = time();		
			$db->pe_update('order', array('order_id'=>$out_trade_no), $order);
		}
        echo "success";		//请不要修改或删除
    }
	//该判断表示卖家已经发了货，但买家还没有做确认收货的操作
	elseif ($_POST['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
		if ($info['order_state'] == 'paid') {
			$order['order_state'] = 'send';
			$order['order_stime'] = time();					
			$db->pe_update('order', array('order_id'=>$out_trade_no), $order);
		}
        echo "success";		//请不要修改或删除
    }
	//该判断表示买家已经确认收货，这笔交易完成
	else if($_POST['trade_status'] == 'TRADE_FINISHED') {
		if ($info['order_state'] == 'send') {
			$order['order_state'] = 'success';					
			$db->pe_update('order', array('order_id'=>$out_trade_no), $order);
		}
        echo "success";		//请不要修改或删除
    }
	//其他状态判断
    else {
        echo "success";
    }
}
//验证失败
else {
    echo "fail";
}
?>