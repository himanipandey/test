
function addBrokerToDB( name, address ) {
    var postJson = "{\"action\":\"add_broker_to_db\", \"name\":\""+name+"\", \"address\":\""+address+"\"}";
    var brokerId = 0;
    $.ajax({
        async   : false,
        type    : 'POST',
        url     : 'ajax-supply.php',
        data    : 'json='+postJson,
        dataType: 'json',
        success : function( json ) {
            var response = JSON.parse(json);
            if ( response['result'] == "success" ) {
                brokerId = response['brokerId'];
            }
            else {
                brokerId = -1;
            }
        }
    });

    return brokerId;
}

function contactTypeChange( id ) {
    if ( id == 'owner' ) {
        $('#broker-radio').attr( 'disabled', 'disabled' );
        $('#contact-name').removeAttr( 'disabled' );
        $('#email').removeAttr( 'disabled' );
        $('#mobile-no').removeAttr( 'disabled' );
        resetContactInfo();
    }
    else {
        $('#broker-radio').removeAttr( 'disabled' );
        $('#contact-name').attr( 'disabled', 'disabled' );
        $('#email').attr( 'disabled', 'disabled' );
        $('#mobile-no').attr( 'disabled', 'disabled' );
    }
}

function fillBrokerInfo() {
    var broId = document.getElementById('broker-radio').value;
    if ( broId !== undefined && broId !== "" ) {
        var val = document.getElementById('broId-'+broId).getAttribute('other-data');
        val = val.split("_##_");
        var name = document.getElementById('broId-'+broId).innerHTML,
            email = ( val[0] !== undefined ) ? val[0] : "",
            ph = ( val[1] !== undefined ) ? val[1] : "";

        setContactInfo( name, email, ph );
    }
    else {
        resetContactInfo();
    }
}

function setContactInfo( name, email, ph ) {
    document.getElementById('contact-name').value = name;
    $('#contact-name').removeClass('err');
    document.getElementById('email').value = email;
    $('#email').removeClass('err');
    document.getElementById('mobile-no').value = ph;
    $('#mobile-no').removeClass('err');
}

function resetContactInfo() {
    document.getElementById('broker-radio').value = "";
    $('#broker-radio').removeClass('err');
    document.getElementById('contact-name').value = "";
    $('#contact-name').removeClass('err');
    document.getElementById('email').value = "";
    $('#email').removeClass('err');
    document.getElementById('mobile-no').value = "";
    $('#mobile-no').removeClass('err');
}

function verifyData( id ) {
    id = id.trim();
    var allOk = true;
    if ( id != 'tow-det' && id != 'ava-prop' ) {
        var val = document.getElementById(id).value.trim();
    }
    else {
        var val = '';
    }

    switch ( id ) {
        case 'contact-name':
            if ( /^[a-zA-Z .]+$/.test( val ) ) {
                $('#'+id).removeClass('err');
            }
            else {
                $('#'+id).addClass('err');
                allOk = false;
            }
            break;
        case 'email':
            if ( /^[a-zA-Z0-9]+[a-zA-Z0-9._]+@[a-zA-Z_.]+?\.[a-zA-Z]{2,5}$/.test( val ) ) {
                $('#'+id).removeClass('err');
            }
            else {
                $('#'+id).addClass('err');
                allOk = false;
            }
            break;
        case 'mobile-no':
            if ( /^[1-9][0-9]{9}$/.test( val ) ) {
                $('#'+id).removeClass('err');
            }
            else {
                $('#'+id).addClass('err');
                allOk = false;
            }
            break;
        case 'park-charge':
        case 'oth-charge':
        case 'dmd-rate':
        case 'login-rate':
        case 'amt-paid':
            if ( id != 'login-rate' && val == "" ) {      //  skip validation
                break;
            }
            var num = parseFloat( val );
            if ( !isNaN( val ) && isFinite( num ) ) {
                $('#'+id).removeClass('err');
            }
            else {
                $('#'+id).addClass('err');
                allOk = false;
            }
            break;
        case 'address':
        case 'desc':
            val = $('#'+id).attr('value');
        case 'flat-no':
            if ( val == "" ) {      //  skip validation
                break;
            }
            if ( val.length > 0 ) {
                $('#'+id).removeClass('err');
            }
            else {
                $('#'+id).addClass('err');
                allOk = false;
            }
            break;
        case 'floor-no':
            if ( val == "" ) {      //  skip validation
                break;
            }
            if ( /^\d+$/.test( val ) ) {
                $('#'+id).removeClass('err');
            }
            else {
                $('#'+id).addClass('err');
                allOk = false;
            }
            break;
        case 'in-price':
            if ( val == "" ) {      //  skip validation
                break;
            }
            if ( val.length > 0 && isNaN( val ) ) {
                $('#'+id).addClass('err');
                allOk = false;
            }
            else {
                $('#'+id).removeClass('err');
            }
            /*      This code will reset the demand price to zero
            //      depending on indicative price. Commenting this
            //      code to remove validation for now.
            if ( !isNaN( val ) ) {
                if ( parseInt( val ) > 0 ) {
                    //  reset demand price
                    $('#dmd-rate').removeClass('err');
                    $('#dmd-rate').attr('value', 0);
                }
            }
            //*/
            break;
        case 'ava-prop':
            val = $('input[name="available_prop_id"]:checked').val();
            val = parseInt( val );
            if ( isNaN( val ) ) {
                $('#'+id).addClass('err');
                $('#project-name').addClass('err');
                allOk = false;
            }
            else {
                $('#'+id).removeClass('err');
                $('#project-name').removeClass('err');
            }
            break;
        case 'tow-det':
            val = $('input[name="tower_id"]:checked').val();
            val = parseInt( val );
            if ( isNaN( val ) ) {
                $('#'+id).addClass('err');
                $('#project-name').addClass('err');
                allOk = false;
            }
            else {
                $('#'+id).removeClass('err');
                $('#project-name').removeClass('err');
            }
            break;
        default :
            allOk = false;
    }
    return allOk;
}

