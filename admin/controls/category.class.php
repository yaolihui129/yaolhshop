<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	/**
	 * 商品分类管理
	 *
	 */
	class Category  {
		//分类列表
		function index() {
			$db = D('category');
			//从分类名中获取所有分类
			$cats = $db->select();
		
		
			//将分类给模板
			$this->assign("cats", CatTree::getList($cats));
			$this->assign("menumark", "category");
			$this->assign("title", "商品分类");
			$this->display("list");
		}

		//增加分类
		function add() {
			//创建表category操作对象
			$db = D('category');
			//如果是在添加页面用户点击添加按钮，就有提交变量
			if(isset($_POST['do_submit'])) {
			
				//调用db中的insert()方法，将数据加入数据库
				if($db->insert()) {
					$cache = new FileCache();
					$cache->delete("menu");
					$cache->delete("category");
					//添加成功转向链接列表页面
					$this->success("商品分类增加成功!",1, "index");
				}else{
					$this->error("商吕分类增加失败...");
				}
			}

			
			
			$this -> assign("select", $db->formselect());
			$this->assign("title", "增加分类");
			$this->display();		
		}

		//修改分类
		function mod() {
			//创建表category操作对象
			$db = D('category');
			//获取父级pid
			$pid = !empty($_GET['pid']) ? $_GET['pid'] : 0;
		
			//如果是在添加页面用户点击添加按钮，就有提交变量
			if(isset($_POST['do_submit'])) {
				
				$_POST['childs'] .=",".$_POST['id'];
				if(in_array($_POST['pid'], explode(",",$_POST['childs']))) {
					$this->error("不能将分类修改到自己或自己的子类中...");
				}

				//调用db中的update()方法，将数据修改到数据库
				if($db->update()) {
					$cache = new FileCache();
					$cache->delete("menu");
					$cache->delete("category");
					//添加成功转向分类列表页面
					$this->success("分类修改成功!", 1, "index");
				}else{
					$this->error("分类修改失败...");
				} 
			}
		
			$this -> assign("select", $db->formselect("pid", $pid));


			//设置标题
			$this->assign("title","修改分类");
			//将所有的子类的ID都给修改页面
			$this->assign("childs", $_GET['childs']);
			$this->assign($db->find($_GET['id']));
			//加载修改页面
			$this->display("add");
		}

		//删除分类
	 	function del() {
			//获取category表操作对象
			$db = D('category');
			if($_GET['childs']!="" ) {
				$this->error("只能删除空分类，有子分类不能删除...");
			}

			$db2 = D("product");

			if($db2->where(array("cid"=>$_GET['id']))->total() > 0) {
				$this->error("不能删除，分类下存有商品...");
			}

			//删除数据库记录
			if($db->delete($_GET['id'])) {
				$cache = new FileCache();
				$cache->delete("menu");
				$cache->delete("category");
				//添加成功转向列表页面
				$this->success("分类删除成功!");
			}else{
				$this->error("分类删除失败...");
			}		
		}

		//分类排序
		function order() {
			//获取category表操作对象
			$db = D('category');
			$num = 0;
			foreach($_POST['ord'] as $id => $ord) {
				$num += $db->update(array("id"=>$id, "ord"=>$ord));
			}

			if($num) {
				$cache = new FileCache();
				$cache->delete("menu");
				$cache->delete("category");
				$this->success("重新排序成功!");
			}else{
				$this->error("重新排序失败...");
			}
		}	

	}
