<?php 
get_header();
?>

<?php if (is_home()) { ?>
	<h1 class="title"><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
	<div class="intro"><?php bloginfo( 'description' ); ?></div>
<?php } ?>

<?php if (is_archive()) { ?>
	<h1 class="title"><?php the_archive_title(); ?></h1>
	<div class="intro">
		<p><?php the_archive_description(); ?></p>
		<small>共有 <?php echo esc_html($wp_query->found_posts); ?> 篇文章</small>
	</div>
<?php } ?>

<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post();?>
	<article>
	<?php $site_title_elem 	= is_front_page() || ( is_home() || is_archive() ) ? 'h2' : 'h1'; ?>

	<?php if ( !is_page() && !is_single()) { ?>
		<time datetime="<?php the_time('Y-m-d'); ?>"><?php echo get_the_date( '', $post->ID ); ?></time>
		<<?php echo $site_title_elem; ?> class="title">
		<a href="<?php the_permalink(); ?>"><?php if ( is_sticky() && is_home() ) : ?>🔝 <?php endif; ?><?php the_title(); ?></a>
		</<?php echo $site_title_elem; ?>>
		<div class="content"><?php the_excerpt(); ?></div>
		<small><?php echo count_words_read_time(); ?></small>
	<?php } ?>
	<?php if ( is_single() ) { ?>
		<<?php echo $site_title_elem; ?> class="title">
		<a href="<?php the_permalink(); ?>"><?php if ( is_sticky() && is_home() ) : ?>🔝 <?php endif; ?><?php the_title(); ?></a>
		</<?php echo $site_title_elem; ?>>
		<small><?php echo count_words_read_time(); ?></small>
		<div class="content"><?php the_content(); ?></div>
		<p><time datetime="<?php the_time('Y-m-d'); ?>"><?php echo get_the_date( '', $post->ID ); ?></time></p>
	<?php } ?>
	<?php if ( is_single() ) { ?>
	<p><?php wp_link_pages(); ?></p>
	<?php if ( get_the_tags() ) { ?><p class="tags"><?php the_tags( ' #', ' #', ' ' ); ?></p><?php } ?>
	<?php } ?>
	</article>
	<?php endwhile;  ?>
	<?php if ( is_single() ) { ?><p><?php if (comments_open()) {comments_template();}?></p><?php } ?>
	<?php if (is_home() || is_archive()) { ?>
	<?php if ( get_the_posts_pagination() ) : ?><nav>
	<?php
		global $wp_query;
		$big = 999999999; // 需要一个不可能的大数字
		echo paginate_links(array(
			'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
			'format' => '?paged=%#%',
			'current' => max(1, get_query_var('paged')),
			'total' => $wp_query->max_num_pages,
			'prev_text' => __('« 上一页'),
			'next_text' => __('下一页 »'),
			'type' => 'list', // 使用列表格式
			'mid_size' => 2, // 显示在当前页码两侧的页码数量
		));
		?></nav><?php endif; ?>
	<?php } ?>
<?php endif; ?>

<?php 
get_footer();
?>