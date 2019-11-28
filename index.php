<?php
// 消息推送测试文件
// +----------------------------------------------------------------------
// | PHP version 5.3+
// +----------------------------------------------------------------------
// | Copyright (c) 2012-2014 http://www.myzy.com.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 阶级娃儿 <262877348@qq.com> 群：304104682
// +----------------------------------------------------------------------
header("Content-Type: Text/Html;Charset=UTF-8");
require "./vendor/autoload.php";

$obj = new \think\ImageLib(360, 640);

$image = [
    'uuid'          => 1,
    'url'           => 'https://img.alicdn.com/i3/2933756947/O1CN01yPhPjE21Bm2BzRX7t_!!2933756947.jpg',
    'title'         => '秋冬运动裤女加绒加厚哈伦裤休闲裤羊羔绒卫裤保暖裤外穿裤子女',
    'mall_type'     => 0,
    'size'          => '498.00',
    'price'         => '298.00',
    'format_volume' => '月销1.33万件',
    'user_code'     => './images/code.png',
    'nickname'      => '遵命,老婆大人',
    'expiry_time'   => '2019-11-29',
];

$obj->printImage($image);





