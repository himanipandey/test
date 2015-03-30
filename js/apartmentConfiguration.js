$(document).ready(function () {

    $(".insertProject").live('click', function () {
        var rowId = jQuery(this).attr('rel');

        var requestURL = "";
        $(this).parent().parent().find('input,select').each(function (index) {

            var insertValue = $(this).val();

            if ((insertValue.indexOf('+') > -1))
            {
                insertValue = insertValue.replace("+", "@");
            }
            if ($(this).attr('tempName') == 'txtCarpetAreaInfo') {
                if ($(this).attr('checked'))
                    requestURL += $(this).attr('tempName') + "=" + 1 + "&";
                else
                    requestURL += $(this).attr('tempName') + "=" + 0 + "&";
            } else {
                requestURL += $(this).attr('tempName') + "=" + insertValue + "&";
            }
        });
        requestURL = requestURL.substring(0, requestURL.length - 1);
        //alert(requestURL);
        $.ajax({
            url: "insertOptions.php?d=" + rowId,
            type: "post",
            dataType: "JSON",
            data: requestURL,
            // callback handler that will be called on success
            success: function (response) {

                var response = $.parseJSON(response);

                console.log(response);

                var optionID = response['optionId'];

                var html = '';
                var roomCategory = $("#roomCategory").html();

                html += "<div><form name='f1' method='post' id='f1'><div><input type='hidden' name='optionId' value='" + optionID + "' ></div>";

                html += "<TABLE>";

                html += "<TR>\n\
                            <TH><b>Type</b></TH>\n\
                            <TH><b>Category Room</b></TH>\n\
                            <TH><b>Length(ft)</b><font color='red'>*</font></TH>\n\
                            <TH><b>Length(inch)</b></TH>\n\
                            <TH><b>Breath(ft)</b><font color='red'>*</font></TH>\n\
                            <TH><b>Length(inch)</b></TH>\n\
                        </TR>";

                var j = 1;


                if (typeof response['roomTypes']['bedrooms'] != 'undefined')
                {

                    for (var i = 1; i <= response['roomTypes']['bedrooms']['qty']; i++) {
                        length = '';
                        breath = '';
                        lengthFT = '';
                        breathFT = '';
                        length_inch = '';
                        breath_inch = '';
                        bed_cats = response['roomTypes']['id'];
                        //fetching existing sizes
                        if (typeof response['roomTypes']['bedrooms']['sizes'] != 'undefined') {
                            roomsizes = response['roomTypes']['bedrooms']['sizes'][i - 1];
                            roomsizes = roomsizes.split('@');
                            length = roomsizes[0].split('-');
                            breath = roomsizes[1].split('-');
                        }


                        if (typeof length[0] != 'undefined')
                            lengthFT = length[0];
                        if (typeof length[1] != 'undefined')
                            length_inch = length[1];
                        if (typeof breath[0] != 'undefined')
                            breathFT = breath[0];
                        if (typeof breath[1] != 'undefined')
                            breath_inch = breath[1];

                        bedRoomCat = 0;

                        // bedRoomCategory = bedRoomCategory.replace('value="' + bed_cats + '"', 'value="' + bed_cats + '" selected');
                        if (i > 1) {
                            bedRoomCat = 2;
                            bedRoomCategory = roomCategory.replace("roomCategory", "roomCategory_" + 2);
                            bedRoomCategory = bedRoomCategory.replace('value="' + 2 + '"', 'value="' + 2 + '" selected');
                        } else {
                            bedRoomCat = 1;
                            bedRoomCategory = roomCategory.replace("roomCategory", "roomCategory_" + 1);
                            bedRoomCategory = bedRoomCategory.replace('value="' + i + '"', 'value="' + 1 + '" selected');
                        }


                        html += "<TR>\n\
                                    <TD style='font-size:14px'>Bedroom " + i + " : <input type='hidden' name='roomCategory[" + j + "][title]' value='Bedroom" + i + "'></TD>\n\
                                    <TD>" + bedRoomCategory + " <input type='hidden' name='roomCategory[" + j + "][id]' value='" + bedRoomCat + "'></TD></TD>\n\
                                    <TD><input style='width:100px' type='text' name='roomCategory[" + j + "][length_ft]'  onkeypress='return isNumberKey(event)' value='" + lengthFT + "' /> </TD>\n\
                                    <TD><input style='width:100px' type='text' name='roomCategory[" + j + "][length_inch]' onkeypress='return isNumberKey(event)' value='" + length_inch + "' /></TD>\n\
                                    <TD><input style='width:100px' type='text' name='roomCategory[" + j + "][breath_ft]' onkeypress='return isNumberKey(event)' value='" + breathFT + "' /></TD>\n\
                                    <TD><input style='width:100px' type='text' name='roomCategory[" + j + "][breath_inch]' onkeypress='return isNumberKey(event)' value='" + breath_inch + "' /></TD>\n\
                                </TR>";
                        j++;
                    }
                }

                //bathrooms toilets
                if (typeof response['roomTypes']['Toilet'] != 'undefined')
                {
                    for (var i = 1; i <= response['roomTypes']['Toilet']['qty']; i++) {
                        for (var i = 1; i <= response['roomTypes']['Toilet']['qty']; i++) {
                            if (typeof response['roomTypes']['Toilet']['sizes'] != 'undefined') {
                                kroomSizes = response['roomTypes']['Toilet']['sizes'];
                            } else {
                                kroomSizes = '';
                            }

                            html += "<TR>\n\
                                    <TD style='font-size:14px'>Bathroom " + i + " :  <input type='hidden' name='roomCategory[" + j + "][title]' value='Bathroom" + i + "'></TD>\n\
                                    <TD><input type='hidden' name='roomCategory[" + j + "][id]' value='" + response['roomTypes']['Toilet']['id'] + "'></TD>\n\
                                    <TD><input style='width:100px' type='text' name='roomCategory[" + j + "][length_ft]' onkeypress='return isNumberKey(event)' value='" + roomLength(kroomSizes, i - 1, 'ft') + "' /></TD>\n\
                                    <TD><input style='width:100px' type='text' name='roomCategory[" + j + "][length_inch]' onkeypress='return isNumberKey(event)' value='" + roomLength(kroomSizes, i - 1, 'inch') + "' /></TD>\n\
                                    <TD><input style='width:100px' type='text' name='roomCategory[" + j + "][breath_ft]' onkeypress='return isNumberKey(event)' value='" + roomBreath(kroomSizes, i - 1, 'ft') + "' /></TD>\n\
                                    <TD><input style='width:100px' type='text' name='roomCategory[" + j + "][breath_inch]' onkeypress='return isNumberKey(event)' value='" + roomBreath(kroomSizes, i - 1, 'inch') + "' /></TD>\n\
                                </TR>";
                            j++;
                        }
                    }
                }



                //creating other room types
                $.each(response['roomTypes'], function (room, roomValues) {

                    if (room != 'bedrooms' && room != 'Toilet') {
                        if (typeof roomValues['sizes'] != 'undefined') {
                            kroomSizes = roomValues['sizes'];
                        } else {
                            kroomSizes = '';
                        }
                        if (typeof roomValues['qty'] != 'undefined')
                        {
                            for (var i = 1; i <= roomValues['qty']; i++) {


                                html += "<TR>\n\
                                    <TD style='font-size:14px'>" + room + i + " :  <input type='hidden' name='roomCategory[" + j + "][title]' value='" + room + "" + i + "'></TD>\n\
                                    <TD><input type='hidden' name='roomCategory[" + j + "][id]' value='" + roomValues['id'] + "'></TD>\n\
                                    <TD><input style='width:100px' type='text' name='roomCategory[" + j + "][length_ft]' onkeypress='return isNumberKey(event)' value='" + roomLength(kroomSizes, i - 1, 'ft') + "' /></TD>\n\
                                    <TD><input style='width:100px' type='text' name='roomCategory[" + j + "][length_inch]' onkeypress='return isNumberKey(event)' value='" + roomLength(kroomSizes, i - 1, 'inch') + "' /></TD>\n\
                                    <TD><input style='width:100px' type='text' name='roomCategory[" + j + "][breath_ft]' onkeypress='return isNumberKey(event)' value='" + roomBreath(kroomSizes, i - 1, 'ft') + "' /></TD>\n\
                                    <TD><input style='width:100px' type='text' name='roomCategory[" + j + "][breath_inch]' onkeypress='return isNumberKey(event)' value='" + roomBreath(kroomSizes, i - 1, 'inch') + "' /></TD>\n\
                                </TR>";
                                j++;
                            }
                        } else {

                            html += "<TR>\n\
                                    <TD style='font-size:14px'>" + room + ":  <input type='hidden' name='roomCategory[" + j + "][title]' value='" + room + "'></TD>\n\
                                    <TD><input type='hidden' name='roomCategory[" + j + "][id]' value='" + roomValues['id'] + "'></TD>\n\
                                    <TD ><input style='width:100px' type='text' name='roomCategory[" + j + "][length_ft]' onkeypress='return isNumberKey(event)' value='" + roomLength(kroomSizes, 0, 'ft') + "' /></TD>\n\
                                    <TD ><input style='width:100px' type='text' name='roomCategory[" + j + "][length_inch]' onkeypress='return isNumberKey(event)' value='" + roomLength(kroomSizes, 0, 'inch') + "' /></TD>\n\
                                    <TD><input style='width:100px' type='text' name='roomCategory[" + j + "][breath_ft]' onkeypress='return isNumberKey(event)' value='" + roomBreath(kroomSizes, 0, 'ft') + "' /></TD>\n\
                                    <TD><input style='width:100px' type='text' name='roomCategory[" + j + "][breath_inch]' onkeypress='return isNumberKey(event)' value='" + roomBreath(kroomSizes, 0, 'inch') + "' /></TD>\n\
                                </TR>";
                            j++;
                        }
                    }
                });




                html += "</TABLE>";

                html += "<input type='hidden' name='count' value='" + j + "'>";
                html += "<input type='hidden' name='rowId' value='" + rowId + "'>";

                html += "<br><br><div><input type='button' name='Save' value='Save' onClick='submitroomCategory()'/></div></form></div>";

                if($('#room-type-auth').val()){
                    html += "<br/><br/><input type='text' name='newCategory' id='newCategory'> <input type='button' name='addCategory' onclick='addRoomCategory();' value='Create New Room Type' ><br/><br/>"
                }
                


                $.fancybox({
                    'content': html,
                    'onCleanup': function () {
                        //	$("#row_"+rowId).remove();
                    }

                });


                // log a message to the console
                //console.log("Hooray, it worked!");
            },
            // callback handler that will be called on error
            error: function (jqXHR, textStatus, errorThrown) {
                // log the error to the console
                console.log(
                        "The following error occured: " +
                        textStatus, errorThrown
                        );
            },
        });


    });
});


