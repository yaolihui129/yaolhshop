<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	/**
	 * 管理员管理
	 *
	 */
	class Admin {
		//管理员列表
		function index() {
			//创建数据库操作对象
			$db = D("admin");
			//获取全部管理员，并能ord正序获取
			$data = $db->order("id asc")->select();
			//将全部数据库二维数组分到前台
			$this->assign("data", $data);
			//标题
			$this->assign("title", "管理员列表");	
			//菜单标记
			$this->assign("menumark", "admin");
			//显示模板
			$this->display();
		}

		//添加管理员
		function add() {
			//如果是在添加页面用户点击添加按钮，就有提交变量
			if(isset($_POST['do_submit'])) {
				//获取admin表操作对象
				$db = D('admin');

				$_POST['atime'] = $_POST['ltime'] =  time();
				$_POST['pw'] =  md5(md5('bro_'.$_POST['pw']));
				
				//调用db中的insert()方法，将数据加入数据库
				if($db->insert()) {
				
					//添加成功转向管理员列表页面
					$this->success("管理员增加成功!", 1, "index");
				}else{
					$this->error("管理员增加失败...");
				}
			}

			//设置标题
			$this->assign("title","添加管理员");

			//加载添加管理员页面
			$this->display();
		}

		//修改管理员
		function mod() {
			//获取admin表操作对象
			$db = D('admin');
			//如果是在添加页面用户点击添加按钮，就有提交变量
			if(isset($_POST['do_submit'])) {

				if(!empty($_POST['pw'])) {
					$_POST['pw'] =  md5(md5('bro_'.$_POST['pw']));
				}

				//调用db中的insert()方法，将数据加入数据库
				if($db->update()) {
				
					//添加成功转向管理员列表页面
					$this->success("管理员修改成功!", 1, "index");
				}else{
					$this->error("管理员修改失败...");
				}
			}

			//设置标题
			$this->assign("title","修改管理员");

			$this->assign($db->find($_GET['id']));
			//加载添加管理员页面
			$this->display();
		}

		//删除管理员
		function del() {
			//获取admin表操作对象
			$db = D('admin');
			
			if($_GET['id']==1) {
				$this->error("抱歉，默认管理员不可删除...");
			} 

			//删除数据库记录
			if($db->delete($_GET['id'])) {
			
				//添加成功转向管理员列表页面
				$this->success("管理员删除成功!");
			}else{
				$this->error("管理员删除失败...");
			}		
		}

	
	}

