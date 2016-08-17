<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	/**
	 * 网站基本信息管理
	 */
	class Setting {
		function mod() {
			$db = D('setting');
			//如果用户提交则修改 
			if(isset($_POST['do_submit'])) {
										
				//如果用户重新选择了图片才去上传
				if($_FILES['web_logo']['error']== 0) {
					//调用自定义函数
					$up = bro_upload('web_logo');
					//如果上传成功，则传给$_POST
					if($up[0]) {
						$_POST['web_logo'] = $up[1];
					}else {
						$this->error($up[1]);
					}
					
				}


				$num = 0;

				foreach($_POST as $k => $v) {
					$num += $db -> where(array("skey"=>$k)) -> update("svalue='{$v}'");

					if($k=="web_tpl") {
						$filename = "index.php";
						file_put_contents($filename, preg_replace("/define\(\"TPLSTYLE\".+?;/i", "define(\"TPLSTYLE\", \"{$v}\");", file_get_contents($filename)));
					}
				}

				if($num) {
					//如果有修改就更新缓存
					$cache = new FileCache();
					$cache -> delete('setting');
					$this->success("基本信息设置成功!");
				}else{
					$this->error('基本信息设置失败...');
				}
			}

			
			
			$allsets = $db -> select();


			foreach($allsets as $v) {
				$this->assign($v['skey'], $v['svalue']);
			}

			//设置使用的模板,调用全局函数
			$this->assign("tpls", bdirs('./home/views/'));

			$this->assign("title","基本信息");
			$this->assign("menumark", "setting");
			$this->display();
		}
	}
