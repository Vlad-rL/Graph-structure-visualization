<?php
mb_internal_encoding("UTF-8");
$firstObj = 0;
$isAdmin=false;
$allObjects = array();
// --------------------------------------------
function fill(&$allObjects,$val,$ext=true)
	{// 3
	 global $link;
	  if ($ext)
	  { // 4
	  $query = "INSERT INTO `objects` (`number`,
	`parent`,`brothUp`,`brothDn`,`fson`,`switch`,`title`,`descript`) VALUES (".$val['number']." ,".$val['parent'].",".$val['brothUp'].",".$val['brothDn'].",".$val['fson'].",".$val['switch'].",'".$val['title']."','".$val['descript']."')";
  	  $res=mysqli_query($link,$query);
	  } 
	  else $res=true;
  	  if ($res)
	  {// 4
	  $number = $val['number'];
	  $allObjects[$number] = $val;
	  }
	else print_r('Error = '.mysqli_error($link));
	}; // 3
// --------------------------------------------
function linkBase()
 {
 global $repl, $link;
 if (!$link)
	{include_once 'defbase.php';
	$link = mysqli_connect(host, user, password, db_name);
	if ( !$link )
	 { 
	 if ( mysqli_connect_errno($link) )
		{
		$repl .= " Error: unsuccesful attempt to connect to base:".mysqli_connect_error($link);
		}
	 return false;
	 }
	}
 return true;
 }
	function TandConv($str)
	{if (!$code=mb_detect_encoding($str, ['windows-1251', 'UTF-8'], true))
		if ($code!='UTF-8')
			return iconv('CP1251','UTF-8',$str);
 return $str;
	}
// -----------------------------------------------------------
// ------------------------ begin --------------------
$password = 'admin';
$hash = password_hash($password, PASSWORD_BCRYPT);
$repl = '';
$regRepl = '';
$link;
$_SESSION['user']=TandConv('гость');
//======================= onScreen =========================

