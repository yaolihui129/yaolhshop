<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	class Product {
		
		//商品内容页面
		function index() {
			$productdb = D('product');

		

			$product = $productdb->find($_GET['id']);
			
			

			//如果没有设置分类id页从根分类获取
			$pid = $product['cid'];
			//获取当前ID对应的分类的所有信息
			$callid = $this->category[$pid];

			//是所有子类id, 包括自己
			$childs = trim($callid['childs'])=="" ? $pid : $callid['childs'].",".$pid;
			//所有父级路径
			$path = ltrim($callid['path'].",".$pid, "0,");
	
			//现在的路径
			$nowpath = "您现在的位置：<a href='index.php'>首页</a>";

			foreach(explode(",", $path) as $v) {
				$cattmp = $this->category[$v];
				$nowpath .= " &gt; <a href='index.php?m=product&a=plist&cid={$v}' title='{$cattmp['catname']}'>{$cattmp['catname']}</a>";
			}

			$nowpath .= " &gt; {$product['name']}";

			$this -> assign("nowpath", $nowpath);

			
			/*  商品分类, 通过父级ID获取子分类
			 *  
			 *  变量名：$cats;
			 *  四维数组： 第一维索引， 第二维关联；

			 */
		

			$catdb = D('category');

			$catlist = $catdb -> field('id, catname')->order('ord asc,id asc')->where(array('pid'=>$pid))
					  -> r_select(
						  	array('category', 'id, catname', 'pid', array('subcats', 'ord asc, id asc'))
						
						  );
			$this -> assign("catlist", $catlist);

			/*  本类商品的热销排行
			 *  
			 *  变量名：$tjs;
			 *  二维数组： 第一维索引， 第二维关联；
			 *  第一维数组数量最大值： 3个
			 *  第二维数组元素下标如下所示：
			 *
			 *  id :  商品ID
			 *  name: 商品名称
			 *  logo: 商品logo
			 *  money: 商品销售价格
			 */
			
			//获取当前分类和当前所有子分类中的热销排行
			$selllists = $productdb ->field('id, name, logo, money') -> where(array('cid'=>explode(',', $childs), 'state'=>1)) -> order('sellnum desc')  -> limit(6) -> select();

	
			/* $selllists =  array(
				0 => array("id"=>1, "name"=>"2014新款夏季女凉鞋", "logo"=>"thumb_60x60_20140720203302u.jpg", "money"=>400.0 ), 
				1 => array("id"=>2, "name"=>"2014新款夏季女凉鞋", "logo"=>"thumb_60x60_20140720203302u.jpg", "money"=>400.0 ), 
				2 => array("id"=>3, "name"=>"2014新款夏季女凉鞋", "logo"=>"thumb_60x60_20140720203302u.jpg", "money"=>400.0 ), 
				3 => array("id"=>4, "name"=>"2014新款夏季女凉鞋", "logo"=>"thumb_60x60_20140720203302u.jpg", "money"=>400.0 ), 
				4 => array("id"=>5, "name"=>"2014新款夏季女凉鞋", "logo"=>"thumb_60x60_20140720203302u.jpg", "money"=>400.0 ), 
				5 => array("id"=>6, "name"=>"2014新款夏季女凉鞋", "logo"=>"thumb_60x60_20140720203302u.jpg", "money"=>400.0 )
			); */

			$this -> assign("selllists", $selllists);



			//商品分配到前台, 直接分配， 数组元素变变量
			$product['content'] = str_replace( array("&quot;", "&#039;"), array("\"", "'"), $product['content']);
			$this -> assign($product);

			//咨询
			$askdb = D('ask');

			$ask_list = $askdb -> field('id, uname, atime, asktext, replytext,replytime')->order('atime desc')->select();

			$this -> assign("ask_list", $ask_list);

			//评论
			$commentdb = D('comment');

			$comment_list = $commentdb -> field('id, uname, atime, content')->order('atime desc')->select();


			$this -> assign("comment_list", $comment_list);

			$pagedb = D("page");

			//售后服务
			$pagetext = $pagedb->field('content') ->find(13); 

			$pagetext = str_replace( array("&quot;", "&#039;"), array("\"", "'"), $pagetext['content']);

			$this -> assign("pagetext", $pagetext);

			$this->assign('title', $product['name']);

			$this->display();
			//在最后更新点击数
			$productdb->where($_GET['id'])->update('clicknum = clicknum+1');
		}

		

		//商品列表页面
		function plist() {
			$productdb = D('product');
			//如果没有设置分类id页从根分类获取
			$pid = !empty($_GET['cid']) ? $_GET['cid'] : 0;
			//获取当前ID对应的分类的所有信息
			$callid = $this->category[$pid];

			//是所有子类id, 包括自己
			$childs = trim($callid['childs'])=="" ? $pid : $callid['childs'].",".$pid;
			//所有父级路径
			$path = ltrim($callid['path'].",".$pid, "0,");
	
			//现在的路径
			$nowpath = "您现在的位置：<a href='index.php'>首页</a>";

			foreach(explode(",", $path) as $v) {
				$cattmp = $this->category[$v];
				$nowpath .= " &gt; <a href='index.php?m=product&a=plist&cid={$v}' title='{$cattmp['catname']}'>{$cattmp['catname']}</a>";
			}

			
			$this -> assign("nowpath", $nowpath);
			$this -> assign("title", $this->category[$pid]['catname']);
			
			/*  商品分类, 通过父级ID获取子分类
			 *  
			 *  变量名：$cats;
			 *  四维数组： 第一维索引， 第二维关联；

			 */
		

			$catdb = D('category');

			$catlist = $catdb -> field('id, catname')->order('ord asc,id asc')->where(array('pid'=>$pid))
					  -> r_select(
						  	array('category', 'id, catname', 'pid', array('subcats', 'ord asc, id asc'))
						
						  );
			$this -> assign("catlist", $catlist);

			/*  本类商品的热销排行
			 *  
			 *  变量名：$tjs;
			 *  二维数组： 第一维索引， 第二维关联；
			 *  第一维数组数量最大值： 3个
			 *  第二维数组元素下标如下所示：
			 *
			 *  id :  商品ID
			 *  name: 商品名称
			 *  logo: 商品logo
			 *  money: 商品销售价格
			 */
			
			//获取当前分类和当前所有子分类中的热销排行
			$selllists = $productdb ->field('id, name, logo, money') -> where(array('cid'=>explode(',', $childs), 'state'=>1)) -> order('sellnum desc')  -> limit(6) -> select();


			/* $selllists =  array(
				0 => array("id"=>1, "name"=>"2014新款夏季女凉鞋", "logo"=>"thumb_60x60_20140720203302u.jpg", "money"=>400.0 ), 
				1 => array("id"=>2, "name"=>"2014新款夏季女凉鞋", "logo"=>"thumb_60x60_20140720203302u.jpg", "money"=>400.0 ), 
				2 => array("id"=>3, "name"=>"2014新款夏季女凉鞋", "logo"=>"thumb_60x60_20140720203302u.jpg", "money"=>400.0 ), 
				3 => array("id"=>4, "name"=>"2014新款夏季女凉鞋", "logo"=>"thumb_60x60_20140720203302u.jpg", "money"=>400.0 ), 
				4 => array("id"=>5, "name"=>"2014新款夏季女凉鞋", "logo"=>"thumb_60x60_20140720203302u.jpg", "money"=>400.0 ), 
				5 => array("id"=>6, "name"=>"2014新款夏季女凉鞋", "logo"=>"thumb_60x60_20140720203302u.jpg", "money"=>400.0 )
			); */

			$this -> assign("selllists", $selllists);

			/*  本类及子类的商品, 当前分类的在最前后， 子分类的在后面, 用分页展示
			 *  
			 *  变量名：$info_list;
			 *  二维数组： 第一维索引， 第二维关联；
			 *  第一维数组数量最大值： 3个
			 *  第二维数组元素下标如下所示：
			 *
			 *  id :  商品ID
			 *  name: 商品名称
			 *  logo: 商品logo
			 *  money: 商品销售价格
			 *  smoney: 商品原价
			 */
			if(!empty($_GET['keyword'])) {

				$where = array('name'=>"%{$_GET['keyword']}%", 'state'=>1); 
				$this->assign('args', "keyword={$_GET['keyword']}");
			}else {
				$where = array('cid'=>explode(',', $childs), 'state'=>1); 
				$this->assign('args', "cid={$_GET['cid']}");
			}

			$page = new Page($productdb->where($where)->total(), 16);
			$page -> set('head', "个商品");
			//设置排序
			$order = isset($_GET['orderby']) ? str_replace("_", " ", $_GET['orderby']) : 'cid asc, id desc';

			$info_list =  $productdb ->field('id, name, logo, money, smoney') -> where($where) -> order($order)  -> limit($page->limit) -> select();

		

			/* $info_list = array(
					0 => array("id"=>1, "name"=>"IT峰播 商城模板测试商品", "logo"=>"thumb_150x150_20140720210131u.jpg", "money"=>450.0, "smoney"=>999.0 ), 
					1 => array("id"=>2, "name"=>"IT峰播 商城模板测试商品", "logo"=>"thumb_150x150_20140720210131u.jpg", "money"=>450.0, "smoney"=>999.0 ), 
					2 => array("id"=>3, "name"=>"IT峰播 商城模板测试商品", "logo"=>"thumb_150x150_20140720210131u.jpg", "money"=>450.0, "smoney"=>999.0 ), 
					3 => array("id"=>4, "name"=>"IT峰播 商城模板测试商品", "logo"=>"thumb_150x150_20140720210131u.jpg", "money"=>450.0, "smoney"=>999.0 ), 
					4 => array("id"=>5, "name"=>"IT峰播 商城模板测试商品", "logo"=>"thumb_150x150_20140720210131u.jpg", "money"=>450.0, "smoney"=>999.0 ), 
					5 => array("id"=>6, "name"=>"IT峰播 商城模板测试商品", "logo"=>"thumb_150x150_20140720210131u.jpg", "money"=>450.0, "smoney"=>999.0 ), 
					6 => array("id"=>7, "name"=>"IT峰播 商城模板测试商品", "logo"=>"thumb_150x150_20140720210131u.jpg", "money"=>450.0, "smoney"=>999.0 ), 
					7 => array("id"=>8, "name"=>"IT峰播 商城模板测试商品", "logo"=>"thumb_150x150_20140720210131u.jpg", "money"=>450.0, "smoney"=>999.0 ), 
					8 => array("id"=>9, "name"=>"IT峰播 商城模板测试商品", "logo"=>"thumb_150x150_20140720210131u.jpg", "money"=>450.0, "smoney"=>999.0 ), 
					9 => array("id"=>10, "name"=>"IT峰播 商城模板测试商品", "logo"=>"thumb_150x150_20140720210131u.jpg", "money"=>450.0, "smoney"=>999.0 ), 
					10 => array("id"=>11, "name"=>"IT峰播 商城模板测试商品", "logo"=>"thumb_150x150_20140720210131u.jpg", "money"=>450.0, "smoney"=>999.0 ), 
					11 => array("id"=>12, "name"=>"IT峰播 商城模板测试商品", "logo"=>"thumb_150x150_20140720210131u.jpg", "money"=>450.0, "smoney"=>999.0 )
				); */

			//特价商品分配到前台
			$this -> assign("info_list", $info_list);
			$this -> assign("fpage", $page->fpage(4,5,6,7,8, 0));

			$this->display();
		}
	

		//添加商品收藏
		function collectadd() {
			debug(0);
			$collectdb = D('collect');

			$_GET['uid'] = $_SESSION['id'];     //当前的用户id
		
			if ($collectdb->where(array("uid"=>$_GET['uid'], "pid"=>$_GET['pid']))->total() > 0) {
				$show = '您已经收藏过该商品了，请不要重复收藏噢...';
			} else {
				$_GET['atime'] = time();
			
				if ($collectdb -> insert($_GET)) {
				
					$productdb = D('product');

					//将商品中的评价数增加
					$productdb -> where($_GET['pid']) -> update('collectnum=collectnum+1');
				
					$show = '商品收藏成功！';
				}else {
					$show = '商品收藏失败...';
				}
			}
			echo json_encode(array('show'=>$show));		
			exit;
		}

		//添加商品咨询
		function askadd() {
			debug(0);
			if (isset($_POST['do_submit'])) {
				//创建咨询数据库操作对象
				$askdb = D('ask');
				
				$_POST['atime'] = time(); //当前的咨询时间
				$_POST['uid'] = $_SESSION['id'];     //当前的用户id
				$_POST['uname'] = $_SESSION['name']; //咨询者的用户名
				$_POST['uip'] = bro_ip();            //用户的IP
			
				if($askdb -> insert()) {
					$productdb = D('product');

					//将商品中的评价数增加
					$productdb -> where($_POST['pid']) -> update('asknum=asknum+1');
					//告诉客户端结果成功
					$result = true;
					
					$atime = date("Y-m-d H:i:s", $_POST['atime']);
					$asktext = htmlspecialchars($_POST['asktext']);
$html = <<<html
<ul>
	<li class="fl">会员：{$_POST['uname']}</li>
	<li class="fr">咨询日期：{$atime}</li>
</ul>
<div class="padb10 mal10">
	<div class="mat5">{$asktext}</div>
</div>
html;
				
				}else {
					$result = false;
				}
				echo json_encode(array('result'=>$result, 'html'=>$html));

			}	
			exit;	
		}

		//商品评价添加
		function commentadd() {
			debug(0);
			if (isset($_POST['do_submit'])) {
				//创建咨询数据库操作对象
				$commentdb = D('comment');
				
				$_POST['atime'] = time(); //当前的咨询时间
				$_POST['uid'] = $_SESSION['id'];     //当前的用户id
				$_POST['uname'] = $_SESSION['name']; //咨询者的用户名
				$_POST['uip'] = bro_ip();            //用户的IP


			
				if ($commentdb->insert()) {
					$productdb = D('product');

					//将商品中的评价数增加
					$productdb -> where($_POST['pid']) -> update('commentnum=commentnum+1');

					$result = true;

					$atime = date("Y-m-d H:i:s", $_POST['atime']);
					$commenttext = htmlspecialchars($_POST['content']);

$html = <<<html
<ul>
	<li class="fl">会员：{$_POST['uname']}</li>
	<li class="fr">评价日期：{$atime}</li>
</ul>
<div class="pingjia">{$commenttext}</div>
html;
				}else {
					$result = false;
				}
			
				echo json_encode(array('result'=>$result, 'html'=>$html));
			}
			exit;
		}

		
	}
