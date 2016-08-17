<?php
	defined('IN_BROSHOP') or exit('No permission resources.');
	class Article {

		//文章内容
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

			//将分类转换成一维数组
			$classes = array();

			foreach($class as $v) {
				$classes[$v['id']]=$v['catname'];
			}

			//获取当前id获取文章
			$articledb = D('article');

			$article = $articledb->find($_GET['id']);
			$article['content'] = str_replace( array("&quot;", "&#039;"), array("\"", "'"), $article['content']);

			$this->assign($article);

			$this->assign("nowpath", " > 资讯中心 > <a href='".B_APP."?m=article&a=plist&cid={$article['cid']}'>{$classes[$article['cid']]}</a> > <a href='".B_APP."?m=article&a=index&id={$article['id']}'>{$article['name']}</a>");
			$this->assign("classes", $classes); 

			$this->assign('title', $article['name']);

			$this->display("page/index");
			//将文章的文章数加一
			$articledb -> where($_GET['id']) -> update('clicknum = clicknum+1');
		}

		//文章列表
		function plist() {
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


			//将分类转换成一维数组
			$classes = array();

			foreach($class as $v) {
				$classes[$v['id']]=$v['catname'];
			}

			//获取当前cid分类下的所有文章
			$articledb = D('article');
			//分页对象
			$fpage = new Page($articledb->where(array("cid"=>$_GET['cid']))->total(), 20);

			$articles = $articledb ->where(array("cid"=>$_GET['cid'])) ->limit($fpage->limit)-> select();
		
		
			$this->assign("info_list", $articles);


			$this->assign("nowpath", " > 资讯中心 > <a href='".B_APP."?m=article&a=plist&cid={$_GET['cid']}'>{$classes[$_GET['cid']]}</a>");
			$this->assign("classes", $classes); 
			//输出分页
			$this->assign("fpage", $fpage->fpage(4,5,6,7,8, 0));

			$this->assign('title', "资讯中心");
			$this->display("page/index");
		}
	}
