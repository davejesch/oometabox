<?php
/*
Plugin Name: Object Oriented MetaBox Demo
Plugin URI: http://SpectrOMtech.com
Description: An Object Oriented example of Meta Box implementation
Author: Dave Jesch
Author URI: http://SpectrOMtech.com
Version: 1.0.0
*/

// Adopted from the MetaBox demo in "Professional WordPress Plugin Development" page 87

class OOMetaBoxDemoPlugin
{
	private static $_instance = NULL;

	private function __construct()
	{
		add_action('init', array(&$this, 'init'));
	}

	/**
	 * Create singleton reference to plugin
	 * @return Object The created instance
	 */
	public static function get_instance()
	{
		if (NULL === self::$_instance)
			self::$_instance = new self();
		return (self::$_instance);
	}

	/**
	 * Callback for the 'init' action
	 */
	public function init()
	{
		// create the metabox instance
		new DemoMetaBox();
	}
}

require_once(dirname(__FILE__) . '/class.spectrommetabox.php');

class DemoMetaBox extends SpectrOMMetaBox
{
	const META_KEY = '_oo_metabox_image';

	/**
	 * Create the metabox, setting the CSS class, title, etc.
	 */
	public function __construct()
	{
		parent::__construct(
			'oo-image-meta',						// CSS class for metbox
			__('Set Image', 'oometaboxdemo'),		// metabox title
			array(&$this, 'output_metabox')			// metabox callback
			// remaining parameters are defaults
		);

		// register the javascript
		add_action('admin_enqueue_scripts', array(&$this, 'admin_scripts'));
	}

	/*
	 * Output the contents for the metabox
	 * @param WP_POST $post The post object that is being edited
	 */
	public function output_metabox($post)
	{
		$img = get_post_meta($post->ID, self::META_KEY, TRUE);

		_e('Image: ', 'oometaboxdemo');
		echo '<input type="text" id="oomb_image" name="oomb_image" size="75" value="', esc_url($img), '" />', PHP_EOL;
		echo '<input type="button" id="oomb_upload_image" value="', __('Media Library Image', 'oometaboxdemo'),
			'" class="button-secondary" />', PHP_EOL;
		echo '<br />';
		_e(' Enter an image URL or use an image from the Media Library', 'oometaboxdemo');

		// only enqueue the scripts when the metabox is drawn
		$this->enqueue_scripts();
	}

	/**
	 * Registers the scripts and styles that the metabox uses
	 * @param type $page
	 */
	public function admin_scripts($page)
	{
		if ('post.php' === $page || 'post-new.php' === $page) {
			wp_register_script('oometaboxdemo', plugin_dir_url(__FILE__) . 'oometaboxdemo.js',
				array('jquery') /*, 'media-upload', 'thickbox')*/, '1.0', TRUE);
		}
	}

	/**
	 * Enqueues the scripts needed for the metabox
	 */
	private function enqueue_scripts()
	{
		wp_enqueue_script('oometaboxdemo');
		wp_enqueue_style('thickbox');
	}

	/*
	 * Callback for the 'save_post' action
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_data($post_id)
	{
		if (isset($_POST['oomb_image'])) {
			$img = $_POST['oomb_image'];

			// delete or update the meta data
			if (empty($img))
				delete_post_meta($post_id, self::META_KEY);
			else
				update_post_meta($post_id, self::META_KEY, esc_url_raw($img));
		}
	}
}

OOMetaBoxDemoPlugin::get_instance();
