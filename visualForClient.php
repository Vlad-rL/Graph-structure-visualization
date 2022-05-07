<?php
echo '
	<script type="text/javascript">
	window.addEventListener("DOMContentLoaded", function ()
 	{ 
	var allObjects;
	var inRowCnt=0;
	allObjects = JSON.parse(\''.$allObjectsJson.'\');

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
	im=(mnClass.length==0)?"<img src=\'minus.gif\'>":"<img src=\'"+mnGif+"\'>";
	mn = (mnClass.length==0)? "":" class=\'"+mnClass+"\'";
	newEl=document.createElement("div");
	newEl.id = newId;
	newEl.className = "dia";
	newEl.innerHTML ="<div class=\'number\'>"+numberStr+"</div><div class=\'title\'>"+ allObjects[numberStr]["title"]+"</div><div class=\'descript\'>"+	allObjects[numberStr]["descript"]+"</div><div class=\'mnemo\'><span "+mn+">"+im+"</span></div><div class =\'nInr\'>"+rowCnt+ "</div>";
	parentEl.append(newEl);
	return newEl;
	}
// --------------------	
// ���������� �������
// first - ����� ������� �������� � �������
	function crCol(parentEl,first,allObjects,lastColY,disp)
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

// ��������� ���������
	function construct()
	{
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
	}
// ------------------------- begin --------------

	construct();

// -----------------------------------
// -------������������ � ����� �� ����� ---------------
// ----------------------------------------------
	diagEl = document.getElementById("diagr");
 	diagEl.addEventListener("click",function () 
	 { // 1
	 event.stopPropagation();
	 var target = event.target || event.srcElement;
	 elCl = target;
	 var elPar;
	do
	 { // 2
	 if (elCl.classList.contains("dia"))
		{elPar = elCl;
		 break;
		}
	  elCl = elCl.parentElement;
	  if(elCl.classList.contains("row")) break;
	 } while (elCl != diagEl)
	 elCl = target;
	 if (elCl.classList.contains("mnemo")) elCl = elCl.firstElementChild;
	 if (elCl.classList.contains("plus") || elCl.classList.contains("arrow"))
	  { // 2
// ����������� ���������
		elClNumb = elPar.querySelector("div.number").innerHTML;
		pnumber = allObjects[elClNumb]["parent"];
		if (parseInt(elClNumb) == 0)
			{  // 3
			allObjects[elClNumb]["switch"] = 1-parseInt(allObjects[elClNumb]["switch"]);
			}
		else
		{ // 3
		 theOnlyPar =[];
		 for (var i = 0; i < Object.keys(allObjects).length; i++)
		 { // 4
  		 if (allObjects[i]["parent"] == pnumber)
			{ // 5
    		theOnlyPar.push(allObjects[i]);
  			}
		 }
		 if (theOnlyPar.length > 0)
		 { // 4
		 swAr = [];
		 for (var i = 0; i < Object.keys(theOnlyPar).length; i++)
			{ // 5
  			if (parseInt(theOnlyPar[i]["switch"]) == 1)
				{ // 6
    			swAr.push(theOnlyPar[i]);
  				}
			}
		 if (Object.keys(swAr).length > 0) 
			{ // 5
			 swNum = parseInt(swAr[0]["number"]);
			 allObjects[swNum]["switch"] = 1-parseInt(allObjects[swNum]["switch"]);
			 if (elClNumb != swNum) 
			 	allObjects[elClNumb]["switch"] = 1-parseInt(allObjects[elClNumb]["switch"]);
			}
		  else
			{ // 5
			 if (parseInt(allObjects[elClNumb]["fson"])== -1) return;
			 	allObjects[elClNumb]["switch"] = 1-parseInt(allObjects[elClNumb]["switch"]);
			}
// ������� ���� ���������
		 nodeC=diagEl;
		 while (nodeC.firstChild)
			{nodeC.removeChild(nodeC.firstChild)
			}
		 } // 4
	     } // 3
// ��������� ���� ���������
		diagEl.innerHTML = "";
		inRowCnt=0;
		construct();
	  } // 2
	  else
		if (elPar)
		   if (elPar.classList.contains("dia"))
			{
			elClDesc = elPar.querySelector("div.descript");
			infboxId = document.getElementById("infboxV");
			elClSpan = infboxId.querySelector("span");
			elClSpan.innerHTML = elClDesc.innerHTML;
			infboxId.style.display = "block";
			}
	 });
	});
   </script>';
?>