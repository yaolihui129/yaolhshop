<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	/**
	 * 文章管理
	 *
	 */
	class Article {
		//文章列表
		function index() {
			//创建数据库操作对象
			$db = D('article');

			//获取分类的数据库操作对象，用于获取分类列表
			$cat = D('class');

			$where = array();
			
		
			//判断用户是否按分类查找
			if(!empty($_GET['cid']) || $_GET['cid'] != 0) {
				$where['cid'] = $_GET['cid'];
			
			}

			if(!empty($_GET['name'])) {
				$where['name'] = "%{$_GET['name']}%";
			}

		
			
			$total = $db->where($where)->total();

			//设置分页
			$page = new Page($total, 20);
		
			
			//获取数据
			$data = $db ->field('id,name,cid,atime,clicknum')->where($where)->limit($page->limit)->select();
		
	
			//将设置的查询条件发到模板
			$this->assign("where", $_GET);


			//将获取的文章信息分到前台显示
			$this -> assign("data", $data);
			//将分页给前台
			$this -> assign("fpage", $page->fpage(0,4,6,8));
			//将分类名数组分到前台
			$aclass =  $cat->select();
			$this -> assign("aclass", $aclass);
			//在列表中使用分类
			$fcat = array();
			foreach($aclass as $v) {
				$fcat[$v['id']] = $v['catname'];
			}
			$this -> assign("fcat", $fcat);

			$this->assign("menumark", "article");
			$this->assign("title", "文章列表");
			$this->display();
		}

		//文章添加
		function add() {
			//获取分类的数据库操作对象，用于获取分类列表
			$cat = D('class');
			

			//如果用户提交则处理
			if(isset($_POST['do_submit'])) {

				//将时间格式转换
				$_POST['atime'] = strtotime($_POST['atime']);
				//格式转换
				$_POST['content'] = str_replace(array("\"", "'"), array("&quot;", "&#039;"), stripslashes($_POST['content']));
				//创建文章数据库操作对象
				$db = D('article');
				
				//调用db中的insert()方法，将数据加入数据库
				if($db->insert($_POST, array('content'))) {
				
					//添加成功转向链接列表页面
					$this->success("文章增加成功!", 1, "index");
				}else{
					$this->error("文章增加失败...");
				}
			
			}
			//如果是获取修改页面需要创建一个数据对象获取数据

			//给前台一个日历
			$this->assign("date", Form::date('atime', date('Y-m-d H:i:s'), 1));
			//给前台一个文本编辑器
			$this->assign("textarea", Form::editor('content', '', 'full', 300));
			//将分类列表分给前台
			$this->assign("aclass", $cat->select());
			$this->assign("title", "发布文章");
			$this->display();
		}
	



		//文章修改
		function mod() {
			//获取分类的数据库操作对象，用于获取分类列表
			$cat = D('class');

			//创建文章数据库操作对象
			$db = D('article');

			//如果是获取修改页面需要创建一个数据对象获取数据
			$srcdata = $db->find($_GET['id']);
			//格式转回来
			$srcdata['content'] = str_replace( array("&quot;", "&#039;"), array("\"", "'"), $srcdata['content']);

			//如果用户提交则处理
			if(isset($_POST['do_submit'])) {
			
				//将时间格式转换
				$_POST['atime'] = strtotime($_POST['atime']);

				//格式转换
				$_POST['content'] = str_replace(array("\"", "'"), array("&quot;", "&#039;"), stripslashes($_POST['content']));
			
				
				//调用db中的insert()方法，将数据加入数据库
				if($db->update($_POST, array('content'))) {
					//添加成功转向列表页面
					$this->success("文章修改成功!", 1,  "index");
				}else{
					$this->error("文章修改失败...");
				}
			
			}


			//给前台一个日历
			$this->assign("date", Form::date('atime', date('Y-m-d H:i:s', $srcdata['atime']), 1));
			//给前台一个文本编辑器
			$this->assign("textarea", Form::editor('content', $srcdata['content'], 'full', 300));
			//将分类列表分给前台
			$this->assign("aclass", $cat->select());
			$this->assign($srcdata);
			$this->assign("title", "修改文章");
			$this->display("add");			
		}

		//文章删除
		function del() {
			//如果是批量删除则$_POST['id']这个数组会存在， 如果是删除单个$_GET['id']一个值会存在
			$id = !empty($_POST['id']) ? $_POST['id'] : $_GET['id'];

			$db = D('article');

			if($db->delete($id)) {
				$this->success('删除成功!');
			}else{
				$this->error('删除失败...');
			}
		}

	}

