/**
 * User: swapnil
 * Date: 7/23/13
 * Time: 7:10 PM
 */
window.areaResponse = { suburb:0,locality:0,city:0, landmark:0 };
var selectedItem;

$(document).ready(function(){
    initVar();
    //console.log( window.areaResponse );
    var response = getSubLocData();
    updateDropDown( response );
    updateDisplayLocation();


    $.widget( "custom.catcomplete", $.ui.autocomplete, {
    _renderMenu: function( ul, items ) {
      var that = this,
        currentCategory = "";
      $.each( items, function( index, item ) {
        if ( item.table != currentCategory ) {
          ul.append( "<li class='ui-autocomplete-category'><strong>" + item.table + "</strong></li>" );
          currentCategory = item.table;
        }
        that._renderItemData( ul, item );
      });
    }
    });

    $( "#search" ).catcomplete({
      source: function( request, response ) {
        $.ajax({
          url: "/findSpecificAliases.php",
          dataType: "json",
          data: {
            featureClass: "P",
            style: "full",
            maxRows: 10,
            name_startsWith: request.term,
            cityId: window.areaResponse['city']
          },
          success: function( data ) {
            
            response( $.map( data, function( item ) {
              return {
                label: item.name,
                value: item.name,
                table: item.table,
                id: item.id,

              }
            }));
          }
        });
      },
      minLength: 3,
      select: function( event, ui ) {
        selectedItem = ui.item;
        $("#landmarkId").val(selectedItem.id);
        $("#landmarkName").val(selectedItem.label);
        window.areaResponse['landmark'] = selectedItem.id;
        areaTypeChanged( 'landmark' );
        //alert(selectedItem.label);
        //log( ui.item ?
         // "Selected: " + ui.item.label :
          //"Nothing selected, input was " + this.value);
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });


    


});

function initVar() {
    window.areaResponse['city'] = $('#city-list').val();
    window.areaResponse['suburb'] = $('#area-type-sub').val();
    window.areaResponse['locality'] = $('#area-type-loc').val();
    window.areaResponse['landmark'] = $('#landmarkId').val();
    
}

function getData() {
    var data = "";
    if ($("#search").val().trim()!='' && $("#landmarkId").val() > 0) {
         data = "&landmark="+window.areaResponse['landmark'];
         //alert(data);
    }
    else if ( window.areaResponse['city'] ) {
        data = "city="+window.areaResponse['city'];
        if ( window.areaResponse['suburb'] ) {
            data += "&suburb="+window.areaResponse['suburb'];
        }
        if ( window.areaResponse['locality'] ) {
            data += "&locality="+window.areaResponse['locality'];
        }
        if ( window.areaResponse['landmark'] ) {
            data += "&landmark="+window.areaResponse['landmark'];
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
    var imgType = $("#imgCat :selected").text();
    if(imgType.indexOf('Select') >= 0)
        imgType = "";
        console.log(window.areaResponse);

    if ( $('#search').val().trim()!='') {
        areaType = "landmark";
        value = $('#landmarkName').val().trim();
        $('#area-txt-name').html( areaType + " : " + value );
        //fill display name for landmarks
        if ( window.areaResponse['city'] != 0 ) {
            elementId = "drp-dwn-city-" + window.areaResponse['city'];
            var cityName = $('#'+elementId).html();
            if(imgType!="")
                $('#img-name').html(imgType+"-"+value+", "+cityName).val(imgType+"-"+value+", "+cityName); 
            else if(imgType=="")
                $('#img-name').html(value+", "+cityName).val(value+", "+cityName);
            else 
                $('#img-name').html("").val("");
     
        }
        else{
            if(imgType!="")
             $('#img-name').html(imgType+"-"+value).val(imgType+"-"+value);
            else if(imgType=="")
             $('#img-name').html(value).val(value);
            else 
                 $('#img-name').html("").val("");
        }
        $("#imgName").val($('#img-name').val()); 
        return;
    }  
    else if ( window.areaResponse['locality'] != 0 ) {
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
    if ( window.areaResponse['landmark'] != 0 ) {
       $('#area-txt-name').html( areaType + " : " + "hello"  );
    }
    areaName = $('#'+elementId).html().trim();
    $('#area-txt-name').html( areaType + " : " + areaName );

//fill display name
    var cityid = "drp-dwn-city-" + window.areaResponse['city'];
    var cityName = $('#'+cityid).html();
    if(imgType!="" && areaType != "City")
            $('#img-name').html(imgType+"-"+areaName+","+cityName).val(imgType+"-"+areaName+","+cityName);
    else if(imgType=="" && areaType != "City")
            $('#img-name').html(areaName+", "+cityName).val(areaName+", "+cityName);
    else if(imgType=="" && areaType == "City")
         $('#img-name').html(areaName).val(areaName);
    else  if(imgType!="" && areaType == "City")
        $('#img-name').html(imgType+"-"+areaName).val(imgType+"-"+areaName);
    else 
         $('#img-name').html("").val("");
    $("#imgName").val($('#img-name').val()); 
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
    var dataResult = getPhotosFromImageService();
    if ( dataResult['data'] != null && dataResult['data'].length > 0 ) {
        for( var __imgNo = 0; __imgNo < dataResult['data'].length; __imgNo++ ) {
            showThisPhoto( dataResult['data'][ __imgNo ]);
        }
        toggleSaveBtn( 'show' );
    }
    else {
        $('.image-block').html('No photos found for this area');
    }
}

function showThisPhoto( imgData ) {
    //console.log(imgData);
    imgData['IMAGE_DISPLAY_NAME'] = imgData['IMAGE_DISPLAY_NAME'] == null ? "" : imgData['IMAGE_DISPLAY_NAME'];
    imgData['IMAGE_CATEGORY'] = imgData['IMAGE_CATEGORY'] == null ? "" : imgData['IMAGE_CATEGORY'];
    imgData['IMAGE_DESCRIPTION'] = imgData['IMAGE_DESCRIPTION'] == null ? "" : imgData['IMAGE_DESCRIPTION'];
    imgData['priority'] = imgData['priority'] == null ? "" : imgData['priority'];
    imgData['CITY_ID'] = imgData['CITY_ID'] == null ? "" : imgData['CITY_ID'];
    imgData['LOCALITY_ID'] = imgData['LOCALITY_ID'] == null ? "" : imgData['LOCALITY_ID'];
    imgData['SUBURB_ID'] = imgData['SUBURB_ID'] == null ? "" : imgData['SUBURB_ID'];
    imgData['LANDMARK_ID'] = imgData['LANDMARK_ID'] == null ? "" : imgData['LANDMARK_ID'];

    imgData['SERVICE_IMAGE_PATH'] = imgData['SERVICE_IMAGE_PATH'] == null ? "" : imgData['SERVICE_IMAGE_PATH'];
    
    
    if(imgData['CITY_ID'] != ''){
        imgData['OBJECT_ID'] = imgData['CITY_ID'];
        imgData['OBJECT_TYPE'] = 'city';
    }
    else if(imgData['LOCALITY_ID'] != ''){
        imgData['OBJECT_ID'] = imgData['LOCALITY_ID'];
        imgData['OBJECT_TYPE'] = 'locality';
    }
    else if(imgData['SUBURB_ID'] != ''){
        imgData['OBJECT_ID'] = imgData['SUBURB_ID'];
        imgData['OBJECT_TYPE'] = 'suburb';
    }
    else if(imgData['LANDMARK_ID'] != ''){
        imgData['OBJECT_ID'] = imgData['LANDMARK_ID'];
        imgData['OBJECT_TYPE'] = 'landmark';
    }
    imgData['priority'] = imgData['priority'] == null ? "" : imgData['priority'];
    imgData['SERVICE_IMAGE_ID'] = imgData['SERVICE_IMAGE_ID'] == null ? "" : imgData['SERVICE_IMAGE_ID'];
    var template = '<div style="padding:5px; border:solid 1px #ccc; display:inline-block;">'+
                        '<div class="img-wrap" style="float:left;"> <img src="'+imgData['SERVICE_IMAGE_PATH']+'" width = 150 height = 100 /> </div>'+
                        '<div class="img-dtls" style="float:right; margin:0px 0px 0px 10px;">'+
                            '<b>Category:</b>&nbsp;&nbsp;'+imgData['IMAGE_CATEGORY'];
       template +='<input type = "hidden" name="imgCate_'+imgData['IMAGE_ID']+'[]" value = "'+imgData['IMAGE_CATEGORY']+'">';
                   
     template +=            '</select><br /><br />'+
                            '<b>Name:</b>&nbsp;&nbsp;<input type="text" name="imgName_'+imgData['IMAGE_ID']+'[]" placeholder="Enter Name" value="'+imgData['IMAGE_DISPLAY_NAME']+'"><br />'+
                            '<b>Description:</b>&nbsp;&nbsp;<input type="text" name="imgDesc_'+imgData['IMAGE_ID']+'[]" placeholder="Enter Description" value="'+imgData['IMAGE_DESCRIPTION']+'"><br>'+
                            '<b>Priority:</b>&nbsp;&nbsp;<select name = "priority_'+imgData['IMAGE_ID']+'[]"><option value = "999">Select Priority</option>'+
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
                            '<input type="hidden" name="locality_id" value="'+imgData['LOCALITY_ID']+'">'+
                            '<input type="hidden" name="suburb_id" value="'+imgData['SUBURB_ID']+'">'+
                            '<input type="hidden" name="city_id" value="'+imgData['CITY_ID']+'">'+
                            '<input type="hidden" name="landmark_id" value="'+imgData['LANDMARK_ID']+'">'+
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

function getPhotosFromImageService() {
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
                res = __json;
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

//landmark related code

