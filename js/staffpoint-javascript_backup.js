function GetXmlHttpObject()
		{
			var xmlHttp=null;
			try
			  {
			  xmlHttp=new XMLHttpRequest();
			  }
			catch (e)
			  {
				  try
		
				{
				xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
				}
			  catch (e)
				{
				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
			  }
			return xmlHttp;
		
	}
	function ShowCity(fid,toid)
	{
			if(document.getElementById(fid).value!="")
			{
				xmlHttp=GetXmlHttpObject()
				if (xmlHttp==null)
				{
				alert ("Browser does not support HTTP Request")
				return
				} 
				var url="getRegisterState.php?country_id="+document.getElementById(fid).value+"&sid="+Math.random();
				//window.location=url;
				xmlHttp.onreadystatechange=function()
				{
					document.getElementById(toid).length=1;
					if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
					{ 
						mearray=xmlHttp.responseText.split("[]");
						for (i=0;i<mearray.length;i++)
						{
							if(mearray[i]!="")
							{
								valcat=mearray[i].split("{}");
								oOption = document.createElement("Option");
								oOption.text =valcat[1];
								oOption.value = valcat[0];
								document.getElementById(toid).options.add(oOption);
							}
						}
					}
		
				} 
				xmlHttp.open("GET",url,true)
				xmlHttp.send(null)
			}
			else
			{
				document.getElementById(toid).length=1;
			}
}
function informationFormReset()
{
		document.getElementById('informationForm').reset();
}
function locationLogin(backurl)
{
		if(document.getElementById('loc_email').value=='')
	{
		alert("Please enter email");
		return;
	}
	var remail=/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;   // Regular expression to check email format
	if(!remail.test(document.getElementById('loc_email').value))
		{
			alert("Enter a valid email address");
			return ;
		}
	if(document.getElementById('loc_password').value=='')
	{
		alert("Please enter password");
		return;
	}
	//document.getElementById('location_log').submit();
	
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	{
	alert ("Browser does not support HTTP Request")
	return
	} 
	var url="location-login-process.php?loc_email="+document.getElementById('loc_email').value+"&sid="+Math.random()+"&loc_password="+document.getElementById('loc_password').value+"&loc_id="+document.getElementById('loc_id').value;
	//window.location=url;
	xmlHttp.onreadystatechange=function()
	{
		
		if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{ 
			if(xmlHttp.responseText==0)
			{
				document.getElementById('invError').style.display='';
			}
			else
			{
					if(backurl=='')
					{
						window.location='location_home.php';
					}
					else
					{
							window.location=backurl;
					}
			}
		}

	} 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)

}
function locationMessage()
{
	if(document.getElementById('title_msg').value=='')
	{
		alert("Please enter title");
		return;
	}
	
	if(document.getElementById('message_msg').value=='')
	{
		alert("Please enter message");
		return;
	}
	document.getElementById('location_msg').submit();
}

