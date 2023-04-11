<?php

include_once(__DIR__.'/library.php');
include_once(DIR_INCLUDES.'functions.php');
include_once(DIR_INCLUDES.'functions_bot.php');
include_once(DIR_AUTH.'config.php');
include('auth/check.php');

if (isset($_GET['bot_id'])){
	$bot_id = $_GET['bot_id'];
	if ($bot_id){
	    $mydata = array();
	    $select = mysqli_query($conn, "SELECT * FROM bot_users_data bud LEFT JOIN bot_users busr ON (bud.chat_id=busr.chat_id AND bud.bot_id=busr.bot_id) WHERE bud.bot_id='".$bot_id."' ORDER BY bud.data_id DESC");
	    if ($select){
	    	while ($res = mysqli_fetch_array($select)) { 
	    		$mydata[] = array(
	    			'date'		 	 => $res['date'],
	    			'user_name'		 => $res['user_name'],
	    			'user_login'	 => $res['user_login'],
	    			'phone'			 => $res['phone'],
	    			'steps'			 => $res['steps']
	    		);
	    	} 
	    }
	}
}

?>
<html>
	<head> 
		<meta charset="utf-8" content="text/html" />
		<title> Simple Stat </title>
		<link rel="stylesheet" type="text/css" href="/styles.css" />
	</head>

	<body>
		<? include_once(DIR_INCLUDES.'header.php'); ?>
		<? include_once(DIR_INCLUDES.'menu.php'); ?>
		<? include_once(DIR_INCLUDES.'support.php'); ?>
		<? if ( getMybot($auth_login, $bot_id)) {?>
		<div class="center" style="padding-bottom: 100px;">
			<h3>Посетители:</h3>
			<div style="background: white; padding: 5px; margin: 3px; height: 25px;">
				<div style="width: 15%; margin-top: 5px; float: left;">Дата</div>
				<div style="width: 15%; margin-top: 5px; float: left;">Логин в телеграм</div>
				<div style="width: 15%; margin-top: 5px; float: left;">Имя</div>
				<div style="width: 15%; margin-top: 5px; float: left;">Телефон</div>
				<div style="width: 40%; margin-top: 5px; float: left;">Путь посещения</div>
			</div>
			<?if ($auth_role == '1') {
			    include_once(DIR_SETTING.'tabs1.php'); 
			} else {
				foreach ($mydata as $string){
					if ($string['user_login'] != '@Garant18'  && $string['user_login'] != '@garant18' && $string['user_login'] != '@pavel_myalik'){?>
					<div style="background: white; padding: 5px; margin: 3px; height: 60px;">
						<div style="width: 15%; margin-top: 20px; float: left;"><?echo $string['date'];?>&nbsp;</div>
						<div style="width: 15%; margin-top: 20px; float: left;"><?echo $string['user_login'];?>&nbsp;</div>
						<div style="width: 15%; margin-top: 20px; float: left;"><?echo $string['user_name'];?>&nbsp;</div>
						<div style="width: 15%; margin-top: 20px; float: left;"><?echo $string['phone'];?>&nbsp;</div>
						<div style="width: 40%; float: left; overflow: auto; height: 60px;"><?echo $string['steps'];?>&nbsp;</div>
					</div>
			<?		} 
				}
			}   
			?>
		</div>
		<? } ?>
		<? include_once(DIR_INCLUDES.'footer.php'); ?>
	</body>
</html>