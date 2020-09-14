<?php
/**
 * @package Basic_Form
 * @version 1.0.0
 */

/*
 * Plugin Name: Basic_Form
 * Plugin URI: https://github.com/ngtwewy/basicform
 * Description: 这是一个基础表单插件，用来管理用户提交的表单信息，比如网站留言。
 * Author: ngtwewy <62006464@qq.com>
 * Author URI: https://javascript.net.cn
 * License: Apache Licence 2.0
 * License URI: http://www.apache.org/licenses/LICENSE-2.0
 */

define( 'MY__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

//将插件在左侧菜单中显示
function register_left_my_menu()
{
    add_options_page("Basic_Form设置页面", "意向登记记录", 8, __FILE__, "basic_form");
}

//插件内容
function basic_form()
{
    //echo '这里是basic_form插件的页面内容，可以添加表单设置。';
    require_once MY__PLUGIN_DIR . "/views/index.php";
}

//在 admin_menu 勾子中添加动作 basic_form
if (is_admin()) {
    add_action("admin_menu", "register_left_my_menu");
}