function informationFormEdit()
{
		
		if(document.getElementById('password').value=='')
	{
		alert("Please enter password");
		return;
	}
		if(document.getElementById('salutation').value=='')
	{
		alert("Please select Salutation");
		return;
	}
	if(document.getElementById('first_name').value=='')
	{
		alert("Please enter First Name");
		return;
	}
	if(document.getElementById('last_name').value=='')
	{
		alert("Please enter Last Name");
		return;
	}
	if(document.getElementById('address').value=='')
	{
		alert("Please enter Address");
		return;
	}
	if(document.getElementById('address').value.length<2)
	{
		alert("Please enter a valid address");
		return;
	}
	if(document.getElementById('zip').value=='')
	{
		alert("Please enter Zip code");
		return;
	}
	if(document.getElementById('zip').value.length<5)
	{
		alert("Please enter a valid Zip code");
		return;
	}
	if(document.getElementById('city').value=='')
	{
		alert("Please enter City");
		return;
	}
	if(document.getElementById('country').value=='')
	{
		alert("Please select country");
		return;
	}
	if(document.getElementById('state').value=='')
	{
		alert("Please select state");
		return;
	}
	if(document.getElementById('phone').value=='')
	{
		alert("Please enter phone");
		return;
	}
	if(document.getElementById('phone').value.length<10)
	{
		alert("Phone number does not meet 10 digit minimum requirement.");
		return;
	}
	var numberRegex = /^[0-9_\.\-\+\(\)\[\]\s]+$/;
	
	if(!numberRegex.test(document.getElementById('phone').value))
	{
		 alert("Please enter a valid 10 digit phone number");
		return;
	}
	document.getElementById('informationForm').submit();
}
function informationFormSubmit()
{
	if(document.getElementById('email').value=='')
	{
		alert("Please enter email");
		return;
	}
	var remail=/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;   // Regular expression to check email format
	if(!remail.test(document.getElementById('email').value))
	{
		alert("Please enter a valid email address");
		return ;
	}
	if(document.getElementById('password').value=='')
	{
		alert("Please enter password");
		return;
	}
	if(document.getElementById('conpassword').value=='')
	{
		alert("Please enter confirm password");
		return;
	}
	if(document.getElementById('password').value!=document.getElementById('conpassword').value)
	{
		alert("Please enter equal password and confirm password");
		return;
	}
	
	if(document.getElementById('salutation').value=='')
	{
		alert("Please select Salutation");
		return;
	}
	if(document.getElementById('first_name').value=='')
	{
		alert("Please enter First Name");
		return;
	}
	if(document.getElementById('last_name').value=='')
	{
		alert("Please enter Last Name");
		return;
	}
	if(document.getElementById('address').value=='')
	{
		alert("Please enter Address");
		return;
	}
	
	if(document.getElementById('address').value.length<2)
	{
		alert("Please enter a valid address");
		return;
	}
	if(document.getElementById('zip').value=='')
	{
		alert("Please enter Zip code");
		return;
	}
	if(document.getElementById('zip').value.length<5)
	{
		alert("Please enter a valid Zip code");
		return;
	}
	if(document.getElementById('city').value=='')
	{
		alert("Please enter City");
		return;
	}
	if(document.getElementById('country').value=='')
	{
		alert("Please select country");
		return;
	}
	
	if($('#state option').length>1 && $("#state").val()=='')
   {
		alert("Select State");
		return;
	}
	
	if(document.getElementById('phone').value=='')
	{
		alert("Please enter phone");
		return;
	}
	
	if(document.getElementById('phone').value.length<10)
	{
		alert("Phone number does not meet 10 digit minimum requirement.");
		return;
	}
	var numberRegex = /^[0-9_\.\-\+\(\)\[\]\s]+$/;
	
	if(!numberRegex.test(document.getElementById('phone').value))
	{
		 alert("Please enter a valid 10 digit phone number");
		return;
	}
	if(document.getElementById('entercode').value=='')
	{
		alert("Please enter Code");
		return;
	}
	
	
	
if(document.getElementById('image').value!='')
{
var fileName=document.getElementById('image').value;
var ext = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
if(ext !="jpg" && ext != "jpeg")
{
alert("You must upload JPEG file");
return;
}
}

if(document.getElementById('resume').value!='')
{
var fileName=document.getElementById('resume').value;
var ext = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
//alert(ext);
if(ext !="pdf" && ext != "doc" && ext != "docx")
{
alert("You must upload PDF or DOC file or DOCX file");
return;
}
}


	
	
	
	
	
	
	
	
	
	
	
	xmlHttp=GetXmlHttpObject()
		if (xmlHttp==null)
		{
		alert ("Browser does not support HTTP Request")
		return
		} 
		var url="validate-email.php?email="+document.getElementById('email').value+"&sid="+Math.random();
		//window.location=url;
		xmlHttp.onreadystatechange=function()
		{
			
			if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
			{ 
				
				if(xmlHttp.responseText=='0')
				{
							document.getElementById('informationForm').submit();
				}
				else if(xmlHttp.responseText=='1')
				{
					alert("Please enter a valid email address");
					return;
				}
				else
				{
						alert("The email address that you have entered has been used to register perviously.\n If you forgot your password click the forgot password link at the top of the page. ");
						return;
				}
				
			}

		} 
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	
	

}

