<style type="text/css">
.btn.btn-small {
	width: 38%;
	display: inline-block;
	margin-bottom: 3px;
}
.progress{margin-top: 0px !important;margin-bottom: 5px !important;}
div#imgdigital img {
    height: 220px;
    object-fit: cover;
    position: relative;
}
</style>
<?php                                                                                                                                              
	include_once 'includes/session.php';
	include_once("config/accessConfig.php");
	$id=$_REQUEST['id'];
	//echo $id;
	$image='';
	$image_url='';
	if($id!=0)
	{
		$sql = "select * from location_dm_images where id = '$id'";
		$res=mysql_query($sql);
		$row=mysql_fetch_array($res);
		$image=$row['image'];
	}else{ ?>
	<script>
	var img = jQuery('#imagebox img').attr('src');
	if(typeof img!== 'undefined'&& img!=''){
	jQuery('#imgdigital').html('<img onerror="this.src=\'images/photos/1.png\'" class="imgpreview img-polaroid" src="'+img+'" title=""/>');
	}
	</script>
	<?php }	

        ?>

    <canvas id="myCanvas" width="123" height="124" style="display:none">
     
</canvas>
<script> 
var def_im="";
var global_emp="<?php echo $_SESSION["employee_id"];?>";


var global_imgdigital_upload=false;
var global_imgdigital_upload=false;
var global_croped_image =false;
var jj=0;
function imgdigital_btn()
{
			jQuery('#image_imgdigital').click().change(function(evt){
				handleFileSelect(evt,'imgdigital');
				jQuery('body').focus();
			});
}
function imgdigital_delete()
{
 	//jQuery('#imageModal').modal('toggle');
        jConfirm('Are you sure to remove this image?', 'Confirm Delete', function(r) {
			if (r){
	jQuery('#digital_image_name').val('');
	jQuery('#old_image').val('');
	jQuery('#digital_image_delete').val('Y');
	jQuery('#imagebox').html('');
	jQuery('#imageModal').modal('hide');
	
	}
	});
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
        return false;
      }
	  
      var reader = new FileReader();
      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
			jQuery('#imgdigital').html('<img class="imgpreview img-polaroid" src="'+e.target.result+'" title="'+theFile.name+'"/>');
			global_imgdigital_upload=true;
		};
      })(f);
      reader.readAsDataURL(f);
	  if (!formdata) {
			formdata.append("images", f);
	  }
}

 jQuery(document).ready(function(){
 	
	 jQuery("#save_imgdigital").click(function(){
	 
		if(global_imgdigital_upload)
		{
			if(jj==0){
			jQuery('#imgdigital_form').submit();
			}
			jj++;
		}else if(global_croped_image){
			jQuery('#imageModal').modal('hide');
		}
	});
 
 	jQuery('#imgdigital_form').ajaxForm({
				beforeSend: function() {
					imgdigital_uploading=true;
					var percentVal = '0%';
					jQuery('#imgdigital_progress').show();
					jQuery('#imgdigital_bar').width(percentVal);
					jQuery('#imgdigital_percent').html(percentVal);
				},
				uploadProgress: function(event, position, total, percentComplete) {
					var percentVal = percentComplete + '%';
					jQuery('#imgdigital_bar').width(percentVal);
					jQuery('#imgdigital_percent').html(percentVal);
				},
				success: function() {
					var percentVal = '100%';
					jQuery('#imgdigital_bar').width(percentVal);
					jQuery('#imgdigital_percent').html(percentVal);
				},
				complete: function(xhr) {
					
					if(xhr.responseText.search("-.")!=-1)
					{
						global_imgdigital=xhr.responseText;
						jQuery('#digital_image_name').val(global_imgdigital);
						jQuery('#digital_image_delete').val('N');
						jQuery('#imagebox').html('<img class="img-polaroid" src="temp_img/'+global_imgdigital+'" style="height:250px;" >');
						console.log(global_imgdigital);
						jQuery('#imageLink').attr('href','upload_digital_menu_image.php');
					}
					else
					{
						alert("Error occured on uploading this file");
					}
					imgdigital_uploading=false;
					global_imgdigital_upload=false;
					jQuery('#imgdigital_progress').hide();
					jQuery('#imageModal').modal('hide');
				}
			});
 });
 
 function htmlcrop_btn()
 {
 	window.open('crop_image.php','Crop Image Window', "height=550,width=800,scrollbars=1");
 }

 function imgcrop_btn()
 {
 	jQuery.ajax({
		type: "GET",
		url: "ajax_crop_image.php"
		}).done(function(msg){
		jQuery("#cropperModalHtml").html(msg);
		jQuery("#cropperModal").modal('show');
	});
		
 }
 function cropImage(image)
 {
 	jQuery('#digital_image_name').val(image);
	jQuery('#digital_image_delete').val('N');
	jQuery('#imagebox').html('<img src="temp_img/'+image+'" style="width:50px;height:50px;">');
	imgdigital_uploading=false;
	global_imgdigital_upload=false;
	global_croped_image = true;
	//jQuery('#imageModal').modal('hide');
	document.getElementById('imgdigital').innerHTML='<img class="imgpreview img-polaroid" src="temp_img/'+image+'" title=""/>';
			
 }
 function cropWindowImage()
 {
	 return jQuery('.imgpreview').attr("src");
 }
 
