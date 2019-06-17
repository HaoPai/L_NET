<!DOCTYPE html>
<html>
      <head>
            <title>英语标准库单词上传</title>
            <style type="text/css">
			         body {padding-left:50px; }
					 td { padding:10px;}
					 input { width:100px; height:32px; margin:20px 0px; }
					 select { width:150px; height:32px; font-size:1em; }
					 textarea { width:600px; height:400px; }
					 h3 {color:red; font-weight:normal;}
			</style>
      </head>
      <body>
             <h2>请输入单词内容：</h2>
             <h3>请谨慎操作，输入非法数据会破坏原有结构！</h3>
             <form id="upload_words">
                   <table>
                          <tr>
                                <td>请选择词频级别:</td><td><select id="word_rate"><option value="5">柯林斯5星</option>
                                                                <option value="4">柯林斯4星</option>
                                                                <option value="3">柯林斯3星</option>
                                                                <option value="2">柯林斯2星</option>
                                                                <option value="1">柯林斯1星</option>
                                                                <option value="0">柯林斯0星</option>
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
             <script type="text/javascript">
			        var table = document.getElementById("upload_words");
					var rate = document.getElementById("word_rate");
					var text = document.getElementById("input_words");
					table.onsubmit = function (event){
						var ign1 = /\[.{0,}\]/gi ;
						var ign2 = /\(.{0,}\)/gi;
						var ign3 = /\d+.*\n/gi; 
						var ign4 = /\n/gi;
						var ign5 = /\s{2,}/gi;
						var ign6 = /\'/gi;
						var input = text.value;
						input = input.replace(ign1," ");
						input = input.replace(ign2," ");
						input = input.replace(ign3," ");
						input = input.replace(ign4," ");
						input = input.replace(ign5," ");
						input = input.replace(ign6,"\\'");
						var parts = [];
						parts.push(encodeURIComponent("english_analysis_material")+"="+ "");
						//parts.push(encodeURIComponent("collins_rate")+"="+ encodeURIComponent(rate.value));
						parts.push(encodeURIComponent("content")+"="+ encodeURIComponent(input));
						parts.push(encodeURIComponent("material_id")+"="+ encodeURIComponent("14"));
						var  xhr = new XMLHttpRequest();
						xhr.onreadystatechange = function(){
							if(xhr.readyState == 4)
							{
								if(xhr.status < 200 || xhr.status >=300)
								alert("数据上传出错！"); 
								else
								text.value = "";
								
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