function loginValidForm()
{
	document.getElementById('loginForm').submit();
}
function showMessage(empmaster_id,location_job_id)
{
		xmlHttp=GetXmlHttpObject()
		if (xmlHttp==null)
		{
		alert ("Browser does not support HTTP Request")
		return
		} 
		var url="message-all.php?empmaster_id="+empmaster_id+"&sid="+Math.random()+"&location_job_id="+location_job_id;
		//window.location=url;
		xmlHttp.onreadystatechange=function()
		{
			
			if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
			{ 
				
				document.getElementById('message_view').innerHTML=xmlHttp.responseText;
				$(".messageEvent").click(function(){
						var stringid=$(this).attr("id");
						showSingleMessage(stringid);
					});
					$(".messageEvent").hover(
					function () {
						$(this).addClass("underline");
					},
					function () {
						$(this).removeClass("underline");
					}
					);
					
					messageShow();
					
			}

		} 
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	
}

function showLocationMessage(location_id,location_job_id)
{
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	{
	alert ("Browser does not support HTTP Request")
	return
	} 
	var url="message-location.php?location_id="+location_id+"&sid="+Math.random()+"&location_job_id="+location_job_id;
	//window.location=url;
	xmlHttp.onreadystatechange=function()
	{
		
		if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{ 
			
			document.getElementById('message_view').innerHTML=xmlHttp.responseText;
			$(".messageEvent").click(function(){
							var stringid=$(this).attr("id");
							showSingleMessage(stringid);
						});
						$(".messageEvent").hover(
						function () {
							$(this).addClass("underline");
						},
						function () {
							$(this).removeClass("underline");
						}
						);
						
			messageShow();
			
		}

	} 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}


function showSingleMessage(message_id)
{
		xmlHttp=GetXmlHttpObject()
		if (xmlHttp==null)
		{
		alert ("Browser does not support HTTP Request")
		return
		} 
		var url="message-desc.php?message_id="+message_id+"&sid="+Math.random();
		//window.location=url;
		xmlHttp.onreadystatechange=function()
		{
			
			if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
			{ 
				
				document.getElementById('message_desc').innerHTML=xmlHttp.responseText;
				
						
			}

		} 
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
}
function ShowJob(fid,toid,empmaster_id)
{
			if(document.getElementById(fid).value!="")
			{
				xmlHttp=GetXmlHttpObject()
				if (xmlHttp==null)
				{
				alert ("Browser does not support HTTP Request")
				return
				} 
				
				var url="getJob.php?location_id="+document.getElementById(fid).value+"&sid="+Math.random()+"&empmaster_id="+empmaster_id;
				//window.location=url;
				xmlHttp.onreadystatechange=function()
				{
					document.getElementById(toid).length=1;
					if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
					{ 
						mearray=xmlHttp.responseText.split("[]");
						for (i=0;i<mearray.length;i++)
						{
							if(mearray[i]!="")
							{
								valcat=mearray[i].split("{}");
								oOption = document.createElement("Option");
								oOption.text =valcat[1];
								oOption.value = valcat[0];
								document.getElementById(toid).options.add(oOption);
							}
						}
					}
		
				} 
				xmlHttp.open("GET",url,true)
				xmlHttp.send(null)
			}
			else
			{
				document.getElementById(toid).length=1;
			}
}
function locationMessageSave()
{
	if(document.getElementById('location_id_msg').value=='')
	{
		alert("Select Location");
		return;
	}
	
	if(document.getElementById('job_msg').value=='')
	{
		alert("Select Job");
		return;
	}
	if(document.getElementById('title_msg').value=='')
	{
		alert("Please enter title");
		return;
	}
	
	if(document.getElementById('message_msg').value=='')
	{
		alert("Please enter message");
		return;
	}
	document.getElementById('location_save_msg').submit();
}


