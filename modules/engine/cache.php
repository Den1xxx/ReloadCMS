<?
// Start output

DEFINE('CACHE_DIR',DATA_PATH.'cache');

CLASS CACHE

{//Begin class

var $enable_cache = true;

//��� ��� �����
var $hash_code='';

//������� ����������� ������
var $dir_cache = CACHE_DIR;

//����� ����� ���� � ��������

var $time_file_cache=600;

//�������� �������� �������� �����

var $CONTENT;

function __construct(){

if ($this ->enable_cache) return true;

else return false;

}

/*

�������� ���, ���� ��� - �������.

*/

function start_cache (){

if ($this ->enable_cache) {
ob_start();
return true;
}
else return false;

}

/*

�������� ���

*/

function show_cache() {

if ($this->is_fresh()) {							//��� ���������� � �� ���������

$this->CONTENT = file_get_contents($this->dir_cache.'/'.$this->hash_code);

return true;

} else return false;


}

/*

��������� ���

*/

function save_cache() {

$this->CONTENT = ob_get_contents();				//���������� ����� � ����������

rcms_delete_files($this->dir_cache."/".$this->hash_code);

file_write_contents($this->dir_cache."/".$this->hash_code, $this->CONTENT);

}

/*

������� ����� ����

*/

function clear_cache() {

ob_end_clean(); 

}

/*

�������� ���� �� ��� � ������ �� ��

*/

function is_fresh() {

$this->hash_code = md5($_SERVER['REQUEST_URI']); 	//��� ��� ������� ��������

if (is_file($this->dir_cache.'/'.$this->hash_code)) {//��� ����

if ((filemtime($this->dir_cache.'/'.$this->hash_code)+ $this->time_file_cache) > time()) //�� ���������

return true;

} else return false;

}



}
 ?>