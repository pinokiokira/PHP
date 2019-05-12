
<?php                                                                                                                                              
	include_once 'includes/session.php';
	include_once("config/accessConfig.php");
	$id=$_REQUEST['id'];
	//echo $id;
	$image='';
	$image_url='';
	if($id!=0)
	{
		$sql = "SELECT local_item_image FROM location_inventory_items WHERE id = '$id'";
		$res=mysql_query($sql);
		$row=mysql_fetch_array($res);
		$image=$row['local_item_image'];
		
		$img = explode('/',$image);
		if(trim($img[1]) == ''){
			$image = '';
		}
		
	}	
?>

    <canvas id="myCanvas" width="123" height="124" style="display:none">
     
</canvas>
<script> 
jQuery(document).ready(function(){
	var img = jQuery('#imagebox img').attr('src');	
	if(img!=""){
		//jQuery('.typttt').attr('src',img);
	}
	
});
var def_im="";
var global_emp="<?php echo $_SESSION["employee_id"];?>";


var global_imgdigital_upload=false;
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
 	//jQuery('#imageModal').modal('toggle');
        jConfirm('Are you sure to remove this image?', 'Confirm Delete', function(r) {
			if (r){


	jQuery('#digital_image_name').val('');
	jQuery('#digital_image_delete').val('Y');
	jQuery('#imgdigital img').attr('src','');
	jQuery('.imgpreview').attr('src','images/photos/1.png');
	jQuery('#imagebox').html('');
	jQuery('#imageModal').modal('toggle');
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
	var i=0;
	
 	var form = jQuery('#imgdigital_form').ajaxForm({
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
						jQuery('#imagebox').html('<img src="temp_img/'+global_imgdigital+'" class="img-polaroid" style="height:250px;">');
						console.log(global_imgdigital);
						jQuery('#imageModal').modal('hide');
					}
					else
					{
						alert("Error occured on uploading this file");
					}
					imgdigital_uploading=false;
					global_imgdigital_upload=false;
					jQuery('#imgdigital_progress').hide();
					jQuery('#imageModal').modal('hide');
					//jQuery('#imageModal').modal('toggle');
				}
			});
 });
</script>
 
<div id="immn" class="mediaWrapper row-fluid">
	<div class="span5 imginfo">
    	<form id="imgdigital_form" name="imgdigital_form" method="post"  action="upload_manage_item_process.php" enctype="multipart/form-data" >
			<input type="file"  name="images" id="image_imgdigital" style="display:none;" >
		</form>
    	<div id="imgdigital" >
		<?
			if($image=='')
	{
		?>
			<img src="images/photos/1.png" alt="" class="img-polaroid typttt" />
			<?
			}
			else
			{
				$path_info = pathinfo($image);
				$imgext =  $path_info['extension'];
                                            
				if ($imgext== 'jpeg' || $imgext== 'png' || $imgext== 'gif' || $imgext=='jpg') 
				{
				
				}$image_url = API."images/" . $image;
			?>
				<img src="<?=$image_url?>" alt="" class="imgpreview img-polaroid" />
			<?
			}
			?>
		</div>
		<div class="progress" id="imgdigital_progress">
				<div class="bar" id="imgdigital_bar"></div >
				<div class="percent" id="imgdigital_percent">0%</div >
		</div>
		
        <p style="margin-top: 10px;">
			<a href="javascript:imgdigital_btn();" class="btn btn-small"><span class="icon-pencil"></span> <? if($id!=0)
	{?>Edit<? }else{?>Upload<?  }?> Image</a>
	<? if($id!=0)
	{?><a href="javascript:imgdigital_delete();" class="btn btn-small"><span class="icon-trash"></span> Delete Image</a><? }?>
	
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
