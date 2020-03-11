<?php


if (!class_exists( 'RWMB_Loader' )) {
	return;
}



add_filter( 'rwmb_meta_boxes', 'gt3_pteam_meta_boxes' );
function gt3_pteam_meta_boxes( $meta_boxes ) {
    $meta_boxes[] = array(
        'title'      	=> esc_html__( 'Team Options', 'ewebot' ),
        'post_types' 	=> array( 'team' ),
        'context' 		=> 'advanced',
        'fields'     	=> array(
        	array(
	            'name' 			=> esc_html__( 'Member Job', 'ewebot' ),
	            'id'   			=> 'position_member',
	            'type' 			=> 'text',
	            'class' => 'field-inputs'
	        ),

	        array(
	            'name' 			=> esc_html__( 'Short Description', 'ewebot' ),
	            'id'   			=> 'member_short_desc',
	            'type' 			=> 'textarea'
	        ),
			array(
				'name' 			=> esc_html__( 'Fields', 'ewebot' ),
	            'id'   			=> 'social_url',
	            'type' 			=> 'social',
	            'clone' => true,
	            'sort_clone'     => true,
	            'desc' 			=> esc_html__( 'Description', 'ewebot' ),
	            'options' => array(
					'name'    => array(
						'name' 			=> esc_html__( 'Title', 'ewebot' ),
						'type_input' => "text"
						),
					'description' => array(
						'name' 			=> esc_html__( 'Text', 'ewebot' ),
						'type_input' => "text"
						),
					'address' => array(
						'name' 			=> esc_html__( 'Url', 'ewebot' ),
						'type_input' => "text"
						),
				),
	        ),
	        array(
				'name' 			=> esc_html__( 'Icons', 'ewebot' ),
				'id'          	=> "icon_selection",
				'type'        	=> 'select_icon',
				'text_option' => true,
				'options'     	=> function_exists('gt3_get_all_icon') ? gt3_get_all_icon() : '',
				'clone' => true,
				'sort_clone'     => true,
				'placeholder' => esc_html__( 'Select an icon', 'ewebot' ),
				'multiple'    	=> false,
				'std'  			=> 'default',
			),
	        array(
		        'name'             => esc_html__( 'Signature', 'ewebot' ),
		        'id'               => "mb_signature",
		        'type'             => 'image_advanced',
		        'max_file_uploads' => 1,
	        ),
        ),
    );
    return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'gt3_blog_meta_boxes' );
function gt3_blog_meta_boxes( $meta_boxes ) {
	$meta_boxes[] = array(
		'title'      	=> esc_html__( 'Post Format Layout', 'ewebot' ),
		'post_types' 	=> array( 'post' ),
		'context' 		=> 'advanced',
		'fields'     	=> array(
			// Standard Post Format
			array(
				'name' 			=> esc_html__( 'You can use only featured image for this post-format', 'ewebot' ),
				'id' 			=> "post_format_standard",
				'type' 			=> 'static-text',
				'attributes' 	=>  array(
					'data-dependency' => array(
						array(
							array('formatdiv','=','0'),
							array('post-format-selector-0','=','standard')
						),
					),
				),
			),
			// Gallery Post Format
			array(
				'name' 			=> esc_html__( 'Gallery images', 'ewebot' ),
				'id' 			=> "post_format_gallery_images",
				'type' 			=> 'image_advanced',
				'max_file_uploads' => '',
				'attributes' 	=>  array(
					'data-dependency' => array(
						array(
							array('formatdiv','=','gallery'),
							array('post-format-selector-0','=','gallery')
						),
					),
				),
			),
			// Video Post Format
			array(
				'name' 			=> esc_html__( 'oEmbed', 'ewebot' ),
				'id'   			=> "post_format_video_oEmbed",
				'desc' 			=> esc_html__( 'enter URL', 'ewebot' ),
				'type' 			=> 'oembed',
				'attributes' 	=>  array(
					'data-dependency' => array(
						array(
							array('formatdiv','=','video'),
							array('post-format-selector-0','=','video')
						),
						array(
							array('post_format_video_select','=','oEmbed')
						)
					),
				),
			),
			// Audio Post Format
			array(
				'name' 			=> esc_html__( 'oEmbed', 'ewebot' ),
				'id'   			=> "post_format_audio_oEmbed",
				'desc' 			=> esc_html__( 'enter URL', 'ewebot' ),
				'type' 			=> 'oembed',
				'attributes' 	=>  array(
					'data-dependency' => array(
						array(
							array('formatdiv','=','audio'),
							array('post-format-selector-0','=','audio')
						),
						array(
							array('post_format_audio_select','=','oEmbed')
						)
					),
				),
			),
			// Quote Post Format
			array(
				'name' 			=> esc_html__( 'Quote Author', 'ewebot' ),
				'id' 			=> "post_format_qoute_author",
				'type' 			=> 'text',
				'attributes' 	=>  array(
					'data-dependency' => array(
						array(
							array('formatdiv','=','quote'),
							array('post-format-selector-0','=','quote')
						),
					),
				),
			),
			array(
				'name' 			=> esc_html__( 'Author Image', 'ewebot' ),
				'id' 			=> "post_format_qoute_author_image",
				'type' 			=> 'image_advanced',
				'max_file_uploads' => 1,
				'attributes' 	=>  array(
					'data-dependency' => array(
						array(
							array('formatdiv','=','quote'),
							array('post-format-selector-0','=','quote')
						),
					),
				),
			),
			array(
				'name' 			=> esc_html__( 'Quote Content', 'ewebot' ),
				'id' 			=> "post_format_qoute_text",
				'type' 			=> 'textarea',
				'attributes' 	=>  array(
					'data-dependency' => array(
						array(
							array('formatdiv','=','quote'),
							array('post-format-selector-0','=','quote')
						),
					),
				),
			),
			// Link Post Format
			array(
				'name' 			=> esc_html__( 'Link URL', 'ewebot' ),
				'id' 			=> "post_format_link",
				'type' 			=> 'url',
				'attributes' 	=>  array(
					'data-dependency' => array(
						array(
							array('formatdiv','=','link'),
							array('post-format-selector-0','=','link')
						),
					),
				),
			),
			array(
				'name' 			=> esc_html__( 'Link Text', 'ewebot' ),
				'id' 			=> "post_format_link_text",
				'type' 			=> 'text',
				'attributes' 	=>  array(
					'data-dependency' => array(
						array(
							array('formatdiv','=','link'),
							array('post-format-selector-0','=','link')
						),
					),
				),
			),


		)
	);
	return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'gt3_page_layout_meta_boxes' );
