<?
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////


//Loading function
function load_file($requested_url, $loading_file, $settimeout ) {
	global  $user_agent, $requested_size;

	$settimeout  = IntVal($settimeout );
	if ($settimeout >0)
		$start_time = getmicrotime();

	$real_url = $requested_url;
	$start_size = 0;
	$real_size = 0;

	$if_can_doupload = false;

	// Initialize if doupload
	$real_url_tmp = "";
	$real_size_tmp = 0;
	if (file_exists($loading_file.".tmp") && file_exists($loading_file.".log") && filesize($loading_file.".log")>0)
	{
		$fh = fopen($loading_file.".log", "rb");
		$file_contents_tmp = fread($fh, filesize($loading_file.".log"));
		fclose($fh);

		list($real_url_tmp, $real_size_tmp) = split("\n", $file_contents_tmp);
		$real_url_tmp = Trim($real_url_tmp);
		$real_size_tmp = Trim($real_size_tmp);
	}
	if ($real_size_tmp<=0 || strlen($real_url_tmp)<=0)
	{
		$real_url_tmp = "";
		$real_size_tmp = 0;

		if (file_exists($loading_file.".tmp"))
			@unlink($loading_file.".tmp");

		if (file_exists($loading_file.".log"))
			@unlink($loading_file.".log");
	}
	else
	{
		$real_url = $real_url_tmp;
		$real_size = $real_size_tmp;
		$start_size = filesize($loading_file.".tmp");
	}
	// end initialize if doupload


	// Search file and load info
	do
	{
		$lasturl = $real_url;
		$redirection = "";
		$parsedurl = @parse_url($real_url);
			$host = $parsedurl["host"];
			$port = $parsedurl["port"];
			$hostname = $host;
		$port = $port ? $port : "80";
		$sockethandle = @fsockopen($host, $port, $error_id, $error_msg, 30);
		if (!$sockethandle)	return false;
		
		else
		{
			if (!$parsedurl["path"])
				$parsedurl["path"] = "/";

			$request = "";
				$request .= "HEAD ".$parsedurl["path"].($parsedurl["query"] ? '?'.$parsedurl["query"] : '')." HTTP/1.0\r\n";
				$request .= "Host: $hostname\r\n";

			if ($user_agent != "")
				$request .= "User-Agent: $user_agent\r\n";
			$request .= "\r\n";
			fwrite($sockethandle, $request);
			$result = "";
			$answer_header = "";
			while (($result = fgets($sockethandle, 4024)) && $result!="\r\n")	{
				$answer_header .= $result;
			}
			fclose($sockethandle);
			$ar_answer_header = split("\r\n", $answer_header);
			$answer_proto = "";
			$answer_version = "";
			$answer_code = 0;
			$answer_msg = "";
			if (ereg("([A-Z]{4})/([0-9.]{3}) ([0-9]{3})", $ar_answer_header[0], $regs))	{
				$answer_proto = $regs[1];
				$answer_version = $regs[2];
				$answer_code = IntVal($regs[3]);
				$answer_msg = substr($ar_answer_header[0], strpos($ar_answer_header[0], $answer_code) + strlen($answer_code) + 1, strlen($ar_answer_header[0]) - strpos($ar_answer_header[0], $answer_code) + 1);
			}

			if ($answer_code!=200 && $answer_code!=302)	{
	rcms_showAdminMessage(__('Server error').': '.$answer_code.' - '.$answer_msg.'<br/>'.htmlspecialchars($requested_url));
				return false;
			}
			$to_ranges = "";
			$url_location = "";
			$real_new_size = 0;
			for ($i = 1; $i < count($ar_answer_header); $i++)	{
				if (strpos($ar_answer_header[$i], "Location") !== false)
				$url_location = trim(substr($ar_answer_header[$i], strpos($ar_answer_header[$i], ":") + 1, strlen($ar_answer_header[$i]) - strpos($ar_answer_header[$i], ":") + 1));
				elseif (strpos($ar_answer_header[$i], "Content-Length") !== false)
				$real_new_size = intVal(trim(substr($ar_answer_header[$i], strpos($ar_answer_header[$i], ":") + 1, strlen($ar_answer_header[$i]) - strpos($ar_answer_header[$i], ":") + 1)));
				elseif (strpos($ar_answer_header[$i], "Accept-Ranges") !== false)
				$to_ranges = trim(substr($ar_answer_header[$i], strpos($ar_answer_header[$i], ":") + 1, strlen($ar_answer_header[$i]) - strpos($ar_answer_header[$i], ":") + 1));
			}

			if (strlen($url_location)>0) {
				$redirection = $url_location;
				$redirected = true;
				if ((strpos($redirection, "http://")===false))
					$real_url = dirname($lasturl)."/".$redirection;
				else
					$real_url = $redirection;
			}

			if (strlen($url_location)<=0)
				break;
		}
	}
	while (true);
	// End search file and load info

	$if_can_doupload = ($to_ranges == "bytes");

	// If doupload
	if ($if_can_doupload)	{
		$fh = fopen($loading_file.".log", "wb");
		if (!$fh)		{
		rcms_showAdminMessage(__('File error').': <br/>'. __('File').' '.$loading_file.'.log '.__('do not open to write'));
			return false;
		}
		fwrite($fh, $real_url."\n");
		fwrite($fh, $real_new_size."\n");
		fclose($fh);
	}
	// End if doupload

	$requested_size = $real_new_size;

	// Loading file
	$parsedurl = parse_url($real_url);

		$host = $parsedurl["host"];
		$port = $parsedurl["port"];
		$hostname = $host;
	
	$port = $port ? $port : "80";

		rcms_showAdminMessage(__('Loader message').': <br/>'.__('Start loading').' '.__('from').' http://'.$host);
	$sockethandle = @fsockopen($host, $port, $error_id, $error_msg, 30);
	if (!$sockethandle)	{
		rcms_showAdminMessage('<br/>'. __('Error').' '. ('loading').' ['.$error_id.'] '.$error_msg);
		return false;
	}
	else
	{
		if (!$parsedurl["path"])	$parsedurl["path"] = "/";
		
		$request = "GET ".$parsedurl["path"].($parsedurl["query"] ? '?'.$parsedurl["query"] : '')." HTTP/1.0\r\n";
		$request .= "Host: $hostname\r\n";
		if ($user_agent != "") $request .= "User-Agent: $user_agent\r\n";

		if ($if_can_doupload && $start_size>0)	$request .= "Range: bytes=".$start_size."-\r\n";

		$request .= "\r\n";

		fwrite($sockethandle, $request);

		$result = "";
		$answer_header = "";
		while (($result = fgets($sockethandle, 4096)) && $result!="\r\n")
			$answer_header .= $result;

		$ar_answer_header = split("\r\n", $answer_header);

		$answer_proto = "";
		$answer_version = "";
		$answer_code = 0;
		$answer_msg = "";
		if (ereg("([A-Z]{4})/([0-9.]{3}) ([0-9]{3})", $ar_answer_header[0], $regs))
		{
			$answer_proto = $regs[1];
			$answer_version = $regs[2];
			$answer_code = IntVal($regs[3]);
			$answer_msg = substr($ar_answer_header[0], strpos($ar_answer_header[0], $answer_code) + strlen($answer_code) + 1, strlen($ar_answer_header[0]) - strpos($ar_answer_header[0], $answer_code) + 1);
		}

		if ($answer_code!=200 && $answer_code!=302 && $answer_code!=206)
		{
		rcms_showAdminMessage(__('Server error').'# '.$answer_code.' - '.$answer_msg);
			return false;
		}
		
		$to_ranges = "";
		$content_range = "";
		$content_length = 0;
		for ($i = 1; $i < count($ar_answer_header); $i++)	{
			if (strpos($ar_answer_header[$i], "Content-Range") !== false)
			$content_range = trim(substr($ar_answer_header[$i], strpos($ar_answer_header[$i], ":") + 1, strlen($ar_answer_header[$i]) - strpos($ar_answer_header[$i], ":") + 1));
			elseif (strpos($ar_answer_header[$i], "Content-Length") !== false)
				$content_length = doubleval(Trim(substr($ar_answer_header[$i], strpos($ar_answer_header[$i], ":") + 1, strlen($ar_answer_header[$i]) - strpos($ar_answer_header[$i], ":") + 1)));
			elseif (strpos($ar_answer_header[$i], "Accept-Ranges") !== false)
				$to_ranges = Trim(substr($ar_answer_header[$i], strpos($ar_answer_header[$i], ":") + 1, strlen($ar_answer_header[$i]) - strpos($ar_answer_header[$i], ":") + 1));
		}

		$reload_file = true;
		if (strlen($content_range)>0) {
			if (eregi(" *bytes +([0-9]*) *- *([0-9]*) */ *([0-9]*)", $content_range, $regs)){
				$start_bytes = doubleval($regs[1]);
				$end_bytes = doubleval($regs[2]);
				$size_bytes = doubleval($regs[3]);

				if ($start_bytes==$start_size && $end_bytes==($real_new_size-1)	&& $size_bytes==$real_new_size)	{
					$reload_file = false;
				}
			}
		}

		if ($reload_file) {
			@unlink($loading_file.".tmp");
			$start_size = 0;
		}

		if (($content_length+$start_size)!=$real_new_size)	{
		rcms_showAdminMessage(__('File error').': '. __('error size').' - '.$real_new_size.__(' bytes in size'));
			return false;
		}

		$fh = fopen($loading_file.".tmp", "ab");
		if (!$fh)	{
		rcms_showAdminMessage(__('File error').': '.$loading_file.'.tmp '.__('do not open to write'));
			return false;
		}

		$finish = true;
		$download_size = (double) $start_size;
		while (!feof($sockethandle)) {
			if ($settimeout >0 && (getmicrotime()-$start_time)>$settimeout ) {
				$finish = false;
				break;
			}
			$result = fread($sockethandle, 256 * 1024);
			$download_size += strlen($result);
			if ($result=="") break;
			fwrite($fh, $result);
		}
		fclose($fh);
		fclose($sockethandle);

		if ($finish){
			@unlink($loading_file);
			if (!@rename($loading_file.".tmp", $loading_file)) {
		rcms_showAdminMessage(__('File rename error').'<br/>'.__('File').'1: '.$loading_file.'.tmp<br/>'. __('File').'2: '.$loading_file);
				return false;
			}
			@unlink($loading_file.".tmp");
		}
		else
			return 2;

		rcms_showAdminMessage('<br/>'.__('Loader message').': <br/>'. __('File').' '.  $loading_file.' '. __('loaded and saved').': '.$download_size.__(' bytes in size'));
		@unlink($loading_file.".log");
		return 1;
	}
	// End loading file
}
//End loading function
?>