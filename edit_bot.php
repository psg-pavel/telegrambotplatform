<?php
include_once(__DIR__.'/library.php');
include_once(DIR_INCLUDES.'functions.php');
include_once(DIR_INCLUDES.'functions_bot.php');

include_once(DIR_AUTH.'config.php');
include('auth/check.php');



if (isset($_GET['bot_id'])){
	$bot_id = $_GET['bot_id'];
	if ($bot_id){
	    $mybot = array();
	    $select = mysqli_query($conn, "SELECT * FROM bots bts WHERE bts.bot_id='".$bot_id."'");
	    if ($select){
	    	while ($res = mysqli_fetch_array($select)) { 
	    		$mybot = array(
	    			'bot_id'		 => $res['bot_id'],
	    			'token'			 => $res['token'],
	    			'name'			 => $res['name'],
	    			'first_block_id' => $res['first_block_id'],
	    			'current_block'	 => $res['current_block']
	    		);
	    		break;
	    	} 
	    }
	}

	$types = array();
	$select4 =  mysqli_query($conn, "SELECT * FROM bot_types");
	if ($select4){
    	while ($result5 = mysqli_fetch_array($select4)) { 
    		$types[] = array(
    			'type_id'			=> $result5['type_id'],
    			'type_description'	=> $result5['type_description']
    		);
    	}
    }


	$blocks = array();
    $select3 = mysqli_query($conn, "SELECT * FROM bots bts LEFT JOIN bot_blocks blcks ON (bts.bot_id=blcks.bot_id) LEFT JOIN bot_types tps ON (tps.type_id=blcks.type) WHERE bts.bot_id='".$bot_id."' ORDER BY blcks.block_id ASC");
    if ($select3){
    	while ($result = mysqli_fetch_array($select3)) { 
    		$buts = null;
    		if($result['type'] == 2){
    			$select2 = mysqli_query($conn, "SELECT bmi.button, bmi.button_id, bmi.next_block_id FROM bot_blocks_menu_items bmi LEFT JOIN bot_blocks blcks ON (bmi.block_id=blcks.block_id) WHERE blcks.block_id='".$result['block_id']."'");
    			if ($select2){
    				$buts = array();
    				while ($result2 = mysqli_fetch_array($select2)) { 
    					$buts[] = array(
    						'button'		=> $result2['button'],
    						'button_id'		=> $result2['button_id'],
    						'next_block_id'	=> $result2['next_block_id']
    					);
    				}
    			}
			}

    		$blocks[] = array(
    			'block_id' 			=> $result['block_id'],
	    		'type' 	   			=> $result['type'],
	    		'type_description' 	=> $result['type_description'],
	    		'message'  			=> $result['message'],
	    		'next_block_id'		=> $result['next_block_id'],
	    		'buttons'  			=> $buts
    		);	
    	} 
    }
    $current_block = array();
    $select5 = mysqli_query($conn, "SELECT * FROM bots bts LEFT JOIN bot_blocks blcks ON (bts.current_block=blcks.block_id) LEFT JOIN bot_types tps ON (tps.type_id=blcks.type) WHERE bts.bot_id='".$bot_id."'");
    if ($select5){
    	while ($result = mysqli_fetch_array($select5)) { 
    		$current_block = array(
    			'block_id' 			=> $result['block_id'],
    			'next_block_id'		=> $result['next_block_id'],
	    		'type' 	   			=> $result['type']
    		);
    		break;
    	}
    }
}
/*---------------------константы-----------------------*/
$bot_token = 'https://api.telegram.org/bot'.$mybot['token']; 

define('URL', $bot_token);

$site_dir = dirname(dirname(__FILE__)).'/'; // корень сайта

$data = json_decode(file_get_contents('php://input'), TRUE);

//file_put_contents('message.txt', 'Клиент: '.print_r($data, 1)."\n", FILE_APPEND);

/*---------------------константы-----------------------*/

