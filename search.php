<?php
/*
Template Name: 搜索模板
*/
?>
<?php get_header(); ?>
<h1 class="title">搜索<?php if ( is_search()) : ?><?php printf( '关键词：%s' , '<span>&ldquo;' . get_search_query() . '&rdquo;</span>' ); ?><?php endif; ?></h1>
<form action="/" method="get" class="searchform">
<input type="text" name="s" id="search" value="<?php the_search_query(); ?>" placeholder="请输入关键词..." />
<input type="submit" id="searchsubmit" value="搜索" />
</form>
<article>
<?php if ( is_search() && have_posts() ) : ?>
<div class="intro"><?php global $found_posts; printf( '为你找到 %s 条相关结果', $wp_query->found_posts ); ?></div>
<ul class="results">
<?php while ( have_posts() ) : the_post();?>
<li>
<?php if ( 'post' == get_post_type()){ echo "[ "; the_category(', '); echo " ]"; } ?> <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a><br /><small><time datetime="<?php the_time('Y年m月d日');?>"><?php the_time('Y/m/d');?></time></small>
</li>
<?php endwhile; ?>
</ul>
<?php elseif ( is_search() && ! have_posts() ) : ?>
<?php global $found_posts; printf( '抱歉，没有找到相关的结果，你可以在上方搜索栏中尝试其他关键词。' , get_search_query() ); ?>
<?php endif; ?>
</article>
<?php if ( get_the_posts_pagination() ) : ?><nav>
<?php if ( get_previous_posts_link() ) : ?><?php previous_posts_link( '上一页' ); ?><?php endif; ?>
<?php if ( get_next_posts_link() ) : ?><?php next_posts_link( '下一页' ); ?><?php endif; ?></nav><?php endif; ?>
<?php get_footer(); ?>