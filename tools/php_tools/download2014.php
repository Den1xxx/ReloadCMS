<?
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) 2011 ReloadCMS Development Team                            //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

//Initialize
$lang=array();
$user_agent = "ReloadCMS_Loader";
$rcms_release = 		array(
	array(
	"title" => "ReloadCMS 1.5.2 The Architect",
	"logo" => "http://fromgomel.com/uploads/rcms_2011.png",
	"download" =>"http://fromgomel.com/uploads/reloadcms2014_1398864751.tar.gz",
	"documentation" => "https://github.com/Den1xxx/ReloadCMS/blob/master/ReadMe.md",
	"comments" => "Latest stable version"				)
	);
$tmp_name = 'reloadcms.tgz';

if (@preg_match('#ru#i',$_SERVER['HTTP_ACCEPT_LANGUAGE']))	$user_lang = 'ru';
elseif (@preg_match('#ua#i',$_SERVER['HTTP_ACCEPT_LANGUAGE']))	$user_lang = 'uk';
else $user_lang = 'en';
if (!empty($_GET['lang'])) $user_lang = $_GET['lang'];
//End initialize

//Russian lang config
if ($user_lang=='ru'){
$lang['This script load ReloadCMS to your site'] = 'Этот скрипт загрузит ReloadCMS на ваш сайт';
$lang['Please push button to delete downloader:'] = 'Пожалуйста нажмите кнопку, чтобы удалить загрузчик:';
$lang['Delete'] = 'Удалить';
$lang['Done'] = 'Сделано';
$lang['Clean root directory'] = 'Очистить корневой каталог';
$lang['Go to site'] = 'Перейти на сайт';
$lang['If you have just installed ReloadCMS, please, register a first user who will be a site administrator automatically. '] = 'Сразу после установки системы зарегистрируйте первого пользователя, который автоматически получит все привилегии администратора сайта.';
$lang['Loading ReloadCMS'] = 'Загрузка ReloadCMS';
$lang['Loader message'] = 'Сообщение загрузчика';
$lang['Start'] = 'Старт';
$lang['loading'] = 'загружается';
$lang['Start loading'] = 'Начало загрузки';
$lang['from'] = 'из';
$lang['loaded and saved'] = 'загружен и сохранён';
$lang['Error'] = 'Ошибка';
$lang['Error occurred'] = 'Произошла ошибка';
$lang['Load query file'] = 'Загружается файл запросов';
$lang['Initialize'] = 'Инициализация';
$lang['Please wait'] = 'Пожалуйста, подождите';
$lang['File'] = 'Файл';
$lang['Server error'] = 'Ошибка сервера';
$lang['File error'] = 'Файловая ошибка';
$lang['Size'] = 'Размер';
$lang['File rename error'] = 'Ошибка переименования файла';
$lang['End loading'] = 'Конец загрузки';
$lang['Unpack'] = 'Распаковать';
$lang['Unpack done'] = 'Распаковка завершена';
$lang['Unpack data'] = 'Распаковка данных';
$lang['Select version'] = 'Выберите версию';
$lang['Information'] = 'Информация';
}
//End russian lang config

//Ukrainian lang config
if ($user_lang=='uk'){
$lang['This script load ReloadCMS to your site'] = 'Цей скрипт завантажить ReloadCMS на ваш сайт';
$lang['Please push button to delete downloader:'] = 'Будь ласка натисніть кнопку, щоб видалити завантажувач:';
$lang['Delete'] = 'Видалити';
$lang['Done'] = 'Зроблено';
$lang['Clean root directory'] = 'Очистити кореневої каталог';
$lang['Go to site'] = 'Перейти на сайт';
$lang['If you have just installed ReloadCMS, please, register a first user who will be a site administrator automatically. '] = 'Відразу після установки системи зареєструйте першого користувача, який автоматично отримає всі привілеї адміністратора сайту.';
$lang['Loading ReloadCMS'] = 'Завантажується ReloadCMS';
$lang['Loader message'] = 'Повідомлення завантажувальника';
$lang['Start'] = 'Старт';
$lang['loading'] = 'завантажується';
$lang['Start loading'] = 'Початок завантаження';
$lang['from'] = 'із';
$lang['loaded and saved'] = 'завантажений і збережений';
$lang['Error'] = 'Помилка';
$lang['Error occurred'] = 'Сталася помилка';
$lang['Load query file'] = 'Завантажується файл запитів';
$lang['Initialize'] = 'Иніціалізація';
$lang['Please wait'] = 'Будь ласка, зачекайте';
$lang['File'] = 'Файл';
$lang['Server error'] = 'Помилка сервера';
$lang['File error'] = 'Помилка у файлі';
$lang['Size'] = 'Розмір';
$lang['File rename error'] = 'Помилка переіменування файла';
$lang['End loading'] = 'Кінець завантажування';
$lang['Unpack'] = 'Розпакувати';
$lang['Unpack done'] = 'Розпакування завершено';
$lang['Unpack data'] = 'Розпакування даних';
$lang['Select version'] = 'Виберіть версію';
$lang['Information'] = 'Інформація';
}
//End ukrainian lang config

