$(document).ready(function() {
  
  $("#amount").on("blur", function() {
    var value = parseFloat($(this).val());
    value = value.toFixed(2);
    $(this).val(value);
  });
});

$(document).on("keyup", ".purchase_code",  function(event) {
    // makes a selection in input
    var selection = window.getSelection().toString();
    if ( selection !== '' ) {
      return;
    }
 
    // Presses arrow keys
    if ( $.inArray( event.keyCode, [38,40,37,39] ) !== -1 ) {
      return;
    }
    
    // retrieve input
    var $this = $( this );
    var input = $(this).val();

    // remove dashes and other non-numbers
    input = input.replace(/[\W\s\._\-]+/g, '');
 
    // chunks by lengths below
    var splitArray = [4, 3, 4, 3, 3, 3, 4];
    var split = 0;
    var chunk = [];
    
    for (var i = 0, len = input.length; i < len; i+= split) {
      if ((i > 0 && i < 7) || (i >= 11 && i < 20)) {
        split = 3;
      }
      else {
        split = 4;
      }
      chunk.push( input.substr( i, split ) );
    }

    // puts chunks separated by dashes in input
    $this.val(function() {
       return chunk.join("-");
    });
    
  });