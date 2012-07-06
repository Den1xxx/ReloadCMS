<?
function smiles_disabled()
{
	$arr = parse_ini_file(CONFIG_PATH . 'disable.ini');
	return isset($arr['smiles']);
}

function show_smiles($data)
{
$form = '';
if (!smiles_disabled()) {
	$smile = parse_ini_file(CONFIG_PATH . 'smiles.ini');
	$res = array_keys($smile);
	sort($res);
	foreach ($res as $key) {
		if (isset($smile[$key])){
			$form .= '<img src="'.SMILES_PATH.$key.'" alt = "'.basename($key, ".gif").'" onclick="document.forms[\''.$data[0].'\'].elements[\''.$data[1].'\'].value += \'['.basename($key, ".gif").']\'"/>'."\n";
		}
	}
	if ($form !== '') 
		$form = '<hr/>'.$form.'<hr/>';
}
echo $form;
}
?>