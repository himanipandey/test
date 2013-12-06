<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>{$SITETITLE}</title>
	<link href="{$FORUM_SERVER_PATH}css/css.css" rel="stylesheet" type="text/css">
	{if isset($photoCSS) && $photoCSS==1}
	<link href="{$FORUM_SERVER_PATH}css/photo.css" rel="stylesheet" type="text/css">
	{/if}
	<script language="javascript" src="{$FORUM_SERVER_PATH}js/jquery/jquery-1.4.4.min.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="{$FORUM_SERVER_PATH}jscal/skins/aqua/theme.css" title="Aqua" />
	<!-- <link href="{$FORUM_SERVER_PATH}css/calendar.css" rel="stylesheet" type="text/css">
	<link href="{$FORUM_SERVER_PATH}css/picker.css" rel="stylesheet" type="text/css"> -->
	<!-- <script language="javascript" src="{$FORUM_SERVER_PATH}js/calendar.js"></script>
	<script language="javascript" src="{$FORUM_SERVER_PATH}js/picker.js"></script>

 -->
	
</head>
<body >
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		<tr>
			<td align="left" valign="top">
				<table border="0" cellpadding="0" cellspacing="0" width="100%" style='border-bottom:1px solid #F17715;border-top:1px solid #c2c2c2;'>
					<tr>
						<td width = '16%'  bgcolor = '#ffffff' align="center" valign="middle">
							<a target="_new"  href = 'http://www.proptiger.com'><img border = '0' src='{$FORUM_POPUP_IMAGE_PATH}adminlogo.gif' width='150'></a>
						</td>
						<td width = "41%" bgcolor = '#666666'  height="40" align="right" valign="middle"><font size="4" color="#FFFFFF">Projects Administrator Panel</font></td>
						<td width = "41%" bgcolor = '#666666'  height="40" align="right" valign="middle" style ="padding-right:30px;">
							<table align = "right">
								<tr>
									<td style = "font-size:11px;color:#FFFFFF">
										<font  color="#FFFFFF">{if $AdminUserName != ''} Welcome <b>{$AdminUserName}</b> !{/if}</font> &nbsp;&nbsp;<strong>|</strong>&nbsp;&nbsp;<A href="changePass.php" style="color:#FFFFFF;font-size:11px;text-decoration:none;font-weight:bold">Change Password</A> | &nbsp;<b>{$dept}</b>
									</td>
								</tr>
								<tr>
									<td  style = "font-size:11px;"><font color="#FFFFFF">{if $AdminUserName != '' && $LAST_LOGIN_IP != ''} Last Login: {$LAST_LOGIN_DATE}, IP: {$LAST_LOGIN_IP} {/if}</font></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table> 

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		  <tr>
			  <td class="white-bg" align="center" bgcolor="#ffffff" valign="top">
	