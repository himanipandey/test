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

function getData() {
    var data = "";
    if ( window.areaResponse['city'] ) {
        data = "city="+window.areaResponse['city'];
        if ( window.areaResponse['suburb'] ) {
            data += "&suburb="+window.areaResponse['suburb'];
        }
        if ( window.areaResponse['locality'] ) {
            data += "&locality="+window.areaResponse['locality'];
        }
    }
    return data;
}

function getSubLocData() {
    var data = getData(),
        res = null;

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
    var selectVal = $('#'+areaType+'-id').html();
    if ( !isNaN( selectVal ) ) {
        $('#'+areaType+'-id').remove();
    }
    else {
        selectVal = -1;
    }

    $('#'+areaId).append( "<option value='0'><span> Select "+ areaType +" </span></option>" );
    for( var __cnt = 0; __cnt < data.length; __cnt++ ) {
        var html = "<option value='"+ data[ __cnt ]['id'] +"' id='drp-dwn-"+ areaType + "-" + data[ __cnt ]['id'] +"'";
        if ( window.areaResponse[ areaType ] == data[ __cnt ]['id'] || selectVal == data[ __cnt ]['id'] ) {
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

function getPhotos() {
    toggleSaveBtn( 'hide' );
    $('.image-block').html('');
    var data = getPhotosFromDB();
    if ( data != null && data.length > 0 ) {
        for( var __imgNo = 0; __imgNo < data.length; __imgNo++ ) {
            showThisPhoto( data[ __imgNo ] );
        }
        toggleSaveBtn( 'show' );
    }
    else {
        $('.image-block').html('No photos found for this area');
    }
}

function showThisPhoto( imgData ) {
    imgData['IMAGE_DISPLAY_NAME'] = imgData['IMAGE_DISPLAY_NAME'] == null ? "" : imgData['IMAGE_DISPLAY_NAME'];
    imgData['IMAGE_CATEGORY'] = imgData['IMAGE_CATEGORY'] == null ? "" : imgData['IMAGE_CATEGORY'];
    imgData['IMAGE_DESCRIPTION'] = imgData['IMAGE_DESCRIPTION'] == null ? "" : imgData['IMAGE_DESCRIPTION'];
    var template = '<div style="padding:5px; border:solid 1px #ccc; display:inline-block;">'+
                        '<div class="img-wrap" style="float:left;"> <img src="/images_new/locality/thumb_'+imgData['IMAGE_NAME']+'" /> </div>'+
                        '<div class="img-dtls" style="float:right; margin:0px 0px 0px 10px;">'+
                            '<select name="imgCate['+imgData['IMAGE_ID']+']">'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == '' ? 'selected' : '' ) +' value="">Category</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Mall' ? 'selected' : '' ) +' value="Mall">Mall</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Hospital' ? 'selected' : '' ) +' value="Hospital">Hospital</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'School' ? 'selected' : '' ) +' value="School">School</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Road' ? 'selected' : '' ) +' value="Road">Road</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Other' ? 'selected' : '' ) +' value="Other">Other</option>'+
                            '</select><br />'+
                            '<input type="text" name="imgName['+imgData['IMAGE_ID']+']" placeholder="Enter Name" value="'+imgData['IMAGE_DISPLAY_NAME']+'"><br />'+
                            '<input type="text" name="imgDesc['+imgData['IMAGE_ID']+']" placeholder="Enter Description" value="'+imgData['IMAGE_DESCRIPTION']+'">'+
                        '</div>'+
                        '<div class="clearfix" style="clear:both;"></div>'+
                    '</div>';
    $('.image-block').append( template );
}

function toggleSaveBtn( sh ) {
    if ( sh == 'show' ) {
        $('#s-btn').show();
    }
    else {
        $('#s-btn').hide();
    }
}

function getPhotosFromDB() {
    initVar();
    var data = getData(),
        res = null;

    $.ajax({
        async: false,
        type : 'GET',
        url  : '/ajax/photo.php',
        data : data+"&getPh=1",
        success: function( json ) {
            var __json = JSON.parse( json );
            if ( __json['result'] == true ) {
                res = __json['data'];
            }
            else {
                res = null;
            }
        }
    });
    return res;
}