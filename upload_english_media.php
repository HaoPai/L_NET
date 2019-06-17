<?php require_once('struct_php/global.php'); ?>
<!DOCTYPE html>
<html>
      <head>
            <title>英语资源上传</title>
            <style type="text/css">
			         body {padding-left:50px; }
					 td { padding:10px;}
					 input { width:100px; height:32px; margin:20px 0px; }
					 select { width:250px; height:32px; font-size:1em; }
					 textarea { width:600px; height:400px; }
					 h3 {color:red; font-weight:normal;}
					 
					 #english_nav { padding:5px 0px;margin:50px 0px;border-bottom:1px solid #ccc ;}
					 #english_nav li { padding:5px 10px; margin:0px 40px 0px 0px; display:inline; }
					 
					 #english_nav a { border:none ; color:#666; font-size:1.2em; text-decoration:none;}
					 
					 
			</style>
      </head>
      <body>
             <?php require_once('struct_php/english_sys_nav.php'); ?>
             <h2>请输入资源内容：</h2>
             <h3>请谨慎操作，输入非法数据会破坏原有结构！</h3>
             <form id="upload_media">
                   <table>
                          <tr>
                                <td>请选择资源:</td><td>
                                 <select id="resource_id">
<?php
                                 $query = "select * from english_resource where resource_id = 291  order by resource_id desc ";
								 $result = mysql_query($query,$dbc);
								 while($row = mysql_fetch_array($result))
								 {
									 echo '<option id="'.$row['resource_type'].'" value = "'.$row['resource_id'].'">'.$row['resource_name'].'</option>';
								 }
?>
                                </select></td>
                          </tr>
                          <tr>
                                <td>请输入欲添加内容：</td><td><textarea id="input_words"></textarea></td>
                           </tr>
                           <tr>
                                 <td></td><td><input type="submit" value="提交"/></td>
                           </tr>
                   </table>
             </form>
             <div id="response">
             </div>
             <script type="text/javascript">
			        var table = document.getElementById("upload_media");
					var resource_id = document.getElementById("resource_id");
					var text = document.getElementById("input_words");
					table.onsubmit = function (event){
/////////////////////////////////////////////////////////////////////////
					
///////////////////////////////////////////////////////////////////////////
						var index = resource_id.selectedIndex;
						var resource_type = resource_id.options[index].id;
						if(resource_type ==2 || resource_type == 3)
						{
						   var pattern = /[a-zA-Z0-9]+:[^:]+<>/gi;
						}
						if(resource_type == 1 || resource_type == 4 || resource_type == 5)
						   var pattern = /"{0,}[a-z]{1}[^.?!]{1,}[.?!]"{0,}/gi;
						var ign1 = /\[.{0,}\]/gi ;
						var ign2 = /\(.{0,}\)/gi;
						var input = text.value;
						input = input.replace(ign1," ");
						input = input.replace(ign2," ");
						input = input.replace(/.*<delete>.*/gi," ");
						input = input.replace(/'/g,'"');
						input = input.replace(/Mr\W+/g,'Mr');
						input = input.replace(/Mrs\W+/g,'Mrs');
						input = input.replace(/U\.N\./g,'UN');
						input = input.replace(/U\.S\./g,'US');
						input = input.replace(/\n\r/g,' ');
						input = input.replace(/'/g,'"');
						input = input.replace(/'/g,'"');
						input = input.replace(/[.]{3}/g,',');

////////////////////////////////////////////////////////////////////teset
						if(resource_type == 2 || resource_type == 3)
						{
							  var pattern = /\b[a-zA-Z]+[^a-zA-Z:]*:/gi;
							  var out = input.match(pattern);
							  out.sort();
							  var name = new Array();
							  name.push(out[0]);
							  for(var i = 1; i < out.length;i++)
							  {
								  if(name[name.length-1]!=out[i])
									  name.push(out[i]);
							  }
							  for(var i = 0; i< name.length; i++){
								  var reg = new RegExp(name[i],"g");
								  name[i]=name[i].replace(/[^a-zA-Z0-9:]/gi,'');
								  input = input.replace(reg,'<>'+name[i]);
							  }
							  input+='<>';
							  var pattern = /[a-zA-Z0-9]+:[^:]+<>/gi;
							  var result = input.match(pattern);
							  for(var i=0;i<result.length;i++)
							  {
								  result[i] = result[i].replace(/<>/g,' ');
								  result[i] = result[i].replace(/\n/g,' ');
								  result[i] = result[i].replace(/[a-z]+\s*$/i,' ');
							  }
							  var out = result;
						}
						else
						{
							var out = input.match(pattern);
						}
						
///////////////////////////////////////////////////////////////////
						
						
						var parts = [];
						parts.push(encodeURIComponent("add_sentances")+"="+ "");
						parts.push(encodeURIComponent("resource_id")+"="+ encodeURIComponent(resource_id.value));
						for( var  i = 0 ; i < out.length ; i++)
						{
							parts.push(encodeURIComponent("sentances[]")+"="+ encodeURIComponent(out[i]));
						}
						var  xhr = new XMLHttpRequest();
						xhr.onreadystatechange = function(){
							if(xhr.readyState == 4)
							{
								if(xhr.status < 200 || xhr.status >=300)
								alert("数据上传出错！");
								else
								{
								    text.value = "";
									var response = document.getElementById('response');
									response.innerHTML = xhr.responseText;
								}
								
							}
						}
						xhr.open("POST","data_process_php/receive_data.php",true);
						xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
						xhr.send(parts.join("&"));
						event.preventDefault();
						
					}
			 </script>
      </body>
</html>

<?php mysql_close($dbc); ?>