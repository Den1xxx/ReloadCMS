<?
// Start output

DEFINE('CACHE_DIR',DATA_PATH.'cache');

CLASS CACHE

{//Begin class

var $enable_cache = true;

//Хэш код файла
var $hash_code='';

//Каталог кэширования файлов
var $dir_cache = CACHE_DIR;

//Время жизни кеша в секундах

var $time_file_cache=600;

//Хранение значения контента файла

var $CONTENT;

function __construct(){

if ($this ->enable_cache) return true;

else return false;

}

/*

Показать кеш, если нет - создать.

*/

function start_cache (){

if ($this ->enable_cache) {
ob_start();
return true;
}
else return false;

}

/*

Показать кеш

*/

function show_cache() {

if ($this->is_fresh()) {							//Кеш существует и не просрочен

$this->CONTENT = file_get_contents($this->dir_cache.'/'.$this->hash_code);

return true;

} else return false;


}

/*

Сохранить кеш

*/

function save_cache() {

$this->CONTENT = ob_get_contents();				//Записываем буфер в переменную

rcms_delete_files($this->dir_cache."/".$this->hash_code);

file_write_contents($this->dir_cache."/".$this->hash_code, $this->CONTENT);

}

/*

Очищаем буфер кеша

*/

function clear_cache() {

ob_end_clean(); 

}

/*

Проверка есть ли кеш и свежий ли он

*/

function is_fresh() {

$this->hash_code = md5($_SERVER['REQUEST_URI']); 	//Хэш код текущей страницы

if (is_file($this->dir_cache.'/'.$this->hash_code)) {//кеш есть

if ((filemtime($this->dir_cache.'/'.$this->hash_code)+ $this->time_file_cache) > time()) //не просрочен

return true;

} else return false;

}



}
 ?>