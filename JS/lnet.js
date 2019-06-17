// JavaScript Document
{
	document.documentElement.scrollTop = "0px";
	var user_id = -1 ;
	var user_name = '';
	var portrait_id = -1;
	var sysDate = new Date();
	var oLogIn = document.getElementById('log_in');
	if(oLogIn)
	{
		  var oControlBar = oLogIn.querySelector('.control_bar');
		  oControlBar.onmousedown = function (ev){
			  event = ev || window.event;
			  var disX = event.clientX - oLogIn.offsetLeft;
			  var disY = event.clientY - oLogIn.offsetTop;
			  document.onmousemove = function(ev){
				  event = ev || window.event;
				  oLogIn.style.left = event.clientX - disX -200 + "px";
				  oLogIn.style.top = event.clientY - disY + "px";
				  event.preventDefault();
			  }
			  document.onmouseup = function(){
				  document.onmousemove = null;
				  document.onmouseup = null;
			  }
			}
	}
}

function getUserInfo(){
	var myDate = new Date();
	var xhr = new XMLHttpRequest();
	xhr.open('GET','../data_process_php/get_data.php?target=user_info&time='+myDate.getTime(),true);
	xhr.onreadystatechange = function(){
		if(xhr.readyState==4)
		{
			if(((xhr.status>=200)&&(xhr.status<300))||(xhr.status==304))
			{
				    var regexp = /\S+/ ;
					var newStr = xhr.responseText.match(regexp);
				    setUser(newStr);
				
			}
			else
			{
			  alert('数据获取出错'+xhr.status);
			  user_id = -1 ;
			  user_name = '';
			  portrait_id = -1;
			}
		}
	}
	xhr.send(null);
}

function setUser(userInfo){
       if(userInfo)
	   {
		   var user = JSON.parse(userInfo);
		   user_id = user[0]['user_id'];
		   user_name = user[0]['user_name'];
		   portrait_id = user[0]['portrait_id'];
	   }
	   else
	   {
		   user_id = -1 ;
		   user_name = '';
		   portrait_id = -1;
	   }
}

function showForm(formId) {
	var oForm = document.getElementById(formId);
	oForm.style.position = "absolute";
	oForm.style.top = "280px";
	oForm.style.display="block";
	oForm.style.zIndex="100";
	var oGraylayer = document.getElementById('gray_layer');
	oGraylayer.style.display="block";
	
	
}
function hideForm(formId)
{
	var oForm = document.getElementById(formId);
	oForm.style.display="none";
	var oGraylayer = document.getElementById('gray_layer');
	oGraylayer.style.display="none";
}
           
function redirect_check(path)
{
	var url = 'http://'+window.location.host+'/'+path;
	alert(url);
	window.location.assign(url);
	
	
	
}

function getProgress(target,name,fun_succed,fun_failed,fun_going,env)
{
	if(arguments.length<6)
	   return false;
	var xhr = new XMLHttpRequest();
	xhr.open('GET','../data_process_php/progress.php?target='+target+'&PHP_SESSION_UPLOAD_PROGRESS='+name+'&time='+sysDate.getTime(),true);
	xhr.onreadystatechange = function(){
		if(xhr.readyState==4)
		{
			if(((xhr.status>=200)&&(xhr.status<300))||(xhr.status==304))
			{
				var result = JSON.parse(xhr.responseText);
				switch(result.status)
				{
					case 'ok':
					    fun_succed.call(env,result.path);
						break;
					case 'mistake':
					    fun_failed.call(env,result.error);
						break;
					case 'loading':
					    fun_going.call(env,result.stage);
						break;
					default:
					     fun_failed.call(env,'未知错误');
				}
			}
			else
			{
			  alert('数据获取出错'+xhr.status);
			}
		}
    }
	xhr.send(null);
	
}

function getData( target , para , fun , env)
{
	var xhr = new XMLHttpRequest();
	xhr.open('GET','../data_process_php/get_data.php?target='+target+'&para='+para+'&time='+sysDate.getTime(),true);
	xhr.onreadystatechange = function(){
		if(xhr.readyState==4)
		{
			if(((xhr.status>=200)&&(xhr.status<300))||(xhr.status==304))
			{
				if(fun)
				{
				   if(env)
				   {
					   fun.call(env,xhr.responseText);
				   }
				   else
				   {
				       fun(xhr.responseText);
				   }
				}
			}
			else
			{
			  alert('数据获取出错'+xhr.status);
			}
		}
    }
	xhr.send(null);
	
}

function sendData(data,fun,env){
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function(){
		if(xhr.readyState==4)
		{
			if(fun)
			{
			   if(env)
			   {
				   fun.call(env,xhr.responseText);
			   }
			   else
			   {
				   fun(xhr.responseText);
			   }
			}
		}
	}
	xhr.open('POST','../data_process_php/receive_data.php',true);
	xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhr.send(data);
	
}
function move(obj , property , dest,func){
 if(obj.timer)
 {
	 clearInterval(obj.timer);
 }
 obj.timer = setInterval(function(){
	 switch(property){
		 case 'left':
		 var speed = Math.ceil( (obj.offsetLeft - dest)/10);
		 obj.style.left = obj.offsetLeft - speed + 'px';
		 if(Math.abs(obj.offsetLeft - dest)<1)
		 {
			obj.style.left = dest + 'px'; 
			clearInterval(obj.timer);
		 }
		 break;
		 case 'top':
		 if(obj.offsetTop - dest>0)
		 {
			 var speed = Math.ceil( (obj.offsetTop - dest)/10);
		 }
		 else
		 {
			 var speed = Math.floor( (obj.offsetTop - dest)/10);
		 }
		 obj.style.top = obj.offsetTop - speed + 'px';
		 if(Math.abs(obj.offsetTop - dest)<1)
		 {
			obj.style.top = dest + 'px'; 
			clearInterval(obj.timer);
			if(func)
	            func();
		 }
		 break;
		 
		 
	 }
	 
    },17);
	
 
 
}

function toWords(string){
	var words = new Array();
	string = string.toLowerCase();
	var regex = /[^a-z]{1}/g;
	string = string.replace(regex,' ');
	var originWords = string.split(' ');
	for(var i=0; i< originWords.length ; i++)
	{
		var temp = originWords[i].trim();
		if(temp)
		{
			words.push(temp);
		}
	}
	return words;
}
function myTrim(strIn){

	var reg = /\S.*\S/ ;
	strOut = strIn.match(reg);
	if(strOut)
	  return strOut ;
	 else
	   return '';
}