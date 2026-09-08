/**
 * Live preview bindings for the Customizer.
 */
(function ($) {
	'use strict';

	wp.customize('blogname', function (value) {
		value.bind(function (to) {
			$('.if-brand__text a').text(to);
		});
	});

	wp.customize('blogdescription', function (value) {
		value.bind(function (to) {
			$('.if-brand__tagline').text(to);
		});
	});

	wp.customize('indfir_header_tagline', function (value) {
		value.bind(function (to) {
			$('.if-brand__tagline').text(to);
		});
	});
})(jQuery);
