</td>
</tr>
<tr>
    <td class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff></td>
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
                <table cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0>
                    <tbody>
                        <tr>
                            <td class=h1 align=left background=../images/heading_bg.gif bgColor=#ffffff height=40>
                                <table cellSpacing=0 cellPadding=0 width="99%" border=0>
                                    <tbody>
                                        <tr>
                                            <td class="h1">
                                                <IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Edit SEO Meta Templates
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td vAlign=top align=middle class="backgorund-rt" height=450><br>
                                {if $seoMetaAccess == ""}
                                    <form method="post" enctype="multipart/form-data" id="builderForm">
                                        <table cellSpacing=1 cellPadding=4 width="90%" align=center border=0>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table cellSpacing=1 cellPadding=4 align=center border=0>
                                                            <tr>
                                                                <td></td>
                                                                <td><font color = "red" style="font-size:17px;">{$ErrorMsg}</font></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" vlaign="top"><label><b>Template Name :</b></label></td>
                                                                <td><input type="text" name="template_name" value="{$result["template_name"]}" {($result["template_name"])?'readonly="readonly"':""} style="width:330px" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" align="right"><label><b>Titile :</b></label></td>
                                                                <td><textarea name="title" rows="5" cols="45">{$result["title"]}</textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" align="right"><label><b>Description :</b></label></td>
                                                                <td><textarea name="description" rows="10" cols="45">{$result["description"]}</textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" align="right"><label><b>Keywords :</b></label></td>
                                                                <td><textarea name="keywords" rows="10" cols="45">{$result["keywords"]}</textarea></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td>
                                                        <table cellSpacing=1 cellPadding=4 align=center border=0>
                                                            <tr>
                                                                <td valign="top" align="right">
                                                                    <label><b>H1 :</b></label>
                                                                </td>
                                                                <td><textarea name="h1" rows="3" cols="45">{$result["h1"]}</textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" align="right"><label><b>H2 :</b></label></td>
                                                                <td><textarea name="h2" rows="3" cols="45">{$result["h2"]}</textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" align="right"><label><b>H3 :</b></label></td>
                                                                <td><textarea name="h3" rows="3" cols="45">{$result["h3"]}</textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" align="right"><label><b>H4 :</b></label></td>
                                                                <td><textarea name="h4" rows="4" cols="45">{$result["h4"]}</textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" align="right"><label><b>Other :</b></label></td>
                                                                <td><textarea name="others" rows="5" cols="45">{$result["others"]}</textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" align="right"></td>
                                                                <td>
                                                                    <input type="submit" name="btnSave" value="{($result)?"Update":"Save"}"/>
                                                                    <a href="meta_templates.php"><input type="button" value="Exit"/></a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                {else}
                                    <div  style="color: red">{$seoMetaAccess}</div>
                                {/if}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
</table>
</tr>