function gt3_page_layout_meta_boxes( $meta_boxes ) {

    $meta_boxes[] = array(
        'title'      	=> esc_html__( 'Page Layout', 'ewebot' ),
        'post_types' 	=> array( 'page' , 'post', 'team', 'product', 'proof_gallery', 'portfolio' ),
        'context' 		=> 'advanced',
        'fields'     	=> array(
        	array(
				'name' 			=> esc_html__( 'Page Sidebar Layout', 'ewebot' ),
				'id'          	=> "mb_page_sidebar_layout",
				'type'        	=> 'select',
				'options'     	=> array(
					'default' => esc_html__( 'default', 'ewebot' ),
					'none' 	  => esc_html__( 'None', 'ewebot' ),
					'left'    => esc_html__( 'Left', 'ewebot' ),
					'right'   => esc_html__( 'Right', 'ewebot' ),
				),
				'multiple'    	=> false,
				'std'  			=> 'default',
			),
			array(
				'name' 			=> esc_html__( 'Page Sidebar', 'ewebot' ),
				'id'          	=> "mb_page_sidebar_def",
				'type'        	=> 'select',
				'options'     	=> gt3_get_all_sidebar(),
				'multiple'    	=> false,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_page_sidebar_layout','!=','default'),
						array('mb_page_sidebar_layout','!=','none'),
					)),
				),
			),
        )
    );
    return $meta_boxes;
}

add_filter('rwmb_meta_boxes', 'gt3_header_meta_boxes');
function gt3_header_meta_boxes($meta_boxes) {
    $preset_opt = gt3_option('gt3_header_builder_presets');
    $presets_array = array();
    if (isset($preset_opt['current_active'])) {
        unset($preset_opt['current_active']);
    }
    if (isset($preset_opt['def_preset'])) {
        unset($preset_opt['def_preset']);
    }
    if (isset($preset_opt['items_count'])) {
        unset($preset_opt['items_count']);
    }
    $presets_array['default'] = esc_html__( 'Default from Theme Options', 'ewebot' );
    if (empty($preset_opt) || !is_array($preset_opt)) {
        return $meta_boxes;
    }
    foreach ($preset_opt as $key => $value) {
        if (!empty($value['title'])) {
            $presets_array[$key] = $value['title'];
        }else{
            $presets_array[$key] = esc_html__( 'No Name', 'ewebot' );
        }
    }

    $meta_boxes[] = array(
        'title'      => esc_html__( 'Header Builder', 'ewebot' ),
        'post_types' 	=> array( 'page', 'post', 'team', 'product', 'proof_gallery', 'portfolio' ),
        'context' => 'advanced',
        'fields'     => array(
            array(
                'name'     => esc_html__( 'Choose presets', 'ewebot' ),
                'id'          => "mb_header_presets",
                'type'        => 'select',
                'options'     => $presets_array,
                'multiple'    => false,
                'std'         => 'default', 
            ),
        )
    );
    return $meta_boxes;
}