// Archiver class
class archiveTar
{
	var $archive_name = '';
	var $tmp_file = 0;
	var $file_pos = 0;
	var $isGzipped = true;
	var $errors = array();

	function archiveTar(){
		if (!isset($this->errors)) $this->errors = array();
	}
	
/*
* Create archive from array filenames or directories, example:
* $backup = new archiveTar();
* $backup->archive_name = './content.tgz';
* $backup->createArchive(array('./config','./content'));
*/	
	
	function createArchive($file_list){
		$result = false;
		
		if (file_exists($this->archive_name) && is_file($this->archive_name)) 	$newArchive = false;
		else $newArchive = true;

		if ($newArchive){
			if (!$this->openWrite())
				return false;
		} else {

		if (filesize($this->archive_name) == 0)
			return $this->openWrite();

		if ($this->isGzipped){
			$this->closeTmpFile();

			if (!@rename($this->archive_name, $this->archive_name.'.tmp')){
				$this->errors[] = __('Cannot rename').' '.$this->archive_name.__(' to ').$this->archive_name.'.tmp';
				return false;
			}

			$tmpArchive = @gzopen($this->archive_name.'.tmp', 'rb');
			if (!$tmpArchive){
				$this->errors[] = $this->archive_name.'.tmp '.__('is not readable');
				@rename($this->archive_name.'.tmp', $this->archive_name);
				return false;
			}

			if (!$this->openWrite()){
				@rename($this->archive_name.'.tmp', $this->archive_name);
				return false;
			}

			$buffer = @gzread($tmpArchive, 512);
			if (!@gzeof($tmpArchive)){
				do {
					$binaryData = pack('a512', $buffer);
					$this->writeBlock($binaryData);
					$buffer = @gzread($tmpArchive, 512);
				}
				while (!@gzeof($tmpArchive));
			}
			@gzclose($tmpArchive);
			@unlink($this->archive_name.'.tmp');
		} else {
		$this->tmp_file = @fopen($this->archive_name, 'r+b');
		if (!$this->tmp_file)	return false;
		}
		}
		
		if (isset($file_list) && is_array($file_list)) {
		if (count($file_list)>0)
			$result = $this->packFileArray($file_list);
		} else $this->errors[] = __('No file').__(' to ').__('Archive');
		
		if (($result)&&(is_resource($this->tmp_file))){
			$binaryData = pack('a512', '');
			$this->writeBlock($binaryData);
		}

		$this->closeTmpFile();

		if ($newArchive && !$result){
		$this->closeTmpFile();
		@unlink($this->archive_name);
		}
		return $result;
	}

/*
* Restore archive including the path, example:
* $Archive = new archiveTar();
* $Archive->archive_name = './content.tgz';
* $Archive->restoreArchive('./');
*/

	function restoreArchive($path){
		
		$fileName = $this->archive_name;
		
		if (!$this->isGzipped){
			if (@file_exists($fileName)){
				if ($fp = @fopen($fileName, 'rb')){
					$data = fread($fp, 2);
					fclose($fp);
					if ($data == '\37\213'){
						$this->isGzipped = true;
					}
				}
			}
			elseif ((substr($fileName, -2) == 'gz') OR (substr($fileName, -3) == 'tgz')) $this->isGzipped = true;
		} 
		
		$result = true;

		if ($this->isGzipped)
			$this->tmp_file = @gzopen($fileName, 'rb');
		else
			$this->tmp_file = @fopen($fileName, 'rb');

		if (!$this->tmp_file){
			$this->errors[] = $fileName.' '.__('is not readable');
			return false;
		}
		
		$result = $this->unpackFileArray($path);
			$this->closeTmpFile();

		return $result;
	}

/* Show errors when pack/unpack 
*	Example:
* $Archive = new archiveTar();
* //pack or unpack;
* echo $Archive->showErrors($Archive->archive_name);	
*/

