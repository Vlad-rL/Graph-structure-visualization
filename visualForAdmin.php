<?php
echo '
	<script type="text/javascript">
	window.addEventListener("DOMContentLoaded", function ()
 	{ 
	var allObjects;
	var inRowCnt=0;
	allObjects = JSON.parse(\''.$allObjectsJson.'\');
	console.log("allObjects = ");
	console.log(allObjects);

// ���������� ������
// --------------------	
// ���������� ��������
	function fill(numberStr,parentEl,newId,mnClass,rowCnt)
	{
	switch (mnClass)
	 {
	  case "arrow":{mnGif="arrow.gif";
		break;}
	  case "plus":{mnGif="plus.gif";
		break;}
	  default:
		break;
	 }
	but=(mnClass.length==0)?"<img src=\'minus.gif\'>":"<div><form action=\'\' method=\'POST\' name=\'form\'><input id=\'numberEd\' name=\'numb\' type=\'text\' value=\'"+numberStr+"\'  readonly><input name=\'switch\' type=\'image\' src= \'"+mnGif+"\' alt=\'switch\'></form></div>";
	newEl=document.createElement("div");
	newEl.id = newId;
	newEl.className = "dia";
	newEl.innerHTML ="<div class=\'number\'>"+numberStr+"</div><div class=\'title\'>"+ allObjects[numberStr]["title"]+"</div><div class=\'descript\'>"+	allObjects[numberStr]["descript"]+"</div><div class=\'mnemo\'>"+but+"</div><div class =\'nInr\'>"+rowCnt+ "</div>";
	parentEl.append(newEl);
	return newEl;
	}
// --------------------	
// ���������� �������
// first - ����� ������� �������� � �������
	function crCol(parent0El,first,allObjects,lastColY,disp)
	{
	newEl=document.createElement("div");
	newEl.className ="row";
	newEl.style.transform = "translateY("+lastColY+"px)";
	newEl.style.display = disp;
	parent0El.append(newEl);
	parentRow=newEl;
	switchPointer = -1;
	lastNumForSw = 0;
	number=first;
	rowCnt=0;
	do
	{
	if (allObjects[number]["switch"] == 1)
		{switchPointer = number;
		 lastNumForSw = rowCnt;
		}
	number = allObjects[number]["brothDn"];
	}
	while (number != -1)
	rowCnt=0;
	number=first;
	var switchEl;
	do
	{
	newId = "s"+allObjects[number]["number"];
	if (switchPointer == number)
		switchEl=fill(number,parentRow,newId,"arrow",rowCnt);
	  else 
	  if (allObjects[number]["fson"] != -1)
		fill(number,parentRow,newId,"plus",rowCnt);
	    else
 		fill(number,parentRow,newId,"",rowCnt);
	number = allObjects[number]["brothDn"];
	rowCnt++;
	}
	while (number != -1)

	return switchEl;
	}

// ���������� ������
// ------------------------- begin --------------
	lastColY=0;
	firstSon = allObjects[0]["fson"];
	parent0El = document.getElementById("diagr");
	switchEl=crCol(parent0El,0,allObjects,lastColY,"block");
	if (switchEl)
	{
	el= switchEl;
	margStr=getComputedStyle(el).marginBottom;
	margin = parseInt(margStr.substring(0,(margStr.length-1)));
	elHeight = el.offsetHeight+margin;
	while (firstSon != -1 )
	{
	lastColY += inRowCnt*elHeight;
	if (firstSon != 0)
		{
		switchEl=crCol(parent0El,firstSon,allObjects,lastColY,"block");
		if (!switchEl) break;
		}
	firstSon = allObjects[switchEl.querySelector("div.number").innerHTML]["fson"];
	inRowCnt=switchEl.querySelector("div.nInr").innerHTML;
	}
	}
// -----------------------------------
// -------������������ � ����� �� ����� ---------------
// --------------------------------------------
	diagEl = document.getElementById("diagr");
 	diagEl.addEventListener("click",function () 
	 {
	 event.stopPropagation();
	 var target = event.target || event.srcElement;
	 elCl = target;
	 do
	 {
	 if (elCl.classList.contains("mnemo") || elCl == diagEl) return;
	 elCl = elCl.parentElement;
	 } while (!elCl.classList.contains("row"))
	 elCl = target;
	 total = 0;
	 do
	 {
	 if (elCl.classList.contains("dia") || elCl.classList.contains("number") || elCl.classList.contains("title") || elCl.classList.contains("descript"))
		{if (!elCl.classList.contains("dia")) elCl = elCl.parentElement;
		 elPar = elCl;
		 total = 1;
		 break;
		}
	  if(elCl == diagEl) return;
	  if(elCl.classList.contains("row")) return;
	  elCl = elCl.parentElement;
	 } while (elCl != diagEl)

 	 if (total==1)
			{
			elClNumb = elPar.querySelector("div.number");
			elClTitl = elPar.querySelector("div.title");
			elClDesc = elPar.querySelector("div.descript");
			elClPar = allObjects[elClNumb.innerHTML]["parent"];
			elClPar = elPar.parentElement.querySelector("div.number");
			elWin = document.getElementById("editWin");
			elWin.querySelector("input#numberEd").value = elClNumb.innerHTML;
			elWin.querySelector("input#titleEd").value = elClTitl.innerHTML;
			elWin.querySelector("input#descrEd").value = elClDesc.innerHTML;
//			elWin.querySelector("input#parenEd").value = elClPar;
			elWin.style.top = lastColY+"px";
			elWin.style.display = "block";
			}
	 });
    });
   </script>';
?>