function verifyForm() {
    var idList = ['contact-name', 'email', 'mobile-no', 'park-charge', 'oth-charge', 'dmd-rate', 'login-rate', 'amt-paid', 'in-price', 'flat-no', 'floor-no', 'address', 'desc', 'ava-prop', 'tow-det'],
        verified = true;
    for( var __count = 0; __count < idList.length; __count++) {
        verified = verifyData( idList[__count] ) && verified;
    }
    //  check if one of demand and indicative rate is valid
    var dr = document.getElementById('dmd-rate').value.trim(),
        ip = document.getElementById('in-price').value.trim();
    dr = parseInt( dr );
    ip = parseInt( ip );
    if ( !( dr > 0 || ip > 0 ) ) {
        alert('Please enter Demand rate OR Indicative rate');
        verified = false;
    }
    else if ( !verified ) {
        alert('Please correct the fields marked in red');
    }
    return verified;
}

function getProjects() {
    var pro = document.getElementById('project-name').value.trim();
    if ( pro.length < 3 ) {
        $('#auto-sug').hide();
        return;
    }
    var data = {'action':'get_project','query':pro};
    $.ajax({
        url: "ajax-supply.php",
        data: data,
        success: function( json ) {
            $('#auto-sug').html('');

            json = JSON.parse( json );
            if ( json.length == 0 ) {
                return;
            }
            for ( var __cnt = 0; __cnt < json.length; __cnt++ ) {
                var obj = json[__cnt];
                var projectId = obj.id.split('-').pop();
                var html = '<a onclick="fillProjectInfo('+projectId+'); return false;"><li class="auto-sug-el">'+obj.TYPEAHEAD_DISPLAY_TEXT+'</li></a>';
                $('#auto-sug').append( html );
                //console.log(id)
            }
            $('#auto-sug').show();
        },
        error: function() {
            console.log('error !!');
        }
    });
}

function fillProjectInfo( proId ) {
    $('#auto-sug').hide();
    var data = {'action':'get_project_detail', 'id':proId};
    $.ajax({
        url: "ajax-supply.php",
        data: data,
        success: function( json ) {
            $('#act-project-name').show();
            $('#project-name').attr('value', '');

            json = JSON.parse( json );
            var projData = json.projDetail;

            if ( projData != null ) {
                $('#pro-city').attr('value', projData.CITY);
                $('#pro-loc').attr('value', projData.LOCALITY);
                $('#pro-build').attr('value', projData.BUILDER_NAME);
                $('#pos-date').attr('value', projData.COMPLETION_DATE);
                $('#act-project-name').attr('value', projData.PROJECT_NAME);
            }
            var yearNow = new Date().getFullYear(),
                year = projData.COMPLETION_DATE.split(' ').pop();
            year = parseInt( year );
            if ( yearNow > year ) {
                var diffYear = yearNow - year;
                $('#pro-cons-date').attr('value', diffYear);
            }
            else {
                $('#pro-cons-date').attr('value', '-NA-');
            }
            $('#project-id').attr('value', proId);

            //  reset flat, floor and address info
            $('#flat-no').attr('value', '');
            $('#floor-no').attr('value', '');
            $('#address').attr('value', '');

            $('#tow-det').html('');
            if ( json.towerDetail != null ) {
                createTowerTable( json.towerDetail );
            }
            $('#ava-prop').html('');
            if ( json.availableDetail != null ) {
                createPropertyTable( json.availableDetail );
            }

            $('#add-new-prop').attr("onclick", "window.open('newdetail.php?ac=prop&id="+proId+"','Add Property','height=500,width=550');return false;");
            $('#add-new-prop').show();
            $('#add-new-tow').attr("onclick", "window.open('newdetail.php?ac=tow&id="+proId+"','Add Tower','height=500,width=550');return false;");
            $('#add-new-tow').show();
        },
        error: function() {
            console.log('error !!');
        }
    });
}

