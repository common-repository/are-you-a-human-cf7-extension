<?php
require_once('WPASDPlugin.class.php');

class CF7ayah extends WPASDPlugin {

	// php4 Constructor
	function CF7ayah($options_name, $textdomain_name) {
	    
	    $args = func_get_args();
	    call_user_func_array(array(&$this, "__construct"), $args);
	}
	
	
	// php5 Constructor
	function __construct($options_name, $textdomain_name) {
	    parent::__construct($options_name, $textdomain_name);
	    
	}
	
	function getClassFile() 
	{
	    return __FILE__;
	}
	
	
	function pre_init()
	{
	}
	
	function post_init() 
	{
		wpcf7_add_shortcode( 'ayah', array(&$this, 'wpcf7_ayah_shortcode_handler'), true );
	}

	function register_default_options() {
	    /*if (is_array($this->options) && isset($this->options['reset_on_activate']) && $this->options['reset_on_activate'] !== 'on')
			return;	
	
	    $default_options = $this->get_default_options();
	    ayah_get_options();
	    $combined_options = array_merge($_SESSION['ayah_options'], $default_options);
	    
	    // add the options based on the environment
	    // WPASDPlugin::update_options($this->options_name, $combined_options);
	    ayah_set_options($combined_options);*/
	}
        
	function get_default_options() {
		$default_options = array();
    
	    $default_options['reset_on_activate'] = 'on';
	    $default_options['theme_selection'] = 'comments_theme';
	    $default_options['select_theme'] = 'select_theme';
	    $default_options['cf7recapext_theme'] = 'red';
	    $default_options['language_selection'] = 'language_selection';
	    $default_options['select_lang'] = 'select_lang';
	    $default_options['cf7recapext_language'] = 'en';
            
		return $default_options;
	}
	
	function add_settings() 
	{
	}

	function register_scripts() 
	{
	}

	function validate_options($input) 
	{
	}
	
	function register_settings_page() 
	{
	}

	function show_settings_page() 
	{
	}
	
	function register_actions() {
		add_action( 'admin_init', array(&$this, 'wpcf7_add_tag_generator_ayah'), 45 );
	}
	
	function register_filters() {
		add_filter( 'wpcf7_validate_ayah', array(&$this, 'wpcf7_ayah_validation_filter'), 10, 2 );	
	}
		
	function wpcf7_ayah_shortcode_handler( $tag ) {
		//require("wp-content/plugins/are-you-a-human/ayah.php");
		error_log(getcwd());
		
		$type = $tag['type'];
		$name = $tag['name'];
		$options = (array) $tag['options'];
		$values = (array) $tag['values'];
		
		
		foreach ( $options as $option ) {
			if ( preg_match( '%^id:([-0-9a-zA-Z_]+)$%', $option, $matches ) ) {
				$id_att = $matches[1];
	
			} elseif ( preg_match( '%^class:([-0-9a-zA-Z_]+)$%', $option, $matches ) ) {
				$class_att .= ' ' . $matches[1];
	
			} elseif ( preg_match( '%^([0-9]*)[/x]([0-9]*)$%', $option, $matches ) ) {
				$size_att = (int) $matches[1];
				$maxlength_att = (int) $matches[2];
	
			} elseif ( preg_match( '%^tabindex:(\d+)$%', $option, $matches ) ) {
				$tabindex_att = (int) $matches[1];
	
			}
		}
	
		//Make a new integration library object
		$ayah = $this->ayah_init();
		
		//Add some CSS that we use for every form
		//echo ayah_css();
		
		//Insert the game markup
		$publisher_html = $ayah->getPublisherHTML();
		
		$html = '<span class="wpcf7-form-control-wrap ' . $name . '" id="' . $name . '">' . $publisher_html . '</span>';

		return $html;
	}
	
	/* Validation filter */
	
	function wpcf7_ayah_validation_filter( $result, $tag ) {
		$type = $tag['type'];
		$name = $tag['name'];
			
		$ayah = $this->ayah_init();
		
		$scoreResult = $ayah->scoreResult();
		
		if ( $scoreResult ) {
		} else {
			$result['valid'] = false;
			$result['reason'][$name] = "Are You A Human CAPTCHA Failed. Please refresh the game and try again.";	
		}
		
		return $result;
	}
	
	function wpcf7_check_ayah( $prefix, $response ) {
		global $wpcf7_captcha;
	
		if ( ! wpcf7_init_captcha() )
			return false;
		$captcha =& $wpcf7_captcha;
	
		return $captcha->check( $prefix, $response );
	}
	
	/* Tag generator */
	
	function wpcf7_add_tag_generator_ayah() {
		wpcf7_add_tag_generator( 'ayah', __( 'Are You A Human', 'wpcf7' ),
			'wpcf7-tg-pane-ayah', array(&$this, 'wpcf7_tg_pane_ayah') );
	}
	
	function wpcf7_tg_pane_ayah( &$contact_form ) {
	?>
	<div id="wpcf7-tg-pane-ayah" class="hidden">
		<form action="">
		<table>

		<tr><td><?php _e( 'Name', $this->textdomain_name ); ?><br /><input type="text" name="name" class="tg-name oneline" /></td><td></td></tr>
		</table>

		<div class="tg-tag"><?php _e( "Copy this code and paste it into the form left.", $this->textdomain_name ); ?>
		<br />
		<input type="text" name="ayah" class="tag" readonly="readonly" onfocus="this.select()" />
		</div>
		</form>
	</div>
	<?php
	}
	
	function ayah_init() {
	    ayah_get_options();
	    return ayah_load_library();
	}
}
?>