	function showErrors	($message = '') {
		$Errors = $this->errors;
		if(count($Errors)>0) {
		if (!empty($message)) $message = ' ('.$message.')';
			$message = __('Error occurred').$message.': <br/>';
			foreach ($Errors as $value)
				$message .= $value.'<br/>';
			return $message;	
		} else return '';
		
	}
	
	function packFileArray($file_array){
		$result = true;

		if (!$this->tmp_file){
			$this->errors[] = __('Invalid file descriptor');
			return false;
		}

		if (!is_array($file_array) || count($file_array)<=0)
          return true;

		for ($i = 0; $i<count($file_array); $i++){
			$filename = $file_array[$i];

			if ($filename == $this->archive_name)
				continue;

			if (strlen($filename)<=0)
				continue;

			if (!file_exists($filename)){
				$this->errors[] = __('No file').' '.$filename;
				continue;
			}
			
			if (!$this->tmp_file){
			$this->errors[] = __('Invalid file descriptor');
			return false;
			}

		if (strlen($filename)<=0){
			$this->errors[] = __('Filename').' '.__('is incorrect');;
			return false;
		}

		$filename = str_replace('\\', '/', $filename);

		$keep_filename = $this->makeGoodPath($filename);

		if (is_file($filename)){
			if (($file = @fopen($filename, 'rb')) == 0){
				$this->errors[] = __('Mode ').__('is incorrect');
			}
			

				if(($this->file_pos == 0)){
					if(!$this->writeHeader($filename, $keep_filename))
						return false;
				}

				while (($buffer = fread($file, 512)) != ''){
					$binaryData = pack('a512', $buffer);
					$this->writeBlock($binaryData);
				}
				
			fclose($file);
		}	else $this->writeHeader($filename, $keep_filename);

			if (@is_dir($filename)){
				if (!($handle = opendir($filename))){
					$this->errors[] = __('Error').': '.__('Directory ').$filename.__('is not readable');
					continue;
				}

				while (false !== ($dir = readdir($handle))){
					if ($dir!='.' && $dir!='..'){
						$file_array_tmp = array();
						if ($filename != '.')
							$file_array_tmp[] = $filename.'/'.$dir;
						else
							$file_array_tmp[] = $dir;

						$result = $this->packFileArray($file_array_tmp);
					}
				}

				unset($file_array_tmp);
				unset($dir);
				unset($handle);
			}
		}

		return $result;
	}

