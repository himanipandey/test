<link href="css/css.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<table align="center" style = "border:1px solid;" width = "600px" height ="80px">
    <tr bgcolor ="#c2c2c2" height="30px">
        <td colspan="7" align = "center"><b>Assignment Of Brokers for {ucwords($projectName)}</b></td>
    </tr>
    <tr height="30px">
        
        <td colspan="7" align = "center">
           <form method="post">
               {if count($errorMsg)>0}{$errorMsg['oneSelection']}<br>{/if}
                Broker Name: <input type="text" name = "broker" value="{$broker}" style="width:150px;">
                Mobile: <input type="text" name = "mobile" value="{$mobile}"  style="width:150px;">
                <input type="submit" name="search" value="Search">
            </form>
        </td>
    </tr>
</table>
 <table align="center" width = "600px" height ="20px">
    <tr height="30px">
        <td colspan="7" align = "center">
            &nbsp;
        </td>
    </tr>
</table>
<table align="center" style = "border:1px solid;" width = "600px" height ="400px">
    {$totalRow = count($arrAllActiveBrokerList)}
    {if $totalRow != 0}
        <tr class ="headingrowcolor" height="30px">
            <th class ="whiteTxt" align = "left"><b>S.NO.</b></th>
             <th style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Broker Name</b></th>
             <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Contact Person Name</b></th>
             <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Contact E-mail</b></th>
             <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Contact Mobile Number</b></th>
             <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Broker Address</b></th>
             <th class ="whiteTxt" align = "left"><b>Assign</b></th>
        </tr>
        <form name ="frm" method = "post">
        {$cnt = 0}

        {foreach from= $arrAllActiveBrokerList key=k item = val}
            {$cnt = $cnt+1}
            {if $cnt%2 == 0}
                {$bgcolor = '#F7F7F7'}
            {else}
                {$bgcolor = '#FCFCFC'}
            {/if}
            <tr bgcolor = "{$bgcolor}" height="30px">
               <td align = "center">{$cnt}</td>
               <td style ="padding-left: 10px;" align = "left">{$val['BROKER_NAME']}</td>
               <td style ="padding-left: 10px;" align = "left">{$val['CONTACT_NAME']}</td>
               <td style ="padding-left: 10px;" align = "left">{$val['BROKER_EMAIL']}</td>
               <td style ="padding-left: 10px;" align = "left">{$val['BROKER_MOBILE']}</td>
               <td style ="padding-left: 10px;" align = "left">{$val['BROKER_ADDRESS']}</td>
               <td class ="tdcls_{$cnt}" align = "center">
                   <input id = "chk_{$cnt}" type = "checkbox" value ="{$val['BROKER_ID']}" name="broker[]"
                {if array_key_exists($val['BROKER_ID'],$allBrokerByProject)}
                    checked
                {/if}>
               </td>
           </tr>
        {/foreach}
        <input type = "hidden" name = "projectId" value ="{$projectId}">
        <tr class ="headingrowcolor" height="30px">
            <th  colspan ="7" class ="whiteTxt" align = "right">
            <input type = "submit" name ="submit" value ="Assign" onclick ="return removeUncheckedRow({$totalRow});">
            &nbsp;&nbsp;<input type = "submit" name ="exit" value ="Exit">
            </th>   
        </tr>
        </form>
     {else}
         <tr height="30px"><td colspan="7" align = "center"><font color = "red">{if $firstTimeSearch == 1}No records Found!{/if}</font></td></tr>
     {/if}   
    
</table>
    
<script>
    function removeUncheckedRow(totalRow){
        var i = 1;
        for(i=1;i<totalRow;i++)
        {
            var chkId = "chk_"+i;
            var className	= "tdcls_"+i;
            if(document.getElementById(chkId).checked == false)
            {
                $("."+className).remove();
            }
        }
       return confirm("Are you sure! you want to assign brokers which are checked.");
    }
</script>