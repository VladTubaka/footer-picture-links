<?php

/**
 * @package Footer_Pic_Links
 * @version 0.1.2
 */
/*
Plugin Name: Footer Picture Links
Plugin URI: none
Description: Adds three picture navigation links to the footer.
Author: Kerry Lee
Version: 0.1.2
Author URI: none
*/

class Footer_Pic_Links {

  public function __construct() {
	  // Hook into the admin menu
    add_action( 'admin_enqueue_scripts', array( $this, 'fpl_uploader_enqueue' ) );
	  add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
    add_action( 'admin_init', array( $this, 'setup_sections' ) );
    add_action( 'admin_init', array( $this, 'setup_fields' ) );
    add_filter( 'wp_footer', array( $this, 'footerpiclinks' ) );
    add_filter( 'wp_footer', array( $this, 'footerpiclinks_css' ) );
  }

  public function fpl_uploader_enqueue() {
    wp_enqueue_media();
    wp_register_script( 'fpl-uploader.js', plugins_url( 'fpl-uploader.js' , __FILE__), array('jquery') );
    wp_enqueue_script( 'fpl-uploader.js' );
  }

  public function create_plugin_settings_page() {
    // Add the menu item and page
    $page_title = 'Footer Picture Links Settings';
    $menu_title = 'Footer Picture Links';
    $capability = 'manage_options';
    $slug = 'fpl_fields';
    $callback = array( $this, 'plugin_settings_page_content' );

    add_submenu_page( 'options-general.php', $page_title, $menu_title, $capability, $slug, $callback );
  }

  public function plugin_settings_page_content() { ?>
	<div class="wrap">
		<h2>Footer Picture Links Settings</h2>
		<form method="post" action="options.php">
            <?php
                settings_fields( 'fpl_fields' );
                do_settings_sections( 'fpl_fields' );
                submit_button();
            ?>
		</form>
	</div>
  <?php
  }

  public function setup_sections() {
	  add_settings_section( 'footerpiclink_one', 'Footer Picture Link 1', array( $this, 'section_callback' ), 'fpl_fields' );
    add_settings_section( 'footerpiclink_two', 'Footer Picture Link 2', array( $this, 'section_callback' ), 'fpl_fields' );
    add_settings_section( 'footerpiclink_three', 'Footer Picture Link 3', array( $this, 'section_callback' ), 'fpl_fields' );
  }
  public function section_callback( $arguments ) {
  	switch( $arguments['id'] ){
  		case 'footerpiclink_one':
  			echo 'Caption, Link, and Image for the leftmost Footer Picture Link';
  			break;
  		case 'footerpiclink_two':
  			echo 'Caption, Link, and Image for the center Footer Picture Link';
  			break;
  		case 'footerpiclink_three':
  			echo 'Caption, Link, and Image for the rightmost Footer Picture Link';
  			break;
  	}
  }
  public function setup_fields() {
    add_settings_field( 'caption_one', 'First Caption', array( $this, 'caption_inputs' ), 'fpl_fields', 'footerpiclinks_one', 'caption_one' );
    add_settings_field( 'caption_two', 'Second Caption', array( $this, 'caption_inputs' ), 'fpl_fields', 'footerpiclinks_two', 'caption_two' );
    add_settings_field( 'caption_three', 'Third Caption', array( $this, 'caption_inputs' ), 'fpl_fields', 'footerpiclinks_three', 'caption_three' );
    add_settings_field( 'picture_one', 'First Picture', array( $this, 'picture_inputs' ), 'fpl_fields', 'footerpiclinks_one', 'picture_one' );
    add_settings_field( 'picture_two', 'Second Picture', array( $this, 'picture_inputs' ), 'fpl_fields', 'footerpiclinks_two', 'picture_two' );
    add_settings_field( 'picture_three', 'Third Picture', array( $this, 'picture_inputs' ), 'fpl_fields', 'footerpiclinks_three', 'picture_three' );
    add_settings_field( 'link_one', 'First Link', array( $this, 'link_inputs' ), 'fpl_fields', 'footerpiclinks_one', 'link_one' );
    add_settings_field( 'link_two', 'Second Link', array( $this, 'link_inputs' ), 'fpl_fields', 'footerpiclinks_two', 'link_two' );
    add_settings_field( 'link_three', 'Third Link', array( $this, 'link_inputs' ), 'fpl_fields', 'footerpiclinks_three', 'link_three' );

    register_setting( 'fpl_fields', 'caption_one' );
    register_setting( 'fpl_fields', 'caption_two' );
    register_setting( 'fpl_fields', 'caption_three' );
    register_setting( 'fpl_fields', 'picture_one' );
    register_setting( 'fpl_fields', 'picture_two' );
    register_setting( 'fpl_fields', 'picture_three' );
    register_setting( 'fpl_fields', 'link_one' );
    register_setting( 'fpl_fields', 'link_two' );
    register_setting( 'fpl_fields', 'link_three' );
  }

