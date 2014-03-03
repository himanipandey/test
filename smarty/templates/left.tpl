    <table cellspacing="0" BGCOLOR='#FFFFFF' cellPadding=0 width="202" border="0">
	<tr>
		<td height="6"></td>
	</tr>

	<tr>
		<td class="thinline" align="left" colSpan="2"></td>
	</tr>

	<tr>
		<td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
		  <td align="left" height="22"><A class="leftnav" href="ProjectList.php?page=1&sort=all"><font color = "#f15a22">Projects Management</font></A></td>
	</tr>

	<tr>
		<td class="thinline" align="left" colSpan="2"></td>
	</tr>
        {if $builderAuth == true}
            <tr>
                    <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                      <td align="left" height="22"><A class="leftnav" href="BuilderList.php"><font color = "#f15a22">Builder Management</font></A></td>
            </tr>

            <tr>
                    <td class="thinline" align="left" colSpan="2"></td>
            </tr>
        {/if}

        {if $cityAuth == true}
            <tr>
                    <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                      <td align="left" height="22"><A class="leftnav" href="CityList.php"><font color = "#f15a22">City Management</font></A></td>
            </tr>

            <tr>
                    <td class="thinline" align="left" colSpan="2"></td>
            </tr>
        {/if}
        
        {if $localityAuth == true}
            <tr>
                    <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                      <td align="left" height="22"><A class="leftnav" href="localityList.php"><font color = "#f15a22">Locality Management</font></A></td>
            </tr>

            <tr>
                    <td class="thinline" align="left" colSpan="2"></td>
            </tr>
        {/if}

        {if $suburbAuth == true}
            <tr>
                    <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                      <td align="left" height="22"><A class="leftnav" href="suburbList.php"><font color = "#f15a22">Suburb Management</font></A></td>
            </tr>

            <tr>
                    <td class="thinline" align="left" colSpan="2"></td>
            </tr>
        {/if}
        {if $cityAuth == true}
            <tr>
                    <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                      <td align="left" height="22"><A class="leftnav pt_click" href="javascript:void(0);" title = "Add Quick City"><font color = "#f15a22">Add Quick City</font></A></td>
            </tr>

            <tr>
                    <td class="thinline" align="left" colSpan="2"></td>
            </tr>
        {/if}

        {if $labelAuth == true}
            <tr>
                    <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                      <td align="left" height="22"><A class="leftnav label_click" href="javascript:void(0);" title = "Add New Label"><font color = "#f15a22">Label Management</font></A></td>
            </tr>
            <tr>
                    <td class="thinline" align="left" colSpan="2"></td>
            </tr>
        {/if}
            <tr>
                    <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                      <td align="left" height="22"><A class="leftnav" href="townships.php" title = "TownShips"><font color = "#f15a22">TownShips Management</font></A></td>
            </tr>
            <tr>
                    <td class="thinline" align="left" colSpan="2"></td>
            </tr>
       
        {if $bankAuth == true}
            <tr>
                    <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                      <td align="left" height="22"><A class="leftnav" href="bank_list.php"><font color = "#f15a22">Bank Management</font></A></td>
            </tr>

            <tr>
                    <td class="thinline" align="left" colSpan="2"></td>
            </tr>
        {/if}	
        
        {if $imageAuth == true}
            <tr>
                <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><A class="leftnav" href="photo.php"><font color = "#f15a22">Upload Pictures</font></A></td>
            </tr>

            <tr>
                <td class="thinline" align="left" colSpan="2"></td>
            </tr>
        {/if}
        {if $urlAuth == true}
	<tr>
            <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><A class="leftnav" href="redirectUrlManage.php"><font color = "#f15a22">Redirect URL Management</font></A></td>
	</tr>
        {/if}
        
	{if $migrateAuth == true}
	<tr>
            <td class="thinline" align="left" colSpan="2"></td>
	</tr>
	<tr>
            <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><A class="leftnav" href="forceMigrate.php"><font color = "#f15a22">Force Migrate</font></A></td>
	</tr>
	{/if}

	{if $bulkProjUpdateAuth == true}
	<tr>
            <td class="thinline" align="left" colSpan="2"></td>
	</tr>
	<tr>
            <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><A class="leftnav" href="transferProject.php"><font color = "#f15a22">Bulk Project Update</font></A></td>
	</tr>
	{/if}


	<!-- Reports & MIS -->
	{if $dailyPerformanceReportAuth == true}
                <tr>
                    <td class="thinline" align="left" colspan="2"></td>
                </tr>
                <tr>
                    <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                    <td align="left" height="22"><a class="leftnav" href="#"><font color = "#f15a22">REPORTS & MIS</font></a></td>
                </tr>
                <tr><td colspan='2' style="padding-left:10px;">
                <table width='100%'>
                <tr>
                    <td class="thinline" align="left" colspan="2"></td>
                </tr>
                <tr>
                    <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                    <td align="left" height="22"><a class="leftnav" href="daily-performance-report.php"><font color = "#f15a22">Daily Performance Report</font></a></td>
                </tr>
                </table>
                </td></tr>
	{/if}
	<!-- Reports & MIS -->
        
        
	<tr>
            <td class="thinline" align="left" colspan="2"></td>
	</tr>
       <!--Callcenter start-->
       {if $myProjectsCallCenterAuth == true || $callCenterAuth == true}
	<tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="#"><font color = "#f15a22">Data Collection Flow CallCenter</font></a></td>
	</tr>
        {/if}
	<tr><td colspan='2' style="padding-left:10px;">
	<table width='100%'>
	<tr>
            <td class="thinline" align="left" colspan="2"></td>
	</tr>
        {if $callCenterAuth == true && $myProjectsCallCenterAuth == false}
	<tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="project-status.php?flag=callcenter"><font color = "#f15a22">Project Status</font></a></td>
	</tr>
        <tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="project-status-summary.php?flag=callcenter"><font color = "#f15a22">Project Status Summary</font></a></td>
	</tr>
        
	<tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="executive-workload.php?flag=callcenter"><font color = "#f15a22">Executive Workload</font></a></td>
	</tr>
        <tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="executive-performance.php?flag=callcenter"><font color = "#f15a22">Executive Performance</font></a></td>
	</tr>
        {/if}
        {if $myProjectsCallCenterAuth == true && $callCenterAuth == false}
        <tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="my-projects.php"><font color = "#f15a22">My Projects</font></a></td>
	</tr>
        {/if}
	</table>
	</td></tr>
         <!--Callcenter start-->
        <!--Survey start-->
        {if $myProjectsSurveyAuth == true || $surveyAuth == true}
	<tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="#"><font color = "#f15a22">Data Collection Flow Survey</font></a></td>
	</tr>
        {/if}
	<tr><td colspan='2' style="padding-left:10px;">
	<table width='100%'>
	<tr>
            <td class="thinline" align="left" colspan="2"></td>
	</tr>
        {if $surveyAuth == true && $myProjectsSurveyAuth == false}
            <tr>
                <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><a class="leftnav" href="project-status.php?flag=survey"><font color = "#f15a22">Project Status</font></a></td>
            </tr>
            <tr>
                <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><a class="leftnav" href="project-status-summary.php?flag=survey"><font color = "#f15a22">Project Status Summary</font></a></td>
            </tr>

            <tr>
                <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><a class="leftnav" href="executive-workload.php?flag=survey"><font color = "#f15a22">Executive Workload</font></a></td>
            </tr>
            <tr>
                <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><a class="leftnav" href="executive-performance.php?flag=survey"><font color = "#f15a22">Executive Performance</font></a></td>
            </tr>
        {/if}
        {if $myProjectsSurveyAuth == true && $surveyAuth == true}
        <tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="my-projects.php"><font color = "#f15a22">My Projects</font></a></td>
	</tr>
        {/if}
	</table>
	</td></tr>
	<!--survey end-->
        
        {if $brokerAuth == true}
            <tr>
                <td class="thinline" align="left" colspan="2"></td>
            </tr>
            <tr>
                <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><a class="leftnav" href="brokerList.php"><font color = "#f15a22">Broker Management</font></a></td>
            </tr>
            
             <tr>
                <td class="thinline" align="left" colspan="2"></td>
            </tr>
            <tr>
                <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><a class="leftnav" href="callToBroker.php"><font color = "#f15a22">Direct Call To Broker</font></a></td>
            </tr>
        {/if}
        <tr>
            <td class="thinline" align="left" colspan="2"></td>
	</tr>
    
    <tr>
                <td class="thinline" align="left" colspan="2"></td>
    </tr>
    {if $priorityMgmtPermissionAccess == 1}
    <tr>
        <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
        <td align="left" height="22"><a class="leftnav" href="#"><font color = "#f15a22">Priority Management</font></a></td>
    </tr>
    <tr><td colspan='2' style="padding-left:10px;">
        <table width='100%'>
            <tr>
                <td class="thinline" align="left" colspan="2"></td>
            </tr>
            <tr>
                <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><a class="leftnav" href="loc_sub_priority.php"><font color = "#f15a22">Suburb/Locality Priority</font></a></td>
            </tr>
            <tr>
                <td class="thinline" align="left" colspan="2"></td>
            </tr>
            <tr>
                <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><a class="leftnav" href="project_priority.php"><font color = "#f15a22">Project Priority</font></a></td>
            </tr>
            <tr>
                <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><a class="leftnav" href="locality_near_places_priority.php"><font color = "#f15a22">Locality Near Places Priority</font></a></td>
            </tr>
        </table>
    </td></tr>
    {/if}
    {if $isMetricsAccess == true}
     <tr>
        <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
        <td align="left" height="22"><a class="leftnav" href="metricsDashboard.php"><font color = "#f15a22">IS Metrics DashBoard</font></a></td>
    </tr>
    <tr>
        <td class="thinline" align="left" colspan="2"></td>
    </tr>
    {/if}
    
    {if $reportErrorPermissionAccess == true}
    <tr>
        <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
        <td align="left" height="22"><a class="leftnav" href="reportError.php"><font color = "#f15a22">Error Report on Project</font></a></td>
    </tr>
    <tr><td class="thinline" align="left" colspan="2"></td></tr>
    {/if}
    
    <tr>
        <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
        <td align="left" height="22"><a class="leftnav" href="projects-waiting-migration.php"><font color = "#f15a22">Projects Pending Migration</font></a></td>

    </tr>
    <tr><td class="thinline" align="left" colspan="2"></td></tr>
    
    <tr>
        <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
        <td align="left" height="22"><a class="leftnav" href="userList.php"><font color = "#f15a22">User Management</font></a></td>

    </tr>
    <tr><td class="thinline" align="left" colspan="2"></td></tr>
    <tr>
        <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
        <td align="left" height="22"><a class="leftnav" href="logout.php"><font color = "#f15a22">Logout</font></a></td>
    </tr>
    <tr><td class="thinline" align="left" colspan="2"></td></tr>

</table>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery(".pt_click").live('click',function(){

		var title =  jQuery(this).attr('title');
		if(title=='Add Quick City'){
			jQuery(this).attr('href','javascript:void(0)');
			window.open('AddQuickCity.php','CityManagement','height=300,width=600,scrollbars=yes,toolbar=no,left=150,resizable=1,top=150');
		}
	});
});

jQuery(document).ready(function(){
	jQuery(".label_click").live('click',function(){
		var title =  jQuery(this).attr('title');
		jQuery(this).attr('href','javascript:void(0)');
		window.open('AddQuickLabel.php','LabelManagement','height=300,width=800,scrollbars=yes,toolbar=no,left=150,resizable=1,top=150');
	});
});
</script>
