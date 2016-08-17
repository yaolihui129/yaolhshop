<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	/**
	 *咨询管理
	 */
	class Ask {
		//咨询列表
		function index() {
			$askdb = D('ask');

			//用户选择回复的状态（待回复｜已回复）			
			$sqlwhere = " and a.`state` = '".intval($_GET['state'])."'";
			//如果名子为空， 按商品名子搜索
			!empty($_GET['name']) && $sqlwhere .= " and b.`name` like '%{$_GET['name']}%'";
			//如果咨询内容不为空， 按咨询内容搜索
			!empty($_GET['asktext']) && $sqlwhere .= " and a.`asktext` like '%{$_GET['asktext']}%'";
			//如果用户名不为空，按用户名搜索
			!empty($_GET['uname']) && $sqlwhere .= " and a.`uname` like '%{$_GET['uname']}%'";
			//关联ask表和product两个表
			$sql = "select a.id aid, a.state, b.name, a.atime,a.asktext,a.uip, a.uname, b.logo, b.id pid, a.replytext, a.replytime from `".TABPREFIX."ask` a, `".TABPREFIX."product` b where a.`pid` = b.`id` {$sqlwhere} order by a.`id` desc";


			$page = new Page($askdb->query($sql, 'total'), 20);

		
	
			$sql .="  LIMIT {$page->limit}";

			$data = $askdb->query($sql, 'select');


		
			$this->assign('data', $data);
			$this->assign('fpage', $page->fpage(0,4,6,8));
			$this->assign('title', "商品咨询");
			$this->assign("menumark", "ask");
			$this->display();
		}

	

		//咨询回复
		function mod() {

			$askdb = D('ask');


			if(isset($_POST['do_submit'])) {
				if(!empty($_POST['replytext'])) {
					$_POST['replytime'] = time();
					$_POST['state'] = 1;		
				}else{
					$_POST['replytime'] = $_POST['state'] = 0;
					
				}

							
				
				if($askdb->where($_POST['id'])->update("replytext='{$_POST['replytext']}', replytime='{$_POST['replytime']}',state='{$_POST['state']}'")) {
					$this->success("回复成功！",1,"ask/index/state/1");
				}else {
					$this->error("回复失败...");
				}
			
			}

			$sql = "select a.id, a.atime, a.uname, a.asktext,a.pid,a.uip,a.replytext, b.name from `".TABPREFIX."ask` a, `".TABPREFIX."product` b where a.`pid` = b.`id` and a.id='{$_GET['id']}'";


			$data= $askdb -> query($sql, 'find');

	

			$this->assign($data);
			$this->assign('title', "咨询详情");
			$this->display();			
		}

		//咨询删除
		function del() {
			//如果是批量删除则$_POST['id']这个数组会存在， 如果是删除单个$_GET['id']一个值会存在
			$id = !empty($_POST['id']) ? $_POST['id'] : $_GET['id'];

			$db = D('ask');

			if($db->delete($id)) {
				$this->success('删除成功!');
			}else{
				$this->error('删除失败...');
			}
		}


	}
