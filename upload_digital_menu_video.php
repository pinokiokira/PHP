<style type="text/css">
	.progress{margin-top: 0px !important;margin-bottom: 5px !important;}
	div#viedodigital img {
	    height: 240px;
	    object-fit: cover;
	    position: relative;
	}
</style>
<?php 
        include_once 'includes/session.php';
	include_once("config/accessConfig.php");
	$id=$_REQUEST['id'];
	$image='';
	$image_url='';
	if($id!=0)
	{
		$sql = "select * from location_dm_images where id = '$id'";
		$res=mysql_query($sql);
		$row=mysql_fetch_array($res);
		$image=$row['video'];
	}	
?>
<script>
var global_viedodigital_upload=false;
function viedodigital_btn()
{
			jQuery('#image_viedodigital').click().change(function(evt){
				handleFileSelect(evt,'video_desc');
				jQuery('body').focus();
			});
}
	function viedodigital_delete()
{
	jQuery('#digital_video_name').val('');
	jQuery('#digital_video_delete').val('Y');
	jQuery('#video_canvas').html('');
	jQuery('#videoModal').modal('toggle');
}
function handleFileSelect(evt,id) {
    var files = evt.target.files; // FileList object
	 var formdata;
		if (window.FormData) {
				formdata = new FormData();
		}
	  f = files[0];
      // Only process image files.
      if (!f.type.match('image.*')) {
       // return false;
      }
	  
      var reader = new FileReader();
      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
			document.getElementById(id).innerHTML=theFile.name;
			global_viedodigital_upload=true;
		};
      })(f);
      reader.readAsDataURL(f);
	  if (!formdata) {
			formdata.append("images", f);
	  }
}

 jQuery(document).ready(function(){
 jQuery("#save_viedodigital").click(function(){
		if(global_viedodigital_upload)
		{	
			console.log("Aqui la info del video");
			console.log(jQuery("#image_viedodigital")[0].files[0].name);
			var extension = jQuery("#image_viedodigital")[0].files[0].name.split(".");
			console.log(extension);
			extension = extension[extension.length-1];
			console.log("Extension del video");
			console.log(extension);
			if (jQuery("#image_viedodigital")[0].files[0].size > 57671680){
                            jAlert("This file is over 55 MB. Please contact SoftPoint Support for alternative upload options.");
                        }else if(extension != "mp4") {
							jAlert("Sorry, video most be of type MP4 and type "+extension+" detected!");
                        }
                        else{
                            jQuery('#viedodigital_form').submit();
                        }
		}
	});
 
 	jQuery('#viedodigital_form').ajaxForm({
				beforeSend: function() {
					viedodigital_uploading=true;
					var percentVal = '0%';
					jQuery('#viedodigital_progress').show();
					jQuery('#viedodigital_bar').width(percentVal);
					jQuery('#viedodigital_percent').html(percentVal);
				},
				uploadProgress: function(event, position, total, percentComplete) {
					var percentVal = percentComplete + '%';
					jQuery('#viedodigital_bar').width(percentVal);
					jQuery('#viedodigital_percent').html(percentVal);
				},
				success: function() {
					var percentVal = '100%';
					jQuery('#viedodigital_bar').width(percentVal);
					jQuery('#viedodigital_percent').html(percentVal);
				},
				complete: function(xhr) {
					
					if(xhr.responseText.search("-.")!=-1)
					{
						global_viedodigital=xhr.responseText;
						jQuery('#digital_video_name').val(global_viedodigital);
						jQuery('#digital_video_delete').val('N');
						var video='<object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" >';
					   	video+='<param name="movie" value="player.swf"/>';
					 	video+='<param name="allowfullscreen" value="true"/>';
						video+='<param name="allowscriptaccess" value="always"/>';
						video+='<param name="wmode" value="opaque"/>';
						video+='<param name="flashvars" value="file=temp_img/'+global_viedodigital+'"/>';
				   		video+='<embed type="application/x-shockwave-flash" id="player2" name="player2" src="player.swf" allowscriptaccess="always" allowfullscreen="true" flashvars="file=temp_img/'+global_viedodigital+'"/>';
						video+='</object>';
					jQuery("#video_canvas").html(video);
								console.log(global_viedodigital);
					}
					else
					{
						alert("Error occured on uploading this file");
					}
					viedodigital_uploading=false;
					global_viedodigital_upload=false;
					jQuery('#viedodigital_progress').hide();
					jQuery('#videoModal').modal('toggle');
				}
			});
 });
</script>
<div class="mediaWrapper row-fluid">
	<div class="span5 imginfo">
    	<form id="viedodigital_form" name="viedodigital_form" method="post"  action="upload_digital_process.php" enctype="multipart/form-data" >
		  <input type="file"  name="images" id="image_viedodigital" style="display:none;">
		</form>
    	<div id="viedodigital" >
		<?
		if($image==''){
		?>
			<img src="images/photos/video.png" alt="" class="imgpreview img-polaroid" />
			<?
			} else {
				$path_info = pathinfo($image);
				$imgext =  $path_info['extension'];
                                            
				$image_url = APIIMAGE."images/" . $image;
			?>
			<object name="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="player" style="width: 260px;"> <param value="player.swf" name="movie">   <param value="true" name="allowfullscreen">    <param value="always" name="allowscriptaccess">    <param value="opaque" name="wmode">    <param value="file=<?=$image_url?>" name="flashvars">     <embed flashvars="file=<?=$image_url?>" allowfullscreen="true" allowscriptaccess="always" src="player.swf" name="player2" id="player2" type="application/x-shockwave-flash" style="width: 260px;"></object>
			<? } ?>
			
		</div>
		<span id="video_desc">
		</span>
		<div class="progress" id="viedodigital_progress">
			<div class="bar" id="viedodigital_bar"></div >
			<div class="percent" id="viedodigital_percent">0%</div >
		</div>
		<p style="margin-top: 10px;">
			<a href="javascript:viedodigital_btn();" class="btn btn-small"><span class="icon-pencil"></span> <? if($id!=0)
			{?>Edit<? }else{?>Upload<?  }?> Video</a>
			<? if($id!=0)
			{?><a href="javascript:viedodigital_delete()" class="btn btn-small"><span class="icon-trash"></span> Delete Video</a><? }?>
		</p>
		<p>
        	
       </p>
    </div><!--span3-->
    <div class="span7 imgdetails">
    	<p>
        	<label>Name:</label>
            <input type="text" class="input-block-level" name="image_name" id="image_name" value="<?=basename($image)?>" readonly="true" />
        </p>
        <p>
        	<label>Link URL:</label>
            <input type="text" class="input-block-level" name="image_url" id="image_url" value="<?=$image_url?>" readonly="true"/>
        </p>
		<p style="color:red"> Upload video in MP4 format </p>
		<!--
        <p style="position: absolute;bottom: 10px;">
        	<button class="btn btn-primary" id="save_viedodigital"><span class="icon-ok icon-white"></span> Save Changes</button>
		</p>-->
    </div><!--span3-->
</div><!--imageWrapper-->
