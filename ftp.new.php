<?php

/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*--*-*-*-*-
Purpose :
Change History :
Mod# Date Who Description
------- -------- ------ ---------------------------------
000000001 12-Feb-2008 Mohammad Created
*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*--*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
/*
-=--=--=--=--=--=--=--=-This code for the refrence : MOHAMMAD SALEEM-=--=--=--=--=--=--=--=-
* @param string $ftp_server server hostname
* @param string $ftp_user_name server user name
* @param string $ftp_user_pass server user password
* @param string $ftp_type eg. FTP_BINARY or FTP_ASCII
* @param array $file_destination to or remote location of the file(s)
* @param array $file_source from or local location of the file(s)
* @return array ($ftp_status, $msg_error, $msg_success)
* @usage list($www_ftp_status, $www_msg_error, $www_msg_success) = ftp_file(WWW_IP_INTERNAL, WWW_FTP_USER_NAME, WWW_FTP_USER_PASS, FTP_BINARY, $arr_www_file_destination, $arr_file_source);
-=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=-
*/
//error_reporting(E_ALL);


define('FTP_SERVER','');		// This is present in live file
define('FTP_USER_NAME','');		// This is present in live file
define('FTP_USER_PASS','');		// This is present in live file


function h_($str='')
{
	return htmlentities($str);
}


function upload_file_to_img_server_using_ftp($file_source,$file_destination,$action=1)
{
	if($action==1) // UPLOAD NEW FILES
		$array_vars=ftp_file(FTP_SERVER,FTP_USER_NAME,FTP_USER_PASS,FTP_BINARY,$file_destination, $file_source);
	if($action==2) // DELETE FILES
		$array_vars=ftp_file_delete(FTP_SERVER,FTP_USER_NAME,FTP_USER_PASS,$file_source);
	if($action==3) // RENAME THE FILES NAME
		$array_vars=ftp_file_rename(FTP_SERVER,FTP_USER_NAME,FTP_USER_PASS,$file_source, $file_destination);
	if($action==4) // CREATE NEW FOLDER
		$array_vars=ftp_create_folder(FTP_SERVER,FTP_USER_NAME,FTP_USER_PASS,$file_source);
	return ($array_vars);
}

function ftp_file($ftp_server, $ftp_user_name, $ftp_user_pass, $ftp_type, $file_destination, $file_source)
{
	$ftp_status = FALSE;
	$msg_error = "";
	$msg_success = "";
	if(!$ftp_server || !$ftp_user_name || !$ftp_user_pass)
	{
		$msg_error .= "Required parameter are missing.<br>";
	}
	elseif(!is_array($file_destination) || !is_array($file_source))
	{
		$msg_error .= "The value of the variable file_destination and file_source needs to be an array.<br>";
	}
	elseif(count($file_destination) < 1 || count($file_source) < 1)
	{
		$msg_error .= "The value of the variable file_destination and file_source cannot be empty.<br>";
	}
	else
	{
		$conn_id = ftp_connect(gethostbyaddr($ftp_server));
		if(!$conn_id)
		{
			$msg_error .= "FTP Connection Failed<br>";
		}
		else
		{
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

			if(!$login_result)
			{
				//$msg_error .= "Failed to connect to the server <b>".h_($ftp_server)."</b> for user <b>".h_($ftp_user_name)."</b><br>";
				$msg_error .= "FTP Connection Failed<br>";
			}
			else
			{
				//$msg_success .= "Connected to the server <b>".h_($ftp_server)."</b> for user <b>".h_($ftp_user_name)."</b><br>";
				$msg_success .= "FTP Server Connected<br>";
				$ftp_status = TRUE;
				$i = 0;
				$is_transfered='';
				foreach($file_source as $key => $value)
				{
					echo "<br>Dest: ".$file_destination[$key]." - Source : ".$file_source[$key];
					
					$transfered = ftp_put($conn_id, $file_destination[$key], $file_source[$key], $ftp_type);

					$i++;
					if(!$transfered)
					{
						$is_transfered.="0,";
						$msg_error .= "$i- Failed to transfer: <b>".h_(basename($file_source[$key]))."</b><br>";
						$ftp_status = FALSE;
					}
					else
					{
						$is_transfered.="1,";
						$msg_success .= "$i- File Transfered: <b>".h_(basename($file_source[$key]))."</b> to <b>".h_(str_replace("/home/sysadmin/public_html", "", $file_destination[$key]))."</b><br>";
					}
					// testing only - begin
					echo "<pre>";print_r(ftp_nlist($conn_id, dirname($file_destination[$key])));echo "</pre>";
					// testing only - end
				}
			}
			
			// close the FTP stream
			ftp_quit($conn_id);
		}
	}
	$is_transfered=substr($is_transfered,0,-1);
	return array($ftp_status, $msg_error, $msg_success,$is_transfered);
}

