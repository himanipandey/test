/**
 * User: swapnil
 * Date: 7/23/13
 * Time: 7:10 PM
 */
window.areaResponse = { suburb:0,locality:0,city:0 };

$(document).ready(function(){
    initVar();
    //console.log( window.areaResponse );
    var response = getSubLocData();
    updateDropDown( response );
    updateDisplayLocation();
});

function initVar() {
    window.areaResponse['city'] = $('#city-list').val();
    window.areaResponse['suburb'] = $('#area-type-sub').val();
    window.areaResponse['locality'] = $('#area-type-loc').val();
}

function getSubLocData() {
    var data = "",
        res = null;
    if ( window.areaResponse['city'] ) {
        data = "city="+window.areaResponse['city'];
        if ( window.areaResponse['suburb'] ) {
            data += "&suburb="+window.areaResponse['suburb'];
        }
    }

    $.ajax({
        async: false,
        type : 'GET',
        url  : '/ajax/photo.php',
        data : data,
        success: function( json ) {
            var __json = JSON.parse( json );
            if ( __json['result'] == true ) {
                res = __json;
            }
            else {
                res = null;
            }
        }
    });
    return res;
}

function areaTypeChanged( areaType ) {
    initVar();
    if ( areaType == 'city' ) {
        window.areaResponse['suburb'] = 0;
    }
    var data = getSubLocData();
    updateDropDown( data );
    updateDisplayLocation();
}

function updateDropDown( response ) {
    if ( response['result'] == true ) {
        response = response['data'];
        if ( typeof  response['suburb'] != 'undefined' ) {
            updateDropDownOption( 'area-type-sub', 'suburb', response['suburb'] );
        }
        if ( typeof response['locality'] != 'undefined' ) {
            updateDropDownOption( 'area-type-loc', 'locality', response['locality'] );
        }
    }
}

function updateDropDownOption( areaId, areaType, data ) {
    $('#'+areaId).empty();

    $('#'+areaId).append( "<option value='0'><span> Select "+ areaType +" </span></option>" );
    for( var __cnt = 0; __cnt < data.length; __cnt++ ) {
        var html = "<option value='"+ data[ __cnt ]['id'] +"' id='drp-dwn-"+ areaType + "-" + data[ __cnt ]['id'] +"'";
        if ( window.areaResponse[ areaType ] == data[ __cnt ]['id'] ) {
            html += " selected ";
        }
        html += "><span>"+ data[ __cnt ]['name'] +"</span></option>";
        $('#'+areaId).append( html );
    }
}

function updateDisplayLocation() {
    initVar();
    var areaType  = "",
        elementId = "",
        areaName  = "";
    if ( window.areaResponse['locality'] != 0 ) {
        areaType = "Locality";
        elementId = "drp-dwn-locality-" + window.areaResponse['locality'];
    }
    else if ( window.areaResponse['suburb'] != 0 ) {
        areaType = "Suburb";
        elementId = "drp-dwn-suburb-" + window.areaResponse['suburb'];
    }
    else {
        areaType = "City";
        elementId = "drp-dwn-city-" + window.areaResponse['city'];
    }
    areaName = $('#'+elementId).html();
    $('#area-txt-name').html( areaType + " : " + areaName );
}

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
    else if ( img.files.length > 10 ) {
        alert('please select 10 or less images');
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