</script>
 
<div id="immn" class="mediaWrapper row-fluid">
	<div class="span5 imginfo">
    	<form id="imgdigital_form" name="imgdigital_form" method="post"  action="upload_digital_process.php" enctype="multipart/form-data" >
			<input type="file"  name="images" id="image_imgdigital" style="display:none;" >
			<input type="hidden" value="saurav" id="image_crop" name="image_crop">
		</form>
    	<div id="imgdigital" >
		<?
			if($image=='')
	{
		?>
			<img src="images/photos/1.png" alt="" class="typttt" />
			<?
			}
			else
			{
				$path_info = pathinfo($image);
				$imgext =  $path_info['extension'];
                                            
				if ($imgext== 'jpeg' || $imgext== 'png' || $imgext== 'gif' || $imgext== 'jpg') 
				{
					$image_url = APIIMAGE."images/" . $image;
				}
			?>
				<img onerror="this.src='images/nImage.png'" src="<?=$image_url?>" alt="" class="imgpreview img-polaroid" />
			<?
			}
			?>
		</div>
		<div class="progress" id="imgdigital_progress">
				<div class="bar" id="imgdigital_bar"></div >
				<div class="percent" id="imgdigital_percent">0%</div >
		</div>
		
        <p style="margin-top: 10px;">
			<a href="javascript:imgdigital_btn();" class="btn btn-small" style=" margin-top: 3px; "><span class="icon-pencil"></span> <? if($id!=0)
	{?>Edit<? }else{?>Upload<?  }?> Image</a>
 <!-- <a href="javascript:imgcrop_btn();" class="btn btn-small" style=" margin-top: 3px; "><span class="icon-pencil"></span> Crop Image</a>	 -->
	<a href="javascript:htmlcrop_btn();" class="btn btn-small" style=" margin-top: 3px; "><span class="icon-pencil"></span>Crop Image</a>	
	
	<? if($id!=0)
	{?><a href="javascript:imgdigital_delete();" class="btn btn-small" style="margin-bottom: 3px; "><span class="icon-trash"></span> Delete Image</a><? }?>
	
		</p>
		<p>
        	<span id="image_desc">
			</span>
       </p>
    </div><!--span3-->
    <div class="span7 imgdetails">
    	<p>
        	<label>Name:</label>
            <input type="text" class="input-block-level" name="image_name" id="image_name" value="<?=basename($image)?>" readonly="true"/>
        </p>
        <p>
        	<label>Link URL:</label>
            <input type="text" class="input-block-level" name="image_url" id="image_url" value="<?=$image_url?>" readonly="true"/>
        </p>
     <!--   <p style="position: absolute;bottom: 10px;">
        	<button class="btn btn-primary" id="save_imgdigital"><span class="icon-ok icon-white"></span> Save Changes</button>
		</p>-->
    </div><!--span3-->
</div><!--imageWrapper--> 
