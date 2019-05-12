<?php                                                                                                                                              
	
	require_once 'require/security.php';
	include 'config/accessConfig.php';
	require_once('require/openid-config.php');
	$id =$_SESSION["client_id"];
	$query = "select image from employees_master where empmaster_id=".$id;
	$res = mysql_query($query);
	$row = mysql_fetch_array($res);
	$image = $row['image'];
	//$image=$_SESSION['image'];
	
	$image_url='';
	
	function get_http_response_code($url) {
		$headers = get_headers(trim($url));
		return substr($headers[0], 9, 3);
	}
	
        ?>

<script>

var def_im="";



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
			
				//->juni [req REQ_018] - 2014-09-24 - remove image
				jQuery('#imgdigital img').attr('src','images/photos/1.png');
				jQuery('.image_name').val('');
				jQuery('.image_url').val('');
				//<-juni [req REQ_018] - 2014-09-24
				//jQuery('.imgpreview').attr('src','');
				//jQuery('#digital_image_name').val('');
				jQuery('#digital_image_delete').val('Y');
				//jQuery('#imagebox').html('');
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
	 jQuery("#save_imgdigital").live('click',function(){	 	
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
						jQuery('#digital_image_name').val(global_imgdigital);
						jQuery('#digital_image_delete').val('N');
						jQuery('#imagebox').show();
						jQuery('#imagebox').html('<img src="<?php echo API?>images/'+global_imgdigital+'" style="max-height:230px;">');
						console.log(global_imgdigital);
						jQuery.colorbox.close();
						jQuery('#close').click();
						jQuery('.modal-backdrop').hide();
						jQuery('.modal-backdrop').removeClass('in');
						jQuery('.modal-backdrop').removeClass('fade');
						window.location.href="setup_editprofile.php";
					}
					else
					{
						alert("Error occured on uploading this file");
					}
					imgdigital_uploading=false;
					global_imgdigital_upload=false;
					jQuery('#imgdigital_progress').hide();
					//jQuery('#imageModal').modal('toggle');
				}
			});
 });
</script>
<style>
#cboxClose {
     display:none;
    bottom: 390px !important;
}
</style>


<div class="modal-header">
<button data-dismiss="modal" type="button" class="close close_btn"  aria-hidden="true">&times;</button>
<h3 id="myModalLabel">Add/Edit Image</h3>
</div>
<div class="modal-body " id="mymodalhtml">
<div class="mediaWrapper row-fluid">
	<div class="span5 imginfo">
    	<form id="imgdigital_form" name="imgdigital_form" method="post"  action="setup_upload_profile_image.php" enctype="multipart/form-data" >
			<input type="file"  name="images" id="image_imgdigital" style="display:none;" >
		</form>
    	<div id="imgdigital" >
		<?
			if($image==''||get_http_response_code(API."images/" . $image)=="404"){
			$image_url = '';
			$image = '';
		?>
			<img src="images/photos/1.png" alt="" class="imgpreview img-polaroid" />
			<?
			}
			else
			{
				$path_info = pathinfo($image);
				$imgext =  $path_info['extension'];
                                            
				if ($imgext== 'jpeg' || $imgext== 'png' || $imgext== 'gif' || $imgext== 'jpg') 
				{
					$image_url = API."images/" . $image;
				}
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
			<a href="javascript:imgdigital_btn();" class="btn btn-small" style="color: #0866c6 !important;"><span class="icon-pencil"></span> <? if($id!=0)
	{?>Edit<? }else{?>Upload<?  }?> Image</a>
	<? if($id!=0)
	{?><a href="javascript:imgdigital_delete();" class="btn btn-small" style="color: #0866c6 !important;"><span class="icon-trash"></span> Delete Image</a><? }?>
	
		</p>
		<p>
        	<span id="image_desc">
			</span>
       </p>
    </div><!--span3-->
    <div class="span7 imgdetails">
    	<p>
        	<label>Name:</label>
            <input type="text" class="input-block-level image_name" name="image_name" id="image_name" value="<?=basename($image)?>" readonly="true"/>
        </p>
        <p>
        	<label>Link URL:</label>
            <input type="text" class="input-block-level image_url" name="image_url" id="image_url" value="<?=$image_url?>" readonly="true"/>
        </p>
     <!--   <p style="position: absolute;bottom: 10px;">
        	<button class="btn btn-primary" id="save_imgdigital"><span class="icon-ok icon-white"></span> Save Changes</button>
		</p>-->
    </div><!--span3-->
</div>
</div>


<div class="modal-footer" style="text-align:center;">
<button data-dismiss="modal" id="close" class="btn  close_btn" style="color: black !important;">Cancel</button>
<button class="btn btn-primary" id="save_imgdigital"><span class="icon-ok icon-white"></span> Save Changes</button>
</div>

<!--<script>
jQuery( document ).ready(function() {

    jQuery(".close_btn").bind('click',function() {

      
          jQuery.colorbox.close();
        


    });

});
</script>-->