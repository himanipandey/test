</td>
</tr>
<tr xmlns="http://www.w3.org/1999/html">
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
                        <table id="upload-tbl" cellSpacing=1 cellPadding=0 border=0 style="width: 100%">
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
                                        <label class="lbl"> Select a City </label>
                                        <div class="valueField">
                                            <select id="city-list" name="cityId" onchange="areaTypeChanged('city')">
                                                {foreach from=$cityList key=id item=cityName}
                                                    <option {if $cityId==$cityName.id}selected{/if} value="{$cityName.id}" id="drp-dwn-city-{$cityName.id}">
                                                        <span>{$cityName.name}</span>
                                                    </option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="lbl"> Select a Suburb </label>
                                        <div class="valueField">
                                            <input type="hidden" name="upImg" value="1">
                                            <select name="suburbId" id="area-type-sub" onchange="areaTypeChanged('suburb')">

                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="lbl"> Select a Locality </label>
                                        <div class="valueField">
                                            <input type="hidden" name="upImg" value="1">
                                            <select name="localityId" id="area-type-loc" onchange="updateDisplayLocation()">

                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="lbl">Upload Pictures for</label>
                                        <div class="valueField">
                                            <span id="area-txt-name" class="stronger"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="lbl">Upload Pictures</label>
                                        <div class="valueField">
                                            <input id="area-img" type="file" name="img[]" multiple  /><br />
                                            <button>Upload</button>
                                        </div>
                                    </td>
                                </tr>
                                    <tr>
                                        <td>
                                            <div style="height: 31px; float: left">
                                                <button onclick="getPhotos(); return false;">Update Photos Description</button>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                            </tbody>
                        </table>

                        <div class="image-block">
                            {if isset($uploadedImage)}
                                {foreach from=$uploadedImage item=row}
                                    <div style="padding:5px; border:solid 1px #ccc; display:inline-block;">
                                        <div class="img-wrap"> <img src="/images_new/locality/thumb_{$row.IMAGE_NAME}" /> </div>
                                        <div class="img-dtls">
                                            <select name="imgCate_{$row.IMAGE_ID}">
                                                <option value="">Category</option>
                                                <option value="Mall">Mall</option>
                                                <option value="Hospital">Hospital</option>
                                                <option value="Map">Map</option>
                                                <option value="School">School</option>
                                                <option value="Road">Road</option>
                                                <option value="Other">Other</option>
                                            </select><br />
                                            <input type="text" name="imgName_{$row.IMAGE_ID}" placeholder="Enter Name"><br />
                                            <input type="text" name="imgDesc_{$row.IMAGE_ID}" placeholder="Enter Description" />
                                            <input type="hidden" name="img_path_{$row.IMAGE_ID}" value="{$row.IMAGE_NAME}" />
                                            <input type="hidden" name="img_service_id_{$row.IMAGE_ID}" value="{$row.SERVICE_IMAGE_ID}" />
                                        </div>
                                        <div class="clearfix" style="clear:both;"></div>
                                    </div>
                                {/foreach}
                            {/if}
                        </div>
                        <div class="clearfix" style="clear:both;"></div>
                        {if isset($uploadedImage)}
                            <button class="btn-save" style="border:solid 1px #000; padding:5px 10px; background:#333; border-radius:5px; color:#fff; font:bold 12px Arial; text-decoration:none; " id="s-btn" onclick="saveDetails(); return false;">SAVE</button>
                        {else}
                            <button class="btn-save" style="display:none; border:solid 1px #000; padding:5px 10px; background:#333; border-radius:5px; color:#fff; font:bold 12px Arial; text-decoration:none; " id="s-btn" onclick="saveDetails(); return false;">SAVE</button>
                        {/if}
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
<div id="staticData" style="display: none;">
<div id="city-id">{$cityId}</div>
{if !empty($suburbId)}
<div id="suburb-id">{$suburbId}</div>
{/if}
{if !empty($localityId)}
<div id="locality-id">{$localityId}</div>
{/if}
</div>
<script type="text/javascript" src="js/photo.js"></script>
