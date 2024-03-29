(function ($) {
	"use strict";
	// Clear cache files
	$(document).ready(function(){
		if ($(".post_type_options_btn_link").length) {
			if ($(".cmb2-id-client-post-title,.cmb2-id-testimonial-post-title,.cmb2-id-team_member-post-title").length) {				
				$(".cmb2-id-client-post-title").attr('id', 'client_p_t');
				$(".cmb2-id-testimonial-post-title").attr('id', 'testimonial_p_t');
				$(".cmb2-id-team-member-post-title").attr('id', 'team_member_p_t');
			}
		}
		if($(".cmb2-id-plus-smart-performance").length > 0 || $(".theplus_form_content #theplus_options").length > 0){
			var ids="theplus-remove-smart-cache";
			var smart_action ='';
			if($(".cmb2-id-plus-smart-performance").length > 0){
				$(".cmb2-id-plus-smart-performance > .cmb-td").append('<a href="#" id="'+ids+'" class="tp-smart-cache-btn">Purge All Cache</a><div class="smart-performace-desc-btn">Use above button to delete all cache our plugin have generated. It will start creating cache once some one start visiting your website.</div>');
				smart_action = "smart_perf_clear_cache";
			}
			if($(".theplus_form_content #theplus_options").length > 0){
				$(".theplus_form_content #theplus_options").append('<a href="#" id="'+ids+'" class="tp-smart-cache-btn">Remove Cache<div class="smart-performace-desc-tooltip">Remove backend cache after enabling/disabling widgets for performance.</div></a>');
				smart_action = "backend_clear_cache";
			}
			var val=$( "select#plus_smart_performance option:selected" ).val();
			if(val=='disable'){
				$("#"+ids).addClass("hidden").css("display","none");
			}else if(val=='enable'){
				$("#"+ids).removeClass("hidden").css("display","inline-block");
			}
			$( "select#plus_smart_performance" ).change(function() {
				var val=$( this ).val();
				if(val=='disable'){
					$("#"+ids).addClass("hidden").css("display","none");
				}else if(val=='enable'){
					$("#"+ids).removeClass("hidden").css("display","inline-block");
				}
			});
			
			$("#"+ids).on("click", function(e) {
				e.preventDefault();
				if($(".cmb2-id-plus-smart-performance").length > 0){
					var confirmation = confirm("Are you sure want to remove all cache files? It will remove all cached JS and CSS files from your server. It will generate automatically on your next visit of page.?");
				}
				if($(".theplus_form_content #theplus_options").length > 0){
					var confirmation = true;
				}
				if (confirmation) {
					var $this = $(this);
					$.ajax({
						url: theplus_ajax_url,
						type: "post",
						data: {
							action: smart_action,
							security: theplus_nonce
						},
						beforeSend: function() {
							$this.html(
								'<svg id="plus-spinner" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48"><circle cx="24" cy="4" r="4" fill="#fff"/><circle cx="12.19" cy="7.86" r="3.7" fill="#fffbf2"/><circle cx="5.02" cy="17.68" r="3.4" fill="#fef7e4"/><circle cx="5.02" cy="30.32" r="3.1" fill="#fef3d7"/><circle cx="12.19" cy="40.14" r="2.8" fill="#feefc9"/><circle cx="24" cy="44" r="2.5" fill="#feebbc"/><circle cx="35.81" cy="40.14" r="2.2" fill="#fde7af"/><circle cx="42.98" cy="30.32" r="1.9" fill="#fde3a1"/><circle cx="42.98" cy="17.68" r="1.6" fill="#fddf94"/><circle cx="35.81" cy="7.86" r="1.3" fill="#fcdb86"/></svg><span style="margin-left: 5px;">Removing Purge...</span>'
							);
						},
						success: function(response) {
							if($(".cmb2-id-plus-smart-performance").length > 0){
								setTimeout(function() {
									$this.html("Purge All Cache");
								}, 100);
							}
							if($(".theplus_form_content #theplus_options").length > 0){
								setTimeout(function() {
									$this.html('Remove Cache<div class="smart-performace-desc-tooltip">Remove backend cache after enabling/disabling widgets for performance.</div>');
								}, 100);
							}
						},
						error: function() {
						}
					});
				}
			});
		}
	});
	
    

	jQuery(document).on( 'click', '.plus-key-notify .notice-dismiss', function() {
		jQuery.ajax({
			url: ajaxurl,
			data: {
				action: 'theplus_key_notice'
			}
		})	
	});	
	$(window).load(function(){
	
		var $category_container = $(".plus-template-main-category");
		var $category_list = $category_container.find(".plus-main-category-list");
		
		if($category_container.length==1 && $category_list.length == 1 ){
			var active_category = $category_list.find(".active-open .plus-templates-tab");
			var category = active_category.data("listing");
			get_template_load(category);
		}
		$('.plus-template-main-category .plus-main-category-list li').on('click',function(e) {
			var $this=$(this);
			
			var parent_class=$this.parent().find('li').removeClass("active-open");			
			$this.addClass("active-open");
			var category = $this.find(".plus-templates-tab").data("listing");
			
			var parent_class=$this.closest(".plus-template-main-category").find(".widgets-listing-content");
			parent_class.removeClass("active");
			
			$("#listing-"+category).addClass("active");
			
			get_template_load(category);
		});
		$(document).on('click','.widgets-listing-content .sub-category-listing li', function(e) {
			e.preventDefault();
			var $this =$(this);
			var filter_category=$this.data("filter");
			var parent_class=$this.parent().find('li').removeClass("active");
			var main_category=$(".plus-main-category-list").find("li.active-open .plus-templates-tab").data("listing");
			$this.addClass("active");
			if(filter_category!='*'){
				$(this).closest(".widgets-listing-content").find('.plus-template-library-template').not('.'+filter_category).hide('400');
				$(this).closest(".widgets-listing-content").find('.plus-template-library-template').filter('.'+filter_category).show('400');
			}else{
				$(this).closest(".widgets-listing-content").find('.plus-template-library-template').show('600');
			}
			var $masonry_column = $("#listing-"+main_category +' .plus-template-innner-content');
			$masonry_column.masonry('layout');			
			setTimeout(function(){
				$masonry_column.imagesLoaded().progress( function() {
				  $masonry_column.masonry('layout');
				});			
			}, 400);
			
		});
		$(document).on('click','.plus-template-library-template-download .template-download', function(e) {
			e.preventDefault();
			var json="json";
			var $this=$(this);
			var template=$(this).data("url");
			var file_type=$(this).data("type");
			var main_category_widget=$(".plus-main-category-list").find("li.active-open .plus-templates-tab").data("listing");
			
			if(template!=''){
			
				$this.find(".download-template").hide();
				$this.find(".loading-template").show();
				$.ajax({
					url : ajaxurl,
					type : 'post',
					data : {
						action : 'plus_template_ajax',
						json : json,
						widget_category : main_category_widget,
						template: template,
						file_type:file_type,
					},
					success : function( data ) {
						if(data!='' && data!=0 && file_type!='zip'){
						 var a = document.createElement("a");
							document.body.appendChild(a);
							a.style = "display: none";
							var blob = new Blob([data], {type: "octet/stream"}),
								url = window.URL.createObjectURL(blob);
							a.href = url;
							a.download = template+'.json';
							a.click();
							window.URL.revokeObjectURL(url);
						}else if(data!='' && data!=0){						
							var a = document.createElement('a');						
							a.href = data;
							a.download = template+'.'+file_type;
							document.body.appendChild(a);
							a.click();
						}
						setTimeout(function(){
							$this.find(".loading-template").hide();
							$this.find(".download-template").show();
						}, 2000);
					}
				});
			setTimeout(function(){
				$this.find(".loading-template").hide();
				$this.find(".download-template").show();
			}, 2000);
			}
		});
	});
	function get_template_load(category){
		if(category!=''){
				$.ajax({
					url : ajaxurl,
					type : 'post',
					data : {
						action : 'plus_template_library_content',
						category : category,
					},
					success : function( data ) {
						if(data!='' && data!=0){
							$("#listing-"+category).html(data);
						}else{
							alert("Not Found Templates");
						}
					},
					complete: function() {						
						var $masonry_column = $("#listing-"+category +' .plus-template-innner-content').masonry({						  
						  itemSelector: '.plus-template-library-template'
						});
						
						$masonry_column.imagesLoaded().progress( function() {
						  $masonry_column.masonry('layout');
						});
						$masonry_column.masonry();
					}
				});
			}
	}
	$(document).ready(function() {
		if($('#elementor-import-template-area').length==1){
		$('#elementor-import-template-area').dialog({
			title: 'Import Template Library',
			dialogClass: 'wp-dialog plus-import-template-popup',
			autoOpen: false,
			draggable: false,
			width: 'auto',
			modal: true,
			resizable: false,
			closeOnEscape: true,
			position: {
			  my: "center",
			  at: "center",
			  of: window
			},
			open: function () {
			  // close dialog by clicking the overlay behind it
			  $('.ui-widget-overlay').bind('click', function(){
				$('#elementor-import-template-area').dialog('close');
			  })
			},
			create: function () {
			  // style fix for WordPress admin
			  $('.ui-dialog-titlebar-close').addClass('ui-button');
			},
		});
		  // bind a button or a link to open the dialog
		$('.theplus-import-template-library').on('click',function(e) {
			e.preventDefault();
			$('#elementor-import-template-area').dialog('open');
		});
		}
		if($("#theplus_verified_api").length==1){
		
			$("#post_type_options").find(".button-primary").remove();
			
			$("#post_type_options").append('<div class="pt-plus-page-form"><div class="alert alert-warning"><strong>Important Notice :</strong><ul><li><b><a href="admin.php?page=theplus_purchase_code">Verify</a></b> your plugin and get access of all functionalities. Go to Verify section of settings to proceed further.</li></ul></div></div>');			
		}
		
		
		//ajax get acf field on post id
		if($("#tp_preview_post_input").length){
			$("#tp_preview_post_input").focusout(function(){
			
			var tp_render_mode = $('[name="tp_render_mode_type"]').val();
            if(tp_render_mode != 'acf_repeater'){
                return;
            }

            var post_id = $("#tp_preview_post").val();
            jQuery.ajax({
                url: ajaxurl,
                dataType: 'json',
                data: {
                    action: 'plus_acf_repeater_field',
                    post_id: post_id,
                },
                success: function (res) {
				
                    jQuery("#tp_acf_field_name").find('option').remove().end();
                    if(res.data.length){
                        jQuery.each(res.data, function(i, d) {
                            jQuery("#tp_acf_field_name").append(jQuery("<option/>", {
                                value: d.meta_id,
                                text: d.text
                            }));
                        });
                    }
                }
            });
			});
		}
	});
})(window.jQuery);