add_filter( 'rwmb_meta_boxes', 'gt3_page_title_meta_boxes' );
function gt3_page_title_meta_boxes( $meta_boxes ) {
    $meta_boxes[] = array(
        'title'      	=> esc_html__( 'Page Title Options', 'ewebot' ),
        'post_types' 	=> array( 'page', 'post', 'team', 'product', 'proof_gallery', 'portfolio' ),
        'context' 		=> 'advanced',
        'fields'     	=> array(
			array(
				'name'     		=> esc_html__( 'Show Page Title', 'ewebot' ),
				'id'          	=> "mb_page_title_conditional",
				'type'        	=> 'select',
				'options'     	=> array(
					'default' 		=> esc_html__( 'default', 'ewebot' ),
					'yes' 			=> esc_html__( 'yes', 'ewebot' ),
					'no' 			=> esc_html__( 'no', 'ewebot' ),
				),
				'multiple'    	=> false,
				'std'         	=> 'default',
			),
			array(
				'id'   			=> 'mb_page_title_use_feature_image',
				'name' 			=> esc_html__( 'Use featured image for the page title background', 'ewebot' ),
				'type' 			=> 'checkbox',
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','!=','no'),
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Page Title Overlay Color', 'ewebot' ),
				'id'   			=> "mb_page_title_overlay_color",
				'type' 			=> 'color',
				'std'         	=> '',
				'js_options' 	=> array(
					'defaultColor' 	=> '',
				),
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','!=','no'),
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Page Sub Title Text', 'ewebot' ),
				'id'   			=> "mb_page_sub_title",
				'type' 			=> 'textarea',
				'cols' 			=> 20,
				'rows' 			=> 3,
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','!=','no'),
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Page Sub Title Text Color', 'ewebot' ),
				'id'   			=> "mb_page_sub_title_color",
				'type' 			=> 'color',
				'std'         	=> '',
				'js_options' 	=> array(
					'defaultColor' 	=> '',
				),
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','!=','no'),
					)),
				),
			),

			array(
				'id'   			=> 'mb_show_breadcrumbs',
				'name' 			=> esc_html__( 'Show Breadcrumbs', 'ewebot' ),
				'type' 			=> 'checkbox',
				'std'  			=> 1,
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','=','yes')
					)),
				),
			),
			array(
				'name'     		=> esc_html__( 'Vertical Alignment', 'ewebot' ),
				'id'       		=> 'mb_page_title_vertical_align',
				'type'     		=> 'select_advanced',
				'options'  		=> array(
					'top' 			=> esc_html__( 'top', 'ewebot' ),
					'middle' 		=> esc_html__( 'middle', 'ewebot' ),
					'bottom' 		=> esc_html__( 'bottom', 'ewebot' ),
				),
				'multiple' 		=> false,
				'std'         	=> 'middle',
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','=','yes')
					)),
				),
			),
			array(
				'name'     		=> esc_html__( 'Horizontal Alignment', 'ewebot' ),
				'id'       		=> 'mb_page_title_horizontal_align',
				'type'     		=> 'select_advanced',
				'options'  		=> array(
					'left' 			=> esc_html__( 'left', 'ewebot' ),
					'center' 		=> esc_html__( 'center', 'ewebot' ),
					'right' 		=> esc_html__( 'right', 'ewebot' ),
				),
				'multiple' 		=> false,
				'std'         	=> 'center',
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Font Color', 'ewebot' ),
				'id'   			=> "mb_page_title_font_color",
				'type' 			=> 'color',
				'std'         	=> '#3b3663',
				'js_options' 	=> array(
					'defaultColor' 	=> '#3b3663',
				),
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Background Color', 'ewebot' ),
				'id'   			=> "mb_page_title_bg_color",
				'type' 			=> 'color',
				'std'  			=> '#ffffff',
				'js_options' 	=> array(
					'defaultColor' 	=> '#ffffff',
				),
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Page Title Background Image', 'ewebot' ),
				'id' 			=> "mb_page_title_bg_image",
				'type' 			=> 'file_advanced',
				'max_file_uploads' => 1,
				'mime_type' 	=> 'image',
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','=','yes')
					)),
				),
			),
			array(
				'name'     		=> esc_html__( 'Background Repeat', 'ewebot' ),
				'id'       		=> 'mb_page_title_bg_repeat',
				'type'     		=> 'select_advanced',
				'options'  		=> array(
					'no-repeat' 	=> esc_html__( 'no-repeat', 'ewebot' ),
					'repeat' 		=> esc_html__( 'repeat', 'ewebot' ),
					'repeat-x' 		=> esc_html__( 'repeat-x', 'ewebot' ),
					'repeat-y' 		=> esc_html__( 'repeat-y', 'ewebot' ),
					'inherit' 		=> esc_html__( 'inherit', 'ewebot' ),
				),
				'multiple' 		=> false,
				'std'         	=> 'no-repeat',
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','=','yes')
					)),
				),
			),
			array(
				'name'     		=> esc_html__( 'Background Size', 'ewebot' ),
				'id'       		=> 'mb_page_title_bg_size',
				'type'     		=> 'select_advanced',
				'options'  		=> array(
					'inherit' 		=> esc_html__( 'inherit', 'ewebot' ),
					'cover' 		=> esc_html__( 'cover', 'ewebot' ),
					'contain' 		=> esc_html__( 'contain', 'ewebot' )
				),
				'multiple' 		=> false,
				'std'         	=> 'cover',
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','=','yes')
					)),
				),
			),
			array(
				'name'     		=> esc_html__( 'Background Attachment', 'ewebot' ),
				'id'       		=> 'mb_page_title_bg_attachment',
				'type'     		=> 'select_advanced',
				'options'  		=> array(
					'fixed' 		=> esc_html__( 'fixed', 'ewebot' ),
					'scroll' 		=> esc_html__( 'scroll', 'ewebot' ),
					'inherit' 		=> esc_html__( 'inherit', 'ewebot' )
				),
				'multiple' 		=> false,
				'std'         	=> 'scroll',
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','=','yes')
					)),
				),
			),
			array(
				'name'     		=> esc_html__( 'Background Position', 'ewebot' ),
				'id'       		=> 'mb_page_title_bg_position',
				'type'     		=> 'select_advanced',
				'options'  		=> array(
					'left top' 		=> esc_html__( 'left top', 'ewebot' ),
					'left center' 	=> esc_html__( 'left center', 'ewebot' ),
					'left bottom' 	=> esc_html__( 'left bottom', 'ewebot' ),
					'center top' 	=> esc_html__( 'center top', 'ewebot' ),
					'center center' => esc_html__( 'center center', 'ewebot' ),
					'center bottom' => esc_html__( 'center bottom', 'ewebot' ),
					'right top' 	=> esc_html__( 'right top', 'ewebot' ),
					'right center' 	=> esc_html__( 'right center', 'ewebot' ),
					'right bottom' 	=> esc_html__( 'right bottom', 'ewebot' ),
				),
				'multiple' 		=> false,
				'std'         	=> 'center center',
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Height', 'ewebot' ),
				'id'   			=> "mb_page_title_height",
				'type' 			=> 'number',
				'std'  			=> 215,
				'min'  			=> 0,
				'step' 			=> 1,
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_page_title_conditional','=','yes')
					)),
				),
			),
			array(
				'id'   			=> 'mb_page_title_top_border',
				'name' 			=> esc_html__( 'Set Page Title Top Border?', 'ewebot' ),
				'type' 			=> 'checkbox',
				'attributes' 	=> array(
				    'data-dependency' => array( array(
				    	array('mb_page_title_conditional','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Page Title Top Border Color', 'ewebot' ),
				'id'   			=> "mb_page_title_top_border_color",
				'type' 			=> 'color',
				'std'  			=> '',
				'js_options' 	=> array(
					'defaultColor' => '',
				),
				'attributes' 	=> array(
				    'data-dependency' => array( array(
				    	array('mb_page_title_conditional','=','yes'),
						array('mb_page_title_top_border','=',true)
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Page Title Top Border Opacity', 'ewebot' ),
				'id'   			=> "mb_page_title_top_border_color_opacity",
				'type' 			=> 'number',
				'std'  			=> 1,
				'min'  			=> 0,
				'max'  			=> 1,
				'step' 			=> 0.01,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
				    	array('mb_page_title_conditional','=','yes'),
						array('mb_page_title_top_border','=',true)
					)),
				),
			),

			array(
				'id'   			=> 'mb_page_title_bottom_border',
				'name' 			=> esc_html__( 'Set Page Title Bottom Border?', 'ewebot' ),
				'type' 			=> 'checkbox',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
				    	array('mb_page_title_conditional','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Page Title Bottom Border Color', 'ewebot' ),
				'id'   			=> "mb_page_title_bottom_border_color",
				'type' 			=> 'color',
				'std'  			=> '',
				'js_options' 	=> array(
					'defaultColor' => '',
				),
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
				    	array('mb_page_title_conditional','=','yes'),
						array('mb_page_title_bottom_border','=',true)
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Page Title Bottom Border Opacity', 'ewebot' ),
				'id'   			=> "mb_page_title_bottom_border_color_opacity",
				'type' 			=> 'number',
				'std'  			=> 1,
				'min'  			=> 0,
				'max'  			=> 1,
				'step' 			=> 0.01,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
				    	array('mb_page_title_conditional','=','yes'),
						array('mb_page_title_bottom_border','=',true)
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Title Bottom Margin', 'ewebot' ),
				'id'   			=> "mb_page_title_bottom_margin",
				'type' 			=> 'number',
				'std'  			=> 80,
				'min'  			=> 0,
				'step' 			=> 1,
				'attributes' 	=> array(
				    'data-dependency' => array( array(
				    	array('mb_page_title_conditional','=','yes')
					)),
				),
			),
        ),
    );
    return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'gt3_footer_meta_boxes' );
