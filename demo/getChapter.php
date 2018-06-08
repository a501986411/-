<?php
/**
 * 获取小说章节信息
 * User: chl
 * Date: 2018/6/7
 * Time: 9:20
 */
require_once __DIR__ . '/../autoloader.php';
use phpspider\core\phpspider;
use phpspider\core\log;

/* Do NOT delete this comment */
/* 不要删除这段注释 */
$configs = array(
    'name' => '易看小说信息',
    //'tasknum' => 4,
    'log_show' => false,
    'save_running_state' => false,
    'domains' => array(
        "www.yikanxiaoshuo.net",
    ),
    'scan_urls' => array(
        "http://www.yikanxiaoshuo.net",
    ),
    'list_url_regexes' => array(
        "http://www.yikanxiaoshuo.net/((xuanhuanmofa)|(wuxiaxiuzhen)|(dushiyanqing)
           |(lishijunshi)|(wangyoujingji)|(kehuanxiaoshuo)|(quanben))/$",
        "http://www.yikanxiaoshuo.net/((xuanhuanmofa)|(wuxiaxiuzhen)|(dushiyanqing)
           |(lishijunshi)|(wangyoujingji)|(kehuanxiaoshuo)|(quanben))/\d+.html$",
        "http://www.yikanxiaoshuo.net/modules/article/articlelist.php\?fullflag=1\&page=\d+$",
    ),
    'content_url_regexes' => array(
        "http://www.yikanxiaoshuo.net/\d+/\d+/$",
    ),
    'export' => array(
        'type' => 'db',
        'table' => 'novel_chapter_info',
    ),
    'db_config' => array(
        'host'  => '127.0.0.1',
        'port'  => 3306,
        'user'  => 'root',
        'pass'  => '',
        'name'  => 'novel2',
    ),
    'fields' => array(
        // 标题
        array(
            'name' => "name",
            'selector' => "//li[@class='chapter']/a",
            'required' => true,
            'repeated' => true,
        ),
        array(
            'name' => "novel_id",
            'selector' => "//li[@class='chapter']/a/@href",
            'required' => true,
            'repeated' => true,
        ),
    ),
    'input_encoding' => array(
        'input_encoding' =>'gbk',
        'output_encoding' =>'UTF-8',
    ),
);
$spider = new phpspider($configs);
$spider->on_extract_page = function ($page, $data) {
    $url = $page['url'];
    $result = [];
    foreach ($data['name'] as $key=>$name){
        $tmp = [];
        $tmp['name'] = $name;
        $tmp['domain'] = $url;
        preg_match_all("~\d+~",$data['novel_id'][$key],$metchs);
        $metchs = $metchs[0];
        $tmp['chapter_id'] = $metchs[count($metchs) -1];
        $tmp['novel_id'] = $metchs[count($metchs) -2];
        $result[$key] = $tmp;
    }
    return $result;
};
$spider->start();