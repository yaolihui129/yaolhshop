<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	/**
	 *评价管理
	 */
	class Comment  {
		//评价列表
		function index() {
			$commentdb = D('comment');

			//如果名子为空， 按商品名子搜索
			!empty($_GET['name']) && $sqlwhere .= " and b.`name` like '%{$_GET['name']}%'";
			//如果评价内容不为空， 按评价内容搜索
			!empty($_GET['content']) && $sqlwhere .= " and a.`content` like '%{$_GET['content']}%'";
			//如果用户名不为空，按用户名搜索
			!empty($_GET['uname']) && $sqlwhere .= " and a.`uname` like '%{$_GET['uname']}%'";
			//关联comment表和product两个表
			$sql = "select a.id aid, b.name, a.content, a.atime,a.uname, b.logo, b.id pid from `".TABPREFIX."comment` a, `".TABPREFIX."product` b where a.`pid` = b.`id` {$sqlwhere} order by a.`id` desc";


			$page = new Page($commentdb->query($sql, 'total'), 20);

		
	
			$sql .="  LIMIT {$page->limit}";

			$data = $commentdb->query($sql, 'select');


		
			$this->assign('data', $data);
			$this->assign('fpage', $page->fpage(0,4,6,8));
			$this->assign('title', "商品评价");
			$this->assign("menumark", "comment");
			$this->display();
		}

	

		//评价修改
		function mod() {

			$commentdb = D('comment');
			if(isset($_POST['do_submit'])) {
							
				
				if($commentdb->where($_POST['id'])->update("content='{$_POST['content']}'")) {
					$this->success("修改成功！", 1, "comment/index");
				}else {
					$this->error("修改失败...");
				}
			
			}

			$sql = "select a.id, a.atime, a.uname, a.content,a.pid,a.uip, b.name from `".TABPREFIX."comment` a, `".TABPREFIX."product` b where a.`pid` = b.`id` and a.id='{$_GET['id']}'";


			$data= $commentdb -> query($sql, 'find');

	

			$this->assign($data);
			$this->assign('title', "评价详情");
			$this->display();			
		}

		//评价删除
		function del() {
			//如果是批量删除则$_POST['id']这个数组会存在， 如果是删除单个$_GET['id']一个值会存在
			$id = !empty($_POST['id']) ? $_POST['id'] : $_GET['id'];

			$db = D('comment');

			if($db->delete($id)) {
				$this->success('删除成功!');
			}else{
				$this->error('删除失败...');
			}
		}


	}

