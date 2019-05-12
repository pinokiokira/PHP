<?php
	$Delivery_trasporation=array("Car","Truck","Motorcycle","Bicycle");
  	$chefedin_market=array("Hotel","Restaurant","Retail","All","Other");

?>
<form class="stdform" method="post" id="client_details_form">
<input type="hidden" name="submitStatusField" id="submitStatusField" value="">
  <div class="tabbedwidget tab-primary" id="tabs">
    <ul class="hormenu">
      <li> <a href="#wiz1step1"> Contact </a> </li>
      <li style="display: none;"> <a href="#wiz1step2"> StaffPoint </a> </li>
	  <li style="display: none;"> <a href="#wiz1step3"> Social Media</a> </li>
          <?php if ($_SESSION['DeliveryPoint']=="Yes"){?>
          <li> <a href="#wiz1step6"> DeliveryPoint</a> </li>
          <?php } ?>
          <?php if ($_SESSION['accessStorePoint']=="Yes"){?>
          <li> <a href="#wiz1step7"> StorePoint</a> </li>
          <?php } ?>
          <?php if ($_SESSION['accessChefedIN']=="Yes"){?>
          <li> <a href="#wiz1step8"> ChefedIN</a> </li>
          <?php } 
           if ($_SESSION['accessStylistFN']=="Yes"){?>
          <li> <a href="#wiz1step9"> StylistFN</a> </li>
          <?php } ?>
	  <li style="display: none;"> <a href="#wiz1step4"> Access</a> </li>
	   <li> <a href="#wiz1step5"> Log</a> </li>
    </ul>
    <div id="wiz1step1" class="formwiz" style="background-color: white;">
      <!--<h4 class="widgettitle">Contact Information</h4>-->
    
      <p style="padding-top:5px;">
        <label>Title:</label>
        <span class="field">
        <select name="salutation" id="salutation" class="uniformselect select-xlarge">
          <option value="">Select Title</option>
          <option value="Mr.">Mr.</option>
          <option value="Ms.">Ms.</option>
          <option value="Mrs.">Mrs.</option>
          <option value="Miss.">Miss.</option>
        </select>
        </span> </p>
      <p>
        <label>First Name:</label>
        <span class="field">
        <input type="text" name="first_name" id="first_name" class="input-xlarge" />
        </span> </p>
      <p>
        <label>Last Name:</label>
        <span class="field">
        <input type="text" name="last_name" id="last_name" class="input-xlarge" />
        </span> </p>
      <p>
        <label>Phone:</label>
        <span class="field">
        <input type="text" name="telephone" id="telephone" class="input-xlarge" />
        </span> </p>
      <p>
        <label>Mobile:</label>
        <span class="field">
        <input type="text" name="mobile" id="mobile" class="input-xlarge" />
        </span> </p>
      <p>
        <label>Fax:</label>
        <span class="field">
        <input type="text" name="fax" id="fax" class="input-xlarge" />
        </span> </p>
      <p>
        <label>Address:</label>
        <span class="field">
        <input type="text" name="address" id="address" class="input-xlarge" />
        </span> </p>
      <p>
        <label>Address 2:</label>
        <span class="field">
        <input type="text" name="address2" id="address2" class="input-xlarge" />
        </span> </p>
      <p>
        <label>Country:</label>
        <span class="field">
        <select name="country" id="country" data-placeholder="Choose a Country..." class="uniformselect select-xlarge">
        </select>
        </span> </p>
      
        <label>State:</label>
        <span class="field">
        <select name="state" id="state" data-placeholder="Choose a State..." class="uniformselect select-xlarge">
        </select>
        </span> </p>
        
        <p>
            <label>City:</label>
            <span class="field">
            <input type="text" name="city" id="city" class="input-xlarge" />
            </span> </p>
          <p>
        
      <p>
        <label>Zip Code:</label>
        <span class="field">
        <input type="text" name="zip" id="zip" class="input-xlarge" />
        </span> </p>
      <p>
        <label>Region:</label>
        <span class="field">
        <input type="text" name="region" id="region" class="input-xlarge" />
        </span> </p>
       <p>
        <label>Neighborhood:</label>
        <span class="field">
        <input type="text" name="neighborhood" id="neighborhood" class="input-xlarge" />
        </span> </p>
        <p>
        <label>Currency:</label>
        <span class="field">
        <select name="currency" id="currency" data-placeholder="Choose a Currency..." class="uniformselect select-xlarge">
        </select>
        </span> </p>
      <p>
       
        <button class="btn btn-primary" onClick="javascript:jQuery('#submitStatusField').val('status-1');" > Submit</button>
		<!-- juni [req REQ_018] - 2014-09-24 - remove spacing 
		<p>  </p>  this is removed from the submit button
        <div>&nbsp;</div>--> 
    </div>
    <!--#wiz1step1-->
    
    <div id="wiz1step2" class="formwiz" style="background-color: white;">
      <!--<h4 class="widgettitle">Basic Information</h4>-->
      
      <p style="padding-top:5px;">
        <label>Viewable:</label>
        <span class="field">
       <select name="viewable" id="viewable" class="uniformselect select-xlarge">
          <option value=""></option>
          <option value="Y">Yes</option>
          <option value="N">No</option>
        </select>
        </span> </p>
      <p>
      <p>
        <label>Date of Birth:</label>
        <span class="field">
        <input type="hidden" name="dob" id="dob" class="input-xlarge" />
        <table>
                            <tr>
                                <td>
                                    <select onchange="change_dateOB()" id="dt_MM" name="dt_MM" style="width: 80px;">
                                        <option value=""></option>
                                        <?php
                                        for ($a = 1; $a <= 12; $a++) {
                                            $i = $a;
                                            if (strlen($a) == 1) {
                                                $i = "0" . $a;
                                            }
                                            echo "<option value='$i' ";                                            
                                            echo " >" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>&nbsp;
                                    <select onchange="change_dateOB()" id="dt_DD" name="dt_DD" style="width: 80px;">
                                        <option value=""></option>
                                        <?php
                                        for ($a = 1; $a <= 31; $a++) {
                                            $i = $a;
                                            if (strlen($a) == 1) {
                                                $i = "0" . $a;
                                            }
                                            echo "<option value='$i' ";
                                            
                                            echo " >" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>&nbsp;
                                    <select onchange="change_dateOB()" id="dt_YYYY" name="dt_YYYY" style="width: 106px;">
                                        <option value=""></option>
                                        <?php
                                        for ($a = 1930; $a < date('Y'); $a++) {
                                            $i = $a;
                                            echo "<option value='$i' ";                                            
                                            echo " >" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
        </span> </p>
      <p>
        <label>Gender:</label>
        <span class="field">
        <select name="sex" id="sex" class="uniformselect select-xlarge">
          <option value=""></option>
          <option value="M">Male</option>
          <option value="F">Female</option>
        </select>
        </span> </p>
      <p>
        <label>Languages:</label>
        <span class="field">
        <select name="languages[]" id="languages" data-placeholder="Choose Language..." class="select-xlarge" style="min-width:200px" multiple="multiple">
          <option value=""></option>
          <!-- <option value="English">English</option>
          <option value="Spanish">Spanish</option>
          <option value="French">French</option> -->
           <?
        $sql_global_languages="select * from global_languages";
      $res_global_languages=mysql_query($sql_global_languages);
      while($row_global_languages=mysql_fetch_array($res_global_languages))
      {
      ?>
      <option value="<?=$row_global_languages['description']?>"><?=$row_global_languages['description']?></option>
          <?
        }
      ?>
        </select>
        </span> </p>
      <p>
        <label>Activities:</label>
        <span class="field">
        <input type="text" name="activities" id="activities" class="input-xlarge" placeholder="Add Activites" />
        </span> </p>
      <p>
        <label>Education:</label>
        <span class="field">
        <input type="text" name="education" id="education" class="input-xlarge" />
        </span> </p>
       <p>
        <label>Competencies:</label>
        <span class="field">
       <textarea name="competencies" id="competencies" class="input-xlarge" ></textarea>
        </span> </p>
        
        <p>
        <label>Previous Job 1 Company :</label>
        <span class="field">
        <input type="text" name="Previous_Job_1_Company" id="Previous_Job_1_Company" class="input-xlarge" />
        </span> </p>
     
       <p class="job1">
        <label>Previous Job 1 Title :</label>
        <span class="field">
        <input type="text" name="Previous_Job_1_Title" id="Previous_Job_1_Title" class="input-xlarge" />
        </span> </p>
  
       <p class="job1">
        <label>Previous Job 1 Location :</label>
        <span class="field">
        <input type="text" name="Previous_Job_1_Location" id="Previous_Job_1_Location" class="input-xlarge" />
        </span> </p>
      
		<div class="job1">
            <label>Previous Job 1 Start Date :</label>
            <span class="field">
                <input type="hidden" name="Previous_Job_1_Startdate" id="Previous_Job_1_Startdate" class="input-xlarge" />
                <table>
                    <tr>
                        <td>
                            <select onchange="change_date_job1SD()" id="dt1SD_MM" name="dt1SD_MM" style="width: 113px;">
                                <option value=""></option>
                                <?php
                                for ($a = 1; $a <= 12; $a++) {
                                    $i = $a;
                                    if (strlen($a) == 1) {
                                        $i = "0" . $a;
                                    }
                                    echo "<option value='$i' ";                                            
                                    echo " >" . $i . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                       
                        <td>&nbsp;
                            <select onchange="change_date_job1SD()" id="dt1SD_YYYY" name="dt1SD_YYYY" style="width: 160px;">
                                <option value=""></option>
                                <?php
                                for ($a = 1930; $a <= date('Y'); $a++) {
                                    $i = $a;
                                    echo "<option value='$i' ";                                            
                                    echo " >" . $i . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </span>
        </div>
        <div class="job1">
        <label>Previous Job 1 End Date :</label>
        <span class="field">
        <input type="hidden" name="Previous_Job_1_Enddate" id="Previous_Job_1_Enddate" class="input-xlarge" />
        <table>
                            <tr>
                                <td>
                                    <select onchange="change_date_job1ED()" id="dt1ED_MM" name="dt1ED_MM" style="width: 113px;">
                                        <option value=""></option>
                                        <?php
                                        for ($a = 1; $a <= 12; $a++) {
                                            $i = $a;
                                            if (strlen($a) == 1) {
                                                $i = "0" . $a;
                                            }
                                            echo "<option value='$i' ";                                            
                                            echo " >" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                               
                                <td>&nbsp;
                                    <select onchange="change_date_job1ED()" id="dt1ED_YYYY" name="dt1ED_YYYY" style="width: 160px;">
                                        <option value=""></option>
                                        <?php
                                        for ($a = 1930; $a <= date('Y'); $a++) {
                                            $i = $a;
                                            echo "<option value='$i' ";                                            
                                            echo " >" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
        </span> </div>
        
        <p class="job1">
        <label>Previous Job 1 Description :</label>
        <span class="field">
        <input type="text" name="Previous_Job_1_Description" id="Previous_Job_1_Description" class="input-xlarge" />
        </span> </p>
        
         <p>
        <label>Previous Job 2 Company :</label>
        <span class="field">
        <input type="text" name="Previous_Job_2_Company" id="Previous_Job_2_Company" class="input-xlarge" />
        </span> </p>
     
       <p class="job2">
        <label>Previous Job 2 Title :</label>
        <span class="field">
        <input type="text" name="Previous_Job_2_Title" id="Previous_Job_2_Title" class="input-xlarge" />
        </span> </p>
  
       <p class="job2">
        <label>Previous Job 2 Location :</label>
        <span class="field">
        <input type="text" name="Previous_Job_2_Location" id="Previous_Job_2_Location" class="input-xlarge" />
        </span> </p>
      
       <div class="job2">
        <label>Previous Job 2 Start Date :</label>
        <span class="field">
        <input type="hidden" name="Previous_Job_2_Startdate" id="Previous_Job_2_Startdate" class="input-xlarge" />
        <table>
                            <tr>
                                <td>
                                    <select onchange="change_date_job2SD()" id="dt2SD_MM" name="dt2SD_MM" style="width: 113px;">
                                        <option value=""></option>
                                        <?php
                                        for ($a = 1; $a <= 12; $a++) {
                                            $i = $a;
                                            if (strlen($a) == 1) {
                                                $i = "0" . $a;
                                            }
                                            echo "<option value='$i' ";                                            
                                            echo " >" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                               
                                <td>&nbsp;
                                    <select onchange="change_date_job2SD()" id="dt2SD_YYYY" name="dt2SD_YYYY" style="width: 160px;">
                                        <option value=""></option>
                                        <?php
                                        for ($a = 1930; $a <= date('Y'); $a++) {
                                            $i = $a;
                                            echo "<option value='$i' ";                                            
                                            echo " >" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
        </span> </div>
        <div class="job2">
        <label>Previous Job 2 End Date :</label>
        <span class="field">
        <input type="hidden" name="Previous_Job_2_Enddate" id="Previous_Job_2_Enddate" class="input-xlarge" />
        <table>
                            <tr>
                                <td>
                                    <select onchange="change_date_job2ED()" id="dt2ED_MM" name="dt2ED_MM" style="width: 113px;">
                                        <option value=""></option>
                                        <?php
                                        for ($a = 1; $a <= 12; $a++) {
                                            $i = $a;
                                            if (strlen($a) == 1) {
                                                $i = "0" . $a;
                                            }
                                            echo "<option value='$i' ";                                            
                                            echo " >" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                               
                                <td>&nbsp;
                                    <select onchange="change_date_job2ED()" id="dt2ED_YYYY" name="dt2ED_YYYY" style="width: 160px;">
                                        <option value=""></option>
                                        <?php
                                        for ($a = 1930; $a <= date('Y'); $a++) {
                                            $i = $a;
                                            echo "<option value='$i' ";                                            
                                            echo " >" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
        </span> </div>
        
        <p class="job2">
        <label>Previous Job 2 Description :</label>
        <span class="field">
        <input type="text" name="Previous_Job_2_Description" id="Previous_Job_2_Description" class="input-xlarge" />
        </span> </p>
        
         <p>
        <label>Previous Job 3 Company :</label>
        <span class="field">
        <input type="text" name="Previous_Job_3_Company" id="Previous_Job_3_Company" class="input-xlarge" />
        </span> </p>
     
       <p class="job3">
        <label>Previous Job 3 Title :</label>
        <span class="field">
        <input type="text" name="Previous_Job_3_Title" id="Previous_Job_3_Title" class="input-xlarge" />
        </span> </p>
  
       <p class="job3">
        <label>Previous Job 3 Location :</label>
        <span class="field">
        <input type="text" name="Previous_Job_3_Location" id="Previous_Job_3_Location" class="input-xlarge" />
        </span> </p>
      
       <div class="job3">
        <label>Previous Job 3 Start Date :</label>
        <span class="field">
        <input type="hidden" name="Previous_Job_3_Startdate" id="Previous_Job_3_Startdate" class="input-xlarge" />
        <table>
                            <tr>
                                <td>
                                    <select onchange="change_date_job3SD()" id="dt3SD_MM" name="dt3SD_MM" style="width: 113px;">
                                        <option value=""></option>
                                        <?php
                                        for ($a = 1; $a <= 12; $a++) {
                                            $i = $a;
                                            if (strlen($a) == 1) {
                                                $i = "0" . $a;
                                            }
                                            echo "<option value='$i' ";                                            
                                            echo " >" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                               
                                <td>&nbsp;
                                    <select onchange="change_date_job3SD()" id="dt3SD_YYYY" name="dt3SD_YYYY" style="width: 160px;">
                                        <option value=""></option>
                                        <?php
                                        for ($a = 1930; $a <= date('Y'); $a++) {
                                            $i = $a;
                                            echo "<option value='$i' ";                                            
                                            echo " >" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
        </span> </div>
        <div class="job3">
        <label>Previous Job 3 End Date :</label>
        <span class="field">
        <input type="hidden" name="Previous_Job_3_Enddate" id="Previous_Job_3_Enddate" class="input-xlarge" />
        <table>
                            <tr>
                                <td>
                                    <select onchange="change_date_job3ED()" id="dt3ED_MM" name="dt3ED_MM" style="width: 113px;">
                                        <option value=""></option>
                                        <?php
                                        for ($a = 1; $a <= 12; $a++) {
                                            $i = $a;
                                            if (strlen($a) == 1) {
                                                $i = "0" . $a;
                                            }
                                            echo "<option value='$i' ";                                            
                                            echo " >" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                               
                                <td>&nbsp;
                                    <select onchange="change_date_job3ED()" id="dt3ED_YYYY" name="dt3ED_YYYY" style="width: 160px;">
                                        <option value=""></option>
                                        <?php
                                        for ($a = 1930; $a <= date('Y'); $a++) {
                                            $i = $a;
                                            echo "<option value='$i' ";                                            
                                            echo " >" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
        </span> </div>
        
        <p class="job3">
        <label>Previous Job 3 Description :</label>
        <span class="field">
        <input type="text" name="Previous_Job_3_Description" id="Previous_Job_3_Description" class="input-xlarge" />
        </span> </p>

        
       <p>
        <label>Employment type:</label>
        <span class="field">
		<select name="employment_type[]" id="employment_type" data-placeholder="Choose Employment Type..." class="select-xlarge" multiple="multiple">
          <option value=""></option>
          <option value="Full Time">Full Time</option>
          <option value="Contractor">Contractor</option>
          <option value="Part Time">Part Time</option>
		   <option value="Intern">Intern</option>
		   <option value="Seasonal / Temp">Seasonal / Temp</option>
        </select>        
        </span> </p>
       <p>
        <label>Interested Position 1:</label>
        <span class="field">
		<select name="emp_position1" id="emp_position1" data-placeholder="Choose Position 1..." class="select-xlarge" >
          <option value=""></option>
          <?
		  	$sql_job_type="select * from job_type order by type ASC";
			$res_job_type=mysql_query($sql_job_type);
			while($row_job_type=mysql_fetch_array($res_job_type))
			{
		  ?>
		  <option value="<?=$row_job_type['job_id']?>"><?=$row_job_type['type']?></option>
          <?
		  	}
		  ?>
        </select>
		
		
        </span> </p>
       <p>
        <label>Interested Position 2:</label>
        <span class="field">
		<select name="emp_position2" id="emp_position2" data-placeholder="Choose Position 2..." class="select-xlarge" >
          <option value=""></option>
          <?
		  	$sql_job_type="select * from job_type order by type ASC";
			$res_job_type=mysql_query($sql_job_type);
			while($row_job_type=mysql_fetch_array($res_job_type))
			{
		  ?>
		  <option value="<?=$row_job_type['job_id']?>"><?=$row_job_type['type']?></option>
          <?
		  	}
		  ?>
        </select>
		
        </span> </p>
        <p>
        <label>Interested Position 3:</label>
        <span class="field">
        <select name="emp_position3" id="emp_position3" data-placeholder="Choose Position 3..." class="select-xlarge" >
          <option value=""></option>
          <?
		  	$sql_job_type="select * from job_type order by type ASC ";
			$res_job_type=mysql_query($sql_job_type);
			while($row_job_type=mysql_fetch_array($res_job_type))
			{
		  ?>
		  <option value="<?=$row_job_type['job_id']?>"><?=$row_job_type['type']?></option>
          <?
		  	}
		  ?>
        </select>
        </span> </p>
     <p>
        
         <button class="btn btn-primary" onClick="javascript:jQuery('#submitStatusField').val('status-2');" > Submit</button>
		 </p>
     <div>&nbsp;</div> 
    </div>
    <!--#wiz1step2-->
   
   <div id="wiz1step3" class="formwiz" style="background-color: white;min-height: 546px;">
      <!--<h4 class="widgettitle">Basic Information</h4>-->
     
      <div id="facebook"></div>
      
      <div id="google"></div>
      
      <div id="linkedin"></div>
      
      <div id="twitter"></div>
     <!-- <p>
        <label>Social Media :</label>
        <span class="field">
        <button href="javascript:void(0)" id="facebook_id" class="btn btn-primary" style="width:200px;">Connect Facebook</button>
        <!--<input type="text" name="facebook_id" id="facebook_id" class="input-xlarge" />
        </span> </p>
      <p>
        <label>&nbsp;</label>
        <span class="field">
        <button href="javascript:void(0)" id="google_id" class="btn btn-primary"  style="width:200px;">Connect Google+</button>
        <!--<input type="text" name="google_id" id="google_id" class="input-xlarge" /> 
        </span> </p>
      <p>
        <label>&nbsp;</label>
        <span class="field">
        <button href="javascript:void(0)" id="linkedin_id" class="btn btn-primary" style="width:200px;">Connect Linkedin</button>
        <!--<input type="text" name="linkedin_id" id="linkedin_id" class="input-xlarge" />
        </span> </p>
      <p>
        <label>&nbsp;</label>
        <span class="field">
        <button href="javascript:void(0)" id="twitter" class="btn btn-primary"  style="width:200px;">Connect Twitter</button>
        <!--<input type="text" name="twitter" id="twitter" class="input-xlarge" /> 
        </span> </p>
        
      <!--<p>
				<label>Primary Dinning</label>
				<span class="field"><input type="text" name="primarydinning" id="primarydinning" class="input-xlarge" /></span>
			</p>--> 
     <div>&nbsp;</div> 
    </div>
	 <?php if ($_SESSION['DeliveryPoint']=="Yes"){?>
	<div id="wiz1step6" class="formwiz" style="background-color: white;">
      <!--<h4 class="widgettitle">Basic Information</h4>-->
      
      <span id="DeliveryPoint_options"  style="display:none " >
			<p>
        <label>Activated:</label>
        <span class="field">
        <input type="text" name="Delivery_activated_datetime" id="Delivery_activated_datetime" class="input-xlarge"  readonly="true"/>
        </span> </p>
		<p>
        <label>Transportation Type:</label>
        <span class="field">
		<!--<select name="Delivery_trasporation[]" id="Delivery_trasporation" data-placeholder="Choose Trasporation Type" class="select-xlarge" multiple="multiple" >
          <option value=""></option>
          <?
		 foreach($Delivery_trasporation as $key=>$value)
		 {	
		  ?>
		  <option value="<?=$value?>"><?=$value?></option>
          <?
		  	}
		  ?>
        </select>-->
        <select name="Delivery_trasporation[]" id="Delivery_trasporation" data-placeholder="Choose Trasporation Type" class="select-xlarge" >
                    <option value="">Please Select Transportation Type</option>
                    <option value="Car">Car</option>
                    <option value="Truck">Truck</option>
                    <option value="Motorcycle">Motorcycle</option>
                    <option value="Bicycle">Bicycle</option>
         </select>
		
        </span> </p>
		
		<p>
        <label>Payment Method:</label>
        <span class="field">
        <input type="text" name="Delivery_payment_method" id="Delivery_payment_method" class="input-xlarge"  />
        </span> </p>
		
		
	</span>
		
		<button class="btn btn-primary" onClick="javascript:jQuery('#submitStatusField').val('status-6');" > Submit</button>
      
	  
     <div>&nbsp;</div> 
    </div>
    <?php } 
    if ($_SESSION['accessStorePoint']=="Yes"){ ?>
    <div id="wiz1step7" class="formwiz" style="background-color: white;">
        <p>
            <label>StorePoint Vendor ID:<span style="color:red;">*</span></label>
        <span class="field">
        <input type="text" name="StorePoint_vendor_id" id="StorePoint_vendor_id" class="input-xlarge"/>
        <input type="hidden" name="vendor_id" id="vendor_id" value="" class="input-xlarge"/>
        <input type="text" style="display:none;" name="StorePoint_vendor_id_search" autocompelete='off' id="StorePoint_vendor_id_search" class="input-xlarge"/>
        </span> </p>
        
        <p>
        <label>Status:<span style="color:red;">*</span></label>
        <span class="field">
        <select name="vendorstatus" id="vendorstatus" class="select-xlarge" >
                 <option value="active">Active</option>
                 <option value="inactive">Inactive</option>
                 <option value="suspended">Suspended</option>
                 <option value="not_registered">Not Registered</option>

            </select>
        </span> </p>
		
		<p>
        <label>Location Link:</label>
        <span class="field">
        <input type="text" name="locationLink" id="locationLink" class="input-xlarge"/>
		<input type="hidden" name="location_link" id="location_link"/>
        </span> </p>

        <p>
        <label>Name:<span style="color:red;">*</span></label>
        <span class="field">
        <input type="text" name="vendorname" id="vendorname" class="input-xlarge"/>
        </span> </p>
        <!--<p class="colorbox">
						<label>Image:</label>
						<span id="imagebox" style="margin:0; width:100%; float:left;">
						</span>
						<span class="field" style="margin:0; width:100%">
						<input type="hidden" name="old_storeimage" id="old_storeimage" value="">                                   
						<input type="hidden" name="image_name" id="image_name" value="">
						<input type="hidden" name="image_delete" id="image_delete" value="N">
						
						<input type="button" name="upload_image"  value="Upload Images" class="submit-green btn btn-primary upload_image" data-imgloc="image">-->	
						<!--<a data-target="#imageModal" href="upload_storepoint_image.php" role="button" class="btn btn-primary upload_image"  id="imageLink" style="padding:3px" data-imgloc="image">Upload Images</a>
						</span>
					</p>-->
                    <div class="colorbox"> 
                    <label>Image: (Image Size Required 225w x 225h)</label>
                    <div class="par">
                      <input type="hidden" name="old_store_img" id="old_store_img" value=""/>
                      <span id="imagebox1" style="width:100%; display:none;"></span>
                      <image  id="old_store_image" onerror="this.src='images/noimage.png'" style="width:100px; display:none;" src=""/>
                     	<p class="colorboximg" style="margin:20px 218px;">
                        <input type="hidden" name="digital_image_name1" id="digital_image_name1" value="">
                        <input type="hidden" name="digital_image_delete1" id="digital_image_delete1" value="N">
                        <a href="upload_storepoint_image.php" id="colorbox_img" style="display:none;"></a>
                        <input type="button" name="upload_image" id="upload_image" value="Upload Image" rel="<?php echo $_GET['idads']; ?>" class="submit-green btn btn-primary">
                      </p>
                      </div>
                      </div>
                    

        <p>
        <label>Contact:<span style="color:red;">*</span></label>
        <span class="field">
        <input type="text" name="vendorcontact" id="vendorcontact" class="input-xlarge"/>
        </span> </p>
		<p>
        <label>Title:</label>
        <span class="field">
        <input type="text" name="vendortitle" id="vendortitle" class="input-xlarge"/>
        </span> </p>
        <p>
        <label>Email:</label>
        <span class="field">
        <input type="text" name="vendoremail" id="vendoremail" class="input-xlarge"/>
        </span> </p>

         

         <p>
        <label>Address:<span style="color:red;">*</span></label>
        <span class="field">
        <input type="text" name="vendoraddress" id="vendoraddress" class="input-xlarge"/>
        </span> </p>

        <p>
        <label>Address 2:</label>
        <span class="field">
        <input type="text" name="vendoraddress2" id="vendoraddress2" class="input-xlarge"/>
        </span> </p>

        <p>
        <label>Country:<span style="color:red;">*</span></label>
        <span class="field">
        <select name="vendorcountry" id="vendorcountry" class="select-xlarge" >
                 <option value=""> - - - Select Country - - - </option>
            <?php   $selcountry = "";
                    $defaultcountry = "";
                    $sqlctry = "SELECT * FROM countries ORDER BY name ASC";
                    $resultctry =mysql_query($sqlctry);
                    while ($rowctry = mysql_fetch_assoc($resultctry)){
                        if ($rowctry["is_default"]=="yes"){
                            $selcountry = "selected";
                            $defaultcountry = $rowctry["id"];
                        }else $selcountry ="";
                ?>
                 <option value="<?php echo $rowctry["id"]?>" <?php echo $selcountry;?>><?php echo $rowctry["name"]?></option>
                <?php }?> 
            </select>
        </span> </p>

        <p>
        <label>City:<span style="color:red;">*</span></label>
        <span class="field">
        <input type="text" name="vendorcity" id="vendorcity" class="input-xlarge"/>
        </span> </p>

         <p>
        <label>State:<span style="color:red;">*</span></label>
        <span class="field">
         <!--  <input type="text" name="vendorstate_id" id="vendorstate_id" class="input-xlarge"/> -->
          <?php 
          //session_start();
          //echo $_SESSION['vendorstate']."-----";die; 
          $myven  =   "SELECT StorePoint_vendor_Id,image,first_name,last_name,state,country,city FROM employees_master WHERE StorePoint='Yes' AND email='".$_SESSION['email']."'";
          $quemyven =   mysql_query($myven) or die(mysql_error());
          $rowmyven = mysql_fetch_array($quemyven);     
          $myven2   =   "SELECT * FROM vendors WHERE id='".$rowmyven["StorePoint_vendor_Id"]."'";
          $quemyven2  =   mysql_query($myven2) or die(mysql_error());
          $rowmyven2  = mysql_fetch_array($quemyven2);
          ?>
        <select name="vendorstate" id="vendorstate" class="select-xlarge" >
                 <option value=""> - - - Select State - - - </option>
                 <?php
                 
                      $sqlst2 = "";
                      if ($defaultcountry!="") {$sqlst2 = " WHERE country_id = {$defaultcountry}";}
                       $sqlst = "SELECT * FROM states " . $sqlst2 . " ORDER BY name ASC";
                    $resultst =mysql_query($sqlst);
                    while ($rowst = mysql_fetch_assoc($resultst)){
                ?>
                 <option value="<?php echo $rowst["id"]?>" <?php if($rowmyven2['state']==$rowst["id"]) echo "selected" ?>><?php echo $rowst["name"]?></option>
                <?php }?> 
            </select>
        </span> </p>

        <p>
        <label>Zip:<span style="color:red;">*</span></label>
        <span class="field">
        <input type="text" name="vendorzip" id="vendorzip" class="input-xlarge"/>
        </span> </p>

        <p>
        <label>Phone:<span style="color:red;">*</span></label>
        <span class="field">
        <input type="text" name="vendorphone" id="vendorphone" class="input-xlarge"/>
        </span> </p>

        <p>
        <label>Fax:</label>
        <span class="field">
        <input type="text" name="vendorfax" id="vendorfax" class="input-xlarge"/>
        </span> </p>

        <p>
        <label>Website:</label>
        <span class="field">
        <input type="text" name="vendorwebsite" id="vendorwebsite" class="input-xlarge"/>
        </span> </p>

         <p>
        <label>Currency:<span style="color:red;">*</span></label>
        <span class="field">
        <select name="vendorcurrency" id="vendorcurrency" class="select-xlarge" >
                 <option value=""> - - - Select Currency - - - </option>
                 <? $sqlcur = "SELECT id,code FROM global_currency WHERE code='USD'";
                    $resultcur = mysql_query($sqlcur);
                    $rowcurrency = mysql_fetch_assoc($resultcur);
                    echo "<option value='".$rowcurrency["id"]."' selected>".$rowcurrency["code"]."</option>";
                    $sqlcur = "SELECT id,code FROM global_currency ORDER BY code";
                    $resultcur = mysql_query($sqlcur);
                    while ($rowcurrency = mysql_fetch_assoc($resultcur)){
                        if ($rowcurrency['code']=="USD") continue;
                ?><option value="<?php echo $rowcurrency["id"]?>" ><?php echo $rowcurrency["code"];?></option>
                <?php                    }
                    ?>
            </select>
        </span> </p>

        <p>
        <label>Description:</label>
        <span class="field">
        <textarea name="vendordescription" id="vendordescription" class="input-xlarge"></textarea>
        </span> </p>

        <p>
        <label>Types of Products:<span style="color:red;">*</span></label>
        <span class="field">
        <select name="vendortype[]" id="vendortype" data-placeholder=" - - - Select Type Of Products - - - " class="select-xlarge" multiple="multiple">                 
                 <? $sqltype = "SELECT vendor_type_id,code FROM vendors_types ORDER BY code";
                    $resulttype = mysql_query($sqltype);
                    while ($rowtype = mysql_fetch_assoc($resulttype)){
                ?><option value="<?php echo $rowtype["vendor_type_id"]?>" ><?php echo $rowtype["code"];?></option>
                <?php                    }
                    ?>
                 
            </select>
        </span> </p>
        <p>
        <label>Vendor Terms:<span style="color:red;">*</span></label>
        <span class="field">
        <select name="vendorterm[]" id="vendorterm" data-placeholder="- - - Select Vendor Terms - - -" class="chzn-select select-xlarge" multiple="multiple">                 
                 <? $sqlterms = "SELECT vendors_terms_types id,code FROM vendors_terms_types ORDER BY id";
				 /*$sqlterms = "SELECT id,terms FROM vendor_terms ORDER BY id";*/
                    $resultterms = mysql_query($sqlterms);
                    while ($rowterms = mysql_fetch_assoc($resultterms)){
                ?><option value="<?php echo $rowterms["id"]?>" ><?php echo $rowterms["code"];?></option>
                <?php                    }
                    ?>
                 
            </select>
        </span> </p> 
		<p>
        <label>Payment Types:<span style="color:red;">*</span></label>
        <span class="field">
        <select name="vendorPaymentType[]" id="vendorPaymentType" data-placeholder=" - - - Select Payment Types - - - " class="select-xlarge" multiple="multiple">                 
                 <?php $sqlptype = "SELECT vendors_payments_id,code FROM vendors_payment_types ORDER BY code";
                    $resultptype = mysql_query($sqlptype);
                    while ($rowptype = mysql_fetch_assoc($resultptype)){
                ?><option value="<?php echo $rowptype["vendors_payments_id"]?>" ><?php echo $rowptype["code"];?></option>
                <?php } ?>
            </select>
        </span> </p>
		<p>
        <label>Delivery Types:<span style="color:red;">*</span></label>
        <span class="field">
        <select name="vendorDeliveryType[]" id="vendorDeliveryType" data-placeholder=" - - - Select Delivery Types - - - " class="select-xlarge" multiple="multiple">                 
                 <?php $sqldtype = "SELECT vendors_delivery_types_id,code FROM vendors_delivery_types ORDER BY code";
                    $resultdtype = mysql_query($sqldtype);
                    while ($rowdtype = mysql_fetch_assoc($resultdtype)){
                ?><option value="<?php echo $rowdtype["vendors_delivery_types_id"]?>" ><?php echo $rowdtype["code"];?></option>
                <?php } ?>
            </select>
        </span> </p>
        <p>
        <label>Created On:</label>
        <span class="field">
        <input type="text" name="vendorcreatedon" id="vendorcreatedon" class="input-xlarge" disabled />
        </span> </p>
        <p>
        <label>Created By:</label>
        <span class="field">
        <input type="text" name="vendorcreatedby" id="vendorcreatedby" class="input-xlarge" disabled />
        </span> </p>
        <p>
        <label>Created Date & Time:</label>
        <span class="field">
        <input type="text" name="vendordatetime" id="vendordatetime" class="input-xlarge" disabled />
        </span> </p>
        
         <p>
        <label>Last On:</label>
        <span class="field">
        <input type="text" name="vendorlaston" id="vendorlaston" class="input-xlarge" disabled />
        </span> </p>
        <p>
        <label>Last By:</label>
        <span class="field">
        <input type="text" name="vendorlastby" id="vendorlastby" class="input-xlarge" disabled />
        </span> </p>
        <p>
        <label>Last Date & Time:</label>
        <span class="field">
        <input type="text" name="vendorlastdatetime" id="vendorlastdatetime" class="input-xlarge" disabled />
        </span> </p>
       
        
        <button class="btn btn-primary" onClick="javascript:jQuery('#submitStatusField').val('status-7');" > Submit</button>
    </div>
    <?php } 
    if ($_SESSION['accessChefedIN']=="Yes"){ ?>
    <div id="wiz1step8" class="formwiz" style="background-color: white;">
        <p>
        <label>Business Name:</label>
        <span class="field">
        <input type="text" name="Chefedin_Business_Name" id="Chefedin_Business_Name" class="input-xlarge" placeholder="Business Name">
        </span> </p>
       
        
        <div class="colorbox"> 
                    <label>Image: (Image Size Required 225w x 225h)</label>
                    <div class="par">
                      <input type="hidden" id="old_chefedin_img" name="old_chefedin_img" value="" />
                      <span id="imageboxs" style="width:100%; display:none;"></span>
                      <image  id="old_chefedin_image" onerror="this.src='images/noimage.png'" style="width:100px; display:none;" src=""/>
                      <p class="colorboximgs"  style="margin:20px 218px;">
                        <input type="hidden" name="digital_image_names" id="digital_image_names" value="">
                        <input type="hidden" name="digital_image_deletes" id="digital_image_deletes" value="N">
                        <a href="upload_chefedin_image.php" id="colorbox_imgs" style="display:none;"></a>
                        <input type="button" name="upload_images" id="upload_images" value="Upload Image" rel="<?php echo $_GET['idads']; ?>" class="submit-green btn btn-primary">
                      </p>
                    </div>
                    </div>
        
        <p>
        <label>Introduction:</label>
        <span class="field">
        <textarea name="introduction" id="introduction" class="input-xlarge" style="height: 100px;"></textarea>
        </span> </p>
        
        <p>
        <label>Services:</label>
        <span class="field">
        <textarea name="services" id="services" placeholder="Add Services" class="input-xlarge" style="height: 100px;"></textarea>
        </span> </p>
        
        <p>
        <label>Experience:</label>
        <span class="field">
        <textarea name="experience" id="experience" class="input-xlarge" style="height: 100px;"></textarea>
        </span> </p>
        
        <p>
        <label>Market:</label>
        <span class="field">
        <select name="chefedin_market[]" id="chefedin_market" data-placeholder="Choose ChefedIN Market" class="select-xlarge" multiple="multiple" >
          <option value=""></option>
          <?
		 foreach($chefedin_market as $key=>$value)
		 {	
		  ?>
		  <option value="<?=$value?>"><?=$value?></option>
          <?
		  	}
		  ?>
        </select>
        </span> </p>
        
        <p>
        <label>Website:</label>
        <span class="field">
        <textarea name="website" id="website" class="input-xlarge" style="height: 100px;"></textarea>
        </span> </p>
        
        <p>
        <label>References:</label>
        <span class="field">
        <textarea name="references" id="references" class="input-xlarge" style="height: 100px;"></textarea>
        </span> </p>
        
        <p>
        <label>Rate:</label>
        <span class="field">
        <textarea name="rate" id="rate" class="input-xlarge" style="height: 100px;"></textarea>
        </span> </p>
        <button class="btn btn-primary" onClick="javascript:jQuery('#submitStatusField').val('status-6');" > Submit</button>
        
    </div>
    <?php } 
    if ($_SESSION['accessStylistFN']=="Yes"){ ?>
    <div id="wiz1step9" class="formwiz" style="background-color: white;">
        <p style="padding-top:6px;">
        <label>Company:</label>
        <span class="field">
        <input type="text" name="StylistFN_Company" id="StylistFN_Company" class="input-xlarge"/>
        </span> </p>
        
        <p>
        <label>Description:</label>
        <span class="field">
        <textarea name="StylistFN_Description" id="StylistFN_Description" class="input-xlarge"></textarea>
        </span> </p>
        
        <p>
        <label>Style:</label>
        <span class="field">
        <textarea name="StylistFN_Style" id="StylistFN_Style" class="input-xlarge"></textarea>
        </span> </p>
        
        <p>
        <label>Located:</label>
        <span class="field">
        <input type="text" name="StylistFN_Located" id="StylistFN_Located" class="input-xlarge"/>
        </span> </p>
        
        <p>
        <label>Location:</label>
        <span class="field">
        <select name="StylistFN_location_id" id="StylistFN_location_id">
            <option> - - - Select Location - - - </option>
            <?php $sql = "SELECT id,concat(name,' ','(ID#: ',id,')') name FROM locations where id in (SELECT location_id from employees WHERE email='{$_SESSION["email"]}') order by name ASC limit 10";
                    $res = mysql_query($sql) or die(mysql_error());
                    $loc_count = mysql_num_rows($res);
                    $setselected="";
                    if ($loc_count==1) $setselected = "selected";
                    if ($res) {
                            while ($row = mysql_fetch_assoc($res)) {	
                               echo "<option value='".$row['id']."' " . $setselected. ">".$row['name']."</option>";
                            }

                    }?>
        </select>
        </span> </p>
        <button class="btn btn-primary" onClick="javascript:jQuery('#submitStatusField').val('status-1');" > Submit</button>
    </div>
    <?php } ?>
	<div id="wiz1step4" class="formwiz" style="background-color: white;">
      <!--<h4 class="widgettitle">Basic Information</h4>-->
      
      <p style="padding-top:6px;">
        <label>StorePoint:</label>
        <span class="field">
		<select name="StorePoint" id="StorePoint" data-placeholder="StorePoint Access" class="select-xlarge" >
                    <option value="">Please Select StorePoint</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
          
        </select>
		
		
        </span> </p>
		
		
		<p>
        <label>ChefedIN:</label>
        <span class="field">
		<select name="ChefedIN" id="ChefedIN" data-placeholder="StorePoint Access" class="select-xlarge" >
                    <option value="">Please Select ChefedIN</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
          
        </select>
		
		
        </span> </p>
		
		<p>
        <label>StylistFN:</label>
        <span class="field">
		<select name="StylistFN" id="StylistFN" data-placeholder="StorePoint Access" class="select-xlarge" >
                    <option value="">Please Select StylistFN</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
          
        </select>
		
		
        </span> </p>
		
		<p>
        <label>DeliveryPoint:</label>
        <span class="field">
		<select name="DeliveryPoint" id="DeliveryPoint" data-placeholder="StorePoint Access" class="select-xlarge" >
                    <option value="">Please Select DeliveryPoint</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
         </select>
		</span> 
		</p>
        
        
	<!--	
		<span id="DeliveryPoint_options"  style="display:none " >
			<p>
        <label>Activated :</label>
        <span class="field">
        <input type="text" name="Delivery_activated_datetime" id="Delivery_activated_datetime" class="input-xlarge"  readonly="true"/>
        </span> </p>
		<p>
        <label>Trasporation Type :</label>
        <span class="field">
		<select name="Delivery_trasporation[]" id="Delivery_trasporation" data-placeholder="Choose Trasporation Type" class="select-xlarge" multiple="multiple" >
          <option value=""></option>
          <?
		 foreach($Delivery_trasporation as $key=>$value)
		 {	
		  ?>
		  <option value="<?=$value?>"><?=$value?></option>
          <?
		  	}
		  ?>
        </select>
		
        </span> </p>
		
		<p>
        <label>Payment Method :</label>
        <span class="field">
        <input type="text" name="Delivery_payment_method" id="Delivery_payment_method" class="input-xlarge"  />
        </span> </p>
		
		
		</span>-->
		
		
		<button class="btn btn-primary" onClick="javascript:jQuery('#submitStatusField').val('status-4');" > Submit</button>
      
     <div>&nbsp;</div> 
    </div>
	<div id="wiz1step5" class="formwiz" style="background-color: white;">
      <!--<h4 class="widgettitle">Basic Information</h4>-->
      
      <p>
        <label>Created On:</label>
        <span class="field">
        <input type="text" name="created_on" id="created_on" class="input-xlarge"  readonly="true"/>
        </span> </p>
		<p>
        <label>Created By:</label>
        <span class="field">
        <input type="text" name="created_by" id="created_by" class="input-xlarge"  readonly="true"/>
        </span> </p>
		<p>
        <label>Created Date & Time:</label>
        <span class="field">
        <input type="text" name="created_datetime" id="created_datetime" class="input-xlarge"  readonly="true"/>
        </span> </p>
      <p>&nbsp;</p>
	  
	  <p>
        <label>Last On:</label>
        <span class="field">
        <input type="text" name="last_on" id="last_on" class="input-xlarge"  readonly="true"/>
        </span> </p>
	   <p>
        <label>Last By:</label>
        <span class="field">
        <input type="text" name="last_by" id="last_by" class="input-xlarge"  readonly="true"/>
        </span> </p>
		
	  <p>
        <label>Last Date & Time:</label>
        <span class="field">
        <input type="text" name="last_datetime" id="last_datetime_x" class="input-xlarge" value="<?php // echo date("Y-m-d H:i:s");?>"  readonly="true"/>
        </span> </p>
	  
     <div>&nbsp;</div> 
    </div>
	
	
	
   
  </div>
  <!--#wizard-->
  <input type="hidden" name="client_id" id="client_id">
  <input type="hidden" name="email" id="current_email">
</form>
<script>

/*function Editterms(values,vendorId,action){
	jQuery.ajax({
			url:'add_vendor_terms.php',
			type:'POST',
			data:{values:values,vendorId:vendorId,action:action},
			success:function(data){
				jQuery('#vendorterm').html(data);
				jQuery("#vendorterm").chosen({
					no_results_text: ""
				}).trigger("liszt:updated");
			}
		});
}
    jQuery(document).ready(function(event){	
    var dropDown = jQuery("#vendorterm").chosen({
		no_results_text: ""	
	}).change(function(){
        var values = jQuery(this).val();
		var vendor_id = jQuery('#StorePoint_vendor_id').val();
			Editterms(values,vendor_id,'delete');
	}); 
   
    
		dropDown.parent().find('#vendorterm_chzn .chzn-choices .search-field input[type=text]').keydown( function (evt) {
			
           var stroke, _ref, target, list;
           // get keycode
           stroke = (_ref = evt.which) != null ? _ref : evt.keyCode;
           target = jQuery(evt.target);               
           // get the list of current options
           list = target.parents('.chzn-container').find('.chzn-choices li.search-choice > span').map(function () { return jQuery(this).text(); }).get();
           if (stroke === 9 || stroke === 13) {
              var value = jQuery.trim(target.val());
              // if the option does not exists
              if (jQuery.inArray(value,list) < 0) {
                 var option = jQuery('<option>');
                 option.text(value).val(value).appendTo(dropDown);
                 option.attr('selected','selected');
				 	var vendor_id = jQuery('#StorePoint_vendor_id').val();
					Editterms(value,vendor_id,'add');
                 // add the option and set as selected
              }
              // trigger the update event
              dropDown.trigger("liszt:updated");
              return true;
           }
        });		
		
	});*/
	jQuery(document).ready(function(event){	
    jQuery("#vendorterm").chosen();
	
	});
	/**cut from heat for vendor terms**/
	
	/* jQuery("#locationLink").keyup(function(){
		var txt = jQuery(this).val();
		console.log(txt);
		jQuery.ajax({
			url:'upload_storepoint_image.php?id='+clientid,
			type:'POST',
			success:function(data){
				console.log(txt);
			}
		});
	}); */
	
	jQuery('#locationLink').typeahead({
		minLength: 3,
		source: function (query, process) {
			return jQuery.ajax({
				url: 'ajax/ajax_get_locations.php',
				type: 'post',
				data: { query: query,  autoCompleteClassName:'autocomplete',
				selectedClassName:'sel',
				attrCallBack:'rel',
				identifier:'estado'},
				dataType: 'json',
					success: function (result) {
					var resultList = result.map(function (item) {
					var aItem = { id: item.id, name: item.label};
					return JSON.stringify(aItem);
					});
					return process(resultList);
				}
			});
		},
		matcher: function (obj) {
			var item = JSON.parse(obj);
			return ~item.name.toLowerCase().indexOf(this.query.toLowerCase())
		},
		sorter: function (items) {      
			var beginswith = [], caseSensitive = [], caseInsensitive = [], item;
			while (aItem = items.shift()) {
				var item = JSON.parse(aItem);
				if (!item.name.toLowerCase().indexOf(this.query.toLowerCase())) beginswith.push(JSON.stringify(item));
				else if (~item.name.indexOf(this.query)) caseSensitive.push(JSON.stringify(item));
				else caseInsensitive.push(JSON.stringify(item));
			}
			return beginswith.concat(caseSensitive, caseInsensitive)
		},
		highlighter: function (obj) {
			var item = JSON.parse(obj);
			var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
			var locvalue=item.name.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
				return '<strong>' + match + '</strong>'
			})
			return locvalue;
		},
		updater: function (obj) {
			var item = JSON.parse(obj);
			jQuery('#location_link').attr('value', item.id);
			var nm=item.name;
			jQuery('#locationLink').attr('value', nm);
			return nm;
		}
	}); 
	
    jQuery("#emp_position1").on("change",function(){
        var sel = jQuery("#emp_position1").val();
        jQuery("#emp_position2 option[value='"+sel+"']").css("display","none");
        jQuery("#emp_position2").trigger("liszt:updated");
        jQuery("#emp_position3 option[value='"+sel+"']").css("display","none");
        jQuery("#emp_position3").trigger("liszt:updated");
    })
    
    
    jQuery("#emp_position2").on("change",function(){
        var sel = jQuery("#emp_position2").val();
        jQuery("#emp_position3 option[value='"+sel+"']").css("display","none");
        jQuery("#emp_position3").trigger("liszt:updated");
        jQuery("#emp_position1 option[value='"+sel+"']").css("display","none");
        jQuery("#emp_position1").trigger("liszt:updated");
    })
    
    jQuery("#emp_position3").on("change",function(){
        var sel = jQuery("#emp_position3").val();
        jQuery("#emp_position1 option[value='"+sel+"']").css("display","none");
        jQuery("#emp_position1").trigger("liszt:updated");
        jQuery("#emp_position2 option[value='"+sel+"']").css("display","none");
        jQuery("#emp_position2").trigger("liszt:updated");
    })
	function change_dateOB(){
		var year = '0000';
		var month = '00';
		var day = '00';
	if(jQuery('#dt_YYYY').val()!=""){
		year = jQuery('#dt_YYYY').val();
	}
	if(jQuery('#dt_MM').val()!=""){
		month = jQuery('#dt_MM').val();
	}
	if(jQuery('#dt_DD').val()!=""){
		day = jQuery('#dt_DD').val();
	}	
	var date = year+'-'+month+'-'+day;
	jQuery('#dob').val(date);
	
	}
  function hide_load() {
            $('#fancybox-loading').css('display', 'none');
            $('#fancybox-overlay').css('display', 'none');
        }
</script>
<script>
jQuery(document).ready(function(){
		
		   
		  jQuery( "#Previous_Job_1_Company" ).on("keyup",function() {
			    var job1val = jQuery("#Previous_Job_1_Company").val();
				if(job1val){
				 jQuery(".job1").show();
				}else{
				jQuery(".job1").hide();
				}			
		   });
		   
		   jQuery( "#Previous_Job_2_Company" ).on("keyup",function() {
			     var job2val = jQuery("#Previous_Job_2_Company").val();
			   if(job2val){
			  jQuery(".job2").show();	
				}else{
					jQuery(".job2").hide();
				}			
		   });
		   
		    jQuery( "#Previous_Job_3_Company" ).on("keyup",function() {
				  var job3val = jQuery("#Previous_Job_3_Company").val();
			  if(job3val){
			  jQuery(".job3").show();	
				}else{
					jQuery(".job3").hide();
				}		
		   });        
    })
	function change_date_job1SD(){
		var year = '0000';
		var month = '00';
		var day = '01';
	if(jQuery('#dt1SD_YYYY').val()!=""){
		year = jQuery('#dt1SD_YYYY').val();
	}
	if(jQuery('#dt1SD_MM').val()!=""){
		month = jQuery('#dt1SD_MM').val();
	}
		
	var date = year+'-'+month+'-'+day;
	jQuery('#Previous_Job_1_Startdate').val(date);
	
	}
	
	function change_date_job1ED(){
		var year = '0000';
		var month = '00';
		var day = '01';
	if(jQuery('#dt1ED_YYYY').val()!=""){
		year = jQuery('#dt1ED_YYYY').val();
	}
	if(jQuery('#dt1ED_MM').val()!=""){
		month = jQuery('#dt1ED_MM').val();
	}
		
	var date = year+'-'+month+'-'+day;
	//alert(date);
	jQuery('#Previous_Job_1_Enddate').val(date);
	
	}
	
	function change_date_job2SD(){
		var year = '0000';
		var month = '00';
		var day = '01';
	if(jQuery('#dt2SD_YYYY').val()!=""){
		year = jQuery('#dt2SD_YYYY').val();
	}
	if(jQuery('#dt2SD_MM').val()!=""){
		month = jQuery('#dt2SD_MM').val();
	}
		
	var date = year+'-'+month+'-'+day;
	//alert(date);
	jQuery('#Previous_Job_2_Startdate').val(date);
	
	}
	
	function change_date_job2ED(){
		var year = '0000';
		var month = '00';
		var day = '01';
	if(jQuery('#dt2ED_YYYY').val()!=""){
		year = jQuery('#dt2ED_YYYY').val();
	}
	if(jQuery('#dt2ED_MM').val()!=""){
		month = jQuery('#dt2ED_MM').val();
	}
			
	var date = year+'-'+month+'-'+day;
	//alert(date);
	jQuery('#Previous_Job_2_Enddate').val(date);
	
	}
	
	function change_date_job3SD(){
		var year = '0000';
		var month = '00';
		var day = '01';
	if(jQuery('#dt3SD_YYYY').val()!=""){
		year = jQuery('#dt3SD_YYYY').val();		
	}
	if(jQuery('#dt3SD_MM').val()!=""){
		month = jQuery('#dt3SD_MM').val();
	}
		
	var date = year+'-'+month+'-'+day;
	//alert(date);
	jQuery('#Previous_Job_3_Startdate').val(date);
	
	}
	
	function change_date_job3ED(){
		var year = '0000';
		var month = '00';
		var day = '01';
	if(jQuery('#dt3ED_YYYY').val()!=""){
		year = jQuery('#dt3ED_YYYY').val();
	}
	if(jQuery('#dt3ED_MM').val()!=""){
		month = jQuery('#dt3ED_MM').val();
	}
			
	var date = year+'-'+month+'-'+day;
	//alert(date);
	jQuery('#Previous_Job_3_Enddate').val(date);
	
	}
jQuery(document).ready(function(){

var foo = function( event ) {
jQuery("#colorbox_img").click();
//alert("fdfd");
};
	

jQuery(document).on("click","#upload_image", function() { 
var clientid= jQuery(this).attr("rel");
/*jQuery("#colorbox_img").attr("href","upload_storepoint_image.php?id="+clientid);
foo();*/
jQuery.ajax({
	url:'upload_storepoint_image.php?id='+clientid,
	type:'POST',
	success:function(data){
		jQuery('#mymodalhtml').html(data);
		jQuery('#StoreimageModal').modal('show');
	}
});
});

jQuery(".colorboximg a").colorbox();

});</script>
<script>jQuery(document).ready(function(){

var foo = function( event ) {
jQuery("#colorbox_imgs").click();
//alert("fdfd");
};
	

jQuery(document).on("click","#upload_images", function() { 
var clientid= jQuery(this).attr("rel");
jQuery("#colorbox_imgs").attr("href","upload_chefedin_image.php?id="+clientid);
foo();


});

jQuery(".colorboximgs a").colorbox();

});</script>
<div id="StoreimageModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: auto;">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 id="myModalLabel">Add/Edit Media</h3>
					</div>
					<div class="modal-body " id="mymodalhtml"></div>
						<div class="modal-footer" style="text-align:center;">
					<button aria-hidden="true" style="color:#333333 !important;" data-dismiss="modal" class="btn">Cancel</button>
					<button class="btn btn-primary" id="save_imgdigital"><span class="icon-ok icon-white"></span> Save Changes</button>
					</div>
				</div>