<?php
return array(
	//'配置项'=>'配置值'

    /**********页面静态配置************/
    'HTML_CACHE_ON'     =>    false, // 开启静态缓存
    'HTML_CACHE_TIME'   =>    60,   // 全局静态缓存有效期（秒）
    'HTML_FILE_SUFFIX'  =>    '.shtml', // 设置静态缓存文件后缀
    'HTML_CACHE_RULES'  =>     array(  // 定义静态缓存规则
        // 定义格式1 数组方式
        'Index:index'    =>     array('index', 86400),  //将首页设置一天静态缓存
        'Index:goods'    =>     array('goods-{id}', 86400),  //将商品详情设置一天静态缓存

    )
);