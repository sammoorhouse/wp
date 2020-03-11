<?php
function betterdocs_settings_args(){
    $query['autofocus[panel]'] = 'betterdocs_customize_options';
    $query['return'] = admin_url( 'edit.php?post_type=docs' );
    $builtin_doc_page = BetterDocs_DB::get_settings('builtin_doc_page');
    $docs_slug = BetterDocs_DB::get_settings('docs_slug');
    if($builtin_doc_page == 1 && $docs_slug){
        $query['url'] = site_url( '/'.$docs_slug );
    }
    $customizer_link = add_query_arg( $query, admin_url( 'customize.php' ) );
    return apply_filters('betterdocs_settings_tab', array(
        'general' => array(
            'title' => __( 'General', 'betterdocs' ),
            'priority' => 10,
            'button_text' => __( 'Save Settings' ),
            'sections' => apply_filters('betterdocs_general_settings_sections', array(
                'general_settings' => apply_filters('betterdocs_general_settings', array(
                    'title' => __( 'General Settings', 'betterdocs' ),
                    'priority' => 10,
                    'fields' => array(
                        'builtin_doc_page' => array(
                            'type'        => 'checkbox',
                            'label'       => __('Enable Built-in Documentation Page' , 'betterdocs'),
                            'default'     => 1,
                            'priority'    => 10,
                            'help'        => __('<strong>Note:</strong> if you disable built-in documentation page, you can use shortcode or page builder widgets to design your documentation page.' , 'betterdocs'),
                        ),
                        'docs_slug' => array(
                            'type'      => 'text',
                            'label'     => __('Documentation Page Slug' , 'betterdocs'),
                            'default'   => 'docs',
                            'priority'	=> 10
                        ),
                        'category_slug' => array(
                            'type'      => 'text',
                            'label'     => __('Custom Category Slug' , 'betterdocs'),
                            'default'   => 'docs-category',
                            'priority'	=> 10
                        ),
                        'tag_slug' => array(
                            'type'      => 'text',
                            'label'     => __('Custom Tag Slug' , 'betterdocs'),
                            'default'   => 'docs-tag',
                            'priority'	=> 10
                        ),
                    ),
                )),
                
            )),
        ),
        'layout' => array(
            'title' => __( 'Layout', 'betterdocs' ),
            'priority' => 10,
            'button_text' => __( 'Save Settings' ),
            'sections' => apply_filters('betterdocs_layout_settings_sections', array(
                
                'layout_inner_tab' => array(
                    'title' => __( 'Layout Tab' ),
                    'tabs' => array(
                        'documentation_page' => apply_filters('betterdocs_layout_documentation_page_settings', array(
                            'title' => __( 'Documentation Page', 'betterdocs' ),
                            'priority' => 10,
                            'fields' => array(
                                'doc_page' => array(
                                    'type'        => 'title',
                                    'label'       => __('Documentation Page' , 'betterdocs'),
                                    'priority'    => 10,
                                ),
                                'live_search' => array(
                                    'type'        => 'checkbox',
                                    'label'       => __('Enable Live Search' , 'betterdocs'),
                                    'default'     => 1,
                                    'priority'    => 10,
                                ),
                                'search_result_image' => array(
                                    'type'        => 'checkbox',
                                    'label'       => __('Search Result Image' , 'betterdocs'),
                                    'default'     => 1,
                                    'priority'    => 10,
                                ),
                                'masonry_layout' => array(
                                    'type'        => 'checkbox',
                                    'label'       => __('Enable Masonry' , 'betterdocs'),
                                    'default'     => 1,
                                    'priority'    => 10,
                                ),
                                'nested_subcategory' => array(
                                    'type'        => 'checkbox',
                                    'label'       => __('Nested Subcategory' , 'betterdocs'),
                                    'default'     => '',
                                    'priority'    => 10,
                                ),
                                'column_number' => array(
                                    'type'      => 'number',
                                    'label'     => __('Number of Columns' , 'betterdocs'),
                                    'default'   => 3,
                                    'priority'	=> 10
                                ),
                                'posts_number' => array(
                                    'type'      => 'number',
                                    'label'     => __('Number of Posts' , 'betterdocs'),
                                    'default'   => 10,
                                    'priority'	=> 10
                                ),
                                'post_count' => array(
                                    'type'        => 'checkbox',
                                    'label'       => __('Enable Post Count' , 'betterdocs'),
                                    'default'     => 1,
                                    'priority'    => 10,
                                ),
                                'exploremore_btn' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Enable Explore More Button' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10,
                                    'dependency'  => array(
                                        1 => array(
                                            'fields' => array( 'exploremore_btn_txt' )
                                        )
                                    ),
                                    'hide'  => array(
                                        0 => array(
                                            'fields' => array( 'exploremore_btn_txt' )
                                        )
                                    )
                                ),
                                'exploremore_btn_txt' => array(
                                    'type'      => 'text',
                                    'label'     => __('Button Text' , 'betterdocs'),
                                    'default'   => 'Explore More',
                                    'priority'	=> 10
                                ),
                            ),
                        )),
                        'single_doc' => apply_filters('betterdocs_layout_single_doc_settings', array(
                            'title' => __( 'Single Doc', 'betterdocs' ),
                            'priority' => 10,
                            'fields' => array(
                                'doc_single' => array(
                                    'type'        => 'title',
                                    'label'       => __('Single Doc' , 'betterdocs'),
                                    'priority'    => 10,
                                ),
                                'enable_toc' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Enable Table of Content (TOC)' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10,
                                    'dependency'  => array(
                                        1 => array(
                                            'fields' => array( 'enable_sticky_toc', 'supported_heading_tag' )
                                        )
                                    ),
                                    'hide'  => array(
                                        0 => array(
                                            'fields' => array( 'enable_sticky_toc', 'supported_heading_tag' )
                                        )
                                    )
                                ),
                                'enable_sticky_toc' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Enable Sticky TOC' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10
                                ),
                                'supported_heading_tag' => array(
                                    'label' => __( 'TOC Supported Heading Tag', 'betterdocs' ),
                                    'type'     => 'multi_checkbox',
                                    'priority' => 10,
                                    'default'  => array(1,2,3,4,5,6),
                                    'options'  => array(
                                        '1' => 'h1',
                                        '2' => 'h2',
                                        '3' => 'h3',
                                        '4' => 'h4',
                                        '5' => 'h5',
                                        '6' => 'h6'
                                    ),
                                ),
                                'title_link_ctc' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Title Link Copy To Clipboard' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10
                                ),
                                'enable_breadcrumb' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Enable Breadcrumb' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10,
                                    'dependency'  => array(
                                        1 => array(
                                            'fields' => array( 'breadcrumb_doc_title' )
                                        )
                                    ),
                                    'hide'  => array(
                                        0 => array(
                                            'fields' => array( 'breadcrumb_doc_title' )
                                        )
                                    )
                                ),
                                'breadcrumb_doc_title' => array(
                                    'type'      => 'text',
                                    'label'     => __('Breadcrumb Page Title' , 'betterdocs'),
                                    'default'   => 'Docs',
                                    'priority'	=> 10,
                                ),
                                'enable_breadcrumb_category' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Enable Category on Breadcrumb' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10
                                ),
                                'enable_breadcrumb_title' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Enable Title on Breadcrumb' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10
                                ),
                                'enable_sidebar_cat_list' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Enable Sidebar Category List' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10
                                ),
                                'enable_print_icon' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Enable Print Icon' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10
                                ),
                                'enable_tags' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Enable Tags' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10
                                ),
                                'email_feedback' => array(
                                    'type'        => 'checkbox',
                                    'label'       => __('Enable Email Feedback' , 'betterdocs'),
                                    'default'     => 1,
                                    'priority'    => 10,
                                    'dependency'  => array(
                                        1 => array(
                                            'fields' => array( 'email_address' )
                                        )
                                    ),
                                    'hide'  => array(
                                        0 => array(
                                            'fields' => array( 'email_address' )
                                        )
                                    )
                                ),
                                'email_address' => array(
                                    'type'      => 'text',
                                    'label'     => __('Email Address' , 'betterdocs'),
                                    'default'   => get_option('admin_email'),
                                    'priority'	=> 10,
                                    'description' => __('The email address where the feedback form should sent' , 'betterdocs'),
                                ),
                                'show_last_update_time' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Show Last Update Time' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10
                                ),
                                'enable_navigation' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Enable Navigation' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10
                                ),
                                'enable_comment' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Enable Comment' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10
                                ),
                                'enable_credit' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Enable Credit' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10
                                ),
                            ),
                        )),
                        'archive_page' => apply_filters('betterdocs_layout_archive_page_settings', array(
                            'title' => __( 'Archive Page', 'betterdocs' ),
                            'priority' => 10,
                            'fields' => array(
                                'archive_page_title' => array(
                                    'type'        => 'title',
                                    'label'       => __('Archive Page' , 'betterdocs'),
                                    'priority'    => 10,
                                ),
                                'enable_archive_sidebar' => array(
                                    'type'      => 'checkbox',
                                    'label'     => __('Enable Sidebar Category List' , 'betterdocs'),
                                    'default'   => 1,
                                    'priority'	=> 10,
                                )
                            ),
                        )),
                    )
                )
                
            )),
        ),
        'design' => array(
            'title' => __( 'Design', 'betterdocs' ),
            'priority' => 10,
            'sections' => apply_filters('betterdocs_design_settings_sections', array(
                'design_settings' => apply_filters('betterdocs_design_settings', array(
                    'title' => __( 'Documentation Page', 'betterdocs' ),
                    'priority' => 10,
                    'fields' => array(
                        'customizer_link' => array(
                            'type'      => 'card',
                            'label'     => __('Customize BetterDocs','betterdocs'),
                            'url'   => esc_url($customizer_link),
                            'priority'	=> 10
                        ),
                    ),
                )), 
            )),
        ),
        'shortcodes' => array(
            'title' => __( 'Shortcodes', 'betterdocs' ),
            'priority' => 10,
            'sections' => apply_filters('betterdocs_shortcodes_settings_sections', array(
                'shortcodes_settings' => apply_filters('betterdocs_shortcodes_settings', array(
                    'title' => __( 'Shortcodes', 'betterdocs' ),
                    'priority' => 10,
                    'fields' => array(
                        'category_grid' => array(
                            'type'      => 'text',
                            'label'     => __('Category Grid' , 'betterdocs'),
                            'default'   => '[betterdocs_category_grid]',
                            'readonly'	=> true,
                            'priority'	=> 10,
                            'help'        => __('<strong>You can use:</strong> [betterdocs_category_grid post_counter="true" icon="true" masonry="true" column="3" posts_per_grid="5"]' , 'betterdocs'),
                        ),
                        'category_box' => array(
                            'type'      => 'text',
                            'label'     => __('Category Box' , 'betterdocs'),
                            'default'   => '[betterdocs_category_box]',
                            'readonly'	=> true,
                            'priority'	=> 10,
                            'help'        => __('<strong>You can use:</strong> [betterdocs_category_box column="3"]' , 'betterdocs'),
                        ),
                        'search_form' => array(
                            'type'      => 'text',
                            'label'     => __('Search Form' , 'betterdocs'),
                            'default'   => '[betterdocs_search_form]',
                            'readonly'	=> true,
                            'priority'	=> 10
                        ),
                        'feedback_form' => array(
                            'type'      => 'text',
                            'label'     => __('Feedback Form' , 'betterdocs'),
                            'default'   => '[betterdocs_feedback_form]',
                            'readonly'	=> true,
                            'priority'	=> 10
                        ),
                    ),
                )), 
            )),
        ),
        'betterdocs_advanced_settings' => array(
            'title'       => __( 'Advanced Settings', 'betterdocs-pro' ),
            'priority'    => 20,
            'button_text' => __( 'Save Settings' ),
            'sections' => apply_filters( 'betterdocs_pro_advanced_settings_sections', array(
                'role_management_section' => array(
                    'title' => __('Role Management', 'betterdocs-pro'),
                    'priority'    => 0,
                    'fields' => array(
                        'rms_title' => array(
                            'type'        => 'title',
                            'label'       => __('Role Management', 'betterdocs-pro'),
                            'priority'    => 0,
                        ),
                        'article_roles' => array(
                            'type'        => 'select',
                            'label'       => __('Who Can Write Articles?', 'betterdocs-pro'),
                            'priority'    => 1,
                            'multiple' => true,
                            'disable' => true,
                            'default' => 'administrator',
                            'options' => BetterDocs_Settings::get_roles()
                        ),
                        'settings_roles' => array(
                            'type'        => 'select',
                            'label'       => __('Who Can Edit Settings?', 'betterdocs-pro'),
                            'priority'    => 1,
                            'multiple' => true,
                            'disable' => true,
                            'default' => 'administrator',
                            'options' => BetterDocs_Settings::get_roles()
                        ),
                        'analytics_roles' => array(
                            'type'        => 'select',
                            'label'       => __('Who Can Check Analytics?', 'betterdocs-pro'),
                            'priority'    => 1,
                            'multiple' => true,
                            'disable' => true,
                            'default' => 'administrator',
                            'options' => BetterDocs_Settings::get_roles()
                        ),
                    )
                )
            ) )
        )
    ));
}