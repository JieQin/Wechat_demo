<?php
	header("Content-Type:text/html;charset=utf-8");
	//1.定义项目的名称
	define('APP_NAME', 'Imooc');
	//2.定义项目路径
	define('APP_PATH', 'Imooc/');
	//3.开启调试模式
	define('APP_DEBUG',True);
	//4.引入tp核心文件
	require('./ThinkPHP/ThinkPHP.php');
