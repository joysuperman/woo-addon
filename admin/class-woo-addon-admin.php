<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://joymojumder.com
 * @since      1.0.0
 *
 * @package    Woo_Addon
 * @subpackage Woo_Addon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Addon
 * @subpackage Woo_Addon/admin
 * @author     joySuperman <hi@joymojumder.com>
 */
class Woo_Addon_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-addon-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-addon-admin.js', array( 'jquery' ), $this->version, false );

		// Generate nonce field in PHP
		$addon_group_nonce = wp_create_nonce('update_addon_group_title_nonce');

		// Localize the script with the nonce value
		wp_localize_script( $this->plugin_name, 'addon_group_nonce', $addon_group_nonce );
	}

	/**
	 * Add meta box for addon options
	 */
	public function addon_options_meta_box()
	{
		add_meta_box(
			'addon-options',
			'Addon Options',
			array($this, 'render_addon_options_meta_box'),
			'product',
			'normal',
			'default'
		);
	}

	/**
	 * Render addon options meta box
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_addon_options_meta_box($post)
	{
		// Retrieve the existing meta values
		$product_groups = get_post_meta( $post->ID, '_product_groups', true );
		?>
        <div class="product-group-container">
			<?php if ( !empty( $product_groups ) ) :
				foreach ( $product_groups as $index => $group ) : ?>
                    <div class="product-group">
                        <div class="group-title">
                            <input type="text" name="product_group_titles[]" value="<?php echo esc_attr( $group['title'] ); ?>" placeholder="Group Title">
                            <button class="remove-group-button button button-primary">Remove Group</button>
                        </div>

                        <div class="group-options">
							<?php foreach ( $group['options'] as $option ) : ?>
                                <div class="option">
                                    <input type="text" name="product_group_options[<?php echo $index; ?>][titles][]" value="<?php echo esc_attr( $option['title'] ); ?>" placeholder="Option Title">
                                    <input type="text" name="product_group_options[<?php echo $index; ?>][prices][]" value="<?php echo esc_attr( $option['price'] ); ?>" placeholder="Option Price">
                                    <button class="remove-option-button button button-secondary button-small">Remove</button>
                                </div>
							<?php endforeach; ?>
                            <button class="add-option-button button button-primary button-small">Add Option</button>
                        </div>
                    </div>
				<?php endforeach;
			endif; ?>
        </div>
        <div class="bottom">
            <button class="add-group-button button button-primary button-large">Add Group</button>
        </div>
		<?php
	}


	/**
	 * Save addon options data
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_addon_options($post_id)
	{
		if ( isset( $_POST['product_group_titles'] ) ) {
			$product_groups = array();
			$titles = $_POST['product_group_titles'];
			$options = $_POST['product_group_options'];
			foreach ( $titles as $index => $title ) {
				$group = array(
					'title' => sanitize_text_field( $title ),
					'options' => array(),
				);
				if ( isset( $options[$index] ) ) {
					foreach ( $options[$index]['titles'] as $key => $opt_title ) {
						$group['options'][] = array(
							'title' => sanitize_text_field( $opt_title ),
							'price' => sanitize_text_field( $options[$index]['prices'][$key] ),
						);
					}
				}
				$product_groups[] = $group;
			}
			update_post_meta( $post_id, '_product_groups', $product_groups );
		} else {
			delete_post_meta( $post_id, '_product_groups' );
		}
	}

}