function postJobs()
{
	if(document.getElementById('status').value=='')
	{
		alert("Select status");
		return;
	}
	
	if(document.getElementById('job').value=='')
	{
		alert("Please enter job");
		return;
	}
	if(document.getElementById('job_id').value=='')
	{
		alert("Select job type");
		return;
	}
	if(document.getElementById('description').value=='')
	{
		alert("Please enter description");
		return;
	}
	
	if(document.getElementById('requirements').value=='')
	{
		alert("Please enter requirements");
		return;
	}
	if(document.getElementById('start_date').value=='')
	{
		alert("Please enter start date");
		return;
	}
	if(document.getElementById('end_date').value=='')
	{
		alert("Please enter end date");
		return;
	}
	if(document.getElementById('contact').value=='')
	{
		alert("Please enter contact");
		return;
	}
	if(document.getElementById('type').value=='')
	{
		alert("Please enter type");
		return;
	}
	
	document.getElementById('location_log').submit();
}

function ShowEmployee(fid,toid,location_id)
{
			if(document.getElementById(fid).value!="")
			{
				xmlHttp=GetXmlHttpObject()
				if (xmlHttp==null)
				{
				alert ("Browser does not support HTTP Request")
				return
				} 
				
				var url="getEmployee.php?location_job_id="+document.getElementById(fid).value+"&sid="+Math.random()+"&location_id="+location_id;
				//window.location=url;
				xmlHttp.onreadystatechange=function()
				{
					document.getElementById(toid).length=1;
					if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
					{ 
						mearray=xmlHttp.responseText.split("[]");
						for (i=0;i<mearray.length;i++)
						{
							if(mearray[i]!="")
							{
								valcat=mearray[i].split("{}");
								oOption = document.createElement("Option");
								oOption.text =valcat[1];
								oOption.value = valcat[0];
								document.getElementById(toid).options.add(oOption);
							}
						}
					}
		
				} 
				xmlHttp.open("GET",url,true)
				xmlHttp.send(null)
			}
			else
			{
				document.getElementById(toid).length=1;
			}
}

function locationEmployeeSave()
{
	if(document.getElementById('location_job_id').value=='')
	{
		alert("Select Job");
		return;
	}
	
	if(document.getElementById('empmaster_id').value=='')
	{
		alert("Select Employee");
		return;
	}
	if(document.getElementById('title_msg').value=='')
	{
		alert("Please enter title");
		return;
	}
	
	if(document.getElementById('message_msg').value=='')
	{
		alert("Please enter message");
		return;
	}
	document.getElementById('location_save_msg').submit();
}
function informationLocationSubmit()
{
	if(document.getElementById('representative').value=='')
	{
		alert("Please enter Representative");
		return;
	}
	
	if(document.getElementById('representative_title').value=='')
	{
		alert("Please enter Representative Title");
		return;
	}
	if(document.getElementById('location_name').value=='')
	{
		alert("Please enter Location Name");
		return;
	}
	if(document.getElementById('address').value=='')
	{
		alert("Please enter Address");
		return;
	}
	if(document.getElementById('address').value.length<2)
	{
		alert("Please enter a address");
		return;
	}
	if(document.getElementById('zip').value=='')
	{
		alert("Please enter zip");
		return;
	}
	if(document.getElementById('zip').value.length<5)
	{
		alert("Please enter a zip");
		return;
	}
	if(document.getElementById('city').value=='')
	{
		alert("Please enter city");
		return;
	}
	if(document.getElementById('country').value=='')
	{
		alert("Select country");
		return;
	}
	
	if(document.getElementById('state').value=='')
	{
		alert("Select state");
		return;
	}
	
	if(document.getElementById('phone').value=='')
	{
		alert("Please enter phone");
		return;
	}
	if(document.getElementById('phone').value.length<10)
	{
		alert("Phone number does not meet 10 digit minimum requirement.");
		return;
	}
	var numberRegex = /^[0-9_\.\-\+\(\)\[\]\s]+$/;
	
	if(!numberRegex.test(document.getElementById('phone').value))
	{
		 alert("Please enter a valid 10 digit phone number");
		return ;
	}
	if(document.getElementById('email').value=='')
	{
		alert("Please enter email");
		return;
	}
	var remail=/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;   // Regular expression to check email format
	if(!remail.test(document.getElementById('email').value))
	{
		alert("Enter a valid email address");
		return ;
	}
	if(document.getElementById('rating').value=='')
	{
		alert("Select rating");
		return;
	}
	//saurav image
		document.getElementById('informationForm').submit();
}
function showLoc(locid)
{
		$('.locationDisplay').attr('href','location-display.php?id='+locid);
		$("a.locationDisplay").trigger('click');
}

