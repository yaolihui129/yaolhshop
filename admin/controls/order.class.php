<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	/**
	 * 定单管理
	 */
	class Order {
		//定单列表
		function index() {

			$where = "";
			//按订单状态
			if(!empty($_GET['state'])) {
				$where['state'] = $_GET['state'];
			}

			//通过订单ID
			if(!empty($_GET['id'])) {
				$where['id'] = $_GET['id'];
			}

			//通过收货人姓名
			if(!empty($_GET['utname'])) {
				$where['utname'] = $_GET['utname'];
			}
			
				
			//通过接货人电话
			if(!empty($_GET['uphone'])) {
				$where['uphone'] = $_GET['uphone'];
			}
			

			$orderdb = D('order');
			
		//	$where = array('uid'=>$_SESSION['id']);

		

			$page = new Page($orderdb->where($where)->total(), 10);

			//获取全部订单
			$info_list =  $orderdb->where($where)->order('id desc')->limit($page->limit)->select();
			//获取全部订单明细
			$orderdatadb = D('orderdata');
			foreach ($info_list as $k => $v) {
				$info_list[$k]['product_list'] = $orderdatadb->where(array('oid'=>$v['id']))->select();    
			}

			
			$this->assign("paywayarr", array(1=>"支付宝付款", 2=>"转账付款", 3=>"货到付款"));
			$this->assign("info_list", $info_list);
			$this->assign('fpage', $page->fpage(0,4,6,8));


			$this->assign('title', '订单列表');
			$this->assign("menumark", "order");
			$this->display();
		}

		//订单修改
		function mod() {
			$order_id = $_GET['id'];
			$orderdb = D('order');
			if(isset($_POST['do_submit'])) {
				$_POST['money'] = $_POST['productmoney'] + $_POST['wlmoney'];
				
				if($orderdb->where(array('id'=>$order_id))->update($_POST)) {
			
					$this->success('订单修改成功!');
				}
				else {
					$this->error('订单修改失败...');
				}
			}	

			$info = $orderdb->find($order_id);

			$orderdatadb = D('orderdata');
			$product_list = $orderdatadb ->where(array('oid'=>$order_id))->select();
				
			//将全部订单发给前台
			$this->assign($info);
			$this->assign("product_list",$product_list);
	

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

			$this->assign("paywayarr", array(1=>"支付宝付款", 2=>"转账付款", 3=>"货到付款"));
			$this->assign("title", "修改订单");
			$this->display();
		}

		//订单删除
		function del() {
			$order_id = !empty($_POST['id']) ? $_POST['id'] : $_GET['id'];
		
			$orderdb = D('order');
	
			if($orderdb ->where(array('id'=>$order_id))-> delete()) {
				//将产品的库存加回去
				$orderdatadb = D('orderdata');
				$info_list = $orderdatadb->where(array('oid'=>$order_id))->field('id,pid,pnum')-> select();
				$productdb = D('product');
				foreach ($info_list as $v) {
					//将每个产品数量对应加回去
					$productdb ->where(array('id'=>$v['pid']))-> update("num = num+{$v['pnum']} ");
				}

				//删除订单子表数据
				$orderdatadb->delete(array('oid'=>$order_id));
			
				$this->success('订单删除成功!');
			}
			else {
				$this->error('订单删除失败...');
			}		
		}

		//订单状态更改
		function state() {
			$order_id = $_GET['id'];

			$orderdb = D('order');
			//根据state处理
			switch ($_GET['state']) {
				//付款
				case '2':
					//获取订单
					$order = $orderdb ->where(array('id'=>$order_id))-> find();
					$_POST['state'] = $order['payway'] == '3' ? '4' : 2; //如果支付是货到付款3，直接成功4， 否则2确认付款	
					$_POST['ptime'] = time();  //付款时间
					//更改订单状态
					if($orderdb->where(array('id'=>$order_id))->update($_POST)) {
						$this->success('订单付款成功!');
					}else{
						$this->error('订单付款失败...');
					}

					break;
				//发货	
				case '3':
					//如果用户提交
					if (isset($_POST['do_submit'])) {
						//获取订单
						$order = $orderdb ->where(array('id'=>$order_id))-> find();
					
						$_POST['stime']  = time();
						//货到付款
						if ($order['payway'] == '3') {
							//状态设置3 发货
							$_POST['state'] = '3';
						}
						//担保交易(支付宝)
						elseif ($order['payway'] == '1') {
							//状态设置3 发货
							$_POST['state'] = '3';
						}
						//即时到帐
						else {
							$_POST['state'] = '4';//即时到帐就不让用户确认了
						}

						//更改订单状态
						if ($orderdb->where(array('id'=>$order_id))->update($_POST)) {
							//更新商品售出数
							//将产品的库存加回去
							$orderdatadb = D('orderdata');
							$info_list = $orderdatadb->where(array('oid'=>$order_id))->field('id,pid,pnum')-> select();
							$productdb = D('product');
							foreach ($info_list as $v) {
								//将每个产品数量对应加回去
								$productdb ->where(array('id'=>$v['pid']))-> update("sellnum = sellnum+{$v['pnum']} ");
							}

							$this->success('商品发货成功!','','top');
						}
						else {
							$this->error('商品发货失败!','','top');
						}
					}
					$this->assign("order_id", $order_id);
					$this->assign('wllist', array('顺丰快递','申通快递','圆通快递','韵达快递','中通快递','EMS快递'));
					$this->display('send');
				
				break;
			}
		}

	}
