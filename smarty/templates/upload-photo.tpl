</td>
</tr>
<tr>
    <td class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
        <table cellSpacing=0 cellPadding=0 border=0>
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
                        <table id="upload-tbl" cellSpacing=1 cellPadding=0 border=0>
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
                                        <label>Upload Pictures for</label>
                                    </td>
                                    <td>
                                        <span id="area-txt-name"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Upload Pictures</label>
                                    </td>
                                    <td>
                                        <input id="area-img" type="file" name="img[]" multiple>
                                    </td>
                                </tr>
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
<script type="text/javascript" src="js/photo.js"></script>