if ($_POST){
	foreach($blocks as $edit_block){
		$block_name = 'message'.$edit_block['block_id'];
		if ($_POST[$block_name]){
			mysqli_query($conn, "UPDATE `bot_blocks` SET message='".$_POST[$block_name]."' WHERE block_id='".$edit_block['block_id']."'");
			unset($_POST[$block_name]);
		}
		
		$block_name = 'new_next'.$edit_block['block_id'];
		if ($_POST[$block_name]){
			mysqli_query($conn, "UPDATE `bot_blocks` SET next_block_id='".$_POST[$block_name]."' WHERE block_id='".$edit_block['block_id']."'");
			unset($_POST[$block_name]);
		}
		
		if ($edit_block['type']==2){
			foreach($edit_block['buttons'] as $btns){
				$button_name = 'button'.$btns['button_id'];
				if ($_POST[$button_name]){
					mysqli_query($conn, "UPDATE `bot_blocks_menu_items` SET button='".$_POST[$button_name]."' WHERE button_id='".$btns['button_id']."'");
					unset($_POST[$block_name]);
				}

				$button_name = 'buttonnext'.$btns['button_id'];
				if ($_POST[$button_name]){
					mysqli_query($conn, "UPDATE `bot_blocks_menu_items` SET next_block_id='".$_POST[$button_name]."' WHERE button_id='".$btns['button_id']."'");
					unset($_POST[$block_name]);
				}

				$button_name = 'deletebutton'.$btns['button_id'];
				if ($_POST[$button_name]){
					mysqli_query($conn, "DELETE FROM `bot_blocks_menu_items` WHERE button_id='".$btns['button_id']."'");
					unset($_POST[$button_name]);
				}
				

			}
			$block_name = 'add_button'.$edit_block['block_id'];
			if ($_POST[$block_name]){
				mysqli_query($conn, "INSERT INTO `bot_blocks_menu_items` (`button_id`, `block_id`, `button`, `next_block_id`) VALUES (NULL, '".$edit_block['block_id']."', '".$_POST[$block_name]."', '".$_POST['add_buttonnext'.$edit_block['block_id']]."');");
				unset($_POST[$block_name]);
			}
		}

		$block_name = 'delete'.$edit_block['block_id'];
		if ($_POST[$block_name]){
			if ($edit_block['type']==2){
				foreach($edit_block['buttons'] as $btns){
					mysqli_query($conn, "DELETE FROM `bot_blocks_menu_items` WHERE WHERE button_id='".$btns['button_id']."'");
				}
			}
			mysqli_query($conn, "DELETE FROM `bot_blocks` WHERE block_id='".$edit_block['block_id']."'");
			unset($_POST[$block_name]);
		}
		
	}

	if ($_POST['add_new_block'] && !empty($auth_login)){
		mysqli_query($conn, "INSERT INTO `bot_blocks` (`block_id`, `type`, `bot_id`, `message`, `time`, `manager`, `next_block_id`) VALUES (NULL, '".$_POST['add_new_block']."','".$bot_id."', NULL, NULL, NULL, NULL);");
		unset($_POST['add_new_block']);
	
	}

	if ($_POST['updatebot'] && !empty($auth_login)){
		mysqli_query($conn, "UPDATE `bots` SET token='".$_POST['token']."', name='".$_POST['bot_name']."', first_block_id='".$_POST['first_block']."' WHERE bot_id='".$mybot['bot_id']."'");
		unset($_POST['updatebot']);
	}

	if ($_POST['text_mess']){
		$txt = $_POST['text_mess'];
		$arr = array('chat_id' => 0);
		$first_block_id = getFirst($auth_login, $bot_id);
		$mss = message_construct(1, $txt, null, $first_block_id, $arr);
		$chat_ids = getMychats($auth_login, $bot_id);
		foreach ($chat_ids as $chat_id){
			if ($chat_id != ''){
				$res = sendMessage((int)$chat_id, $mss['text'], $mss['reply_markup'], $mss['entities']);
			}
		}
	}

	header('Location: '.DIR_DOMAIN.'edit_bot.php/?bot_id='.$bot_id);
	exit();
}

/*------------------------------------------------------------------------------------------------------------*/

// https://api.telegram.org/bot5555281439:AAG-MT7fmHAc4LyNsRvxYjd11DhiW9MUjHc/getMe