function onScreen()
 {
global $isAdmin,$repl,$regRepl,$link;
// ================= общая часть ======================
$scrObjects = array();
$link = mysqli_connect(host, user, password, db_name);
if ( !$link )
 { 
 if ( mysqli_connect_errno($link) )
	{
	$repl .= " Error: unsuccesful attempt to connect to base:".mysqli_connect_error($link);
	}
 return false;
 }
else
 {
  $query = "SELECT * FROM  `objects`";
  $res=mysqli_query($link,$query);
  if ($res)
	{// 3 
	while($val=mysqli_fetch_assoc ($res))
	  {// 4
	   $number = strval($val['number']);
	   foreach($val as $key => $v)
		{
		if ($key == 'title' || $key == 'descript')
			$val[$key] = TandConv($v);
		}
	   $scrObjects[$number]=$val;
	  }
	mysqli_free_result($res);
	mysqli_close($link);
	$allObjectsJson=json_encode($scrObjects,JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);
  	echo '<script  type="text/javascript">
  	allObjectsJson = '.$allObjectsJson.'
  	</script>';
	}
    else $repl .= TandConv("Структура недоступна");
  }  

if ($isAdmin)
	{
// ========== admin brench ==========================
	include 'visualForAdmin.php';
	echo '
<div id="service">
	<div id="infbox"></div>
	<div id="nick" onclick="onoffliswin()" onmouseover= "infreport (event,\''.TandConv('Ваш статус на сайте').'\')" ></div>
<!-- выход из регистрации -->
	  <form action="" method="post" autocomplete="off" novalidate>
	  <input id="acc_exit" name="acc_exit" type="image" src= "exit.gif" onmouseover= "infreport (event,\''.TandConv("выход из аккаунта").'\')" alt="'.TandConv("Выход").'"/>
	  </form>
 	<div id= "regwinRes" onclick="offwin(\'regwinRes\')"></div>
	</div id="service">';
	echo "
	<div id='diagr'>
	</div id='diagr'>
	<div id='editWin'>
	<form action='' method='POST' name='form' autocomplete='off' >
	<div class='line'>
	<label class='star'>".TandConv('Редактирование объекта')." </label>
	<input id='numberEd' name='numb' type='text' value=''  readonly>
	</div class='line'>
	<input id='delObj' name='delObj' type='submit' value='".TandConv('Удалить объект')."' >
	<div class='line'>
        <input id='titleEd' name='titl' type='text' value=''  placeholder='".TandConv('Заголовок')."' >
	   <input id='send-titl' name='send-titl' type='image' src= 'go20x30.gif' alt='".TandConv('Отправить строку')."'>
	</div class='line'>
	<div class='line'>
        <input id='descrEd' name='desc' type='textarea' value='' placeholder='".TandConv('Описание')."' >
	   <input id='send-desc' name='send-desc' type='image' src= 'go20x30.gif' alt='".TandConv('Отправить строку')."'>
	</div class='line'>
	<div class='line'>
        <input id='parenEd' name='par' type='text' value=''  placeholder='".TandConv('Новый родитель - номер')."' >
	   <input id='send-par' name='send-par' type='image' src= 'go20x30.gif' alt='".TandConv('Отправить номер')."'>
	</div class='line'>
	<div class='line'>
        <label class = 'star'>".TandConv('Создать нового потомка')."</label>
	   <input id='send-son' name='send-son' type='image' src= 'go20x30.gif' alt='".TandConv('Отправить')."'>
	</div class='line'>
	</form>
</div id='editWin'>";
	}
else
	{
// ========== client brench ==========================
	include 'visualForClient.php';
	echo "
	<div id='service'>
	<div id='infbox'></div>
	<div id='registr' onclick='onoffwin(\"regwin\")'>
	<img src='keyHole.gif' onmouseover= 'infreport (event,\"".TandConv("Регистрация (необязательно)")."\")' alt='".TandConv("вход")."'></div>
	<div id='nick' onclick='onoffliswin()' onmouseover= 'infreport (event,\"".TandConv("Ваш статус на сайте")."
\")' ></div>
<!-- вход и регистрация -->
	<div id='regwin'>
	  <form class='row' action='' method='post' autocomplete='off' novalidate>
    	  <label>".TandConv('Ваш лoгин (латиница)')."(<span class='star'>*</span>):<br></label>
	  <div><input name='logos' type='text' size='16' maxlength='32'  autocomplete='nope'></div>
	<div class='password'>
	  <label>".TandConv('Ваш пароль (латиница)')."(<span class='star'>*</span>):<br></label>
	  <div class='line'><input id='password-input1' name='password' type='text' autocomplete='new-password' size='20' maxlength='32' >
	  <div class='password-control' onclick='return show_hide_pass(this,\"password-input1\");'></div>
	</div></div>
	  <br><br>
	  <label>".TandConv('Зарегистрироваться:')."</label>
	  <br>
	  <div><input type='submit' name='enter' value='".TandConv('Enter')."'></div>
	  <p class='line'><span class='star'>*</span><span>".TandConv('—
 обязательно для заполнения.')."</span></p>
</form>
	</div id='regwin'>
	<script type='text/javascript'>
	window.addEventListener('DOMContentLoaded', function ()
 	{ 
	bodyEl = document.querySelector('body');
 	newEl=document.createElement('div');
	newEl.id = 'infboxV';
	shrEl=document.createElement('div');
	shrEl.id = 'toshrink';
	shrSp=document.createElement('span');
	newEl.append(shrEl);
	newEl.append(shrSp);
	bodyEl.append(newEl);
	infboxVId = document.getElementById('infboxV');
  	infboxVId.addEventListener('click',function () 
	 {
	 event.stopPropagation();
	 infboxVId.style.display = 'none';
	 });
 	});
   	</script>";
	echo "
 	<div id= 'regwinRes' onclick='offwin(\"regwinRes\")'></div>
	</div id='service'>
	";	
  	 if ($regRepl)
		{ // 5
		 $time=1000*(int)(strlen($repl)/100)+5000;
		 echo '<script  type="text/javascript">window.addEventListener("DOMContentLoaded", function ()
 {
		document.getElementById("regwinRes").style.display = "block";
		document.getElementById("regwinRes").insertAdjacentHTML("afterbegin","'.$regRepl.'"); 
		 function off()
			{ 
			document.getElementById("regwinRes").style.display = "none";
			}
		 setTimeout(off,'.$time.');
});
		 </script>';
		 } // 5
	echo "<div id='diagr'>
	</div id='diagr'>";
	}
