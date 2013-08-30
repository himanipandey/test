</td>
</tr>
<tr>
    <td class="white-bg paddingright10" vAlign=top align=middle>
        <table cellSpacing=0 cellPadding=0 border=0>
            <tbody>
                <tr>
                    <td width=224 height=25>&nbsp;</td>
                    <td width=10>&nbsp;</td>
                    <td width=866>&nbsp;</td>
                </tr>
                <tr>
                    <td class=paddingltrt10 vAlign=top align=middle>
                        {include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
                    </td>
                    <td vAlign=center align=middle width=10>&nbsp;</td>
                    <td vAlign=top align=middle width="100%" height=400>
                        <table id="upload-tbl" cellSpacing=1 cellPadding=0>
                            <tbody>
                                <tr>
                                    <td class="hdng" colspan="2">
                                        <IMG height="18" hspace="5" width="18" src="images/arrow.gif">
                                        Upload photos for Locality/Suburb/City
                                    </td>
                                </tr>
                                {if !empty($message)}
                                    <tr>
                                        <td>
                                            <div class="msg {$message['type']}">
                                                {$message['content']}
                                            </div>
                                        </td>
                                    </tr>
                                {/if}
                                <form method="post" onsubmit="return verifyPhotoFormData();" enctype="multipart/form-data">
                                <tr>
                                    <td>
                                        <label> Select a City </label>
                                    </td>
                                    <td style="width: 375px;">
                                        <select id="city-list" name="cityId" onchange="areaTypeChanged('city')">
                                            {foreach from=$cityList key=id item=cityName}
                                                <option {if $cityId==$cityName.id}selected{/if} value="{$cityName.id}" id="drp-dwn-city-{$cityName.id}">
                                                    <span>{$cityName.name}</span>
                                                </option>
                                            {/foreach}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label> Select a Suburb </label>
                                    </td>
                                    <td style="width: 375px;">
                                        <input type="hidden" name="upImg" value="1">
                                        <select name="suburbId" id="area-type-sub" onchange="areaTypeChanged('suburb')">

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label> Select a Locality </label>
                                    </td>
                                    <td style="width: 375px;">
                                        <input type="hidden" name="upImg" value="1">
                                        <select name="localityId" id="area-type-loc" onchange="updateDisplayLocation()">

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Update Pictures for</label>
                                    </td>
                                    <td>
                                        <span id="area-txt-name"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="height: 31px; float: left">
                                            <button>Fetch Photos</button>
                                        </div>
                                    </td>
                                </tr>
                                </form>
                            </tbody>
                        </table>
                        <div class="image-block" style="float:left; margin:10px;">
                            <div style="padding:5px; border:solid 1px #ccc; display:inline-block;">
                                <div class="img-wrap" style="float:left;"> <img src="http://git.proptiger.com/images/locality/thumb_locality_502_0_1377864215.png" /> </div>
                                <div class="img-dtls" style="float:right; margin:0px 0px 0px 10px;">
                                    <input type="text" name="capt" placeholder="Enter Caption"><br />
                                    <input type="text" name="desc" placeholder="Enter Description" />
                                </div>
                                <div class="clearfix" style="clear:both;"></div>
                            </div>
                        </div>
                        <div class="clearfix" style="clear:both;"></div>
                        <a href="#" class="btn-save" style="border:solid 1px #000; padding:5px 10px; background:#333; border-radius:5px; color:#fff; font:bold 12px Arial; text-decoration:none;" id="">SAVE</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
<script type="text/javascript" src="js/photo.js"></script>