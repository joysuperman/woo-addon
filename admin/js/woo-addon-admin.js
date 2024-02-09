(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	jQuery(document).ready(function($) {
		// Add Group button click
		$('.add-group-button').on('click', function(e) {
			e.preventDefault();
			// Add HTML for a new product group
			$('.product-group-container').append('<div class="product-group"><div class="group-title"><input type="text" name="product_group_titles[]" placeholder="Group Title"><button class="remove-group-button button button-primary">Remove Group</button></div><div class="group-options"> <button class="add-option-button button button-primary button-small">Add Option</button></div></div>');
		});

		// Add Option button click
		$(document).on('click', '.add-option-button', function(e) {
			e.preventDefault();
			// Add HTML for a new option within the group
			$(this).parent().before('<div class="option"><input type="text" name="product_group_options[' + $(this).closest('.product-group').index() + '][titles][]" placeholder="Title"><input type="text" name="product_group_options[' + $(this).closest('.product-group').index() + '][prices][]" placeholder="Price"><button class="remove-option-button button button-secondary button-small">Remove</button></div>');
		});

		// Remove Group button click
		$(document).on('click', '.remove-group-button', function(e) {
			e.preventDefault();
			// Remove the entire product group
			$(this).closest('.product-group').remove();
		});

		// Remove Option button click
		$(document).on('click', '.remove-option-button', function(e) {
			e.preventDefault();
			// Remove the specific option within the group
			$(this).closest('.option').remove();
		});
	});


})( jQuery );
