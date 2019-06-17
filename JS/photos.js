// JavaScript Document

window.onload = function(){
	var oMyself = document.getElementById('myself');
	
	
	oMyself.onclick = function(){
		if(user_id >0)
		{
		    window.location.href = 'photos_home_page.php'; 
		}
		else
		{
			showForm('log_in');
		}
	}
	
	
	getUserInfo();
}