(function( $ ) {
	'use strict';

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

			// Check if any error messages are present
			var hasError = $('.error-message').length > 0;

			// If there are no errors, open all accordion content
			if (!hasError) {
				$('.accordion-content').slideDown();
				$('.group-item').addClass('active');
			}

			// Prevent form submission if there are errors
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
		var originalPrice = parseFloat($('p.price .woocommerce-Price-amount').text().replace(/[^0-9.-]+/g, ""));
		$('p.price .woocommerce-Price-amount').attr('data-original-price', originalPrice);

		$('.group-item input[type="radio"]').on('change', function() {
			updateTotalPrice();
		});

		// Update total price on page load
		updateTotalPrice();
	});


})( jQuery );
