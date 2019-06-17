// JavaScript Document

window.onload = function(){
	 
		
		
	
//定义播放器//**************************************************************	
		var oAudioBox = document.getElementById('audio_box');
		
		var player = new Object();
		
		player.playMode = 0; //设置播放模式   0--顺序播放   1--随机播放   2--单句循环
		player.auto = false;
		player.seeked = false ;  //设置是否播放特定资源
		player.locked = false;
		player.audio = null ; //设置auido
		player.resouceId = 0 ;  //设置播放特定的资源id
		player.loadedIndex = 0; //设置已加载的列表序号
		player.sentanceList = null; //设置播放列表，播放列表是一个数组
		player.seekList = new Array() ;    //设置特定资源播放列表
		player.loadedIndexsList = new Array(); //设置已经浏览的序号列表，方便进行回
		player.sourcesList = new Array(); //记录列表下所有资源序号
		player.buffer = new Array();
		player.items = new Array();
		player.sentanceListAll = null;
		player.itemsAll = null;
		
		player.listIndex = -1 ;
		
		player.setList = function (listItems){
			this.init();
			this.items = listItems;
			for(var i=0 , len = this.items.length ; i < len; i++)
			{
				var sourceId = this.items[i].resource_id;
				for(var j=0 ; j < this.sentanceListAll.length ; j++)
				{
					if(this.sentanceListAll[j].resource_id == sourceId)
					 {
						 this.sentanceList.push(this.sentanceListAll[j]);
					 }
				}
			}
			this.buff();
		}
		
		player.init = function(){
			this.playMode = 0; //默认按序号播放
			this.auto = false; //默认单句播放
			this.seeked = false ;  //默认全局播放
			this.locked = false;
			this.audio = null ; //设置auido
			this.resouceId = -1 ;  //默认搜索id 
			this.loadedIndex = -1; //设置已加载的列表序号
			this.sentanceList = new Array(); //设置播放列表，播放列表是一个数组
		    this.seekList = new Array() ;    //设置特定资源播放列表
		    this.loadedIndexsList = new Array() ; //设置已经浏览的序号列表，方便进行回放
			this.sourcesList = new Array(); //设置该列表下的所有资源序号
			this.buffer = new Array(); //将缓冲区归零
			this.items = new Array();
		}
		
		player.buff = function( mode , start ){
			
			if( mode == true )
			{
				this.buffer = new Array() ;
			}
			var startIndex = parseInt(start) ? parseInt(start): -1;
			while(this.buffer.length < 3)
			{
				var lastIndex = (this.buffer.length==0)? startIndex : this.buffer[this.buffer.length-1].index;
				var nextIndex = this.getNextIndex(lastIndex);
				var temp = new Object();
				temp.audio = document.createElement('audio');
				temp.audio.id = "sentance_audio";
				if(this.seeked)
				{
					temp.audio.src = '/AF/audio/english_sentances/' + this.seekList[nextIndex].sentance_audio_name ;
					temp.sentance = this.seekList[nextIndex].sentance_content;
					temp.sourceId = this.seekList[nextIndex].resource_id;
				}
				else
				{
					temp.audio.src = '/AF/audio/english_sentances/' + this.sentanceList[nextIndex].sentance_audio_name ;
					temp.sentance = this.sentanceList[nextIndex].sentance_content;
					temp.sourceId = this.sentanceList[nextIndex].resource_id;
				}
				if(!temp.sentance)
				{
					alert('缓存出错');
				}
				temp.index = nextIndex;
				this.buffer.push(temp);
				//alert (nextIndex);
				
			}
			
		}
		
		
		player.loadSentance = function (){ //加载方法，加载列表中特定序号的剧组。
		        var loadContent = this.buffer.shift();
				oAudioBox.innerHTML = "";
				
				this.audio = loadContent.audio;
				this.audio.onended = this.playEnded;
				this.audio.onplay = this.onPlay;
				this.audio.onpause = this.playPause;
				this.loadedIndex = loadContent.index;
				this.loadedIndexsList.push(loadContent.index);
				this.resourceId = loadContent.sourceId;
				
				
				oAudioBox.appendChild(this.audio);
				
				var sourceName = this.getItemInfo(loadContent.sourceId).resource_name;
				oPlayTitleFlow.changeTitle(sourceName);
				oPlayFaceTitle.innerHTML = sourceName;
				oDicTitle.innerHTML = '听写：'+sourceName;
				if(this.seeked)
				{
				     oPlayInfo.innerHTML = (this.loadedIndex+1)+"/"+this.seekList.length;
				}
				else
				{
					oPlayInfo.innerHTML = (this.loadedIndex+1)+"/"+this.sentanceList.length;
				}
				var aSubtitleLis = oSubtitleUl.getElementsByTagName('li');
				while(oSubtitleUl.children.length >= 4)
				{
					oSubtitleUl.removeChild(oSubtitleUl.children[0]);
				}
				aSubtitleLis[2].style.height = "109px";
				var prepre = this.loadedIndexsList[this.loadedIndexsList.length-3];
				var pre = this.loadedIndexsList[this.loadedIndexsList.length-2];
				var now = this.loadedIndexsList[this.loadedIndexsList.length-1];
				if(this.seeked)
				{
					
					
					if(typeof prepre == "number")
					{
						aSubtitleLis[0].innerHTML = this.seekList[prepre].sentance_content ;
					}
					else
					{
						aSubtitleLis[0].innerHTML = "";
					}
					if(typeof pre == "number")
					{
						aSubtitleLis[1].innerHTML = this.seekList[pre].sentance_content ;
						aSubtitleLis[1].className ="";
					}
					else
					{
						aSubtitleLis[1].innerHTML = "";
					}
					if(typeof now == "number")
					{
						aSubtitleLis[2].innerHTML = this.seekList[now].sentance_content ;
						oSentance.innerHTML = this.seekList[now].sentance_content ;
						aSubtitleLis[2].className = "now";
					}
					else
					{
						aSubtitleLis[2].innerHTML = "";
					}
					
				 
				}
				else
				{
					if(typeof prepre == "number")
					{
						aSubtitleLis[0].innerHTML = this.sentanceList[prepre].sentance_content ;
					}
					else
					{
						aSubtitleLis[0].innerHTML = "";
					}
					if(typeof pre == "number")
					{
						aSubtitleLis[1].innerHTML = this.sentanceList[pre].sentance_content ;
						aSubtitleLis[1].className ="";
					}
					else
					{
						aSubtitleLis[1].innerHTML = "";
					}
					if(typeof now == "number")
					{
						aSubtitleLis[2].innerHTML = this.sentanceList[now].sentance_content ;
						oSentance.innerHTML = this.sentanceList[now].sentance_content ;
						aSubtitleLis[2].className = "now";
					}
					else
					{
						aSubtitleLis[2].innerHTML = "";
					}
				}
				
				var subtitleLi = document.createElement('li');
				if(this.buffer[0].index == 0)
				{
					if(this.buffer[0].sourceId == this.resouceId)
					{
						if(player.locked)
						{
							subtitleLi.innerHTML = "";
						    this.seek(player.resouceId);
						}
						else
						{
						    subtitleLi.innerHTML = "";
						    var nextSourceId = this.getNextSourceId();
						    this.seek(nextSourceId);
						 }
					}
				}
				else
				{
				      subtitleLi.innerHTML = this.buffer[0].sentance;
				}
				subtitleLi.style.height = "0px";
				oSubtitleUl.appendChild(subtitleLi);
				oSubtitleUl.timer = setInterval(function(){
					var last = oSubtitleUl.children[3];
					if(!last)
					{
						alert('数据获取出错'+last);
						clearInterval(oSubtitleUl.timer);
						
					}
					last.style.height = last.offsetHeight -41 + 3 + 'px';
					if((last.offsetHeight - 150) > -1)
					{
						last.style.height = "109px";
						clearInterval(oSubtitleUl.timer);
					}
					
				},16);
				this.buff();
		};
		
		player.play = function(){//播放方法，播放已经加载的句子
			this.audio.play();
		};
		
		player.pause = function(){ //暂停方法，暂停播放
		    this.audio.pause();
		}
		
		player.seek = function (id){ //选择播放特定的资源，传入资源id
		     this.resouceId = id;
			 this.seeked = true;
			 this.seekList = new Array();
			 for(var i =0 ,len = this.sentanceList.length ; i < len ; i++)
			 {
				 if(this.sentanceList[i].resource_id == id )
				 {
					 this.seekList.push(this.sentanceList[i]);
				 }
			 }

			 this.loadedIndexsList = new Array();
			 this.buffer = new Array();
			 this.buff();
			 
		}
		player.getNextSourceId = function(){
			var indexNow = this.getItemInfo(this.resourceId).index;
			if( (indexNow+1) >= this.items.length)
			{
				return this.items[0].resource_id;
			}
			else
			{
				return this.items[indexNow+1].resource_id;
			}
		}
		player.getNextIndex = function(lastIndex) {  //播放下一个句子
			
			switch(this.playMode)
			{
				case 0:
				       var nextIndex = lastIndex + 1 ;
				       if((this.seeked)&&(nextIndex>=this.seekList.length))
					   {
						   nextIndex = 0 ; 
					   }
					    if((!this.seeked)&&(nextIndex>=this.sentanceList.length))
					   {
						   nextIndex = 0 ;
					   }
					   
				break;
				case 1:
				       len = (this.seeked)? this.seekList.length : this.sentanceList.length ;
					    nextIndex = Math.floor(Math.random()*len);
				break;
				case 2:
				        nextIndex= lastIndex ;
				break;
			}
			return nextIndex;
		}
		player.prev = function() // 播放上一个句子
		{
			if(this.loadedIndexsList.length < 2)
			{
				alert('已经回退到底');
			}
			else
			{
				this.loadedIndexsList.pop();
				prevIndex = this.loadedIndexsList.pop();
				this.buff(true,prevIndex-1);
				this.loadSentance();
			    this.play();
			}
			
			
		}
		
		player.getItemInfoAll = function(sourceId){
			for(var i =0 , len = this.itemsAll.length ; i<len ; i++)
			{
				
				if(this.itemsAll[i].resource_id == sourceId)
				{
					
					return this.itemsAll[i] ;
				}
			}
		}
		player.getItemInfo = function (sourceId){
			for(var i =0 , len = this.items.length ; i<len ; i++)
			{
				
				if(this.items[i].resource_id == sourceId)
				{
					this.items[i].index = i ;
					return this.items[i] ;
				}
			}
			return '' ;
		}
		
		player.getSentancesAll = function (){
			getData( 'sentances' , '' , this.setSentancesAll, this )
		}
		
		player.setSentancesAll = function(data){
			var sentancesAll = JSON.parse(data);
			this.sentanceListAll = sentancesAll ;
			this.sentanceList = sentancesAll;
		}
		player.getItemsAll = function(){
			getData( 'english_resources' , '' , this.setItemsAll , this )
		}
		player.setItemsAll = function(data){
			var myitems = JSON.parse(data);
		    this.itemsAll = myitems ;
			this.items = myitems ;
		}
		player.firstLoad = function (){
			this.init();
			this.getSentancesAll();
			this.getItemsAll();
			var timer = setInterval(function(){
				  if(player.sentanceList.length&&player.items.length)
				  {
						player.seek(player.items[0].resource_id);
						player.auto = true;
						player.loadSentance();
						clearInterval(timer);
				  }
			},30);
			
			
		}
		
// 获取元素 //********************************************************	
	 
	 
	  var oReciteSentance = document.getElementById('recite_sentance');
	  var oQuiry = document.getElementById('query');
	  var oSentance = document.getElementById('sentance');
	  var oPlay = document.getElementById('play');
	  var oPrev = document.getElementById('prev');
	  var oPause = document.getElementById('pause');
	  var oNext = document.getElementById('next');
	  var oPlayList = document.getElementById('playlist');
	  var oPlayInfo = oPlayList.nextSibling;
	  var oOrder = document.getElementById('order');
	  var oShuffle = document.getElementById('shuffle');
	  var oLoop = document.getElementById('loop');
	  var oDicTitle = document.getElementById('dic_title');
	   var oDictation = document.getElementById('sentance_dictation');
	   var oDictationTextarea = oDictation.getElementsByTagName('textarea')[0];
	   var oClearButton = oDictation.getElementsByTagName('button')[0];
	   var oCheckButton = oDictation.getElementsByTagName('button')[1];
	   var oSentanceCheck = document.getElementById('sentance_check');
	   var oShowFrame = document.getElementById('show_frame');
	   var oCover = document.getElementById('cover');
	   var oMain = document.getElementById('main');
	   var aLis = oMain.getElementsByTagName('li');
	   var oSelectSentances = document.getElementById('select_sentances');
	   var aSentancesLi = oSelectSentances.getElementsByTagName('li');
	   var oSelectSentancesButton = oSelectSentances.getElementsByTagName('button')[0];
	   
	    var oQueryResult = document.getElementById('query_result');
		var oResultNumber = oQueryResult.querySelector('.result_number');
		var oResultList = oQueryResult.querySelector('.result_list');
	    var oQueryArea = document.getElementById('query_area');
	    var oQueryButton = oQueryArea.querySelector('.search_button');
		var oQueryText = oQueryArea.getElementsByTagName('input')[0];
		var oQuerySelectUl  =  oQueryArea.getElementsByTagName('ul')[0];
		var oQuerySecectList = oQuerySelectUl.getElementsByTagName('li');
		
		
		var oOperateBoard = document.getElementById('operate_board');
		var oMainMenu = oOperateBoard.querySelector('.main_menu');
		var oContentBox = oOperateBoard.querySelector('.content_box');
		var oScrollBox =  oOperateBoard.querySelector('.scroll_box');
		var oAddListButton = document.getElementById('add_list');
		var oEnglishList = document.getElementById('play_list');
		var oUserList = document.getElementById('user_list');
		var oPlayTitleFlow = document.getElementById('flow');
		var oNowInfo = document.getElementById('now_info');
		var aPlaySource = document.querySelectorAll('.play_source');
		
		
		
		var oPlayerFace = document.getElementById('player_face');
		var oHideFaceButton = document.getElementById('hide_face');
		var oSubtitleUl = oPlayerFace.getElementsByTagName('ul')[0];
		var oPlayFaceTitle = oPlayerFace.getElementsByTagName('h2')[0];
		
		
 //定义事件处理函数//**********************************************************************************************************   
       aLis[0].onclick = function(){   //L英语导航菜单 听英语 选项被点击处理函数
		   for( var i = 0, len = aLis.length ; i<len; i++)
		   {
			   aLis[i].className ="";
		   }
		   this.className ="selected";
		   oReciteSentance.style.display ="block";
		   oQuiry.style.display ="none";
	   }
	   aLis[3].onclick = function(){   //L英语导航菜单 搜索   选项被点击处理函数
		   for( var i = 0 ,len  = aLis.length ; i<len; i++)
		   {
			   aLis[i].className ="";
		   }
		   this.className ="selected";
		   oReciteSentance.style.display ="none";
		   oQuiry.style.display ="block";
	   }
	   
	   oCover.onclick = function(){   //听写封面被单击后的函数
		   move(this,'top',-200);
		   player.play();
	   }
	   
	   oClearButton.onclick = function() //重置按钮单击事件处理函数
	   {
		   oCover.style.top= '0px';
		   oCover.style.display ="block";
		   oCover.innerHTML ="——点我提示——";
		   oSentanceCheck.getElementsByTagName('p')[0].innerHTML = "";
		   oSentanceCheck.style.display="none";
		   oDictationTextarea.value = "";
	   }
	   oCheckButton.onclick = checkSentance;   //检查按钮单击事件处理函数
	   
	  oPlay.onclick=function(){      //播放按钮单击事件处理函数
		  oPlay.style.display='none';
		  oPause.style.display='inline-block';
		  player.play();
	  }
	  oPause.onclick=function(){     //暂停按钮单击事件处理函数
		  oPause.style.display='none';
		  oPlay.style.display = 'inline-block';
		  player.pause();
	  }
	   
	   player.playEnded = function (){  //播放停止事件发生时事件处理函数
		    oPause.style.display='none';
		    oPlay.style.display = 'inline-block';
		    cover();
			oPlayTitleFlow.moveStop();
			if(player.auto)
			{
				player.loadSentance();
				player.play();
			}
	  }
	  
	   player.onPlay = function(){    //播放过程中事件处理函数
		   oPlay.style.display='none';
		  oPause.style.display='inline-block';
		  oPlayTitleFlow.startMove();
	   }
	   player.playPause = function(){
		   oPlayTitleFlow.movePause();
	   }
	   oNext.onclick = function(){     //播放下一个句子
		   oCover.style.top= '0px';
		   oCover.style.display ="block";
		   oCover.innerHTML ="——点我提示——";
		   oSentanceCheck.getElementsByTagName('p')[0].innerHTML = "";
		   oSentanceCheck.style.display="none";
		   oDictationTextarea.value = "";
		    player.loadSentance();
		    player.play();
	   };
	   oPrev.onclick = function(){    //播放上一个句子
		   oCover.style.top= '0px';
		   oCover.style.display ="block";
		   oCover.innerHTML ="——点我提示——";
		   oSentanceCheck.getElementsByTagName('p')[0].innerHTML = "";
		   oSentanceCheck.style.display="none";
		   oDictationTextarea.value = "";
		   player.prev();
	   };
	   
	   for(var i =0 ; i < aSentancesLi.length ; i++)
	   {
		   aSentancesLi[i].onclick = function(){    //为选择句子范围窗体单个项点击添加事件选中对象
			   var oCheckbox = this.children[0];
			   if(oCheckbox.checked == true)
			   {
				     oCheckbox.checked = false ;
					 this.style.backgroundColor = "#eee";
					 this.style.color = "#000";
			   }
			   else
			   {
				     oCheckbox.checked = true ;
					 this.style.backgroundColor = "blue";
					 this.style.color = "#fff";
			   }
			  
		   }
	   }
	   oSelectSentancesButton.onclick = function(){   //选择句子窗体确认按钮单击事件处理函数
		   var parts = new Array();
		   for(var i =0 ; i < aSentancesLi.length ; i++)
	       {
			   var oCheckbox = aSentancesLi[i].children[0];
			   if(oCheckbox.checked==true)
			   {
				   var temp = encodeURIComponent('list_item[]')+"="+encodeURIComponent(oCheckbox.value);
				   parts.push(temp);
			   }
	       }
		   
		    englishListId = this.parentNode.parentNode.li.listId;
			parts.push(encodeURIComponent('list_id')+"="+encodeURIComponent(englishListId));
			parts.push(encodeURIComponent('add_english_list_item')+"="+encodeURIComponent(''));
		    sendData(parts.join("&"));
			hideForm('select_sentances');
			var index = this.parentNode.parentNode.li.listIndex;
			player.listIndex = index;
			getEnglishList() ;
			
	   }
	   oPlayList.onclick = function ()  //不要被这个名称迷惑 ，打开操作面板
	   {
		    oOperateBoard.style.display="block";
			player.auto = true;
			player.locked = false;
	   }
	   oOrder.onclick = function(){    //顺序播放图标被单击事件处理函数
		   oOrder.style.display="none";
		   oShuffle.style.display = "block";
		   player.playMode = 1;
		   player.buff(true,player.loadedIndexsList[player.loadedIndexsList.length-1]);
		   player.loadSentance();
		   player.play();
	   }
	   oShuffle.onclick = function (){    //随机播放图标被单击事件处理函数
		   oShuffle.style.display="none";
		   oLoop.style.display = "block";
		   player.playMode = 2;
		   player.buff(true,player.loadedIndexsList[player.loadedIndexsList.length-1]);
		   player.loadSentance();
		   player.play();
	   }
	   oLoop.onclick = function (){   //循环图标被单击事件处理函数
		   oLoop.style.display="none";
		   oOrder.style.display = "block";
		   player.playMode = 0;
		   player.buff(true,player.loadedIndexsList[player.loadedIndexsList.length-1]);
		   player.loadSentance();
		   player.play();
	   }
	   
	   oQueryButton.onclick = function(){   //查询页面 查询按钮被单击 事件处理函数，传入显示函数
		    oResultList.innerHTML="";
		    oResultNumber.innerHTML="";
			var word = myTrim(oQueryText.value);
			if(!word)
			{
				return false;
			}
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function(){
				if(xhr.readyState==4)
				{
					if((xhr.status>=200&&xhr.status<300)||xhr.status==304)
					{
						
						showQueryResult(xhr.responseText);
					}
					else
					{
						alert(xhr.status);
					}
				}
			}
			xhr.open('get','data_process_php/get_data.php?target=query_word&word='+word);
			xhr.send(null);
	   }
	   oQueryText.onkeydown = function (ev){
		   myEvent = ev || event ;
		   if(myEvent.keyCode==13)
		   {
			   oQueryButton.onclick();
			   oQuerySelectUl.style.display = "none";
			   
		   }
		   
		   
	   }
	   oQueryText.onkeyup = oQueryText.onclick = function(ev){
		   myEvent = ev || window.event;
		   getData( 'hint_english_query' , oQueryText.value , setQUeryList , oQuerySelectUl );
		   if(myEvent.keyCode!=13)
		   {
		       oQuerySelectUl.style.display = "block";
		   }
	   }
	   
	   oQueryText.onfocus = function(){
		   var word = (oQueryText.value) ? oQueryText.value : '' ;
		   getData( 'hint_english_query' , word , setQUeryList , oQuerySelectUl );
		   oQuerySelectUl.style.display = "block";
	   }
	   
	   for(var i=0, len = oQuerySecectList.length ; i<len ; i++)
	   {
		   oQuerySecectList[i].onclick = function(){
			  oQueryText.value = this.innerHTML ; 
			  oQueryButton.onclick();
		   }
	   }
	   oQueryText.onblur = function (){
		  setTimeout(function(){
			  oQuerySelectUl.style.display = "none";
		  },200);
	   }
	   
	   
	 
	   oAddListButton.onclick = function(){   //操作面板添加列表按钮打击事件处理函数
		   if(user_id<0)
		   {
			   showForm('log_in');
			   return false;
		   }
		   var oInput = document.createElement('input');
		   oInput.type = "text";
		   oInput.onkeydown = function(ev){
			   myEvent = ev || window.event ;
			   if(myEvent.keyCode == 13 )
			   {
				   oInput.onblur();
			   }
		   }
		   oInput.onblur = function(){
			   if(this.value)
			   {
				  var parts = new Array();
				  parts.push(encodeURIComponent('english_list_name')+"="+encodeURIComponent(this.value));
				  parts.push(encodeURIComponent('add_english_list')+"="+encodeURIComponent(''));
				  sendData(parts.join("&"));
				  getEnglishList();
 			   }
			   else
			   {
				   this.parentNode.parentNode.removeChild(this.parentNode);
				   oInput = null;
				   
			   }
		   }
		   var oLi = document.createElement('li');
		   oLi.appendChild(oInput);
		   oEnglishList.appendChild(oLi);
		   oInput.focus();
		   
	   }
	   oContentBox.onmousewheel = function(ev) {
		   myEvent = ev || event ;
		   var oScrollBox = this.children[0];
		   if(oScrollBox.offsetHeight > this.clientHeight)
		   {
			   var topNow = oScrollBox.offsetTop;
			   var topMin = this.clientHeight - oScrollBox.offsetHeight -200 ;
			   var topMax = 0;
			   topNow += (myEvent.wheelDelta/3);
			   if(topNow > topMax )
			   {
				   topNow = topMax ;
			   }
			   if(topNow < topMin)
			   {
				   topNow = topMin;
			   }
			   oScrollBox.style.top = topNow  + 'px';
			   
			   if(myEvent.preventDefault)
			   {
				   myEvent.preventDefault();
			   }
			   else
			   {
				   myEvent.returnValue = false;
			   }
		        
		   }

	   }
	   
	   oPlayTitleFlow.changeTitle = function(title){
		   var aDivs = this.getElementsByTagName('div');
		   aDivs[0].innerHTML = title;
		   aDivs[1].innerHTML = title;
		   
	   }
	   oPlayTitleFlow.timer = null;
	   oPlayTitleFlow.startMove = function(){
		   clearInterval(this.timer);
		   this.timer = setInterval(function(){
			   oPlayTitleFlow.style.left = oPlayTitleFlow.offsetLeft - 1 + 'px' ;
			   if(oPlayTitleFlow.offsetLeft< -189)
			   {
				   oPlayTitleFlow.style.left = 0 + 'px';
			   }
		   },20);
	   }
	   oPlayTitleFlow.movePause = function(){
		   clearInterval(this.timer);
	   }
	   oPlayTitleFlow.moveStop = function(){
		   clearInterval(this.timer);
		   this.style.left = 0 + 'px';
		   
	   }
	   oNowInfo.onclick = function(){
		   oPlayerFace.style.display = "block";
		   oPlayerFace.rate = 1;
		   oPlayerFace.timer=setInterval(function(){
			       oPlayerFace.rate *= 1.1 ;
			       oPlayerFace.style.width = parseInt(oPlayerFace.offsetWidth +  20*oPlayerFace.rate )+ 'px';
				   oPlayerFace.style.height = parseInt(oPlayerFace.offsetHeight + 14*oPlayerFace.rate) + 'px';
				   if(oPlayerFace.offsetWidth > 940  )
				   {
					   oPlayerFace.style.width = "960px";
					   oPlayerFace.style.height ="677px";
					   clearInterval(oPlayerFace.timer);
					  
				   }
			 },16);
		   
	   }
	   oHideFaceButton.onclick = function(){
		   oPlayerFace.timer=setInterval(function(){
			       oPlayerFace.style.width = parseInt(oPlayerFace.offsetWidth * 0.7 )+ 'px';
				   oPlayerFace.style.height = parseInt(oPlayerFace.offsetHeight * 0.7) + 'px';
				   if(oPlayerFace.offsetHeight <30 )
				   {
					   clearInterval(oPlayerFace.timer);
					   oPlayerFace.style.display = "none";
				   }
			   },30);
		  
	   }
	   for(i=0,len=aPlaySource.length; i< len; i++)
	   {
		   aPlaySource[i].onclick = function(){
			   var strSource = this.hash.slice(1);
			   sourceId = parseInt(strSource);
			   player.init();
			   player.sentanceList = player.sentanceListAll;
			   var temp = new Object();
			   temp.resource_id = sourceId;
			   temp.resource_name = player.getItemInfoAll(sourceId).resource_name;
			   player.items.push(temp);
			   player.seek(sourceId);
			   player.loadSentance();
			   player.auto = true ;
			   player.play();
		   }
	   }
//初始化//*************************************************************************
	  getUserInfo();
	  getEnglishList();
	  setMenuLi();
	  oMainMenu.getElementsByTagName('li')[0].click();
	  player.firstLoad();
	  oQueryText.value = "mental";
	  oQueryButton.click();
	  

//函数定义//****************************************************************************************************************************	  
	  function cover()   //定义听写封面被点击后的行为函数
	  {   
	      setTimeout( function(){
		               move(oCover,'top',0);
		  },1000);
	  }

	  
	  function createHtml(result)  //根据英语句子听写对比结果创建显示HTML内容
	  {
		  var html_con = '';
		  for( var i =0 ; i<result.length ; i++)
		  {
			  switch(result[i].status)
			  {
				  case 0:
					  html_con = html_con + '<span class="right">'+result[i].word+'</span>';
					  break;
				  case 1:
					  html_con = html_con + '<span class="wrong">'+result[i].word+'</span>';
					  break;
				  case 2:
					  html_con = html_con + '<span class="miss">('+result[i].word+')</span>';
					  break;
			  }
		  }
		  return html_con;
	  }
	  
	  function checkSentance(){     //对英语听写结果进行检查
		  oSentanceCheck.style.display="none";
		  oCover.style.display ="block";
		  var checkResult = new Array();
		  oCover.style.top ='0px';
		  if(oCover.timer)
		  {
			  clearInterval(oCover.timer);
		  }
		  var standardSentance = oSentance.innerHTML;
		  var mySentance = oDictationTextarea.value;
		  var standardWords = toWords(standardSentance);
		  var myWords = toWords(mySentance); 
		  if(myWords.length/standardWords.length < 0.8)
		  {
			  oCover.innerHTML="<p>——内容太少，点我提示——</p>";
		  }
		  else
		  {
			  oCover.style.display = "none";
			  for(var i =0 ; i < myWords.length ; i++)
			  {
				  
				  if(myWords[i]==standardWords[0])
				  {
					  var temp = new Object();
					  temp.word = myWords[i];
					  temp.status = 0;
					  standardWords.shift();
					  checkResult.push(temp);
				  }
				  else 
				  {
					  var index = standardWords.indexOf(myWords[i]);
					  if((index==-1)||(index>4))
					  {
						 var temp = new Object();
						 temp.word = myWords[i];
						 temp.status = 1;
						 checkResult.push(temp);
					  }
					  else
					  {
						  for(var j = 0 ;j< index ; j++)
						  {
							  var temp = new Object();
							  temp.word = standardWords.shift();
							  temp.status=2;
							  checkResult.push(temp);
						  }
						  var temp = new Object();
						  temp.word = myWords[i];
						  temp.status = 0;
						  standardWords.shift();
						  checkResult.push(temp);
					  }
				  }
			  }
			  for(var j = 0 ;j< standardWords.length ; j++)
			  {
				  var temp = new Object();
				  temp.word = standardWords[j];
				  temp.status=2;
				  checkResult.push(temp);
			  }
			  var html_con = createHtml(checkResult);
			  oSentanceCheck.getElementsByTagName('p')[0].innerHTML = html_con;
			  oSentanceCheck.style.display ="block";
			  processResult(checkResult);
			  
		  }
	  }
	  
	  function processResult(checkResult){   //对英语听写错误结果进行处理
		 var englishMistakes  = new Array();
		 var tag = 0;
		 var temp = null;
		 for(var i = 0 ;i < checkResult.length; i++)
		 {
			 
			 switch(checkResult[i].status)
			 {
				 case 0: 
					 if(tag==1)
					{
						var origin = temp.mistake_origin;
						temp.mistake_origin = origin.trim();
						var content = temp.mistake_content;
						temp.mistake_content = content.trim();
						if(temp.mistake_content&&temp.mistake_origin)
						{
							temp.mistake_type = 0;
						}
						else
						{
							if(temp.mistake_content)
							{
								temp.mistake_type = 1;
							}
							else
							{
								temp.mistake_type = 2;
							}
						}
						englishMistakes.push(temp);
						tag = 0;
					}
					break;
				 case  1:
					 if(tag==0)
					 {
						 tag = 1;
						 var temp = new Object();
						 temp.mistake_content = checkResult[i].word;
						 temp.mistake_origin = '';
						 temp.sentance_id = playedSentanceIds[playedSentanceIds.length-1];
					 }
					 else
					 {
						 temp.mistake_content = temp.mistake_content +' '+ checkResult[i].word;
					 }
					 break;
				  case 2:
					 if(tag==0)
					 {
						 tag = 1;
						 var temp = new Object();
						 temp.mistake_origin = checkResult[i].word;
						 temp.mistake_content = '';
						 temp.sentance_id = playedSentanceIds[playedSentanceIds.length-1];
					 }
					 else
					 {
						 temp.mistake_origin =  temp.mistake_origin+' '+checkResult[i].word;
					 }
					 break;
			}
		 }
		 var json = JSON.stringify(englishMistakes)
		 
		 sendData('upload_mistakes','mistakes_json',json);
	
	   }
	   
	   function setQUeryList(queryItems)
	   {
		   var hints = JSON.parse(queryItems);
		   var aLis = this.getElementsByTagName('li');
		   for(var i = 0 ; i < 5 ; i++ )
		   {
			   aLis[i].innerHTML = hints[i];
		   }
		   //alert(this);
	   }
	   
		function showQueryResult(response){  //根据查询结果，将查询结果显示在UL下的Li中
			 var results = eval(response)
			 oResultNumber.innerHTML="共搜索到"+results.length +"个结果";
			 for(var i = 0 ; i< results.length; i++)
			 {
				 var audio_src = '/AF/audio/english_sentances/'+results[i].sentance_audio_name;
				 var audio = '<audio src="'+audio_src+'"></audio>';
				 var sound = '<div class="sound"><img src="img/sound.png" >'+audio+'</div>';
				 var oLi = document.createElement('li');
				 var query = myTrim(oQueryText.value);
				 var reg = new RegExp(query+'[a-z]{0,}',"i");
				 var sentance = results[i].sentance_content;
				 var query2 = sentance.match(reg);
				 sentance = sentance.replace(reg,'<span class="query_content">'+query2+'</span>');
				 oLi.innerHTML = sound+'<div class="text">'+sentance+'</div>'  ;
				 oResultList.appendChild(oLi);
			 }
			 var aImgs = oResultList.getElementsByTagName('img');
			 for(var i = 0 ; i< aImgs.length; i++)
			 {
				 aImgs[i].onclick = function(){
					 var oAudio = this.nextSibling;
					 oAudio.play();
				 }
			 }
			 
		}
		
		
		 function getEnglishList()   //获取用户创建的英语播放列表信息
		 {
			  var xhr = new XMLHttpRequest();
			  xhr.onreadystatechange = function(){
				  if(xhr.readyState==4)
				  {
					  if((xhr.status>=200&&xhr.status<300)||xhr.status==304)
					  {
						  var regexp = /\S+/ ;
					      var newStr = xhr.responseText.match(regexp);
						  showEnglishList(newStr);
					  }
					  else
					  {
						  alert(xhr.status);
					  }
				  }
			  }
			  xhr.open('get','data_process_php/get_data.php?target=english_list&time='+sysDate.getTime());
			  xhr.send(null);
			 
		 }
		 function showEnglishList(listData)   //根据获取的用户英语列表创显示方式
		 {
			 oEnglishList.innerHTML="";
			 var oLi= document.createElement('li');
			 oLi.listIndex = 0;
			 oLi.listId = 30;
			 oLi.listName = "所有内容";
			 oLi.createDate ='2017-06-04';
			 oLi.innerHTML='所有内容<div class="tag"></div>';
			 oEnglishList.appendChild(oLi);
			 if(listData)
			 {
				 var userList = JSON.parse(listData);
				 for( var i =0, len = userList.length ; i < len ; i++ )
				 {
					 var oLi= document.createElement('li');
					 oLi.listIndex = (i+1);
					 oLi.listId = userList[i].list_id ;
					 oLi.listName = userList[i].list_name ;
					 oLi.createDate = userList[i].list_create_time;
					 oLi.innerHTML=userList[i].list_name+'<div class="tag"></div>';
					 oEnglishList.appendChild(oLi);
				 }
			 }
			 var aLis = oEnglishList.getElementsByTagName('li');
			 for(var i = 0 ,len= aLis.length ; i< len ; i++)
			 {
				 getData('english_list_items',aLis[i].listId,setList,aLis[i]);
			 }
			 
			 setMenuLi();
			  
		 }
		 function setList(data)
		 {
			 var items = JSON.parse(data);
			 this.listItems = items;
			 if(this.listIndex == player.listIndex)
			 {
				 this.click();
				 player.listIndex = -1;
			 }
			 
		 }
		 function setMenuLi() {
			     //对英语听写操作面板的按钮选项添加事件处理函数，切换选项卡
			 var aMenuLis = oMainMenu.getElementsByTagName('li');
			 for(var i =0 , len = aMenuLis.length ; i <len; i++)
			 {
				 aMenuLis[i].index = i;
				 aMenuLis[i].onclick = function(){
					 for(var j =0 , len = aMenuLis.length ; j <len; j++)
					 {
						 aMenuLis[j].className="";
					 }
					 this.className="selected";
					 switch(this.index)
					 {
						 case 0 :
						       changeContentById(oScrollBox,'classical_textbooks',null);
						       break;
						 case 1 :
						       changeContentById(oScrollBox,'empty_content',null);
						       break;
						 case 2 :
						       changeContentById(oScrollBox,'empty_content',null);
						       break;
						 case 3 :
						       changeContentById(oScrollBox,'empty_content',null);
						       break;
						 default:
						      changeContentById(oScrollBox,'user_list',showListInfo,this);
						      break;
						 
					 }
				 }
			 }
		 
		 }
		 
		 function changeContentById(parentBox,contentId,editFun,para){ //选项卡切换内容函数
			 for(var i =0 ,len= parentBox.children.length ; i< len; i++)
			 {
				 (parentBox.children)[i].style.display = "none";
			 }
			 var contentToShow = document.getElementById(contentId);
			 if(editFun)
			 editFun(contentToShow,para);
			 contentToShow.style.display ="block";
			 parentBox.style.top = "0px";
			 //parentBox.style.display = "block";
		 }
		 
		
		 
		 function showListInfo(listBox,oLi)   // 显示用户创建的列表，传入显示容器，用户列表信息保存在传入的Li中
		 {   
			 var listName = listBox.getElementsByTagName('h2')[0];
			 var oUserPortrait = listBox.getElementsByTagName('img')[0];
			 var oUserName = listBox.querySelector('.user_name');
			 var oCreateDate = listBox.querySelector('.create_date');
			 var oListTable = listBox.getElementsByTagName('table')[0];
			 var aButtons = listBox.getElementsByTagName('a');
			
			  listName.innerHTML = oLi.listName;
			  oCreateDate.innerHTML = oLi.createDate;
			  
			  aButtons[1].style.color = "#444";
			  aButtons[2].style.color = "#444";
			  
			   aButtons[0].onclick = function(){
				   player.setList(oLi.listItems);
				   player.loadSentance();
				   player.auto = true;
				   player.play();
			   }
			   aButtons[1].onclick = function (){
				   oSelectSentances.li = oLi;
				   showForm('select_sentances');
			   }
			   aButtons[2].onclick = function (){
				   
				   if(oLi.nextSibling)
				   {
					   oLi.nextSibling.click();
				   }
				   else
				   {
					   oLi.previousSibling.click();
				   }
				   oLi.parentNode.removeChild(oLi);
				   
				   var parts = new Array();
				   parts.push(encodeURIComponent('english_list_id')+"="+encodeURIComponent(oLi.listId));
				   parts.push(encodeURIComponent('del_english_list')+"="+encodeURIComponent(''));
				   sendData(parts.join("&"));
			   }
			   if(oLi.listId==30)
			   {
				   oUserName.innerHTML = "公子小白（管理员）";
				   oUserPortrait.style.top= "-79px";
				   oUserPortrait.style.left = "-94px";
				   if(user_id!=36)
				   {
					   aButtons[1].onclick = null;
					   aButtons[2].onclick = null;
					   aButtons[1].style.color = "#999";
					   aButtons[2].style.color = "#999";
				   }
			   }
			   else
			   {
					oUserName.innerHTML = user_name ;
					var top = parseInt(( portrait_id -1 ) / 3) *72+7;
					var left = (( portrait_id -1 )% 3 )*83+11;
					oUserPortrait.style.top = (-top )+ "px";
					oUserPortrait.style.left = (-left) + "-94px";
			   }
				  
				   

			 
			 showList();
			 function showList()  //根据获得的列表详细信息创建表格
			 {
				 oListTable.tBodies[0].innerHTML = "";
				 var listItems = oLi.listItems;
				 for(var i =0 , len = listItems.length; i< len; i++)
				 {
					 var oTr = document.createElement('tr');
					 oTr.resource_id = listItems[i].resource_id ;
					 oTr.itemId = listItems[i].item_id ;
					 var oTd = document.createElement('td');
					 oTd.innerHTML = listItems[i].resource_name ;
					 oTr.appendChild(oTd);
					 var oTd = document.createElement('td');
					 oTd.innerHTML = '<a href="#">播放</a>' ;
					 oTr.appendChild(oTd);
					 var oTd = document.createElement('td');
					 oTd.innerHTML = '<a href="#">听写</a>';
					 oTr.appendChild(oTd);
					 var oTd = document.createElement('td');
					 oTd.innerHTML = '<a href="#">移除</a>';
					 oTr.appendChild(oTd);
					 oListTable.tBodies[0].appendChild(oTr);
					 aButtons = oTr.getElementsByTagName('a');
					 aButtons[0].onclick = function(){
						 player.setList(oLi.listItems);
						 player.seek(this.parentNode.parentNode.resource_id);
						 player.loadSentance();
						 player.auto = true;
						 player.play();
					 };
					 aButtons[1].onclick = function(){
						 player.setList(oLi.listItems);
						 player.seek(this.parentNode.parentNode.resource_id);
						 player.auto = false;
						 player.locked = true;
						 oOperateBoard.style.display="none";
						 player.loadSentance();
						 player.play();
					 }
					 aButtons[2].onclick = function(){
						 var parts = new Array();
						 parts.push(encodeURIComponent('item_id')+"="+encodeURIComponent(this.parentNode.parentNode.itemId));
						 parts.push(encodeURIComponent('del_english_list_item')+"="+encodeURIComponent(''));
						 sendData(parts.join("&"));
						 this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
						 var resourceId = this.parentNode.parentNode.resource_id;
						 for(var i = 0 ; i < oLi.listItems.length ; i ++)
						 {
							 if(oLi.listItems[i].resource_id == resourceId)
							 {
								 oLi.listItems.splice(i,1);
							 }
						 }
					 };
					 if(((oLi.listId==30)&&(user_id!=36)))
					 {
						 aButtons[2].onclick = null;
						 aButtons[2].style.color = "#999";
					 }
					 
				 }
			 }
			 
		 }
	  
}




