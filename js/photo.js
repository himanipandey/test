/**
 * Created with JetBrains PhpStorm.
 * User: swapnil
 * Date: 7/23/13
 * Time: 7:10 PM
 * To change this template use File | Settings | File Templates.
 */
window.areaResponse = { suburb:-1,locality:-1,city:-1 };

function verifyPhotoFormData() {
    var img = document.getElementById('area-img');
    var val = true;
    if ( img ) {
        val = validateThisImg( img );
    }
    return val;
}

function validateThisImg( img ) {
    if ( img.files.length == 0 ) {
        alert('please select at-least 1 image');
        return false;
    }
    for( var i = 0; i < img.files.length; i++ ) {
        var f = img.files[i];
        if ( /^image/.test(f.type ) ) {
            if (f.size > 1048576 ) {
                alert('images size must be less that 1MB');
                return false;
            }
        }
        else {
            alert('only images files allowed');
            return false;
        }
    }
    return true;
}


function areaTypeChanged() {
    var areaType = $('#area-type').val();
    if ( window.areaResponse[ areaType ] == -1 ) {
        $.ajax({
            async:false,
            type: 'GET',
            url:'/ajax/photo.php',
            data:'areaType='+areaType,
            success: function( json ) {
                var __json = JSON.parse( json );
                if ( __json['result'] == true ) {
                    //console.log('yaho00o');
                    window.areaResponse[ areaType ] = __json['data'];
                    updateDropDown( areaType );
                }
                else {
                    //console.log('sad');
                }
            }
        });
    }
    else {
        updateDropDown( areaType );
    }
}

function updateDropDown( area ) {
    var __data = window.areaResponse[ area ];
    $('#area-list').empty();
    var __cnt = 0;
    for( __cnt = 0; __cnt < __data.length; __cnt++ ) {
        var html = "<option value='"+ __data[ __cnt ]['id'] +"'><span>"+ __data[ __cnt ]['name'] +"</span>";
        if ( 'city' in __data[ __cnt ] ) {
            html += "<span> === </span><span>"+ __data[ __cnt ]['city'] +"</span>";
        }
        html += "</option>";

        $('#area-list').append( html );
    }
}