/**
* to delete file between servers
* @param array $files_2_delete remote location of the file(s) to be deleted
* @return array ($ftp_status, $msg_error, $msg_success)
*
* @usage list($www_ftp_status, $www_msg_error, $www_msg_success) = ftp_file_delete(WWW_IP_INTERNAL, WWW_FTP_USER_NAME, WWW_FTP_USER_PASS, $files_2_delete);
*/
function ftp_file_delete($ftp_server, $ftp_user_name, $ftp_user_pass, $files_2_delete)
{
	$ftp_status = FALSE;
	$msg_error = "";
	$msg_success = "";

	if(!$ftp_server || !$ftp_user_name || !$ftp_user_pass)
	{
		$msg_error .= "Required parameter(s) missing<br>";
	}
	elseif(!is_array($files_2_delete))
	{
		$msg_error .= "Variable needs to be an array.<br>";
	}
	elseif(count($files_2_delete) < 1)
	{
		$msg_error .= "Variable cannot be empty.<br>";
	}
	else
	{
		$conn_id = @ftp_connect(gethostbyaddr($ftp_server));
		if(!$conn_id)
		{
			$msg_error .= "FTP Connection Failed<br>";
		}
		else
		{
			$login_result = @ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
			if(!$login_result)
			{
				//$msg_error .= "Failed to connect server <b>".h_($ftp_server)."</b> for user <b>".h_($ftp_user_name)."</b><br>";
				$msg_error .= "FTP Connection Failed<br>";
			}
			else
			{
				//$msg_success .= "Connected to server <b>".h_($ftp_server)."</b>, for user <b>".h_($ftp_user_name)."</b><br>";
				$msg_success .= "FTP Server Connected<br>";
				$ftp_status = TRUE;
				$i = 0;
				$is_deleted='';
				foreach($files_2_delete as $key => $value)
				{
					$deleted = @ftp_delete($conn_id, $files_2_delete[$key]);
					$i++;
					$is_deleted=0;
					if(!$deleted)
					{
						$is_deleted="0,";
						$msg_error .= "$i- Failed to delete: <b>".h_(basename($files_2_delete[$key]))."</b><br>";
						$ftp_status = FALSE;
					}
					else
					{
						$is_deleted="1,";
						$msg_success .= "$i- Deleted: <b>".h_(basename($files_2_delete[$key]))."</b><br>";
					}
				}
			}
			/* testing only - begin
			echo "<pre>";print_r(ftp_nlist($conn_id, dirname($files_2_delete[$key])));echo "</pre>";
			// testing only - end */
			@ftp_quit($conn_id);
		}
	}
	$is_deleted=substr($is_deleted,0,-1);
	return array($ftp_status, $msg_error, $msg_success, $is_deleted);
}

/**
* to rename file between servers
* @param array $files_2_rename remote location of the file(s) to be renamed
* @param array $files_newname newname for file(s) in remote location to be renamed
* @return array ($ftp_status, $msg_error, $msg_success)
* @usage list($www_ftp_status, $www_msg_error, $www_msg_success) = ftp_file_rename(WWW_IP_INTERNAL, WWW_FTP_USER_NAME, WWW_FTP_USER_PASS, $arr_www_files_2_rename, $arr_www_files_newname);
*/

function ftp_file_rename($ftp_server, $ftp_user_name, $ftp_user_pass, $files_2_rename, $files_newname)
{
	$ftp_status = FALSE;
	$msg_error = "";
	$msg_success = "";
	if(!$ftp_server || !$ftp_user_name || !$ftp_user_pass)
	{
		$msg_error .= "Required parameter(s) missing<br>";
	}
	elseif(!is_array($files_2_rename) || !is_array($files_newname))
	{
		$msg_error .= "files_2_rename and files_newname needs to be an array.<br>";
	}
	elseif(count($files_2_rename) < 1 || count($files_newname) < 1)
	{
		$msg_error .= "files_2_rename and files_newname cannot be empty.<br>";
	}
	else
	{
		$conn_id = @ftp_connect(gethostbyaddr($ftp_server));
		if(!$conn_id)
		{
			$msg_error .= "FTP Connection Failed<br>";
		}
		else
		{
			$login_result = @ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
			if(!$login_result)
			{
				//$msg_error .= "Failed to connect server <b>".h_($ftp_server)."</b> for user <b>".h_($ftp_user_name)."</b><br>";
				$msg_error .= "FTP Connection Failed<br>";
			}
			else
			{
				//$msg_success .= "Connected to server <b>".h_($ftp_server)."</b>, for user <b>".h_($ftp_user_name)."</b><br>";
				$msg_success .= "FTP Server Connected<br>";
				$ftp_status = TRUE;

				$i = 0;
				$is_rename='';
				foreach($files_2_rename as $key => $value)
				{
					$renamed = @ftp_rename($conn_id, $files_2_rename[$key], $files_newname[$key]);
					$i++;
					if(!$renamed)
					{
						$is_rename="0,";
						$msg_error .= "$i- Failed to rename: <b>".h_(basename($files_2_rename[$key]))."</b><br>";
						$ftp_status = FALSE;
					}
					else
					{
						$is_rename="1,";
						$msg_success .= "$i- File Renamed: <b>".h_(basename($files_2_rename[$key]))."</b> to <b>".h_(str_replace("/home/sysadmin/public_html", "", $files_newname[$key]))."</b><br>";
					}
				}
			}
			/* testing only - begin
			echo "<pre>";print_r(ftp_nlist($conn_id, dirname($files_2_rename[$key])));echo "</pre>";
			// testing only - end */
			// close the FTP stream
			@ftp_quit($conn_id);
		}
	}
	$is_rename=substr($is_rename,0,-1);
	return array($ftp_status, $msg_error, $msg_success,$is_rename);

}

