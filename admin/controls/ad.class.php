<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	/**
	 * 广告管理
	 */
	class Ad  {
		private $position = array(1=>'首页顶部广告(980*80)', 2=>'首页底部广告(980*80)', 3=>'所有页面顶部广告(980*80)', 4=>'所有页面底部广告(980*80)',  5=>'首页焦点图广告(730*300)');
		//广告列表	
		function index() {
			$db =D("ad");

			$data = $db -> order('ord asc, id asc')->select();

			$this->assign("allposition", $this->position);
			$this->assign("data", $data);
			$this->assign("title", "广告列表");
			$this->assign("menumark", "ad");
			$this->display();
		}

		//增加广告
		function add() {
			//如果用户提交
			if(isset($_POST['do_submit'])) {
				//下面几行上传文件				
				$up = bro_upload('logo');

				if($up[0]) {
					$_POST['logo'] = $up[1];
				}else {
					$this->error($up[1]);
				}


				$db = D('ad');			
				//调用db中的insert()方法，将数据加入数据库
				if($db->insert($_POST, 1, 1)) {
					$cache = new FileCache();
					$cache->delete("ad");
					//添加成功转向列表页面
					$this->success("广告增加成功!", 1, "index");
				}else{
					$this->error($db->getMsg());
				}
			}

			$this->assign("title", "增加广告"); 
			$this->assign("allposition", $this->position);
			$this->display();
		}

		//修改广告
		function mod() {
			$db = D('ad');	
			$srcdata = $db->find($_GET['id']);
		
			//如果用户提交
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

					
				//调用db中的insert()方法，将数据加入数据库
				if($db->update()) {
					//删除原来的图片
					if(isset($_POST['logo'])) {
						@unlink('./public/uploads/attachment/'.$srcdata['logo']);	
					}
					$cache = new FileCache();
					$cache->delete("ad");
					//添加成功转向列表页面
					$this->success("广告修改成功!", 1, "index");
				}else{
					$this->error("广告修改失败...");
				}

			}

			$srcdata['ad_url'] = $srcdata['url'];
			$this->assign($srcdata);
			$this->assign("title", "修改广告");
			$this->assign("allposition", $this->position);
			$this->display("add");
		}

		//广告删除
		function del() {
			//获取表操作对象
			$db = D('ad');
			$srcdata = $db->field('logo')->find($_GET['id']);
			//删除数据库记录
			if($db->delete($_GET['id'])) {
				//删除原来的图片
				@unlink('./public/uploads/attachment/'.$srcdata['logo']);	
				
				$cache = new FileCache();
				$cache->delete("ad");
				//添加成功转向列表页面
				$this->success("广告删除成功!");
			}else{
				$this->error("广告删除失败...");
			}		
		}

		//广告排序
		function order() {
			//获取表操作对象
			$db = D('ad');
			$num = 0;
			foreach($_POST['ord'] as $id => $ord) {
				$num += $db->update(array("id"=>$id, "ord"=>$ord));
			}

			if($num > 0) {
				$cache = new FileCache();
				$cache->delete("ad");
				$this->success("重新排序成功!");
			}else{
				$this->error("重新排序失败...");
			}
		}
	}