// ================= общая часть ======================
echo '<script  type="text/javascript">
window.addEventListener("DOMContentLoaded", function ()
 {
 document.getElementById("nick").insertAdjacentHTML("afterbegin","'.$_SESSION["user"].'");
 document.getElementById("infbox").insertAdjacentHTML("afterbegin","'.$repl.'")
});
window.addEventListener("unload", function() {
	var data = "numb=&switch=&send-son_x=&send-par_x=";
  	var xhr = new XMLHttpRequest();
  	xhr.open("POST", "" , true);
	xhr.send(data);
});
</script>';
unset($_POST);
return;
}
//=======================onScreen end==================

if (!linkBase()) {onScreen(); exit();}
//  Удалось подключиться
$query ="CREATE TABLE IF NOT EXISTS `adminData` (
    	`adminLog` VARCHAR(32) NOT NULL DEFAULT 'admin', 
	`pass` VARCHAR(128) NOT NULL DEFAULT '',
	`sessionId` VARCHAR(32) DEFAULT '',
	`maxId` INT DEFAULT 0,
	`startId` INT DEFAULT 0
    )";
if (!mysqli_query ($link,$query)) 
	{  // доступа в базе нет 
	$repl .= ' Dialogue base is unsucceedable. '.mysqli_error($link);
	}
$query = "SELECT * FROM  `adminData`";
$res=mysqli_query($link,$query);
if (!($res && mysqli_num_rows($res) != 0))
	{//
      $query = "INSERT INTO `adminData` VALUES ('admin','".$hash."','', 0, 0)";
	 $res=mysqli_query($link,$query);
	 }
$query ="CREATE TABLE IF NOT EXISTS `objects` (
	`number` INT DEFAULT 0,
	`parent` INT DEFAULT -1,
	`brothUp` INT DEFAULT -1,
	`brothDn` INT DEFAULT -1,
	`fson` INT DEFAULT -1,
	`switch` INT DEFAULT 0,
 	`title` VARCHAR(32) NOT NULL DEFAULT '',
 	`descript` VARCHAR(240) NOT NULL DEFAULT ''
      )";
if (!mysqli_query($link,$query)) 
	{  // доступа в базе нет 
	$repl .= ' Objects is unsucceedable';
	}
 // 3
  if (session_id())
	{
  	$query = "SELECT * FROM  `adminData` WHERE `sessionId`='".session_id()."'";
  	$res=mysqli_query($link,$query);
  	if ($res && mysqli_num_rows($res) != 0)
		{// 4
		$_SESSION['user']='admin';
		$isAdmin=true;			
		}
	}
  $query = "SELECT * FROM  `objects`";
  $res=mysqli_query($link,$query);
  if ($res && mysqli_num_rows($res) != 0)
	{// 4
	while($val=mysqli_fetch_assoc ($res))
	 {// 5
	  fill($allObjects,$val,false);
	 }
	mysqli_free_result($res);
	} 
	 else 
	  { // 4
	  $tempAr = array('number'=>0, 'parent'=>-1, 'brothUp'=>-1, 'brothDn'=>-1, 'fson'=>-1, 'switch'=>0, 'title'=>'title0', 'descript'=>'description0');
	  fill($allObjects,$tempAr,true);		
	  } // 4
mysqli_close($link);

//================== testreg =============================
// ----------------------------------------------------
function uniquePost($posted1)
{ // 2
// take some form values
$description = $posted1;
// check if session hash matches current form hash
if (isset($_SESSION['form_hash']) && $_SESSION['form_hash'] == md5($description) )
	{ // 3
	// form was re-submitted return false
	return false;
	}
// set the session value to prevent re-submit
$_SESSION['form_hash'] = md5($description);
return true;
}
// --------------------------------------------------------
// проверка регистрации пользователя

if (!$_POST) {onScreen(); exit();}
include_once 'defbase.php';
$link = mysqli_connect(host, user, password, db_name);
if (!linkBase()) {onScreen(); exit();}

//  Удалось подключиться
// Ветвление на запросы регистрации и запросы изменения структуры

