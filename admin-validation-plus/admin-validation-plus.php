<?php
/*
Plugin Name: Admin Validation Plus
Plugin URI: http://leocaseiro.com.br/admin-validate-plus
Description: Use jQuery Validate, jQuery Masket Input in Defaults fields or Custom Fields on Admin Wordpress when create a post, a custom post or a page
Version: 1.0
Author: Leo Caseiro
Author URI: http://leocaseiro.com.br
*/


define( 'AVP_PLUGIN_BASENAME' 	, plugin_basename( __FILE__ ));
define( 'AVP_PLUGIN_NAME' , trim( dirname( AVP_PLUGIN_BASENAME ), '/' ) );
define( 'AVP_PLUGIN_DIR' 	, WP_PLUGIN_DIR . '/' . AVP_PLUGIN_NAME );
define( 'AVP_PLUGIN_URL' 	, WP_PLUGIN_URL . '/' . AVP_PLUGIN_NAME );

register_activation_hook( __FILE__, 'avp_install');
add_filter('init', 'avp_start_plugin');

function avp_start_plugin($hook) {
	add_action( 'admin_enqueue_scripts', 'avp_add_javascripts');
}

function avp_add_javascripts() {
	wp_enqueue_script( 'jquery.maskedinput', AVP_PLUGIN_URL . '/js/thirdparty/jquery.maskedinput.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery.validate', AVP_PLUGIN_URL . '/js/thirdparty/jquery.validate.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery.metadata', AVP_PLUGIN_URL . '/js/thirdparty/jquery.metadata.js', array( 'jquery' ) );
	
	wp_enqueue_script( 'avp_dinamyc_javascript', avp_dinamyc_javascript() , array( 'jquery' ), false, true );
	wp_enqueue_script( 'avp_main', AVP_PLUGIN_URL . '/js/avp_main.js', array( 'jquery' ) );
	
	
}
function avp_dinamyc_javascript() { ?>
	 <script type='text/javascript'>
    /* <![CDATA[ */
		var maskedinputs = [
			{
				"selector": "#title",
				"value": "99/99/9999"
			},
			{
				"selector": "#new-tag-post_tag",
				"value": "99999-99"
			},
		];
	/* ]]> */
    </script>
<?php
}


//http://weblogtoolscollection.com/archives/2010/05/07/adding-scripts-properly-to-wordpress-part-2-javascript-localization/

function avp_install() {
	//...some
}
