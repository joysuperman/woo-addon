<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://joymojumder.com
 * @since      1.0.0
 *
 * @package    Woo_Addon
 * @subpackage Woo_Addon/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woo_Addon
 * @subpackage Woo_Addon/public
 * @author     joySuperman <hi@joymojumder.com>
 */
class Woo_Addon_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-addon-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-addon-public.js', array( 'jquery' ), $this->version, false );

	}

	// Display Addon option
	public function display_addon_options() {
		echo '<h4>Extra Item</h4>';
		global $product;

		// Retrieve the product groups from meta
		$product_groups = get_post_meta($product->get_id(), '_product_groups', true);

		if (!empty($product_groups)) {
			echo '<div class="product-options-group">';
			foreach ($product_groups as $group) {
				echo '<div class="group-item">';
				echo '<h3 class="accordion-title">' . esc_html($group['title']) . '<span class="dropdown-icon"><i class="dashicons dashicons-arrow-down-alt2"></i></span></h3>';
				echo '<div class="accordion-content">';
				foreach ($group['options'] as $option) {
					echo '<label>';
					echo '<div class="title-price"><span><input type="radio" name="addon_options[' . esc_attr($group['title']) . ']" value="' . esc_attr($option['price']) . '"> ';
					echo esc_html($option['title']) . '</span>' . wc_price($option['price']) . '</div>';
					echo '</label>';
				}
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
		}
	}

	public function add_custom_price_to_cart_item_data($cart_item_data, $product_id) {
		if (isset($_POST['addon_options'])) {
			$cart_item_data['addon_options'] = wc_clean(wp_unslash($_POST['addon_options']));
		}
		return $cart_item_data;
	}

	public function calculate_custom_price_in_cart($cart_object) {
		foreach ($cart_object->cart_contents as $key => $value) {
			if (isset($value['addon_options'])) {
				$total_addon_price = array_sum($value['addon_options']);
				$value['data']->set_price($value['data']->get_price() + $total_addon_price);
				$value['custom_price'] = $total_addon_price;
			}
		}
	}

	public function display_addon_options_in_cart($item_data, $cart_item) {
		if (isset($cart_item['addon_options'])) {
			foreach ($cart_item['addon_options'] as $group_title => $addon_price) {
				$item_data[] = array(
					'key' => esc_html($group_title),
					'value' => wc_price($addon_price)
				);
			}
		}

		if (isset($cart_item['custom_price'])) {
			$item_data[] = array(
				'key' => 'Custom Price',
				'value' => wc_price($cart_item['custom_price'])
			);
		}

		return $item_data;
	}

	public function display_addon_options_in_checkout($cart_item, $cart_item_key) {
		if (isset($cart_item['addon_options'])) {
			foreach ($cart_item['addon_options'] as $group_title => $addon_price) {
				echo '<p><strong>' . esc_html($group_title) . ':</strong> ' . wc_price($addon_price) . '</p>';
			}
		}

		if (isset($cart_item['custom_price'])) {
			echo '<p><strong>Custom Price:</strong> ' . wc_price($cart_item['custom_price']) . '</p>';
		}
	}

}