	function unpackFileArray($path){ 

	$path = str_replace('\\', '/', $path);

		if ($path == ''	|| (substr($path, 0, 1) != '/' && substr($path, 0, 3) != '../' && !strpos($path, ':')))
			$path = './'.$path;
		
		clearstatcache();

		while (strlen($binaryData = $this->readBlock()) != 0){

			if (!$this->readHeader($binaryData, $header))
				return false;

			if ($header['filename'] == '')
				continue;
			
			if ($header['typeflag'] == 'L'){			//reading long header
			$filename = '';
			$decr = floor($header['size']/512);
			
				for ($i = 0; $i < $decr; $i++){
				$content = $this->readBlock();
				$filename .= $content;
				}
		
				if (($laspiece = $header['size'] % 512) != 0){
				$content = $this->readBlock();
				$filename .= substr($content, 0, $laspiece);
				}

				$binaryData = $this->readBlock();

				if (!$this->readHeader($binaryData, $header))
				return false;

				else $header['filename'] = $filename;
				return true;
			}
			
				if (($path != './') && ($path != '/')){
					while (substr($path, -1) == '/')
						$path = substr($path, 0, strlen($path)-1);

					if (substr($header['filename'], 0, 1) == '/')
						$header['filename'] = $path.$header['filename'];
					else
						$header['filename'] = $path.'/'.$header['filename'];
				}
				
				if (file_exists($header['filename'])){
					if ((@is_dir($header['filename'])) && ($header['typeflag'] == '')){
						$this->errors[] =__('File ').$header['filename'].__(' already exists').__(' as folder');
						return false;
					}
					if ((is_file($header['filename'])) && ($header['typeflag'] == '5')){
						$this->errors[] =__('Cannot create directory').'. '.__('File ').$header['filename'].__(' already exists');
						return false;
					}
					if (!is_writeable($header['filename'])){
						$this->errors[] = __('Cannot write to file').'. '.__('File ').$header['filename'].__(' already exists');
						return false;
					}
				}
				
				elseif (($this->dirCheck(($header['typeflag'] == '5' ? $header['filename'] : dirname($header['filename'])))) != 1){
					$this->errors[] = __('Cannot create directory').' '.__(' for ').$header['filename'];
					return false;
				}

					if ($header['typeflag'] == '5'){
						if (!@file_exists($header['filename']))
						{
							if (!@mkdir($header['filename'], 0777))
							{
								$this->errors[] = __('Cannot create directory').' '.$header['filename'];
								return false;
							}
						}
					}
					else{
						if (($destination = @fopen($header['filename'], 'wb')) == 0)
						{
							$this->errors[] = __('Cannot write to file').' '.$header['filename'];
							return false;
						}
						else
						{
							$decr = floor($header['size']/512);
							for ($i = 0; $i < $decr; $i++)
							{
								$content = $this->readBlock();
								fwrite($destination, $content, 512);
							}
							if (($header['size'] % 512) != 0)
							{
								$content = $this->readBlock();
								fwrite($destination, $content, ($header['size'] % 512));
							}

							@fclose($destination);

							@touch($header['filename'], $header['time']);
						}

						clearstatcache();
						if (filesize($header['filename']) != $header['size'])
						{
							$this->errors[] = __('Size of file').' '.$header['filename'].' '.__('is incorrect');
							return false;
						}
					}

				if (($file_dir = dirname($header['filename'])) == $header['filename'])
					$file_dir = '';
				if ((substr($header['filename'], 0, 1) == '/') && ($file_dir == ''))
					$file_dir = '/';
		}

		return true;
	}
	
/*
* Check if a directory exists and create it (including parent dirs) if not.
*/

	function dirCheck($dir){
		$parent_dir = dirname($dir);

		if ((@is_dir($dir)) or ($dir == ''))
			return true;

		if (($parent_dir != $dir) and ($parent_dir != '') and (!$this->dirCheck($parent_dir)))
			return false;

		if (!@mkdir($dir, 0777)){
			$this->errors[] = __('Cannot create directory').' '.$dir;
			return false;
		}
		return true;
	}

		function readHeader($binaryData, &$header){
		if (strlen($binaryData)==0){
			$header['filename'] = '';
			return true;
		}

		if (strlen($binaryData) != 512){
			$header['filename'] = '';
			$this->__('Invalid block size').': '.strlen($binaryData);
			return false;
		}

		$checksum = 0;
		for ($i = 0; $i < 148; $i++)
			$checksum+=ord(substr($binaryData, $i, 1));
		for ($i = 148; $i < 156; $i++)
			$checksum += ord(' ');
		for ($i = 156; $i < 512; $i++)
			$checksum+=ord(substr($binaryData, $i, 1));

		$unpack_data = unpack('a100filename/a8mode/a8user_id/a8group_id/a12size/a12time/a8checksum/a1typeflag/a100link/a6magic/a2version/a32uname/a32gname/a8devmajor/a8devminor', $binaryData);

		$header['checksum'] = OctDec(trim($unpack_data['checksum']));
		if ($header['checksum'] != $checksum){
			$header['filename'] = '';

			if (($checksum == 256) && ($header['checksum'] == 0)) 	return true;
			$this->errors[] = __('Error checksum for file ').$unpack_data['filename'];
			return false;
		}

		if (($header['typeflag'] = $unpack_data['typeflag']) == '5')	$header['size'] = 0;
		$header['filename'] = trim($unpack_data['filename']);
		$header['mode'] = OctDec(trim($unpack_data['mode']));
		$header['user_id'] = OctDec(trim($unpack_data['user_id']));
		$header['group_id'] = OctDec(trim($unpack_data['group_id']));
		$header['size'] = OctDec(trim($unpack_data['size']));
		$header['time'] = OctDec(trim($unpack_data['time']));

		return true;
	}

