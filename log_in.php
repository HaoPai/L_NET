<?php require_once('struct_php/global.php'); ?>
<?php require_once('data_process_php/log_in_process.php'); ?>
<!DOCTYPE html>
<html>
      <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
          <title>用户登录</title>
          <link rel="stylesheet" href="CSS/lnet.css">
          <style>
		         #log_in { margin-left:400px;  position:absolute; top:400px; background-color:transparent;  }
				 #log_in .back_layer { background-color:#666; opacity:0.4; position:absolute ; z-index:1; top:0px; left:0px; width:500px; height:400px; }
				 #log_in .form_content { position:absolute; z-index:100; }
				 #log_in h2 { color:#ddd;}
				 #log_in .text { background-color:#fff;}
		  </style>
          <script type="text/javascript" src="JS/lnet.js"></script>
      </head>
      <body style="">
           <?php require_once('struct_php/top_nav.php');?>
           <div class="container" style="height:1200px; background:url(img/back051911.jpg) center center;">
           <div class="content">
              <?php require_once('struct_php/log_in_form.php');?>
           </div>
           </div>
           <?php require_once('struct_php/footer.php');?>
           <script type="text/javascript">
		         var oDiv = document.getElementById('log_in');
				 oDiv.style.display= 'block';
				 var oQuitLogin = document.getElementById('quit_login');
				 oQuitLogin.style.display='none';
		   </script>
           
      </body>
 </html>
   
