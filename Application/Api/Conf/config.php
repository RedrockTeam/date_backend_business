<?php
return array(
	//'配置项'=>'配置值'
    'DEFAULT_MODULE'       =>    'Api',  // 默认模块
    'DEFAULT_FILTER'        => 'trim, strip_tags, htmlspecialchars',//自定义过滤方法
    /**
     * 路由配置
     */
    'URL_ROUTER_ON'   => true,
    'URL_ROUTE_RULES' => [
        //公共
        'public/banner' => 'Banner/index',//广告位
    ],
);