function gt3_footer_meta_boxes( $meta_boxes ) {
    $meta_boxes[] = array(
        'title'      	=> esc_html__( 'Footer Options', 'ewebot' ),
        'post_types' 	=> array( 'page', 'post', 'proof_gallery', 'portfolio' ),
        'context' 		=> 'advanced',
        'fields'     	=> array(
			array(
				'name' 			=> esc_html__( 'Prefooter Map', 'ewebot' ),
				'id'          	=> "mb_map_prefooter",
				'type'        	=> 'select',
				'options'     	=> array(
					'default' => esc_html__( 'default', 'ewebot' ),
					'show' 	  => esc_html__( 'Show', 'ewebot' ),
					'hide'    => esc_html__( 'Hide', 'ewebot' ),
				),
				'multiple'    	=> false,
				'std'  			=> 'default',
			),
			array(
				'name' 			=> esc_html__( 'Show Footer', 'ewebot' ),
				'id'          	=> "mb_footer_switch",
				'type'        	=> 'select',
				'options'     	=> array(
					'default' 		=> esc_html__( 'default', 'ewebot' ),
					'yes' 			=> esc_html__( 'yes', 'ewebot' ),
					'no' 			=> esc_html__( 'no', 'ewebot' ),
				),
				'multiple'    	=> false,
				'std'  			=> 'default',
			),
			array(
				'name' 			=> esc_html__( 'Footer Column', 'ewebot' ),
				'id'          	=> "mb_footer_column",
				'type'        	=> 'select',
				'options'     	=> array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				),
				'multiple'    	=> false,
				'std'  			=> '4',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Footer Column 1', 'ewebot' ),
				'id'          	=> "mb_footer_sidebar_1",
				'type'        	=> 'select',
				'options'     	=> gt3_get_all_sidebar(),
				'multiple'    	=> false,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Footer Column 2', 'ewebot' ),
				'id'          	=> "mb_footer_sidebar_2",
				'type'        	=> 'select',
				'options'     	=> gt3_get_all_sidebar(),
				'multiple'    	=> false,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes'),
						array('mb_footer_column','!=','1')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Footer Column 3', 'ewebot' ),
				'id'          	=> "mb_footer_sidebar_3",
				'type'        	=> 'select',
				'options'     	=> gt3_get_all_sidebar(),
				'multiple'    	=> false,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes'),
						array('mb_footer_column','!=','1'),
						array('mb_footer_column','!=','2')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Footer Column 4', 'ewebot' ),
				'id'          	=> "mb_footer_sidebar_4",
				'type'        	=> 'select',
				'options'     	=> gt3_get_all_sidebar(),
				'multiple'    	=> false,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes'),
						array('mb_footer_column','!=','1'),
						array('mb_footer_column','!=','2'),
						array('mb_footer_column','!=','3')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Footer Column 5', 'ewebot' ),
				'id'          	=> "mb_footer_sidebar_5",
				'type'        	=> 'select',
				'options'     	=> gt3_get_all_sidebar(),
				'multiple'    	=> false,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes'),
						array('mb_footer_column','!=','1'),
						array('mb_footer_column','!=','2'),
						array('mb_footer_column','!=','3'),
						array('mb_footer_column','!=','4')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Footer Column Layout', 'ewebot' ),
				'id'          	=> "mb_footer_column2",
				'type'        	=> 'select',
				'options'     	=> array(
					'6-6' => '50% / 50%',
                    '3-9' => '25% / 75%',
                    '9-3' => '75% / 25%',
                    '4-8' => '33% / 66%',
                    '8-3' => '66% / 33%',
				),
				'multiple'    	=> false,
				'std'  			=> '6-6',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes'),
						array('mb_footer_column','=','2')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Footer Column Layout', 'ewebot' ),
				'id'          	=> "mb_footer_column3",
				'type'        	=> 'select',
				'options'     	=> array(
					'4-4-4' => '33% / 33% / 33%',
                    '3-3-6' => '25% / 25% / 50%',
                    '3-6-3' => '25% / 50% / 25%',
                    '6-3-3' => '50% / 25% / 25%',
					'5-5-2' => '42% / 42% / 16%',
					'4-5-3' => '33% / 42% / 25%',
				),
				'multiple'    	=> false,
				'std'  			=> '5-5-2',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes'),
						array('mb_footer_column','=','3')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Footer Column Layout', 'ewebot' ),
				'id'          	=> "mb_footer_column5",
				'type'        	=> 'select',
				'options'     	=> array(
                    '2-3-2-2-3' => '16% / 25% / 16% / 16% / 25%',
                    '3-2-2-2-3' => '25% / 16% / 16% / 16% / 25%',
                    '3-2-3-2-2' => '25% / 16% / 26% / 16% / 16%',
                    '3-2-3-3-2' => '25% / 16% / 16% / 25% / 16%',
				),
				'multiple'    	=> false,
				'std'  			=> '2-3-2-2-3',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes'),
						array('mb_footer_column','=','5')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Footer Title Text Align', 'ewebot' ),
				'id'          	=> "mb_footer_align",
				'type'        	=> 'select',
				'options'     	=> array(
					'left' 	 => 'Left',
                    'center' => 'Center',
                    'right'  => 'Right'
				),
				'multiple'    	=> false,
				'std'  			=> 'left',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Padding Top (px)', 'ewebot' ),
				'id'   			=> "mb_padding_top",
				'type' 			=> 'number',
				'min'  			=> 0,
				'step' 			=> 1,
				'std'  			=> 57,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Padding Bottom (px)', 'ewebot' ),
				'id'   			=> "mb_padding_bottom",
				'type' 			=> 'number',
				'min'  			=> 0,
				'step' 			=> 1,
				'std'  			=> 57,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Padding Left (px)', 'ewebot' ),
				'id'   			=> "mb_padding_left",
				'type' 			=> 'number',
				'min'  			=> 0,
				'step' 			=> 1,
				'std'  			=> 0,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Padding Right (px)', 'ewebot' ),
				'id'   			=> "mb_padding_right",
				'type' 			=> 'number',
				'min'  			=> 0,
				'step' 			=> 1,
				'std'  			=> 0,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'id'   			=> 'mb_footer_full_width',
				'name' 			=> esc_html__( 'Full Width Footer', 'ewebot' ),
				'type' 			=> 'select_advanced',
				'options'  		=> array(
                    'default'        => esc_html__( 'Default', 'ewebot' ),
                    'strech_footer'  => esc_html__( 'Strech Footer', 'ewebot' ),
                    'strech_content' => esc_html__( 'Strech Footer and Content', 'ewebot' ),
				),
				'multiple' 		=> false,
				'std'  			=> 'default',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Background Size', 'ewebot' ),
				'id'       		=> 'mb_footer_bg_size',
				'type'     		=> 'select_advanced',
				'options'  		=> array(
					'inherit' 		=> esc_html__( 'inherit', 'ewebot' ),
					'cover' 		=> esc_html__( 'cover', 'ewebot' ),
					'contain' 		=> esc_html__( 'contain', 'ewebot' )
				),
				'multiple' 		=> false,
				'std'  			=> 'cover',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Background Color', 'ewebot' ),
				'id'   			=> "mb_footer_bg_color",
				'type' 			=> 'color',
				'std'  			=> '#191a1c',
				'js_options' 	=> array(
					'defaultColor' => '#191a1c',
				),
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Footer Text Color', 'ewebot' ),
				'id'   			=> "mb_footer_text_color",
				'type' 			=> 'color',
				'std'  			=> '#ffffff',
				'js_options' 	=> array(
					'defaultColor' => '#ffffff',
				),
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Footer Heading Color', 'ewebot' ),
				'id'   			=> "mb_footer_heading_color",
				'type' 			=> 'color',
				'std'  			=> '#9da1a5',
				'js_options' 	=> array(
					'defaultColor' => '#9da1a5',
				),
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Footer Background Image', 'ewebot' ),
				'id'            => "mb_footer_bg_image",
				'type'          => 'file_advanced',
				'max_file_uploads' => 1,
				'mime_type'     => 'image',
				'attributes' 	=> array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Background Repeat', 'ewebot' ),
				'id'       		=> 'mb_footer_bg_repeat',
				'type'     		=> 'select_advanced',
				'options'  		=> array(
					'no-repeat' 	=> esc_html__( 'no-repeat', 'ewebot' ),
					'repeat' 		=> esc_html__( 'repeat', 'ewebot' ),
					'repeat-x' 		=> esc_html__( 'repeat-x', 'ewebot' ),
					'repeat-y' 		=> esc_html__( 'repeat-y', 'ewebot' ),
					'inherit' 		=> esc_html__( 'inherit', 'ewebot' ),
				),
				'multiple' 		=> false,
				'std'  			=> 'repeat',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Background Size', 'ewebot' ),
				'id'       		=> 'mb_footer_bg_size',
				'type'     		=> 'select_advanced',
				'options'  		=> array(
					'inherit' 		=> esc_html__( 'inherit', 'ewebot' ),
					'cover' 		=> esc_html__( 'cover', 'ewebot' ),
					'contain' 		=> esc_html__( 'contain', 'ewebot' )
				),
				'multiple' 		=> false,
				'std'  			=> 'cover',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Background Attachment', 'ewebot' ),
				'id'       		=> 'mb_footer_attachment',
				'type'     		=> 'select_advanced',
				'options'  		=> array(
					'fixed'   		=> esc_html__( 'fixed', 'ewebot' ),
					'scroll' 		=> esc_html__( 'scroll', 'ewebot' ),
					'inherit' 		=> esc_html__( 'inherit', 'ewebot' )
				),
				'multiple' 		=> false,
				'std'  			=> 'scroll',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Background Position', 'ewebot' ),
				'id'       		=> 'mb_footer_bg_position',
				'type'     		=> 'select_advanced',
				'options'  		=> array(
					'left top' 		=> esc_html__( 'left top', 'ewebot' ),
					'left center' 	=> esc_html__( 'left center', 'ewebot' ),
					'left bottom' 	=> esc_html__( 'left bottom', 'ewebot' ),
					'center top' 	=> esc_html__( 'center top', 'ewebot' ),
					'center center' => esc_html__( 'center center', 'ewebot' ),
					'center bottom' => esc_html__( 'center bottom', 'ewebot' ),
					'right top' 	=> esc_html__( 'right top', 'ewebot' ),
					'right center' 	=> esc_html__( 'right center', 'ewebot' ),
					'right bottom' 	=> esc_html__( 'right bottom', 'ewebot' ),
				),
				'multiple' 		=> false,
				'std'  			=> 'center center',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'id'   			=> 'mb_footer_top_border',
				'name' 			=> esc_html__( 'Set Footer Top Border?', 'ewebot' ),
				'type' 			=> 'checkbox',
				'std'  			=> 1,
				'attributes' 	=>  array(
					'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Footer Border Color', 'ewebot' ),
				'id'   			=> "mb_footer_top_border_color",
				'type' 			=> 'color',
				'std'  			=> '',
				'js_options' 	=> array(
					'defaultColor' 	=> '#ffffff',
				),
				'attributes' 	=>  array(
					'data-dependency' => array( array(
						array('mb_footer_top_border','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Footer Border Opacity', 'ewebot' ),
				'id'   			=> "mb_footer_top_border_color_opacity",
				'type' 			=> 'number',
				'std'  			=> 0.1,
				'min'  			=> 0,
				'max'  			=> 1,
				'step' 			=> 0.01,
				'attributes' 	=>  array(
					'data-dependency' => array( array(
						array('mb_footer_top_border','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'id'   			=> 'mb_copyright_switch',
				'name' 			=> esc_html__( 'Show Copyright', 'ewebot' ),
				'type' 			=> 'checkbox',
				'std'  			=> 1,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Copyright Editor', 'ewebot' ),
				'id'   			=> "mb_copyright_editor",
				'type' 			=> 'textarea',
				'cols' 			=> 20,
				'rows' 			=> 3,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_copyright_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Copyright Title Text Align', 'ewebot' ),
				'id'       		=> 'mb_copyright_align',
				'type'     		=> 'select',
				'options'  		=> array(
					'left'   => esc_html__( 'left', 'ewebot' ),
					'center' => esc_html__( 'center', 'ewebot' ),
					'right'  => esc_html__( 'right', 'ewebot' ),
				),
				'multiple' 		=> false,
				'std'  			=> 'left',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_copyright_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Copyright Padding Top (px)', 'ewebot' ),
				'id'   			=> "mb_copyright_padding_top",
				'type' 			=> 'number',
				'min'  			=> 0,
				'step' 			=> 1,
				'std'  			=> 20,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_copyright_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Copyright Padding Bottom (px)', 'ewebot' ),
				'id'   			=> "mb_copyright_padding_bottom",
				'type' 			=> 'number',
				'min'  			=> 0,
				'step' 			=> 1,
				'std'  			=> 20,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_copyright_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Copyright Padding Left (px)', 'ewebot' ),
				'id'   			=> "mb_copyright_padding_left",
				'type' 			=> 'number',
				'min'  			=> 0,
				'step' 			=> 1,
				'std'  			=> 0,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_copyright_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Copyright Padding Right (px)', 'ewebot' ),
				'id'   			=> "mb_copyright_padding_right",
				'type' 			=> 'number',
				'min'  			=> 0,
				'step' 			=> 1,
				'std'  			=> 0,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_copyright_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Copyright Background Color', 'ewebot' ),
				'id'   			=> "mb_copyright_bg_color",
				'type' 			=> 'color',
				'std'  			=> '#191a1c',
				'js_options' 	=> array(
					'defaultColor' => '#191a1c',
				),
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_copyright_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Copyright Text Color', 'ewebot' ),
				'id'   			=> "mb_copyright_text_color",
				'type' 			=> 'color',
				'std'  			=> '#9da1a5',
				'js_options' 	=> array(
					'defaultColor' => '#9da1a5',
				),
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
				    	array('mb_copyright_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'id'   			=> 'mb_copyright_top_border',
				'name' 			=> esc_html__( 'Set Copyright Top Border?', 'ewebot' ),
				'type' 			=> 'checkbox',
				'std'  			=> 1,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
				    	array('mb_copyright_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Copyright Border Color', 'ewebot' ),
				'id'   			=> "mb_copyright_top_border_color",
				'type' 			=> 'color',
				'std'  			=> '',
				'js_options' 	=> array(
					'defaultColor' 	=> '',
				),
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
				    	array('mb_copyright_switch','=',true),
						array('mb_footer_switch','=','yes'),
						array('mb_copyright_top_border','=',true)
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Copyright Border Opacity', 'ewebot' ),
				'id'   			=> "mb_copyright_top_border_color_opacity",
				'type' 			=> 'number',
				'std'  			=> 1,
				'min'  			=> 0,
				'max'  			=> 1,
				'step' 			=> 0.01,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
				    	array('mb_copyright_switch','=',true),
						array('mb_footer_switch','=','yes'),
						array('mb_copyright_top_border','=',true)
					)),
				),
			),

			//prefooter
			array(
				'id'   			=> 'mb_pre_footer_switch',
				'name' 			=> esc_html__( 'Show Pre Footer Area', 'ewebot' ),
				'type' 			=> 'checkbox',
				'std'  			=> 0,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Pre Footer Editor', 'ewebot' ),
				'id'   			=> "mb_pre_footer_editor",
				'type' 			=> 'textarea',
				'cols' 			=> 20,
				'rows' 			=> 3,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_pre_footer_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Pre Footer Title Text Align', 'ewebot' ),
				'id'       		=> 'mb_pre_footer_align',
				'type'     		=> 'select',
				'options'  		=> array(
					'left'   => esc_html__( 'left', 'ewebot' ),
					'center' => esc_html__( 'center', 'ewebot' ),
					'right'  => esc_html__( 'right', 'ewebot' ),
				),
				'multiple' 		=> false,
				'std'  			=> 'left',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_pre_footer_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Pre Footer Padding Top (px)', 'ewebot' ),
				'id'   			=> "mb_pre_footer_padding_top",
				'type' 			=> 'number',
				'min'  			=> 0,
				'step' 			=> 1,
				'std'  			=> 20,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_pre_footer_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Pre Footer Padding Bottom (px)', 'ewebot' ),
				'id'   			=> "mb_pre_footer_padding_bottom",
				'type' 			=> 'number',
				'min'  			=> 0,
				'step' 			=> 1,
				'std'  			=> 20,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_pre_footer_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Pre Footer Padding Left (px)', 'ewebot' ),
				'id'   			=> "mb_pre_footer_padding_left",
				'type' 			=> 'number',
				'min'  			=> 0,
				'step' 			=> 1,
				'std'  			=> 0,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_pre_footer_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Pre Footer Padding Right (px)', 'ewebot' ),
				'id'   			=> "mb_pre_footer_padding_right",
				'type' 			=> 'number',
				'min'  			=> 0,
				'step' 			=> 1,
				'std'  			=> 0,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_pre_footer_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),


			array(
				'name' 			=> esc_html__( 'Background Color', 'ewebot' ),
				'id'   			=> "mb_pre_footer_bg_color",
				'type' 			=> 'color',
				'std'  			=> '#191a1c',
				'js_options' 	=> array(
					'defaultColor' => '#191a1c',
				),
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_pre_footer_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Text Color', 'ewebot' ),
				'id'   			=> "mb_pre_footer_text_color",
				'type' 			=> 'color',
				'std'  			=> '#9da1a5',
				'js_options' 	=> array(
					'defaultColor' => '#9da1a5',
				),
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_pre_footer_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),


			array(
				'id'   			=> 'mb_pre_footer_bottom_border',
				'name' 			=> esc_html__( 'Set Pre Footer Bottom Border?', 'ewebot' ),
				'type' 			=> 'checkbox',
				'std'  			=> 1,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
				    	array('mb_pre_footer_switch','=',true),
						array('mb_footer_switch','=','yes')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Pre Footer Border Color', 'ewebot' ),
				'id'   			=> "mb_pre_footer_bottom_border_color",
				'type' 			=> 'color',
				'std'  			=> '',
				'js_options' 	=> array(
					'defaultColor'   => '',
				),
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
				    	array('mb_pre_footer_switch','=',true),
						array('mb_footer_switch','=','yes'),
						array('mb_pre_footer_bottom_border','=',true)
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Pre Footer Border Opacity', 'ewebot' ),
				'id'   			=> "mb_pre_footer_bottom_border_color_opacity",
				'type' 			=> 'number',
				'std'  			=> 1,
				'min'  			=> 0,
				'max'  			=> 1,
				'step' 			=> 0.01,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
				    	array('mb_pre_footer_switch','=',true),
						array('mb_footer_switch','=','yes'),
						array('mb_pre_footer_bottom_border','=',true)
					)),
				),
			),
        ),
     );
    return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'gt3_preloader_meta_boxes' );
function gt3_preloader_meta_boxes( $meta_boxes ) {
    $meta_boxes[] = array(
        'title'      	=> esc_html__( 'Preloader Options', 'ewebot' ),
        'post_types' 	=> array( 'page', 'proof_gallery', 'portfolio' ),
        'context' 		=> 'advanced',
        'fields'     	=> array(
        	array(
				'name' 			=> esc_html__( 'Preloader', 'ewebot' ),
				'id'          	=> "mb_preloader",
				'type'        	=> 'select',
				'options'     	=> array(
					'default' => esc_html__( 'default', 'ewebot' ),
					'custom'  => esc_html__( 'custom', 'ewebot' ),
					'none' 	  => esc_html__( 'none', 'ewebot' ),
				),
				'multiple'    	=> false,
				'std'  			=> 'default',
			),
        	array(
				'name' 			=> esc_html__( 'Preloader type', 'ewebot' ),
				'id'          	=> "mb_preloader_type",
				'type'        	=> 'select',
				'options'     	=> array(
					'linear' 		=> esc_html__( 'Linear', 'ewebot' ),
					'circle' 		=> esc_html__( 'Circle', 'ewebot' ),
					'theme'         => esc_html__( 'Theme', 'ewebot' ),
				),
				'multiple'    	=> false,
				'circle'		=> 'default',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_preloader','=','custom')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Preloader Background', 'ewebot' ),
				'id'   			=> "mb_preloader_background",
				'type' 			=> 'color',
				'std'  			=> '#191a1c',
				'js_options' 	=> array(
					'defaultColor'  => '#191a1c',
				),
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_preloader','=','custom')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Preloader Stroke Background Color', 'ewebot' ),
				'id'   			=> "mb_preloader_item_color",
				'type' 			=> 'color',
				'std'  			=> '#ffffff',
				'js_options' 	=> array(
					'defaultColor'  => '#ffffff',
				),
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_preloader','=','custom'),
					    array('mb_preloader_type','!=','theme'),
				    )),
				),
			),
			array(
				'name' 			=> esc_html__( 'Preloader Stroke Foreground Color', 'ewebot' ),
				'id'   			=> "mb_preloader_item_color2",
				'type' 			=> 'color',
				'std'  			=> '#435bb2',
				'js_options' 	=> array(
					'defaultColor'  => '#435bb2',
				),
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_preloader','=','custom')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Preloader Circle Width in px (Diameter)', 'ewebot' ),
				'id'   			=> "mb_preloader_item_width",
				'type' 			=> 'number',
				'std'  			=> 120,
				'min'  			=> 0,
				'step' 			=> 1,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_preloader','=','custom'),
						array('mb_preloader_type','!=','linear'),
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Preloader Circle Stroke Width', 'ewebot' ),
				'id'   			=> "mb_preloader_item_stroke",
				'type' 			=> 'number',
				'std'  			=> 2,
				'min'  			=> 0,
				'max'  			=> 1000,
				'step' 			=> 1,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_preloader','=','custom'),
					    array('mb_preloader_type','!=','linear'),
				    )),
				),
			),
			array(
				'name' 			=> esc_html__( 'Preloader Logo', 'ewebot' ),
				'id' 			=> "mb_preloader_item_logo",
				'type' 			=> 'image_advanced',
				'size'			=> 'full',
				'max_file_uploads' => 1,
				'max_status' 	=> true,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_preloader','=','custom')
					)),
				),
			),
			array(
				'name' 			=> esc_html__( 'Preloader Logo Width in px', 'ewebot' ),
				'id'   			=> "mb_preloader_item_logo_width",
				'type' 			=> 'number',
				'std'  			=> 45,
				'min'  			=> 0,
				'max'  			=> 1000,
				'step' 			=> 1,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_preloader','=','custom'),
					    array('mb_preloader_type','!=','linear'),
				    )),
				),
			),
			array(
				'id'   			=> 'mb_preloader_full',
				'name' 			=> esc_html__( 'Preloader Fullscreen', 'ewebot' ),
				'type' 			=> 'checkbox',
				'std'  			=> 1,
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_preloader','=','custom')
					)),
				),
			),
        )
    );
    return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'gt3_portfolio_meta_boxes' );
