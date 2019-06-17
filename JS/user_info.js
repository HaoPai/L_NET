// JavaScript Document

window.onload = function(){
//////头像上传插件**********************************************************************************
   var portraitLoader = Object();
   portraitLoader.messageBox = null;
   portraitLoader.imgSource = null;
   portraitLoader.imgFinal = null;
   portraitLoader.fileName = "";
   portraitLoader.fileStatus = "";
   portraitLoader.progressBar = null ;
   portraitLoader.selectButton = null;
   portraitLoader.fileInput = null;
   portraitLoader.timer = null;
   portraitLoader.loadEnd = false;
   portraitLoader.maxProgressValue = 0 ;
   portraitLoader.imgPath ="";
   portraitLoader.init = function(Box,Name,Status,Img,Final,Bar,maxValue,Button,Input){
	   this.messageBox = Box;
	   this.fileName= Name;
	   this.fileStatus = Status;
	   this.imgSource = Img;
	   this.imgFinal = Final;
	   this.progressBar = Bar;
	   this.maxProgressValue = maxValue;
	   this.selectButton = Button;
	   this.fileInput = Input;
	   
	   
	   this.fileInput.onchange = function(){
			 portraitLoader.fileName.innerHTML = portraitLoader.fileInput.value;
			 portraitLoader.fileInput.form.submit();
			 portraitLoader.getProgress();
			 portraitLoader.selectButton.style.display = "none";
			 portraitLoader.messageBox.style.display = "block";
			 portraitLoader.progressBar.parentNode.style.display = "block";
       }
   }
   
   portraitLoader.getProgress = function(){
	   this.timer = setTimeout(function(){
		   getProgress('portrait_file','portrait',portraitLoader.succed,portraitLoader.failed,portraitLoader.loading,portraitLoader);
	   },500);
   }
   
   portraitLoader.loading = function(rate){
	   var wid =  Math.floor( this.maxProgressValue * rate / 100 );
	   this.progressBar.style.width = wid + 'px' ;
	   this.fileStatus.innerHTML = "上传中....";
	   this.getProgress();
   }
   portraitLoader.succed = function(path){
	   this.progressBar.style.width = '360px' ;
	   this.messageBox.style.display = "none";
	   this.imgSource.src = path;
	   this.imgFinal.src = path;
	   this.imgPath =path;
	   this.imgFinal.style.width = "120px";
	   this.imgSource.style.display = "block";
	   this.progressBar.parentNode.style.display = "none";
	   
   }
   portraitLoader.failed = function(error){
	   this.progressBar.width = '0px' ;
	   this.progressBar.parentNode.border = "1px solid red";
	   this.fileStatus.innerHTML = '失败！'+error;
	   alert(error);
	   this.messageBox.style.display = "block";
	   this.loadEnd = true;
   }
  
 //获取引用******************************************************************************************************
	   
	var oBasicInfo = document.getElementById('basic_info');
	var oChangeBasicButton = oBasicInfo.getElementsByTagName('button')[0];
	var oSaveBasicButton = oBasicInfo.getElementsByTagName('button')[0];
	var oBasicTable = oBasicInfo.getElementsByTagName('table')[0];
	var oPortrait = oBasicInfo.getElementsByTagName('img')[0];
	var oSelectPortrait = document.getElementById('select_portrait');
	 oSelectPortrait.portraitId = -1 ;
	var aPortraitList = oSelectPortrait.querySelectorAll('.portrait_frame');
	
	var oPortraitSystem = oSelectPortrait.querySelector('.portrait_list');
	var oUploadPortrait = document.getElementById('upload_portrait');
	
	var oSetPortraitButton = oSelectPortrait.getElementsByTagName('button')[0];
	var oResetButton = oSelectPortrait.getElementsByTagName('button')[1];
	var portraitTab = document.querySelector('.portrait_tab');
	
	var aButtons = portraitTab.getElementsByTagName('span');
	var portraitImg = document.getElementById('portrait_img');
	var portraitButton = oSelectPortrait.querySelector('.portrait_button');
	var portraitName = oSelectPortrait.querySelector('.file_name');
	var portraitMessage = document.getElementById('portrait_message');
	var portraitStatus = oSelectPortrait.querySelector('.file_status');
	var progressBar = document.getElementById('progress_bar');
	var portraitInput = document.getElementById('portrait_file');
	var portraitFinal = document.getElementById('portrait_final');
	
	
	for(var i = 0 , len = aPortraitList.length ; i < len ; i++)
	{
		aPortraitList[i].index = i ;
		aPortraitList[i].onclick = function(){
			for(var i = 0 , len = aPortraitList.length ; i < len ; i++)
	        {
				aPortraitList[i].className = "portrait_frame";
			}
			this.className = "portrait_frame selected";
			oSelectPortrait.portraitId = this.index + 1 ;
		}
	}
	
	
	oSetPortraitButton.onclick = function (){
		if(aButtons[0].className=="selected")
		{
			var myDate = new Date();
			var id = oSelectPortrait.portraitId ;
			if(user_id&&(id > 0))
			{
				var xhr = new XMLHttpRequest();
				xhr.open('GET','../data_process_php/get_data.php?target=set_portrait_id&portrait_id='+id+'&time='+myDate.getTime(),false);
				xhr.send(null);
			}
			if(id>0)
			{
			    changePortrait(id);
			}
		}
		else
		{
			if(portraitLoader.imgSource.src)
			{
			   var parts = new Array();
			   parts.push(encodeURIComponent('path')+'='+encodeURIComponent(portraitLoader.imgPath));
			   parts.push(encodeURIComponent('set_portrait')+'='+encodeURIComponent(''));
			   sendData(parts.join('&'));
			}
		}
		hideForm('select_portrait');
	}
	
	oPortrait.onclick = function (){
		showForm('select_portrait');
	}
	
    aButtons[0].onclick = function(){
		var siblings = this.parentNode.children;
		siblings[1].className="";
		oUploadPortrait.style.display = "none";
		oResetButton.style.display="none";
		oPortraitSystem.style.display = "block";
		this.className = "selected";
	}
	 aButtons[1].onclick = function(){
		 var siblings = this.parentNode.children;
		siblings[0].className="";
		oUploadPortrait.style.display = "block";
		oResetButton.style.display="inline-block";
		oPortraitSystem.style.display = "none";
		this.className = "selected";
	}
	oResetButton.onclick = function(){
		portraitImg.style.display="none";
		portraitImg.src="";
		portraitFinal.src = "";
		portraitButton.style.display="block";
		portraitMessage.style.display ="none";
		progressBar.parentNode.style.display="none";
		portraitStatus.innerHTML = "";
		portraitLoader.imgPath="";
		
	}
/////*************************************************
   portraitLoader.init(portraitMessage,portraitName,portraitStatus,portraitImg,portraitFinal,progressBar,360,portraitButton,portraitInput);





///*************************************************
	
	function changePortrait(num)
	{
		var top = parseInt((num -1) / 3)*144+13;
		var left = parseInt((num -1 )% 3)*166+21;
		oPortrait.style.top = -top+"px";
		oPortrait.style.left = -left+"px";
		
	}
}