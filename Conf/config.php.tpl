<?php
return array(
	// 数据库配置信息
	'DB_TYPE'   	=> 'mysql', // 数据库类型
	'DB_HOST'   	=> 'localhost', // 服务器地址
	'DB_NAME'   	=> '', // 数据库名
	'DB_USER'   	=> '', // 用户名
	'DB_PWD'    	=> '', // 密码
	'DB_PORT'   	=> 3306, // 端口
	'DB_PREFIX' 	=> 'se_', // 数据库表前缀
	// 全局布局
	'LAYOUT_ON'		=> true,
	'LAYOUT_NAME'	=> 'layout',
	// URL
	'URL_MODEL'		=> 2,
	// TOKEN
	'TOKEN_ON'		=> true,  // 是否开启令牌验证
	'TOKEN_NAME'	=> '__hash__',    // 令牌验证的表单隐藏字段名称
	'TOKEN_TYPE'	=> 'md5',  //令牌哈希验证规则 默认为MD5
	'TOKEN_RESET'	=> false,  //令牌验证出错后是否重置令牌 默认为true
);
?>