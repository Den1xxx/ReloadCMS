<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

//---------------------------------------------------------//
// This function perform removing of files and directories //
//---------------------------------------------------------//
function rcms_delete_files($file, $recursive = false) {
	if (!IGNORE_LOCK_FILES && is_file($file . '.lock')) return false;
	if($recursive && @is_dir($file)) {
		$els = rcms_scandir($file, '', '', true);
		foreach ($els as $el) {
			if($el != '.' && $el != '..'){
				rcms_delete_files($file . '/' . $el, true);
			}
		}
	}
	if(@is_dir($file)) {
		return rmdir($file);
	} else {
		return @unlink($file);
	}
}

function rcms_clear_directory($dir) {
if (@is_dir($dir)) $files = rcms_scandir($dir, '', '', true);
else return false;
	foreach ($files as $file) 
		if($file != '.' && $file != '..')	rcms_delete_files($dir . '/' . $file);
	return true; 
}

//---------------------------------------------------------//
// This function perform renaming of file                  //
//---------------------------------------------------------//
function rcms_rename_file($oldfile, $newfile) {
	rename($oldfile, $newfile);
	return true;
}

function rcms_copy_file($oldfile, $newfile) {
	copy($oldfile, $newfile);
	return true;
}

//---------------------------------------------------------//
// This function perform creating of directory             //
//---------------------------------------------------------//
function rcms_mkdir($dir) {
	if(defined('SAFEMODE_HACK') && SAFEMODE_HACK){
		$url = parse_url(SAFEMODE_HACK_FTP);
		if($url['scheme'] != 'ftp') return false;
		return rcms_ftp_mkdir($dir, $url['host'], $url['user'], $url['pass'], '.' . $url['path']);
	}
	if(!@is_dir($dir)){
		if(!@is_dir(dirname($dir))) rcms_mkdir(dirname($dir));
	}
	return @mkdir($dir, 0777);
}

//---------------------------------------------------------//
// This function perform creating of directory by FTP      //
//---------------------------------------------------------//
function rcms_ftp_mkdir($dir, $server, $username, $password, $path) {
	if(!@is_dir(dirname($dir))) rcms_ftp_mkdir(dirname($dir));
	$ftp = ftp_connect($server);
	ftp_login($ftp, $username, $password);
	if(RCMS_ROOT_PATH == '../') $path .= 'admin/';
	ftp_mkdir($ftp, $path . $dir);
	ftp_site($ftp, 'CHMOD 0777 ' . $path . $dir);
	ftp_close($ftp);
	return true;
}

function rcms_parse_ini_file($filename, $blocks = false){
	$array1 = file($filename);
	$section = '';
	foreach ($array1 as $filedata) {
		$dataline = trim($filedata);
		$firstchar = substr($dataline, 0, 1);
		if ($firstchar != ';' && !empty($dataline)) {
			if ($blocks && $firstchar == '[' && substr($dataline, -1, 1) == ']') {
				$section = strtolower(substr($dataline, 1, -1));
			} else {
				$delimiter = strpos($dataline, '=');
				if ($delimiter > 0) {
					preg_match("/^[\s]*(.*?)[\s]*[=][\s]*(\"|)(.*?)(\"|)[\s]*$/", $dataline, $matches);
					$key = $matches[1];
					$value = $matches[3];

					if($blocks){
						if(!empty($section)){
							$array2[$section][$key] = stripcslashes($value);
						}
					} else {
						$array2[$key] = stripcslashes($value);
					}
				} else {
					if($blocks){
						if(!empty($section)){
							$array2[$section][trim($dataline)] = '';
						}
					} else {
						$array2[trim($dataline)] = '';
					}
				}
			}
		}
	}
	return (!empty($array2)) ? $array2 : false;
}

function rcms_chmod($file, $val, $rec = false) {
	$res = @chmod(realpath($file), $val);
	if(@is_dir($file) && $rec){
		$els = rcms_scandir($file);
		foreach ($els as $el) {
			$res = $res && rcms_chmod($file . '/' . $el, $val, true);
		}
	}
	return $res;
}


//---------------------------------------------------------//
// This function is php5 file_put_contents copy            //
//---------------------------------------------------------//
function file_write_contents($file, $text, $mode = 'w+') {
	if(!@is_dir(dirname($file))){
		trigger_error('Directory not found: ' . dirname($file));
		return false;
	}
		$flock = fopen($file . '.lock', 'w+');
		fwrite($flock, 'lock'); //���� �� ������� ���� �����
		fclose($flock);
	if (!$fp=fopen($file, $mode)) {	
		rcms_delete_files($file.'.lock');
		trigger_error('Cannot write to file').$file;
		return FALSE;
	}
		flock($fp, LOCK_EX);
		fwrite($fp, $text);
		flock($fp, LOCK_UN);
		fclose($fp);
		rcms_delete_files($file . '.lock');
        return TRUE;
}

function gzfile_write_contents($file, $text, $mode = 'w+') {
	set_time_limit(0);
if (!IGNORE_LOCK_FILES && is_file($file . '.lock')) return false;		
	$fp = fopen($file . '.lock', 'w+'); 
	fwrite($fp, 'lock'); 
	fclose($fp);
	if($fp = gzopen($file, $mode)) {
		if(!empty($text) && !gzwrite($fp, $text)) return false;
		gzclose($fp);
	} else return false;
	rcms_delete_files($file . '.lock');
	return true;
}

