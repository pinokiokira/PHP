<div class="mediaWrapper row-fluid" style="width:445px; height:100px; min-height:50px;">
	<form id="imageform" method="post" enctype="multipart/form-data" action='ajax/proxy.php?url=upload_profile_photo'>
		<div class="par">
			<label>File Upload</label>
			<div class="fileupload fileupload-new" data-provides="fileupload">
				<div class="input-append">
					<div class="uneditable-input span3">
						<i class="iconfa-file fileupload-exists"></i>
						<span class="fileupload-preview"></span>
					</div>
					<span class="btn btn-file">
						<span class="fileupload-new">Select file</span>
						<span class="fileupload-exists">Change</span>
						<input type="file" name="profile_photo" />
					</span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
				</div>
			</div>
		</div>
		<input type="hidden" name="profile_photo_client_id" id="profile_photo_client_id">
		<input type="hidden" name="email" id="email_profile_photo" value="" />
	</form>
    <div >
    	<button class="btn btn-primary" id="file_upload_submit"></span> Upload</button>
		<button class="btn" id="file_upload_close">Close</button>
		<div id='preview'></div>
    </div><!--span3-->
</div><!--imageWrapper-->