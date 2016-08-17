<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	/**
	 *
	 * 单页管理
	 */
	class Page  {
		//单页列表
		function index() {
			$db = D('page');

			$data = $db -> select();
		

			$this->assign("data", $data);
			$this->assign("title", "单页列表");
			$this->assign("menumark", "page");
			$this->display();
		}

		//添加单页
		function add() {
			//如果是在添加页面用户点击添加按钮，就有提交变量
			if(isset($_POST['do_submit'])) {
				//获取表操作对象
				$db = D('page');
				//格式转换
				$_POST['content'] = str_replace(array("\"", "'"), array("&quot;", "&#039;"), stripslashes($_POST['content']));
				//调用db中的insert()方法，将数据加入数据库
				if($db->insert($_POST, array('content'))) {
					//添加成功转向链接列表页面
					$this->success("单页增加成功!", 1,  "index");
				}else{
					$this->error("单页增加失败...");
				}
			}

		

			$this->assign("textarea", Form::editor('content', '', 'full', 400));
			$this->assign("title", "增加单页");
			$this->display();		
		}

		//修改单页
		function mod() {
			//创建文章数据库操作对象
			$db = D('page');

			//如果是获取修改页面需要创建一个数据对象获取数据
			$srcdata = $db->find($_GET['id']);
			//格式转回来
			$srcdata['content'] = str_replace( array("&quot;", "&#039;"), array("\"", "'"), $srcdata['content']);

			//如果用户提交则处理
			if(isset($_POST['do_submit'])) {
			
			
				//格式转换
				$_POST['content'] = str_replace(array("\"", "'"), array("&quot;", "&#039;"), stripslashes($_POST['content']));
			
				
				//调用db中的insert()方法，将数据加入数据库
				if($db->update($_POST, array('content'))) {
					$cache = new FileCache();
					$cache->delete("page_{$_POST['id']}");
					//添加成功转向列表页面
					$this->success("单页修改成功!", 1, "index");
				}else{
					$this->error("单页修改失败...");
				}
			
			}

			//给前台一个文本编辑器
			$this->assign("textarea", Form::editor('content', $srcdata['content'], 'full', 400));
		
			$this->assign($srcdata);

			$this->assign("title", "修改单页");
			$this->display("add");
		}

		//删除单页
		function del() {
			//如果是批量删除则$_POST['id']这个数组会存在， 如果是删除单个$_GET['id']一个值会存在
			$id = !empty($_POST['id']) ? $_POST['id'] : $_GET['id'];

			$db = D('page');

			if($db->delete($id)) {
				$cache = new FileCache();
				$cache->delete("page_{$_GET['id']}");
				$this->success('删除成功!');
			}else{
				$this->error('删除失败...');
			}		
		}
	}
