<?php
        function img_check($name,$i)
		{
			$type = $_FILES[$name]['type'][$i];
			$size = $_FILES[$name]['size'][$i];
			if(($type=='image/jpeg')&&($size>0))
			{
				return true;
			}
			else
			{
				return false;
			}
			
		}

         function resize( $path,$max_length )
		 {
			 $result = array();
			 $source = imagecreatefromjpeg($path);
			 if(!$source)
			 {
				 $result[0] = false;
				 $result[1] = '不能找到原图片';
				 return $result ;
			 }
			 $size = getimagesize($path);
			 $max = ($size[0]>$size[1])? $size[0]:$size[1] ;
			 if($max<$max_length)
			 {
				 $result[0] = false;
				 $result[1] = '不能增大图片';
				 return $result ;
			 }
			 $rate = $max_length/$max;
			 $new_with = (int)$size[0] * $rate;
			 $new_height= (int)$size[1]*$rate;
			 $new = imagecreatetruecolor($new_with,$new_height);
			 if(imagecopyresampled($new,$source,0,0,0,0,$new_with,$new_height,$size[0],$size[1]))
			 {
				  $rand =''.rand().rand();
				  $new_path = ROOT_PATH.'temp/'.'photos'.$rand.'.jpg';
				  imagejpeg($new,$new_path,100);
			 }
			 $result[0]=true;
			 $result[1]=$new_path;
			 return $result;
				   
		 }
		 function create_thumb($path,$length)
		 {
			 $result = array();
			 $top = 0;
			 $left =0;
			 $source = imagecreatefromjpeg($path);
			 if(!$source)
			 {
				 $result[0] = false;
				 $result[1] = '不能找到原图片';
				 return $result ;
			 }
			 $size = getimagesize($path);
			 if($size[0]<=$size[1])
			 {
				 $max= $size[1];
				 $min = $size[0];
				 $top = ($size[1]-$size[0])/2;
			 }
			 else
			 {
				 $max = $size[0];
				 $min = $size[1];
				 $left = ($size[0]-$size[1])/2;
			 }
			 
			 if($min<$length)
			 {
				 $result[0] = false;
				 $result[1] = '图片太小';
				 return $result ;
			 }
			 $new = imagecreatetruecolor($length,$length);
			 if(imagecopyresampled($new,$source,0,0,$left,$top,$length,$length,$min,$min))
			 {
				  $rand =''.rand().rand();
				  $new_path = ROOT_PATH.'temp/'.'thumb'.$rand.'.jpg';
				  imagejpeg($new,$new_path,100);
			 }
			 $result[0]=true;
			 $result[1]=$new_path;
			 $result[2]= 'temp/'.'thumb'.$rand.'.jpg';
			 return $result;
		 }
		  function get_time()
		 {
			 
			 $hour = (int)(date_create("now")->format("H"));
			 $minute = (int)(date_create("now")->format("i"));
			if ( $hour < 10 )
				   { 
						$hour = ('0'.(string)$hour );
				   }
			 if ( $minute < 10)
					{
						$minute = ('0'.(string)$minute) ;
					}
		  
			 return $hour.':'.$minute;
  
		 }
		  function to_time_int($time)
		   {
			   $hour = (int)substr($time,0,2);
			   $minute =  (int)substr($time,3,2);
			   $time_int = $hour*60+$minute;
			   return $time_int;
		   }
		   function to_time_text($time)
		   {
			   $hour = (int)substr($time,0,2);
			   $minute =  (int)substr($time,3,2);
			   if ( $hour < 10 )
			         { 
					      $hour = ('0'.(string)$hour );
					 }
			   if ( $minute < 10)
			          {
						  $minute = ('0'.(string)$minute) ;
					  }
			   return $hour.'时'.$minute.'分';
		   }
		   function to_time($time_int)
		   {   $time='';
			   $hour =(int)($time_int/60);
			   $minute = (int)($time_int%60);
			   if ( $hour < 10 )
			         { 
					      $hour = ('0'.(string)$hour );
					 }
			   if ( $minute < 10)
			          {
						  $minute = ('0'.(string)$minute) ;
					  }
			
			   return $hour.':'.$minute;
		   }
		   function get_time_diff_int( $begin_time , $end_time)
		   {
			   $begin_time_int = to_time_int($begin_time);
			   $end_time_int = to_time_int($end_time);
			   return $end_time_int-$begin_time_int;
		   }
		   
		   function get_date()
		   {
			   $date_object = date_create('now');
		       $year= $date_object->format('y');
		       $month = $date_object->format('m');
		       $day = $date_object->format('d');
		       $date = '20'.$year.'-'.$month.'-'.$day;
			   return $date;
		   }
		   function get_date_text()
		   {
			   $date_object = date_create('now');
		       $year= $date_object->format('y');
		       $month = $date_object->format('m');
		       $day = $date_object->format('d');
		       $date = '20'.$year.'年'.$month.'月'.$day.'日';
			   return $date;
		   }
		   function get_next_date($date)
		   {
			   $date_next_object=date_create("$date");
		       date_add($date_next_object,date_interval_create_from_date_string("1 day"));
		       $year_next = $date_next_object->format('y');
		       $month_next = $date_next_object->format('m');
		       $day_next = $date_next_object->format('d');
		       $next_date = '20'.$year_next.'-'.$month_next.'-'.$day_next;
			   return $next_date;
		   }
		    function get_month_int()
		   {
			   $date_object = date_create('now');
		       $year= $date_object->format('y');
		       $month = $date_object->format('m');
		       $month_int = $year.$month;
			   return $month_int;
		   }
		   
?>
