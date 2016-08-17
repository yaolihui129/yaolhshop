<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	/**
	 * 商品管理
	 *
	 */
	class Product {
		//商品列表
		function index() {
			//创建数据库操作对象
			$db = D('product');

			//获取分类的数据库操作对象，用于获取分类列表
			$cat = D('category');

			$where = array();
			
			//设置where条件
			//查询上架或下架的商品
			$where['state'] = isset($_GET['state']) ? $_GET['state'] : 1;

		
			//判断用户是否按名称搜索
			if(!empty($_GET['name']) || $_GET['name'] != '') {
				$where['name'] = '%'.$_GET['name'].'%';
			
			}

			//判断用户是否按分类查找
			if(!empty($_GET['cid']) || $_GET['cid'] != 0) {
				$where['cid'] = $_GET['cid'];
			}

			//判断用户是否按商品过滤
			if(!empty($_GET['fileter']) || $_GET['filter'] != '') {
				list($filed, $value) = explode("|", $_GET['filter']);
				$where[$filed] = $value;
			}

			//指定用户排序
			if(!empty($_GET['orderby']) || $_GET['orderby'] != '') {
				$order = str_replace('|', ' ', $_GET['orderby']);
			}else{
				$order = "id desc";
			}

			
			$total = $db->where($where)->total();

			//设置分页
			$page = new page($total, 15);
		
			
			//获取数据
			$data = $db ->field('id,name,logo,cid,money,sellnum,num,clicknum,istuijian,istj')->where($where)->order($order)->limit($page->limit)->select();

		
		//	$db->getSQL();


			$filter_arr = array('istuijian|1'=>'推荐商品', 'istj|1'=>'特价商品', 'wlmoney|0'=>'包邮商品', 'num|0'=>'售空商品');

			$orderby_arr['clicknum|desc'] = '浏览量(多到少)';
			$orderby_arr['clicknum|asc'] = '浏览量(少到多)';
			$orderby_arr['sellnum|desc'] = '销售量(多到少)';
			$orderby_arr['sellnum|asc'] = '销售量(少到多)';
			$orderby_arr['num|desc'] = '库存量(多到少)';
			$orderby_arr['num|asc'] = '库存量(少到多)';
			$orderby_arr['collectnum|desc'] = '收藏数(多到少)';
			$orderby_arr['collectnum|asc'] = '收藏数(少到多)';
			$orderby_arr['asknum|desc'] = '咨询数(多到少)';
			$orderby_arr['asknum|asc'] = '咨询数(少到多)';
			$orderby_arr['commentnum|desc'] = '评价数(多到少)';
			$orderby_arr['commentnum|asc'] = '评价数(少到多)';
			
			



			//将设置的查询条件发到模板
			$this->assign("where", $_GET);
			$args = $_GET;
			unset($args['m']);
			unset($args['a']);
			unset($args['page']);
			$this->assign("args", http_build_query($args));

			$this->assign("filter_arr", $filter_arr);
			$this->assign("orderby_arr", $orderby_arr);

			//将获取的商品信息分到前台显示
			$this -> assign("data", $data);
			//将分页给前台
			$this -> assign("fpage", $page->fpage(0,4,6,8));
			//将分类名数组分到前台
			$this -> assign("cats", $cat->byidname());
			$this -> assign("select", $cat->formselect('cid', $_GET['cid']));
			$this->assign("menumark", "product");
			$this->assign("title", "商品列表");
			$this->display();
		}

		//商品添加
		function add() {
			//获取分类的数据库操作对象，用于获取分类列表
			$cat = D('category');
			

			//如果用户提交则处理
			if(isset($_POST['do_submit'])) {
				
				$up = bro_upload('logo');

				if($up[0]) {
					$_POST['logo'] = $up[1];
				}else {
					$this->error($up[1]);
				}

				//将时间格式转换
				$_POST['atime'] = strtotime($_POST['atime']);
				//格式转换
				$_POST['content'] = str_replace(array("\"", "'"), array("&quot;", "&#039;"), stripslashes($_POST['content']));
				//创建商品数据库操作对象
				$db = D('product');

				//如果不选择物价商品就将istj设置为0
				$_POST['istj'] = isset($_POST['istj']) ? 1 : 0;

				//调用db中的insert()方法，将数据加入数据库
				if($db->insert($_POST, array('content'), 1)) {
				
					//添加成功转向链接列表页面
					$this->success("商品增加成功!", 1, "index");
				}else{
					$this->error($db->getMsg());
				}
			
			}
			//如果是获取修改页面需要创建一个数据对象获取数据

			//给前台一个日历
			$this->assign("date", Form::date('atime', date('Y-m-d H:i:s'), 1));
			//给前台一个文本编辑器
			$this->assign("textarea", Form::editor('content', '', 'full', 300));
			//将分类列表分给前台
			$this->assign("select", $cat->formselect('cid'));
			$this->assign("title", "发布商品");
			$this->display();
		}
	



		//商品修改
		function mod() {
			//获取分类的数据库操作对象，用于获取分类列表
			$cat = D('category');

			//创建商品数据库操作对象
			$db = D('product');

			//如果是获取修改页面需要创建一个数据对象获取数据
			$srcdata = $db->find($_GET['id']);
			//格式转回来
			$srcdata['content'] = str_replace( array("&quot;", "&#039;"), array("\"", "'"), $srcdata['content']);

			//如果用户提交则处理
			if(isset($_POST['do_submit'])) {
				//如果用户重新选择了图片才去上传
				if($_FILES['logo']['error']== 0) {
					//调用自定义函数
					$up = bro_upload('logo');
					//如果上传成功，则传给$_POST
					if($up[0]) {
						$_POST['logo'] = $up[1];
					}else {
						$this->error($up[1]);
					}
					
				}

			
				//将时间格式转换
				$_POST['atime'] = strtotime($_POST['atime']);

				//格式转换
				$_POST['content'] = str_replace(array("\"", "'"), array("&quot;", "&#039;"), stripslashes($_POST['content']));

				//如果不选择物价商品就将istj设置为0
				$_POST['istj'] = isset($_POST['istj']) ? 1 : 0;
				
				//调用db中的insert()方法，将数据加入数据库
				if($db->update($_POST, array('content'), 1)) {
					//删除原来的图片
					if(isset($_POST['logo'])) {
						@unlink(FILEUPPATH.'/'.$srcdata['logo']);	
					}
					//添加成功转向列表页面
					$this->success("商品修改成功!", 1, "index");
				}else{
					$this->error($db->getMsg());
				}
			
			}


			//给前台一个日历
			$this->assign("date", Form::date('atime', date('Y-m-d H:i:s', $srcdata['atime']), 1));
			//给前台一个文本编辑器
			$this->assign("textarea", Form::editor('content', $srcdata['content'], 'full', 300));
			//将分类列表分给前台
			$this->assign("select", $cat->formselect('cid', $srcdata['cid']));
			$this->assign($srcdata);
			$this->assign("title", "修改商品");
			$this->display("add");			
		}

		//商品删除
		function del() {
			//如果是批量删除则$_POST['id']这个数组会存在， 如果是删除单个$_GET['id']一个值会存在
			$id = !empty($_POST['id']) ? $_POST['id'] : $_GET['id'];

			$db = D('product');

			if($db->delete($id)) {
				$this->success('删除成功!');
			}else{
				$this->error('删除失败...');
			}
		}

		//商品上下架
		function state() {
			$db = D('product');
			$result = 0;
			//通过遍历选中的ID，来循环修改上下架state字段
			foreach ($_POST['id'] as $v) {
				$result += $db->update(array('id'=>$v, 'state'=>$_GET['state']));
			}

			if($result) {
				$this->success("操作成功!");
			}else {
				$this->error("操作失败...");
			}
		}

		//商品推荐操作（上下）
		function tuijian() {
			$db = D('product');
			$result = 0;
			//通过遍历选中的ID，来循环修改推荐istuijian字段
			foreach ($_POST['id'] as $v) {
				$result += $db->update(array('id'=>$v, 'istuijian'=>$_GET['tuijian']));
			}

			if ($result) {
				$this->success("推荐操作成功!");
			}else {
				$this->error("推荐操作失败...");
			}
		}


	}
