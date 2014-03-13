<link href="css/css.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<table align="center" style = "border:1px solid;" width = "650px" height ="80px">
    <tr bgcolor ="#c2c2c2" height="30px">
        <td colspan="7" align = "center"><b>Project Old Comments of {ucwords($ProjectDetail[0]['PROJECT_NAME'])}</b></td>
    </tr>
    {if count($errorMsg)>0}
        {foreach from = $errorMsg key = k item =  error}
        <tr height="30px">
            <td colspan="7" align = "center">{$error}</td>
        </tr>
        {/foreach}
    {/if}
    <tr height="30px">
        
        <td colspan="7" align = "center">
           <form method="post">
               <b> Comment Type:</b> 
                <select name = "commentType">
                    <option value="">Select Type</option>
                    <option value="Project" {if $commentType == 'Project'} selected {/if}>Project</option>
                    <option value="Calling" {if $commentType == 'Calling'} selected {/if}>Calling</option>
                    <option value="FieldSurvey" {if $commentType == 'FieldSurvey'} selected {/if}>Field Survey</option>
                    <option value="Audit" {if $commentType == 'Audit'} selected {/if}>Audit</option>
                    <option value="Secondary" {if $commentType == 'Secondary'} selected {/if}>Secondary</option>
                    <option value="SecondaryAudit" {if $commentType == 'SecondaryAudit'} selected {/if}>Secondary Audit</option>
                    <option value="Audit2" {if $commentType == 'Audit2'} selected {/if}>Audit-2</option>
                    <option value="all" {if $commentType == 'all'} selected {/if}>All</option>
                </select>
               
                <b> Comment Cycle:</b> 
                <select name = "commentCycle">
                    <option value="">Select Cycle</option>
                    {foreach from = $allCycle item = item key = key}
                        <option value="{$key}" {if $commentCycle == $key} selected {/if}>{$item}</option>
                    {/foreach}
                    <option value="all" {if $commentCycle == 'all'} selected {/if}>All</option>
                </select>
                
               <input type="hidden" name = "projectId" value="{$ProjectDetail[0]['PROJECT_ID']}">
                <input type="submit" name="submit" value="Search">
            </form>
        </td>
    </tr>
</table>
 <table align="center" width = "650px" height ="10px">
    <tr height="10px">
        <td colspan="7" align = "center">
            &nbsp;
        </td>
    </tr>
</table>
<table align="center" style = "border:1px solid;" width = "650px" height ="400px">
    {$totalRow = count($commentList)}
    {if $totalRow != 0}
       
        {$cnt = 0}

        {foreach from= $commentList key=k item = val}
            {$cnt = $cnt+1}
            {if $cnt%2 == 0}
                {$bgcolor = '#F7F7F7'}
            {else}
                {$bgcolor = '#FCFCFC'}
            {/if}
            <tr bgcolor = "{$bgcolor}">
                <td valign ="top" style ="padding-left: 10px; font-size: 14px;" align = "left"><b>{$commentTypeMap[$val->comment_type]}:</b></td>
                <td valign ="top" style ="padding-left: 10px; font-size: 14px;" align = "left">{$val->comment_text} <b>By {$val->fname} on {($val->date_time)|date_format:'%b-%y'} </b></td>
           </tr>
        {/foreach}
        <input type = "hidden" name = "projectId" value ="{$projectId}">
    {/if}
</table>
