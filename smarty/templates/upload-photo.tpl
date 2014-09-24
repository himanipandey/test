


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
                                        Upload photos for Locality/Landmark
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
                                        <label class="lbl"> Upload Photo For </label>
                                        <div class="valueField">
                                            <input type="radio" name="cb" id="cb" value="0" checked='checked' onclick>Landmark &nbsp; &nbsp;  <input type="radio" name="cb" id="cb" value="1">Locality &nbsp; &nbsp; 
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <label class="lbl"> Select a City </label>
                                        <div class="valueField">
                                            <select id="city-list" name="cityId" onchange="areaTypeChanged('city')">
                                                <option value="">Select City</option>
                                                {foreach from=$cityList key=id item=cityName}
                                                    <option {if $cityId==$cityName.id}selected{/if} value="{$cityName.id}" id="drp-dwn-city-{$cityName.id}">
                                                        <span>{$cityName.name}</span>
                                                    </option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tbody style="display:none;"> 
                                <tr>
                                    <td>
                                        <label class="lbl"> Select a Suburb </label>
                                        <div class="valueField">
                                            <input type="hidden" name="upImg" value="1">
                                            <select name="suburbId" id="area-type-sub" onchange="areaTypeChanged('suburb')">
                                                <option value="">Select Suburb</option>
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
                                                <option value="">Select Locality</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                
                                </tbody> 

                                <tr style="display:none;" id="loc_img_cat">
                                    <td><label class="lbl">Image Category </label>
                                   <div class="valueField">
                                  
                                        <select name="imgCategory" id="imgCat" onchange="updateDisplayLocation()">
                                            <option value="other">Select Category</option>
                                            {foreach from = $localityType key = key item = item}
                                                <option value="{$item}">{$item}</option>
                                            {/foreach}
                                            
                                        </select>
                                    </td>
                                </tr>

                                <tr id="lmk_img_cat">
                                    <td><label class="lbl">Image Category </label>
                                   <div class="valueField">
                                  
                                        <select name="imgCategory" id="imgCat" onchange="updateDisplayLocation()">
                                            <option value="other">Select Category</option>
                                            {foreach from = $landmarkType key = key item = item}
                                                <option value="{$item}">{$item}</option>
                                            {/foreach}
                                            
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="200px">
                                        <label class="lbl"> Search a Landmark </label>
                                    <div class="ui-widget">
                                        <input type="hidden" name="upImg" value="1">
                                        <input type="hidden" name="landmarkId"  id = "landmarkId">
                                        <input type="hidden" name="landmarkName"  id = "landmarkName">
                                        <input id="search"  ></td></div>
                                        <input type="hidden" id="imgName" name="imgDisplayName">
                                    
                                    
                                </tr>
                                
                                
                                 <tr>
                                    <td>
                                        <label class="lbl">Image Display Name</label>
                                        <div >
                                                <span class="lbl" id="img-name"/>
                                                 
                                        </div>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>
                                        <label class="lbl">Image Description</label>
                                        <div class="valueField">
                                                <input type="text" name="imgDescription" value="">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="lbl">Image Display Priority</label>
                                        <div class="valueField">
                                            <select name = "displayPriority" id="imgType">
                                                <option value="999">Select Priority</option>
                                                {$cnt = 0}
                                                {section name=priorityLoop loop=10 step=1}
                                                    {$cnt = $cnt+1}
                                                    <option value="{$cnt}" {if $displayPriority == $cnt}selected{/if}>
                                                        {$cnt}</option>
                                                {/section}
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
                                            <input id="area-img" type="file" name="img[]" autocomplete="off" multiple  /><br />
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
                        <form method = "post" name = "updateDelete" enctype="multipart/form-data">
                        <div class="image-block">
                            
                        </div>
                        <div class="clearfix" style="clear:both;"></div>
                       <!--{if isset($uploadedImage)}
                            <button class="btn-save" style="border:solid 1px #000; padding:5px 10px; background:#333; border-radius:5px; color:#fff; font:bold 12px Arial; text-decoration:none; " id="s-btn" onclick="saveDetails(); return false;">SAVE</button>
                        {else}
                            <button class="btn-save" style="display:none; border:solid 1px #000; padding:5px 10px; background:#333; border-radius:5px; color:#fff; font:bold 12px Arial; text-decoration:none; " id="s-btn" onclick="saveDetails(); return false;">SAVE</button>
                        {/if}-->
                            <span id = "submitBUtton" style = "display:none;">
                                <input type="submit" name = "updateDelete" value = "Update/Delete">
                            </span>
                        </form>
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


<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">
<script type="text/javascript" src="js/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>

<script type="text/javascript" src="js/photo.js"></script>