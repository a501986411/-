<?php
/**
 * 获取小说章节内容
 * User: chl
 * Date: 2018/6/7
 * Time: 9:20
 */
require_once __DIR__ . '/../autoloader.php';
use phpspider\core\phpspider;
use phpspider\core\selector;

/* Do NOT delete this comment */
/* 不要删除这段注释 */
$configs = array(
    'name' => '易看小说信息-章节内容',
    //'tasknum' => 4,
    'log_show' => false,
    'save_running_state' => false,
    'domains' => array(
        'www.yikanxiaoshuo.net',
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
        'table' => 'novel_main_info',
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
            'name' => "title",
            'selector' => "//div[@id='info']/h1",
            'required' => true,
        ),
        //
        array(
            'name' => "author",
            'selector' => "//a[contains(@href,'author')]",
            'required' => true,
        ),
        // 小说简介
        array(
            'name' => "description",
            'selector' => "//div[@id='intro']//p",
            'required' => true,
        ),
        // 小说类型
        array(
            'name' => "type_str",
            'selector' => "//div[@class='con_top']/a[last()]",
            'required' => true,
        ),
        // 图片
        array(
            'name' => "first_image",
            'selector' => "//div[@id='fmimg']//img",
            'required' => true,
        ),
    ),
    'input_encoding' => array(
        'input_encoding' =>'gbk',
        'output_encoding' =>'UTF-8',
    ),
);
$spider = new phpspider($configs);
$spider->on_extract_page = function ($page, $data) {
    $downImages =  function($file_url, $save_to="../data/images")
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch,CURLOPT_URL,$file_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $file_content = curl_exec($ch);
        curl_close($ch);
        $fileName = pathinfo($file_url,PATHINFO_BASENAME);
        $save_to .= '/'.$fileName;
        $downloaded_file = fopen($save_to, 'w');
        fwrite($downloaded_file, $file_content);
        fclose($downloaded_file);
        return $fileName;
    };
    $url = $page['url'];
    $data['domain'] = $url;
    preg_match_all("/\d+/",$url,$ret);
    $data['domain_id'] = $ret[0][count($ret[0])-1];
    $data['first_image'] = call_user_func($downImages,$data['first_image']);
    return $data;
};
$spider->start();