//---------------------------------------------------------//
// This function is created for compatibility              //
//---------------------------------------------------------//
if(!function_exists('file_get_contents')){
	function file_get_contents($file) {
		if(!$file = file($file)) return false;
		if(!$file = implode('', $file)) return false;
		return $file;
	}
}

function gzfile_get_contents($file) {
	if(!$file = gzfile($file)) return false;
	if(!$file = implode('', $file)) return false;
	return $file;
}

//---------------------------------------------------------//
// Function to create ini files                            //
//---------------------------------------------------------//
function write_ini_file($data, $filename, $process_sections = false){
	$ini = '';
	if(!$process_sections){
		if(is_array($data)){
			foreach ($data as $key => $value){
				$ini .= $key . ' = "' . str_replace('"', '&quot;', $value) . "\"\n";
			}
		}
	} else {
		if(is_array($data)){
			foreach ($data as $key => $value){
				$ini .= '[' . $key . ']' . "\n";
				foreach ($value as $ekey => $evalue){
					$ini .= $ekey . ' = "' . str_replace('"', '&quot;', $evalue) . "\"\n";
				}
			}
		}
	}
	return file_write_contents($filename, $ini);
}

//---------------------------------------------------------//
// Advanced php5 scandir analog                            //
//---------------------------------------------------------//
function rcms_scandir($directory, $exp = '', $type = 'all', $do_not_filter = false) {
	$dir = $ndir = array();
	if(!empty($exp)){
		$exp = '/^' . str_replace('*', '(.*)', str_replace('.', '\\.', $exp)) . '$/';
	}
	if(!empty($type) && $type !== 'all'){
		$func = 'is_' . $type;
	}
	if(@is_dir($directory)){
		if(!($fh = @opendir($directory))) return false;
		while (false !== ($filename = readdir($fh))) {
			if(substr($filename, 0, 1) != '.' || $do_not_filter) {
				if((empty($type) || $type == 'all' || $func($directory . '/' . $filename)) && (empty($exp) || preg_match($exp, $filename))){
					$dir[] = $filename;
				}
			}
		}
		closedir($fh);
		natsort($dir);
	}
	return $dir;
}

function rcms_get_current_id($directory, $ending) {
	$files = rcms_scandir($directory, '*' . $ending);
	$endfile = @end($files);
	$current = substr($endfile, 0, strlen($endfile)-strlen($ending));
	$current +=1;
	return $current . $ending;
}

function get_rights_string($file, $if = false){
	$perms = fileperms($file);
	$info = '';
	if(!$if){
		if (($perms & 0xC000) == 0xC000) {
			// Socket
			$info = 's';
		} elseif (($perms & 0xA000) == 0xA000) {
			// Symbolic Link
			$info = 'l';
		} elseif (($perms & 0x8000) == 0x8000) {
			// Regular
			$info = '-';
		} elseif (($perms & 0x6000) == 0x6000) {
			// Block special
			$info = 'b';
		} elseif (($perms & 0x4000) == 0x4000) {
			// Directory
			$info = 'd';
		} elseif (($perms & 0x2000) == 0x2000) {
			// Character special
			$info = 'c';
		} elseif (($perms & 0x1000) == 0x1000) {
			// FIFO pipe
			$info = 'p';
		} else {
			// Unknown
			$info = 'u';
		}
	}


	// Owner
	$info .= (($perms & 0x0100) ? 'r' : '-');
	$info .= (($perms & 0x0080) ? 'w' : '-');
	$info .= (($perms & 0x0040) ?
	(($perms & 0x0800) ? 's' : 'x' ) :
	(($perms & 0x0800) ? 'S' : '-'));

	// Group
	$info .= (($perms & 0x0020) ? 'r' : '-');
	$info .= (($perms & 0x0010) ? 'w' : '-');
	$info .= (($perms & 0x0008) ?
	(($perms & 0x0400) ? 's' : 'x' ) :
	(($perms & 0x0400) ? 'S' : '-'));

	// World
	$info .= (($perms & 0x0004) ? 'r' : '-');
	$info .= (($perms & 0x0002) ? 'w' : '-');
	$info .= (($perms & 0x0001) ?
	(($perms & 0x0200) ? 't' : 'x' ) :
	(($perms & 0x0200) ? 'T' : '-'));

	return $info;
}

function get_rights($file){
	return substr(sprintf('%o', fileperms($file)), -4);
}

function convert_rights_string($mode) {
	$mode = str_pad($mode,9,'-');
	$trans = array('-'=>'0','r'=>'4','w'=>'2','x'=>'1');
	$mode = strtr($mode,$trans);
	$newmode = '0';
	$owner = (int) $mode[0] + (int) $mode[1] + (int) $mode[2]; 
	$group = (int) $mode[3] + (int) $mode[4] + (int) $mode[5]; 
	$world = (int) $mode[6] + (int) $mode[7] + (int) $mode[8]; 
	$newmode .= $owner . $group . $world;
	return intval($newmode, 8);
}


function download_file($file_name) {
    if (!empty($file_name)) {
		if (file_exists($file_name)) {
		header("Content-Disposition: attachment; filename=" . basename($file_name));   
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Description: File Transfer");            
		header("Content-Length: " . filesize($file_name));		
		flush(); // this doesn't really matter.
		$fp = fopen($file_name, "r");
		while (!feof($fp)) {
			echo fread($fp, 65536);
			flush(); // this is essential for large downloads
		} 
		fclose($fp);
		die();
		} else {
        header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        header('Status: 404 Not Found'); 
		die();
        }
    } 
}
?>