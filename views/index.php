<?php
// 加载分页
define( 'MY__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once MY__PLUGIN_DIR . "lib/page/src/pagination.class.php";

// 打印函数
if(!function_exists('p')){
    function p($var)
    {
        echo "<pre>";
        print_r($var);
    }
}


global $wpdb;

// 每页显示条数
$pageSize = 15;
// 数据总数
$sql = "SELECT * FROM my_form WHERE 1 ORDER BY id DESC";
$counter = $wpdb->query($sql);

$pagenum = isset($_GET['pagenum']) ? intval($_GET['pagenum']) : 1;
$start = $pageSize * ($pagenum - 1);
$sql = "SELECT * FROM my_form WHERE 1 ORDER BY id DESC LIMIT %d OFFSET %d";
$list = $wpdb->get_results( $wpdb->prepare( $sql, $pageSize, $start));

// 分页
$page = new Pagination($counter, $pageSize);
$page->setQueryField(['page'=>'pagenum']);
$page->pagerCount = 6; // 显示页数
$page->prevText = '上一页';
$page->nextText = '下一页';

?>

<link href="<?php echo bloginfo('siteurl')."/wp-content/plugins/basicform/lib/page/css/pagination.css"?>" rel="stylesheet">
<link href="xx<?php echo bloginfo('siteurl')."/wp-content/plugins/basicform/assets/css/bootcss.css"?>" rel="stylesheet">

<div class="wrap">
    <h1>意向登记记录插件</h1>
    <p>意向登记记录插件是用来查看和管理前端收集的表单信息。作者地址：http://javascript.net.cn </p>
    <table class="my-form widefat striped">
        <tr>
            <th>公司名称</th>
            <th>称呼</th>
            <th>地区</th>
            <th>手机</th>
            <th>邮箱</th>
            <th>留言</th>
            <th>时间</th>
        </tr>
        <?php foreach($list as $k=>$v):?>
            <tr>
                <td><?php echo $v->company ?></td>
                <td><?php echo $v->user_name ?></td>
                <td><?php echo $v->country ?></td>
                <td><?php echo $v->mobile ?></td>
                <td><?php echo $v->email ?></td>
                <!-- td>
                    <?php
                        switch($v->purpose){
                            case 1:
                                echo "产品加盟";
                                break;
                            case 2:
                                echo "产品代理";
                                break; 
                            case 3:
                                echo "定制";
                                break; 
                            case 4:
                                echo "OEM贴牌";
                                break;
                            default:
                                echo "未填写";
                        }
                    ?>
                </td-->
                <td><?php echo $v->content ?></td>
                <td><?php echo date("Y-m-d H:i", strtotime($v->create_time)) ?></td>
            </tr>
        <?php endforeach ?>
    </table>
    <div class="i-page">
        <?php echo $page->links(['pager', 'prev', 'next']); ?>
    </div>
</div>

<style>
.my-form th{
    font-weight: bold;
}
.my-form tr td:nth-child(1){
    width: 10%;
}
.my-form tr td:nth-child(2){
    width: 10%;
}
.my-form tr td:nth-child(3){
    width: 7%;
}
.i-page{
    margin-top: 15px;
}
.m-pager .m-pager-number:nth-child(1){
    margin-left: 0px!important;
}
</style>