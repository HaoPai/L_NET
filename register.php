<?php require_once('struct_php/global.php'); ?>
<!DOCTYPE html>
<html>
      <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
          <title>用户注册</title>
          <link rel="stylesheet" href="CSS/lnet.css">
          <link rel="stylesheet" href="CSS/register.css">
          <script type="text/javascript" src="JS/lnet.js"></script>
      </head>
      <body style="">
           <?php require_once('struct_php/top_nav.php');?>
           <div class="container" style=" position:relative; height:1200px; background: url(img/back051911.jpg) center ;">
                <div class="content">
                      <div id="register">
                                   <h2>用户注册</h2>
                                   <div class="register_info">
                                       
                                       <form id="user_register" method="post" >
                                        <table>                                                    
                                             
                                             <tr>
                                               <td class="input_name"><i>*</i>用户名：</td>
                                               <td>
                                                  <input type="text"  name="user_name" class="text"  required>
                                                </td>
                                                <td class="hint">字母，数字，下划线及汉字，不含有空格</td>
                                             </tr>
                                             <tr>
                                                 <td class="input_name"><i>*</i>邮箱：</td>
                                                 <td>
                                                    <input type="text" name="user_name" class="text" required>
                                                  </td>
                                                   <td class="hint">请输入邮箱</td>
                                              </tr>
                                              <tr>
                                                   <td class="input_name"><i>*</i>密码：</td>
                                                   <td>
                                                          <input type="password"  name="user_pwd" class="text pwd" required>
                                                   </td>
                                                    <td class="hint">6-20字符，不含有空格</td>
                                                </tr>
                                               <tr>
                                                   <td class="input_name"><i>*</i>确认密码：</td>
                                                   <td>
                                                          <input type="password"  name="user_pwd" class="text pwd" required>
                                                   </td>
                                                    <td class="hint">确认密码</td>
                                                </tr>
                                                <tr>
                                                   <td class="input_name"><i>*</i>滑动验证：</td>
                                                   <td>
                                                         <div class="slide_frame" ><div class="slide_button">|||</div> >>滑动到最右侧>></div>
                                                   </td>
                                                    <td class="hint"></td>
                                                </tr>
                                                <tr>
                                                   <td></td>
                                                   <td>
                                                           <input type="submit" value="注册" name="login" class="submit" >
                                                   </td>
                                                    <td ></td>
                                                </tr>
                                             
                                             
                                         </table>
                                        </form>
                                    
                                   </div>
                                   <div class="sucess">
                                           <h2>恭喜您注册成功！</h2>
                                           <p><a href="log_in.php">立即登录</a></p>
                                           
                                   </div>
                               
                       </div>
                       
                     
                </div>
           </div>
           
           <?php require_once('struct_php/footer.php');?>
           <script type="text/javascript">
		       
		       var oUserRegister = document.getElementById('user_register');
			   var oTable = oUserRegister.querySelector('table');
			   var oSlideFrame = oUserRegister.querySelector('.slide_frame');
			   var oSlideButton  = oUserRegister.querySelector('.slide_button');
			   var inputs = oUserRegister.getElementsByTagName('input');
			   var oRegister = document.getElementById('register');
			  var oSucess = oRegister.querySelector('.sucess');
			  var oInfo = oRegister.querySelector('.register_info');
			   
			   oUserRegister.onsubmit = function(ev){
				   var event = ev || window.event;
				   if(event.preventDefault)
				   {
				       event.preventDefault();
				   }
				   else
				   event.returnValue = false;
				   if(checkForm())
				   {
					  var data = new Array();
					  temp = encodeURIComponent('user_name')+ '='+ encodeURIComponent(inputs[0].value);
					  data.push(temp);
					  temp = encodeURIComponent('email')+ '='+ encodeURIComponent(inputs[1].value);
					  data.push(temp);
					  temp = encodeURIComponent('password')+ '='+ encodeURIComponent(inputs[2].value);
					  data.push(temp);
					  temp = encodeURIComponent('add_user')+ '='+ encodeURIComponent('');
					  data.push(temp);
					  var xhr = new XMLHttpRequest();
					  xhr.onreadystatechange = function(){
						  
						  if(xhr.readyState==4)
						  {
							  if(((xhr.status>=200)&&(xhr.status<300))||(xhr.status==304))
							  {
								  var result = JSON.parse(xhr.responseText)
								  switch(result.status)
								  {
									  case 0 :
										  oInfo.style.display="none";
										  oSucess.style.display = "block";
										  var user_name = inputs[0].value;
										  var oMessage = oSucess.querySelector('h2');
										  oMessage.innerHTML = user_name+',恭喜您已成功注册！';
										  break;
									   case 1 :
									      var oHint = oTable.tBodies[0].rows[0].cells[2];
										  oHint.style.background="";
									      oHint.innerHTML = "该用户名已经被注册";
										  oHint.style.color = "red";
										  inputs[0].style.border = "1px solid red";
										  break;
								  }
							  }
							  else
							  {
								  alert('错误，不能获取结果');
							  }
						  }
					  }
					  xhr.open('POST','../data_process_php/register_process.php',false);
					  xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
					  xhr.send(data.join('&'));
					}
			    }
				
			   
				
				 inputs[0].onblur = checkName;
				 inputs[0].onfocus = function(){
					 var oHint = oTable.tBodies[0].rows[0].cells[2];
					 this.style.border = "1px solid #ccc";
					 oHint.innerHTML = "字母，数字，下划线及汉字，不含有空格";
				     oHint.style.color = "#888" ;
					 oHint.style.background="";
					 
				 }
			   
			      inputs[1].onblur = checkEmail;
				  inputs[1].onfocus = function(){
					 var oHint = oTable.tBodies[0].rows[1].cells[2];
					 this.style.border = "1px solid #ccc";
					 oHint.innerHTML = "请输入邮箱";
				     oHint.style.color = "#888" ;
					 oHint.style.background="";
				 }
			   
			      inputs[2].onblur = checkPass;
				  inputs[2].onfocus = function(){
					 var oHint = oTable.tBodies[0].rows[2].cells[2];
					 this.style.border = "1px solid #ccc";
					 oHint.innerHTML = "6-20字符，不含有空格";
				     oHint.style.color = "#888" ;
					 oHint.style.background="";
				 }
				 
				   inputs[3].onblur = reCheckPass;
				   inputs[3].onfocus = function(){
					 var oHint = oTable.tBodies[0].rows[3].cells[2];
					 this.style.border = "1px solid #ccc";
					 oHint.innerHTML = "确认密码";
				     oHint.style.color = "#888" ;
					 oHint.style.background="";
				 }
				function checkForm(){
					var oForm = document.getElementById('user_register');
					if(!inputs[0].onblur())
					   return false;
					if(!inputs[1].onblur())
					   return false;
					if(!inputs[2].onblur())
					   return false;
					 if(!inputs[3].onblur())
					   return false;
					 if(!checkSlide())
					    return false;
					  return true ;
				}
				   
			   function checkName(){
				   var oHint = oTable.tBodies[0].rows[0].cells[2];
				   if(this.value.length<2)
				   {
					       oHint.innerHTML = "用户名至少包含2个字符";
						   oHint.style.color = "red";
						   this.style.border = "1px solid red";
						   return false;
				   }
				   this.value = myTrim(this.value);
				   var oText = this.value;
				   var regex = /^\S{2,14}$/;
				   if(regex.test(oText))
				   {
					   oHint.innerHTML = "";
					   oHint.style.background = "url(img/confirm2252555.png)  left no-repeat";
					   oHint.style.backgroundSize = "24px";
					   this.style.borderColor = "#ccc";
					   return true ;
				   }
				   else
				   {
					   
					   oHint.innerHTML = "用户名不含有空格";
					   oHint.style.color = "red";
                       oHint.style.background="";
					   return false;
				   }
			   }
				   
			      function checkEmail(){
					  var oHint = oTable.tBodies[0].rows[1].cells[2];
					  this.value = myTrim(this.value);
					  var oEmail = this.value;
					  var regex = /^[\w-]+@[\w-]+.[a-z]{2,8}$/ ;
					  if(regex.test(oEmail))
					  {
						 oHint.innerHTML = "";
						 oHint.style.background = "url(img/confirm2252555.png)  left no-repeat";
						 oHint.style.backgroundSize = "24px";
						 this.style.borderColor = "#ccc";
						 return true ; 
					  }
					  else
					  {
						  oHint.innerHTML = "不是合法的邮箱";
						  oHint.style.color = "red";
						  this.style.border = "1px solid red";
						  oHint.style.background = "";
						  return false;
					  }
				  }
				  function checkPass(){
					  var oHint = oTable.tBodies[0].rows[2].cells[2];
					  var oPass = this.value;
					  var regex = /^\S{6,20}$/;
					  if(regex.test(oPass))
					  {
						 oHint.innerHTML = "";
						 oHint.style.background = "url(img/confirm2252555.png)  left no-repeat";
						 oHint.style.backgroundSize = "24px";
						 this.style.borderColor = "#ccc";
						 return true ; 
					  }
					  else
					  {
						  oHint.innerHTML = "6-20位字符，不能含有空格";
						  oHint.style.color = "red";
						  this.style.border = "1px solid red";
						  oHint.style.background = "";
						  return false;
					  }
				  }
				  function reCheckPass(){
					  var oHint = oTable.tBodies[0].rows[3].cells[2];
					  if((this.value == inputs[2].value)&&(this.value))
					  {
						 oHint.innerHTML = "";
						 oHint.style.background = "url(img/confirm2252555.png)  left no-repeat";
						 oHint.style.backgroundSize = "24px";
						 this.style.borderColor = "#ccc";
						 return true ; 
					  }
					  else if(this.value)
					  {
						  oHint.innerHTML = "密码不一致";
						  oHint.style.color = "red";
						  this.style.border = "1px solid red";
						  oHint.style.background = "";
						  return false;
					  }
					  else
					  {
						  oHint.innerHTML = "密码不能为空";
						  oHint.style.color = "red";
						  this.style.border = "1px solid red";
						  oHint.style.background = "";
						  return false;
					  }
				  }
				    function checkSlide(){
						 var oHint = oTable.tBodies[0].rows[4].cells[2];
						 if(oSlideButton.offsetLeft == 235)
						 {
							   oHint.innerHTML = "";
							   oHint.style.background = "url(img/confirm2252555.png)  left no-repeat";
								oHint.style.backgroundSize = "24px";
							   return true;
						 }
						 else
						 {
							   oHint.innerHTML = "验证失败";
						       oHint.style.color = "red";
							   oHint.style.background = "";
						       return  false;
						 }
					}
			   
			   
			   
			   
			   oSlideButton.onmousedown =  function(ev){
				   inputs[3].blur();
				   var oEvent = ev|| event;
				   var corX = oEvent.clientX;
				   var left = oSlideButton.offsetLeft;
				   document.onmousemove = function(ev){
					   var oEvent = ev || event;
					   oSlideButton.style.left= oEvent.clientX - corX +left+ 'px';
					   if(oSlideButton.offsetLeft<1)
					   {
						   oSlideButton.style.left = "1px";
					   }
					   if(oSlideButton.offsetLeft>235)
					   {
						   oSlideButton.style.left = "235px";
					   }
					   
				   }
				   document.onmouseup = function(){
					   document.onmousemove = null;
					   document.onmouseup = null;
					   if(oSlideButton.offsetLeft==235)
					   {
						  
						   oSlideButton.onmousedown = oSlideFrame.onmousedown= function(){
							inputs[3].blur();
							return false;
					         }
					   }
					   else
					   {
						  move(oSlideButton,'left','1'); 
					   }
					   checkSlide();
				   }
				   return false;
				   
			    }
				oSlideButton.ontouchstart = function(){
					
					 var touch = event.targetTouches[0];
					 var corX = touch.clientX;
				     var left = oSlideButton.offsetLeft;
					 oUserRegister.ontouchmove = function(){
						 event.cancelBubble = true;
						 event.preventDefault();
						 var touch = event.targetTouches[0];
						 oSlideButton.style.left= touch.clientX - corX +left+ 'px';
						 if(oSlideButton.offsetLeft<1)
						 {
							 oSlideButton.style.left = "1px";
						 }
						 if(oSlideButton.offsetLeft>235)
						 {
							 oSlideButton.style.left = "235px";
						 }
						 
					  }
					  document.ontouchend = function(){
						  
						  oUserRegister.ontouchmove = null;
						  document.ontouchend = null;
						  if(oSlideButton.offsetLeft==235)
						   {
							  
							   oSlideButton.onmousedown = oSlideFrame.onmousedown= function(){
								inputs[3].blur();
								return false;
								 }
						   }
						   else
						   {
							  move(oSlideButton,'left','1'); 
							  
						   }
						   checkSlide();
						   
					   }  
					   
				 }
		         
				 function move(obj , property , dest){
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
						 }
					 },17);
				 }
				
		   
		           
		   </script>
           
           
      </body>
 </html>
   
