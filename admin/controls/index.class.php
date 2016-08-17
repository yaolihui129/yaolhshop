<?php
	defined('IN_BROSHOP') or exit('No permission resources.');

	class Index  {
		function index() {

	
			//一些SEO的设置，每个页面显示标题
			$seo['title'] = "后台首页 - 欢迎使用BroShop商城系统";
			$this->assign("seo", $seo);
			
			//商品统计

			$product = D('product');  //获取商品对象

			//上架商品
			$tongji['product_up'] = $product->where(array("state"=>1))->total();
			//下架商品
			$tongji['product_down'] = $product->where(array("state"=>2))->total();
			//缺货商品
			$tongji['product_empty'] =  $product->where(array("state"=>1,"num <"=>1))->total();
			//推荐商品
			$tongji['product_tuijian'] =  $product->where(array("state"=>1,"istuijian"=>1))->total();

			
			//访客统计
			$today =  strtotime(date('Y-m-d'));
			$yesterday = strtotime(date('Y-m-d',strtotime('-1 day')));

		
			$iplog = D('iplog');
			
			//今日访客
			$tongji['iplog_today'] = $iplog->where(array("atime >="=>$today))->total();
		
			//昨日访客
			$tongji['iplog_lastday'] = $iplog->where(array("atime >="=>$yesterday, "atime <"=>$today))->total();
			//累计访客
			$tongji['iplog_all'] =  $iplog->total();;
			//累计注册
			$tongji['iplog_user'] = D('user')->total();



			
			//交易数据
			
			$order = D('order');

			//今日订单
			$tongji['order_today'] = $order->where(array("atime >="=>$today))->total();
			
			$money = $order -> field("sum(productmoney) as money_today")->where(array("atime >="=>$today))->find(); 

			$tongji['money_today'] = empty($money['money_today']) ? "0.00" : $money['money_today'];
			
			//昨日订单
			$tongji['order_lastday'] = $order->where(array("atime >="=>$yesterday, "atime <"=>$today))->total();

			$money = $order -> field("sum(productmoney) as money_lastday")->where(array("atime >="=>$yesterday, "atime <"=>$today))->find(); 
			$tongji['money_lastday'] = empty($money['money_lastday']) ? "0.00" :$money['money_lastday'];
			
			//全部订单
			$tongji['order_all'] =  $order->total();

			$money = $order -> field("sum(productmoney) as money_all")->find(); 
			$tongji['money_all'] = empty($money['money_all']) ? "0.00" : $money['money_all'];
			
			$this -> assign($tongji);


			$this->display();
		}
	}
