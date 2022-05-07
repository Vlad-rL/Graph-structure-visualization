<?php
if (!session_id())
	session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 5.0 Transitional//EN">
<HTML>
<HEAD>
<META http-equiv=Content-Type content="text/html; idcharset=UTF-8">
<TITLE>Просмотр и редактирование объектов </title>
<META NAME="keywords" CONTENT="просмотр редактирование объект">
<META name="description" content="Интерактивное редактирование объектов">

<meta http-equiv="Content-Language" content="ru">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<META http-equiv="Content-Script-Type" content="text/javascript">
<script language="JavaScript1.2" src="service.js">
</script>
<style type="text/css">
#diagr	{
	position: relative;
	display: -webkit-box; display: -webkit-flex; 
  	display: -ms-flexbox; display: flex; 
	-webkit-flex-direction:row; flex-direction: row;
	height: fit-content; 
	}
#editWin	{
	position: relative;
	left: 10px;
	top: 10px;
	width: 360px;
	border: 2px double black;
	border-radius: 15px;
	background-color: white;
	font-size: small;
	display: none; 
	}
#editWin label, #editWin input
	{
	position: relative;
	left: 10px;
	}
div.dia 
	{
	position: relative;
	font-size: small;
	margin: 15px;
	margin-top: 0px;
	padding: 10px;
	padding-top: 0px;
	border: 2px solid black;
	width: 100px;
	max-height: 80px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	display: block;
	background: #ffffff;
	cursor: pointer;
	}

div.dia > div.number
	{
	text-decoration: none;
	color: green
	}
div.dia > div.title
	{
	text-decoration: underline;
	font-weight: 700;
	color: red;
	}
div.dia > div.descript
	{
	text-decoration: none;
 	overflow: hidden;
	color: darkblue;
	}
div.dia > div.nInr
	{
	display: none;
	}

.line		{
	display: -webkit-box; display: -webkit-flex; 
  	display: -ms-flexbox; display: flex; 
	-webkit-flex-direction:row; flex-direction: row; 
	}
.row		{
	position: relative;
	left:0px;
	padding-right: 28px;
	top: 0px;
	display: -webkit-box; display: -webkit-flex; 
  	display: -ms-flexbox; display: flex; 
	-webkit-flex-direction:column; flex-direction: column; 
	transform: translateY(0px); 
	}

div.dia > div.mnemo	{
	position: absolute;
	top: 10px;
	left: 100px; 
	width: 20px;
	height: 20px;
	display: block; 
	cursor: pointer;
	padding: 5px;
	border: 1px;
	list-style: none;
	line-height: 1;
	text-decoration: none;
	background: #ffffff00;
	z-index: 2;
	}
.mnemo span	{
	position: relative;
	top: 0px;
	height: 2px;
	margin: 10px 0;
	color: grey;
	pointer-events: none;
	}

.mnemo form	{
	position: absolute;
	top: 0px;
	left: 0px;
	z-index: 3;
	}
.mnemo form input#numberEd
	{
	position: relative;
	display: none;
	}


input {
	position: relative;
	padding: 3px;
	box-sizing: border-box;
	}
input[type="text"] {
	width: auto;
	}
#service{
	position: relative;
	 top: 10px; 
	display: -webkit-box; display: -webkit-flex; display: -ms-flexbox; display: flex; 
	-webkit-flex-direction:row; flex-direction: row; 
	justify-content: center;
	-webkit-align-items: center; -webkit-box-align: center; -ms-flex-align: center;
	align-items: center;
	padding: 0 35% 4vw;
	}
#service span {
	position:relative;
	width: auto;
	}

#service span a {
	text-decoration: none;	
	}

#infbox	{
	position: absolute;
	top: 10px;
	left: 10px;
	width: auto;
	display: block; 
	z-index:50;  
	margin: 0px;
	padding: 6px;
	border: 0px ;
	-moz-border-radius: 12px;-webkit-border-radius: 12px; 
	font-size: calc( (100vw - 25rem) / (85 - 25) * (1.05 - 1) + 1.05rem);
	background: #f9ff9c;
	opacity: 1;
	transition: opacity 1s;
	z-index:51;  
	}

#nick	{
	position:relative;
	top: 0px; 
	text-decoration: none;
	cursor: pointer;
	}