if (isset($_POST['logos']) || isset($_POST['password']) || isset($_POST['acc_exit_x']) ) 
 {
 if (!$isAdmin)
  {
//заносим введенный пользователем логин в переменную $login
   if (isset($_POST['logos'])) 
		{
	 	$login = $_POST['logos']; 
	  	if ($login == '') 
			unset($login);
		} // 3
//заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
   if (isset($_POST['password'])) 
		{
	 	$password=$_POST['password']; 
       	if ($password =='') 
			unset($password);
		} // 3
   if (empty($login) or empty($password)) 
//если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
    	{ // 3
    	$regRepl .= TandConv("Вы ввели не всю информацию, заполните все поля!");
		mysqli_close($link);
		onScreen(); exit();
    	}
   	else
    //если логин и пароль введены,то обрабатываем их
	{	// 3
	 $login = stripslashes($login);
	 $login = htmlspecialchars($login);
	 $password = trim($password);
	//удаляем лишние пробелы
	 $login = trim($login);
	 $password = trim($password);
	 $query ="SELECT * FROM `adminData`";
	 $result = mysqli_query($link,$query); 
//извлекаем из базы все данные о пользователе с введенным логином
	 if (!$result)
		$regRepl .= TandConv(" Данные базы недоступны, "). mysqli_connect_error($link);
	  else
	  {	// 5
	  $usersrow = mysqli_fetch_array($result);
	  mysqli_free_result($result);
	  $hash_pass=trim($usersrow['pass']);
	  if ($usersrow["adminLog"] != $login || !password_verify($password, $hash_pass))
    	   	{	// 6
	    	$regRepl .= TandConv("Извините, Вы не admin или логин/пароль неверный. На сайте У Вас права Гостя");
			$_SESSION['user']="гость";
	   		}	// 6
	  	else { // 6 последние проверки на звание admin
			//если логин и пароль совпадают
			if ($usersrow['sessionId'] != session_id())
			  { // 7
			   $query = "UPDATE `adminData` SET `sessionId`='".session_id()."'";
			   $res=mysqli_query($link,$query);
			   mysqli_close($link);
		       $_SESSION['user']=$usersrow['adminLog'];
		    	$regRepl .= TandConv("Вы успешно вошли на сайт как ").$_SESSION['user']."! ";
			   $isAdmin = true;
			  } // 7
			 } // 6
		} // 5
	} // 3
	$_POST['logos'] = '';
	$_POST['password'] = '';
	onScreen(); 
	exit();
	}
// -------- is admin else -------------------
  else
	{
 	if (isset($_POST['acc_exit_x'])) 
		{
	 	$query ="UPDATE `adminData` SET `sessionId`=''";
	 	$result = mysqli_query($link,$query); 
	 	mysqli_close($link);
		$isAdmin = false;
		$_SESSION['user']='гость';
		} 
	onScreen(); 
	exit();
	}
 }
 else 
 {
//========================= createObj ======================
 // ------------------------------------------------------
if (!function_exists('tabDel')) 	 
{ // 1
 function tabDel($delEl)
	{ // 3
	 global $link;
	 $query ="DELETE FROM `objects` WHERE `number`=$delEl";
  	 return mysqli_query($link,$query);
	}
}
if (!function_exists('tabUpd')) 	 
{ // 1
 function tabUpd($name,$updEl,$where,&$allObjects,$digit=true)
	{ // 3
	 global $link;
	 $allObjects[$where][$name]=$updEl;
	 $val=($digit==true)? $updEl:"'".$updEl."'";
	 $query ="UPDATE `objects` SET $name=".$val." WHERE `number`=$where";
  	 return mysqli_query($link,$query);
	}
}
// изменение контекста удаляемого элемента
 function changeContext($curEl,&$allObjects)
	{  // 3
	 $sel = 0;
	 $valSon = $allObjects[$curEl]['fson'];
	 $valBrotD = $allObjects[$curEl]['brothDn'];
	 $valBrotU = $allObjects[$curEl]['brothUp'];
	 $valParent = $allObjects[$curEl]['parent'];
	 if ($valBrotD == -1)
		 $sel++;
	 if ($valBrotU == -1) 
		 $sel+=2;
	 switch ($sel)
		 { // 4
		  case 0:{ // 5
			 tabUpd('brothDn',$valBrotD,$valBrotU, $allObjects);
			 tabUpd('brothUp',$valBrotU,$valBrotD, $allObjects);
			 break;
			 }
		  case 1:{ // 5
			 tabUpd('brothDn',-1,$valBrotU,$allObjects);
			 break;
			 }
		  case 2:{ // 5
			 tabUpd('fson',$valBrotD,$valParent,$allObjects);
			 tabUpd('brothUp',-1,$valBrotD,$allObjects);
			 break;
			 }
		  case 3:{ // 5
			 tabUpd('fson',-1,$valParent,$allObjects);
			 tabUpd('switch',0,$valParent,$allObjects);
			 break;
			 } // 5
	  	  } // 4
	}  // 3
// изменение светимости элементов одного родителя
 function switchNewEl($swEl)
	{ // 3
	 global $link,$repl;
	$valSon = array();
	$valSw = array();
	$query = "SELECT `switch`,`number`,`fson` FROM (SELECT `parent` AS `par` FROM `objects` WHERE `number`=".$swEl.") AS `ob1` INNER JOIN `objects` AS `ob2` WHERE `ob2`.`parent`=`ob1`.`par` AND (`ob2`.`switch`=1 OR `ob2`.`number`=".$swEl.")";
	if (!$res=mysqli_query($link,$query))
	   {$repl.=" Operation failed.";
		return;
	    }
	$point = -1;
	while ($val=mysqli_fetch_assoc($res))
		{
		if ($val['fson'] == -1)
			$valSon = $val;
		if ($val['switch'] == 1)
			$valSw = $val;
		if ($val['number'] == $swEl)
			$point = $swEl;
		}
 	if ($valSw) 
		{
		$query = "UPDATE `objects`  AS `o` INNER JOIN (SELECT `switch` AS `sw` FROM  `objects` WHERE `number`=".$valSw['number'].") AS `o1` SET `o`.`switch`= 1-CONVERT(`o1`.`sw`,SIGNED) WHERE `o`.`number`=".$valSw['number'];
  		mysqli_query($link,$query);
		}
	if ($valSon && $valSon['number'] == $swEl) return;
	if ((!$valSw && !$valSon && $point != -1) || ($valSw && $valSw['number'] != $swEl))
		{
		$query = "UPDATE `objects`  AS `o` INNER JOIN (SELECT `switch` AS `sw` FROM  `objects` WHERE `number`=".$swEl.") AS `o1` SET `o`.`switch`= 1-CONVERT(`o1`.`sw`,SIGNED) WHERE `o`.`number`=".$swEl;
  		mysqli_query($link,$query);
		}
	mysqli_free_result($res);
	}

//  ----------------createObj begin -------------------

if (isset($_POST)) 
	{ // 3
	$group='';
	foreach($_POST as $key => $pval)
		{$group .= $pval;
		}
	if (!uniquePost($group)) {onScreen(); exit();}
	if (!isset($_POST['numb'])) {onScreen(); exit();}
	$numberEd=(int)$_POST['numb'];
	if (!linkBase()) {onScreen(); exit();}
	foreach ($_POST as $name => $pval)
		{
		 switch ($name)
			{
			case 'switch_x':
				switchNewEl($numberEd);
				break;
			case 'delObj':
				$delEl=$numberEd;
				if ($delEl==0)
					{$repl .=TandConv(' Нельзя удалить нулевой элемент.');
					 break;
					}
				$query = "SELECT * FROM  `objects`";
  				$res=mysqli_query($link,$query);
  				if ($res)
				 {// 1
				 while($val=mysqli_fetch_assoc($res))
					{
					$valAr[]=$val;
					}
   				 mysqli_free_result($res);
				 if($valAr)
	 			  {// 2
				   tabDel($delEl); // удаление из базы
				   $valSon = $allObjects[$delEl]['fson'];
				   changeContext($delEl,$allObjects);
				   $valPar=$allObjects[$delEl]['parent'];
				   unset($allObjects[$delEl]);
				   $stCurPoint = 0;
				   $stack[$stCurPoint]=-1;
				  do
				   { // 3
		// цикл по сыновьям
				    while ($valSon != -1)
				    { // 4
				     $stack[$stCurPoint]=$valSon;
			    	 $stCurPoint++;
				     if ($allObjects[$valSon]['fson'] == -1)
					 { // 5
					  $valBrot = $allObjects[$valSon]['brothDn'];
		// цикл по братьям
				       if ($valBrot != -1)
						{ // 6
						$valSon = $allObjects[$valBrot]['fson'];
				 		}
					  else {$valSon =-1; break;}
					  } // 5
					 else $valSon=$allObjects[$valSon]['fson'];
				      } // while 4
					$stCurPoint--;
				     if ($stCurPoint >= 0 && $stack[$stCurPoint] != -1)
					 { // 4 
				 	 $valSon = $stack[$stCurPoint];
					 $valPar = $allObjects[$valSon]['parent'];
					  $query ="DELETE FROM `objects` WHERE `parent`=".$valPar;
  	 				  $res=mysqli_query($link,$query);
			       	  $stCurPoint--;
				 	  tabUpd('fson',-1,$valPar,$allObjects);
					  tabUpd('switch',0,$valPar,$allObjects);
					  $valSon = $valPar;
					 } // 4
				   } while($stCurPoint >= 0); // 3
				  } // 2
				 } // 1
				break; 
			case 'send-titl_x':
				if (isset($_POST['numb']))
				$titleEd = $_POST['titl'];
				tabUpd('title',$titleEd,$numberEd,$allObjects,false);
				break; 
			case 'send-desc_x':
				$descrEd = $_POST['desc'];
				tabUpd('descript',$descrEd,$numberEd,$allObjects,false);
				break; 
			case 'send-par_x':
				$parenEd = (int)$_POST['par'];
				$query= "SELECT * FROM  `objects` WHERE `number`=$parenEd";
  				$res=mysqli_query($link,$query);
  				if ($res)
				  {
				  $curEd = (int)$_POST['numb'];
				  changeContext($curEd,$allObjects);
				  $son = $allObjects[$parenEd]['fson'];
				  tabUpd('brothUp',$curEd,$son,$allObjects);
				  tabUpd('brothDn',$son,$curEd,$allObjects);
				  tabUpd('brothUp',-1,$curEd,$allObjects);
				  tabUpd('fson',$curEd,$parenEd,$allObjects);
				  tabUpd('parent',$parenEd,$curEd,$allObjects);
				  switchNewEl($curEd);
   				  mysqli_free_result($res);
				  }
				 else
				  {
				   return $repl .= TandConv(' Недопустимый номер назначенного объекта');
				  }
				break; 
			case 'send-son_x':
				$curEd = $numberEd;
		
				$query ="SELECT * FROM `adminData`";
				$res = mysqli_query($link,$query);
	 			if($res)
					{
					$val=mysqli_fetch_assoc($res);
					$max = $val['maxId']+1;
					$query ="UPDATE `adminData` SET `maxId`=$max";
	 				mysqli_query($link,$query);
					$tempAr = array('number' => $max,
					 'parent' => $curEd,
					 'brothUp' => -1,
					 'brothDn' => $allObjects[$curEd]['fson'],
					 'fson' => -1,
					 'switch' => 0,
					 'title' => 'title'.$max,
					 'descript' => 'description'.$max);
					 $son =$allObjects[$curEd]['fson'];
					if ($son != -1)
					  {
	  				   fill($allObjects,$tempAr);
					   tabUpd('brothUp',$max,$son,$allObjects);
					   tabUpd('fson',$max,$curEd,$allObjects);
					   }
					   else 
						{
	  				   	fill($allObjects,$tempAr);
					   	tabUpd('brothDn',-1,$max,$allObjects);
					   	tabUpd('fson',$max,$curEd,$allObjects);
					   	switchNewEl($curEd);
						}
					   }
					else print_r('++Error = '.mysqli_error($link));
				   break; 
			default: 
			}
		} // foreach
	mysqli_close($link);
	} // isset

//================= createObj end=========================
 }
onScreen(); 
unset($_POST);
exit();
?>