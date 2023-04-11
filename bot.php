<?php
include_once(__DIR__.'/library.php');
include_once(DIR_INCLUDES.'functions.php');
include_once(DIR_AUTH.'config.php');
include('auth/check.php');

if ($_POST){
	if ($_POST['new_bot'] && !empty($auth_login)){
		mysqli_query($conn, "INSERT INTO `bots` (`bot_id`, `login`, `token`, `name`, `first_block_id`) VALUES (NULL, '".$auth_login."', '".$_POST['token']."', '".$_POST['bot_name']."', NULL);");
	header('Location: ' . DIR_DOMAIN . 'redir.php/?back=bot');
	}
}

/* получаем то, что есть */


if ($auth_login != null){
    $bots = array();
    $select = mysqli_query($conn, "SELECT * FROM bots bts WHERE bts.login='".$auth_login."' ORDER BY bts.bot_id ASC");
    if ($select){
    	while ($result = mysqli_fetch_array($select)) { 
    		$bots[] = array(
    			'bot_id'	=> $result['bot_id'],
    			'token'		=> $result['token'],
    			'name'		=> $result['name']
    		);
    	} 
    }
}
error_reporting(0);
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
		<? if ($auth_login) {?>
		<div class="center">
			<h3>Мои чат-боты:</h3>
			<?if ($auth_role == '1') {
			    include_once(DIR_SETTING.'tabs1.php'); 
			} else {
				foreach ($bots as $bot){?>
					<div style="background: white; padding: 5px; margin: 3px;">
						<div style="min-width: 150px; float: left;"><?echo $bot['name'];?>&nbsp;</div>
						<div style="min-width: 150px; float: left;">
							<a href='<?echo DIR_DOMAIN;?>edit_bot.php/?bot_id=<?echo $bot['bot_id'];?>'>&nbsp;
							Редактировать</a>
						</div>
						<div style="min-width: 150px; float: left;">
							<a href='<?echo DIR_DOMAIN;?>stat_bot.php/?bot_id=<?echo $bot['bot_id'];?>'>&nbsp;
							Посетители бота</a>
						</div>
						
							<?
							$web_hook = file_get_contents("https://api.telegram.org/bot". $bot['token']."/setWebhook?url=".DIR_DOMAIN."edit_bot.php/?bot_id=".$bot['bot_id']);
							if(json_decode($web_hook)->ok == 1){?>
								<div style="min-width: 150px; color: green; ">Токен проверен</div>
							<?} else {?>
								<div style="min-width: 150px; color: red;">Введите верный токен</div>
							<?}?>
						
					</div>
			<?	}
			}   
			?>
			<div>
				<p style="cursor:pointer;" onclick="addBot()">+Добавить</p>
				<div id="add_bot"  hidden>
					<form method="POST">
						<input type="text" name="new_bot" value="1" hidden>
						<input  id='time' type="text" name="bot_name" placeholder="Название бота"  value="" required>
						<input  id='time' type="text" name="token" placeholder="Токен бота"  value="" required>
						<input id='time' type="submit" name="Добавить">
					</form>
				</div>
			</div>
		</div>
		<?}?>
		<? include_once(DIR_INCLUDES.'footer.php'); ?>

	<script type="text/javascript">
		function addBot(){
			document.getElementById('add_bot').hidden = false;
		}

	</script>
	</body>
</html>