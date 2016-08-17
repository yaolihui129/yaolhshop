<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	class User {
		//用户登录
		function login() {
			if (isset($_POST['do_submit'])) {
				
				$name = $_POST['name'];
				$pw =  md5(md5('bro_'.$_POST['pw']));

				$db = D('user');
				//通过用户名和密码从数据中查找
				$user = $db -> field("id, name")->where(array("name"=>$name, "pw"=>$pw))->find();
				//如果找到用户则说明用户名和密码是对的， 设置登录
				if($user) {
					//设置用户的最后登录时间
					$db->update(array("id"=>$user['id'], 'ltime'=>time()));

					$_SESSION = $user; //将查出来的用户的信息都放到session中

					//设置一个前台user登录的暗号token
					$_SESSION['user_token'] = md5($user['id'].$_SERVER['HTTP_HOST']);


					//未登录时的购物车列表入库 ----------如果COOKIE有购物数据就取出放入对应用户的数据库
					if (is_array($cart_list = unserialize(stripslashes($_COOKIE['cart_list'])))) {
						//先从购物车中看看这个用户原来有没有购物数据
						$cartdb = D('cart');

						$data = $cartdb->where(array('uid'=>$user['id']))->select();
						//将数组的下标，使用产品ID标识
						$cart_rows = array();
						foreach($data as $v) {
							$cart_rows[$v['pid']] = $v;
						}

						//将每个商品放入购物车表
						foreach ($cart_list as $k => $v) {
							//如果COOKIE中的商品和购物车中原来商品一至，就更新两个数量相加
							if (array_key_exists($k, $cart_rows)) {
								//购物车的id作为更新条件， 更新对应的商品数量为, 购物车中加上cookie中的和
								$cartdb->update(array("id"=>intval($cart_rows[$k]['id']),'pnum'=>intval($cart_rows[$k]['pnum']+$cart_list[$k]['pnum'])));
							//如果原来用户就没有买过商品
							}else {
								//从cookie中将购买的商品整理出来
								$cart_info['atime'] = time();
								$cart_info['pid'] = intval($k);
								$cart_info['pnum'] = intval($v['pnum']);
								$cart_info['uid'] = $user['id'];
								//加入到数据库中
								$cartdb -> insert($cart_info);
							
							}
						}	
						setcookie('cart_list', '', time()-3600, '/');
					}

				

					$this->success('用户登录成功！', 1, $_GET['fromto']);
				
				} else {
					$this->error('用户名或密码错误...');
				}

		
			}
			
			$this->assign('title', '用户登录');



			$this->display();		
		}

		//用户退出
		function logout() {
			$_SESSION=array();

			if(isset($_COOKIE[session_name()])){
				setCookie(session_name(), '', time()-3600, '/');
			}

			session_destroy();

			$this->success('用户退出成功！');
		}

		//用户注册 
		function register() {
			$db = D("user");
			
			//如果是Ajax过来, 检查用户名是否存在
			if (!empty($_GET['name'])) {
				$finduser = $db ->where(array("name"=>$_GET['name']))-> find();

				if($finduser) {
					echo "false";
				}else{
					echo "true";
				}

				die();
			}

			if(isset($_POST['do_submit'])) {

				$_POST['pw'] = md5(md5('bro_'.$_POST['pw']));
				$_POST['atime'] = $_POST['ltime'] = time();

				if($lastinsertid = $db->insert()) {
					$user = $db->field('id,name')->find($lastinsertid);
					$_SESSION = $user; //将查出来的用户的信息都放到session中

					//设置一个前台user登录的暗号token
					$_SESSION['user_token'] = md5($user['id'].$_SERVER['HTTP_HOST']);

				
					$this->success("注册成功！", 1, $_GET['fromto']);
				}else{
					$this->error("注册失败...");
				}

			}


			$this->display();
		}

		//订单列表
		function order() {
			$orderdb = D('order');
			
			$where = array('uid'=>$_SESSION['id']);

		

			$page = new Page($orderdb->where($where)->total(), 10);


			$info_list =  $orderdb->where($where)->order('id desc')->limit($page->limit)->select();

			$orderdatadb = D('orderdata');
			foreach ($info_list as $k => $v) {
				$info_list[$k]['product_list'] = $orderdatadb->where(array('oid'=>$v['id']))->select();    
			}

			
			$this->assign("paywayarr", array(1=>"支付宝付款", 2=>"转账付款", 3=>"货到付款"));
			$this->assign("info_list", $info_list);
			$this->assign('fpage', $page->fpage(0,4,6,8));
			$this->assign("title", '我的订单');
			$this->display();		
		}

		//订单详情
		function orderview() {
			$order_id = $_GET['id'];
			
			
			$orderdb = D('order');
			//订单
			$info = $orderdb->where(array('uid'=>$_SESSION['id'], 'id'=>$order_id))->find();
			
			$orderdatadb = D("orderdata");
			//订单商品
			$product_list = $orderdatadb ->where(array('oid'=>$order_id))-> select();


			$this->assign($info);

			$this->assign("product_list", $product_list);

			$this->assign("title", '订单详情');	
			$this->assign("paywayarr", array(1=>"支付宝付款", 2=>"转账付款", 3=>"货到付款"));
			$this->display();		
		}

		//订单删除
		function orderdel() {
			$order_id = $_GET['id'];
			
			
			$orderdb = D('order');
			//订单, 按state = 1 查没有付款的订单
			$info = $orderdb->where(array('uid'=>$_SESSION['id'], 'id'=>$order_id, 'state'=>1))->find();
			//如果查到就是没有付款的订单
			if ($info) {
				//删除指定的订单
				if($orderdb->delete($info['id'])) {
		
				
					//将产品的库存加回去
					$orderdatadb = D('orderdata');
					$info_list = $orderdatadb->where(array('ord'=>$info['id']))->field('id,pid,pnum')-> select();
					$productdb = D('product');
					foreach ($info_list as $v) {
						//将每个产品数量对应加回去
						$productdb ->where(array('id'=>$v['pid']))-> update("num = num+{$v['pnum']} ");
					}

					//删除定单明细
					$orderdatadb ->where(array('oid'=>$info['id']))->delete();


					$this->success('订单删除成功！');
				}
				else {
					$this->error('订单删除失败...');
				}
			}else {
				$this->error('抱歉，已付款订单不能删除...');
			}
		}

		//收藏列表
		function collect() {

			$collectdb = D();

		
			//关联ask表和product两个表
			$sql = "select a.id, a.atime,b.name,b.money, b.logo, b.id pid from `".TABPREFIX."collect` a, `".TABPREFIX."product` b where a.`pid` = b.`id` and a.uid='{$_SESSION['id']}' order by a.`id` desc";


			$page = new Page($collectdb->query($sql, 'total'), 10);

		
	
			$sql .="  LIMIT {$page->limit}";

			$data = $collectdb->query($sql, 'select');


		
			$this->assign('data', $data);
			$this->assign('fpage', $page->fpage(0,4,6,8));



			$this->assign("title", "我的收藏");
			$this->display();		
		}

		//收藏删除
		function collectdel() {

			$collectdb = D('collect');

			if($collectdb ->where(array('pid'=>$_GET['pid'], 'id'=>$_GET['id']))-> delete()) {
				$productdb = D('product');
				$productdb -> where($_GET['pid']) -> update('collectnum=collectnum-1');
				$this->success("商品收藏删除成功！");	
			}else {
				$this->error('商品收藏删除失败...');
			}	

		}

		//咨询列表
		function ask() {
			$askdb = D();

		
			//关联ask表和product两个表
			$sql = "select a.id aid, a.state, b.name, a.atime,a.asktext,a.uip, a.uname, b.logo, b.id pid, a.replytext, a.replytime from `".TABPREFIX."ask` a, `".TABPREFIX."product` b where a.`pid` = b.`id` and a.uid='{$_SESSION['id']}' order by a.`id` desc";


			$page = new Page($askdb->query($sql, 'total'), 10);

		
	
			$sql .="  LIMIT {$page->limit}";

			$data = $askdb->query($sql, 'select');


		
			$this->assign('data', $data);
			$this->assign('fpage', $page->fpage(0,4,6,8));

			$this->assign("title", "我的咨询");
			$this->display();
		}

		//评价列表
		function comment() {
			$db = D();
			//关联comment表和product两个表
			$sql = "select a.id aid, b.name, a.content, a.atime,a.uname, b.logo, b.id pid from `".TABPREFIX."comment` a, `".TABPREFIX."product` b where a.`pid` = b.`id` and a.uid='{$_SESSION['id']}' order by a.`id` desc";


			$page = new Page($db->query($sql, 'total'), 10);

		
	
			$sql .="  LIMIT {$page->limit}";

			$data = $db->query($sql, 'select');


		
			$this->assign('data', $data);
			$this->assign('fpage', $page->fpage(0,4,6,8));

			$this->assign("title", "我的评价");
			$this->display();		
		}

		//基本信息
		function base() {
			$userdb = D('user');

			if(isset($_POST['do_submit'])) {
	
			

				if($userdb->where(array('id'=>$_SESSION['id']))->update()) {
					$this->success("基本信息设置成功!");
				}else{
					$this->error("基本信息设置失败...");
				}
			}
			

			$this->assign($userdb->field('id, name, address, phone,tname,email,atime')->find($_SESSION['id']));
			$this->assign("title", "基本资料");
			$this->display();		
		}

		//密码修改
		function pw() {
			if(isset($_POST['do_submit'])) {
				if($_POST['pw']!=$_POST['pw1']) {
					$this->error('两次密码输入必须一致...');
				}

				$userdb = D('user');

				if($userdb->update(array('id'=>$_SESSION['id'], 'pw'=>md5(md5('bro_'.$_POST['pw']))))) {
					$this->success("新密码设置成功!");
				}else{
					$this->error("密码设置失败...");
				}
			}

			$this->assign("title", "修改密码");
			$this->display();
		}
	}
