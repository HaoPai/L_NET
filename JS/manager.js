// JavaScript Document

window.onload = function() {
	
	var oPageNav = document.getElementById('page_nav');
	var aLi = oPageNav.getElementsByTagName('li');
	var oPageIntro = document.getElementById('page_intro');
	var oResume = document.getElementById('resume');
	var oManagerMessages = document.getElementById('manager_messages');
	var oMessageForm = oManagerMessages.getElementsByTagName('form')[0];
	var oLogIn = document.getElementById('log_in');
	var oLogError = oLogIn.querySelector('.error');
	var oMessageList = document.getElementById('message_list');
    for(var i =0 , len = aLi.length ; i <len ; i++)
	{
		aLi[i].index = i;
		aLi[i].onclick = function(){
			hideAll();
			switch(this.index)
			{
				case 0:
				   oPageIntro.style.display = "block";
				   break;
				case 1:
				   oResume.style.display = "block";
				   break;
				case 2:
				   oManagerMessages.style.display = "block";
				   break;
			}
		}
	}
	oMessageForm.onsubmit = function (ev){
		var event = ev || window.event ;
		if(user_id>0)
		{
			var message = oMessageForm.elements['manager_message'];
			if(message.value)
			{
				  var parts = new Array();
				  parts.push(encodeURIComponent(message.name)+"="+encodeURIComponent(message.value));
				  parts.push(encodeURIComponent('add_manager_message')+"="+encodeURIComponent(''));
				  sendData(parts.join("&"),getMessages,window.onload);
				  message.value = "";
				  getMessages();
			}
			else
			{
				alert("留言不能为空！");
			}
			
		}
		else
		{
			 oLogError.innerHTML = "您需要先登录才能留言！";
			 showForm('log_in');	 
		}
		if(event.preventDefault)
		{
			event.preventDefault();
		}
	   else
	   {
		    event.returnValue = false;
	   }
		
	}
	getUserInfo();
	getMessages();
	function hideAll(){
		oPageIntro.style.display = "none";
		oResume.style.display = "none";
		oManagerMessages.style.display = "none";
	}
	function getMessages()
	{
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState==4)
			{
				if(((xhr.status>=200)&&(xhr.status<300))||(xhr.status ==304))
				{
					showMessages(xhr.responseText);
					xhr = null;
				}
				else
				{
					alert('数据错误'+xhr.status);
					xhr = null;
				}
			}
		}
		var myDate = new Date();
		xhr.open('GET','../data_process_php/get_data.php?target=manager_messages&time='+myDate.getTime(),true);
		xhr.send(null);
		
	}
	
	function showMessages(data)
	{
		clearMessages();
		oMessageList.innerHTML="";
		var messageList = JSON.parse(data);
		for(var i =0 , len = messageList.length ; i< len ; i++)
		{
			var messageId = messageList[i].message_id;
			var portraitId = messageList[i].portrait_id;
			var userId = messageList[i].user_id;
			var userName =messageList[i].user_name;
			var messageContent = messageList[i].message_content;
			var messageTime = messageList[i].message_time;
			
			var oPortrait = document.createElement('img');
			if(portraitId<=15)
			{
				  oPortrait.src ="img/portrait/portrait-min.png";
				  oPortrait.height = "360" ;
				  oPortrait.style.height = "360px";
				  var top = parseInt((portraitId -1) / 3)*72+7;
				  var left = parseInt((portraitId -1 )% 3)*83+11;
				  oPortrait.style.position = "absolute";
				  oPortrait.style.top = -top+"px";
				  oPortrait.style.left = -left+"px";
			}
			else
			{
				  oPortrait.src ="img/portrait/"+messageList[i].portrait_path;;
				  oPortrait.style.height = "60px";
			}
			
			var oPortraitFrame = document.createElement('div');
			oPortraitFrame.className = "portrait_frame";
			oPortraitFrame.appendChild(oPortrait);
			
			var oMessageMain = document.createElement('div');
			oMessageMain.className = "message_main";
			if(user_id==userId)
			{
				temp = '<div class="message_head" ><span class="user_name">'+userName+'</span>';
				temp += ('<a  class="delete_message">删除</a>');
				temp += '</div>';
				oMessageMain.innerHTML = temp;
			}
			else
			{
			    oMessageMain.innerHTML = '<div class="message_head" ><span class="user_name">'+userName+'</span></div>';
			}
			oMessageMain.innerHTML += '<div class="message_content">'+messageContent+'</div>';
			oMessageMain.innerHTML += '<div class="message_time">'+messageTime+'</div>';
			
			var oMessageLi = document.createElement('li');
			oMessageLi.className ="clearfix";
			oMessageLi.id = messageId;
			oMessageLi.appendChild(oPortraitFrame);
			oMessageLi.appendChild(oMessageMain);
			oMessageList.appendChild(oMessageLi);
			
			
		}
		var aDeleteMessages = oMessageList.querySelectorAll('.delete_message');
		for( var i=0 ,len =aDeleteMessages.length; i < len ; i++ )
		{
			aDeleteMessages[i].onclick = function(ev){
				id= this.parentNode.parentNode.parentNode.id;
				oMessageList.removeChild(this.parentNode.parentNode.parentNode);
				myEvent = ev || window.event;
				if(myEvent.preventDefault)
				{
					myEvent.preventDefault();
				}
				else
				{
					myEvent.returnValue = false;
				}
				var xhr = new XMLHttpRequest();
	            xhr.open('GET','../data_process_php/get_data.php?target=delete_message&id='+id,true);
				xhr.send(null);
			}
		}
	}
	function clearMessages(){
		var messages = oMessageList.getElementsByTagName('li');
		//alert(messages.length);
	}
	
}