function gt3_portfolio_meta_boxes( $meta_boxes ) {
	$meta_boxes[] = array(
		'title'      	=> esc_html__( 'Portfolio Options', 'ewebot' ),
		'post_types' 	=> array( 'portfolio' ),
		'context' 		=> 'advanced',
		'fields'     	=> array(
			array(
				'name'     		=> esc_html__( 'Portfolio Post Title', 'ewebot' ),
				'id'          	=> "mb_portfolio_title_conditional",
				'type'        	=> 'select',
				'options'     	=> array(
					'default' 		=> esc_html__( 'default', 'ewebot' ),
					'yes' 			=> esc_html__( 'yes', 'ewebot' ),
					'no' 			=> esc_html__( 'no', 'ewebot' ),
				),
				'multiple'    	=> false,
				'std'         	=> 'default',
			),
		)
	);
	return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'gt3_shortcode_meta_boxes' );
function gt3_shortcode_meta_boxes( $meta_boxes ) {
	$meta_boxes[] = array(
		'title'      	=> esc_html__( 'Shortcode Above Content', 'ewebot' ),
		'post_types' 	=> array( 'page' ),
		'context' 		=> 'advanced',
		'fields'     	=> array(
			array(
				'name' 			=> esc_html__( 'Shortcode', 'ewebot' ),
				'id'   			=> "mb_page_shortcode",
				'type' 			=> 'textarea',
				'cols' 			=> 20,
				'rows' 			=> 3
			),
		),
     );
    return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'gt3_bubbles_block_meta_boxes' );
