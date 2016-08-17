<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	class Order {
		//购物车商品加入
		function cartadd() {
			debug(0);
			$cart['atime'] = time();  //购物时间
			$cart['pid']  = intval($_GET['pid']); //选购的产品编号
			$cart['pnum'] = intval($_GET['pnum']); //选购的产品数量

			//创建数据库对象，从数据中或取产品信息
			$productdb = D('product'); 
			$product = $productdb -> find($cart['pid']);

			//先前端一个值是 -1 说明库存不足
			$result = '-1';

			//如果产品中的存量大于用户选构的数量，说明库存充足
			if ($product['num'] >= $cart['pnum']) {
				//如果是登录用户，使用表来管理用户购物车
				if (bro_login('user')) {
					$cart['uid'] = $_SESSION['id'];	 //将当前用户的id放到购物车中
					
					//先查询购物车表
					$cartdb = D('cart');
					$carttab = $cartdb -> where(array('pid'=>$cart['pid'], 'uid'=>$cart['uid'])) -> find();

					//如果按用户ID和商品ID在构物车表中找到了, 并且有购买数量， 说明这个用户已经将商品放入了构物车, 则只更新购买数量
					if ($carttab['pnum']) {
						//更新购物车表中的数量是原来数量加上新输入的数量
						$result = $cartdb -> update(array("id"=>$carttab['id'], "pnum"=>$carttab['pnum']+$cart['pnum'])) ? true : false;
					}
					else {
						//在表中新加一条记录
						$result = $cartdb -> insert($cart) ? true : false;
					
					}
				//如果用户还没有登录，使用cookie保存购物车	
				} else {
					//先从cookie中将购物记录拿出来, 存时是序例化的， 取时使用反序列化回数组
					$cart_list = unserialize(stripslashes($_COOKIE['cart_list']));
				
					//产品ID编号作为索引
					$product_index = $cart['pid'];

					//也是先判断是否已经有购物记录了， 如果有，则改数量没有则新创建
					if (is_array($cart_list[$product_index])) {
						//更新购物车表中的数量是原来数量加上新输入的数量
						$cart_list[$product_index]['pnum'] = $cart_list[$product_index]['pnum'] + $cart['pnum'];
					} else {
						//在cookie中创建一个数组作为购物的一条数据
						$cart_list[$product_index] = $cart;
					}
					$result = is_array($cart_list[$product_index]) ? true : false;
					setcookie('cart_list', serialize($cart_list), 0, '/');
				}
			}
			echo json_encode(array('result'=>$result));
			exit;
		}

		//购物车商品更改数量（为零则删除）
		function cartnum() {
			debug(0);
			$pid = $_GET['pid'];  //ajax传过来的产品编号ID
			$pnum = $_GET['pnum']; //ajax传过来的产品数量

			//如果是登录用户则处理表
			if (bro_login('user')) {
			
				$where['uid'] = $_SESSION['id'];   //通过session获取当前登录用户的id
				$where['pid'] = intval($pid);
			
				//创建数据库对象，
				$cartdb = D('cart'); 
				$result = $pnum ? $cartdb->where($where)->update("pnum='{$pnum}'") : $cartdb->where($where)->delete();
		
			//如果不是登录用户处理cookie
			}else {
				//从cookie中获取购物车数据
				$cart_list = unserialize(stripslashes($_COOKIE['cart_list']));
				//如果ajax传过来的数量不为0, 就更改购物车的数量
				if ($pnum) {

					$cart_list[$pid]['pnum'] = $pnum;
					$result = is_array($cart_list[$pid]) ? true : false;
				}
				//如果为0 则删除
				else {
					//从数组中删除
					unset($cart_list[$pid]);
					$result = is_array($cart_list[$pid]) ? false : true;
				}
				//重新写回cookie
				setcookie('cart_list', serialize($cart_list), 0, '/');
			}
			//重新获取价格信息
			$cart_list = cart_list($cart_list);
			echo json_encode(array('result'=>$result, 'money'=>$cart_list['money']));	
			exit;	
		}

		//订单增加
		function add() {
			//从缓存中获取支付方式
			//创建一个缓存对象 
			$cache = new FileCache();

			//从缓存中获取网站的设置信息
			$payway = $cache -> get('payway');

			if(!$setting) {
				//从数据库获取信息
				$db = D("payway");
				$tmppay = $db->order('ord asc,id asc')->select();

				$payway = array();

				foreach($tmppay as $v) {
					$payway[$v['mark']] = $v;
				}
				
				//写入缓存
				$cache -> set("payway", $payway);
			}


			

			$this -> assign("payway", $payway);



			//获取购物车记录######
			$cart_list = cart_list(unserialize(stripslashes($_COOKIE['cart_list'])));
			$info_list = $cart_list['list']; //购物车列表
			$this->assign("info_list", $info_list);
			$money = $cart_list['money'];
			$this->assign("money",  $money);
		
		
		
			//如果提交订单，则处理-----------------------
			if (isset($_POST['do_submit'])) {
				//如果用户没有购买商品，就不能提交生成订单
				!count($info_list) && $this->error('购物车商品为空...');
				//如果不选择支付方式， 也不能提交订单
				!$_POST['payway'] && $this->error('请选择付款方式...');


				$orderdb = D('order');
				//从定单表中把最后一个订单找到
				$order = $orderdb->order('id desc')->find();
				
			
				//组织订单数据
				//如果订单ID不以6位的年月日开始，就设置第一个ID是以6位的年月日开始的从1开始的编号
				if(substr($order['id'], 0 , 6) != date('ymd')) {
					$sql_order['id']  = date('ymd').'0001';
				}
			
				
				$sql_order['productmoney'] = $money['order_productmoney'];   //产品总价
				$sql_order['wlmoney'] = $money['order_wlmoney'];	     //物流总价
				$sql_order['money'] = $money['order_money'];                 //总价
				$sql_order['atime'] = time();                                //订单生成时间,使用当前时间
				$sql_order['payway'] = $_POST['payway'];		     //支付方式
				$sql_order['content'] = $_POST['content'];                //订单留言 		     
				$sql_order['uid'] = $_SESSION['id'];                         //提交用户
				$sql_order['uname'] = $_SESSION['name'];                 //提交用户名
				$sql_order['uaddress'] = "{$_POST['province']}{$_POST['city']}{$_POST['address']}";   //用户的邮寄地址
				$sql_order['utname'] = $_POST['tname'];                       //接货人姓名
				$sql_order['uphone'] = $_POST['phone'];                       //接货人手机
				
				//入库， 如果成功，写入订单明细
				if ($order_id = $orderdb->insert($sql_order)) {
					$orderdatadb = D('orderdata');
					$productdb = D('product');
					foreach ($info_list as $v) {
						$sql_orderdata['pid'] = $v['pid'];             //产品ID
						$sql_orderdata['pname'] = $v['name'];          //产品名称
						$sql_orderdata['plogo'] = $v['logo'];          //产品标志
						$sql_orderdata['pmoney'] = $v['money'];        //产品价格
						$sql_orderdata['pnum'] = $v['pnum'];           //购买数量
						$sql_orderdata['oid'] = $order_id;             //定单编号orderid
						//将购物车中的每个购买的商品一条条放入订单明细
						$orderdatadb -> insert($sql_orderdata);
						//将每个产品数量对应减去
						$productdb ->where(array('id'=>$v['pid']))-> update("num = num-{$v['pnum']} ");

					
					}

					//清空购物车
					if (bro_login('user')) {
						//创建购物车操作对象，并通过当前登录用户ID删除全部记录
						$cartdb = D('cart');
						$cartdb -> delete(array('uid'=>$_SESSION['id']));	
					}
					else {
						//将cart_list设置为空
						setcookie('cart_list', '', 0, '/');
					}
					$this->success('订单提交成功！', 1, "order/pay/id/{$order_id}");
				}
				else {
					$this->error('订单提交失败...');
				}
					
			}
			
		
			
			//---------------------------------------
			//调用用户个人信息里的收货地址
			$userdb = D('user');
			$this->assign("info", $userdb->find($_SESSION['id']));

			$this->assign("title", "填写收货信息");
	
			$this->display(); 
		}

		//选择支付方式
		function pay() {
			$order_id = $_GET['id'];  //获取订单ID
		
	
			//创建一个缓存对象 
			$cache = new FileCache();

			//从缓存中获取网站的设置信息
			$payway = $cache -> get('payway');

			if(!$setting) {
				//从数据库获取信息
				$db = D("payway");
				$tmppay = $db->order('ord asc,id asc')->select();

				$payway = array();

				foreach($tmppay as $v) {
					$payway[$v['mark']] = $v;
				}
				
				//写入缓存
				$cache -> set("payway", $payway);
			}


			

			$this -> assign("cache_payway", $payway);

		


			$orderdb = D('order');

			$order = $orderdb ->where(array("id"=>$order_id, 'state'=>1))-> find();
			


			!$order['id'] && $this->error('订单号错误...');

			$this->assign($order);
			//如果是支付宝
			if (isset($_POST['do_submit'])) {
				//需要加上支付信息使用接口
				echo '正在为您连接支付网站，请稍后...';
				die();
				
			} 
		
		
			$this->assign($order);
			$this->assign('title', '选择支付方式');
			$this->display();
		}

		//订单查询
		function plist() {

			$orderdb = D('order');
			$orderdatadb = D('orderdata');

			$info = $orderdb->where(array('id'=>$_GET['id'], 'uphone'=>$_GET['uphone']))->find();

			$product_list = $orderdatadb ->where(array('oid'=>$_GET['id']))-> select();

			$this->assign($info);
			$this->assign("product_list", $product_list);

			
			$this->assign('title', '订单查询');
			$this->display();
		}

	}


	//购物车商品列表和价格
	function cart_list($cookie_cart_list=array()) {
		debug(0);
		//如果是登录用户， 从数据中获取购物车
		if (bro_login('user')) {
			$cartdb = D('cart');
			//通过登录中session中的用户id从，购物车中获取数据
			$cart_list = $cartdb ->where(array("uid"=>$_SESSION['id']))-> select();
		} else {
			//如果不是登录用户，则从cookie中获取数据
			$cart_list = is_array($cookie_cart_list) ? $cookie_cart_list : array();
		}


		
		$money['order_productmoney'] = 0;  //产品的总价格
		$money['order_wlmoney'] = 0;   //物流的总费用

		$productdb = D('product');
		
		foreach ($cart_list as $k=>$v) {
			$v['pid'] = intval($v['pid']);
		
			//按产品ID找到产品全部信息
			$product = $productdb ->field('id as pid, name, logo,money, wlmoney, num as product_maxnum')-> find($v['pid']);

			//数组的索引,使用产品ID编号
			$product_index = $v['pid'];
			//将商品的全部内容放入数组$info_list， 以产品为ID下标的数组
			$info_list[$product_index] = $product;
			//将当前产品购买的数量放入这个数组
			$info_list[$product_index]['pnum'] = intval($v['pnum']);
			//总费用就是每个产品的价格， 乘以购买的数量
			$money['order_productmoney'] += $v['pnum'] * $product['money'];
			//同一个订单上，物流费用， 购买同一个产品多份， 按一次物流价格
			$money['order_wlmoney'] += $product['wlmoney'];
		}
		//总费用，就是产品加物流
		$money['order_money'] = number_format($money['order_wlmoney'] + $money['order_productmoney'], 1, '.', '');
		$money['order_productmoney'] = number_format($money['order_productmoney'], 1, '.', '');
		$money['order_wlmoney'] = number_format($money['order_wlmoney'], 1, '.', '');

		return array('list'=>(array)$info_list, 'money'=>$money);
		exit;
	}