	function writeHeader($filename, $keep_filename){
	
		$packF = 'a100a8a8a8a12A12';
		$packL = 'a1a100a6a2a32a32a8a8a155a12';
		
		if (strlen($keep_filename)<=0)
			$keep_filename = $filename;

      $filename_ready = $this->makeGoodPath($keep_filename);

		if (strlen($filename_ready) > 99){							//write long header

		$dataFirst = pack($packF, '././@LongLink', 0, 0, 0, sprintf('%11s ', DecOct(strlen($filename_ready))), 0);
		$dataLast = pack($packL, 'L', '', '', '', '', '', '', '', '', '');

        //  Calculate the checksum
		$checksum = 0;
        //  First part of the header
		for ($i = 0; $i < 148; $i++)
			$checksum += ord(substr($dataFirst, $i, 1));
        //  Ignore the checksum value and replace it by ' ' (space)
		for ($i = 148; $i < 156; $i++)
			$checksum += ord(' ');
        //  Last part of the header
		for ($i = 156, $j=0; $i < 512; $i++, $j++)
			$checksum += ord(substr($dataLast, $j, 1));
        //  Write the first 148 bytes of the header in the archive
		$this->writeBlock($dataFirst, 148);
        //  Write the calculated checksum
		$checksum = sprintf('%6s ', DecOct($checksum));
		$binaryData = pack('a8', $checksum);
		$this->writeBlock($binaryData, 8);
        //  Write the last 356 bytes of the header in the archive
		$this->writeBlock($dataLast, 356);

		$tmp_filename = $this->makeGoodPath($filename_ready);

		$i = 0;
			while (($buffer = substr($tmp_filename, (($i++)*512), 512)) != ''){
			$binaryData = pack('a512', $buffer);
			$this->writeBlock($binaryData);
			}
		return true;
		}
		$file_info = stat($filename);

		if (@is_dir($filename)){
			$typeflag = '5';
			$size = sprintf('%11s ', DecOct(0));
		} else {
			$typeflag = '';
			clearstatcache();
			$size = sprintf('%11s ', DecOct(filesize($filename)));
		}

		$dataFirst = pack($packF, $filename_ready, sprintf('%6s ', DecOct(fileperms($filename))), sprintf('%6s ', DecOct($file_info[4])), sprintf('%6s ', DecOct($file_info[5])), $size, sprintf('%11s', DecOct(filemtime($filename))));
		
		$dataLast = pack($packL, $typeflag, '', '', '', '', '', '', '', '', '');

		$checksum = 0;
		for ($i = 0; $i < 148; $i++)
			$checksum += ord(substr($dataFirst, $i, 1));
		for ($i = 148; $i < 156; $i++)
			$checksum += ord(' ');
		for ($i = 156, $j = 0; $i < 512; $i++, $j++)
			$checksum += ord(substr($dataLast, $j, 1));

		$this->writeBlock($dataFirst, 148);

		$checksum = sprintf('%6s ', DecOct($checksum));
		$binaryData = pack('a8', $checksum);
		$this->writeBlock($binaryData, 8);

		$this->writeBlock($dataLast, 356);

		return true;
	}

	function openWrite(){
	
		if ($this->isGzipped)
			$this->tmp_file = @gzopen($this->archive_name, 'wb9f');
		else
			$this->tmp_file = @fopen($this->archive_name, 'wb');

		if (!($this->tmp_file)){
			$this->errors[] = __('Cannot write to file').' '.$this->archive_name;
			return false;
		}
		return true;
	}

	function readBlock(){

		if (is_resource($this->tmp_file)){
			if ($this->isGzipped)
				$block = @gzread($this->tmp_file, 512);
			else
				$block = @fread($this->tmp_file, 512);
		} else	$block = '';

		return $block;
	}

	function writeBlock($data, $length = 0){
		if (is_resource($this->tmp_file)){
		
			if ($length === 0){
				if ($this->isGzipped)
					@gzputs($this->tmp_file, $data);
				else
					@fputs($this->tmp_file, $data);
			} else {
				if ($this->isGzipped)
					@gzputs($this->tmp_file, $data, $length);
				else
					@fputs($this->tmp_file, $data, $length);
			}
		}
	}

	function closeTmpFile(){
	
		if (is_resource($this->tmp_file)){
			if ($this->isGzipped)
				@gzclose($this->tmp_file);
			else
				@fclose($this->tmp_file);

			$this->tmp_file = 0;
		}
	}

