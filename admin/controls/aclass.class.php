<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	/**
	 *文章分类管理
	 */
	class Aclass {
		//分类列表
		function index() {
			//创建数据库操作对象
			$db = D("class");
			//获取全部分类，并能ord正序获取
			$data = $db->order("ord asc,id asc")->select();
			//将全部数据库二维数组分到前台
			$this->assign("data", $data);
			//标题
			$this->assign("title", "分类管理");	
			//菜单标记
			$this->assign("menumark", "aclass");
			//显示模板
			$this->display();
		}

		//添加分类
		function add() {
			//如果是在添加页面用户点击添加按钮，就有提交变量
			if(isset($_POST['do_submit'])) {
				//获取class表操作对象
				$db = D('class');
			
				//调用db中的insert()方法，将数据加入数据库
				if($db->insert()) {
				
					$cache = new FileCache();
					$cache->delete("class");
					//添加成功转向分类列表页面
					$this->success("分类增加成功!", 1,  "index");
				}else{
					$this->error("分类增加失败...");
				}
			}

			//设置标题
			$this->assign("title","添加分类");

			//加载添加分类页面
			$this->display();
		}

		//修改分类
		function mod() {
			//获取class表操作对象
			$db = D('class');
			//如果是在添加页面用户点击添加按钮，就有提交变量
			if(isset($_POST['do_submit'])) {
			
				//调用db中的insert()方法，将数据加入数据库
				if($db->update()) {
					$cache = new FileCache();
					$cache->delete("class");
					//添加成功转向分类列表页面
					$this->success("分类修改成功!", 1, "index");
				}else{
					$this->error("分类修改失败...");
				}
			}

			//设置标题
			$this->assign("title","修改分类");

			$this->assign($db->find($_GET['id']));
			//加载添加分类页面
			$this->display("add");
		}

		//删除分类
		function del() {
			//获取class表操作对象
			$db = D('class');
			//删除数据库记录
			if($db->delete($_GET['id'])) {
				$cache = new FileCache();
				$cache->delete("class");
				//添加成功转向分类列表页面
				$this->success("分类删除成功!");
			}else{
				$this->error("分类删除失败...");
			}		
		}

		//分类排序
		function order() {
		
			//获取class表操作对象
			$db = D('class');
			$num = 0;
			foreach($_POST['ord'] as $id => $ord) {
				$num += $db->update(array("id"=>$id, "ord"=>$ord));
			}

			if($num) {
				$cache = new FileCache();
				$cache->delete("class");
				$this->success("重新排序成功!");
			}else{
				$this->error("重新排序失败...");
			}

		}
	}

