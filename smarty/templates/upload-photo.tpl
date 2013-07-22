</td>
</tr>
<tr>
    <td class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
        <table cellSpacing=0 cellPadding=0 width="100%" border=0>
            <tbody>
                <tr>
                    <td width=224 height=25>&nbsp;</td>
                    <td width=10>&nbsp;</td>
                    <td width=866>&nbsp;</td>
                </tr>
                <tr>
                    <td class=paddingltrt10 vAlign=top align=middle bgColor=#ffffff>
                        {include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
                    </td>
                    <td vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</td>
                    <td vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
                        <table cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=1>
                            <tbody>
                                <tr>
                                    <td class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                                        <table cellSpacing=0 cellPadding=0 width="99%" border=0><tbody>
                                                <tr>
                                                    <td class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Upload photos for Locality/Suburb/City</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                     </td>
                                </tr>
                                {if !empty($message)}
                                    <tr>
                                        <td>
                                            <div class="{$message['type']}" style="text-align: left;">
                                                {$message['content']}
                                            </div>
                                        </td>
                                    </tr>
                                {/if}
                                <form method="post" onsubmit="return verifyPhotoFormData();" enctype="multipart/form-data">
                                <tr>
                                    <td>
                                        <input type="hidden" name="upImg" value="1">
                                        <select name="areaType" id="area-type" onchange="areaTypeChanged()">
                                            <option {$selectedAreaType.locality} value="locality">Locality</option>
                                            <option {$selectedAreaType.suburb} value="suburb">Suburb</option>
                                            <option {$selectedAreaType.city} value="city">City</option>
                                        </select>
                                    </td>
                                </tr>
                                {if count($areaList)>0}
                                <tr>
                                    <td>
                                       <select id="area-list" name="areaId">
                                            {foreach from=$areaList key=id item=areaName}
                                                <option {if $areaId==$areaName.id}selected{/if} value="{$areaName.id}">
                                                <span>{$areaName.name}</span>
                                                    {if isset($areaName.city)}
                                                    <span> === </span>
                                                    <span>{$areaName.city}</span>
                                                    {/if}
                                                </option>
                                            {/foreach}
                                        </select>
                                        <input type="file" name="img[]" multiple>
                                    </td>
                                </tr>
                                {/if}
                                <tr>
                                    <td colspan="0">
                                        <div style="height: 31px; float: left">
                                            <button>Upload</button>
                                        </div>
                                    </td>
                                </tr>
                                </form>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
<script type="text/javascript">
    window.areaResponse = { suburb:-1,locality:-1,city:-1 };

    function verifyPhotoFormData() {
        return true;
        return false;
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
</script>
