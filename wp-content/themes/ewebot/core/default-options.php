<?php
function gt3_get_default_option(){
    $option = get_option( 'ewebot_default_options' );
    if (true || empty($option)) {
        $option = '{
    "redux-section": "4",
    "last_tab": "3",
    "responsive": "1",
    "page_comments": "1",
    "back_to_top": "1",
    "bubbles_block": "1",
    "team_slug": "",
    "portfolio_slug": "",
    "page_404_bg": {
        "url": "https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/09/404.png",
        "id": "1935",
        "height": "1261",
        "width": "1920",
        "thumbnail": "https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/09/404-150x150.png",
        "title": "404",
        "caption": "",
        "alt": "",
        "description": ""
    },
    "disable_right_click": "",
    ",custom_js": "",
    "header_custom_js": "",
    "preloader": "",
    "preloader_type": "circle",
    "preloader_background": "#191a1c",
    "preloader_item_color": "#ffffff",
    "preloader_item_color2": "#435bb2",
    "preloader_item_width": {
        "width": "140"
    },
    "preloader_item_stroke": {
        "width": "3"
    },
    "preloader_item_logo": {
        "url": "",
        "id": "",
        "height": "",
        "width": "",
        "thumbnail": "",
        "title": "",
        "caption": "",
        "alt": "",
        "description": ""
    },
    "preloader_item_logo_width": {
        "width": "45px",
        "units": "px"
    },
    "preloader_full": "1",
    "main_header_preset": "0",
    "404_header_preset": "1",
    "gt3_header_builder_id": {
        "all_item": {
            "layout": "all",
            "title": "All Item",
            "content": {
                "placebo": "placebo"
            }
        },
        "top_left": {
            "layout": "one-thirds",
            "title": "Top Left",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "top_center": {
            "layout": "one-thirds",
            "title": "Top Center",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "top_right": {
            "layout": "one-thirds",
            "title": "Top Right",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "middle_left": {
            "layout": "one-thirds clear-item",
            "title": "Middle Left",
            "has_settings": "1",
            "content": {
                "placebo": "placebo",
                "logo": {
                    "title": "gt3_flagLogo",
                    "has_settings": "1"
                }
            }
        },
        "middle_center": {
            "layout": "one-thirds",
            "title": "Middle Center",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "middle_right": {
            "layout": "one-thirds",
            "title": "Middle Right",
            "has_settings": "1",
            "content": {
                "placebo": "placebo",
                "menu": {
                    "title": "gt3_flagMenu",
                    "has_settings": "1"
                }
            }
        },
        "bottom_left": {
            "layout": "one-thirds clear-item",
            "title": "Bottom Left",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "bottom_center": {
            "layout": "one-thirds",
            "title": "Bottom Center",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "bottom_right": {
            "layout": "one-thirds",
            "title": "Bottom Right",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "all_item__tablet": {
            "layout": "all",
            "extra_class": "tablet",
            "title": "All Item",
            "content": {
                "placebo": "placebo"
            }
        },
        "top_left__tablet": {
            "layout": "one-thirds",
            "extra_class": "tablet",
            "title": "Top Left",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "top_center__tablet": {
            "layout": "one-thirds",
            "extra_class": "tablet",
            "title": "Top Center",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "top_right__tablet": {
            "layout": "one-thirds",
            "extra_class": "tablet",
            "title": "Top Right",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "middle_left__tablet": {
            "layout": "one-thirds clear-item",
            "extra_class": "tablet",
            "title": "Middle Left",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "middle_center__tablet": {
            "layout": "one-thirds",
            "extra_class": "tablet",
            "title": "Middle Center",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "middle_right__tablet": {
            "layout": "one-thirds",
            "extra_class": "tablet",
            "title": "Middle Right",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "bottom_left__tablet": {
            "layout": "one-thirds clear-item",
            "extra_class": "tablet",
            "title": "Bottom Left",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "bottom_center__tablet": {
            "layout": "one-thirds",
            "extra_class": "tablet",
            "title": "Bottom Center",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "bottom_right__tablet": {
            "layout": "one-thirds",
            "extra_class": "tablet",
            "title": "Bottom Right",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "all_item__mobile": {
            "layout": "all",
            "extra_class": "mobile",
            "title": "All Item",
            "content": {
                "placebo": "placebo"
            }
        },
        "top_left__mobile": {
            "layout": "one-thirds",
            "extra_class": "mobile",
            "title": "Top Left",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "top_center__mobile": {
            "layout": "one-thirds",
            "extra_class": "mobile",
            "title": "Top Center",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "top_right__mobile": {
            "layout": "one-thirds",
            "extra_class": "mobile",
            "title": "Top Right",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "middle_left__mobile": {
            "layout": "one-thirds clear-item",
            "extra_class": "mobile",
            "title": "Middle Left",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "middle_center__mobile": {
            "layout": "one-thirds",
            "extra_class": "mobile",
            "title": "Middle Center",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "middle_right__mobile": {
            "layout": "one-thirds",
            "extra_class": "mobile",
            "title": "Middle Right",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "bottom_left__mobile": {
            "layout": "one-thirds clear-item",
            "extra_class": "mobile",
            "title": "Bottom Left",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "bottom_center__mobile": {
            "layout": "one-thirds",
            "extra_class": "mobile",
            "title": "Bottom Center",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        },
        "bottom_right__mobile": {
            "layout": "one-thirds",
            "extra_class": "mobile",
            "title": "Bottom Right",
            "has_settings": "1",
            "content": {
                "placebo": "placebo"
            }
        }
    },
    "header_logo": "",
    "logo_height_custom": "1",
    "logo_height": {
        "height": "48"
    },
    "logo_max_height": "",
    "sticky_logo_height": {
        "height": ""
    },
    "logo_sticky": {},
    "logo_tablet": {},
    "logo_teblet_width": {},
    "logo_mobile": {},
    "logo_mobile_width": {
        "width": "149"
    },
    "menu_select": "main-menu",
    "menu_ative_top_line": "",
    "sub_menu_background": {
        "color": "#ffffff",
        "alpha": "1",
        "rgba": "rgba(255,255,255,1)"
    },
    "sub_menu_color": "#3b3663",
    "sub_menu_color_hover": "#f47514",
    "burger_sidebar_select": "sidebar_header-sidebar",
    "top_left-align": "left",
    "top_center-align": "center",
    "top_right-align": "right",
    "middle_left-align": "left",
    "middle_center-align": "left",
    "middle_right-align": "right",
    "bottom_left-align": "left",
    "bottom_center-align": "center",
    "bottom_right-align": "right",
    "top_left__tablet-align": "left",
    "top_center__tablet-align": "center",
    "top_right__tablet-align": "right",
    "middle_left__tablet-align": "left",
    "middle_center__tablet-align": "center",
    "middle_right__tablet-align": "right",
    "bottom_left__tablet-align": "left",
    "bottom_center__tablet-align": "center",
    "bottom_right__tablet-align": "right",
    "top_left__mobile-align": "left",
    "top_center__mobile-align": "center",
    "top_right__mobile-align": "right",
    "middle_left__mobile-align": "left",
    "middle_center__mobile-align": "center",
    "middle_right__mobile-align": "right",
    "bottom_left__mobile-align": "left",
    "bottom_center__mobile-align": "center",
    "bottom_right__mobile-align": "right",
    "text1_editor": "",
    "text2_editor": "",
    "text3_editor": "",
    "text4_editor": "",
    "text5_editor": "",
    "text6_editor": "",
    "delimiter1_height": {
        "height": "1em",
        "units": "em"
    },
    "delimiter1_border_color": {
        "color": "#ffffff",
        "alpha": "1",
        "rgba": "rgba(255,255,255,1)"
    },
    "delimiter2_height": {
        "height": "1em",
        "units": "em"
    },
    "delimiter2_border_color": {
        "color": "#ffffff",
        "alpha": "1",
        "rgba": "rgba(255,255,255,1)"
    },
    "delimiter3_height": {
        "height": "1em",
        "units": "em"
    },
    "delimiter3_border_color": {
        "color": "#ffffff",
        "alpha": "1",
        "rgba": "rgba(255,255,255,1)"
    },
    "delimiter4_height": {
        "height": "1em",
        "units": "em"
    },
    "delimiter4_border_color": {
        "color": "#ffffff",
        "alpha": "1",
        "rgba": "rgba(255,255,255,1)"
    },
    "delimiter5_height": {
        "height": "1em",
        "units": "em"
    },
    "delimiter5_border_color": {
        "color": "#ffffff",
        "alpha": "1",
        "rgba": "rgba(255,255,255,1)"
    },
    "delimiter6_height": {
        "height": "1em",
        "units": "em"
    },
    "delimiter6_border_color": {
        "color": "#ffffff",
        "alpha": "1",
        "rgba": "rgba(255,255,255,1)"
    },
    "side_top_background": {
        "color": "#ffffff",
        "alpha": "1",
        "rgba": "rgba(255,255,255,1)"
    },
    "side_top_background2": {
        "color": "",
        "alpha": "",
        "rgba": ""
    },
    "side_top_color": "#696687",
    "side_top_color_hover": "#5747e4",
    "side_top_height": {
        "height": "46"
    },
    "side_top_spacing": {
        "padding-right": "",
        "padding-left": ""
    },
    "side_top_border": "1",
    "side_top_border_color": {
        "color": "#696687",
        "alpha": "0.1",
        "rgba": "rgba(105,102,135,0.1)"
    },
    "side_top_border_radius": "",
    "side_top_sticky": "1",
    "side_top_background_sticky": {
        "color": "#ffffff",
        "alpha": "1",
        "rgba": "rgba(255,255,255,1)"
    },
    "side_top_color_sticky": "#222328",
    "side_top_color_hover_sticky": "#232325",
    "side_top_height_sticky": {
        "height": "58"
    },
    "side_top_spacing_sticky": {
        "padding-right": "",
        "padding-left": ""
    },
    "side_middle_background": {
        "color": "#ffffff",
        "alpha": "1",
        "rgba": "rgba(255,255,255,1)"
    },
    "side_middle_background2": {
        "color": "#ffffff",
        "alpha": "0",
        "rgba": "rgba(255,255,255,0)"
    },
    "side_middle_color": "#3b3663",
    "side_middle_color_hover": "#f47514",
    "side_middle_height": {
        "height": "108"
    },
    "side_middle_spacing": {
        "padding-right": "",
        "padding-left": ""
    },
    "side_middle_border": "",
    "side_middle_border_color": {
        "color": "#F3F4F4",
        "alpha": "1",
        "rgba": "rgba(243,244,244,1)"
    },
    "side_middle_border_radius": "",
    "side_middle_sticky": "1",
    "side_middle_background_sticky": {
        "color": "#ffffff",
        "alpha": "1",
        "rgba": "rgba(255,255,255,1)"
    },
    "side_middle_color_sticky": "#222328",
    "side_middle_color_hover_sticky": "#232325",
    "side_middle_height_sticky": {
        "height": "58"
    },
    "side_middle_spacing_sticky": {
        "padding-right": "",
        "padding-left": ""
    },
    "side_bottom_background": {
        "color": "#ffffff",
        "alpha": "1",
        "rgba": "rgba(255,255,255,1)"
    },
    "side_bottom_background2": {
        "color": "#ffffff",
        "alpha": "0",
        "rgba": "rgba(255,255,255,0)"
    },
    "side_bottom_color": "#232325",
    "side_bottom_color_hover": "#232325",
    "side_bottom_height": {
        "height": "100"
    },
    "side_bottom_spacing": {
        "padding-right": "",
        "padding-left": ""
    },
    "side_bottom_border": "",
    "side_bottom_border_color": {
        "color": "#F3F4F4",
        "alpha": "1",
        "rgba": "rgba(243,244,244,1)"
    },
    "side_bottom_border_radius": "",
    "side_bottom_sticky": "1",
    "side_bottom_background_sticky": {
        "color": "#ffffff",
        "alpha": "1",
        "rgba": "rgba(255,255,255,1)"
    },
    "side_bottom_color_sticky": "#222328",
    "side_bottom_color_hover_sticky": "#232325",
    "side_bottom_height_sticky": {
        "height": "58"
    },
    "side_bottom_spacing_sticky": {
        "padding-right": "",
        "padding-left": ""
    },
    "side_top__tablet_custom": "",
    "side_top__tablet_background": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_top__tablet_background2": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_top__tablet_color": "",
    "side_top__tablet_color_hover": "",
    "side_top__tablet_height": {
        "height": ""
    },
    "side_top__tablet_spacing": {
        "padding-right": "",
        "padding-left": ""
    },
    "side_top__tablet_border": "",
    "side_top__tablet_border_color": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_top__tablet_border_radius": "",
    "side_top__tablet_sticky": "1",
    "side_middle__tablet_custom": "",
    "side_middle__tablet_background": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_middle__tablet_background2": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_middle__tablet_color": "",
    "side_middle__tablet_color_hover": "",
    "side_middle__tablet_height": {
        "height": ""
    },
    "side_middle__tablet_spacing": {
        "padding-right": "",
        "padding-left": ""
    },
    "side_middle__tablet_border": "",
    "side_middle__tablet_border_color": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_middle__tablet_border_radius": "",
    "side_middle__tablet_sticky": "1",
    "side_bottom__tablet_custom": "",
    "side_bottom__tablet_background": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_bottom__tablet_background2": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_bottom__tablet_color": "",
    "side_bottom__tablet_color_hover": "",
    "side_bottom__tablet_height": {
        "height": ""
    },
    "side_bottom__tablet_spacing": {
        "padding-right": "",
        "padding-left": ""
    },
    "side_bottom__tablet_border": "",
    "side_bottom__tablet_border_color": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_bottom__tablet_border_radius": "",
    "side_bottom__tablet_sticky": "1",
    "side_top__mobile_custom": "",
    "side_top__mobile_background": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_top__mobile_background2": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_top__mobile_color": "",
    "side_top__mobile_color_hover": "",
    "side_top__mobile_height": {
        "height": ""
    },
    "side_top__mobile_spacing": {
        "padding-right": "",
        "padding-left": ""
    },
    "side_top__mobile_border": "",
    "side_top__mobile_border_color": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_top__mobile_border_radius": "",
    "side_top__mobile_sticky": "1",
    "side_middle__mobile_custom": "1",
    "side_middle__mobile_background": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_middle__mobile_background2": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_middle__mobile_color": "",
    "side_middle__mobile_color_hover": "",
    "side_middle__mobile_height": {
        "height": ""
    },
    "side_middle__mobile_spacing": {
        "padding-right": "10px",
        "padding-left": "10px"
    },
    "side_middle__mobile_border": "",
    "side_middle__mobile_border_color": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_middle__mobile_border_radius": "",
    "side_middle__mobile_sticky": "1",
    "side_bottom__mobile_custom": "",
    "side_bottom__mobile_background": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_bottom__mobile_background2": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_bottom__mobile_color": "",
    "side_bottom__mobile_color_hover": "",
    "side_bottom__mobile_height": {
        "height": ""
    },
    "side_bottom__mobile_spacing": {
        "padding-right": "",
        "padding-left": ""
    },
    "side_bottom__mobile_border": "",
    "side_bottom__mobile_border_color": {
        "color": "",
        "alpha": "1",
        "rgba": ""
    },
    "side_bottom__mobile_border_radius": "",
    "side_bottom__mobile_sticky": "1",
    "header_full_width": "0",
    "header_on_bg": "0",
    "header_sticky": "",
    "header_sticky_appearance_style": "classic",
    "header_sticky_appearance_from_top": "auto",
    "header_sticky_appearance_number": {
        "height": "300"
    },
    "header_sticky_shadow": "1",
    "tablet_header_on_bg": "0",
    "tablet_header_sticky": "",
    "mobile_header_on_bg": "0",
    "mobile_header_sticky": "",
    "page_title_conditional": "1",
    "blog_title_conditional": "1",
    "team_title_conditional": "",
    "portfolio_title_conditional": "1",
    "page_title_breadcrumbs_conditional": "1",
    "page_title_vert_align": "middle",
    "page_title_horiz_align": "center",
    "page_title_font_color": "#ffffff",
    "page_title_bg_color": "#ffffff",
    "page_title_overlay_color": "",
    "page_title_bg_image": {
        "background-repeat": "no-repeat",
        "background-size": "cover",
        "background-attachment": "scroll",
        "background-position": "center center",
        "background-image": "https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/09/pic_paralax_2.jpg",
        "media": {
            "id": "2651",
            "height": "1000",
            "width": "3000",
            "thumbnail": "https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/09/pic_paralax_2-150x150.jpg"
        }
    },
    "page_title_height": {
        "height": "260"
    },
    "page_title_top_border": "",
    "page_title_top_border_color": {
        "color": "#191a1c",
        "alpha": "1",
        "rgba": "rgba(25,26,28,1)"
    },
    "page_title_bottom_border": "",
    "page_title_bottom_border_color": {
        "color": "#191a1c",
        "alpha": "1",
        "rgba": "rgba(25,26,28,1)"
    },
    "page_title_bottom_margin": {
        "margin-bottom": "80"
    },
    "footer_full_width": "default",
    "footer_bg_color": "#ffffff",
    "footer_text_color": "#ffffff",
    "footer_heading_color": "#ffffff",
    "footer_bg_image": {
        "background-repeat": "no-repeat",
        "background-size": "cover",
        "background-attachment": "scroll",
        "background-position": "center center",
        "background-image": "https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/09/pic_footer.jpg",
        "media": {
            "id": "2692",
            "height": "510",
            "width": "1920",
            "thumbnail": "https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/09/pic_footer-150x150.jpg"
        }
    },
    "footer_top_border": "1",
    "footer_top_border_color": {
        "color": "#ffffff",
        "alpha": "0.1",
        "rgba": "rgba(255,255,255,0.1)"
    },
    "footer_switch": "1",
    "footer_column": "4",
    "footer_column2": "6-6",
    "footer_column3": "5-5-2",
    "footer_column5": "2-3-2-2-3",
    "footer_align": "left",
    "footer_spacing": {
        "padding-top": "45",
        "padding-right": "0",
        "padding-bottom": "45",
        "padding-left": "0"
    },
    "copyright_switch": "1",
    "copyright_editor": "",
    "copyright_align": "center",
    "copyright_spacing": {
        "padding-top": "17",
        "padding-right": "0",
        "padding-bottom": "17",
        "padding-left": "0"
    },
    "copyright_bg_color": "",
    "copyright_text_color": "#ffffff",
    "copyright_top_border": "",
    "copyright_top_border_color": {
        "color": "#191a1c",
        "alpha": "1",
        "rgba": "rgba(25,26,28,1)"
    },
    "pre_footer_switch": "1",
    "pre_footer_editor": "",
    "pre_footer_align": "left",
    "pre_footer_spacing": {
        "padding-top": "50",
        "padding-right": "15",
        "padding-bottom": "14",
        "padding-left": "15"
    },
    "pre_footer_bg_color": "",
    "pre_footer_text_color": "#ffffff",
    "pre_footer_bottom_border": "",
    "pre_footer_bottom_border_color": {
        "color": "#191a1c",
        "alpha": "1",
        "rgba": "rgba(25,26,28,1)"
    },
    "related_posts": "",
    "related_posts_filter": "tag",
    "author_box": "1",
    "post_comments": "1",
    "post_pingbacks": "1",
    "blog_post_likes": "",
    "blog_post_share": "",
    "blog_post_listing_content": "",
    "blog_post_fimage_animation": "",
    "page_sidebar_layout": "right",
    "page_sidebar_def": "sidebar_main-sidebar",
    "blog_single_sidebar_layout": "right",
    "blog_single_sidebar_def": "sidebar_main-sidebar",
    "portfolio_single_sidebar_layout": "none",
    "portfolio_single_sidebar_def": "",
    "team_single_sidebar_layout": "none",
    "team_single_sidebar_def": "",
    "sidebars": [
        "Main Sidebar",
        "Menu Sidebar",
        "Shop Sidebar",
        "Header Sidebar"
    ],
    "theme-custom-color": "#6254e7",
    "theme-custom-color-start": "#9289f1",
    "theme-custom-color2": "#ff7426",
    "theme-custom-color2-start": "#f0ac0e",
    "body-background-color": "#ffffff",
    "menu-font": {
        "font-family": "Rubik",
        "font-options": "",
        "google": "1",
        "font-weight": "400",
        "font-style": "",
        "subsets": "",
        "text-transform": "none",
        "font-size": "16px",
        "line-height": "22px",
        "letter-spacing": ""
    },
    "main-font": {
        "font-family": "Rubik",
        "font-options": "",
        "google": "1",
        "font-weight": "400",
        "font-style": "",
        "font-all-weight": "400,500",
        "subsets": "",
        "font-size": "18px",
        "line-height": "27px",
        "color": "#696687"
    },
    "secondary-font": {
        "font-family": "Nunito",
        "font-options": "",
        "google": "1",
        "font-weight": "400",
        "font-style": "",
        "subsets": "",
        "font-size": "18px",
        "line-height": "27px",
        "color": "#696687"
    },
    "header-font": {
        "font-family": "Nunito",
        "font-options": "",
        "google": "1",
        "font-weight": "800",
        "font-style": "",
        "subsets": "",
        "color": "#3b3663"
    },
    "h1-font": {
        "font-family": "Nunito",
        "font-options": "",
        "google": "1",
        "font-weight": "800",
        "font-style": "",
        "subsets": "",
        "font-size": "40px",
        "line-height": "43px"
    },
    "h2-font": {
        "font-family": "Nunito",
        "font-options": "",
        "google": "1",
        "font-weight": "800",
        "font-style": "",
        "subsets": "",
        "font-size": "30px",
        "line-height": "40px"
    },
    "h3-font": {
        "font-family": "Nunito",
        "font-options": "",
        "google": "1",
        "font-weight": "800",
        "font-style": "",
        "subsets": "",
        "font-size": "24px",
        "line-height": "30px"
    },
    "h4-font": {
        "font-family": "Nunito",
        "font-options": "",
        "google": "1",
        "font-weight": "800",
        "font-style": "",
        "subsets": "",
        "font-size": "20px",
        "line-height": "33px"
    },
    "h5-font": {
        "font-family": "Nunito",
        "font-options": "",
        "google": "1",
        "font-weight": "700",
        "font-style": "",
        "subsets": "",
        "font-size": "18px",
        "line-height": "30px"
    },
    "h6-font": {
        "font-family": "Nunito",
        "font-options": "",
        "google": "1",
        "font-weight": "600",
        "font-style": "",
        "subsets": "",
        "font-size": "16px",
        "line-height": "24px"
    },
    "map_prefooter_default": "",
    "google_map_api_key": "",
    "google_map_latitude": "-37.8172507",
    "google_map_longitude": "144.9535833",
    "zoom_map": "14",
    "map_marker_info": "1",
    "custom_map_marker": "https://livewp.site/assets/img/ewebot.png",
    "map_marker_info_street_number": "",
    "map_marker_info_street": "",
    "map_marker_info_descr": "",
    "map_marker_info_background": "#0a0b0b",
    "map-marker-font": {
        "font-family": "",
        "font-options": "",
        "google": "1",
        "font-weight": "",
        "font-style": "",
        "subsets": ""
    },
    "map_marker_info_color": "#ffffff",
    "custom_map_style": "",
    "custom_map_code": "",
    "products_layout": "container",
    "products_sidebar_layout": "left",
    "products_sidebar_def": "sidebar_shop-sidebar",
    "products_per_page_frontend": "",
    "products_sorting_frontend": "",
    "products_infinite_scroll": "none",
    "woocommerce_pagination": "top_bottom",
    "woocommerce_grid_list": "off",
    "label_color_sale": {
        "color": "#dc1c52",
        "alpha": "1",
        "rgba": "rgba(230,55,100,1)"
    },
    "label_color_hot": {
        "color": "#71d080",
        "alpha": "1",
        "rgba": "rgba(113,208,128,1)"
    },
    "label_color_new": {
        "color": "#435bb2",
        "alpha": "1",
        "rgba": "rgba(106,209,228,1)"
    },
    "product_layout": "horizontal",
    "activate_carousel_thumb": "",
    "product_container": "container",
    "sticky_thumb": "",
    "product_sidebar_layout": "none",
    "product_sidebar_def": "",
    "shop_size_guide": "",
    "size_guide": {
        "url": "",
        "id": "",
        "height": "",
        "width": "",
        "thumbnail": "",
        "title": "",
        "caption": "",
        "alt": "",
        "description": ""
    },
    "next_prev_product": "1",
    "shop_cat_title_conditional": "1",
    "product_title_conditional": "",
    "customize_shop_title": "",
    "shop_title_vert_align": "middle",
    "shop_title_horiz_align": "left",
    "shop_title_font_color": "#ffffff",
    "shop_title_bg_color": "#0a0b0b",
    "shop_title_overlay_color": "",
    "shop_title_bg_image": {
        "background-repeat": "no-repeat",
        "background-size": "cover",
        "background-attachment": "scroll",
        "background-position": "center center",
        "background-image": "",
        "media": {
            "id": "",
            "height": "",
            "width": "",
            "thumbnail": ""
        }
    },
    "shop_title_height": {
        "height": "300"
    },
    "shop_title_top_border": "",
    "shop_title_top_border_color": {
        "color": "#0a0b0b",
        "alpha": "1",
        "rgba": "rgba(10,11,11,1)"
    },
    "shop_title_bottom_border": "",
    "shop_title_bottom_border_color": {
        "color": "#0a0b0b",
        "alpha": "1",
        "rgba": "rgba(10,11,11,1)"
    },
    "shop_title_bottom_margin": {
        "margin-bottom": "60"
    },
    "shop_header": "",
    "import_link": "",
    "logo_height_mobile": null,
    "side_top_mobile": null,
    "side_middle_mobile": null,
    "side_bottom_mobile": null,
    "delimiter1_margin": null,
    "delimiter2_margin": null,
    "delimiter3_margin": null,
    "delimiter4_margin": null,
    "delimiter5_margin": null,
    "delimiter6_margin": null,
    "search_type": null,
    "search_type_color": null,
    "search_type_bg_color": null,
    "side_top_custom": null,
    "side_middle_custom": null,
    "side_bottom_custom": null,
    "redux-backup": 1,
    "info_normal": "",
    "header_templates-start": "",
    "header_templates-end": "",
    "no_item-start": "",
    "no_item_message": "",
    "no_item-end": "",
    "logo-start": "",
    "logo-end": "",
    "menu-start": "",
    "menu-end": "",
    "burger_sidebar-start": "",
    "burger_sidebar-end": "",
    "top_left-start": "",
    "top_left-end": "",
    "top_center-start": "",
    "top_center-end": "",
    "top_right-start": "",
    "top_right-end": "",
    "middle_left-start": "",
    "middle_left-end": "",
    "middle_center-start": "",
    "middle_center-end": "",
    "middle_right-start": "",
    "middle_right-end": "",
    "bottom_left-start": "",
    "bottom_left-end": "",
    "bottom_center-start": "",
    "bottom_center-end": "",
    "bottom_right-start": "",
    "bottom_right-end": "",
    "top_left__tablet-start": "",
    "top_left__tablet-end": "",
    "top_center__tablet-start": "",
    "top_center__tablet-end": "",
    "top_right__tablet-start": "",
    "top_right__tablet-end": "",
    "middle_left__tablet-start": "",
    "middle_left__tablet-end": "",
    "middle_center__tablet-start": "",
    "middle_center__tablet-end": "",
    "middle_right__tablet-start": "",
    "middle_right__tablet-end": "",
    "bottom_left__tablet-start": "",
    "bottom_left__tablet-end": "",
    "bottom_center__tablet-start": "",
    "bottom_center__tablet-end": "",
    "bottom_right__tablet-start": "",
    "bottom_right__tablet-end": "",
    "top_left__mobile-start": "",
    "top_left__mobile-end": "",
    "top_center__mobile-start": "",
    "top_center__mobile-end": "",
    "top_right__mobile-start": "",
    "top_right__mobile-end": "",
    "middle_left__mobile-start": "",
    "middle_left__mobile-end": "",
    "middle_center__mobile-start": "",
    "middle_center__mobile-end": "",
    "middle_right__mobile-start": "",
    "middle_right__mobile-end": "",
    "bottom_left__mobile-start": "",
    "bottom_left__mobile-end": "",
    "bottom_center__mobile-start": "",
    "bottom_center__mobile-end": "",
    "bottom_right__mobile-start": "",
    "bottom_right__mobile-end": "",
    "text1-start": "",
    "text1-end": "",
    "text2-start": "",
    "text2-end": "",
    "text3-start": "",
    "text3-end": "",
    "text4-start": "",
    "text4-end": "",
    "text5-start": "",
    "text5-end": "",
    "text6-start": "",
    "text6-end": "",
    "delimiter1-start": "",
    "delimiter1-end": "",
    "delimiter2-start": "",
    "delimiter2-end": "",
    "delimiter3-start": "",
    "delimiter3-end": "",
    "delimiter4-start": "",
    "delimiter4-end": "",
    "delimiter5-start": "",
    "delimiter5-end": "",
    "delimiter6-start": "",
    "delimiter6-end": "",
    "side_top-start": "",
    "side_top-end": "",
    "side_middle-start": "",
    "side_middle-end": "",
    "side_bottom-start": "",
    "side_bottom-end": "",
    "side_top__tablet-start": "",
    "side_top__tablet_styling-start": "",
    "side_top__tablet_styling-end": "",
    "side_top__tablet-end": "",
    "side_middle__tablet-start": "",
    "side_middle__tablet_styling-start": "",
    "side_middle__tablet_styling-end": "",
    "side_middle__tablet-end": "",
    "side_bottom__tablet-start": "",
    "side_bottom__tablet_styling-start": "",
    "side_bottom__tablet_styling-end": "",
    "side_bottom__tablet-end": "",
    "side_top__mobile-start": "",
    "side_top__mobile_styling-start": "",
    "side_top__mobile_styling-end": "",
    "side_top__mobile-end": "",
    "side_middle__mobile-start": "",
    "side_middle__mobile_styling-start": "",
    "side_middle__mobile_styling-end": "",
    "side_middle__mobile-end": "",
    "side_bottom__mobile-start": "",
    "side_bottom__mobile_styling-start": "",
    "side_bottom__mobile_styling-end": "",
    "side_bottom__mobile-end": "",
    "desktop_header_settings-start": "",
    "desktop_header_settings-end": "",
    "tablet_header_settings-start": "",
    "tablet_header_settings-end": "",
    "mobile_header_settings-start": "",
    "mobile_header_settings-end": "",
    "page_title-start": "",
    "page_title-end": "",
    "footer-start": "",
    "footer-end": "",
    "section-label_color-start": "",
    "section-label_color-end": "",
    "shop_title-start": "",
    "shop_title-end": "",
    "wbc_demo_importer": "",
    "redux_import_export": ""
}';

        $option = json_decode($option,true);
        update_option( 'ewebot_default_options', $option );
    }
    //update_option( 'ewebot_default_options', '' );
}
gt3_get_default_option();
if (!function_exists('gt3_default_fonts')) {
	function gt3_default_fonts(){
	    $link = '//fonts.googleapis.com/css?family=Rubik%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CNunito%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic';
	    wp_enqueue_style('gt3-default-font',$link);
	}
}

if ( !class_exists( 'GT3_Core_Elementor' ) ) {
    add_action('wp_enqueue_scripts', 'gt3_default_fonts');
}
add_action('admin_enqueue_scripts', 'gt3_default_fonts');

function gt3_header_presets(){
    $header_presets = array();

    $header_presets['header_preset_1'] = '{"gt3_header_builder_id":{"all_item":{"layout":"all","title":"All Item","content":{"placebo":"placebo","login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space1":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space2":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space3":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space4":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space5":{"title":"gt3_flag%26%238592%3B%26%238594%3B"}}},"top_left":{"layout":"one-thirds","title":"Top Left","has_settings":"1","content":{"placebo":"placebo","text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"}}},"top_center":{"layout":"one-thirds","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right":{"layout":"one-thirds","title":"Top Right","has_settings":"1","content":{"placebo":"placebo","text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"}}},"middle_left":{"layout":"one-thirds clear-item","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"}}},"middle_center":{"layout":"one-thirds","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo","menu":{"title":"gt3_flagMenu","has_settings":"1"}}},"middle_right":{"layout":"one-thirds","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo","search":{"title":"gt3_flagSearch"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"}}},"bottom_left":{"layout":"one-thirds clear-item","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center":{"layout":"one-thirds","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right":{"layout":"one-thirds","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}},"all_item__tablet":{"layout":"all","extra_class":"tablet","title":"All Item","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"},"menu":{"title":"gt3_flagMenu","has_settings":"1"},"search":{"title":"gt3_flagSearch"},"login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space1":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space2":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space3":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space4":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space5":{"title":"gt3_flag%26%238592%3B%26%238594%3B"}}},"top_left__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left__tablet":{"layout":"one-thirds clear-item","extra_class":"tablet","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo"}},"middle_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo"}},"bottom_left__tablet":{"layout":"one-thirds clear-item","extra_class":"tablet","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}},"all_item__mobile":{"layout":"all","extra_class":"mobile","title":"All Item","content":{"placebo":"placebo","search":{"title":"gt3_flagSearch"},"login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space1":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space2":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space3":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space4":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space5":{"title":"gt3_flag%26%238592%3B%26%238594%3B"}}},"top_left__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left__mobile":{"layout":"one-thirds clear-item","extra_class":"mobile","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"}}},"middle_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo","menu":{"title":"gt3_flagMenu","has_settings":"1"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"}}},"bottom_left__mobile":{"layout":"one-thirds clear-item","extra_class":"mobile","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}}},"header_logo":{"url":"https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/08/logo_retinablack.png","id":"1456","height":"96","width":"298","thumbnail":"https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/08/logo_retinablack-150x96.png","title":"logo_retina(black)","caption":"","alt":"","description":""},"logo_height_custom":"1","logo_height":{"height":"48"},"logo_max_height":"","sticky_logo_height":{"height":""},"logo_sticky":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_tablet":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_teblet_width":{"width":""},"logo_mobile":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_mobile_width":{"width":"149"},"menu_select":"main-menu","menu_ative_top_line":"","sub_menu_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"sub_menu_color":"#3b3663","sub_menu_color_hover":"#f47514","burger_sidebar_select":"sidebar_header-sidebar","top_left-align":"left","top_center-align":"center","top_right-align":"right","middle_left-align":"left","middle_center-align":"left","middle_right-align":"right","bottom_left-align":"left","bottom_center-align":"center","bottom_right-align":"right","top_left__tablet-align":"left","top_center__tablet-align":"center","top_right__tablet-align":"right","middle_left__tablet-align":"left","middle_center__tablet-align":"center","middle_right__tablet-align":"right","bottom_left__tablet-align":"left","bottom_center__tablet-align":"center","bottom_right__tablet-align":"right","top_left__mobile-align":"left","top_center__mobile-align":"center","top_right__mobile-align":"right","middle_left__mobile-align":"left","middle_center__mobile-align":"center","middle_right__mobile-align":"right","bottom_left__mobile-align":"left","bottom_center__mobile-align":"center","bottom_right__mobile-align":"right","text1_editor":"<p><a class=\"gt3_icon_link gt3_custom_color\" href=\"#\" target=\"_blank\" data-color=\"#c1bfcc\" data-hover-color=\"#4296c3\" style=\"font-size: 14px; color: #c1bfcc; margin-right: 7px;\" rel=\"noopener\"><i class=\"fa fa-twitter\"> </i></a> <a class=\"gt3_icon_link gt3_custom_color\" href=\"#\" target=\"_blank\" data-color=\"#c1bfcc\" data-hover-color=\"#5f6d99\" style=\"font-size: 14px; color: #c1bfcc; margin-right: 9px;\" rel=\"noopener\"><i class=\"fa fa-facebook\"> </i></a> <a class=\"gt3_icon_link gt3_custom_color\" href=\"#\" target=\"_blank\" data-color=\"#c1bfcc\" data-hover-color=\"#b9666d\" style=\"font-size: 14px; color: #c1bfcc; margin-right: 9px;\" rel=\"noopener\"><i class=\"fa fa-google-plus\"> </i></a> <a class=\"gt3_icon_link gt3_custom_color\" href=\"#\" target=\"_blank\" data-color=\"#c1bfcc\" data-hover-color=\"#a75061\" style=\"font-size: 14px; color: #c1bfcc; margin-right: 9px;\" rel=\"noopener\"><i class=\"fa fa-pinterest-p\"> </i></a> <a class=\"gt3_icon_link gt3_custom_color\" href=\"#\" target=\"_blank\" data-color=\"#c1bfcc\" data-hover-color=\"#427ea8\" style=\"font-size: 14px; color: #c1bfcc; margin-right: 0px;\" rel=\"noopener\"><i class=\"fa fa-linkedin\"> </i></a></p>","text2_editor":"<p><a href=\"tel:+88002534236\"></a><a class=\"gt3_icon_link gt3_custom_color\" href=\"#\" target=\"_blank\" data-color=\"#5747e4\" data-hover-color=\"#5747e4\" style=\"font-size: 16px; color: #5747e4; margin-right: 10px;\" rel=\"noopener\"><i class=\"fa fa-phone\" style=\"font-weight: bold;\"> </i></a><a href=\"tel:+88002534236\"><span class=\"gt3_font-weight\">8 800 2563 123</span></a></p>","text3_editor":"<p><a class=\"gt3_icon_link gt3_custom_color\" href=\"#\" target=\"_blank\" data-color=\"#5747e4\" data-hover-color=\"#5747e4\" style=\"font-size: 16px; margin-right: 8px; color: #5747e4;\" rel=\"noopener\"><i class=\"fa fa-envelope\"> </i></a> <a href=\"mailto:email@yoursite.com\">email@yoursite.com</a></p>","text4_editor":"","text5_editor":"","text6_editor":"","delimiter1_height":{"height":"1em","units":"em"},"delimiter1_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter2_height":{"height":"1em","units":"em"},"delimiter2_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter3_height":{"height":"1em","units":"em"},"delimiter3_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter4_height":{"height":"1em","units":"em"},"delimiter4_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter5_height":{"height":"1em","units":"em"},"delimiter5_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter6_height":{"height":"1em","units":"em"},"delimiter6_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_top_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_top_background2":{"color":"","alpha":"","rgba":""},"side_top_color":"#696687","side_top_color_hover":"#5747e4","side_top_height":{"height":"46"},"side_top_spacing":{"padding-right":"","padding-left":""},"side_top_border":"1","side_top_border_color":{"color":"#696687","alpha":"0.1","rgba":"rgba(105,102,135,0.1)"},"side_top_border_radius":"","side_top_sticky":"1","side_top_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_top_color_sticky":"#222328","side_top_color_hover_sticky":"#232325","side_top_height_sticky":{"height":"58"},"side_top_spacing_sticky":{"padding-right":"","padding-left":""},"side_middle_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_middle_background2":{"color":"#ffffff","alpha":"0","rgba":"rgba(255,255,255,0)"},"side_middle_color":"#3b3663","side_middle_color_hover":"#f47514","side_middle_height":{"height":"108"},"side_middle_spacing":{"padding-right":"","padding-left":""},"side_middle_border":"","side_middle_border_color":{"color":"#F3F4F4","alpha":"1","rgba":"rgba(243,244,244,1)"},"side_middle_border_radius":"","side_middle_sticky":"1","side_middle_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_middle_color_sticky":"#222328","side_middle_color_hover_sticky":"#232325","side_middle_height_sticky":{"height":"58"},"side_middle_spacing_sticky":{"padding-right":"","padding-left":""},"side_bottom_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_bottom_background2":{"color":"#ffffff","alpha":"0","rgba":"rgba(255,255,255,0)"},"side_bottom_color":"#232325","side_bottom_color_hover":"#232325","side_bottom_height":{"height":"100"},"side_bottom_spacing":{"padding-right":"","padding-left":""},"side_bottom_border":"","side_bottom_border_color":{"color":"#F3F4F4","alpha":"1","rgba":"rgba(243,244,244,1)"},"side_bottom_border_radius":"","side_bottom_sticky":"1","side_bottom_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_bottom_color_sticky":"#222328","side_bottom_color_hover_sticky":"#232325","side_bottom_height_sticky":{"height":"58"},"side_bottom_spacing_sticky":{"padding-right":"","padding-left":""},"side_top__tablet_custom":"","side_top__tablet_background":{"color":"","alpha":"1","rgba":""},"side_top__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_top__tablet_color":"","side_top__tablet_color_hover":"","side_top__tablet_height":{"height":""},"side_top__tablet_spacing":{"padding-right":"","padding-left":""},"side_top__tablet_border":"","side_top__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_top__tablet_border_radius":"","side_top__tablet_sticky":"1","side_middle__tablet_custom":"","side_middle__tablet_background":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_color":"","side_middle__tablet_color_hover":"","side_middle__tablet_height":{"height":""},"side_middle__tablet_spacing":{"padding-right":"","padding-left":""},"side_middle__tablet_border":"","side_middle__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_border_radius":"","side_middle__tablet_sticky":"1","side_bottom__tablet_custom":"","side_bottom__tablet_background":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_color":"","side_bottom__tablet_color_hover":"","side_bottom__tablet_height":{"height":""},"side_bottom__tablet_spacing":{"padding-right":"","padding-left":""},"side_bottom__tablet_border":"","side_bottom__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_border_radius":"","side_bottom__tablet_sticky":"1","side_top__mobile_custom":"","side_top__mobile_background":{"color":"","alpha":"1","rgba":""},"side_top__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_top__mobile_color":"","side_top__mobile_color_hover":"","side_top__mobile_height":{"height":""},"side_top__mobile_spacing":{"padding-right":"","padding-left":""},"side_top__mobile_border":"","side_top__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_top__mobile_border_radius":"","side_top__mobile_sticky":"1","side_middle__mobile_custom":"1","side_middle__mobile_background":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_color":"","side_middle__mobile_color_hover":"","side_middle__mobile_height":{"height":""},"side_middle__mobile_spacing":{"padding-right":"10px","padding-left":"10px"},"side_middle__mobile_border":"","side_middle__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_border_radius":"","side_middle__mobile_sticky":"1","side_bottom__mobile_custom":"","side_bottom__mobile_background":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_color":"","side_bottom__mobile_color_hover":"","side_bottom__mobile_height":{"height":""},"side_bottom__mobile_spacing":{"padding-right":"","padding-left":""},"side_bottom__mobile_border":"","side_bottom__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_border_radius":"","side_bottom__mobile_sticky":"1","header_full_width":"0","header_on_bg":"0","header_sticky":"","header_sticky_appearance_style":"classic","header_sticky_appearance_from_top":"auto","header_sticky_appearance_number":{"height":"300"},"header_sticky_shadow":"1","tablet_header_on_bg":"0","tablet_header_sticky":"","mobile_header_on_bg":"0","mobile_header_sticky":""}';


    $header_presets['header_preset_2'] = '{"gt3_header_builder_id":{"all_item":{"layout":"all","title":"All Item","content":{"placebo":"placebo","login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"},"text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"empty_space1":{"title":"gt3_flag%E2%86%90%E2%86%92"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space3":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space4":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space5":{"title":"gt3_flag%E2%86%90%E2%86%92"},"search":{"title":"gt3_flagSearch"}}},"top_left":{"layout":"one-thirds","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center":{"layout":"one-thirds","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right":{"layout":"one-thirds","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left":{"layout":"one-thirds clear-item","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"}}},"middle_center":{"layout":"one-thirds","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right":{"layout":"one-thirds","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo","menu":{"title":"gt3_flagMenu","has_settings":"1"},"empty_space2":{"title":"gt3_flag%E2%86%90%E2%86%92"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"}}},"bottom_left":{"layout":"one-thirds clear-item","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center":{"layout":"one-thirds","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right":{"layout":"one-thirds","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}},"all_item__tablet":{"layout":"all","extra_class":"tablet","title":"All Item","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"},"search":{"title":"gt3_flagSearch"},"login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"},"text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"menu":{"title":"gt3_flagMenu","has_settings":"1"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space1":{"title":"gt3_flag%E2%86%90%E2%86%92"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space2":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space3":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space4":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space5":{"title":"gt3_flag%E2%86%90%E2%86%92"}}},"top_left__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left__tablet":{"layout":"one-thirds clear-item","extra_class":"tablet","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo"}},"middle_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo"}},"bottom_left__tablet":{"layout":"one-thirds clear-item","extra_class":"tablet","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}},"all_item__mobile":{"layout":"all","extra_class":"mobile","title":"All Item","content":{"placebo":"placebo","login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space1":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space2":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space3":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space4":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space5":{"title":"gt3_flag%E2%86%90%E2%86%92"},"search":{"title":"gt3_flagSearch"}}},"top_left__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left__mobile":{"layout":"one-thirds clear-item","extra_class":"mobile","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"}}},"middle_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo","menu":{"title":"gt3_flagMenu","has_settings":"1"}}},"bottom_left__mobile":{"layout":"one-thirds clear-item","extra_class":"mobile","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}}},"header_logo":{"url":"https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/08/logo_retina.png","id":"1455","height":"96","width":"298","thumbnail":"https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/08/logo_retina-150x96.png","title":"logo_retina","caption":"","alt":"","description":""},"logo_height_custom":"1","logo_height":{"height":"48"},"logo_max_height":"","sticky_logo_height":{"height":""},"logo_sticky":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_tablet":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_teblet_width":{"width":""},"logo_mobile":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_mobile_width":{"width":"149"},"menu_select":"main-menu","menu_ative_top_line":"","sub_menu_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"sub_menu_color":"#696687","sub_menu_color_hover":"#f47514","burger_sidebar_select":"","top_left-align":"left","top_center-align":"center","top_right-align":"right","middle_left-align":"left","middle_center-align":"right","middle_right-align":"right","bottom_left-align":"left","bottom_center-align":"center","bottom_right-align":"right","top_left__tablet-align":"left","top_center__tablet-align":"center","top_right__tablet-align":"right","middle_left__tablet-align":"left","middle_center__tablet-align":"center","middle_right__tablet-align":"right","bottom_left__tablet-align":"left","bottom_center__tablet-align":"center","bottom_right__tablet-align":"right","top_left__mobile-align":"left","top_center__mobile-align":"center","top_right__mobile-align":"right","middle_left__mobile-align":"left","middle_center__mobile-align":"center","middle_right__mobile-align":"right","bottom_left__mobile-align":"left","bottom_center__mobile-align":"center","bottom_right__mobile-align":"right","text1_editor":"<p><a class=\"button alignment_center\" href=\"#\">Call Us: +1 800-326-4538</a></p>","text2_editor":"","text3_editor":"","text4_editor":"<p><a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-linkedin\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-twitter\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-facebook\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc;\" rel=\"noopener\"><i class=\"fa fa-instagram\"> </i></a></p>","text5_editor":"","text6_editor":"","delimiter1_height":{"height":"1em","units":"em"},"delimiter1_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter2_height":{"height":"1em","units":"em"},"delimiter2_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter3_height":{"height":"1em","units":"em"},"delimiter3_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter4_height":{"height":"1em","units":"em"},"delimiter4_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter5_height":{"height":"1em","units":"em"},"delimiter5_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter6_height":{"height":"1em","units":"em"},"delimiter6_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_top_background":{"color":"#2b3034","alpha":"1","rgba":"rgba(43,48,52,1)"},"side_top_background2":{"color":"","alpha":"","rgba":""},"side_top_color":"#a3adb8","side_top_color_hover":"#ffffff","side_top_height":{"height":"42"},"side_top_spacing":{"padding-right":"","padding-left":""},"side_top_border":"1","side_top_border_color":{"color":"#ffffff","alpha":"0.2","rgba":"rgba(255,255,255,0.2)"},"side_top_border_radius":"","side_top_sticky":"1","side_top_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_top_color_sticky":"#222328","side_top_color_hover_sticky":"#232325","side_top_height_sticky":{"height":"58"},"side_top_spacing_sticky":{"padding-right":"","padding-left":""},"side_middle_background":{"color":"#2b3034","alpha":"0","rgba":"rgba(43,48,52,0)"},"side_middle_background2":{"color":"#ffffff","alpha":"0","rgba":"rgba(255,255,255,0)"},"side_middle_color":"#e1e1e1","side_middle_color_hover":"#ffffff","side_middle_height":{"height":"108"},"side_middle_spacing":{"padding-right":"0","padding-left":"0"},"side_middle_border":"","side_middle_border_color":{"color":"#F3F4F4","alpha":"1","rgba":"rgba(243,244,244,1)"},"side_middle_border_radius":"","side_middle_sticky":"1","side_middle_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_middle_color_sticky":"#222328","side_middle_color_hover_sticky":"#232325","side_middle_height_sticky":{"height":"58"},"side_middle_spacing_sticky":{"padding-right":"","padding-left":""},"side_bottom_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_bottom_background2":{"color":"#ffffff","alpha":"0","rgba":"rgba(255,255,255,0)"},"side_bottom_color":"#232325","side_bottom_color_hover":"#232325","side_bottom_height":{"height":"100"},"side_bottom_spacing":{"padding-right":"","padding-left":""},"side_bottom_border":"","side_bottom_border_color":{"color":"#F3F4F4","alpha":"1","rgba":"rgba(243,244,244,1)"},"side_bottom_border_radius":"","side_bottom_sticky":"1","side_bottom_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_bottom_color_sticky":"#222328","side_bottom_color_hover_sticky":"#232325","side_bottom_height_sticky":{"height":"58"},"side_bottom_spacing_sticky":{"padding-right":"","padding-left":""},"side_top__tablet_custom":"","side_top__tablet_background":{"color":"","alpha":"1","rgba":""},"side_top__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_top__tablet_color":"","side_top__tablet_color_hover":"","side_top__tablet_height":{"height":""},"side_top__tablet_spacing":{"padding-right":"","padding-left":""},"side_top__tablet_border":"","side_top__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_top__tablet_border_radius":"","side_top__tablet_sticky":"1","side_middle__tablet_custom":"","side_middle__tablet_background":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_color":"","side_middle__tablet_color_hover":"","side_middle__tablet_height":{"height":""},"side_middle__tablet_spacing":{"padding-right":"","padding-left":""},"side_middle__tablet_border":"","side_middle__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_border_radius":"","side_middle__tablet_sticky":"1","side_bottom__tablet_custom":"","side_bottom__tablet_background":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_color":"","side_bottom__tablet_color_hover":"","side_bottom__tablet_height":{"height":""},"side_bottom__tablet_spacing":{"padding-right":"","padding-left":""},"side_bottom__tablet_border":"","side_bottom__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_border_radius":"","side_bottom__tablet_sticky":"1","side_top__mobile_custom":"","side_top__mobile_background":{"color":"","alpha":"1","rgba":""},"side_top__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_top__mobile_color":"","side_top__mobile_color_hover":"","side_top__mobile_height":{"height":""},"side_top__mobile_spacing":{"padding-right":"","padding-left":""},"side_top__mobile_border":"","side_top__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_top__mobile_border_radius":"","side_top__mobile_sticky":"1","side_middle__mobile_custom":"1","side_middle__mobile_background":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_color":"","side_middle__mobile_color_hover":"","side_middle__mobile_height":{"height":""},"side_middle__mobile_spacing":{"padding-right":"8px","padding-left":"8px"},"side_middle__mobile_border":"","side_middle__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_border_radius":"","side_middle__mobile_sticky":"1","side_bottom__mobile_custom":"","side_bottom__mobile_background":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_color":"","side_bottom__mobile_color_hover":"","side_bottom__mobile_height":{"height":""},"side_bottom__mobile_spacing":{"padding-right":"","padding-left":""},"side_bottom__mobile_border":"","side_bottom__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_border_radius":"","side_bottom__mobile_sticky":"1","header_full_width":"0","header_on_bg":"1","header_sticky":"","header_sticky_appearance_style":"classic","header_sticky_appearance_from_top":"auto","header_sticky_appearance_number":{"height":"300"},"header_sticky_shadow":"1","tablet_header_on_bg":"1","tablet_header_sticky":"","mobile_header_on_bg":"1","mobile_header_sticky":""}';


    $header_presets['header_preset_3'] = '{"gt3_header_builder_id":{"all_item":{"layout":"all","title":"All Item","content":{"placebo":"placebo","login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"empty_space1":{"title":"gt3_flag%E2%86%90%E2%86%92"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space3":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space2":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space4":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space5":{"title":"gt3_flag%E2%86%90%E2%86%92"}}},"top_left":{"layout":"one-thirds","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center":{"layout":"one-thirds","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right":{"layout":"one-thirds","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left":{"layout":"one-thirds clear-item","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"}}},"middle_center":{"layout":"one-thirds","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo","menu":{"title":"gt3_flagMenu","has_settings":"1"}}},"middle_right":{"layout":"one-thirds","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo","search":{"title":"gt3_flagSearch"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"}}},"bottom_left":{"layout":"one-thirds clear-item","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center":{"layout":"one-thirds","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right":{"layout":"one-thirds","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}},"all_item__tablet":{"layout":"all","extra_class":"tablet","title":"All Item","content":{"placebo":"placebo","login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space1":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space2":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space3":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space4":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space5":{"title":"gt3_flag%E2%86%90%E2%86%92"}}},"top_left__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left__tablet":{"layout":"one-thirds clear-item","extra_class":"tablet","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"}}},"middle_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo","menu":{"title":"gt3_flagMenu","has_settings":"1"}}},"middle_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo","search":{"title":"gt3_flagSearch"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"}}},"bottom_left__tablet":{"layout":"one-thirds clear-item","extra_class":"tablet","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}},"all_item__mobile":{"layout":"all","extra_class":"mobile","title":"All Item","content":{"placebo":"placebo","login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space1":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space2":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space3":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space4":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space5":{"title":"gt3_flag%E2%86%90%E2%86%92"},"search":{"title":"gt3_flagSearch"}}},"top_left__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left__mobile":{"layout":"one-thirds clear-item","extra_class":"mobile","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"}}},"middle_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo","menu":{"title":"gt3_flagMenu","has_settings":"1"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"}}},"bottom_left__mobile":{"layout":"one-thirds clear-item","extra_class":"mobile","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}}},"header_logo":{"url":"https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/08/logo_retinablack.png","id":"1456","height":"96","width":"298","thumbnail":"https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/08/logo_retinablack-150x96.png","title":"logo_retina(black)","caption":"","alt":"","description":""},"logo_height_custom":"1","logo_height":{"height":"48"},"logo_max_height":"","sticky_logo_height":{"height":""},"logo_sticky":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_tablet":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_teblet_width":{"width":""},"logo_mobile":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_mobile_width":{"width":"149"},"menu_select":"main-menu","menu_ative_top_line":"","sub_menu_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"sub_menu_color":"#3b3663","sub_menu_color_hover":"#f47514","burger_sidebar_select":"sidebar_header-sidebar","top_left-align":"left","top_center-align":"center","top_right-align":"right","middle_left-align":"left","middle_center-align":"center","middle_right-align":"right","bottom_left-align":"left","bottom_center-align":"center","bottom_right-align":"right","top_left__tablet-align":"left","top_center__tablet-align":"center","top_right__tablet-align":"right","middle_left__tablet-align":"left","middle_center__tablet-align":"center","middle_right__tablet-align":"right","bottom_left__tablet-align":"left","bottom_center__tablet-align":"center","bottom_right__tablet-align":"right","top_left__mobile-align":"left","top_center__mobile-align":"center","top_right__mobile-align":"right","middle_left__mobile-align":"left","middle_center__mobile-align":"center","middle_right__mobile-align":"right","bottom_left__mobile-align":"left","bottom_center__mobile-align":"center","bottom_right__mobile-align":"right","text1_editor":"<p><a class=\"button alignment_center\" href=\"#\">Get in touch</a></p>","text2_editor":"","text3_editor":"","text4_editor":"<p><a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-linkedin\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-twitter\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-facebook\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc;\" rel=\"noopener\"><i class=\"fa fa-instagram\"> </i></a></p>","text5_editor":"","text6_editor":"","delimiter1_height":{"height":"1em","units":"em"},"delimiter1_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter2_height":{"height":"1em","units":"em"},"delimiter2_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter3_height":{"height":"1em","units":"em"},"delimiter3_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter4_height":{"height":"1em","units":"em"},"delimiter4_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter5_height":{"height":"1em","units":"em"},"delimiter5_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter6_height":{"height":"1em","units":"em"},"delimiter6_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_top_background":{"color":"#2b3034","alpha":"1","rgba":"rgba(43,48,52,1)"},"side_top_background2":{"color":"","alpha":"","rgba":""},"side_top_color":"#a3adb8","side_top_color_hover":"#ffffff","side_top_height":{"height":"42"},"side_top_spacing":{"padding-right":"","padding-left":""},"side_top_border":"1","side_top_border_color":{"color":"#ffffff","alpha":"0.2","rgba":"rgba(255,255,255,0.2)"},"side_top_border_radius":"","side_top_sticky":"1","side_top_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_top_color_sticky":"#222328","side_top_color_hover_sticky":"#232325","side_top_height_sticky":{"height":"58"},"side_top_spacing_sticky":{"padding-right":"","padding-left":""},"side_middle_background":{"color":"#2b3034","alpha":"0","rgba":"rgba(43,48,52,0)"},"side_middle_background2":{"color":"#ffffff","alpha":"0","rgba":"rgba(255,255,255,0)"},"side_middle_color":"#3b3663","side_middle_color_hover":"#f47514","side_middle_height":{"height":"108"},"side_middle_spacing":{"padding-right":"0","padding-left":"0"},"side_middle_border":"","side_middle_border_color":{"color":"#F3F4F4","alpha":"1","rgba":"rgba(243,244,244,1)"},"side_middle_border_radius":"","side_middle_sticky":"1","side_middle_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_middle_color_sticky":"#222328","side_middle_color_hover_sticky":"#232325","side_middle_height_sticky":{"height":"58"},"side_middle_spacing_sticky":{"padding-right":"","padding-left":""},"side_bottom_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_bottom_background2":{"color":"#ffffff","alpha":"0","rgba":"rgba(255,255,255,0)"},"side_bottom_color":"#232325","side_bottom_color_hover":"#232325","side_bottom_height":{"height":"100"},"side_bottom_spacing":{"padding-right":"","padding-left":""},"side_bottom_border":"","side_bottom_border_color":{"color":"#F3F4F4","alpha":"1","rgba":"rgba(243,244,244,1)"},"side_bottom_border_radius":"","side_bottom_sticky":"1","side_bottom_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_bottom_color_sticky":"#222328","side_bottom_color_hover_sticky":"#232325","side_bottom_height_sticky":{"height":"58"},"side_bottom_spacing_sticky":{"padding-right":"","padding-left":""},"side_top__tablet_custom":"","side_top__tablet_background":{"color":"","alpha":"1","rgba":""},"side_top__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_top__tablet_color":"","side_top__tablet_color_hover":"","side_top__tablet_height":{"height":""},"side_top__tablet_spacing":{"padding-right":"","padding-left":""},"side_top__tablet_border":"","side_top__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_top__tablet_border_radius":"","side_top__tablet_sticky":"1","side_middle__tablet_custom":"1","side_middle__tablet_background":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_color":"","side_middle__tablet_color_hover":"","side_middle__tablet_height":{"height":"80"},"side_middle__tablet_spacing":{"padding-right":"","padding-left":""},"side_middle__tablet_border":"","side_middle__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_border_radius":"","side_middle__tablet_sticky":"1","side_bottom__tablet_custom":"","side_bottom__tablet_background":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_color":"","side_bottom__tablet_color_hover":"","side_bottom__tablet_height":{"height":""},"side_bottom__tablet_spacing":{"padding-right":"","padding-left":""},"side_bottom__tablet_border":"","side_bottom__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_border_radius":"","side_bottom__tablet_sticky":"1","side_top__mobile_custom":"","side_top__mobile_background":{"color":"","alpha":"1","rgba":""},"side_top__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_top__mobile_color":"","side_top__mobile_color_hover":"","side_top__mobile_height":{"height":""},"side_top__mobile_spacing":{"padding-right":"","padding-left":""},"side_top__mobile_border":"","side_top__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_top__mobile_border_radius":"","side_top__mobile_sticky":"1","side_middle__mobile_custom":"1","side_middle__mobile_background":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_color":"","side_middle__mobile_color_hover":"","side_middle__mobile_height":{"height":""},"side_middle__mobile_spacing":{"padding-right":"0","padding-left":"0"},"side_middle__mobile_border":"","side_middle__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_border_radius":"","side_middle__mobile_sticky":"1","side_bottom__mobile_custom":"","side_bottom__mobile_background":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_color":"","side_bottom__mobile_color_hover":"","side_bottom__mobile_height":{"height":""},"side_bottom__mobile_spacing":{"padding-right":"","padding-left":""},"side_bottom__mobile_border":"","side_bottom__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_border_radius":"","side_bottom__mobile_sticky":"1","header_full_width":"0","header_on_bg":"1","header_sticky":"1","header_sticky_appearance_style":"classic","header_sticky_appearance_from_top":"auto","header_sticky_appearance_number":{"height":"300"},"header_sticky_shadow":"1","tablet_header_on_bg":"0","tablet_header_sticky":"1","mobile_header_on_bg":"0","mobile_header_sticky":""}';


    $header_presets['header_preset_4'] = '{"gt3_header_builder_id":{"all_item":{"layout":"all","title":"All Item","content":{"placebo":"placebo","login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"empty_space1":{"title":"gt3_flag%E2%86%90%E2%86%92"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space4":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space5":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space3":{"title":"gt3_flag%E2%86%90%E2%86%92"}}},"top_left":{"layout":"one-thirds","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center":{"layout":"one-thirds","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right":{"layout":"one-thirds","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left":{"layout":"one-thirds clear-item","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"},"empty_space2":{"title":"gt3_flag%E2%86%90%E2%86%92"},"menu":{"title":"gt3_flagMenu","has_settings":"1"}}},"middle_center":{"layout":"one-thirds","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right":{"layout":"one-thirds","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo","text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"search":{"title":"gt3_flagSearch"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"}}},"bottom_left":{"layout":"one-thirds clear-item","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center":{"layout":"one-thirds","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right":{"layout":"one-thirds","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}},"all_item__tablet":{"layout":"all","extra_class":"tablet","title":"All Item","content":{"placebo":"placebo","login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space1":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space4":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space2":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space3":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space5":{"title":"gt3_flag%E2%86%90%E2%86%92"}}},"top_left__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left__tablet":{"layout":"one-thirds clear-item","extra_class":"tablet","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"},"menu":{"title":"gt3_flagMenu","has_settings":"1"}}},"middle_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo","text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"search":{"title":"gt3_flagSearch"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"}}},"bottom_left__tablet":{"layout":"one-thirds clear-item","extra_class":"tablet","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}},"all_item__mobile":{"layout":"all","extra_class":"mobile","title":"All Item","content":{"placebo":"placebo","login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space1":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space2":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space3":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space4":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space5":{"title":"gt3_flag%E2%86%90%E2%86%92"},"search":{"title":"gt3_flagSearch"}}},"top_left__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left__mobile":{"layout":"one-thirds clear-item","extra_class":"mobile","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"}}},"middle_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo","menu":{"title":"gt3_flagMenu","has_settings":"1"}}},"bottom_left__mobile":{"layout":"one-thirds clear-item","extra_class":"mobile","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}}},"header_logo":{"url":"https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/08/logo_retinablack.png","id":"1456","height":"96","width":"298","thumbnail":"https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/08/logo_retinablack-150x96.png","title":"logo_retina(black)","caption":"","alt":"","description":""},"logo_height_custom":"1","logo_height":{"height":"48"},"logo_max_height":"","sticky_logo_height":{"height":""},"logo_sticky":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_tablet":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_teblet_width":{"width":""},"logo_mobile":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_mobile_width":{"width":"149"},"menu_select":"main-menu","menu_ative_top_line":"","sub_menu_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"sub_menu_color":"#3b3663","sub_menu_color_hover":"#f47514","burger_sidebar_select":"sidebar_header-sidebar","top_left-align":"left","top_center-align":"center","top_right-align":"right","middle_left-align":"left","middle_center-align":"center","middle_right-align":"right","bottom_left-align":"left","bottom_center-align":"center","bottom_right-align":"right","top_left__tablet-align":"left","top_center__tablet-align":"center","top_right__tablet-align":"right","middle_left__tablet-align":"left","middle_center__tablet-align":"center","middle_right__tablet-align":"right","bottom_left__tablet-align":"left","bottom_center__tablet-align":"center","bottom_right__tablet-align":"right","top_left__mobile-align":"left","top_center__mobile-align":"center","top_right__mobile-align":"right","middle_left__mobile-align":"left","middle_center__mobile-align":"center","middle_right__mobile-align":"right","bottom_left__mobile-align":"left","bottom_center__mobile-align":"center","bottom_right__mobile-align":"right","text1_editor":"<p><a class=\"button alignment_center\" href=\"#\">Get in touch</a></p>","text2_editor":"<p><a href=\"tel:+88002534236\" style=\"font-size: 14px;\">Call Us: <span style=\"font-weight: 500; font-size: 16px;\" class=\"gt3_font-weight\">8 800 2563 123</span></a></p>","text3_editor":"","text4_editor":"<p><a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-linkedin\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-twitter\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-facebook\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc;\" rel=\"noopener\"><i class=\"fa fa-instagram\"> </i></a></p>","text5_editor":"","text6_editor":"","delimiter1_height":{"height":"1em","units":"em"},"delimiter1_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter2_height":{"height":"1em","units":"em"},"delimiter2_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter3_height":{"height":"1em","units":"em"},"delimiter3_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter4_height":{"height":"1em","units":"em"},"delimiter4_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter5_height":{"height":"1em","units":"em"},"delimiter5_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter6_height":{"height":"1em","units":"em"},"delimiter6_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_top_background":{"color":"#2b3034","alpha":"1","rgba":"rgba(43,48,52,1)"},"side_top_background2":{"color":"","alpha":"","rgba":""},"side_top_color":"#a3adb8","side_top_color_hover":"#ffffff","side_top_height":{"height":"42"},"side_top_spacing":{"padding-right":"","padding-left":""},"side_top_border":"1","side_top_border_color":{"color":"#ffffff","alpha":"0.2","rgba":"rgba(255,255,255,0.2)"},"side_top_border_radius":"","side_top_sticky":"1","side_top_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_top_color_sticky":"#222328","side_top_color_hover_sticky":"#232325","side_top_height_sticky":{"height":"58"},"side_top_spacing_sticky":{"padding-right":"","padding-left":""},"side_middle_background":{"color":"#2b3034","alpha":"0","rgba":"rgba(43,48,52,0)"},"side_middle_background2":{"color":"#ffffff","alpha":"0","rgba":"rgba(255,255,255,0)"},"side_middle_color":"#3b3663","side_middle_color_hover":"#f47514","side_middle_height":{"height":"108"},"side_middle_spacing":{"padding-right":"50px","padding-left":"50px"},"side_middle_border":"","side_middle_border_color":{"color":"#F3F4F4","alpha":"1","rgba":"rgba(243,244,244,1)"},"side_middle_border_radius":"","side_middle_sticky":"1","side_middle_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_middle_color_sticky":"#222328","side_middle_color_hover_sticky":"#232325","side_middle_height_sticky":{"height":"58"},"side_middle_spacing_sticky":{"padding-right":"","padding-left":""},"side_bottom_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_bottom_background2":{"color":"#ffffff","alpha":"0","rgba":"rgba(255,255,255,0)"},"side_bottom_color":"#232325","side_bottom_color_hover":"#232325","side_bottom_height":{"height":"100"},"side_bottom_spacing":{"padding-right":"","padding-left":""},"side_bottom_border":"","side_bottom_border_color":{"color":"#F3F4F4","alpha":"1","rgba":"rgba(243,244,244,1)"},"side_bottom_border_radius":"","side_bottom_sticky":"1","side_bottom_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_bottom_color_sticky":"#222328","side_bottom_color_hover_sticky":"#232325","side_bottom_height_sticky":{"height":"58"},"side_bottom_spacing_sticky":{"padding-right":"","padding-left":""},"side_top__tablet_custom":"","side_top__tablet_background":{"color":"","alpha":"1","rgba":""},"side_top__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_top__tablet_color":"","side_top__tablet_color_hover":"","side_top__tablet_height":{"height":""},"side_top__tablet_spacing":{"padding-right":"","padding-left":""},"side_top__tablet_border":"","side_top__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_top__tablet_border_radius":"","side_top__tablet_sticky":"1","side_middle__tablet_custom":"","side_middle__tablet_background":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_color":"","side_middle__tablet_color_hover":"","side_middle__tablet_height":{"height":""},"side_middle__tablet_spacing":{"padding-right":"","padding-left":""},"side_middle__tablet_border":"","side_middle__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_border_radius":"","side_middle__tablet_sticky":"1","side_bottom__tablet_custom":"","side_bottom__tablet_background":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_color":"","side_bottom__tablet_color_hover":"","side_bottom__tablet_height":{"height":""},"side_bottom__tablet_spacing":{"padding-right":"","padding-left":""},"side_bottom__tablet_border":"","side_bottom__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_border_radius":"","side_bottom__tablet_sticky":"1","side_top__mobile_custom":"","side_top__mobile_background":{"color":"","alpha":"1","rgba":""},"side_top__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_top__mobile_color":"","side_top__mobile_color_hover":"","side_top__mobile_height":{"height":""},"side_top__mobile_spacing":{"padding-right":"","padding-left":""},"side_top__mobile_border":"","side_top__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_top__mobile_border_radius":"","side_top__mobile_sticky":"1","side_middle__mobile_custom":"1","side_middle__mobile_background":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_color":"","side_middle__mobile_color_hover":"","side_middle__mobile_height":{"height":""},"side_middle__mobile_spacing":{"padding-right":"10px","padding-left":"10px"},"side_middle__mobile_border":"","side_middle__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_border_radius":"","side_middle__mobile_sticky":"1","side_bottom__mobile_custom":"","side_bottom__mobile_background":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_color":"","side_bottom__mobile_color_hover":"","side_bottom__mobile_height":{"height":""},"side_bottom__mobile_spacing":{"padding-right":"","padding-left":""},"side_bottom__mobile_border":"","side_bottom__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_border_radius":"","side_bottom__mobile_sticky":"1","header_full_width":"1","header_on_bg":"1","header_sticky":"","header_sticky_appearance_style":"classic","header_sticky_appearance_from_top":"auto","header_sticky_appearance_number":{"height":"300"},"header_sticky_shadow":"1","tablet_header_on_bg":"1","tablet_header_sticky":"","mobile_header_on_bg":"1","mobile_header_sticky":""}';


    $header_presets['header_preset_5'] = '{"gt3_header_builder_id":{"all_item":{"layout":"all","title":"All Item","content":{"placebo":"placebo","search":{"title":"gt3_flagSearch"},"login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"menu":{"title":"gt3_flagMenu","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space1":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space2":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space3":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space4":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"logo":{"title":"gt3_flagLogo","has_settings":"1"},"empty_space5":{"title":"gt3_flag%26%238592%3B%26%238594%3B"}}},"top_left":{"layout":"one-thirds","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center":{"layout":"one-thirds","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right":{"layout":"one-thirds","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left":{"layout":"one-thirds clear-item","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo"}},"middle_center":{"layout":"one-thirds","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right":{"layout":"one-thirds","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo"}},"bottom_left":{"layout":"one-thirds clear-item","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center":{"layout":"one-thirds","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right":{"layout":"one-thirds","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}},"all_item__tablet":{"layout":"all","extra_class":"tablet","title":"All Item","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"},"menu":{"title":"gt3_flagMenu","has_settings":"1"},"search":{"title":"gt3_flagSearch"},"login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space1":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space2":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space3":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space4":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space5":{"title":"gt3_flag%26%238592%3B%26%238594%3B"}}},"top_left__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left__tablet":{"layout":"one-thirds clear-item","extra_class":"tablet","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo"}},"middle_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo"}},"bottom_left__tablet":{"layout":"one-thirds clear-item","extra_class":"tablet","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}},"all_item__mobile":{"layout":"all","extra_class":"mobile","title":"All Item","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"},"menu":{"title":"gt3_flagMenu","has_settings":"1"},"search":{"title":"gt3_flagSearch"},"login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space1":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space2":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space3":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space4":{"title":"gt3_flag%26%238592%3B%26%238594%3B"},"empty_space5":{"title":"gt3_flag%26%238592%3B%26%238594%3B"}}},"top_left__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left__mobile":{"layout":"one-thirds clear-item","extra_class":"mobile","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo"}},"middle_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo"}},"bottom_left__mobile":{"layout":"one-thirds clear-item","extra_class":"mobile","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}}},"header_logo":{"url":"https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/08/logo_retinablack.png","id":"1456","height":"96","width":"298","thumbnail":"https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/08/logo_retinablack-150x96.png","title":"logo_retina(black)","caption":"","alt":"","description":""},"logo_height_custom":"1","logo_height":{"height":"48"},"logo_max_height":"","sticky_logo_height":{"height":""},"logo_sticky":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_tablet":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_teblet_width":{"width":""},"logo_mobile":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_mobile_width":{"width":""},"menu_select":"main-menu","menu_ative_top_line":"","sub_menu_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"sub_menu_color":"#3b3663","sub_menu_color_hover":"#f47514","burger_sidebar_select":"sidebar_header-sidebar","top_left-align":"left","top_center-align":"center","top_right-align":"right","middle_left-align":"left","middle_center-align":"left","middle_right-align":"right","bottom_left-align":"left","bottom_center-align":"center","bottom_right-align":"right","top_left__tablet-align":"left","top_center__tablet-align":"center","top_right__tablet-align":"right","middle_left__tablet-align":"left","middle_center__tablet-align":"center","middle_right__tablet-align":"right","bottom_left__tablet-align":"left","bottom_center__tablet-align":"center","bottom_right__tablet-align":"right","top_left__mobile-align":"left","top_center__mobile-align":"center","top_right__mobile-align":"right","middle_left__mobile-align":"left","middle_center__mobile-align":"center","middle_right__mobile-align":"right","bottom_left__mobile-align":"left","bottom_center__mobile-align":"center","bottom_right__mobile-align":"right","text1_editor":"<p><a class=\"gt3_icon_link gt3_custom_color\" href=\"#\" target=\"_blank\" data-color=\"#c1bfcc\" data-hover-color=\"#4296c3\" style=\"font-size: 14px; color: #c1bfcc; margin-right: 8px;\" rel=\"noopener\"><i class=\"fa fa-twitter\"> </i></a> <a class=\"gt3_icon_link gt3_custom_color\" href=\"#\" target=\"_blank\" data-color=\"#c1bfcc\" data-hover-color=\"#5f6d99\" style=\"font-size: 14px; color: #c1bfcc; margin-right: 8px;\" rel=\"noopener\"><i class=\"fa fa-facebook\"> </i></a> <a class=\"gt3_icon_link gt3_custom_color\" href=\"#\" target=\"_blank\" data-color=\"#c1bfcc\" data-hover-color=\"#b9666d\" style=\"font-size: 14px; color: #c1bfcc; margin-right: 8px;\" rel=\"noopener\"><i class=\"fa fa-google-plus\"> </i></a> <a class=\"gt3_icon_link gt3_custom_color\" href=\"#\" target=\"_blank\" data-color=\"#c1bfcc\" data-hover-color=\"#a75061\" style=\"font-size: 14px; color: #c1bfcc; margin-right: 8px;\" rel=\"noopener\"><i class=\"fa fa-pinterest-p\"> </i></a> <a class=\"gt3_icon_link gt3_custom_color\" href=\"#\" target=\"_blank\" data-color=\"#c1bfcc\" data-hover-color=\"#5991b6\" style=\"font-size: 14px; color: #c1bfcc;\" rel=\"noopener\"><i class=\"fa fa-linkedin\"> </i></a> </p>","text2_editor":"<p><a href=\"tel:+88002534236\"></a><a class=\"gt3_icon_link gt3_custom_color\" href=\"#\" target=\"_blank\" data-color=\"#5747e4\" data-hover-color=\"#5747e4\" style=\"font-size: 16px; color: #5747e4;\" rel=\"noopener\"><i class=\"fa fa-phone\"> </i></a><a href=\"tel:+88002534236\"><span class=\"gt3_font-weight\">8 800 2563 123</span></a></p>","text3_editor":"<p><a class=\"gt3_icon_link gt3_custom_color\" href=\"#\" target=\"_blank\" data-color=\"#5747e4\" data-hover-color=\"#5747e4\" style=\"font-size: 16px; color: #5747e4;\" rel=\"noopener\"><i class=\"fa fa-envelope\"> </i></a> <a href=\"mailto:email@yoursite.com\">email@yoursite.com</a></p>","text4_editor":"<p><a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-linkedin\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-twitter\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-facebook\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc;\" rel=\"noopener\"><i class=\"fa fa-instagram\"> </i></a></p>","text5_editor":"","text6_editor":"","delimiter1_height":{"height":"1em","units":"em"},"delimiter1_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter2_height":{"height":"1em","units":"em"},"delimiter2_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter3_height":{"height":"1em","units":"em"},"delimiter3_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter4_height":{"height":"1em","units":"em"},"delimiter4_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter5_height":{"height":"1em","units":"em"},"delimiter5_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter6_height":{"height":"1em","units":"em"},"delimiter6_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_top_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_top_background2":{"color":"","alpha":"","rgba":""},"side_top_color":"#696687","side_top_color_hover":"#5747e4","side_top_height":{"height":"46"},"side_top_spacing":{"padding-right":"","padding-left":""},"side_top_border":"1","side_top_border_color":{"color":"#696687","alpha":"0.1","rgba":"rgba(105,102,135,0.1)"},"side_top_border_radius":"","side_top_sticky":"1","side_top_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_top_color_sticky":"#222328","side_top_color_hover_sticky":"#232325","side_top_height_sticky":{"height":"58"},"side_top_spacing_sticky":{"padding-right":"","padding-left":""},"side_middle_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_middle_background2":{"color":"#ffffff","alpha":"0","rgba":"rgba(255,255,255,0)"},"side_middle_color":"#3b3663","side_middle_color_hover":"#f47514","side_middle_height":{"height":"108"},"side_middle_spacing":{"padding-right":"","padding-left":""},"side_middle_border":"","side_middle_border_color":{"color":"#F3F4F4","alpha":"1","rgba":"rgba(243,244,244,1)"},"side_middle_border_radius":"","side_middle_sticky":"1","side_middle_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_middle_color_sticky":"#222328","side_middle_color_hover_sticky":"#232325","side_middle_height_sticky":{"height":"58"},"side_middle_spacing_sticky":{"padding-right":"","padding-left":""},"side_bottom_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_bottom_background2":{"color":"#ffffff","alpha":"0","rgba":"rgba(255,255,255,0)"},"side_bottom_color":"#232325","side_bottom_color_hover":"#232325","side_bottom_height":{"height":"100"},"side_bottom_spacing":{"padding-right":"","padding-left":""},"side_bottom_border":"","side_bottom_border_color":{"color":"#F3F4F4","alpha":"1","rgba":"rgba(243,244,244,1)"},"side_bottom_border_radius":"","side_bottom_sticky":"1","side_bottom_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_bottom_color_sticky":"#222328","side_bottom_color_hover_sticky":"#232325","side_bottom_height_sticky":{"height":"58"},"side_bottom_spacing_sticky":{"padding-right":"","padding-left":""},"side_top__tablet_custom":"","side_top__tablet_background":{"color":"","alpha":"1","rgba":""},"side_top__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_top__tablet_color":"","side_top__tablet_color_hover":"","side_top__tablet_height":{"height":""},"side_top__tablet_spacing":{"padding-right":"","padding-left":""},"side_top__tablet_border":"","side_top__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_top__tablet_border_radius":"","side_top__tablet_sticky":"1","side_middle__tablet_custom":"","side_middle__tablet_background":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_color":"","side_middle__tablet_color_hover":"","side_middle__tablet_height":{"height":""},"side_middle__tablet_spacing":{"padding-right":"","padding-left":""},"side_middle__tablet_border":"","side_middle__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_border_radius":"","side_middle__tablet_sticky":"1","side_bottom__tablet_custom":"","side_bottom__tablet_background":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_color":"","side_bottom__tablet_color_hover":"","side_bottom__tablet_height":{"height":""},"side_bottom__tablet_spacing":{"padding-right":"","padding-left":""},"side_bottom__tablet_border":"","side_bottom__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_border_radius":"","side_bottom__tablet_sticky":"1","side_top__mobile_custom":"","side_top__mobile_background":{"color":"","alpha":"1","rgba":""},"side_top__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_top__mobile_color":"","side_top__mobile_color_hover":"","side_top__mobile_height":{"height":""},"side_top__mobile_spacing":{"padding-right":"","padding-left":""},"side_top__mobile_border":"","side_top__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_top__mobile_border_radius":"","side_top__mobile_sticky":"1","side_middle__mobile_custom":"1","side_middle__mobile_background":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_color":"","side_middle__mobile_color_hover":"","side_middle__mobile_height":{"height":""},"side_middle__mobile_spacing":{"padding-right":"8px","padding-left":"8px"},"side_middle__mobile_border":"","side_middle__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_border_radius":"","side_middle__mobile_sticky":"1","side_bottom__mobile_custom":"","side_bottom__mobile_background":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_color":"","side_bottom__mobile_color_hover":"","side_bottom__mobile_height":{"height":""},"side_bottom__mobile_spacing":{"padding-right":"","padding-left":""},"side_bottom__mobile_border":"","side_bottom__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_border_radius":"","side_bottom__mobile_sticky":"1","header_full_width":"1","header_on_bg":"1","header_sticky":"","header_sticky_appearance_style":"classic","header_sticky_appearance_from_top":"auto","header_sticky_appearance_number":{"height":"300"},"header_sticky_shadow":"1","tablet_header_on_bg":"0","tablet_header_sticky":"","mobile_header_on_bg":"0","mobile_header_sticky":""}';


    $header_presets['header_preset_6'] = '{"gt3_header_builder_id":{"all_item":{"layout":"all","title":"All Item","content":{"placebo":"placebo","login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"empty_space1":{"title":"gt3_flag%E2%86%90%E2%86%92"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space4":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space5":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space3":{"title":"gt3_flag%E2%86%90%E2%86%92"}}},"top_left":{"layout":"one-thirds","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center":{"layout":"one-thirds","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right":{"layout":"one-thirds","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left":{"layout":"one-thirds clear-item","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"},"empty_space2":{"title":"gt3_flag%E2%86%90%E2%86%92"},"menu":{"title":"gt3_flagMenu","has_settings":"1"}}},"middle_center":{"layout":"one-thirds","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right":{"layout":"one-thirds","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo","text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"search":{"title":"gt3_flagSearch"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"}}},"bottom_left":{"layout":"one-thirds clear-item","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center":{"layout":"one-thirds","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right":{"layout":"one-thirds","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}},"all_item__tablet":{"layout":"all","extra_class":"tablet","title":"All Item","content":{"placebo":"placebo","login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space1":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space4":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space2":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space3":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space5":{"title":"gt3_flag%E2%86%90%E2%86%92"}}},"top_left__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left__tablet":{"layout":"one-thirds clear-item","extra_class":"tablet","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"},"menu":{"title":"gt3_flagMenu","has_settings":"1"}}},"middle_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo","text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"search":{"title":"gt3_flagSearch"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"}}},"bottom_left__tablet":{"layout":"one-thirds clear-item","extra_class":"tablet","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right__tablet":{"layout":"one-thirds","extra_class":"tablet","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}},"all_item__mobile":{"layout":"all","extra_class":"mobile","title":"All Item","content":{"placebo":"placebo","login":{"title":"gt3_flagLogin"},"cart":{"title":"gt3_flagCart"},"burger_sidebar":{"title":"gt3_flagBurger+Sidebar","has_settings":"1"},"text1":{"title":"gt3_flagText%2FHTML+1","has_settings":"1"},"text2":{"title":"gt3_flagText%2FHTML+2","has_settings":"1"},"text3":{"title":"gt3_flagText%2FHTML+3","has_settings":"1"},"text4":{"title":"gt3_flagText%2FHTML+4","has_settings":"1"},"text5":{"title":"gt3_flagText%2FHTML+5","has_settings":"1"},"text6":{"title":"gt3_flagText%2FHTML+6","has_settings":"1"},"delimiter1":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter2":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter3":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter4":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter5":{"title":"gt3_flag%7C","has_settings":"1"},"delimiter6":{"title":"gt3_flag%7C","has_settings":"1"},"empty_space1":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space2":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space3":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space4":{"title":"gt3_flag%E2%86%90%E2%86%92"},"empty_space5":{"title":"gt3_flag%E2%86%90%E2%86%92"},"search":{"title":"gt3_flagSearch"}}},"top_left__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Left","has_settings":"1","content":{"placebo":"placebo"}},"top_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Center","has_settings":"1","content":{"placebo":"placebo"}},"top_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Top Right","has_settings":"1","content":{"placebo":"placebo"}},"middle_left__mobile":{"layout":"one-thirds clear-item","extra_class":"mobile","title":"Middle Left","has_settings":"1","content":{"placebo":"placebo","logo":{"title":"gt3_flagLogo","has_settings":"1"}}},"middle_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Middle Center","has_settings":"1","content":{"placebo":"placebo"}},"middle_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Middle Right","has_settings":"1","content":{"placebo":"placebo","menu":{"title":"gt3_flagMenu","has_settings":"1"}}},"bottom_left__mobile":{"layout":"one-thirds clear-item","extra_class":"mobile","title":"Bottom Left","has_settings":"1","content":{"placebo":"placebo"}},"bottom_center__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Bottom Center","has_settings":"1","content":{"placebo":"placebo"}},"bottom_right__mobile":{"layout":"one-thirds","extra_class":"mobile","title":"Bottom Right","has_settings":"1","content":{"placebo":"placebo"}}},"header_logo":{"url":"https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/12/logo_retina_white.png","id":"3298","height":"96","width":"298","thumbnail":"https://livewp.site/wp/md/ewebot/wp-content/uploads/sites/64/2019/12/logo_retina_white-150x96.png","title":"logo_retina_white","caption":"","alt":"","description":""},"logo_height_custom":"1","logo_height":{"height":"48"},"logo_max_height":"","sticky_logo_height":{"height":""},"logo_sticky":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_tablet":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_teblet_width":{"width":""},"logo_mobile":{"url":"","id":"","height":"","width":"","thumbnail":"","title":"","caption":"","alt":"","description":""},"logo_mobile_width":{"width":"149"},"menu_select":"main-menu","menu_ative_top_line":"","sub_menu_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"sub_menu_color":"#3b3663","sub_menu_color_hover":"#f47514","burger_sidebar_select":"sidebar_header-sidebar","top_left-align":"left","top_center-align":"center","top_right-align":"right","middle_left-align":"left","middle_center-align":"center","middle_right-align":"right","bottom_left-align":"left","bottom_center-align":"center","bottom_right-align":"right","top_left__tablet-align":"left","top_center__tablet-align":"center","top_right__tablet-align":"right","middle_left__tablet-align":"left","middle_center__tablet-align":"center","middle_right__tablet-align":"right","bottom_left__tablet-align":"left","bottom_center__tablet-align":"center","bottom_right__tablet-align":"right","top_left__mobile-align":"left","top_center__mobile-align":"center","top_right__mobile-align":"right","middle_left__mobile-align":"left","middle_center__mobile-align":"center","middle_right__mobile-align":"right","bottom_left__mobile-align":"left","bottom_center__mobile-align":"center","bottom_right__mobile-align":"right","text1_editor":"<p><a class=\"button alignment_center\" href=\"#\">Get in touch</a></p>","text2_editor":"<p><a href=\"tel:+88002534236\" style=\"font-size: 14px;\">Call Us: <span style=\"font-weight: 500; font-size: 16px;\" class=\"gt3_font-weight\">8 800 2563 123</span></a></p>","text3_editor":"","text4_editor":"<p><a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-linkedin\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-twitter\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc; margin-right: 20px;\" rel=\"noopener\"><i class=\"fa fa-facebook\"> </i></a> <a class=\"gt3_icon_link\" href=\"#\" target=\"_blank\" style=\"font-size: 18px; color: #bcbcbc;\" rel=\"noopener\"><i class=\"fa fa-instagram\"> </i></a></p>","text5_editor":"","text6_editor":"","delimiter1_height":{"height":"1em","units":"em"},"delimiter1_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter2_height":{"height":"1em","units":"em"},"delimiter2_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter3_height":{"height":"1em","units":"em"},"delimiter3_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter4_height":{"height":"1em","units":"em"},"delimiter4_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter5_height":{"height":"1em","units":"em"},"delimiter5_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"delimiter6_height":{"height":"1em","units":"em"},"delimiter6_border_color":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_top_background":{"color":"#2b3034","alpha":"1","rgba":"rgba(43,48,52,1)"},"side_top_background2":{"color":"","alpha":"","rgba":""},"side_top_color":"#a3adb8","side_top_color_hover":"#ffffff","side_top_height":{"height":"42"},"side_top_spacing":{"padding-right":"","padding-left":""},"side_top_border":"1","side_top_border_color":{"color":"#ffffff","alpha":"0.2","rgba":"rgba(255,255,255,0.2)"},"side_top_border_radius":"","side_top_sticky":"1","side_top_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_top_color_sticky":"#222328","side_top_color_hover_sticky":"#232325","side_top_height_sticky":{"height":"58"},"side_top_spacing_sticky":{"padding-right":"","padding-left":""},"side_middle_background":{"color":"#2b3034","alpha":"0","rgba":"rgba(43,48,52,0)"},"side_middle_background2":{"color":"#ffffff","alpha":"0","rgba":"rgba(255,255,255,0)"},"side_middle_color":"#ffffff","side_middle_color_hover":"#f47514","side_middle_height":{"height":"108"},"side_middle_spacing":{"padding-right":"50px","padding-left":"50px"},"side_middle_border":"","side_middle_border_color":{"color":"#F3F4F4","alpha":"1","rgba":"rgba(243,244,244,1)"},"side_middle_border_radius":"","side_middle_sticky":"1","side_middle_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_middle_color_sticky":"#222328","side_middle_color_hover_sticky":"#232325","side_middle_height_sticky":{"height":"58"},"side_middle_spacing_sticky":{"padding-right":"","padding-left":""},"side_bottom_background":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_bottom_background2":{"color":"#ffffff","alpha":"0","rgba":"rgba(255,255,255,0)"},"side_bottom_color":"#232325","side_bottom_color_hover":"#232325","side_bottom_height":{"height":"100"},"side_bottom_spacing":{"padding-right":"","padding-left":""},"side_bottom_border":"","side_bottom_border_color":{"color":"#F3F4F4","alpha":"1","rgba":"rgba(243,244,244,1)"},"side_bottom_border_radius":"","side_bottom_sticky":"1","side_bottom_background_sticky":{"color":"#ffffff","alpha":"1","rgba":"rgba(255,255,255,1)"},"side_bottom_color_sticky":"#222328","side_bottom_color_hover_sticky":"#232325","side_bottom_height_sticky":{"height":"58"},"side_bottom_spacing_sticky":{"padding-right":"","padding-left":""},"side_top__tablet_custom":"","side_top__tablet_background":{"color":"","alpha":"1","rgba":""},"side_top__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_top__tablet_color":"","side_top__tablet_color_hover":"","side_top__tablet_height":{"height":""},"side_top__tablet_spacing":{"padding-right":"","padding-left":""},"side_top__tablet_border":"","side_top__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_top__tablet_border_radius":"","side_top__tablet_sticky":"1","side_middle__tablet_custom":"","side_middle__tablet_background":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_color":"","side_middle__tablet_color_hover":"","side_middle__tablet_height":{"height":""},"side_middle__tablet_spacing":{"padding-right":"","padding-left":""},"side_middle__tablet_border":"","side_middle__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_middle__tablet_border_radius":"","side_middle__tablet_sticky":"1","side_bottom__tablet_custom":"","side_bottom__tablet_background":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_background2":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_color":"","side_bottom__tablet_color_hover":"","side_bottom__tablet_height":{"height":""},"side_bottom__tablet_spacing":{"padding-right":"","padding-left":""},"side_bottom__tablet_border":"","side_bottom__tablet_border_color":{"color":"","alpha":"1","rgba":""},"side_bottom__tablet_border_radius":"","side_bottom__tablet_sticky":"1","side_top__mobile_custom":"","side_top__mobile_background":{"color":"","alpha":"1","rgba":""},"side_top__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_top__mobile_color":"","side_top__mobile_color_hover":"","side_top__mobile_height":{"height":""},"side_top__mobile_spacing":{"padding-right":"","padding-left":""},"side_top__mobile_border":"","side_top__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_top__mobile_border_radius":"","side_top__mobile_sticky":"1","side_middle__mobile_custom":"1","side_middle__mobile_background":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_color":"","side_middle__mobile_color_hover":"","side_middle__mobile_height":{"height":""},"side_middle__mobile_spacing":{"padding-right":"10px","padding-left":"10px"},"side_middle__mobile_border":"","side_middle__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_middle__mobile_border_radius":"","side_middle__mobile_sticky":"1","side_bottom__mobile_custom":"","side_bottom__mobile_background":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_background2":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_color":"","side_bottom__mobile_color_hover":"","side_bottom__mobile_height":{"height":""},"side_bottom__mobile_spacing":{"padding-right":"","padding-left":""},"side_bottom__mobile_border":"","side_bottom__mobile_border_color":{"color":"","alpha":"1","rgba":""},"side_bottom__mobile_border_radius":"","side_bottom__mobile_sticky":"1","header_full_width":"1","header_on_bg":"1","header_sticky":"","header_sticky_appearance_style":"classic","header_sticky_appearance_from_top":"auto","header_sticky_appearance_number":{"height":"300"},"header_sticky_shadow":"1","tablet_header_on_bg":"1","tablet_header_sticky":"","mobile_header_on_bg":"1","mobile_header_sticky":""}';


    return $header_presets;
}