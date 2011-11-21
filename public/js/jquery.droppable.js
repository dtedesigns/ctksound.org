/**
 * HTML5 Droppable
 * Originally created by: Kevin Gustavson
 * Based on jQuery and "Native HTML5 Drag and Drop"
 * http://www.html5rocks.com/en/tutorials/dnd/basics/#toc-dataTransfer
 */

(function( $ ){

  var methods = {
    init : function( options ) {
      return this.each( function() {

        this.addEventListener('dragenter', methods.dragEnter, false);
        this.addEventListener('dragover', methods.dragOver, false);
        this.addEventListener('dragleave', methods.dragLeave, false);
        this.addEventListener('drop', methods.drop, false);
        this.addEventListener('dragend', methods.dragEnd, false);

      });
    },

    dragStart: function (ev) {
      ev.stopPropagation();
      ev.preventDefault();
      this.style.opacity = '0.4';

      ev.dataTransfer.effectAllowed = 'move';
      ev.dataTransfer.setData('text/html', this.innerHTML);

      return false;
    },

    dragEnter : function (ev) {
      this.classList.add('droppable_over');
      ev.stopPropagation();
      ev.preventDefault();

      return false;
    },

    dragLeave : function (ev) {
      this.classList.remove('droppable_over');
      ev.stopPropagation();
      ev.preventDefault();

      return false;
    },

    dragOver : function (ev) {
      this.classList.add('droppable_over');
      if (ev.preventDefault) {
        ev.stopPropagation();
        ev.preventDefault();
      }

      ev.dataTransfer.dropEffect = 'move';

      return false;
    },

    drop : function(ev) {
      // this/ev.target = target element

      console.log('dragDrop');
      this.classList.remove('droppable_over');

      if (ev.stopPropagation) {
        ev.stopPropagation();
        ev.preventDefault();
      }

      target = this.id.split('_');
      file = ev.dataTransfer.getData('Text');

      $.post('import_file/'+file,
        {
          type:   target[0],
          id:     target[1]
        },
        function(response) {
          console.log(response);
        }
      );

      return false;
    },

    dragEnd: function (ev) {
      // this/e.target = source node
      console.log('dragEnd');
    }

  };

  $.fn.droppable = function( method ) {

    if ( methods[method] ) {

      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));

    } else if ( typeof method === 'object' || ! method ) {

      return methods.init.apply( this, arguments );

    } else {

      $.error( 'Method ' +  method + ' does not exist on jQuery.droppable' );

    }
  };
})( jQuery );