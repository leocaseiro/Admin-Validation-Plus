<?php
/*
Plugin Name: Admin Validation Plus
Plugin URI: http://leocaseiro.com.br/admin-validate-plus
Description: Use <a href="http://bassistance.de/jquery-plugins/jquery-plugin-validation/">jQuery Validate</a>, <a href="http://digitalbush.com/projects/masked-input-plugin/">jQuery Masket Input Plugin</a> in Defaults fields or Custom Fields on Admin Wordpress when create a post, a custom post or a page
Version: 0.1
Author: Leo Caseiro
Author URI: http://leocaseiro.com.br
*/

define( 'AVP_PLUGIN_BASENAME' 	, plugin_basename( __FILE__ ));
define( 'AVP_PLUGIN_NAME' , trim( dirname( AVP_PLUGIN_BASENAME ), '/' ) );
define( 'AVP_PLUGIN_DIR' 	, WP_PLUGIN_DIR . '/' . AVP_PLUGIN_NAME );
define( 'AVP_PLUGIN_URL' 	, WP_PLUGIN_URL . '/' . AVP_PLUGIN_NAME );

add_filter( 'admin_init', 'avp_start_plugin' );
add_action( 'admin_menu', 'avp_config_page' );

function avp_config_page() {
	if ( function_exists('add_submenu_page') )
		add_submenu_page('options-general.php', 'Admin Validation Plus', 'Admin Validation Plus', 'manage_options', 'admin-validation-plus', 'avp_options');
}
function avp_options() {
?>
	<div class="wrap">
		<h2>Admin Validaton Plus - Settings</h2>
		<form method="post" action="options.php">
			
			<?php settings_fields( 'admin-validation-plus-settings' ); ?>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">Masked Inputs</th>
						<td>
							<textarea cols="60" rows="8" name="avp_maskedinputs"><?php echo get_option('avp_maskedinputs'); ?></textarea>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">jQuery Validate</th>
						<td>
							<textarea cols="80" rows="8" name="avp_validate"> <?php echo get_option('avp_validate'); ?> </textarea>
						</td>		
					</tr>
				</tbody>
			</table>
			<input class="button-primary" type="submit" value="<?php _e('Save Changes') ?>" />
		</form>
	</div>
<?php
}


function avp_plugin_action_links( $links, $file ) {
	static $this_plugin;
 
    if ( !$this_plugin )
        $this_plugin = plugin_basename(__FILE__);
    
	if ( $file == $this_plugin )
		$links[] = '<a href="options-general.php?page=admin-validation-plus">'.__('Settings').'</a>';
	
	return $links;
}
add_filter( 'plugin_action_links', 'avp_plugin_action_links', 10, 2 );

function avp_start_plugin($hook) {
	register_setting( 'admin-validation-plus-settings', 'avp_maskedinputs');
	register_setting( 'admin-validation-plus-settings', 'avp_validate');

	add_action( 'admin_enqueue_scripts', 'avp_add_javascripts');

	add_action('admin_footer-post.php', 'avp_dinamyc_javascript');
	add_action('admin_footer-post-new.php', 'avp_dinamyc_javascript');
}

function avp_add_javascripts() {
	wp_enqueue_script( 'jquery.maskedinput', AVP_PLUGIN_URL . '/js/thirdparty/jquery.maskedinput.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery.validate', AVP_PLUGIN_URL . '/js/thirdparty/jquery.validate.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery.metadata', AVP_PLUGIN_URL . '/js/thirdparty/jquery.metadata.js', array( 'jquery' ) );
}	

function avp_dinamyc_javascript() {
	global $post_type;
	
	//Masks with Masked Input jQuery
	if( $post_type == 'post' || $post_type == 'page' ){
?>
		<script type="text/javascript">
		/* <![CDATA[ */
			var maskedinputs = [
				<?php echo get_option('avp_maskedinputs'); ?>
			];
			var total_masks = maskedinputs.length;
		   
			for ( i = 0; i < total_masks ; i++) {
				jQuery(maskedinputs[i].selector).mask(maskedinputs[i].value);
			}
		/* ]]> */
		</script>
<?php 
	}
	
	//jQuery Validate
	if( $post_type == 'post' || $post_type == 'page' ) {
?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				var validator = jQuery("#post").validate({
					
				<?php echo get_option('avp_validate'); ?>
					
					invalidHandler: function(form, validator) {
						  var errors = validator.numberOfInvalids();
						  if (errors) {
							var message = errors == 1 ? 'Favor corrigir o seguinte erro:\n' : 'Favor corrigir os ' + errors + ' erros:\n';
							var errors = "";
							if (validator.errorList.length > 0) {
								for (x=0;x<validator.errorList.length;x++) {
									errors += "\n\u25CF " + validator.errorList[x].message;
								}
							}
							alert(message + errors);
						  }
						  validator.focusInvalid();
					},
					errorPlacement: function(error,element) {
						return true;
					}
				});
			});
		</script>
<?php
		}
}

/* 

//Masket Samples
{
	"selector": "#title",
	"value": "99/99/9999"
},
{
	"selector": "#new-tag-post_tag",
	"value": "99999-999"
},

//jQuery Validate Samples
 rules: {
		post_title: {
			required: true,
			minlength: 2
		},
		content: {
			required: true,
			minlength: 5					
		}
	},
	messages: {
		post_title: {
			required: "Favor preencher o Título do Post",
			minlength: "O Título precisa ter no mínimo {0} caracateres"
		},
		content: {
			required: "Favor preencha o Conteúdo",
			minlength: "O Conteúdo precisa ter no mínimo {0} caracateres"
		}					
	}, 
*/