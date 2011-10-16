(function( $ ){

  var callbackProgress = function(pbar, file) {
    return function(ev) {
      var percentComplete = Math.round(100 * ev.loaded / ev.total);
      console.log('Progress ' + percentComplete);
      if (ev.lengthComputable) {
        pbar.progressbar('value', percentComplete);
      }
    };
  };

  var callbackComplete = function(pbar, file) {
    return function(ev) {
      console.log('Upload complete');
      console.log(ev);
      pbar.progressbar('value', 100);
      $('#uploaded_files').append('<span>');
      $("<span class='icon'>"+file.name+"</span>").appendTo('#uploaded_files');
    };
  };

  var methods = {
    init : function( options ) {

      return this.each( function () {

        var $this = $(this);

        $.each(options, function( label, setting ) {
          $this.data(label, setting);
        });

        $this.bind('dragenter.dndUploader', methods.dragEnter);
        $this.bind('dragover.dndUploader', methods.dragOver);
        $this.bind('drop.dndUploader', methods.drop);

      });
    },

    dragEnter : function ( event ) {
      event.stopPropagation();
      event.preventDefault();

      return false;
    },

    dragOver : function ( event ) {
      event.stopPropagation();
      event.preventDefault();

      return false;
    },

    drop : function( event ) {
      event.stopPropagation();
      event.preventDefault();

      var $this = $(this);
      var dataTransfer = event.originalEvent.dataTransfer;

      if (dataTransfer.files.length > 0) {
        $.each(dataTransfer.files, function ( i, file ) {
          var xhr  = new XMLHttpRequest();

          xhr.open($this.data('method') || 'POST', $this.data('url'), true);
          xhr.setRequestHeader('X-Filename', file.name);
          xhr.setRequestHeader('X-Gustavson', 'Kevin was here!');

          var pbar = $('#progress');
          xhr.upload.addEventListener('progress', callbackProgress(pbar, file), false);
          xhr.upload.addEventListener('load', callbackComplete(pbar, file), false);

          xhr.send(file);
        });
      }

      return false;
    }
  };

  $.fn.dndUploader = function( method ) {

    if ( methods[method] ) {

      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));

    } else if ( typeof method === 'object' || ! method ) {

      return methods.init.apply( this, arguments );

    } else {

      $.error( 'Method ' +  method + ' does not exist on jQuery.dndUploader' );

    }
  };
})( jQuery );
