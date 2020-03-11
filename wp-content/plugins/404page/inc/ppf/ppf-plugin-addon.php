<?php

/**
 * Plugin Addon Base Class
 *
 * Peter's Plugins Foundation 04
 *
 * @package    PPF04
 * @author     Peter Raschendorfer
 * @license    GPL2+
 */

 
if ( !class_exists( 'PPF04_Plugin_Addon' ) ) {
  
  abstract class PPF04_Plugin_Addon extends PPF04_Plugin {
    
    /**
     * Base Plugin Name
     *
     * @since  PPF04
     * @var    string
     * @access private
     */
    private $base_plugin_name;
    
    
    /**
     * Base Plugin Function
     *
     * @since  PPF04
     * @var    string
     * @access private
     */
    private $base_plugin_function;
    
    
    /**
     * Base Plugin Min Required Version
     *
     * @since  PPF04
     * @var    string
     * @access private
     */
    private $base_plugin_min_version;
    

    /**
     * Settings Class ( if the plugin uses settings )
     *
     * @since  PPF01
     * @var    object
     * @access private
     */
    private $settings;
    

    /**
     * Init the Class 
     *
     * @since PPF04
     * same as PPFxx_Plugin plus
     *   @type string $base_plugin_name        Name of Base Plugin
     *   @type string $base_plugin_function    Function to access Base Plugin
     *   @type string $base_plugin_min_version Minimal required version of Base Plugin
     */
    public function __construct( $settings ) {
     
      $this->plugin_file      = $settings['file'];
      $this->plugin_slug      = $settings['slug'];
      $this->plugin_name      = $settings['name'];
      $this->plugin_shortname = $settings['shortname'];
      $this->plugin_version   = $settings['version'];
      
      $this->base_plugin_name        =  $settings['base_plugin_name'];
      $this->base_plugin_function    =  $settings['base_plugin_function'];
      $this->base_plugin_min_version =  $settings['base_plugin_min_version'];
      
      $this->_data_key = str_replace( '-', '_', $settings['slug'] ) . '_data';
      $this->data_load(); 
      
      $this->plugin_file      = $settings['file'];
      
      $this->addon_check();
      
    }


    /**
	   * get Base Plugin Name
     *
     * @since  PPF04
     * @access public
     * @return string
     */
    public function get_base_plugin_name() {
      
      return $this->base_plugin_name;
      
    }
    
    
    /**
	   * get Base Plugin Function
     *
     * @since  PPF04
     * @access public
     * @return string
     */
    public function get_base_plugin_function() {
      
      return $this->base_plugin_function;
      
    }
    
    
    /**
	   * get Base Plugin minimum required version^
     *
     * @since  PPF04
     * @access public
     * @return string
     */
    public function get_base_plugin_min_version() {
      
      return $this->base_plugin_min_version;
      
    }
    
    
    /**
     * check if base plugin exists and has required minimum version
     *
     * @since  PPF04
     * @access private
     */
    private function addon_check() {
      
      // we need to place all the stuff in plugins_loaded to ensure the base plugin is loaded
      
      add_action( 'plugins_loaded', function() {
        
        $this->plugin_install_update();
      
        $this->plugin_init();        
        
        $base = $this->get_base_plugin_function();
        
        if ( ! function_exists( $base ) ) {
          
          add_action('admin_notices', array( $this, 'admin_notice_base_plugin_not_found' ) );
          
        } elseif ( version_compare( $this->get_base_plugin_min_version(), $base()->get_plugin_version(), '>' ) ) {
          
          add_action('admin_notices', array( $this, 'admin_notice_base_plugin_version_insufficient' ) );
          
        } else {
      
          $this->addon_init();
          
        }
        
      } );
      
      
    }
    
    
    /**
     * addon init
     *
     * force to be defined
     *
     * @since PPF04
     */
    abstract public function addon_init();    
    
    
    /**
     * add admin notice if base plugin not found
     *
     * force to be defined
     *
     * @since PPF04
     */
    abstract public function admin_notice_base_plugin_not_found();    
    
    
    /**
     * add admin notice if base plugin version insufficient
     *
     * force to be defined
     *
     * @since PPF04
     */
    abstract public function admin_notice_base_plugin_version_insufficient();    

    
  }
  
}

?>