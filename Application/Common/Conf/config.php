<?php
return array(

    /************数据库配置*************/
	'DB_TYPE' =>  'pdo',     // mysql,mysqli,pdo
    'DB_DSN'    => 'mysql:host=localhost;dbname=ecshop;charset=utf8',
    'DB_USER' =>  'root',      // 用户名
    'DB_PWD' =>  'q123456',          // 密码
    'DB_PORT' =>  '3306',        // 端口
    'DB_PREFIX' =>  'ec_',    // 数据库表前缀
    //'DB_HOST' =>  'localhost', // 服务器地址
    //'DB_NAME' =>  'php39',          // 数据库名
    //'DB_CHARSET' =>  'utf8',      // 数据库编码默认采用utf8
    'DEFAULT_FILTER' => 'trim,htmlspecialchars',


    /************图片相关配置*************/
    'IMAGE_CONFIG' => array(
        'maxSize' => 1024 * 1024 , // 1M
        'exts' => array('jpg', 'gif', 'png', 'jpeg'),// 设置附件上传类型
        'rootPath' => './Public/Uploads/', // 设置附件上传根目录
        'viewPath' => '/Public/Uploads/', // 设置模板中显示根目录

    )

);