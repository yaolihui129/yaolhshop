<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	//所有前台控制器的父类， 可以写一个公用的操作
	class Common extends Action {
		protected $category;
		function init() {
			//如果用户不登录，就不能访问个人中心
			if(!bro_login("user") && $_GET['m']=='user' && !in_array($_GET['a'], array('login', 'register'))) {
				$this->error('你还没有登录， 请先登录...', 3, 'Index/index');	
			}


			//创建一个缓存对象 
			$cache = new FileCache();

			//从缓存中获取网站的设置信息
			$setting = $cache -> get('setting');

			if(!$setting) {
				//从数据库获取信息
				$db = D("setting");
				$allsetting = $db->select();
				
				//转换数组
				$setting = array();
				foreach($allsetting as $v) {
					$setting[$v['skey']] = $v['svalue'];
				}	
				//写入缓存
				$cache -> set("setting", $setting);
			}

			$this -> assign("setting", $setting);


			/**
			 *
			 * 系统菜单
			 *  变量名：$cats;
			 *  二维数组： 第一维索引， 第二维关联；
			 *  第一维数组数量最大值： 8个
			 *  第二维数组元素下标如下所示：
			 *
			 *  id :  商品分类ID
			 *  catname: 商品分类名称
			 *
			 */



			$cats = $cache -> get("menu");

			if(!$cats) {
				$catdb = D("category");
				
				$cats = $catdb -> field("id,catname") ->where(array('pid'=>0))->order('ord asc, id asc')->limit(10)-> select();

				$cache -> set("menu", $cats);
			}

			$this -> assign("cats", $cats);

	

			

			//获取所有系统分类, 并缓存起来
			
			$this->category = $cache->get('category');

			if(!$this->category) {
				$catdb = D("category");
			
				$cattree = new CatTree();

				$this->category = $cattree->getList($catdb->select());

				$cache -> set("category", $this->category);
			}

		
	
		

			/**
			 *
			 * 单页
			 *  变量名：$page;
			 *  二维数组： 第一维索引， 第二维关联；
			 *  第一维数组数量最大值： 12个
			 *  第二维数组元素下标如下所示：
			 *
			 *  id :  单页ID
			 *  name: 单页名称
			 *
			 */

			$page = $cache -> get("page");

			if(!$page) {
				$pagedb = D("page");
				
				$page = $pagedb -> field("id,name") -> select();

				$cache -> set("page", $page);
			}

			$this -> assign("page", $page);

			/**
			 *
			 * 友情链接
			 *  变量名：$links;
			 *  二维数组： 第一维索引， 第二维关联；
			 *  第一维数组数量最大值： 10个
			 *  第二维数组元素下标如下所示：
			 *
			 *  id :  友情链接ID
			 *  url:  友情链接的网址
			 *  name: 网站名称
			 *
			 */


		/*	$links = array(
					array("id"=>1, "url"=>"http://www.lampbrother.net", "name"=>"兄弟连"),
					array("id"=>2, "url"=>"http://yun.itxdl.cn", "name"=>"云课堂")
				); */
			//先从缓存中获取数据
			$links = $cache->get("link");
			//如果没有再从数据库获取
			if(!$links) {
				$linkdb = D("link");
				$links = $linkdb -> order('ord asc,id asc')->limit(10)->select();
				//加到缓存中
				$cache -> set("link", $links);

			
			}
			$this -> assign("links", $links);


		
				/**
			 *
			 * 广告
			 *  变量名：$ads;
			 *  二维数组： 第一维索引， 第二维关联；
			 *  第一维数组数量最大值： 10个
			 *  第二维数组元素下标如下所示：
			 *
			 *  id :  友情链接ID
			 *  url:  友情链接的网址
			 *  name: 网站名称
			 *
			 */

			$ads = $cache -> get('ad');

			if(!$ads) {
				$db = D('ad');

				$ads = $db->order("ord asc,id asc")->select();

				$cache -> set("ad", $ads);

			}

			$this->assign("ads", $ads);
			//获取购物车中的商品数量分到前台
			$cartdb = D('cart');
			$cart_num = bro_login('user') ? $cartdb->total(array('uid'=>$_SESSION['id'])) : (unserialize(stripslashes($_COOKIE['cart_list'])) ? count(unserialize(stripslashes($_COOKIE['cart_list']))) : 0);

		
			$this->assign('cart_num', $cart_num);

			//记录访客一次访问
			
			D('iplog')->insert(array('ip'=>bro_ip(), 'atime'=>time()));
			
			


		}
	}