#regwin, #regwinRes	{
	position: absolute;
	top: 65px;  
	text-align:left; 
	margin:10px; 
	padding: 10px;
	display:none; 
	font-size: calc( (100vw - 25rem) / (85 - 25) * (1.05 - 1) + 1.05rem); 
	z-index:65; 
	background:white; 
	border: 1px solid #bbbbbb;
	opacity: 1.0; 
	width: 18rem;
	overflow: visible;
	}
#regwinRes	{
	border:solid 1px #666666;
	-moz-border-radius: 6px;-webkit-border-radius: 6px; 
	}


.star	{
	vertical-align:-4px;
	padding: 5px;
	}
label {
	position: relative;
	}

/* show_hide_password */

.password {
	position: relative;
	height: 25px;
	top: 0px;
	}

.password-control {
	position: relative;
	top: 4px; 
	left: 20px; 
	display: inline-block;
	width: 12px;
	height: 15px;
	}

.password-control::after {
	content: url(view.svg); 
	right: 9px;
	width: 15px;
	}

.password-control.view::after  {
	content: url(no-view.svg); 
	}
textarea {
	font-size: calc( (100vw - 25rem) / (85 - 25) * (1.05 - 1) + 1.05rem); 
	font-family: Verdana, arial, Tahoma, helvetica, sans-serif; 
	}
#toshrink	{
	position: absolute;
	left: -30px; 
	width: 20px;
	height: 20px;
	top: -20px;
	cursor: pointer;
	z-index:3;  
	padding: 0;
	margin: 10px 0;
	color: grey;
	border: 1px;
	list-style: none;
	line-height: 1;
	border-radius: 10px;
	text-decoration: none;
	text-align: center;
	background: #bbbbbb;
	}
#toshrink:before, #toshrink:after {
	position: absolute;
	left: 10px;
	content: ' ';
	height: 20px;
	width: 2px;
	background-color: #333;
	}
#toshrink:before {
	transform: rotate(45deg);
	}
#toshrink:after {
	transform: rotate(-45deg);
	}
#infboxV	{
	position: absolute;
	top: 20px;
	right: 95px;
	width: 200px;
	display: none; 
	z-index:50;  
	margin: 0px;
	padding: 6px;
	border: 0px ;
	-moz-border-radius: 8px;-webkit-border-radius: 8px; 
	font-size: normal;
	background: lightblue;
	opacity: 1;
	transition: opacity 1s;
	z-index:3;  
	}

</style>

</HEAD>      

<body>
<script type="text/javascript">
// --------------------	
// заполнение элемента
	function JSONparse(Json)
	{
	JsonStr= Json.toString();
	var outputG = [[]];
	var output = [];
	console.log('JSONparse...')
	console.log('Json = '+JsonStr)
	for (let i=0; i < JsonStr.length; i++)
	 {
	 fBr=JsonStr.indexOf("{", i);
	 if (fBr == -1) return;
	 lBr=JsonStr.indexOf("}", fBr);
	 if (lBr == -1) return;
	 var output = [];
	 var smAr;
	 var comma0 = fBr;
	 do{
	   comma=JsonStr.indexOf(',', comma0);
	   if (comma == -1 || comma > lBr) break;
	   pair = JsonStr.substring(comma0+1, comma);
	   var pairAr;
	   sQuot = comma0+1;
	   var fQuot;

	 	fQuot=pair.indexOf('"', sQuot);
	 	sQuot=pair.indexOf('"', fQuot);
	  	key = pair.substring(fQuot+1, sQuot);
	 	fQuot=pair.indexOf('"', sQuOt+1);
	 	sQuot=pair.indexOf('"', fQuot);
	  	value = pair.substring(fQuot+1, sQuot);
		console.log('key = '+key)
		console.log('value = '+value)
		pairAr== {key:value};
		console.log(pairAr)
		var item;
		for (var type in pairAr) {
    			item = {};
    			item.type = type;
    			item.name = pairAr[type];
    			output.push(item);
			}
		console.log('output:')
		console.log(output)
	    comma0=comma+1;
	   } while (comma < lBr)
	 outputG[i]=output;
	console.log('outputG:')
	console.log(outputG)
	 i=lBr++;
	 }
	return outputG;
	}

</script>
<?php 
include 'construct.php';
?>


</body>

</html>