  public function caption_inputs( $args ) {
    $value = get_option( $args );
    printf( '<input name="%1$s" id="%1$s" type="text" placeholder="some caption" value="%2$s" />', $args, $value );
    printf( '<span class="helper">Text that will appear over your picture link</span>' );
    printf( '<p class="description">Be concise</p>' );
  }

  public function picture_inputs( $args ) {
    $value = get_option( $args );
    printf( '<input name="%1$s" id="%1$s' . '_' . 'input" type="text" placeholder="a picture URL" value="%2$s" />', $args, $value );
    printf( '<button id="picture_one_button">Select Image</button>' );
    printf( '<span class="helper">The URL of the image for your picture link</span>' );
    printf( '<p class="description">Be creative</p>' );
  }

  public function link_inputs( $args ) {
    $value = get_option( $args ); ?>
    <select name='page-dropdown'>
      <option value=''>
      <?php echo esc_attr( __( 'Select page' ) ); ?></option>
      <?php
        $pages = get_pages();
        foreach ( $pages as $page ) {
          printf( '<option value="%1$s">' . $page->post_title . '</option>', $value );
        }
      ?>
    </select>
    <?php
  }

  // html and css for plugin
  function footerpiclinks() {
    ?>
    <div class='bottom-row' role='navigation'>
      <a href='<?php echo get_option( 'link_one' ); ?>'>
        <div class='pic_one'>
          <div class='caption'>
            <h3><?php echo get_option( 'caption_one' ); ?></h3>
          </div>
        </div>
      </a>
      <a href='<?php get_option( 'link_two' ); ?>'>
        <div class='pic_two'>
          <div class='caption'>
            <h3><?php get_option( 'caption_two' ); ?></h3>
          </div>
        </div>
      </a>
      <a href='<?php get_option( 'link_three' ); ?>'>
        <div class='pic_three'>
          <div class='caption'>
            <h3><?php get_option( 'caption_three' ); ?></h3>
          </div>
        </div>
      </a>
    </div>
    <?php
  }

  function footerpiclinks_css() {
    ?>
    <style type='text/css'>
    .bottom-row {
      height: 120px;
      position: relative;
      display: flex;
      clear: both;
      z-index: 1;
    }
    .bottom-row a {
      text-decoration: none;
      height: inherit;
      width: 100%;
    }
    .pic_one, .pic_two, .pic_three {
      position: relative;
      display: flex;
      height: inherit;
      width: 100%;
      margin: auto;
      background-repeat: no-repeat;
    }
    .pic_one {
      background-image: url( '<?php echo get_option( 'picture_one' ); ?>' );
      background-position: 50% 50%;
      background-size: cover;
      -webkit-filter: grayscale(0%);
      filter: grayscale(0%);
      transition: -webkit-filter 0.5s linear;
      -moz-transition: filter 0.5s linear;
    }
    .pic_two {
      background-image: url( '<?php get_option( 'picture_two' ); ?>' );
      background-position: 66% 25%;
      background-size: cover;
      -webkit-filter: grayscale(0%);
      filter: grayscale(0%);
      transition: -webkit-filter 0.5s linear;
      -moz-transition: filter 0.5s linear;
    }
    .pic_three {
      background-image: url('<?php get_option( 'picture_three' ); ?>');
      background-position: 50% 35%;
      background-size: cover;
      -webkit-filter: grayscale(0%);
      filter: grayscale(0%);
      transition: -webkit-filter 0.5s linear;
      -moz-transition: filter 0.5s linear;
    }
    .pic_one:hover, pic_two:hover, pic_three:hover {
      -webkit-filter: grayscale(100%);
      filter: grayscale(100%);
    }
    .caption {
      position: relative;
      margin: auto;
    }
    .caption h3 {
      text-align: center;
      color: white;
      text-shadow: 1px 1px 1px black;
    }
    </script> <?php
  }

}
new Footer_Pic_Links();

?>
