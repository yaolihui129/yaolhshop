<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	/**
	 * 友情链接管理
	 *
	 */
	class Link  {
		//友情链接列表
		function index() {
			//创建数据库操作对象
			$db = D("link");
			//获取全部友情链接，并能ord正序获取
			$data = $db->order("ord asc,id asc")->select();
			//将全部数据库二维数组分到前台
			$this->assign("data", $data);
			//标题
			$this->assign("title", "友情链接");	
			//菜单标记
			$this->assign("menumark", "link");
			//显示模板
			$this->display();
		}

		//添加友情链接
		function add() {
			//如果是在添加页面用户点击添加按钮，就有提交变量
			if(isset($_POST['do_submit'])) {
				//获取link表操作对象
				$db = D('link');
				//如果用户在输入url时， 没有加http://给他加上
				stripos($_POST['url'], 'http://') === false && $_POST['url'] = "http://{$_POST['url']}";
				//调用db中的insert()方法，将数据加入数据库
				if($db->insert()) {
					$cache = new FileCache();
					$cache->delete("link");
					//添加成功转向链接列表页面
					$this->success("链接增加成功!", 1, "index");
				}else{
					$this->error("链接增加失败...");
				}
			}

			//设置标题
			$this->assign("title","添加友情链接");

			//加载添加链接页面
			$this->display();
		}

		//修改友情链接
		function mod() {
			//获取link表操作对象
			$db = D('link');
			//如果是在添加页面用户点击添加按钮，就有提交变量
			if(isset($_POST['do_submit'])) {
				//如果用户在输入url时， 没有加http://给他加上
				stripos($_POST['url'], 'http://') === false && $_POST['url'] = "http://{$_POST['url']}";
				//调用db中的insert()方法，将数据加入数据库
				if($db->update()) {
					$cache = new FileCache();
					$cache->delete("link");
					//添加成功转向链接列表页面
					$this->success("链接修改成功!", 1,  "index");
				}else{
					$this->error("链接修改失败...");
				}
			}

			//设置标题
			$this->assign("title","修改友情链接");

			$link = $db->find($_GET['id']);
			$link['link_url'] = $link['url'];
			$this->assign($link);
			//加载添加链接页面
			$this->display("add");
		}

		//删除友情链接
		function del() {
			//获取link表操作对象
			$db = D('link');
			//删除数据库记录
			if($db->delete($_GET['id'])) {
				$cache = new FileCache();
				$cache->delete("link");
				//添加成功转向链接列表页面
				$this->success("链接删除成功!");
			}else{
				$this->error("链接删除失败...");
			}		
		}

		//友情链接排序
		function order() {
			//获取link表操作对象
			$db = D('link');
			$num = 0;
			foreach($_POST['ord'] as $id => $ord) {
				$num += $db->update(array("id"=>$id, "ord"=>$ord));
			}

			if($num > 0) {
				$cache = new FileCache();
				$cache->delete("link");
				$this->success("重新排序成功!");
			}else{
				$this->error("重新排序失败...");
			}

		}
	}
