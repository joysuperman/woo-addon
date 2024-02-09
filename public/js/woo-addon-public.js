(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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

	// Add to Cart button click
	jQuery(document).ready(function($) {

		// Accordion functionality
		$('.accordion-title').click(function() {
			var $accordionContent = $(this).next('.accordion-content');
			$accordionContent.slideToggle();

			// Toggle 'active' class for the parent '.group-item'
			$(this).parent('.group-item').toggleClass('active');
		});


		$('button.single_add_to_cart_button').on('click', function(e) {
			var isValid = true;
			$('.group-item').each(function() {
				var groupTitle = $(this).find('h3').text();
				if (!$(this).find('input[type="radio"]').is(':checked')) {
					isValid = false;
					if (!$(this).find('.error-message').length) {
						$('<span class="error-message">Please select an option for ' + groupTitle + '</span>').insertAfter($(this).find('h3'));
					}
				} else {
					$(this).find('.error-message').remove();
				}
			});
			if (!isValid) {
				e.preventDefault();
			}
		});

		// Function to update total price
		function updateTotalPrice() {
			var totalPriceText = $('p.price .woocommerce-Price-amount').text(); // Extract total price text
			var currencySymbol = totalPriceText.replace(/[0-9.,]/g, '').trim(); // Extract currency symbol
			var originalPrice = parseFloat($('p.price .woocommerce-Price-amount').attr('data-original-price')); // Get original product price
			var totalPrice = originalPrice; // Initialize total price with original price

			// Iterate through each checked option and update the total price
			$('.group-item input[type="radio"]:checked').each(function() {
				var price = parseFloat($(this).val());
				totalPrice += price;
			});

			$('p.price .woocommerce-Price-amount').text(totalPrice.toFixed(2) + currencySymbol);
		}

		// Store original product price on page load
		var originalPrice = parseFloat($('p.price .woocommerce-Price-amount').text().replace(/[^0-9.-]+/g,""));
		$('p.price .woocommerce-Price-amount').attr('data-original-price', originalPrice);

		$('.group-item input[type="radio"]').on('change', function() {
			updateTotalPrice();
		});

		// Update total price on page load
		updateTotalPrice();
	});


})( jQuery );
