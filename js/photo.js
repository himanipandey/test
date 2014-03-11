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
    $("#submitBUtton").show();
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
    imgData['priority'] = imgData['priority'] == null ? "" : imgData['priority'];
    var i=0;
    imgData['SERVICE_IMAGE_ID'] = imgData['SERVICE_IMAGE_ID'] == null ? "" : imgData['SERVICE_IMAGE_ID'];
    var template = '<div style="padding:5px; border:solid 1px #ccc; display:inline-block;">'+
                        '<div class="img-wrap" style="float:left;"> <img src="/images_new/locality/thumb_'+imgData['IMAGE_NAME']+'" /> </div>'+
                        '<div class="img-dtls" style="float:right; margin:0px 0px 0px 10px;">'+
                            '<b>Category:</b>&nbsp;&nbsp;<select name="imgCate_'+imgData['IMAGE_ID']+'[]">'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == '' ? 'selected' : '' ) +' value="">Category</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Mall' ? 'selected' : '' ) +' value="Mall">Mall</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Map' ? 'selected' : '' ) +' value="Map">Map</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Road' ? 'selected' : '' ) +' value="Road">Road</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Hospital' ? 'selected' : '' ) +' value="Hospital">Hospital</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'School' ? 'selected' : '' ) +' value="School">School</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Hotel' ? 'selected' : '' ) +' value="Hotel">Hotel</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Bank' ? 'selected' : '' ) +' value="Bank">Bank</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Station' ? 'selected' : '' ) +' value="Station">Station</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Gurdwara' ? 'selected' : '' ) +' value="Gurdwara">Gurdwara</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Mosque' ? 'selected' : '' ) +' value="Mosque">Mosque</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Bus Stand' ? 'selected' : '' ) +' value="Bus Stand">Bus Stand</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Park' ? 'selected' : '' ) +' value="Park">Park</option>'+
                                 '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Hall' ? 'selected' : '' ) +' value="Hall">Hall</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Office' ? 'selected' : '' ) +' value="Office">Office</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Buildings' ? 'selected' : '' ) +' value="Buildings">Buildings</option>'+
                                '<option '+ ( imgData['IMAGE_CATEGORY'] == 'Other' ? 'selected' : '' ) +' value="Other">Other</option>'+
                            '</select><br />'+
                            '<b>Name:</b>&nbsp;&nbsp;<input type="text" name="imgName_'+imgData['IMAGE_ID']+'[]" placeholder="Enter Name" value="'+imgData['IMAGE_DISPLAY_NAME']+'"><br />'+
                            '<b>Description:</b>&nbsp;&nbsp;<input type="text" name="imgDesc_'+imgData['IMAGE_ID']+'[]" placeholder="Enter Description" value="'+imgData['IMAGE_DESCRIPTION']+'"><br>'+
                            '<b>Priority:</b>&nbsp;&nbsp;<select name = "priority_'+imgData['IMAGE_ID']+'[]"><option value = "">Select Priority</option>'+
                            '<option '+ ( imgData['priority'] == '1' ? 'selected' : '' ) +' value = "1">1</option>'+
                            '<option '+ ( imgData['priority'] == '2' ? 'selected' : '' ) +' value = "2">2</option>'+
                            '<option '+ ( imgData['priority'] == '3' ? 'selected' : '' ) +' value = "3">3</option>'+
                            '<option '+ ( imgData['priority'] == '4' ? 'selected' : '' ) +' value = "4">4</option>'+
                            '<option '+ ( imgData['priority'] == '5' ? 'selected' : '' ) +' value = "5">5</option>'+
                            '<option '+ ( imgData['priority'] == '6' ? 'selected' : '' ) +' value = "6">6</option>'+
                            '<option '+ ( imgData['priority'] == '7' ? 'selected' : '' ) +' value = "7">7</option>'+
                            '<option '+ ( imgData['priority'] == '8' ? 'selected' : '' ) +' value = "8">8</option>'+
                            '<option '+ ( imgData['priority'] == '9' ? 'selected' : '' ) +' value = "9">9</option>'+
                            '<option '+ ( imgData['priority'] == '10' ? 'selected' : '' ) +' value = "10">10</option>'+
                             +'</select><br>'+
                            '<input type="hidden" name="img_id[]" value="'+imgData['IMAGE_ID']+'"><br>'+
                            '<input type="file" name="img_'+imgData['IMAGE_ID']+'[]"><br>'+
                            '<input type="radio" name="updateDelete_'+imgData['IMAGE_ID']+'[]" value=up> Update'+
                            '<input type="radio" name="updateDelete_'+imgData['IMAGE_ID']+'[]" value=del> Delete'+
                            '<input type="hidden" name="img_service_id_'+imgData['IMAGE_ID']+'[]" value="'+imgData['SERVICE_IMAGE_ID']+'">'+
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

function saveDetails() {
    var cateList = $('[name^="imgCate"]'),
        nameList = $('[name^="imgName"]'),
        descList = $('[name^="imgDesc"]'),
        pathList = $('[name^="img_path"]'),
        serviceIdList = $('[name^="img_service_id"]'),
        data     = {},
        res      = null;
    for( var __cnt = 0; __cnt < cateList.length; __cnt++ ) {
        var __id = cateList[ __cnt ].name.split('_')[1];
        var __data = {
            'IMAGE_ID'          : __id,
            'IMAGE_CATEGORY'    : cateList[ __cnt ].value.trim(),
            'IMAGE_DESCRIPTION' : descList[ __cnt ].value.trim(),
            'IMAGE_DISPLAY_NAME': nameList[ __cnt ].value.trim(),
            'IMAGE_NAME'        : pathList[ __cnt ].value.trim(),
            'SERVICE_IMAGE_ID': serviceIdList[ __cnt ].value.trim()
        };
        data[ __cnt ] = __data;
    }

    $.ajax({
        async: false,
        type : 'GET',
        url  : '/ajax/photo.php',
        data : "upPh="+JSON.stringify( data ),
        success: function( json ) {
            var __json = JSON.parse( json );
            if ( __json['result'] == true ) {
                alert('data saved');
            }
            else {
                alert('unable to save the complete data');
            }
        }
    });

    //  reloading the photos and their corresponding data
    getPhotos();
}
