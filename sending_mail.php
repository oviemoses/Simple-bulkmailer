<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Simple Bulk Mail</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body bgcolor="#333333">
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" height="100%">
  <tr>
    <td valign="top" height="100%"><table width="100%" height="70%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><table width="100%" height="80%" border="0" cellspacing="0" cellpadding="0"  bgcolor="#FFFFFF">
     
      <tr>
        <td height="10" valign="top"></td>
      </tr>
      <tr>
        <td valign="top"><table width="100%" height="410" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="250" valign="middle" align="center" class="text">Sending mail...</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="19" valign="top"></td>
      </tr>
      <tr>
        <td height="17" bgcolor="#FFFFFF" class="text1"><img src="image/line1.jpg" width="778" height="3" /></td>
        </tr>
      <tr>
	    <td><table width="100%"><TR>
      
		</TR></table></td>
      </tr>
    </table></td>
  </tr>
    </table></td>
  </tr>
</table>

</body>
</html>
<?php
include_once 'send_mail_attachment.php';
$sleep_time=1;
set_time_limit(0);
if(isset($_REQUEST['settings'])){
	if($_REQUEST['settings']=="messageperhour"){
		if(trim($_REQUEST['message'])==""){
			$sleep_time=1;
		}else{
			$sleep_time = (3600/$_REQUEST['message']);
		}
	}else{
		$sleep_time = 1;
	}
	if($_REQUEST['settings']=="timeoutsec"){
		if(trim($_REQUEST['timeout'])==""){
			set_time_limit(0);
		}else{
			set_time_limit($_REQUEST['timeout']);
		}
	}
} 

?>


<?  

	if($_REQUEST["from"]!="")
		$from=$_REQUEST["from"];
	if($_REQUEST["subject"]!="")
		$subject=$_REQUEST["subject"];
	if($_REQUEST["content"]!="")
		$content=$_REQUEST["content"];
    if(isset($_REQUEST['reply_to'])){
		$reply_to = $_REQUEST['reply_to'];
	}else{
		$reply_to = $from;
	}
	$Subject = $subject;
	#echo $_REQUEST['reply_to'];
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'From: ' . $from .''. "\r\n";
	$headers .= 'Reply-To:' . $reply_to . '' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	if($_FILES['image']['name']==""){
		$SendMessage = "$content";		
	}
	else{
	   $image=basename($_FILES['image']['name']); 
	   $fsname=$_FILES['image']['name'];
	     $target_image = "upload/".$image; 
	    move_uploaded_file($_FILES['image']['tmp_name'], $target_image);
		$SendMessage = "<html><head><title>".$content."</title></head><body><p>".$content."</p><table><tr><td colspan='4'><img src='upload/".$fsname."'></td></tr></table></body></html>";
		
	}
	$fname=basename($_FILES['address']['name']);
	$target_path = "upload/".$fname;
	
	$image_tempname = $_FILES['address']['name'];
	$len = strlen($image_tempname);
	$dot = strrpos($image_tempname,".");
	$ext = substr($image_tempname,$dot,$len);
	
	if(strtolower($ext)==".txt" || strtolower($ext)==".xls" ){
			move_uploaded_file($_FILES['address']['tmp_name'], $target_path);
		if(strtolower($ext)==".txt"){
			$file=fopen($target_path,"r");
			$str="";
			while(!feof($file)){
			  $str.=fgetc($file);
			}
			$str1=explode(",",$str);
		}else{
			include("excel_reader.php");
			$str1 = get_excel_data($target_path);
		}
	}
	
	if($_FILES['address1']['name']!=""){
		$fname1=basename($_FILES['address1']['name']);
	    $target_path1 = "upload/".$fname1;
	    move_uploaded_file($_FILES['address1']['tmp_name'], $target_path1);
	}
	
	foreach($str1 as $value){
		if($_FILES['address1']['name']==""){
			sleep($sleep_time);
			$to=$value;
			if(mail($to,$Subject,$SendMessage,$headers)){
				echo $to;
			}else{
				echo "False : " . $to;
			}
	   }
	   else{
		   $to=$value;
		   sleep($sleep_time);
		   
		     send_mail_attachment($from, $to,$reply_to,$Subject,$SendMessage, $target_path1);
		}	
	}
	fclose($file);
	unlink($target_path);
	echo "<meta http-equiv='refresh' content='0;url=confirm.html'>"; 	
?>