if (!empty($data['message']['text'])) {
	$method = 'sendMessage';
    $chat_id = $data['message']['from']['id'];
    $user_name = $data['message']['from']['username'];
    $first_name = $data['message']['from']['first_name'];
    $last_name = $data['message']['from']['last_name'];
    $text = trim($data['message']['text']);  
    $main = array(
    	'method'     => $method,
    	'chat_id'	 => $chat_id,
    	'user_login' => $user_name,
    	'user_name'  => $first_name.' '.$last_name,
    	'text'		 => $text
    );
    
    // стартовый блок
    if ($text == '/start') {
    	user_to_base($main['chat_id'], '@'.$main['user_login'], $main['user_name'], $mybot['bot_id']);

    	$block = getNetxblock($mybot['first_block_id']);
    	$main['manager'] = getUser($block['message'])['chat_id'];
    	$main['steps_to_send'] = steps_to_send($main['chat_id']).' '.$main['user_name'].' @'.$main['user_login'];

		$mess = message_construct($block['type'], $block['message'], $block['buttons'], $block['next_block_id'], $main);
		$res = sendMessage($main['chat_id'], $mess['text'], $mess['reply_markup'], $mess['entities']);
    	to_base($mybot['bot_id'], $block['block_id']);

    	//file_put_contents('message.txt', 'Старт: '.print_r($res, 1)."\n", FILE_APPEND);

    	// получение данных и переход на следующий блок
	} elseif ($current_block['type'] == 5) {

		if ($data['message']['entities'][0]['type'] == 'phone_number'){
			phone_to_base($main['chat_id'], $main['text']);
		}

		steps_to_base($main['chat_id'], $main['text'], $mybot['bot_id']);
		$block = getNetxblock($current_block['next_block_id']);
		$main['manager'] = getUser($block['message'])['chat_id'];
    	$main['steps_to_send'] = steps_to_send($main['chat_id']).' '.$main['user_name'].' @'.$main['user_login'];

		$mess = message_construct($block['type'], $block['message'], $block['buttons'], $block['next_block_id'], $main);
		$res = sendMessage($mess['chat_id'], $mess['text'], $mess['reply_markup'], $mess['entities']);

		to_base($mybot['bot_id'], $block['block_id']);

		//file_put_contents('message.txt', 'Старт5: '.print_r($res, 1)."\n", FILE_APPEND);
		
		if ($block['type'] == 4){
			$block = getNetxblock($block['next_block_id']);
			$main['manager'] = getUser($block['message'])['chat_id'];
	    	$main['steps_to_send'] = steps_to_send($main['chat_id']).' '.$main['user_name'].' @'.$main['user_login'];

			$mess = message_construct($block['type'], $block['message'], $block['buttons'], $block['next_block_id'], $main);
			$res = sendMessage($mess['chat_id'], $mess['text'], $mess['reply_markup'], $mess['entities']);

			to_base($mybot['bot_id'], $block['block_id']);
		}

		//file_put_contents('message.txt', 'Старт5: '.print_r($res, 1)."\n", FILE_APPEND);

	} 
}

// основной сценарий

if (!empty($data['callback_query']['data'])) {
	$method = 'sendMessage';
	$next_block = $data['callback_query']['data'];
	$chat_id = $data['callback_query']['from']['id'];
	$message_id = $data['callback_query']['message']['message_id'];
	$first_name  = $data['callback_query']['from']['first_name'];
	$last_name = $data['callback_query']['from']['last_name'];
	$text_step  = getStepText($next_block, $data['callback_query']['message']['reply_markup']['inline_keyboard']);
	$user_name = $data['callback_query']['from']['username'];
	$main = array(
    	'method'     => $method,
    	'chat_id'	 => $chat_id,
    	'user_login' => $user_name,
    	'user_name'  => $first_name.' '.$last_name,
    	'text'		 => $next_block
    );
	steps_to_base($main['chat_id'], $text_step, $mybot['bot_id']);

	$block = getNetxblock($next_block);

	$main['manager'] = getUser($block['message'])['chat_id'];
    $main['steps_to_send'] = steps_to_send($main['chat_id']).' '.$main['user_name'].' @'.$main['user_login'];

	if ($block['type'] != 3) { // меню или сообщение //переход к менеджеру

		$mess = message_construct($block['type'], $block['message'], $block['buttons'], $block['next_block_id'], $main);
		$res = sendMessage($mess['chat_id'], $mess['text'], $mess['reply_markup'], $mess['entities']);
    	to_base($mybot['bot_id'], $block['block_id']);

    	//file_put_contents('message.txt', 'Бот: '.print_r($res, 1)."\n", FILE_APPEND);

    } elseif($block['type'] == 3) {  // задержка
    	if ( is_numeric((int)$block['message']) ){
    		sleep((int)$block['message']);
    	}
    	$blck = getNetxblock($block['next_block_id']);
		$mess = message_construct($blck['type'], $blck['message'], $blck['buttons'], $blck['next_block_id'], $main);
		$res = sendMessage($mess['chat_id'], $mess['text'], $mess['reply_markup'], $mess['entities']);
		//file_put_contents('message.txt', 'Бот: '.print_r($res, 1)."\n", FILE_APPEND);
    } 

    if($block['type'] == 4) { //переход к менеджеру

		$blck = getNetxblock($block['next_block_id']);
		$mess = message_construct($blck['type'], $blck['message'], $blck['buttons'], $blck['next_block_id'], $main);
		$res = sendMessage($mess['chat_id'], $mess['text'], $mess['reply_markup'], $mess['entities']);
		//file_put_contents('message.txt', 'После менеджера: '.print_r($res, 1)."\n", FILE_APPEND);
    }
}

