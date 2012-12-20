$.fn.spin = function(opts) {
  this.each(function() {
    var $this = $(this),
        data = $this.data();

    if (data.spinner) {
      data.spinner.stop();
      delete data.spinner;
    }
    if (opts !== false) {
      data.spinner = new Spinner($.extend({color: $this.css('color')}, opts)).spin(this);
    }
  });
  return this;
};

$( function() {

	var spinOptions = {
		lines    : 13,			// The number of lines to draw
		length   : 21,			// The length of each line
		width    : 12,			// The line thickness
		radius   : 32,			// The radius of the inner circle
		corners  : 1,			// Corner roundness (0..1)
		rotate   : 0,			// The rotation offset
		color    : '#000',		// #rgb or #rrggbb
		speed    : 1,			// Rounds per second
		trail    : 60,			// Afterglow percentage
		shadow   : true,		// Whether to render a shadow
		hwaccel  : true,		// Whether to use hardware acceleration
		className: 'spinner',	// The CSS class to assign to the spinner
		zIndex   : 2e9,			// The z-index (defaults to 2000000000)
		top      : 'auto',		// Top position relative to parent in px
		left     : 'auto'		// Left position relative to parent in px
	};

	$( '#progress .spin' ).spin( spinOptions );

} );