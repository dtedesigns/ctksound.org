(function( $ ){

   var methods = {

    init : function( options ) {
      return this.each(function() {

        $this = $(this);

        $inner = $('<div class="inner pbar_plugin"/>').css({
          background: options.background,
          height:     'inherit',
          width:      options.value+'%'
        });

        $this.html($inner).css({
          height:  options.height,
          width:   options.width
        });

      });
    },

    value : function( val ) {
      return this.each(function() {
        $('.pbar_plugin', this).css('width', val+'%');
      });
    }

  };

  $.fn.progressbar = function( args ) {
    var options = {
      background: 'lightgray',
      height:     '20px',
      value:      0,
      width:      '100%'
    };

    if ( methods[args] ) {

      return methods[args].apply( this, Array.prototype.slice.call( arguments, 1 ));

    } else if ( ! options || typeof options === 'object' ) {

      $.extend(options, args);
      return methods.init.call( this, options );

    }

    $.error( 'Method ' + args + ' does not exist on jQuery.progressbar' );

  };
})( jQuery );