function createPropertyTable( propertyDetail ) {
    $('#ava-prop').html('');
    for( var __count = 0; __count < propertyDetail.length; __count++ ) {
        var value = propertyDetail[__count].OPTIONS_ID,
            unitName = propertyDetail[__count].UNIT_NAME,
            otherText = propertyDetail[__count].UNIT_TYPE + " ("+ propertyDetail[__count].SIZE +" "+ propertyDetail[__count].MEASURE +")",
            mailText = propertyDetail[__count].UNIT_NAME + " ("+ propertyDetail[__count].SIZE +" "+ propertyDetail[__count].MEASURE +")";

        var html = '<span class="conf-sub-tab"><table border="0"><tr><td rowspan="2"><input type="radio" name="available_prop_id" value="'+value+'" onclick="updateDemand('+propertyDetail[__count].SIZE+',\''+mailText+'\')"></td><td><div class="prop-type">'+unitName+'</div>'+otherText+'</td></tr></table></span>';
        $('#ava-prop').append( html );
    }
}

function createTowerTable( towerDetail ) {
    $('#tow-det').html('');
    for( var __count = 0; __count < towerDetail.length; __count++ ) {
        var value = towerDetail[__count].TOWER_ID,
            unitName = towerDetail[__count].TOWER_NAME,
            otherText = "Flats: "+towerDetail[__count].NO_OF_FLATS+", Floors: "+towerDetail[__count].NO_OF_FLOORS;

        var html = '<span class="conf-sub-tab"><table border="0"><tr><td rowspan="2"><input type="radio" name="tower_id" id="tower-id-'+value+'" value="'+value+'"></td><td><div id="tower-name-'+value+'" class="prop-type">'+unitName+'</div>'+otherText+'</td></tr></table></span>';
        $('#tow-det').append( html );
    }
}

function addToAddress( curAdd, addEl ) {
    if ( addEl == null || addEl == undefined ) {
        return curAdd;
    }
    if ( curAdd == null || curAdd == undefined || curAdd == "" ) {
        curAdd = addEl;
    }
    else {
        curAdd = curAdd +", "+ addEl;
    }
    return curAdd;
}

function createAdd() {
    var curAdd = $('#address').attr('value');
    var add = "";
    if ( curAdd == undefined || curAdd.trim() == "" ) {
        var flat = $('#flat-no').attr('value');
        if ( flat != null ) {
            add = addToAddress( add, flat );
        }
        var tower_id = $('form #tow-det input[type=radio]:checked').attr('value');
        if ( tower_id != null ) {
            var tower_name = $('#tower-name-'+tower_id).html();
            add = addToAddress( add, tower_name );
        }
        var project_name = $('#act-project-name').attr('value');
        var city = $('#pro-city').attr('value');
        var locality = $('#pro-loc').attr('value');
        add = addToAddress( add, project_name );
        add = addToAddress( add, locality );
        add = addToAddress( add, city );
        $('#address').attr('value', add);
    }
}

function validateImg() {
    var img = document.getElementById('in-img');
    var val = true;
    if ( img ) {
        val = validateThisImg( img );
    }
    if ( val ) {
        img = document.getElementById('ex-img');
        if ( img ) {
            val = val && validateThisImg( img );
        }
    }
    return val;
}

function validateThisImg( img ) {
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

function updateDemand( area, otherText ) {
    $('#ava-prop-mail').attr('value', otherText);
    var in_price = $('#in-price').attr('value').trim();
    if ( in_price.length > 0 ) {
        return;
    }
    area = parseInt( area );
    if ( area > 0 ) {
        //  get demand rate unit
        var unit = $('#dmd-rate-unit').attr('value');
        var dmd = $('#dmd-rate').attr('value');
        dmd = parseInt( dmd );
        if ( dmd > 0 ) {
            if ( unit == 2 ) {
                dmd = dmd / 9;    //  sq yard to sq feet
            }
            in_price = area * dmd;
            //  update in-price
            $('#in-price').removeClass('err');
            $('#in-price').attr('value', in_price);
        }
    }
}
