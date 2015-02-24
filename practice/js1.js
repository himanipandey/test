$(document).ready(function() {
	var x = " India.";

	$("#text-id").on( 'click', function () {
	    $.ajax({
	        type: 'post',
	        url: 'prac1.php',
	        data: {
	            source1: "Hello ",
	            source2: "World ",
	            source3: x
	        },
	        success: function( data ) {
	            //console.log( data );
	        }
	    });
	});
}) 
