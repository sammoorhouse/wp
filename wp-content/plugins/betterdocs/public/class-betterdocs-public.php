<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wpdeveloper.net
 * @since      1.0.0
 *
 * @package    BetterDocs
 * @subpackage BetterDocs/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    BetterDocs
 * @subpackage BetterDocs/public
 * @author     WPDeveloper <support@wpdeveloper.net>
 */
class BetterDocs_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'init', array( $this, 'public_hooks' ) );

	}					

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in BetterDocs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The BetterDocs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, BETTERDOCS_PUBLIC_URL . 'css/betterdocs-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'simplebar', BETTERDOCS_PUBLIC_URL . 'css/simplebar.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in BetterDocs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The BetterDocs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script('masonry');
		wp_enqueue_script( 'clipboard', BETTERDOCS_PUBLIC_URL . 'js/clipboard.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, BETTERDOCS_PUBLIC_URL . 'js/betterdocs-public.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'simplebar', BETTERDOCS_PUBLIC_URL . 'js/simplebar.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'betterdocspublic', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'post_id' => get_the_ID(),  
		));
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function public_hooks() {
		add_filter( 'archive_template', array( $this, 'get_docs_archive_template' ) );
		add_filter( 'single_template', array( $this, 'get_docs_single_template' ), 99 );
		add_filter( 'template_include', array( $this, 'get_docs_category_taxonomy_template' ) );
		add_filter( 'template_include', array( $this, 'get_docs_tag_taxonomy_template' ) );
		$enable_toc = BetterDocs_DB::get_settings('enable_toc');
		if($enable_toc == 1) {
			add_filter( 'the_content', array( $this, 'betterdocs_the_content' ) );
		}
		$defaults = betterdocs_generate_defaults();
		if($defaults['betterdocs_docs_layout_select'] === 'layout-2') {
			add_filter( 'betterdocs_doc_page_cat_icon_size2_default', 80 );
		}
		if (is_admin()) {
		add_filter('plugin_action_links_' . BETTERDOCS_BASENAME, array($this, 'insert_plugin_links'));
		}
	}

	/**
	 * Get Archive Template for the docs base directory.
	 *
	 * @since    1.0.0
	 */
	public function get_docs_archive_template( $archive_template ) {

		if ( is_post_type_archive( 'docs' ) ) {
			$layout_select = get_theme_mod('betterdocs_docs_layout_select', 'layout-1');
			if($layout_select === 'layout-2'){
				$archive_template = BETTERDOCS_PUBLIC_PATH . 'partials/archive-template/category-box.php';
			}else{
				$archive_template = BETTERDOCS_PUBLIC_PATH . 'partials/archive-template/category-list.php';
			}
		}
		return $archive_template;
	}

	/**
	 * Get Category Taxonomy Template for the docs base directory.
	 *
	 * @since    1.0.0
	 */
	public function get_docs_category_taxonomy_template( $template ) {

		if ( is_tax( 'doc_category' ) ) {
			$template = BETTERDOCS_PUBLIC_PATH . 'betterdocs-category-template.php';
		}
		return $template;
	}

	/**
	 * Get Tags Taxonomy Template for the docs base directory.
	 *
	 * @since    1.0.0
	 */
	public function get_docs_tag_taxonomy_template( $template ) {

		if ( is_tax( 'doc_tag' ) ) {
			$template = plugin_dir_path( dirname( __FILE__ ) ) . 'public/betterdocs-tag-template.php';
		}
		return $template;
	}

	/**
	 * Get Single Page Template for docs base directory.
	 *
	 * @param int $single_template Overirde single templates.
	 * 
	 * @since    1.0.0
	 */
	public function get_docs_single_template( $single_template ) {

		if ( is_singular( 'docs' ) ) {
			$single_template = plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/template-single/layout-1.php';
		}
		return $single_template;
	}

	/**
	 * Get supported heading tag from settings
	 *
	 * @since    1.0.0
	 */
	private static function htag_support(){
		$supported_tag = BetterDocs_DB::get_settings('supported_heading_tag');
		if( ! empty( $supported_tag ) && $supported_tag !== 'off' ) {
			$tags = implode(',',$supported_tag);
		}
		return $tags;
	}

	private static function list_hierarchy() {
		$content = get_the_content();
		preg_match_all( '/(<h(['.self::htag_support().']{1})[^>]*>).*<\/h\2>/msuU', $content, $matches, PREG_SET_ORDER );

		$current_depth      = 6;
		$html               = '';
		$numbered_items     = array();
		$numbered_items_min = NULL;
		$html .= '<ol class="toc-list">';
		// find the minimum heading to establish our baseline
		for ( $i = 0; $i < count( $matches ); $i ++ ) {
			

			if ( $current_depth > $matches[ $i ][2] ) {
				$current_depth = (int) $matches[ $i ][2];
			}
		}

		$numbered_items[ $current_depth ] = 0;
		$numbered_items_min               = $current_depth;

		for ( $i = 0; $i < count( $matches ); $i ++ ) {

			if ( $current_depth == (int) $matches[ $i ][2] ) {

				$html .= '<li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement">';
			}

			// start lists
			

			for ( $current_depth; $current_depth < (int) $matches[ $i ][2]; $current_depth++ ) {
				if ( $current_depth + 1 == (int) $matches[ $i ][2] ) {
					$numbered_items[ $current_depth + 1 ] = 0;
					$html .= '<ol><li>';
				}
				
			}
			

			// list item

			$title = $matches[ $i ][0];
			$title = strip_tags( $title );
			$has_id = preg_match('/id=(["\'])(.*?)\1[\s>]/si', $matches[ $i ][0], $matched_ids);
			$id = $has_id ? $matched_ids[2] : $i . '-' . sanitize_title($title);
			$html .= '<a itemprop="item" href="#'.$id.'">' . $title . '</a>';

			// end lists
			if ( $i != count( $matches ) - 1 ) {

				if ( $current_depth > (int) $matches[ $i + 1 ][2] ) {

					for ( $current_depth; $current_depth > (int) $matches[ $i + 1 ][2]; $current_depth-- ) {
						if ( $current_depth == (int) $matches[ $i ][2] ) {
							$html .= '</li></ol>';
							$numbered_items[ $current_depth ] = 0;
						}
					}
				}

				if ( $current_depth == (int) @$matches[ $i + 1 ][2] ) {

					$html .= '</li>';
				}

			} else {

				// this is the last item, make sure we close off all tags
				for ( $current_depth; $current_depth >= $numbered_items_min; $current_depth -- ) {

					$html .= '</li>';

					if ( $current_depth != $numbered_items_min ) {
						if ( $current_depth == (int) $matches[ $i ][2] ) {
							$html .= '</ol>';
						}
					}
				}
			}
		}
		$html .= '</ol>';
		return $html;
	}

	
	/**
	 * Return table of content list before single post the_content
	 * 
	 * @since    1.0.0
	 */
	public function betterdocs_the_content($content){
		if ( in_array( get_post()->post_type, [ 'docs' ] ) ) {
			if( preg_match_all( '/(<h(['.self::htag_support().']{1})[^>]*>).*<\/h\2>/msuU', $content, $matches, PREG_SET_ORDER )){
				$table_of_content = "
					<div class='betterdocs-toc'>
						<h2 class='toc-title'>".esc_html__('Table of Contents','betterdocs')."</h2>
				";
				$table_of_content .= self::list_hierarchy();
				$table_of_content .= '</div>';
				$index = 0;

				$content = preg_replace_callback('#<(h['.self::htag_support().'])(.*?)>(.*?)</\1>#si', function ($matches) use (&$index, &$table_of_content) {
					$tag = $matches[1];
					$title = strip_tags($matches[3]);
					$has_id = preg_match('/id=(["\'])(.*?)\1[\s>]/si', $matches[2], $matched_ids);
					$id = $has_id ? $matched_ids[2] : $index++ . '-' . sanitize_title($title);

					if ($has_id) {
						return $matches[0];
					}
					
					$title_link_ctc = BetterDocs_DB::get_settings('title_link_ctc');
					if($title_link_ctc == 1){
						$hash_link = '<a href="#'.$id.'" class="anchor" data-clipboard-text="'. get_permalink() .'#'. $id .'" data-title="'.esc_html__('Copy URL','betterdocs').'">#</a>';
					} else {
						$hash_link = '';
					}
					return sprintf('<%s%s class="betterdocs-content-heading" id="%s">%s %s</%s>', $tag, $matches[2], $id, $matches[3], $hash_link, $tag);
				}, $content);
				

				$content = $table_of_content . '<div id="betterdocs-single-content" class="betterdocs-content" itemscope itemtype="http://schema.org/Article">'. $content . '</div>';
			}else{
				$content = '<div id="betterdocs-single-content" class="betterdocs-content" itemscope itemtype="http://schema.org/Article">'. $content . '</div>';
			}
			return $content;
		} else {
			return $content;
		}
	}

	/**
	 * Insert quick action link to plugin page
	 * 
	 * @since    1.1.5
	 */
	public function insert_plugin_links($links) {
        // settings
        $links[] = sprintf('<a href="admin.php?page=betterdocs-settings">' . esc_html__('Settings','betterdocs') . '</a>');
        //$links[] = sprintf('<a href="https://betterdocs.co/docs" target="_blank">' . esc_html__('Documentation','betterdocs') . '</a>');

        return $links;
	}

}
