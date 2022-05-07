// функции: resoff,onoffwin,offwin,show_hide_pass, infreport
// информационное сопровождение взаимодействия с элементами экрана

function resoff()
{
el=document.getElementById('regwinRes');
el.style.display="none"; 
}

eldis=0;
function onoffwin(id)
{
// switching on/off windows
	console.log("onoffwin(id) = "+id)
	el=document.getElementById(id);
	if (el != eldis) 
		{if (eldis!=0) 
			{eldis.style.display="none";
			}
		 el.style.display="block"; 
		 eldis=el;
		 resoff(); 
		}
		else {el.style.display="none"; eldis=0;}
}

// opened window switching off
document.addEventListener("click", function(event){
el=event.target
while (el)
	{
	 if(el.id=='service')
		{ 
		resoff(); 
		return;
		}
	 el=el.parentNode
	}
if (eldis!=0) 
	{eldis.style.display="none"; 
	eldis=0;
	}
resoff(); 
return;
}, false);

function offwin(id)
{
// switching off windows

	el=document.getElementById(id);
	el.style.display="none"; eldis=0;
}

function show_hide_pass(target,cid){
	var input = document.getElementById(cid);
	if (input.getAttribute('type') == 'password') {
		target.classList.remove('view');
		input.setAttribute('type', 'text');
		input.setAttribute('autocomplete', 'nope');
	} else {
		target.classList.add('view');
		input.setAttribute('type', 'password');
		input.setAttribute('autocomplete', 'new-password');
	}
	return false;
}

var timeId;

function infreport (e,report)
{
if (document.documentElement.classList.contains('can-touch'))
	return;
if(timeId) clearTimeout(timeId)
X=e.clientX;
Y=e.clientY;
var el=document.getElementById("infbox");
el.style.opacity=1;
el.style.left=X-report.length*5/2+'px';
el.style.top=Y+20+'px';
console.log (report);
el.innerHTML=report;
el.style.display="block";
timeId=setTimeout(function()
	{
	el.style.opacity=0;
	el.style.display="none";
	}, 4000);
}