function gt3_bubbles_block_meta_boxes( $meta_boxes ) {
	$meta_boxes[] = array(
		'title'      	=> esc_html__( 'Bubbles Options', 'ewebot' ),
		'post_types' 	=> array( 'page' , 'post', 'team', 'product', 'proof_gallery', 'portfolio' ),
		'context' 		=> 'advanced',
		'fields'     	=> array(
			array(
				'name' 			=> esc_html__( 'Bubbles', 'ewebot' ),
				'id'          	=> "mb_bubbles_block",
				'type'        	=> 'select',
				'options'     	=> array(
					'default' => esc_html__( 'default', 'ewebot' ),
					'show' 	  => esc_html__( 'Show', 'ewebot' ),
					'hide'    => esc_html__( 'Hide', 'ewebot' ),
				),
				'multiple'    	=> false,
				'std'  			=> 'default',
			),
		)
	);
	return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'gt3_single_product_meta_boxes' );
function gt3_single_product_meta_boxes( $meta_boxes ) {

    $meta_boxes[] = array(
        'title'      	=> esc_html__( 'Single Product Settings', 'ewebot' ),
        'post_types' 	=> array( 'product' ),
        'context' 		=> 'advanced',
        'fields'     	=> array(
        	array(
				'name' 			=> esc_html__( 'Single Product Page Settings', 'ewebot' ),
				'id'          	=> "mb_single_product",
				'type'        	=> 'select',
				'options'     	=> array(
					'default' => esc_html__( 'default', 'ewebot' ),
					'custom'  => esc_html__( 'Custom', 'ewebot' ),
				),
				'multiple'    	=> false,
				'std'  			=> 'default',
			),

			array(
				'name' 			=> esc_html__( 'Product Page Layout', 'ewebot' ),
				'id'          	=> "mb_product_container",
				'type'        	=> 'select',
				'options'     	=> array(
					'container' 	=> esc_html__( 'Container', 'ewebot' ),
					'full_width' 	=> esc_html__( 'Full Width', 'ewebot' ),
				),
				'multiple'    	=> false,
				'std'  			=> 'container',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
						array('mb_single_product','=','custom')
					)),
				),
			),

            // Thumbnails Layout Settings
            array(
                'name' 			=> esc_html__( 'Thumbnails Layout', 'ewebot' ),
                'id'          	=> "mb_thumbnails_layout",
                'type'        	=> 'select',
                'options'     	=> array(
                    'horizontal' 	=> esc_html__( 'Thumbnails Bottom', 'ewebot' ),
                    'vertical' 		=> esc_html__( 'Thumbnails Left', 'ewebot' ),
                    'thumb_grid' 	=> esc_html__( 'Thumbnails Grid', 'ewebot' ),
                    'thumb_vertical'=> esc_html__( 'Thumbnails Vertical Grid', 'ewebot' ),
                ),
                'multiple'    	=> false,
                'std'  			=> 'horizontal',
                'attributes' 	=>  array(
                    'data-dependency' => array( array(
                        array('mb_single_product','=','custom')
                    )),
                ),
            ),
            array(
                'id'   			=> 'mb_sticky_thumb',
                'name' 			=> esc_html__( 'Sticky Thumbnails', 'ewebot' ),
                'type' 			=> 'checkbox',
                'attributes' 	=>  array(
                    'data-dependency' => array( array(
                        array('mb_single_product','=','custom'),
                        array('mb_thumbnails_layout','!=','thumb_vertical'),
                    )),
                ),
            ),
        	array(
				'name' 			=> esc_html__( 'Size Guide for this product', 'ewebot' ),
				'id'          	=> "mb_img_size_guide",
				'type'        	=> 'select',
				'options'     	=> array(
					'default' => esc_html__( 'default', 'ewebot' ),
					'custom'  => esc_html__( 'Custom', 'ewebot' ),
					'none'    => esc_html__( 'None', 'ewebot' ),
				),
				'multiple'    	=> false,
				'std'  			=> 'default',
			),
			array(
				'id'   			=> 'mb_size_guide',
				'name' 			=> esc_html__( 'Size guide Popup Image', 'ewebot' ),
				'type' 			=> 'image_advanced',
				'attributes' 	=>  array(
				    'data-dependency' => array( array(
				    	array('mb_img_size_guide','=','custom')
					)),
				),
			),
            array(
                'name'     => esc_html__('Image Size for Masonry Layout', 'ewebot'),
                'id'       => "mb_img_size_masonry",
                'type'     => 'select',
                'options'  => array(
                    'small_h_rect' => esc_html__('Small Horizontal Rectangle', 'ewebot'),
                    'large_h_rect' => esc_html__('Large Horizontal Rectangle', 'ewebot'),
                    'large_v_rect' => esc_html__('Large Vertical Rectangle', 'ewebot'),
                    'large_rect'   => esc_html__('Large 2x Rectangle', 'ewebot'),
                ),
                'multiple' => false,
                'std'      => 'small_h_rect',
            ),
        )
    );
    return $meta_boxes;
}

