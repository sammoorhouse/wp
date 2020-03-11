<?php get_header();

$page_bg = gt3_option('page_404_bg');
$page_bg_url  = $page_bg['url'];
$class_404 = '';
if ($page_bg_url) {
	$class_404 .= ' has_bg';
}
?>
	<div class="wrapper_404 <?php echo esc_attr($class_404); ?>" <?php echo !empty($page_bg_url) ? "style='background-image: url(".esc_url($page_bg_url).")';" : "" ?> ><div class="container_vertical_wrapper"><div class="container"><h1 class="number_404"><span><?php echo esc_html__('4', 'ewebot'); ?></span><div class="planet_404_wrapper"><div class="planet_404_front"></div><div class="planet_404_ring"></div><div class="planet_404_back"></div></div><span><?php echo esc_html__('4', 'ewebot'); ?></span></div><h2><?php echo esc_html__('Sorry We Can\'t Find That Page!', 'ewebot'); ?></h2><p><?php echo esc_html__('The page you are looking for was moved, removed, renamed or never existed.', 'ewebot'); ?></p><div class="gt3_module_button"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Take me Home', 'ewebot'); ?></a></div></div>
		</div>
	</div>
<?php get_footer(); ?>