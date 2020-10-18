Drupal.behaviors.restaurant = {
	attach: function(context, settings) {
		jQuery('#set-time-slots', context).once('restaurantBehavior').on('click', function() {
			var url = '/node/1';
			window.open(url, '_blank');
		});
	}
};