function scheduleDelete(option)
{
		if(option=='delete_dates')
		{
				if(document.getElementById('start_date').value=='')
				{
					alert("Please enter Start Date");
					return;
				}
				if(document.getElementById('end_date').value=='')
				{
					alert("Please enter End Date");
					return;
				}
				
				var SDate1 =  document.getElementById('start_date').value;
				 var EDate1 =  document.getElementById('end_date').value;
				 var endDate1 = new Date(EDate1);    	
				 var startDate1= new Date(SDate1);
				 if(startDate1 > endDate1)
				{
					alert("Please enter end date greater then start date");
					return;
				}
				
				if(document.getElementById('start_time').value=='' )
				{
					alert("Please enter Start Time");
					return;
				}
						
				if(document.getElementById('end_time').value=='')
				{
					alert("Please enter End Time");
					return;
				}
		
		}
		
		if(confirm("Please confirm.\nDo you want to delete this schedule?"))
		{
			
			$('#option').val(option);
			document.getElementById('location_save_msg').submit();
		}		
}
function scheduleEdit()
{
		if(document.getElementById('start_date').value=='')
		{
			alert("Please enter Start Date");
			return;
		}
		if(document.getElementById('end_date').value=='')
		{
			alert("Please enter End Date");
			return;
		}
		
		var SDate1 =  document.getElementById('start_date').value;
		 var EDate1 =  document.getElementById('end_date').value;
		 var endDate1 = new Date(EDate1);    	
		 var startDate1= new Date(SDate1);
		 if(startDate1 > endDate1)
		{
			alert("Please enter end date greater then start date");
			return;
		}
		
		if(document.getElementById('start_time').value=='' )
		{
			alert("Please enter Start Time");
			return;
		}
				
		if(document.getElementById('end_time').value=='')
		{
			alert("Please enter End Time");
			return;
		}
		document.getElementById('location_save_msg').submit();
}

