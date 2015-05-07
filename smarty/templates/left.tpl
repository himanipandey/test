    <table cellspacing="0" BGCOLOR='#FFFFFF' cellPadding=0 width="202" border="0">
	<tr>
		<td height="6"></td>
	</tr>

	<tr>
		<td class="thinline" align="left" colSpan="2"></td>
	</tr>
        {if $projectManageAuth == true}
	<tr>
		<td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
		  <td align="left" height="22"><A class="leftnav" href="ProjectList.php?page=1&sort=all"><font color = "#f15a22">Projects Management</font></A></td>
	</tr>
        {/if}
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
    

    {if $listingAuth == true}
        <tr>
            <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><A class="leftnav" href="listing_list.php"><font color = "#f15a22">Listing Management</font></A></td>
        </tr>

        <tr>
            <td class="thinline" align="left" colSpan="2"></td>
        </tr>

    {/if}
    {if $listingPhotoAuth == true}

        <tr>
            <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><A class="leftnav" href="listing_img_add.php"><font color = "#f15a22">Upload Listing Photo</font></A></td>
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
        
        {if $mapVarifyAuth == true}
            <tr>
                    <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                      <td align="left" height="22"><A class="leftnav" href="mapLocationVarification.php"><font color = "#f15a22">Map Location Verification Tool</font></A></td>
            </tr>

            <tr>
                    <td class="thinline" align="left" colSpan="2"></td>
            </tr>
        {/if}

    {if $companyAuth == true}
        <tr>
            <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><A class="leftnav" href="companyList.php"><font color = "#f15a22">Company Management</font></A></td>
        </tr>
        <tr>
            <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><A class="leftnav" href="brokerAgent.php"><font color = "#f15a22">Broker Agent Management</font></A></td>
        </tr>
        <tr>
            <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><A class="leftnav" href="findOTP.php"><font color = "#f15a22">One Time Password</font></A></td>
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
        {if $townshipManageAuth == true}
            <tr>
                    <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                      <td align="left" height="22"><A class="leftnav" href="townships.php" title = "TownShips"><font color = "#f15a22">TownShips Management</font></A></td>
            </tr>
        
            <tr>
                    <td class="thinline" align="left" colSpan="2"></td>
            </tr>
         {/if}
        {if $authorityAuth == true}
            <tr>
                    <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                      <td align="left" height="22"><A class="leftnav" href="housingAuthorities.php" title = "TownShips"><font color = "#f15a22">Housing Authorities Management</font></A></td>
            </tr>
            <tr>
                    <td class="thinline" align="left" colSpan="2"></td>
            </tr>
        {/if}
       
        {if $bankAuth == true}
            <tr>
                    <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                      <td align="left" height="22"><A class="leftnav" href="bank_list.php"><font color = "#f15a22">Bank Management</font></A></td>
            </tr>

            <tr>
                    <td class="thinline" align="left" colSpan="2"></td>
            </tr>

            

        {/if}	
        

        {if $peDealsAuth == true}
            <tr>
                    <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                      <td align="left" height="22"><A class="leftnav" href="privateEquity.php"><font color = "#f15a22">Private Equity Deals</font></A></td>
            </tr>

            <tr>
                    <td class="thinline" align="left" colSpan="2"></td>
            </tr>
        {/if}

        <tr>
            <td class="thinline" align="left" colSpan="2"></td>
        </tr>

    {if $companyOrderAdminAuth == true || $companyOrderViewAuth == true}
        <tr>
            <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><A class="leftnav" href="companyOrdersList.php"><font color = "#f15a22">Company Orders Management</font></A></td>
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

    {if $suburbAuth == true}
        <tr>
            <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><A class="leftnav" href="suburbList.php"><font color = "#f15a22">Suburb Management</font></A></td>
        </tr>

        <tr>
            <td class="thinline" align="left" colSpan="2"></td>
        </tr>
    {/if}

    <tr>
        <td class="thinline" align="left" colSpan="2"></td>
    </tr>
    {if $authorityAuth == true}
        <tr>
            <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><A class="leftnav" href="housingAuthorities.php" title = "TownShips"><font color = "#f15a22">Housing Authorities Management</font></A></td>
        </tr>
        <tr>
            <td class="thinline" align="left" colSpan="2"></td>
        </tr>
    {/if}

    {if $bankAuth == true}
        <tr>
            <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><A class="leftnav" href="bank_list.php"><font color = "#f15a22">Bank Management</font></A></td>
        </tr>

        <tr>
            <td class="thinline" align="left" colSpan="2"></td>
        </tr>



    {/if}	


    {if $couponAuth == true}
        <tr>
            <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><A class="leftnav" href="couponGenerate.php"><font color = "#f15a22">Coupon Catalogue Management</font></A></td>
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
                        <td align="left" height="22"><a class="leftnav" href="locality_near_places_priority.php"><font color = "#f15a22">Landmarks Priority</font></a></td>
                    </tr>

                    <tr>
                        <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                        <td align="left" height="22"><a class="leftnav" href="alias_mgmt.php"><font color = "#f15a22">Alias Management</font></a></td>
                    </tr>
                </table>
            </td></tr>
        {/if}

    <!--process assignment for construction image update-->
    {if $processAssignmentForConstImg == 1}
        <tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="#"><font color = "#f15a22">Construction Image Assignment</font></a></td>
        </tr>
        <tr><td colspan='2' style="padding-left:10px;">
                <table width='100%'>
                    <tr>
                        <td class="thinline" align="left" colspan="2"></td>
                    </tr>
                    {if $processAssignmentLead == 1 && $processAssignmentForConstImg == 1}

                        <tr>
                            <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                            <td align="left" height="22"><A class="leftnav const_label_click" href="javascript:void(0);" title = "Add New Construction Label"><font color = "#f15a22">Construction Label Manage</font></A></td>
                        </tr>
                        <tr>
                            <td class="thinline" align="left" colSpan="2"></td>
                        </tr>

                        <tr>
                            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                            <td align="left" height="22"><a class="leftnav" href="transferConst.php"><font color = "#f15a22">Bulk update construction</font></a></td>
                        </tr>
                        <tr>
                            <td class="thinline" align="left" colSpan="2"></td>
                        </tr>

                        <tr>
                            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                            <td align="left" height="22"><a class="leftnav" href="project_const_img.php"><font color = "#f15a22">Projects for assignment</font></a></td>
                        </tr>
                        <tr>
                            <td class="thinline" align="left" colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                            <td align="left" height="22"><a class="leftnav" href="project_const_history.php"><font color = "#f15a22">Projects assignment history</font></a></td>
                        </tr>
                        <tr>
                            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                            <td align="left" height="22"><a class="leftnav" href="daily_productivity_report.php"><font color = "#f15a22">Daily Productivity Report</font></a></td>
                        </tr>
                        <tr>
                            <td class="thinline" align="left" colspan="2"></td>
                        </tr>
                    {else if $processAssignmentExec == 1 && $processAssignmentForConstImg == 1}
                        <tr>
                            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                            <td align="left" height="22"><a class="leftnav" href="my_projects_const_img.php"><font color = "#f15a22">My Projects</font></a></td>
                        </tr>
                        <tr>
                            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                            <td align="left" height="22"><a class="leftnav" href="project_const_history.php"><font color = "#f15a22">Projects assignment history</font></a></td>
                        </tr>
                    {/if}            
                </table>
            </td></tr>
        {/if}
    <!--end for process assignment cunstruction image-->

    {if $executivePerformanceAuth == 1}
        <tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="#"><font color = "#f15a22">Executive Performance</font></a></td>
        </tr>
        <tr><td colspan='2' style="padding-left:10px;">
                <table width='100%'>
                    <tr>
                        <td class="thinline" align="left" colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                        <td align="left" height="22"><a class="leftnav" href="agingOfProjectinField.php"><font color = "#f15a22">Report With Aging</font></a></td>
                    </tr>
                    <tr>
                        <td class="thinline" align="left" colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                        <td align="left" height="22"><a class="leftnav" href="citywiseDoneNotDone.php"><font color = "#f15a22">CityWise Report</font></a></td>
                    </tr>
                    <tr>
                        <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                        <td align="left" height="22"><a class="leftnav" href="report3.php"><font color = "#f15a22">Report-3</font></a></td>
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
    
    <tr>
                <td class="thinline" align="left" colspan="2"></td>
    </tr>
      
    <!--process assignment for construction image update-->
    {if $processAssignmentForConstImg == 1}
    <tr>
        <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
        <td align="left" height="22"><a class="leftnav" href="#"><font color = "#f15a22">Construction Image Assignment</font></a></td>
    </tr>
    <tr><td colspan='2' style="padding-left:10px;">
        <table width='100%'>
            <tr>
                <td class="thinline" align="left" colspan="2"></td>
            </tr>
            {if $processAssignmentLead == 1 && $processAssignmentForConstImg == 1}
            
            <tr>
                <td class="blue_txt" noWrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><A class="leftnav const_label_click" href="javascript:void(0);" title = "Add New Construction Label"><font color = "#f15a22">Construction Label Manage</font></A></td>
            </tr>
            <tr>
                <td class="thinline" align="left" colSpan="2"></td>
            </tr>
            
            <tr>
                <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><a class="leftnav" href="transferConst.php"><font color = "#f15a22">Bulk update construction</font></a></td>
            </tr>
            <tr>
                <td class="thinline" align="left" colSpan="2"></td>
            </tr>
            
            <tr>
                <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><a class="leftnav" href="project_const_img.php"><font color = "#f15a22">Projects for assignment</font></a></td>
            </tr>
            <tr>
                <td class="thinline" align="left" colspan="2"></td>
            </tr>
            <tr>
                <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><a class="leftnav" href="project_const_history.php"><font color = "#f15a22">Projects assignment history</font></a></td>
            </tr>
            <tr>
                <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                <td align="left" height="22"><a class="leftnav" href="daily_productivity_report.php"><font color = "#f15a22">Daily Productivity Report</font></a></td>
            </tr>
            <tr>
                <td class="thinline" align="left" colspan="2"></td>
            </tr>
            {else if $processAssignmentExec == 1 && $processAssignmentForConstImg == 1}
                <tr>
                    <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                    <td align="left" height="22"><a class="leftnav" href="project_const_history.php"><font color = "#f15a22">Projects assignment history</font></a></td>
                </tr>
                <tr>
                    <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                    <td align="left" height="22"><a class="leftnav" href="my_projects_const_img.php"><font color = "#f15a22">My Projects</font></a></td>
                </tr>
            {/if}            
        </table>
    </td></tr>
    {/if}
    <!--end for process assignment cunstruction image-->

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

    {if $userManagement == true}
        <tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="userList.php"><font color = "#f15a22">User Management</font></a></td>

        </tr>
        <tr><td class="thinline" align="left" colspan="2"></td></tr>
        {/if}
        {if $campaigndidsAuth == true}
        <tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="campagindids.php"><font color = "#f15a22">Campaign DIDs Management</font></a></td>

        </tr>
        <tr><td class="thinline" align="left" colspan="2"></td></tr>
        {/if}

    {if $micrositeFlgExec == true}
        <tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="microsite-add-edit.php"><font color = "#f15a22">Microsite Management</font></a></td>

        </tr>
        <tr><td class="thinline" align="left" colspan="2"></td></tr>
        {/if}

    {if $crawlingAuth == true}
        <tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="crawling_upload.php"><font color = "#f15a22">Crawling/Youtube Upload</font></a></td>

        </tr>
        <tr><td class="thinline" align="left" colspan="2"></td></tr>
        {/if}

    {if $contentDeliveryManage == true || $contentDeliveryAccess == true}
        <tr>
            <td class="thinline" align="left" colspan="2"></td>
        </tr>
        <tr>
            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
            <td align="left" height="22"><a class="leftnav" href="#"><font color = "#f15a22">Content Delivery System</font></a></td>
        </tr>
        <tr><td colspan='2' style="padding-left:10px;">
                <table width='100%'>
                    <tr>
                        <td class="thinline" align="left" colspan="2"></td>
                    </tr>
                    {if $contentDeliveryManage == true}
                        <tr>
                            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                            <td align="left" height="22"><a class="leftnav" href="create_content_lot.php"><font color = "#f15a22">Create Lot</font></a></td>
                        </tr>
                        <tr>
                            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                            <td align="left" height="22"><a class="leftnav" href="content_lot_list.php"><font color = "#f15a22">Lot List</font></a></td>
                        </tr>
                  
                    {elseif $contentDeliveryAccess == true}                        
                        <tr>
                            <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
                            <td align="left" height="22"><a class="leftnav" href="content_lot_list_assigned.php"><font color = "#f15a22">My Assigned Lots</font></a></td>
                        </tr>
                    {/if}

                </table>
            </td></tr>
        {/if}

    </tr>
    <tr><td class="thinline" align="left" colspan="2"></td></tr>
    {/if}
    
    {if $seoMetaAuth == true}
    <tr>
        <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
        <td align="left" height="22"><a class="leftnav" href="meta_templates.php"><font color = "#f15a22">SEO Meta Template</font></a></td>
    </tr>
    {/if}

    <tr>
        <td class="blue_txt" nowrap align="left" width="2%" height="22"><img height="9" src="{$OFFLINE_PROJECT_POPUP_IMAGE_PATH}plus.gif" width="9">&nbsp;</td>
        <td align="left" height="22"><a class="leftnav" href="logout.php"><font color = "#f15a22">Logout</font></a></td>
    </tr>
    <tr><td class="thinline" align="left" colspan="2"></td></tr>

</table>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".label_click").live('click', function () {
            var title = jQuery(this).attr('title');
            jQuery(this).attr('href', 'javascript:void(0)');
            window.open('AddQuickLabel.php', 'LabelManagement', 'height=300,width=800,scrollbars=yes,toolbar=no,left=150,resizable=1,top=150');
        });

        jQuery(".const_label_click").live('click', function () {
            var title = jQuery(this).attr('title');
            jQuery(this).attr('href', 'javascript:void(0)');
            window.open('addConstructionLabel.php', 'ConstructionLabelManagement', 'height=300,width=800,scrollbars=yes,toolbar=no,left=150,resizable=1,top=150');
        });
    });
</script>
