// JavaScript Document
window.onload = function(){
	var myselfLink = document.getElementById('myself');
	
	var oCity = document.getElementById('city');
	var changeCityButton = document.getElementById('change_city');
	var oUserWord = document.getElementById('word');
	var changeWordButton = document.getElementById('change_word');
		
	var albumsTitle = document.getElementById('albums_title');
	var manageAlbumButton = albumsTitle.getElementsByTagName('a')[0];
	var createAlbumButton = albumsTitle.getElementsByTagName('a')[1];
	var finishAlbumButton = albumsTitle.getElementsByTagName('a')[2];
	var createAlbumForm = document.getElementById('create_album');
	var createAlbumSubmit = document.getElementById('create_album_submit');
	var delAlbumButtons = document.querySelectorAll('.del_album');
	var quitCreateButton = createAlbumForm.querySelector('.quit_form');
	var grayLayer = document.getElementById('gray_layer');
	
	myselfLink.onclick = function(){
		if(user_id < 0)
		{
			showForm('log_in');
		}
	}
	
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
	
	
	
	
	
	manageAlbumButton.onclick = function(){
		manageAlbumButton.style.display ="none";
		createAlbumButton.style.display = "none";
		finishAlbumButton.style.display ="inline-block";
		for(var i = 0 ,len = delAlbumButtons.length; i < len ;i++)
	    {
			delAlbumButtons[i].style.display = "block";
		}
		
	}
	createAlbumButton.onclick = function(){
		showForm('create_album');
	}
	finishAlbumButton.onclick = function(){
		manageAlbumButton.style.display ="block";
		createAlbumButton.style.display = "block";
		finishAlbumButton.style.display ="none";
		for(var i = 0 ,len = delAlbumButtons.length; i < len ;i++)
	    {
			delAlbumButtons[i].style.display = "none";
		}
	}
	quitCreateButton.onclick = function(){
		hideForm('create_album');
	}
	createAlbumSubmit.onclick = function(ev){
		var myEvent = ev || event;
		if(myEvent.preventDefault)
		{
			myEvent.preventDefault();
		}
		else
		{
			myEvent.returnValue = false;
		}
		var albumName = document.getElementById('album_name');
		var albumDescription = document.getElementById('album_description');
		if(albumName.value)
		{
			var parts = new Array();
			parts.push(encodeURIComponent('album_name')+'='+encodeURIComponent(albumName.value));
			parts.push(encodeURIComponent('album_description')+'='+encodeURIComponent(albumDescription.value));
			parts.push(encodeURIComponent('create_album')+'='+encodeURIComponent(''));
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function(){
				if(xhr.readyState==4)
				{
					if(((xhr.status>=200)&&(xhr.status<300))||(xhr.status==304))
					{
						window.location.reload();
						hideForm('create_album');
						
					}
					else
					{
						alert('数据出错！');
						hideForm('create_album');
					}
				}
			}
			xhr.open('POST','../data_process_php/photos_home_process.php',true);
			xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xhr.send(parts.join('&'));
		}
		
		
	}
	for(var i = 0 ,len = delAlbumButtons.length; i < len ;i++)
	{
		delAlbumButtons[i].onclick = function(){
			var num = this.hash.substr(1);
			var album = this.parentNode;
			album.parentNode.removeChild(album);
			var parts = new Array();
			parts.push(encodeURIComponent('album_id')+'='+encodeURIComponent(num));
			parts.push(encodeURIComponent('del_album')+'='+encodeURIComponent(''));
			sendData(parts.join('&'));
		}
	}
	//********************************************************
	getUserInfo();
}