function scheduleSave()
{
	
	  
		if(document.getElementById('availability_type').value=='' )
		{
			alert("Please select Availibility Type");
			return;
		}
		if(document.getElementById('start_date').value=='')
		{
			alert("Please enter Start Date");
			return;
		}
		var SDate1 =  new Date();
		 var EDate1 =  document.getElementById('start_date').value;
		 var endDate1 = new Date(EDate1);    	
		 var startDate1= new Date(SDate1);
		  
		 if(startDate1 > endDate1)
		{
			alert("When entering start date enter 2 days after today's date.");
			return;
		}
		if(document.getElementById('end_date').value=='')
		{
			alert("Please enter End Date");
			return;
		}
		
		var SDate1 =  document.getElementById('start_date').value;
		 var EDate1 =  document.getElementById('end_date').value;
		 var endDate1 = new Date(EDate1);    	
		 var startDate1= new Date(SDate1);
		 if(startDate1 > endDate1)
		{
			alert("Please enter end date greater then start date");
			return;
		}
		
		if(document.getElementById('start_time').value=='' )
		{
			alert("Please enter Start Time");
			return;
		}
				
		if(document.getElementById('end_time').value=='')
		{
			alert("Please enter End Time");
			return;
		}
		
		
		
		document.getElementById('location_save_msg').submit();
}
function addDays(day)
{
	if($('#'+day).val()=='N')
	{
			$('#'+day).val('Y');
			$('#'+day+'_link').addClass('sel');
	}
	else
	{
			$('#'+day).val('N');
			$('#'+day+'_link').removeClass('sel');
	}
}
function ScheduleOption(option)
{
		$('#schedule_option').val(option);
		$('.editSchedule').attr('href','scheduling_new-schedule.php?option='+$('#schedule_option').val());
		$("a.editSchedule").trigger('click');
}
function ScheduleDisplay(id)
{
		$('.editSchedule').attr('href','scheduling_new-schedule.php?option=edit&id='+id);
		$("a.editSchedule").trigger('click');
}
function showEmp(id,job)
{
		$('.empDisplay').attr('href','employee-display.php?id='+id+'&job_id='+job);
		$("a.empDisplay").trigger('click');
}
function helpSave()
{
		if(document.getElementById('name').value=='')
		{
			alert("Please enter name");
			return;
		}
		if(document.getElementById('email').value=='')
		{
			alert("Please enter email");
			return;
		}
		var remail=/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;   // Regular expression to check email format
		if(!remail.test(document.getElementById('email').value))
		{
			alert("Enter a valid email address");
			return ;
		}
		if(document.getElementById('phone').value=='')
		{
			alert("Please enter phone");
			return;
		}
		if(document.getElementById('phone').value.length<10)
		{
			alert("Phone number does not meet 10 digit minimum requirement.");
			return;
		}
		var numberRegex = /^[0-9_\.\-\+\(\)\[\]\s]+$/;
	
	if(!numberRegex.test(document.getElementById('phone').value))
	{
		 alert("Please enter a valid 10 digit phone number");
		return ;
	}
		if(document.getElementById('message').value=='')
		{
			alert("Please enter message");
			return;
		}
		document.getElementById('ContactForm').submit();
}
function helpReset()
{
	document.getElementById('ContactForm').reset();
}


function informationInsertLocationSubmit()
{
	
		if(document.getElementById('email').value=='')
	{
		alert("Please enter email");
		return;
	}
	var remail=/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;   // Regular expression to check email format
	if(!remail.test(document.getElementById('email').value))
	{
		alert("Enter a valid email address");
		return ;
	}
	if(document.getElementById('password').value=='')
	{
		alert("Please enter password");
		return;
	}
	if(document.getElementById('conpassword').value=='')
	{
		alert("Please enter confirm password");
		return;
	}
	if(document.getElementById('password').value!=document.getElementById('conpassword').value)
	{
		alert("Please enter equal password and confirm password");
		return;
	}
	
	if(document.getElementById('representative').value=='')
	{
		alert("Please enter Representative");
		return;
	}
	
	if(document.getElementById('representative_title').value=='')
	{
		alert("Please enter Representative Title");
		return;
	}
	if(document.getElementById('location_name').value=='')
	{
		alert("Please enter Location Name");
		return;
	}
	if(document.getElementById('address').value=='')
	{
		alert("Please enter Address");
		return;
	}
	if(document.getElementById('address').value.length<2)
	{
		alert("Please enter a address");
		return;
	}
	if(document.getElementById('zip').value=='')
	{
		alert("Please enter zip");
		return;
	}
	if(document.getElementById('zip').value.length<5)
	{
		alert("Please enter a zip");
		return;
	}
	if(document.getElementById('city').value=='')
	{
		alert("Please enter city");
		return;
	}
	if(document.getElementById('country').value=='')
	{
		alert("Select country");
		return;
	}
	
	if($('#state option').length>1 && $("#state").val()=='')
   {
		alert("Select State");
		return;
	}
	
	
	if(document.getElementById('phone').value=='')
	{
		alert("Please enter valid phone number");
		return;
	}
	if(document.getElementById('phone').value.length<10)
	{
		alert("Phone number does not meet 10 digit minimum requirement.");
		return;
	}
	var numberRegex = /^[0-9_\.\-\+\(\)\[\]\s]+$/;
	
	if(!numberRegex.test(document.getElementById('phone').value))
	{
		 alert("Please enter a valid 10 digit phone number");
		return ;
	}

	if(document.getElementById('rating').value=='')
	{
		alert("Select rating");
		return;
	}
	if(document.getElementById('entercode').value=='')
	{
		alert("Please enter Security Code");
		return;
	}
	
		document.getElementById('informationForm').submit();
}

