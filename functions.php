<?php
// 每页显示5条内容
function set_posts_per_page($query) {
   $query->set('posts_per_page', 5);
}
add_action('pre_get_posts', 'set_posts_per_page');
// 增加文章字数统计，阅读时长估计
function count_words_read_time () {
  global $post;
  $text_num = mb_strlen(preg_replace('/s/','',html_entity_decode(strip_tags($post->post_content))),'UTF-8');
  $read_time = ceil($text_num/200); // 修改数字200调整时间
  $output .= '共计' . $text_num . '字，阅读大约' . $read_time  . '分钟';
  return $output;
}
// 分类页默认显示文章100个字 其它的省略
function new_excerpt_length($length) { 
    return 100; 
} 
add_filter('excerpt_length', 'new_excerpt_length'); 
function theme_excerpt_more($more) {
    global $post;
    return '<div><a class="read-more" href="' . get_permalink($post->ID) . '" class="read-more" title="阅读全文">继续阅读</a></div>';
}
add_filter('excerpt_more', 'theme_excerpt_more');
// 去除归档页面的"分类："
function my_theme_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    }
  
    return $title;
}
add_filter( 'get_the_archive_title', 'my_theme_archive_title' );

// 主题设置
if (!function_exists('yayu_setup')) :
	function yayu_setup()
	{
      add_theme_support('automatic-feed-links');
      add_theme_support('title-tag');
	  add_theme_support('custom-background');
      register_nav_menu('primary-menu', 'Primary Menu');
    }
    add_action('after_setup_theme', 'yayu_setup');
endif;

// 主题样式
if ( ! function_exists( 'yayu_load_style' ) ) :
	function yayu_load_style() {
		wp_enqueue_style( 'yayu-style', get_stylesheet_uri(), array(), '20240831' );
	}
	add_action( 'wp_enqueue_scripts', 'yayu_load_style' );
endif;

// 程序优化
remove_action('wp_head', 'wp_generator'); // 移除WordPress版本
remove_filter('comment_text', 'make_clickable', 9); // 移除wordpress留言中自动链接功能
remove_action('wp_head', 'rsd_link'); // 移除离线编辑器开放接口
remove_action('wp_head', 'index_rel_link'); // 去除本页唯一链接信息
remove_action('wp_head', 'wlwmanifest_link'); // 移除离线编辑器开放接口
remove_filter('the_content', 'wptexturize'); // 禁止代码标点符合转义

// 禁用REST API、移除wp-json链接
add_filter('rest_enabled', '_return_false');
add_filter('rest_jsonp_enabled', '_return_false');
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

// 禁用l10n.js
wp_deregister_script('l10n');

// 禁止头部加载s.w.org
function remove_dns_prefetch($hints, $relation_type)
{
	if ('dns-prefetch' === $relation_type) {
		return array_diff(wp_dependencies_unique_hosts(), $hints);
	}
	return $hints;
}
add_filter('wp_resource_hints', 'remove_dns_prefetch', 10, 2);

// 移除原生 gallery style
add_filter('use_default_gallery_style', '__return_false');

// 彻底移除管理员工具条
add_filter('show_admin_bar','__return_false');

// 禁用Open Sans
function remove_open_sans()
{
	wp_deregister_style('open-sans');
	wp_register_style('open-sans', false);
	wp_enqueue_style('open-sans', '');
}
add_action('init', 'remove_open_sans');

// 禁用 auto-embeds
remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );

// 阻止站内文章 Pingback
add_action('pre_ping', 'no_self_ping');
function no_self_ping(&$links)
{
	$home = home_url();
	foreach ($links as $l => $link)
		if (0 === strpos($link, $home))
			unset($links[$l]);
}

// WordPress 关闭 XML-RPC 的 pingback 端口
add_filter( 'xmlrpc_methods', 'remove_xmlrpc_pingback_ping' );
function remove_xmlrpc_pingback_ping( $methods ) {
	unset( $methods['pingback.ping'] );
	return $methods;
}

// 禁用XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// 禁用 emoji's
function disable_emojis()
{
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
}
add_action('init', 'disable_emojis');

// 用于删除tinymce插件的emoji
function disable_emojis_tinymce($plugins)
{
	if (is_array($plugins)) {
		return array_diff($plugins, array('wpemoji'));
	} else {
		return array();
	}
}

// 禁用 wp-embed.min.js
function my_deregister_scripts(){
    wp_dequeue_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );

// 删除'wpembed'TinyMCE插件
function disable_embeds_tiny_mce_plugin( $plugins ) {
    return array_diff( $plugins, array( 'wpembed' ) );
}

// 禁用古滕堡编辑器
add_filter('use_block_editor_for_post', '__return_false', 10);  
add_filter('use_widgets_block_editor', '__return_false', 10);
remove_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );

// 移除头部 Gutenberg global-styles-inline-css
add_action( 'wp_print_styles', function()
{
  wp_deregister_style('global-styles');
} );

//移除经典主题样式 classic-theme-styles-inline-css
add_action( 'wp_enqueue_scripts', function() {
	wp_dequeue_style( 'classic-theme-styles' );
}, 20 );
?>