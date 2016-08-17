<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	class Index  {
		function index() {

			
					
			/*  特价商品
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
			 *  smoney: 商品原价
			 */
			$productdb = D('product');

			$tjs = 	$productdb -> where(array("istj"=>1, 'state'=>1))->order('id desc')->limit(3)->select();

		

			//特价商品分配到前台
			$this -> assign("tjs", $tjs);







			/*  商城公告
			 *  
			 *  变量名：$notices;
			 *  二维数组： 第一维索引， 第二维关联；
			 *  第一维数组数量最大值： 8个
			 *  第二维数组元素下标如下所示：
			 *
			 *  id :  公告ID
			 *  name: 公告标题
			 */
	

			$articledb = D('article');

			$notices = $articledb->field('id, name')->where(array("cid"=>1))->order('id desc')->limit(8)->select();

			//数据分配到前台
			$this -> assign("notices", $notices);

			/*  推荐商品
			 *  
			 *  变量名：$tuijians;
			 *  二维数组： 第一维索引， 第二维关联；
			 *  第一维数组数量最大值： 5个
			 *  第二维数组元素下标如下所示：
			 *
			 *  id :  商品ID
			 *  name: 商品名称
			 *  logo: 商品logo
			 *  money: 商品销售价格
			 *  smoney: 商品原价
			 */
		
			$tuijians = $productdb ->field('id, name, logo, money, smoney')-> where(array("istuijian"=>1, 'state'=>1))->order('id desc')->limit(5)->select();

			//推荐商品分配到前台
			$this -> assign("tuijians", $tuijians);



			/*  分类展示商品
			 *  
			 *  变量名：$allcats;
			 *  二维数组： 第一维索引， 第二维关联； 二维数中还包括一层二维数组，是商品的信息
			 *  第一维数组数量最大值： 5个
			 *  第二维数组元素下标如下所示：
			 *
			 *  id :  商品ID
			 *  name: 商品名称
			 *  logo: 商品logo
			 *  money: 商品销售价格
			 *  smoney: 商品原价
			 */

			//获取商品分类对象
			$catdb = D('category');

			$allcats = $catdb -> field('id, catname')->order('ord asc')->where(array('pid'=>0))
					  -> r_select(
						  	array('product', 'id, name, logo, money, smoney', 'cid', array('newlists', 'id desc', '8', "state='1'")),
							array('product', 'id, name, logo, money', 'cid',array('selllists', 'sellnum desc', '5', "state='1'"))

						  );


			//推荐商品分配到前台
			$this -> assign("allcats", $allcats);

		
			$this->display();
		}
	}