/**
* get size b, kb, mb, gb, tb
* @param integer $size eg. 1024*50
* @return string eg. 50 Kb
* @usage echo get_size($size);
*/
function get_size($size)
{
	// SETUP BASIC SIZE MEASUREMENTS.
	$kb = 1024; // Kilobyte
	$mb = 1024 * $kb; // Megabyte
	$gb = 1024 * $mb; // Gigabyte
	$tb = 1024 * $gb; // Terabyte

	if($size < $kb) return $size." b";
	elseif($size < $mb) return round(($size/$kb), 2)." Kb";
	elseif($size < $gb) return round(($size/$mb), 2)." Mb";
	elseif($size < $tb) return round(($size/$gb), 2)." Gb";
	else return round(($size/$tb), 2)." Tb";
}

function http_file_exists($url, $followRedirects = true)
{
	$url_parsed = @parse_url($url);
	@extract($url_parsed);
	if (!@$scheme) $url_parsed =@parse_url('http://'.$url);
	@extract($url_parsed);

	if(!@$port) $port = 80;
	if(!@$path) $path = '/';
	if(@$query) $path .= '?'.$query;

	$out = "HEAD $path HTTP/1.0\r\n";
	$out .= "Host: $host\r\n";
	$out .= "Connection: Close\r\n\r\n";
	
	if(!$fp = @fsockopen($host, $port, $es, $en, 5))
	{
		return false;
	}
	
	fwrite($fp, $out);
	while (!feof($fp))
	{
		$s = fgets($fp, 128);
		if(($followRedirects) && (preg_match('/^Location:/i', $s) != false))
		{
			fclose($fp);
			return http_file_exists(trim(preg_replace("/Location:/i", "", $s)));
		}
		if(preg_match('/^HTTP(.*?)200/i', $s))
		{
			fclose($fp);
			return true;
		}
	}

	fclose($fp);
	return false;
}


/**
* to create folder servers
* @param array $files_2_rename remote location of the file(s) to be renamed
* @param array $files_newname newname for file(s) in remote location to be renamed
* @return array ($ftp_status, $msg_error, $msg_success)
* @usage list($www_ftp_status, $www_msg_error, $www_msg_success) = ftp_file_rename(WWW_IP_INTERNAL, WWW_FTP_USER_NAME,
WWW_FTP_USER_PASS, $arr_www_files_2_rename, $arr_www_files_newname);

*/

function ftp_create_folder($ftp_server, $ftp_user_name,$ftp_user_pass, $dirname)
{
	$ftp_status = FALSE;
	$msg_error = "";
	$msg_success = "";
	if(!$ftp_server || !$ftp_user_name || !$ftp_user_pass)
	{
		$msg_error .= "Required parameter(s) missing<br>";
	}
	elseif($dirname=='')
	{
		$msg_error .= "Folder is not allowed.<br>";
	}
	else
	{
		$conn_id = @ftp_connect(gethostbyaddr($ftp_server));
		if(!$conn_id)
		{
			$msg_error .= "FTP Connection Failed<br>";
		}
		else
		{
			$login_result = @ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
			if(!$login_result)
			{
				//$msg_error .= "Failed to connect server <b>".h_($ftp_server)."</b> for user <b>".h_($ftp_user_name)."</b><br>";
				$msg_error .= "FTP Connection Failed<br>";
			}
			else
			{
				//$msg_success .= "Connected to server <b>".h_($ftp_server)."</b>, for user <b>".h_($ftp_user_name)."</b><br>";
				$msg_success .= "FTP Server Connected<br>";
				$ftp_status = TRUE;

				$i = 0;
				$makedir='';
				$makedir = @ftp_mkdir($conn_id, $dirname);
				//$permisssion = ftp_chmod($conn_id,777,$dirname);
				ftp_site($conn_id,"chmod 0777 ".$dirname);
				if(!$makedir)
				{
					$makedir="0,";
					$msg_error .= "$i- Failed to create: <b>".h_(basename($dirname))."</b><br>";
					$ftp_status = FALSE;
				}
				else
				{
					$makedir="1,";
					$msg_success .= "$i- Folder Created: <b>".h_(basename($dirname))."</b></b><br>";
				}
			}
			/* testing only - begin
			echo "<pre>";print_r(ftp_nlist($conn_id, dirname($files_2_rename[$key])));echo "</pre>";
			// testing only - end */
			// close the FTP stream
			@ftp_quit($conn_id);
		}
	}
	return array($ftp_status, $msg_error, $msg_success);
}
?>
