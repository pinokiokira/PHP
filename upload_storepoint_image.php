<?
require_once 'require/security.php';
include 'config/accessConfig.php';

	$id=$_SESSION['StorePointVendorID'];
	$image='';
	$image_url='';
	if($id!=0)
	{
	    $sql = "SELECT StorePoint_image as image from vendors where id = '$id'";
		$res=mysql_query($sql);
		$row=mysql_fetch_array($res);
		$image=$row['image'];
	}	
?>
<script>
jQuery(document).ready(function(){
	var imag = jQuery('#imgdigital').html();
	var imagg = jQuery('#imagebox1').html();
	
	if(imagg!=""){		
		jQuery('#imgdigital').html(imagg);
		jQuery('#imgdigital img').addClass('imgpreview img-polaroid');
	}else if(imag!=""){
		jQuery('#imgdigital').html(imag);
		jQuery('#imgdigital img').addClass('imgpreview img-polaroid');
	}
	
});
var global_imgdigital_upload=false;
function imgdigital_btn()
{
			jQuery('#image_imgdigital').click().change(function(evt){
				handleFileSelect(evt,'imgdigital');
				jQuery('body').focus();
			});
}
function imgdigital_delete()
{
	jQuery('#digital_image_name1').val('');
	jQuery('#digital_image_delete1').val('Y');
	jQuery('#imagebox1').html('');
	jQuery.colorbox.close();
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
			document.getElementById(id).innerHTML='<img class="imgpreview img-polaroid" src="'+e.target.result+'" title="'+theFile.name+'"/>';
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
			jQuery('#imgdigital_form').submit();
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
						jQuery('#old_store_image').hide();
						jQuery('#digital_image_name1').val(global_imgdigital);
						jQuery('#digital_image_delete1').val('N');
						jQuery("#imagebox1").show();
						jQuery('#imagebox1').html('<img src="temp_img/'+global_imgdigital+'" width="100px;">');
						console.log(global_imgdigital);
					}
					else
					{
						alert("Error occured on uploading this file");
					}
					imgdigital_uploading=false;
					global_imgdigital_upload=false;
					jQuery('#imgdigital_progress').hide();
					//jQuery.colorbox.close();
					jQuery('#StoreimageModal').modal('hide');
				}
			});
 });
</script>
<style>
#cboxClose {
     display:none;
    bottom: 390px ! important;
   }
   #cboxTopLeft, #cboxTopRight, #cboxTopCenter, #cboxMiddleLeft, #cboxMiddleRight, #cboxMiddleCenter, #cboxBottomLeft, #cboxBottomRight, #cboxBottomCenter {background:none;}
#cboxLoadedContent, #cboxWrapper, #cboxContent{ height:auto !important;}
#colorbox{ height:570px !important;}
#cboxLoadedContent{ margin-top:0px !important;}
.imgpreview{
	max-height:233px !important;
	max-width:233px !important;
}
</style>

<div class="mediaWrapper row-fluid">
	<div class="span5 imginfo">
    	<form id="imgdigital_form" name="imgdigital_form" method="post"  action="upload_vendor_image_process.php" enctype="multipart/form-data" >
			<input type="file"  name="images" id="image_imgdigital" style="display:none;" >
		</form>
    	<div id="imgdigital" >
		<?
			if($image=='')
	     {
		?>
			<img src="images/photos/1.png" alt="" class="imgpreview img-polaroid" />
			<?
			}
			else
			{
				$path_info = pathinfo($image);
				$imgext =  $path_info['extension'];
                                            
				/*if ($imgext== 'jpeg' || $imgext== 'png' || $imgext== 'gif' || $imgext== 'jpg') 
				{*/
					$image_url = API."images/" . $image;
				//}
			?>
				<img src="<?=$image_url?>" alt="" class="imgpreview img-polaroid" />
			<?
			}
			?>
		</div>
		<div class="progress" id="imgdigital_progress" style="display:none;">
				<div class="bar" id="imgdigital_bar"></div >
				<div class="percent" id="imgdigital_percent">0%</div >
		</div>
		
        <p style="margin-top: 10px;">
			<a style="color: #0866C6 !important;" href="javascript:imgdigital_btn();" class="btn btn-small"><span class="icon-pencil"></span> <? if($id!=0)
	{?>Edit<? }else{?>Upload<?  }?> Image</a>
	<? if($id!=0)
	{?><a style="color: #0866C6 !important;" href="javascript:imgdigital_delete();" class="btn btn-small"><span class="icon-trash"></span> Delete Image</a><? }?>
	
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
      
    </div><!--span3-->
</div><!--imageWrapper-->