	function makeGoodPath($path){

		if (strlen($path)>0){
			$path = str_replace('\\', '/', $path);
			$partPath = explode('/', $path);
			$els = count($partPath)-1;
            //  Study directories from last to first
			for ($i = $els; $i>=0; $i--){
                //  Look for current path
				if ($partPath[$i] == '.'){
                    //  Ignore this directory
                    // Should be the first $i=0, but no check is done
                }
				elseif ($partPath[$i] == '..'){
                    //  Ignore it and ignore the $i-1
                    $i--;
                }
				elseif (($partPath[$i] == '') and ($i!=$els) and ($i!=0)){
                    //  Ignore only the double '//' in path,
                    // but not the first and last /
                }	else
					$result = $partPath[$i].($i!=$els ? '/'.$result : '');
			}
		} else $result = '';
		
		return $result;
	}


}//End archiver class

//Loading function
function load_file($requested_url, $loading_file, $settimeout ) {
	global  $user_agent, $requested_size;
	set_time_limit(0);
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
	show_window(__('Server error'), __('Error').' '.$answer_code." - ".$answer_msg.'<br/>'.htmlspecialchars($requested_url));
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
		show_window(__('File error'), __('File').' '.$loading_file.'.log '.__('do not open to write'));
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

		show_window(__('Loader message').': ',__('Start loading').' '.__('from').' '.$host);
	$sockethandle = @fsockopen($host, $port, $error_id, $error_msg, 30);
	if (!$sockethandle)	{
		show_window('<br/>'.__('Loader message').': ', __('Error').' '. ('loading').' ['.$error_id.'] '.$error_msg);
		return false;
	}
	else
	{
		if (!$parsedurl["path"])	$parsedurl["path"] = "/";
		
		show_window('<br/>'.__('Loader message').': ', __('Load query file').'...');
		
		$request = "GET ".$parsedurl["path"].($parsedurl["query"] ? '?'.$parsedurl["query"] : '')." HTTP/1.0\r\n";
		$request .= "Host: $hostname\r\n";
		if ($user_agent != "") $request .= "User-Agent: $user_agent\r\n";

		if ($if_can_doupload && $start_size>0)	$request .= "Range: bytes=".$start_size."-\r\n";

		$request .= "\r\n";

		fwrite($sockethandle, $request);

		$result = "";
		show_window('<br/>'.__('Loader message').': ', __('Initialize').'. ');

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
		show_window(__('Server error'), __('Error').'#: '.$answer_code.' - '.$answer_msg);
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
		show_window(__('File error'), __('File'). __('error size'));
			return false;
		}

		$fh = fopen($loading_file.".tmp", "ab");
		if (!$fh)	{
		show_window(__('File error'), __('File').': '.$loading_file.'.tmp '.__('do not open to write'));
			return false;
		}

		$finish = true;
		$download_size = (double) $start_size;
		show_window('<br/>'.__('Loader message').': ', __('File').' ReloadCMS '.__('loading').'. '.__('Please wait').'...');
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
		show_window(__('File rename error'), __('File').'1: '.$loading_file.'.tmp<br/>'. __('File').'2: '.$loading_file);
				return false;
			}
			@unlink($loading_file.".tmp");
		}
		else
			return 2;

		show_window('<br/>'.__('Loader message').': ', __('File'). ' ' . __('loaded and saved').':<br/>'.__('File').': '.$loading_file.' '. __('Size').': '.$download_size);
		@unlink($loading_file.".log");
		return 1;
	}
	// End loading file
}
//End loading function

//Other service function
function rcms_delete_files($file, $recursive = false) {
	if($recursive && is_dir($file)) {
		$els = rcms_scandir($file, '', '', true);
		foreach ($els as $el) {
			if($el != '.' && $el != '..' && $el != basename(__FILE__)){
				rcms_delete_files($file . '/' . $el, true);
			}
		}
	}
	if(is_dir($file)) {
		if ($file != dirname(__FILE__)) return rmdir($file);
	} else {
		return @unlink($file);
	}
}

