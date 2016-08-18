<?php
	define("DEBUG", 0);				      //开启调试模式 1 开启 0 关闭
	define("CHARSET", 'utf8');			      //设置数据库的传输字符集， 注释则不进行设置, 如果设计默认值为utf8
	define("DRIVER", "pdo");				      //数据库的驱动，本系统支持pdo(默认)和mysqli两种
	//define("DSN", "mysql:host=localhost;dbname=brophp"); //如果使用PDO可以使用，不使用则默认连接MySQL
	define("HOST", "localhost:3307");			      //数据库主机
	define("USER", "root");                               //数据库用户名
	define("PASS", "root");                                   //数据库密码
	define("DBNAME", "broshop");			      //数据库名
	define("TABPREFIX", "bro_");                           //数据表前缀
	define("CSTART", 0);                                  //缓存开关 1开启，0为关闭
	define("CTIME", 60*60*24*7);                          //缓存时间




	//$memServers = array("localhost", 11211);	     //使用memcache服务器
	/*
	如果有多台memcache服务器可以使用二维数组
	$memServers = array(
			array("www.lampbrother.net", '11211'),
			array("www.brophp.com", '11211'),
			...
		);
	 */




	define('IN_BROSHOP', true);   //每个通过框架加载的文件，都需要判断这个常量是否存在



