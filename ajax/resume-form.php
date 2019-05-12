<div class="mediaWrapper row-fluid" style="width:445px; height:100px; min-height:50px;">
	<form id="resumeform" method="post" enctype="multipart/form-data" action='ajax/proxy.php?url=upload_resume'>
    <input type="hidden" name="old_resume" id="old_resume" >
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
						<input type="file" name="resume" />
					</span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
				</div>
			</div>
		</div>
		<input type="hidden" name="resume_client_id" id="resume_client_id">
	</form>
    <div >
    	<button class="btn btn-primary" id="file_upload_submit"></span> Upload</button>
		<button class="btn" id="file_upload_close">Close</button>
		<div id='preview'></div>
    </div><!--span3-->
</div><!--imageWrapper-->