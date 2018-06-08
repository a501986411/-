<?php
/**
 * Created by PhpStorm.
 * User: chl
 * Date: 2018/6/7
 * Time: 9:54
 */
require_once __DIR__ . '/../autoloader.php';
use phpspider\core\phpspider;
use phpspider\core\requests;
use phpspider\core\selector;
use phpspider\core\log;
log::$log_file = "../data/phpspider.log";
requests::$input_encoding = 'gbk';
requests::$output_encoding = 'UTF-8';
/* Do NOT delete this comment */
/* 不要删除这段注释 */

$url = "/3/3636/2742314.html";
$ret = preg_match_all("#\d+#i", $url,$matchs);
print_r($matchs);die;
//$html = requests::get($url);

// 抽取文章标题
$selector = "//div[@id='info']/h1";
$title = selector::select($html, $selector);
Log::info($title);
echo json_encode($title,JSON_UNESCAPED_UNICODE);die;