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
                                        <label> Select a Locality/Suburb/City </label>
                                    </td>
                                    <td style="width: 375px;">
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
                                        <label> Choose the area for which<br /> pictures are to be uploaded </label>
                                    </td>
                                    <td>
                                       <select id="area-list" name="areaId">
                                            {foreach from=$areaList key=id item=areaName}
                                                <option {if $areaId==$areaName.id}selected{/if} value="{$areaName.id}">
                                                    <span>{$areaName.name}</span>
                                                    {if isset($areaName.city)}
                                                    <span> ,  </span>
                                                    <span>{$areaName.city}</span>
                                                    {/if}
                                                </option>
                                            {/foreach}
                                        </select>
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
<script type="text/javascript" src="js/photo.js"></script>