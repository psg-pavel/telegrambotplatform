<?php


$mtime = microtime();        //Считываем текущее время
$mtime = explode(" ",$mtime);    //Разделяем секунды и миллисекунды
// Составляем одно число из секунд и миллисекунд
// и записываем стартовое время в переменную
$tstart = $mtime[1] + $mtime[0];

include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');
include(DIR_AUTH.'config.php');
$login_querry = 'data_admin';
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
  } 
mysqli_set_charset($conn, 'utf8');
/*---------------------------------------------------------------------------*/

function key_clear($key)
{
    $symbols = array('!', '/', '+', '"');
    for ($i=0; $i<count($symbols); $i++) {
        $key = str_replace($symbols[$i], '', $key);
    }
    $pos = strpos($key, '-');
    if ($pos !== false) {
        $res = substr($key, 0, ($pos - 0));
    } else {
        $res = $key;
    }
    return $res;
}

echo '+ohb"f!nn/jeve +ujove-jnerv'.'<br>';
echo key_clear('+ohbf!nn/jeve +ujove-jnerv').'<br>';


/*---------------------------------------------------------------------------*/

// Делаем все то же самое, чтобы получить текущее время
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$totaltime = ($mtime - $tstart);//Вычисляем разницу
// Выводим не экран
printf ("Страница сгенерирована за %f секунд !", $totaltime);
echo "<br>".__DIR__;

?>