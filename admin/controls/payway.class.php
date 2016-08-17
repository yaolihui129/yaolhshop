<?php
	defined('IN_BROSHOP') or exit('No permission resources.');

	/**
	 * 支付管理
	 *
	 */
	class Payway  {
		//支付列表
		function index() {
			//获取表操作对象
			$db = D('payway');

			$info_list = $db->order('ord asc, id asc')->select();

			$this->assign('info_list', $info_list);
			$this->assign('title', '支付方式');
			$this->assign("menumark", "payway");
			$this->display();
		}

		//支付修改
		function mod() {


			//获取表操作对象
			$db = D('payway');

			if (isset($_POST['do_submit'])) {
				$_POST['config'] = $_POST['config'] ? serialize($_POST['config']) : '';
				
		
							
				if ($db->where(array('id'=>$_GET['id']))->update()) {
					$cache = new FileCache();
					$cache->delete("payway");
				
					$this->success('支付修改成功!', 1, 'index');
				}
				else {
					$this->error('支付修改失败...' );
				}
			}

			//从数据库获取数据
			$info = $db->find($_GET['id']);

	
		
			$info['model'] = $info['model'] ? unserialize($info['model']) : array();
			$info['config'] = $info['config'] ? unserialize($info['config']) : array();
			

		
	
			$this->assign($info);

			$this->assign("qt", array('1'=>'启用', '0'=>'停用') );
			$this->assign('title', '编辑支付');
			$this->assign("menumark", "payway");
			$this->display();
			
		}

		//支付删除
		function del() {
			//获取表操作对象
			$db = D('payway');
			//删除数据库记录
			if($db->delete($_GET['id'])) {
				$cache = new FileCache();
				$cache->delete("payway");
				//添加成功转向列表页面
				$this->success("支付删除成功!");
			}else{
				$this->error("支付删除失败...");
			}		
		}

		//支付排序
		function order() {
			//获取表操作对象
			$db = D('payway');
			$num = 0;
			foreach($_POST['ord'] as $id => $ord) {
				$num += $db->update(array("id"=>$id, "ord"=>$ord));
			}

			if($num > 0) {
				$cache = new FileCache();
				$cache->delete("payway");
				$this->success("重新排序成功!");
			}else{
				$this->error("重新排序失败...");
			}
		}
	}