function rcms_scandir($directory, $exp = '', $type = 'all', $do_not_filter = false) {
	$dir = $ndir = array();
	if(!empty($exp)){
		$exp = '/^' . str_replace('*', '(.*)', str_replace('.', '\\.', $exp)) . '$/';
	}
	if(!empty($type) && $type !== 'all'){
		$func = 'is_' . $type;
	}
	if(is_dir($directory)){
		$fh = opendir($directory);
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

function show_window($title, $data, $align = 'left'){
echo 	'<table>
		<tr><td><h2>'.$title.'</h2></td></tr>
		<tr><td style="align:'.$align.'">'.$data.'</td></tr>
		</table>';
}
	
function __($string){
	global $lang, $user_lang;
	if ($user_lang=='en') return $string;
	if(!empty($lang[$string])&& is_array($lang)) {
		return $lang[$string];
	} else return $string;
}

function restore($restorefile)		{				//let's rock!
	$archive = new gzip_file  ($restorefile); 
	$archive->extract_files();
	if (empty($archive->error)) return true;
		else return false;
}

//End other service function

//Main process
$title ='';
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>                                                        
    <title>'.__('Loading ReloadCMS').'</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
';
$data = '';

$unpack = '
<form action="" method="POST">
<input type="hidden" name="action" value="unpack"/>
<input type="hidden" name="file" value="'.$tmp_name.'"/>
<input type="submit" value="'.__('Unpack').'" />
</form>';
$random = '';

if (!empty($_POST['clean'])) {
echo __('Clean root directory').'...';
rcms_delete_files(dirname(__FILE__),true);
echo ' '.__('Done').'.';
}
if (!empty($_POST['download'])){
	if (load_file($_POST['download'], $tmp_name, 0)) 
$data .= $unpack;	
else $data .= __('Loading ReloadCMS').': '.__('Error occurred');
	$title .= '--------------------------'.__('End loading').'--------------------------';
} elseif (!empty($_POST['action'])){
if ($_POST['action']=='tosite') {
unlink($_POST['deletefile1']);
unlink($_POST['deletefile2']);
$data .=__('If you have just installed ReloadCMS, please, register a first user who will be a site administrator automatically. ').'<br/>';
$data .='<a href="http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'] . basename($_SERVER['SCRIPT_NAME'])).'/index.php"> '.__('Go to site').' </a>';
} elseif ($_POST['action']=='unpack'){
if (empty($_POST['file'])) return false;
//Unpack file to site
$title .= __('Unpack');	

$Archive = new archiveTar();
$Archive->archive_name = $_POST['file'];
$Archive->restoreArchive('./');
if (empty ($Archive->errors))
	$data  .= __('Unpack done').'.<br/>
<form action="" method="POST">
<input type="hidden" name="action" value="tosite"/>
<input type="hidden" name="deletefile1" value="'.$tmp_name.'"/>
<input type="hidden" name="deletefile2" value="'.basename(__FILE__).'"/>
'.__('Please push button to delete downloader:').'
<input type="submit" value="'.__('Delete').'" />
</form>';
	else $data .= $Archive->showErrors($_POST['file']);
} 
} else {
$title .= __('This script load ReloadCMS to your site');
$data = '
<a href="?lang=ru">Русский язык</a>&nbsp;&nbsp;&nbsp;
<a href="?lang=uk">Українська мова</a>&nbsp;&nbsp;&nbsp;
<a href="?lang=en">English language</a><br/><br/>
	<form action="" method="post">
	<div><b>'.__('Select version').':</b></div>';
	for ($i = 0; $i < count($rcms_release); $i++){
	$data .= '<div style="width:auto; float:left; margin:11px;">
	<input type="radio" name="download" value="'.$rcms_release[$i]['download'].'">
	<img src="'.$rcms_release[$i]['logo'].'" alt="'.$rcms_release[$i]['title'].'" width="200" 
	title="'.$rcms_release[$i]['comments'].'" /><br/>	'
	.$rcms_release[$i]['title'].'&nbsp;&nbsp;&nbsp;<a href="'.$rcms_release[$i]['documentation'].'" 
	title="'.$rcms_release[$i]['comments'].'"target="_blahk">'.__('Information').'</a></div>';
	}
$data .= '  <div style="clear:both"><input type="checkbox" name="clean" />'.__('Clean root directory').'<br/>';
$data .= '  <input type="submit" value="'.__('Start').'!" /></div>';
$data .= '</form>';
}
//Output
show_window($title,$data);
echo '</body></html>';
//End main process
?>
