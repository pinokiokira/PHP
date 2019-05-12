<form id="editlogininfoform" method="post">
	<input type="hidden" name="last_five" id="last_five" value="">
  <h4 class="widgettitle">Log In Information</h4>
  <div class="widgetcontent" style="margin-bottom: 0;padding-bottom: 0;">
    <div>
      <label>Email:</label>
      <input type="text" name="email" id="email" class="input-xlarge" value="" disabled />
    </div>
    <div class="control-group">
      <label>Change Password:</label>
      <input type="password" name="password" id="password" class="input-xlarge" value="" />
      <input type="text" name="password_text" id="password_text" class="input-xlarge" style="display:none;">
      <div>
            <input type="checkbox" id="show_password"/> <span style="font-size:12px; font-weight: bold; color:#666666;">Show Password</span>
    </div>
    </div>
    <div id='password_row' class="control-group" style="margin-bottom:0px;">
      <label>Confirm Password:</label>
      <input type="password" name="confirm_password" id="confirm_password" class="input-xlarge"/>
      <input type="text" name="confirmpassword_text" id="confirmpassword_text" class="input-xlarge" style="display:none;">
      <div>
            <input type="checkbox" id="show_confirm_password"/> <span style="font-size:12px; font-weight: bold; color:#666666;">Show Confirm Password</span>
				<div id="pswd_info">   <!-- juni - 06.07.2014 -> Add password requirements pop'up-->     
					<ul>
						<li id="length" class="invalid">At least <strong>6 character</strong></li>
						<li id="u_name" class="invalid"><strong>Password can not contain email</strong></li>
						<li id="number" class="invalid">At least <strong>one alpha numeric character</strong></li>
						<li id="capital" class="invalid">At least <strong>One Special character or  1 Upper case character</strong></li>
					</ul>
				</div>				
    </div>
    </div>
    
      <p><button class="btn btn-primary" id="update_login_info"> Submit</button></p>
   
  </div>
</form>
