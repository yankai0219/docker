<?php
require('Smarty/Smarty.class.php');
// smarty 的初始化
$smarty = new Smarty();
$baseDir = '/home/yankai/devspace/demo/src/www/front/';
$smarty->template_dir = $baseDir . 'templates';
$smarty->config_dir   = $baseDir . 'configs';
$smarty->cache_dir    = $baseDir . 'cache';
$smarty->compile_dir  = $baseDir . 'templates_c';
$smarty->left_delimiter = '{%';
$smarty->right_delimiter = '%}';
// 开启缓存 method1
// $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
// 开启缓存 method2
// 让每个缓存的过期时间都可以在display执行前单独设置。
#$smarty->setCaching(Smarty::CACHING_LIFETIME_SAVED);
#$smarty->setCacheLifetime(5);

$smarty->assign('name', '明天');
$smarty->assign('sex', '男');
$smarty->assign('time', time());
$arr = array(
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
    'key4' => 'value4',
);
$smarty->assign('array', $arr);
$carr = array(
    'cc1', 
    'cc2', 
    'cc3',
);
$smarty->assign('carr', $carr);
$smarty->display('index.tpl');
