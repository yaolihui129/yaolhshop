<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	class Common extends Action {
		function init() {
			//如果用户不是登录用户，直接到登录页面去
			if(!bro_login("admin")) {
				$this->error('你还没有登录， 请先登录...', 3, 'Login/index');	
			}



				
			$adminmenu[] = array(
				'headnav' => '商品管理',
				'subnav' => array(
						array('name' => '商品列表', 'menumark' => 'product', 'url' => B_APP.'?m=product'),
						array('name' => '商品分类', 'menumark' => 'category', 'url' => B_APP.'?m=category'),
						array('name' => '商品咨询', 'menumark' => 'ask', 'url' => B_APP.'?m=ask'),
						array('name' => '商品评价', 'menumark' => 'comment', 'url' => B_APP.'?m=comment')
				)
			);

			$adminmenu[] = array(
				'headnav' => '交易管理',
				'subnav' => array(
					array('name' => '订单管理', 'menumark' => 'order', 'url' => B_APP.'?m=order'),
					array('name' => '支付方式', 'menumark' => 'payway', 'url' => B_APP.'?m=payway')
				)
			);

			$adminmenu[] = array(
				'headnav' => '信息管理',
				'subnav' => array(
					array('name' => '文章分类', 'menumark' => 'aclass', 'url' => B_APP.'?m=aclass'),
					array('name' => '文章列表', 'menumark' => 'article', 'url' => B_APP.'?m=article'),
					array('name' => '单页列表', 'menumark' => 'page', 'url' => B_APP.'?m=page')
				)
			);

			$adminmenu[] = array(
				'headnav' => '用户管理',
				'subnav' => array(
					array('name' => '会员列表', 'menumark' => 'user', 'url' => B_APP.'?m=user'),
					array('name' => '管理列表', 'menumark' => 'admin', 'url' => B_APP.'?m=admin')
				)
			);

			$adminmenu[] = array(
				'headnav' => '控制面板',
				'subnav' => array(
					array('name' => '基本信息', 'menumark' => 'setting', 'url' => B_APP.'?m=setting&a=mod'),
					array('name' => '缓存管理', 'menumark' => 'cache', 'url' => B_APP.'?m=cache'),
					array('name' => '友情链接', 'menumark' => 'link', 'url' => B_APP.'?m=link'),
					array('name' => '广告列表', 'menumark' => 'ad', 'url' => B_APP.'?m=ad')
				)
			);
			
			
			$this -> assign("adminmenu", $adminmenu);
		}

		function upimage(){
			$up = new FileUpload();
			$path =  rtrim(B_UP_PATH, '/').'/tmp';
			$up -> set('path', $path)->set('datedir', 'Y-m');		
			$up->set("allowtype", array("gif", "png", "jpg", "jpeg"));
			 

			if($up->upload("upload")){
				$filename=$up->getFileName();
				$this->mkhtml(rtrim(B_UPW_PATH, '/')."/tmp/".$filename);
			}else{
				$mess=strip_tags($up->getErrorMsg());	
				$this->mkhtml('', $mess);
			}


		}

		function upflash(){
			$up = new FileUpload();
			$path =  rtrim(B_UP_PATH, '/').'/tmp';
			$up -> set('path', $path)->set('datedir', 'Y-m');
			$up->set("allowtype", array("flv","swf"));
		
			if($up->upload("upload")){
				$filename=$up->getFileName();
				$this->mkhtml(rtrim(B_UPW_PATH, '/')."/tmp/".$filename);
			}else{
				$mess=strip_tags($up->getErrorMsg());	
				$this->mkhtml('', $mess);
			}
		}


		protected function mkhtml($fileurl,$message="") {
			$str='<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$_GET['CKEditorFuncNum'].', \''.$fileurl.'\', \''.$message.'\');</script>';
			exit($str);
		}

	}
