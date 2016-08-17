<?php
	defined('IN_BROSHOP') or exit('No permission resources.');

	class Login extends Action {
		//用户登录处理
		function index() {
			if (isset($_POST['do_submit'])) {
				if(strtoupper($_POST['code']) != strtoupper($_SESSION['code'])) {
					$this->error("验证码输入有误...");
				}


				$name = $_POST['name'];
				$pw =  md5(md5('bro_'.$_POST['pw']));

			

				$db = D('admin');
				//通过用户名和密码从数据中查找
				$user = $db -> field("id, name")->where(array("name"=>$name, "pw"=>$pw))->find();
				//如果找到用户则说明用户名和密码是对的， 设置登录
				if($user) {
					//设置用户的最后登录时间
					$db->update(array("id"=>$user['id'], 'ltime'=>time()));

					$_SESSION = $user; //将查出来的用户的信息都放到session中

					//设置一个前台user登录的暗号token
					$_SESSION['admin_token'] = md5($user['id'].$_SERVER['HTTP_HOST']);

					$this->success('用户登录成功！', 1, 'index/index');
				
				} else {
					$this->error('用户名或密码错误...');
				}

		
			}
			

			$this->display();
		}
		//用户退出
		function  logout() {
			

			$_SESSION=array();


			if(isset($_COOKIE[session_name()])){
				setCookie(session_name(), '', time()-3600, '/');
			}

			session_destroy(); 

			$this->success('管理员退出成功！', 1,'login/index');	
		}

		function vcode() {
			echo new Vcode();
		}
	}