/*--------------------------------------------------------------------------------------------------------*/
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
		<? if ( getMybot($auth_login, $mybot['bot_id'])) {?>

		<div class="center" style="margin-bottom: 100px;">
			<? include_once(DIR_INCLUDES.'message.php'); ?>
			<h3>Настройки</h3>
			<?if ($auth_role == '1') {
			    include_once(DIR_SETTING.'tabs1.php'); 
			} else {?>
				<form method="POST">
					<label>Название</label>
					<input  id='time' type="text" name="bot_name" value="<?echo $mybot['name'];?>">
					<label>Токен</label>
					<input  id='time' type="text" name="token" value="<?echo $mybot['token'];?>">
					<label>Первый блок</label>
					<input  id='time' type="text" name="first_block" value="<?echo $mybot['first_block_id'];?>">
					<input  id='time' type="submit" name="updatebot" value="Сохранить"><br><br>
				</form>
					<?foreach ($blocks as $block) {?>
						<?if (!$block['block_id']){?>
							<p>Создайте первый блок</p>
						<?}else{?>
						<div id="<?echo $block['block_id'];?>" style="border:1px solid gray; margin: 5px; padding: 10px; border-radius: 10px; width: 550px;" >
							<form method="POST" style="margin-bottom:-20px;margin-top:-17px;" >
								<p>ID блока: &nbsp; <?echo $block['block_id'];?></p>
								<? if($block['type']==4){ ?>
									<div style="position: relative; left: 430px; top: -17px; ">
										<? if( getManager($block['message'], $mybot['bot_id'])) { ?>
											<div style="color: white; background: green; border-radius: 5px; padding: 5px; width: 90px;">
												Есть связь
											</div>
										<?} else {?>
											<div style="color: white; background: red;  border-radius: 5px; padding: 5px; width: 90px;">
												Нет связи
											</div>
										<?}?>
									</div>
								<?}?>
								<p style="margin-top: -17px;">Тип блока: &nbsp; <?echo $block['type_description'];?></p>
								<label>Текст:</label>

								<!-- смайлики -->
								<div style="cursor: pointer; margin-bottom: -20px; position: relative; margin-left: 294px;" onclick="openSmile('smile<?echo $block['block_id'];?>')">&#128515;</div>
								<div id="smile<?echo $block['block_id'];?>" style="position: absolute; margin-left: 295px; margin-top: -50px; width: 200px; height: 100px; z-index: 20;   border-radius: 15px;  background: white;  display: none;">
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%83', 'txt-<?echo $block['block_id'];?>')">&#128512;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%83', 'txt-<?echo $block['block_id'];?>')">&#128515;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%84', 'txt-<?echo $block['block_id'];?>')">&#128516;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%81', 'txt-<?echo $block['block_id'];?>')">&#128513;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%86', 'txt-<?echo $block['block_id'];?>')">&#128518;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%85', 'txt-<?echo $block['block_id'];?>')">&#128517;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%82', 'txt-<?echo $block['block_id'];?>')">&#128514;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%83', 'txt-<?echo $block['block_id'];?>')">&#128578;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%91%87', 'txt-<?echo $block['block_id'];?>')">&#128071;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%89', 'txt-<?echo $block['block_id'];?>')">&#128521;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%8A', 'txt-<?echo $block['block_id'];?>')">&#128522;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%8D', 'txt-<?echo $block['block_id'];?>')">&#128525;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%8B', 'txt-<?echo $block['block_id'];?>')">&#128523;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%9C', 'txt-<?echo $block['block_id'];?>')">&#128540;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%98%B5', 'txt-<?echo $block['block_id'];?>')">&#129322;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%92%B5', 'txt-<?echo $block['block_id'];?>')">&#128181;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%91%8B', 'txt-<?echo $block['block_id'];?>')">&#128075;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%E2%9C%8B', 'txt-<?echo $block['block_id'];?>')">&#128400;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%91%8D', 'txt-<?echo $block['block_id'];?>')">&#128077;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%99%8F', 'txt-<?echo $block['block_id'];?>')">&#128591;</div>
									<div style="float: left; margin: 5px; cursor: pointer;" onclick="putsmile('%F0%9F%91%8C', 'txt-<?echo $block['block_id'];?>')">&#129309;</div>
								</div>
								<!-- END смайлики -->

								<textarea  id="txt-<?echo $block['block_id'];?>" class="time" type="text" name="message<?echo $block['block_id'];?>" value="" style="width:50%;height:60px;"  onclick="closesmile('smile<?echo $block['block_id'];?>')"><?echo $block['message'];?></textarea>
								<?if ($block['type']==2){?>
									<p>Кнопки:</p>
									<?foreach ($block['buttons'] as $button) {?>
										<label>Текст:</label>
										<input  id='time' type="text" name="button<?echo $button['button_id'];?>" value="" placeholder="<?echo $button['button'];?>">
										<label>-></label>
										<input  id='time' type="text" name="buttonnext<?echo $button['button_id'];?>" value="" placeholder="<?echo $button['next_block_id'];?>">
										<input  id='time' type="submit" name="deletebutton<?echo $button['button_id'];?>" value="X"><br>
									<?}?>
									<br><label>Добавить кнопку:</label>
										<input  id='time' type="text" name="add_button<?echo $block['block_id'];?>" value="" placeholder="Название кнопки">
										<label>-></label>
										<input  id='time' type="text" name="add_buttonnext<?echo $block['block_id'];?>" value="" placeholder="Следующий блок"><br>
								<?}else{?>
									<label>-></label>
									<input  id='time' type="text" name="new_next<?echo $block['block_id'];?>" value="" placeholder="<?echo $block['next_block_id'];?>"><br>
								<?}?>
								<br><input id='time' type="submit" name="" value="Сохранить">
							</form>
							<form method="POST" style="margin-left:430px; margin-top:-15px; margin-bottom:-5px;">
								<input  id='time' type="submit" name="delete<?echo $block['block_id'];?>" value="Удалить блок" >
							</form>
						</div>
					<?}}?>
					<form method="POST">
						<div style="border:1px solid gray; margin: 5px; padding: 5px; border-radius: 10px; width: 550px;">
							<label>Добавить блок:</label>
							<select name="add_new_block">
								<?foreach ($types as $type) {?>
									<option value="<?echo $type['type_id'];?>"><?echo $type['type_description'];?></option>
								<?}?>
							</select>
							<input  id='time' type="submit" name="" value="Добавить">
						</div>
					</form>
			<? } ?>
		</div>
		<? } ?>
		<? include_once(DIR_INCLUDES.'footer.php'); ?>

	<script type="text/javascript">
		function addBot(){
			document.getElementById('add_bot').hidden = false;
		}

		function openSmile(smileid){
			document.getElementById(smileid).style.display = 'block';
		}
		function closesmile(smileid){
			document.getElementById(smileid).style.display = 'none';
		}

		function insertText(txtid, code){
			var txtarea = document.getElementById(txtid);
			//ищем первое положение выделенного символа
			var start = txtarea.selectionStart;
			//ищем последнее положение выделенного символа
			var end = txtarea.selectionEnd;
			// текст до + вставка + текст после (если этот код не работает, значит у вас несколько id)
			var finText = txtarea.value.substring(0, start) + code + txtarea.value.substring(end);
			// подмена значения
			txtarea.value = finText;
			// возвращаем фокус на элемент
			txtarea.focus();
			// возвращаем курсор на место - учитываем выделили ли текст или просто курсор поставили
			txtarea.selectionEnd = ( start == end )? (end + code.length) : end ;
		}

		function putsmile(code, txtid){
			insertText(txtid, code);
		}


	</script>
	</body>
</html>