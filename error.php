<?php
//номер ошибки для вывода: если пусто - нафиг!
if (!empty($_GET['id'])) $id=$_GET['id']; else die();
switch ($id) {
case '404': 
    die ('Not found!');
    break;
case '403': 
    die ('Forbidden');
    break;
default: 
   die ('Error occurred');
	break;
}
?>