// JavaScript Document
// JavaScript Document


window.onload = function(){
	
////播放器*****************************************************************************	
	    var photoPlayer = new Object();
	        photoPlayer.photos = null;
			photoPlayer.index = 0;
			photoPlayer.photoIds = new Array();
			photoPlayer.buffer = new Array();
			photoPlayer.buff = function(){
			}
			photoPlayer.init = function() {
				this.index = 0 ;
				this.photoIds.length = 0;
				var parts = new Array();
				var aPhotos = document.querySelectorAll('.photo');
				for(var i = 0,len = aPhotos.length; i< len; i++)
				{
					var temp = aPhotos[i].querySelector('a').hash.substr(1);
					parts.push(encodeURIComponent('photo_ids[]')+ '=' + encodeURIComponent(temp) );
					this.photoIds.push(temp);
				}
				 var target = 'get_photo&' + parts.join('&');
				 getData( target , '',this.setPhotos,this);
			}
			photoPlayer.setPhotos = function(data){
				this.photos = JSON.parse(data);
				
			}
			photoPlayer.getPath = function(id){
				for(var i = 0 ,len = this.photos.length ; i < len ; i++)
				{
					if(this.photos[i].photo_id == id )
					   return this.photos[i].photo_path;
				}
			}
			photoPlayer.show = function(num){
				if(typeof num == "number")
				{
					if(num < 0 )
					{
						num= this.photos.length -1 ;
					}
					if(num  > (this.photos.length -1))
					{
						num = 0;
					}
					this.index = num ;
				}
				oShowBox.style.display = "none";
				oShowBox.style.opacity = 0;
				oPicFrame.innerHTML = "";
				var img = document.createElement('img');
				img.src = this.getPath(this.photoIds[this.index]);
				img.onload = function(){
					var rate = img.width / img.height ;
					if((rate>=1)&&(img.width > 800))
					{
						img.width = 800 ;
						img.height = parseInt(800/rate) ;
					}
					if((rate<1)&&(img.height >800))
					{
						img.height = 800 ;
						img.width = parseInt(800*rate);
					}
					img.onclick = function(){
						photoPlayer.next();
					}
					oPicFrame.innerHTML = "";
					oPicFrame.appendChild(img);
					oShowBox.style.width = img.width + 'px';
					oShowBox.style.height = img.height + 'px';
					oShowBox.myOpacity = 0;
					oShowBox.style.marginTop = parseInt((800 - img.height)/2) +'px';
					oShowBox.style.display = "block";
					var timer = setInterval(function(){
						if(oShowBox.myOpacity > 100)
						{
							oShowBox.style.opacity = 1;
							clearInterval(timer);
						}
						oShowBox.myOpacity += 10 ;
						oShowBox.style.opacity = oShowBox.myOpacity/100 ;
					},30);
				}
				
			}
			photoPlayer.next = function(){
				this.show(this.index + 1);
			}
			photoPlayer.prev = function(){
				this.show(this.index - 1);
			}
//////照片上传**********************************************************************************

var uploadFiles = new Array();

var albumUploadFile = new Object();

albumUploadFile.files = uploadFiles;
albumUploadFile.getProgress = function(){
	var parts = new Array();
	for(var i = 0 ; i < this.files.length ; i ++)
	{
		if((this.files[i].status == "finished")||(this.files[i].status == "mistake"))
		{
			continue;
		}
		parts.push(encodeURIComponent('file_names[]')+'='+encodeURIComponent(this.files[i].fileName));
	}
	parts.push(encodeURIComponent('name')+'='+encodeURIComponent('form'));
	parts.push(encodeURIComponent('album_upload')+'='+encodeURIComponent(''));
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function(){
		if(xhr.readyState==4)
		{
			if(((xhr.status >= 200)&&(xhr.status <300))||(xhr.status ==304))
			{
				albumUploadFile.setProgress.call(albumUploadFile,xhr.responseText);
				//alert(xhr.responseText);
			}
			else
			{
				alert('数据传输出错，停止上传！');
			}
		}
	}
	xhr.open('GET','data_process_php/album_progress.php?'+ parts.join('&')+'&time='+ sysDate.getTime(),true);
	xhr.send(null);
}
albumUploadFile.setProgress = function(progressData){
	var fileProgress = JSON.parse(progressData);
	for( var i = 0 ; i < this.files.length ; i++)
	{
		if((this.files[i].status == "finished")||(this.files[i].stauts=="mistake"))
		{
			continue;
		}
		else
		{
			var name = this.files[i].fileName ;
			
			//alert(fileProgress[name]['status']);
			switch(fileProgress[name]['status'])
			{
				case "finished":
				this.files[i].status = "finished";
				this.files[i].setStatus("上传完成",1);
				this.files[i].setImg(fileProgress[name]['path']);
				this.files[i].tempPath = fileProgress[name]['photo_path'];
				this.files[i].tempOriginPath = fileProgress[name]['photo_origin_path'];
				this.files[i].tempThumbPath = fileProgress[name]['photo_thumb_path'];
				this.files[i].progressBar.parentNode.style.display = "none";
				break;
				case "mistake":
				this.files[i].status = "mistake";
				this.files[i].setStatus('发生错误，'+fileProgress[name]['error'],0);
				this.files[i].statusDom.className = "file_status mistake";
				break;
				case "waiting":
				this.files[i].status = "waiting";
				this.files[i].setStatus('等待上传！',0);
				break;
				case "loading":
				this.files[i].status = "loading";
				this.files[i].setStatus('上传中！',fileProgress[name]['bytes_processed']/this.files[i].fileSize);
				break;
				case "processing":
				this.files[i].status = "processing";
				this.files[i].setStatus('数据处理中！',1);
				break;
			}
		}
	}
	var callTag  = false ;
	for( var i = 0 ; i < this.files.length ; i++)
	{
		if((this.files[i].status == "finished")||(this.files[i].stauts=="mistake"))
		{
			continue;
		}
		else
		{
			callTag = true;
			break;
		}
	}
	if(callTag)
	{
		setTimeout(function(){
		     albumUploadFile.getProgress();
		},500);
	}
	
	//alert(progressData);
}

function LoadingFile(name,size,dom){
	this.fileName = name;
	this.fileSize = size;
	this.finished = 0;
	this.status = "loading";
	this.fileDom = dom;
	
	this.imgDom = null;
	this.nameDom = null;
	this.statusDom = null;
	this.progressBar = null;
	
	this.tempPath = "";
	this.tempOriginPath = "";
	this.tempThumbPath = "";
}

LoadingFile.prototype.init = function()
{
	if(this.fileDom)
	{
		this.imgDom = this.fileDom.querySelector('.photo_img');
		this.nameDom = this.fileDom.querySelector('.file_name');
		this.statusDom = this.fileDom.querySelector('.file_status');
		this.progressBar = this.fileDom.querySelector('.progress_bar');
		
		this.nameDom.innerHTML = this.fileName.substr(0,15);
		return true;
	}
	else
	{
		return false;
	}
}

LoadingFile.prototype.setStatus = function( status ,rate){
	this.statusDom.innerHTML = status ;
	this.progressBar.style.width = parseInt(150*rate) + 'px';
}
    
LoadingFile.prototype.setImg = function(path){
	this.imgDom.src = path;
	this.imgDom.style.width = "160px";
}
   	   
	   
           

/////获取引用******************************************************************************************
	
	    var myselfLink = document.getElementById('myself');
		var oShowLayer = document.getElementById('show_layer');
		var oShowContent = document.querySelector('.show_content');
		var oQuit = document.querySelector('.quit');
		var aPhotos = document.querySelectorAll('.photo');
		var oShowBox = document.getElementById('show_box');
		var oPicFrame = oShowLayer.querySelector('.pic_frame');
		var prevButton = document.getElementById('prev_button');
		var nextButton = document.getElementById('next_button');
		
		var oCity = document.getElementById('city');
		var changeCityButton = document.getElementById('change_city');
		var oUserWord = document.getElementById('word');
		var changeWordButton = document.getElementById('change_word');
		
		var albumTitle = document.getElementById('album_title');
		var allAlbumsButton = albumTitle.getElementsByTagName('a')[0];
		var manageButton = albumTitle.getElementsByTagName('a')[1];
		var uploadButton = albumTitle.getElementsByTagName('a')[2];
		var finishButton = albumTitle.getElementsByTagName('a')[3];
		var selectButton =   albumTitle.getElementsByTagName('a')[4];
		var moveButton =   albumTitle.getElementsByTagName('a')[5];
		
		var delButtons = document.querySelectorAll('.del_photo');
		var checkButtons =  document.querySelectorAll('.check');
		
		
		
		var uploadPhotos = document.getElementById('upload_photos');
		var quitUploadButton = uploadPhotos.querySelector('.form_quit');
		var albumSelect = uploadPhotos.getElementsByTagName('select')[0];
		var photoFile = document.getElementById('photo_file');
		var selectPhotoButton = uploadPhotos.querySelector('.select_photo');
		var loadingFiles = document.getElementById('loading_files');
		var loadFormFoot = uploadPhotos.querySelector('.form_foot');
		
		var uploadComfirmButton = loadFormFoot.querySelectorAll('.button')[0];
		
		
		var moveToAlbum = document.getElementById('move_to_album');
		var quitMove = moveToAlbum.querySelector('.form_quit');
		var albumLinks = moveToAlbum.getElementsByTagName('a');
		
		

		
		
///////添加事件*****************************************************************************	
        myselfLink.onclick = function(){
			if(user_id<0)
			{
				showForm('log_in');
			}
		}
		for(var i = 0, len = aPhotos.length ; i <len ; i++)
		{
			aPhotos[i].index = i ;
			aPhotos[i].onclick = function (){
				var width = document.documentElement.clientWidth;
				var height = document.documentElement.clientHeight;
				var pageScroll =  document.documentElement.scrollTop || document.body.scrollTop ;
				oShowLayer.style.top = pageScroll + 'px';
				document.onmousewheel = function (ev){
					var myEvent = ev|| event;
					if(myEvent.preventDefault)
					    myEvent.preventDefault();
					 else
					    myEvent.returnValue = false;
				}
				document.ontouchmove = function(ev){
					var myEvent = ev || event;
					if(myEvent.preventDefault)
					    myEvent.preventDefault();
					 else
					    myEvent.returnValue = false;
				}
				oShowContent.style.left = parseInt((width - 960)/2)+'px';
				oShowContent.style.top =  parseInt((height - 800)/2)+'px';
				oShowLayer.style.display = "block";
				photoPlayer.show(this.index);
			}
		}
		oQuit.onclick = function (){
			oShowLayer.style.display = "none";
			document.documentElement.style.overflow = "scroll";
			document.ontouchmove = null;
			document.onmousewheel = null;
		}
		prevButton.onclick = function(){
			photoPlayer.prev();
		}
		nextButton.onclick = function(){
			photoPlayer.next();
		}
		window.onresize = function(){
			if(oShowLayer.style.display == "block")
			{
				var width = document.documentElement.clientWidth;
				var height = document.documentElement.clientHeight;
				oShowContent.style.left = parseInt((width - 960)/2)+'px';
				oShowContent.style.top =  parseInt((height - 800)/2)+'px';
			}
		}
		
//*********************************************
		changeCityButton.onclick = function(){
			var cityValue = oCity.innerHTML;
			oCity.innerHTML ="";
			var cityInput = document.createElement('input');
			cityInput.value = cityValue;
			cityInput.onblur = function(){
			     if((cityInput.value!=cityValue)&&(cityInput.value.length))
				 {
					 var parts = new Array();
					 parts.push(encodeURIComponent('user_id')+'='+encodeURIComponent(user_id));
					 parts.push(encodeURIComponent('city')+'='+encodeURIComponent(cityInput.value));
					 parts.push(encodeURIComponent('change_user_city')+'='+encodeURIComponent(''));
					 sendData(parts.join('&'));
				 }
				 cityInput.onblur = null ;
				 oCity.innerHTML = (cityInput.value)? cityInput.value : cityValue;
				 cityInput = null;
			}
			cityInput.onkeydown = function(ev)
			{
				var myEvent = ev || event ;
				if(myEvent.keyCode == 13 )
				{
					cityInput.onblur();
				}
			}
			oCity.appendChild(cityInput);
			cityInput.focus();
		}
		changeWordButton.onclick = function(){
			var wordValue = oUserWord.innerHTML;
			oUserWord.innerHTML ="";
			var wordInput = document.createElement('input');
			wordInput.value = wordValue;
			wordInput.onblur = function(){
			     if((wordInput.value!=wordValue)&&(wordInput.value.length))
				 {
					 var parts = new Array();
					 parts.push(encodeURIComponent('user_id')+'='+encodeURIComponent(user_id));
					 parts.push(encodeURIComponent('word')+'='+encodeURIComponent(wordInput.value));
					 parts.push(encodeURIComponent('change_user_word')+'='+encodeURIComponent(''));
					 sendData(parts.join('&'));
				 }
				 wordInput.onblur = null ;
				 oUserWord.innerHTML = (wordInput.value)? wordInput.value : wordValue;
				 wordInput = null;
			}
			wordInput.onkeydown = function(ev)
			{
				var myEvent = ev || event ;
				if(myEvent.keyCode == 13 )
				{
					wordInput.onblur();
				}
			}
			oUserWord.appendChild(wordInput);
			wordInput.focus();
		}
		
		uploadButton.onclick = function(){
			showForm('upload_photos');
			uploadPhotos.style.top = "5%";
		}
        quitUploadButton.onclick = function(){
			hideForm('upload_photos');
		}
		photoFile.onchange = function(){
			
			this.form.submit();
			loadingFiles.style.display = "block";
			loadFormFoot.style.display = "block";
			selectPhotoButton.style.display ="none";
			if((this.files)&&(this.files.length>1))
			{
				if(this.files.length>20)
				{
					var num = 20;
					alert('单次最多上传20个文件，已经忽略超出部分！');
				}
				else
				{
					var num = this.files.length;
				}
				for(var i =0; i< num; i++)
				{
					var fileDom = document.createElement('li');
					fileDom.className="loading_file";
					fileDom.innerHTML = '<img class="photo_img" src=""><div class="message_box"><div>文件:<span class="file_name"></span></div><div>状态：<span class="file_status"></span></div> </div> <div class="progress"><div class="progress_bar"></div></div>';
					//alert(this.files[i].size);
					var file = new  LoadingFile(this.files[i].name,this.files[i].size,fileDom);
					file.init();
					uploadFiles.push(file);
					loadingFiles.appendChild(fileDom);
				}
			}
			else
			{
				var fileDom = document.createElement('li');
				fileDom.className="loading_file";
				fileDom.innerHTML = '<img class="photo_img" src=""><div class="message_box"><div>文件:<span class="file_name"></span></div><div>状态：<span class="file_status"></span></div> </div> <div class="progress"><div class="progress_bar"></div></div>';
				if(this.files)
				{
					var fileName = this.files[0].name;
					var fileSize = this.files[0].size;
				}
				else
				{
					var fileName = this.value;
					var fileSize = 10;
				}
				var file = new  LoadingFile(fileName,fileSize,fileDom);
				file.init();
				uploadFiles.push(file);
				loadingFiles.appendChild(fileDom);
			}
			
			 albumUploadFile.getProgress();
		
		}
		uploadComfirmButton.onclick = function(){
			var prepareTag = true;
			for( var i = 0 ; i < albumUploadFile.files.length ; i++)
			{
				if((albumUploadFile.files[i].status == "finished")||(albumUploadFile.files[i].status=="mistake"))
				{
					continue;
				}
				else
				{
					prepareTag = false;
					break;
				}
			}
			if(prepareTag)
			{
				var photos = new Array();
				for( var i = 0 ; i < albumUploadFile.files.length ; i++)
				{
					if(albumUploadFile.files[i].status == "finished")
					{
						var temp = new Object();
						temp['path'] = albumUploadFile.files[i].tempPath;
						temp['origin_path'] = albumUploadFile.files[i].tempOriginPath;
						temp['thumb_path'] = albumUploadFile.files[i].tempThumbPath;
						photos.push(temp);
					}
					
				}
				var photosJson = JSON.stringify(photos);
				var parts = new Array();
				parts.push(encodeURIComponent('photos_json')+'='+encodeURIComponent(photosJson));
				parts.push(encodeURIComponent('album_id')+'='+encodeURIComponent(albumSelect.value));
				parts.push(encodeURIComponent('add_photos')+'='+encodeURIComponent(''));
				var xhr = new XMLHttpRequest();
				xhr.onreadystatechange = function(){
					if(xhr.readyState == 4 )
					{
						window.location.reload();
					}
				}
				xhr.open('POST','data_process_php/album_data_process.php',true);
				xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
				xhr.send(parts.join('&'));
				loadingFiles.innerHTML = "";
				selectPhotoButton.style.display = "block";
				loadFormFoot.style.display = "none";
				uploadPhotos.style.display = "none";
			}
			else
			{
				alert('请等待上传完成！');
			}
		}
		manageButton.onclick = function(){
			allAlbumsButton.style.display = "none";
			manageButton.style.display = "none";
			uploadButton.style.display = "none";
			finishButton.style.display = "block";
			selectButton.style.display = "block";
			for(var i = 0, len = aPhotos.length ; i <len ; i++)
		    {
				var delPhoto = aPhotos[i].querySelector('.del_photo');
				delPhoto.style.display = "block";
		    }
			
		}
		finishButton.onclick = function(){
			allAlbumsButton.style.display = "block";
			manageButton.style.display = "block";
			uploadButton.style.display = "block";
			finishButton.style.display = "none";
			selectButton.style.display = "none";
			moveButton.style.display = "none";
			for(var i = 0, len = aPhotos.length ; i <len ; i++)
		    {
				var delPhoto = aPhotos[i].querySelector('.del_photo');
				delPhoto.style.display = "none";
				var chedckButton = aPhotos[i].querySelector('.check');
				chedckButton.style.display = "none";
		    }
		}
		selectButton.onclick = function(){
			selectButton.style.display ="none";
			moveButton.style.display = "block";
			for(var i = 0, len = aPhotos.length ; i <len ; i++)
		    {
				var delPhoto = aPhotos[i].querySelector('.del_photo');
				delPhoto.style.display = "none";
				var chedckButton = aPhotos[i].querySelector('.check');
				chedckButton.style.display = "block";
		    }
			
		}
		moveButton.onclick = function(){
			showForm('move_to_album');
		}
		for(var i = 0; i< delButtons.length ; i ++)
		{
			delButtons[i].onclick = function(ev){
				myEvent = ev || event;
				if(myEvent.stopPropagation)
				{
					myEvent.stopPropagation();
				}
				else
				{
					myEvent.cancelBubble = true;
				}
				var photoDom = this.parentNode;
				photoDom.parentNode.removeChild(photoDom);
				aPhotos = document.querySelectorAll('.photo');
				var num = this.hash.substr(1);
				var parts  = new Array();
				parts.push(encodeURIComponent('photo_id')+'='+encodeURIComponent(num));
				parts.push(encodeURIComponent('del_photo')+'='+encodeURIComponent(''));
				sendData(parts.join('&'));
			}
		}
		for(var i = 0; i< checkButtons.length ; i ++)
		{
			checkButtons[i].onclick = function(ev){
				myEvent = ev || event;
				if(myEvent.stopPropagation)
				{
					myEvent.stopPropagation();
				}
				else
				{
					myEvent.cancelBubble = true;
				}
				var checkBox = this.getElementsByTagName('input')[0];
				if(checkBox.checked == true)
				{
					checkBox.checked = false;
					this.style.backgroundImage = "none";
				}
				else
				{
					checkBox.checked = true;
					this.style.backgroundImage = "url(../img/ico/check.png)";
					this.style.backgroundSize = "14px 14px";
				}
				
			}
		}
		quitMove.onclick = function(){
			hideForm('move_to_album');
		}
		for(var i = 0; i < albumLinks.length ; i ++ )
		{
			albumLinks[i].onclick = function(){
				var parts = new Array();
				for(var j = 0, len = aPhotos.length ; j <len ; j++)
				{
					var checkBox = aPhotos[j].getElementsByTagName('input')[0];
					if(checkBox.checked == true)
					{
						parts.push(encodeURIComponent('photo_ids[]')+'='+encodeURIComponent(checkBox.value));
						aPhotos[j].parentNode.removeChild(aPhotos[j]);
					}
				}
				aPhotos = document.querySelectorAll('.photo');
				var num = this.hash.substr(1);
				parts.push(encodeURIComponent('album_id')+'='+encodeURIComponent(num));
				parts.push(encodeURIComponent('move_to_album')+'='+encodeURIComponent(''));
				sendData(parts.join('&'));
				hideForm('move_to_album');
				
			}
		}
		
//初始化********************************************************************************************
        getUserInfo();
		photoPlayer.init();







// 定义函数***********************************************************************************

       function getUploadProgress(files){
		   
	   }


};