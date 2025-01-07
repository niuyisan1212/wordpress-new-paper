<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header>
	<p class="home-art-text"><?php bloginfo('name'); ?></p>
<?php if ( !is_home() ) { ?>
<a style="font-weight: bold; color: var(--blockquote-color)" href="<?php echo esc_url(home_url('/')); ?>">← 首页</a>
<?php if ( is_single() ) { ?> | <span style="font-weight: bold; color: var(--blockquote-color)"><?php the_category( ', ' );?></span> <?php } ?>
<?php } else { ?>
<nav class="main-navigation">
<?php if ( has_nav_menu( 'primary-menu' ) ) :?>
 <ul class="nav-list">
        <!-- 左侧：分类列表 -->
        <?php
        $categories = get_categories(array('orderby' => 'name', 'hide_empty' => false));
        foreach ($categories as $category) {
            echo '<li class="category-item"><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
        }
        ?>

        <!-- 右侧：“关于我”链接 -->
        <li class="menu-item-about-us">
            <a href="<?php echo esc_url(home_url('/2025/01/05/%e5%85%b3%e4%ba%8e%e6%88%91/')); ?>">关于我</a>
        </li>
    </ul>
</div>
<?php endif; ?>
</nav>
<?php } ?>
</header>
<main>