<?
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

class archiveTar
{
	var $archive_name = '';
	var $tmp_file = 0;
	var $file_pos = 0;
	var $isGzipped = true;
	var $errors = array();
	var $files = array();
	
	function __construct(){
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

			if (!rename($this->archive_name, $this->archive_name.'.tmp')){
				$this->errors[] = __('Cannot rename').' '.$this->archive_name.__(' to ').$this->archive_name.'.tmp';
				return false;
			}

			$tmpArchive = gzopen($this->archive_name.'.tmp', 'rb');
			if (!$tmpArchive){
				$this->errors[] = $this->archive_name.'.tmp '.__('is not readable');
				rename($this->archive_name.'.tmp', $this->archive_name);
				return false;
			}

			if (!$this->openWrite()){
				rename($this->archive_name.'.tmp', $this->archive_name);
				return false;
			}

			$buffer = gzread($tmpArchive, 512);
			if (!gzeof($tmpArchive)){
				do {
					$binaryData = pack('a512', $buffer);
					$this->writeBlock($binaryData);
					$buffer = gzread($tmpArchive, 512);
				}
				while (!gzeof($tmpArchive));
			}
			gzclose($tmpArchive);
			unlink($this->archive_name.'.tmp');
		} else {
		$this->tmp_file = fopen($this->archive_name, 'r+b');
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
		unlink($this->archive_name);
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
			if (file_exists($fileName)){
				if ($fp = fopen($fileName, 'rb')){
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
			$this->tmp_file = gzopen($fileName, 'rb');
		else
			$this->tmp_file = fopen($fileName, 'rb');

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
			if (($file = fopen($filename, 'rb')) == 0){
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

			if (is_dir($filename)){
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
					if ((is_dir($header['filename'])) && ($header['typeflag'] == '')){
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
						if (!file_exists($header['filename']))		{
							if (!mkdir($header['filename'], 0777))	{
								
								$this->errors[] = __('Cannot create directory').' '.$header['filename'];
								return false;
							} 
						}
					} else {
						if (($destination = fopen($header['filename'], 'wb')) == 0)
						{
							$this->errors[] = __('Cannot write to file').' '.$header['filename'];
							return false;
						} else {
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

							fclose($destination);

							touch($header['filename'], $header['time']);
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
				$this->dirs[] = $file_dir;
				$this->files[] = $header['filename'];
				
		}

		return true;
	}
	
/*
* Check if a directory exists and create it (including parent dirs) if not.
*/

	function dirCheck($dir){
		$parent_dir = dirname($dir);

		if ((is_dir($dir)) or ($dir == ''))
			return true;

		if (($parent_dir != $dir) and ($parent_dir != '') and (!$this->dirCheck($parent_dir)))
			return false;

		if (!mkdir($dir, 0777)){
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

		$dataFirst = pack($packF, '././LongLink', 0, 0, 0, sprintf('%11s ', DecOct(strlen($filename_ready))), 0);
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

		if (is_dir($filename)){
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
			$this->tmp_file = gzopen($this->archive_name, 'wb9f');
		else
			$this->tmp_file = fopen($this->archive_name, 'wb');

		if (!($this->tmp_file)){
			$this->errors[] = __('Cannot write to file').' '.$this->archive_name;
			return false;
		}
		return true;
	}

	function readBlock(){

		if (is_resource($this->tmp_file)){
			if ($this->isGzipped)
				$block = gzread($this->tmp_file, 512);
			else
				$block = fread($this->tmp_file, 512);
		} else	$block = '';

		return $block;
	}

	function writeBlock($data, $length = 0){
		if (is_resource($this->tmp_file)){
		
			if ($length === 0){
				if ($this->isGzipped)
					gzputs($this->tmp_file, $data);
				else
					fputs($this->tmp_file, $data);
			} else {
				if ($this->isGzipped)
					gzputs($this->tmp_file, $data, $length);
				else
					fputs($this->tmp_file, $data, $length);
			}
		}
	}

	function closeTmpFile(){
	
		if (is_resource($this->tmp_file)){
			if ($this->isGzipped)
				gzclose($this->tmp_file);
			else
				fclose($this->tmp_file);

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


}
?>
