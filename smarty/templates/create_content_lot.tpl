<link rel="stylesheet" type="text/css" href="csss.css"> 
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="tablesorter/css/pager-ajax.css">
<script type="text/javascript" src="js/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">

<div class="modal">Please Wait..............</div>
</TD>
</TR>
<TR>
    <TD class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
        <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><TBODY>
                <TR>
                    <TD width=224 height=25>&nbsp;</TD>
                    <TD width=10>&nbsp;</TD>
                    <TD width=866>&nbsp;</TD>
                </TR>
                <TR>
                    <TD class=paddingltrt10 vAlign=top align=middle bgColor=#ffffff>
                        {include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
                    </TD>
                    <TD vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
                    <TD vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>

                        {if $contentDeliveryManage == true}

                            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                                    <TR>
                                        <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                                            <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                                                    <TR>
                                                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Create Lot<findOTP/TD>
                                                    </TR>
                                                </TBODY></TABLE>
                                        </TD>
                                    </TR>
                                    <TR>
                                        <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>

                                            <div id='create_agent' align="left">

                                                <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1" bgColor="#fcfcfc" style = "border:1px solid #c2c2c2;margin: 20px;">
                                                    <form method = "POST" action = "" onsubmit = "return validation();">
                                                        <tr>
                                                            <td height="25" align="center" colspan= "2">
                                                                <span>
                                                                    <font color = "red">{if $errors} {$errors} {/if}</font>
                                                                </span>                                                                
                                                            </td>
                                                        </tr> 
                                                        <tr>
                                                            <td align="right" style = "padding-left:20px;"><b>Lot Type<font color="red">*</font>:</b></td>
                                                            <td align="left" style = "padding-left:20px;">
                                                                <select name = 'lotType' id = "lotType" >
                                                                    <option value = "">Select</option>                                                                   
                                                                    {foreach from = $arrLotTypes key= key item = val}
                                                                        <option value = "{$key}" {if $lotType == $key} selected  {else}{/if}>{$val}</option>                                                                        
                                                                    {/foreach}                                                                            
                                                                </select>
                                                            </td>
                                                        </tr>                                                        
                                                        <tr><td>&nbsp;</td></tr>
                                                        <tr id="cityContiner">
                                                            <td align="right" style = "padding-left:20px;"><b>City<font color="red">*</font>:</b></td>
                                                            <td align="left" style = "padding-left:20px;">
                                                                <select name = 'city' id = "cities" >
                                                                    <option value = "">Select City</option>                                                                    
                                                                    {foreach from = $CityDataArr key= key item = val}
                                                                        <option value = "{$key}" {if $city == $key} selected  {else}{/if}>{$val}</option>
                                                                    {/foreach}                                                                    
                                                                </select>
                                                            </td>
                                                        </tr>  

                                                        <tr><td>&nbsp;</td></tr>
                                                        <tr>
                                                            <td align="right" style = "padding-left:20px;"><b>Selected IDs<font color="red">*</font>:</b></td>
                                                            <td align="left" style = "padding-left:20px;">
                                                                <table>
                                                                    <tbody><tr>
                                                                            <td>
                                                                                <textarea placeholder="Please Enter Comma(,) Seperated Ids..." readonly="true" id="selArticles" name="selArticles" rows="10" cols="50"> </textarea>
                                                                            </td>
                                                                            <td>
                                                                                <input type="button" name="selectIDs" onclick="selectLotContentIds();" value="Click To Select IDs">
                                                                                <br/><br/>
                                                                                <b>Articles</b>:&nbsp;<span id="totalArticles">0</span>
                                                                                <br/>
                                                                                <b>Words</b>:&nbsp;<span id="totalWords">0</span>
                                                                            </td>

                                                                        </tr>
                                                                    </tbody></table>
                                                            </td>
                                                        </tr>
                                                        <tr><td>&nbsp;</td></tr>
                                                        <tr>
                                                            <td align="right" style = "padding-left:20px;"><b>Assign To:</b></td>
                                                            <td align="left" style = "padding-left:20px;">
                                                                <select name = 'assignTo' id = "assignTo" >
                                                                    <option value = "" >Select</option>
                                                                    {foreach from = $assignToUsers key= key item = val}
                                                                        <option value = "{$key}" {if $assignTo == $key} selected  {else}{/if}>{$val}</option>
                                                                    {/foreach}                                                                    
                                                                </select>
                                                            </td>
                                                        </tr> 
                                                        <tr><td>&nbsp;</td></tr>
                                                        <tr>
                                                            <td height="25" align="center" colspan= "2"  style = "padding-right:40px;">
                                                                <input type = "submit" value = "Save" id="createLot" name = "createLot" class="page-button">
                                                                <input type = "button" value = "Cancel" id="cancel" name = "cancel" class="page-button">
                                                            </td>
                                                        </tr>
                                                        <tr><td>&nbsp;</td></tr>
                                                    </form>
                                                </TABLE>                                                                                           
                                            </div> 
                                        </TD>
                                    </TR>
                                </TBODY></TABLE>
                            {/if}
                    </TD>

                </TR>
            </TBODY></TABLE>
    </TD>
</TR>
<script type="text/javascript">
    var arrIDs = [];
    var wordCount = 0;
    $(document).ready(function () {
        
        //empty the old values
        $('#selArticles').on('keyup change', function(){
            arrIDs = []; 
            $('#totalArticles').html(0);
            $('#totalWords').html(0);          
        });
        
        $('#lotType').on('change', function () {  
            //empty the old values
            $('#selArticles').val('');
            arrIDs = []; 
            $('#totalArticles').html(0);
            $('#totalWords').html(0);
            $('#cities').val('');
        
            if ($(this).val() == 'city') {
                $('#cityContiner').hide();
            } else {
                $('#cityContiner').show();
            }            
            
        });
        
        $('#cities, #lotType').on('change', function(){
            //arrIDs = []; //empty the old values
            if($('#lotType').val() == 'project' && $('#cities').val() == ''){
               $('#selArticles').attr('readonly', false); 
            }else{
               $('#selArticles').attr('readonly', true);               
            }                    
                      
        });
        
        $('#assignTo').on('change', function() {
            if ($(this).val() != '') {
                $('#createLot').val('Save & Assign Lot');
            } else {
                $('#createLot').val('Save');
            }
        });
        
        $('#cancel').on('click', function(){
            window.location = 'content_lot_list.php';
        });

    });
    function selectLotContentIds() {
        var lotType = $('#lotType').val();
        var city = $('#cities').val();
        var pids = $('#selArticles').val().trim();
        
        if(lotType == 'project' && $('#cities').val() == -1 && $('#selArticles').val().trim() == ''){
            alert('Please enter Project IDs to select!');
            return;
        }

        $.ajax({
            type: "POST",
            url: 'ajax/selectContentLotIDs.php',
            data: { lotType:lotType, city: city, pids:pids },
            beforeSend: function () {
                    $("body").addClass("loading");
                },
            success: function (msg) {
                $("body").removeClass("loading");
                if (msg) {
                    $.fancybox({
                        'content': msg,
                        'onCleanup': function () {
                            //	$("#row_"+rowId).remove();
                        }

                    });
                }
            }
        });


    }
    function validation() {
        var lotType = $('#lotType').val().trim();
        var lotCity = $('#cities').val().trim();
        var selArticles = $('#selArticles').val().trim();
        var assignTo = $('#assignTo').val().trim();

        if (lotType == '') {
            alert('Please select Lot Type');
            return false;
        }
        
        if (lotType != 'city' && lotType != 'project' && lotCity == '') {
            alert('Please select Lot City');
            return false;
        }
        
        if (selArticles == '' || arrIDs.length == 0) {
            alert('Please select Ids to assign!');
            return false;
        }

        return true;

    }
</script>