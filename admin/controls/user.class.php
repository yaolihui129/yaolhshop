<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	/**
	 * 会员管理
	 *
	 */
	class User{
		//会员列表
		function index() {
			//创建数据库操作对象
			$db = D('user');

	
			if(!empty($_GET['name'])) {
				$where['name'] = "%{$_GET['name']}%";
			}

	
			if(!empty($_GET['email'])) {
				$where['email'] = "%{$_GET['email']}%";
			}

	
			if(!empty($_GET['phone'])) {
				$where['phone'] = "%{$_GET['phone']}%";
			}

		
			
			$total = $db->where($where)->total();

			//设置分页
			$page = new Page($total, 20);

			if(!empty($_GET['orderby'])) {
				$order= "id desc";
			}else{
				$orderby = "{$_GET['orderby']} desc";
			}
				
			
			//获取数据
			$data = $db->where($where)->limit($page->limit)->order($order)->select();
		


			$args = $_GET;
		
			unset($args['orderby']);
			$this->assign("args", http_build_query($args));

			//将获取的会员信息分到前台显示
			$this -> assign("data", $data);
			//将分页给前台
			$this -> assign("fpage", $page->fpage(0,4,6,8));
	
	

			$this->assign("menumark", "user");
			$this->assign("title", "会员列表");
			$this->display();
		}


		//会员修改
		function mod() {
			//创建会员数据库操作对象
			$db = D('user');

		
			//如果用户提交则处理
			if(isset($_POST['do_submit'])) {
			
			
			
			
				if(!empty($_POST['pw'])) {
					$_POST['pw'] =  md5(md5('bro_'.$_POST['pw']));
				}

			
				//调用db中的update()方法，将数据加入数据库
				if($db->update()) {
					//添加成功转向列表页面
					$this->success("会员修改成功!", 1, "index");
				}else{
					$this->error("会员修改失败...");
				}
			
			}


		
			$this->assign($db->find($_GET['id']));
			$this->assign("title", "修改会员");
			$this->display();			
		}

		//会员删除
		function del() {
			//如果是批量删除则$_POST['id']这个数组会存在， 如果是删除单个$_GET['id']一个值会存在
			$id = !empty($_POST['id']) ? $_POST['id'] : $_GET['id'];

			$db = D('user');

			if($db->delete($id)) {
				$this->success('删除成功!');
			}else{
				$this->error('删除失败...');
			}
		}

	}