function submitroomCategory() {
    var i = jQuery("#f1").serialize();

    $.ajax({
        url: "enquiryRoom.php",
        type: "post",
        data: i,
        // callback handler that will be called on success
        success: function (response) {
            if (response.trim() == '')
                jQuery.fancybox.close();
            else
                alert(response);

        }
    });

}

function addRoomCategory() {
    var newRoom = $('#newCategory').val().trim();
    if (newRoom) {
        $.ajax({
            url: "enquiryRoom.php",
            type: "post",
            data: "newRoom=" + newRoom,
            // callback handler that will be called on success
            success: function (response) {

                jQuery.fancybox.close();
                alert(response);

            }
        });
    }

}


function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode == 99 || charCode == 118)
        return true;
    if (charCode > 31 && (charCode < 46 || charCode > 57) || (charCode == 13))
        return false;

    return true;
}

function roomLength(arrroomsizes, i, tp) {
    roomsizes = arrroomsizes[i];
    length = '';
    //fetching existing sizes
    if (typeof roomsizes != 'undefined' && roomsizes != '') {
        roomsizes = roomsizes.split('@');
        length = roomsizes[0].split('-');

        if (tp == 'ft')
            return length[0];
        else
            return length[1];
    }

    return length;

}
function roomBreath(arrroomsizes, i, tp) {

    roomsizes = arrroomsizes[i];
    breath = '';
    //fetching existing sizes
    if (typeof roomsizes != 'undefined' && roomsizes != '') {
        console.log('breathe:', roomsizes);
        roomsizes = roomsizes.split('@');
        breath = roomsizes[1].split('-');
        if (tp == 'ft')
            return breath[0];
        else
            return breath[1];
    }

    return breath;
}