function forgotPassword()
{
	if(document.getElementById('for_email').value=='')
	{
		alert("Please enter email");
		return;
	}
	var remail=/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;   // Regular expression to check email format
	if(!remail.test(document.getElementById('for_email').value))
	{
		alert("Enter a valid email address");
		return ;
	}
	

xmlHttp=GetXmlHttpObject()
		if (xmlHttp==null)
		{
		alert ("Browser does not support HTTP Request")
		return
		} 
		var url="forgot-email.php?email="+document.getElementById('for_email').value+"&sid="+Math.random();
		//window.location=url;
		xmlHttp.onreadystatechange=function()
		{
			
			if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
			{ 
				
				if(xmlHttp.responseText=='0')
				{
						document.getElementById('invError').style.display='';
						document.getElementById('invSuccess').style.display='none';
				}
				else
				{
						document.getElementById('invSuccess').style.display='';
						document.getElementById('invError').style.display='none';
				}
				
			}

		} 
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)

}


function forgotLocationPassword()
{
	if(document.getElementById('for_email').value=='')
	{
		alert("Please enter email");
		return;
	}
	var remail=/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;   // Regular expression to check email format
	if(!remail.test(document.getElementById('for_email').value))
	{
		alert("Enter a valid email address");
		return ;
	}

		xmlHttp=GetXmlHttpObject()
		if (xmlHttp==null)
		{
		alert ("Browser does not support HTTP Request")
		return
		} 
		var url="forgot-location-email.php?email="+document.getElementById('for_email').value+"&sid="+Math.random();
		//window.location=url;
		xmlHttp.onreadystatechange=function()
		{
			
			if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
			{ 
				
				if(xmlHttp.responseText=='0')
				{
						document.getElementById('invError').style.display='';
						document.getElementById('invSuccess').style.display='none';
				}
				else
				{
						document.getElementById('invSuccess').style.display='';
						document.getElementById('invError').style.display='none';
				}
				
			}

		} 
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)

}















function empLogin()
{
	if(document.getElementById('empmail').value=='')
	{
		alert("Please enter email");
		return;
	}
	var remail=/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;   // Regular expression to check email format
	if(!remail.test(document.getElementById('empmail').value))
		{
			alert("Enter a valid email address");
			return ;
		}
	if(document.getElementById('emppassword').value=='')
	{
		alert("Please enter password");
		return;
	}
	//document.getElementById('location_log').submit();
	
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	{
	alert ("Browser does not support HTTP Request")
	return
	} 
	var url="emp-login-process.php?empmail="+document.getElementById('empmail').value+"&sid="+Math.random()+"&emppassword="+document.getElementById('emppassword').value;
	//window.location=url;
	xmlHttp.onreadystatechange=function()
	{
		
		if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{ 
		
			if(xmlHttp.responseText==0)
			{
				document.getElementById('invError').style.display='';
			}
			else
			{
					window.location='profile.php';
			}
		}

	} 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)

}