<?php
	//全局可以使用的通用函数声明在这个文件中.
	function bro_login($utype){
		return (md5($_SESSION["id"].$_SERVER['HTTP_HOST']) == $_SESSION["{$utype}_token"]) ? 1 : 0;
	}


	//设置图片缩放
	function thumb($img, $w, $h, $wm=true) {
		if(empty($img)) {
			return  B_PUBLIC."/images/nopic.gif";
		}

		//原图片
		$srcpath = rtrim(B_UP_PATH, "/")."/".dirname($img);

		//原图片名
		$srcimg = basename($img);

		//缩略图名称前缀
		$thumbname = "thumb_{$w}x{$h}_";
	


		//缩略图片保存名称
		$thumbimgpathname = "/".dirname($img)."/".$thumbname.$srcimg;
		

		$cacheimg =  rtrim(B_UPC_PATH, '/').$thumbimgpathname;
		//远程请求的根
		$webroot =  rtrim(B_UPCW_PATH, '/').$thumbimgpathname;



		//如果没有缓存图片就生成一缓存
		if(!file_exists($cacheimg)) {
		
			//如果原图不存在使用一张默认图片
			if(!file_exists($srcpath."/".$srcimg)) {
				return  B_PUBLIC."/images/nopic.gif";	
			}
		
			createFolder(dirname($cacheimg));

			
			$image =new Image($srcpath);

			$tmp = $image -> thumb($srcimg, $w, $h, $thumbname);
			//通过第四个参数，设置不加水印
			if($wm) {
				//如果图片够大就加水印
				if($w > 150 && $h > 150) {
					$image -> watermark($tmp,  './public/images/water.gif', 5, '');
				}
			}

			rename($srcpath."/".$tmp, $cacheimg);
			
			
		}
		

	
		return $webroot;
		
	}



	//上传图片
	function bro_upload($upname='logo') {
		//下面几行文件上传
		$up = new FileUpload();
		
		$path =  rtrim(B_UP_PATH, "/");   //使用配置文件指定的上传路径常量B_UP_PATH

		$up -> set('path', $path)->set('datedir', 'Y-m');


		if($up->upload($upname)) {
			$picname = $up->getFileName();
			//图片缩放， 系统用到的最大图片不会超过1000*1000, 所以上传的图片都要控制在这个像素以里
			$img = new Image($path.'/'.dirname($picname));
			//默认让图片上传都不超过1000x1000， 可以通过变量在配置文件中设置
			$img -> thumb(basename($picname), 1000, 1000, '');

			return array(true, $picname);	
		}else{

			return array(false, $up->getErrorMsg());
		}
	}
	

