<?php
require "vendor/autoload.php";

$api = new veapon\NeteaseMusic();
//$album = $api->album(2515029);

//echo '<pre>';
//print_r($album);
//

$result = $api->search('Trouble', veapon\NeteaseMusic::TYPE_ALBUM);
echo '<pre>';
print_r($result);
