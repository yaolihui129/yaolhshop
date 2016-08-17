<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	class Page  {
		//单页内容
		function index() {
			//创建一个缓存对象 
			$cache = new FileCache();
			$class = $cache -> get('class');

			if(!$class) {

				//文章分类对象
				$classdb = D("class");

				$class = $classdb->order("ord asc,id asc")->select();

				$cache -> set('class', $class);

			}

			$this->assign("class", $class);
	

			$page = $cache -> get("page_{$_GET['id']}");

			if(!$page) {

				$pagedb = D("page");
			
				$page = $pagedb->find($_GET['id']);

				$cache -> set("page_{$_GET['id']}", $page);
			}

			$page['content'] = str_replace( array("&quot;", "&#039;"), array("\"", "'"), $page['content']);

			$this->assign("nowpath", " > 帮助中心 > <a href='".B_APP."?m=page&a=index&id={$page['id']}'>{$page['name']}</a>");
			$this->assign($page);
			$this->display();
		}


	}
