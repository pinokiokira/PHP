var API_URL = 'ajax/proxy.php?url=';
var global_realod = false;

jQuery(document).ready(function () {
    jQuery('#login').submit(function (e) {
        e.preventDefault();
        var u = jQuery('#username').val();
        var p = jQuery('#password').val();
        var rememberme;
        if (jQuery('#rememberme').is(':checked')) {
            rememberme = "checked";
        } else rememberme = "";

        if (u == '' && p == '') {
            jQuery('.login-alert').fadeIn();
        } else {
            jQuery.blockUI({ message: null });
			//juni -> 06.07.2014 -> removed block of code and added from admin panel in index.js
           /* jQuery.ajax({
                url: 'setCookie.php',
                data: {
                    username: u,
                    rememberme: rememberme
                },
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {

                }
            })*/
            jQuery.ajax({
                url: API_URL + 'login_process.php',
                data: {
                    username: u,
                    password: p
                },
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {
                    if (data.success) {
						//console.log(data);
                       jQuery.post('require/session.php', {
                            client_id: data.response.client_id,
                            password: jQuery('#password').val(),
                            first_name: data.response.first_name,
                            last_name: data.response.last_name,
                            name: data.response.name,
                            email: data.response.email,
                            image: data.response.image,
                            StorePoint: data.response.StorePoint,
                            ChefedIN: data.response.ChefedIN,
                            StylistFN: data.response.StylistFN,
                            DeliveryPoint: data.response.DeliveryPoint,
                            StorePointVendorID: data.response.StorePointVendorID,
                            ChefedIN_Business_Name: data.response.ChefedIN_Business_Name,
                            latitude:jQuery("#latitude").val(),
                            longitude:jQuery("#longitude").val()
                        }, function () {
                           //alert("ppp"); window.location.href = 'dashboard.php';
                        });
                    } else {
                        jQuery.unblockUI();
                        jQuery('.login-alert').fadeIn();
                        loginError(data.code);
                    }
                }
            })
        }
    });

    var userdata, vendordata;
    if (jQuery('#clientDiv').length > 0) {
        jQuery(document).ready(function () {
            getClientInfo();
        });
    }

    if (jQuery('#locationsDiv').length > 0) {
        jQuery(document).ready(function () {
			statuswhr = jQuery('#locationsDiv').attr('statuswhr');
            getLocationInfo(statuswhr);


        });
    }

    function getClientInfo() {

        //jQuery.blockUI({ css: { backgroundColor: 'none', border: 'none' }, message: '<img alt="" src="images/loaders/loader6.gif">' });
        jQuery.ajax({
            url: API_URL + 'return_client.php',
            data: {
                client_id: client_id
            },
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
                userdata = data;

                jQuery.unblockUI();
            }
        });
    }

    function getLocationInfo(statuswhr) {console.log(API_URL + 'return_location.php');

        jQuery.blockUI({ message: null });
        jQuery.ajax({
            url: API_URL + 'return_location.php',
            data: {
                client_id: client_id,
				statuswhr:statuswhr
            },
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
				jQuery.unblockUI();
                if (data.success) {

                    jQuery("#LocationsAssociatedWithEmployee").html(data.LocationsAssociatedWithEmployee);
                    jQuery("#LocationsLinkedWithEmployee").html(data.LocationsLinkedWithEmployee);
                }
                else {
                    alert('Invalid data');
                }
                
            }
        });
    }

    if (jQuery('#change_photo_option').length > 0) {
        jQuery("#change_photo_option").colorbox({
            href: 'ajax/profile-photo-form.php',
            title: '<h4 class="widgettitle title-primary">Change Profile Photo</h4>',
            width: '500px',
            height: '220px',
            initialWidth: '200px',
            initialHeight: '100px',
            onComplete: function () {
                jQuery('#profile_photo_client_id').val(client_id);
                jQuery('#email_profile_photo').val(jQuery('#current_email').val());

                jQuery('#file_upload_close').live('click', function () {
                    jQuery.fn.colorbox.close();
                });

                jQuery('#file_upload_submit').live('click', function () {
                    jQuery.blockUI({ message: null });
                    jQuery("#imageform").ajaxForm({
                        target: '#preview',
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.code == 0) {
                                getClientInfo();
                                //jQuery.alerts.dialogClass = 'alert-inverse';
                                jAlert('Profile Updated Successfully!', 'Update Login Info', function () {
                                    jQuery.alerts.dialogClass = null; // reset to default
                                    jQuery.fn.colorbox.close();
									if(global_realod){
										window.location.reload();															  
									}
                                });
                            }
                            jQuery.unblockUI();
                        }
                    }).submit();
                });
            }
        });
    }

    if (jQuery('#remove_photo_option').length > 0) {
        jQuery('#remove_photo_option').click(function () {
            jConfirm('You are about to remove profile photo, Are you sure?', 'Remove Photo', function (r) {
                if (r) {
                    var cm = jQuery('#current_email').val();
                    jQuery.blockUI({ message: null });
                    jQuery.ajax({
                        url: API_URL + 'delete_profile_photo',
                        data: {
                            client_id: client_id,
                            email: cm
                        },
                        type: 'POST',
                        dataType: 'JSON',
                        success: function (data) {
                            jQuery.unblockUI();
                            getClientInfo();
                        }
                    });
                }
            });
        });
    }

    if (jQuery('#editlogininfoform').length > 0) {

        jQuery('#show_password').click(function () {
            if (jQuery(this).is(':checked')) {
                jQuery('#password_text').show();
                jQuery('#password').hide();
            }
            else {
                jQuery('#password').show();
                jQuery('#password_text').hide();
            }
        });

        jQuery('#password').keyup(function () {
            jQuery('#password_text').val(jQuery('#password').val());
        });
        jQuery('#password_text').keyup(function () {
            jQuery('#password').val(jQuery('#password_text').val());
        });

        jQuery("#editlogininfoform").validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: API_URL + 'check_availability',
                        data: {
                            email: jQuery('#signup_email').val(),
                            client_id: client_id
                        }
                    }
                },
                password: { //juni -> 04.07.2014 -> new criteria
					 required: true,
					 pwcheck: true,
					 minlength: 6
					  ,notEqualTo: "#signup_email"
				 },
                confirm_password: { required: true, equalTo: "#password" }
            },
            messages: {
                signup_email: {
                    required: "Please enter a email address",
                    email: "Please enter a valid email address",
                    remote: "Already exists, please choose different one"
                },
                //password:  "Please enter a valid password",
                password:  "",
                confirm_password: { required: "Please enter confirm password", equalTo: "Your password does not match!" }
            },
            highlight: function (label) {
                jQuery(label).closest('span').addClass('error');
            },
            errorPlacement: function (error, element) {
                //jQuery(element).attr({"title": error.append()});
                if (jQuery(element).attr('id') == 'password') {
                    error.insertAfter("#password_text");
                }
                else {
                    error.insertAfter(element);
                }
            },
            errorElement: 'span'
        });
		//juni -> 04.07.2014 -> new criteria(s)
		jQuery.validator.addMethod("pwcheck", function(value) {
			return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
			&& /[a-z]/.test(value) // has a lowercase letter
			&& /\d/.test(value) // has a digit
		});
		jQuery.validator.addMethod("notEqualTo", function (value, element, param)	{
			var target = jQuery(param);
			if (value) return value != target.val();
			else return this.optional(element);
		}, "Does not match");
		
        jQuery('#update_login_info').on('click',function (event) {
            event.preventDefault();
			var pswd = jQuery('#password_text').val();
			 //juni -> 09.07.2014
				if(jQuery("#password_text").val()=='')	{
						jAlert('Please Enter Password!','Alert')
						return false;
				}else if(jQuery("#confirmpassword_text").val()=='')	{
						jAlert('Please Enter Confirm Password!','Alert')
						return false;
				}else if(jQuery("#password_text").val() != jQuery("#confirmpassword_text").val())	{
						jAlert('Please Enter Same Password in confirm password!','Alert')
						return false;
					} else if (jQuery("#password_text").val().length < 6) { //juni -> 09.07.2014
					jAlert("Please enter at least 6 characters!","Alert Dialog");	
					return false;				
				} else if (pswd.length < 6) { //->juni [req 1.37]	 //validate password
					jAlert("Please enter at least 6 characters!","Alert Dialog");	
					return false;				
				} else if (!pswd.match(/[A-z]/) ) {//validate letter
					jAlert("Please enter at least one letter!","Alert Dialog");	
					return false;	
				} else if (!(pswd.match(/[A-Z]/) || pswd.match(/[\@#\$\%\^\&*()_+!]/)) ) {//validate capital letter
					jAlert("Please enter at least One Special character or 1 Upper case character!","Alert Dialog");	
					return false;
				} else if (!pswd.match(/\d/) ) {	//validate number
					jAlert("Please enter at least one alpha numeric characther!","Alert Dialog");	
					return false;
				} else if (jQuery("#last_five").val() == "1") { //<-juni [req 1.37]			
					jAlert("You can not use your old password!","Alert Dialog");	
					return false;
				}  else	{		//<- juni 09.07.2014		
					//jQuery("#editlogininfoform").valid()
					if (true) {
						console.log( API_URL + 'client.php');
						var email = jQuery('#email').val();
						var password = jQuery('#password').val();
						var confirm_password = jQuery('#confirm_password').val();
						jQuery.blockUI({ message: null });
						jQuery.ajax({
							url: API_URL + 'client.php',
							data: {
								email: email,
								password: password,
								client_id: client_id
							},
							type: 'POST',
							dataType: 'JSON',
							success: function (data) {
								if (data.success) {
									jQuery('#password').val('');
									jQuery('#confirm_password').val('');
									//jQuery.alerts.dialogClass = 'alert-inverse';
									jAlert('Your login information updated successfully.', 'Update Login Info', function () {
										jQuery.alerts.dialogClass = null; // reset to default
									});
									jQuery('#head_email').html(email);
									jQuery.post('require/session.php', { client_id: client_id, email: email }, function () {
										console.log('session updated');

									});
								}
								jQuery.unblockUI();
							}
						});
					}
				}
        });
    }

    if (jQuery('#client_details_form').length > 0) {

        jQuery('#facebook_id, #google_id, #linkedin_id, #twitter_id').click(function (event) {
            event.preventDefault();
        });

        // Smart Wizard 	
        jQuery('#client_details_form').submit(function () {
            //onFinish: function(){
            var isValid=1;
			
            //if (jQuery("#tabs").tabs('option', 'active')==3){
				console.log(jQuery(".ui-tabs-active a").html());	
				if(jQuery.trim(jQuery(".ui-tabs-active a").html())=='StorePoint'){					
					
                if (jQuery("#StorePoint").val()=="Yes"){					
					if (jQuery("#StorePoint_vendor_id").val()==""){
                        isValid = 0;
                        jAlert("Please insert Vendor ID!");
                        jQuery("#StorePoint_vendor_id").focus();
                    } else 
					if (jQuery("#vendorstatus").val()==""){
                        isValid = 0;
                        jAlert("Please select Vendor Status!");
                        jQuery("#vendorstatus").focus();
                    } else if (jQuery("#vendorname").val()==""){
                        isValid = 0;
                        jAlert("Please select Vendor Name!");
                        jQuery("#vendorname").focus();
                    } else if (jQuery("#vendorcontact").val()==""){
                        isValid = 0;
                        jAlert("Please select Vendor Contact!");
                        jQuery("#vendorcontact").focus();
                    }  else if (jQuery("#vendoraddress").val()==""){
                        isValid = 0;
                        jAlert("Please select Vendor Address!");
                        jQuery("#vendoraddress").focus();
                    } else if (jQuery("#vendorcountry").val()==""){
                        isValid = 0;
                        jAlert("Please select Vendor Country!");
                        jQuery("#vendorcountry").focus();
                    } else if (jQuery("#vendorcity").val()==""){
                        isValid = 0;
                        jAlert("Please select Vendor City!");
                        jQuery("#vendorcity").focus();
                    } else if (jQuery("#vendorstate").val()==""){
                        isValid = 0;
                        jAlert("Please select Vendor State!");
                        jQuery("#vendorstate").focus();
                    } else if (jQuery("#vendorzip").val()==""){
                        isValid = 0;
                        jAlert("Please select Vendor Zip!");
                        jQuery("#vendorzip").focus();
                    } else if (jQuery("#vendorphone").val()==""){
                        isValid = 0;
                        jAlert("Please insert Vendor Phone!");
                        jQuery("#vendorphone").focus();
                    }else if (jQuery("#vendortype").val()=="" || jQuery("#vendortype").val()=== null){
                        isValid = 0;
                        jAlert("Please Select Vendor Type!");
                        jQuery("#vendortype").focus();
                    }else if (jQuery("#vendorterm").val()=="" || jQuery("#vendorterm").val()=== null){
                        isValid = 0;
                        jAlert("Please Select Vendor Term!");
                        jQuery("#vendorterm").focus();
                    }else if (jQuery("#vendorPaymentType").val()=="" || jQuery("#vendorPaymentType").val()=== null){
                        isValid = 0;
                        jAlert("Please Select Vendor Payment Type!");
                        jQuery("#vendorPaymentType").focus();
                    }else if (jQuery("#vendorDeliveryType").val()=="" || jQuery("#vendorDeliveryType").val()=== null){
                        isValid = 0;
                        jAlert("Please Select Vendor Delivery Type!");
                        jQuery("#vendorDeliveryType").focus();
                    }
					
                }
            }
            var form_data = jQuery('#client_details_form').serialize();
            form_data + '&name=' + jQuery('#first_name').val() + ' ' + jQuery('#last_name').val();
            var arr = form_data.split('&');
            var obj = {};

            var languages = '';
            var employment_type = '';
            var Delivery_trasporation = '';
            var chefedin_market = '';
            var vendor_type = '';
			var vendor_term = '';
			var payment_type = '';
			var delivery_type = '';
			
            for (var i = 0; i < arr.length; i++) {
                var bits = arr[i].split('=');
                if (bits[0].search("languages") != -1) {
                    languages += bits[1] + ',';
                }
                else if (bits[0].search("employment_type") != -1) {
                    employment_type += bits[1] + ',';
                }
                else if (bits[0].search("Delivery_trasporation") != -1) {
                    Delivery_trasporation += bits[1] + ',';
                }
                else if (bits[0].search("chefedin_market") != -1) {
                    chefedin_market += bits[1] + ',';
                }
                else if (bits[0].search("vendortype") != -1) {
                    vendor_type += bits[1] + ',';
                }
				else if (bits[0].search("vendorterm") != -1) {
                    vendor_term += bits[1] + ',';
                }
				else if (bits[0].search("vendorPaymentType") != -1) {
                    payment_type += bits[1] + ',';
                }
				else if (bits[0].search("vendorDeliveryType") != -1) {
                    delivery_type += bits[1] + ',';
                }
                else {
                    obj[bits[0]] = bits[1];
                }
            }

            form_data = '';
            for (key in obj) {
                if (key == 'dob_dateLists_year_list' || key == 'dob_dateLists_month_list' || key == 'dob_dateLists_day_list' || key == 'document_issue_date_dateLists_year_list' || key == 'document_issue_date_dateLists_month_list' || key == 'document_issue_date_dateLists_day_list') {
                    continue;
                }
                form_data += key + '=' + obj[key] + '&';
            }
            form_data = form_data.slice(0, form_data.length - 1);
            if (languages.length > 0) {
                languages = languages.slice(0, languages.length - 1);
                form_data += "&languages=" + languages;
            }
            if (employment_type.length > 0) {
                employment_type = employment_type.slice(0, employment_type.length - 1);
                form_data += "&employment_type=" + employment_type;
            }
            if (Delivery_trasporation.length > 0) {
                Delivery_trasporation = Delivery_trasporation.slice(0, Delivery_trasporation.length - 1);
                form_data += "&Delivery_trasporation=" + Delivery_trasporation;
            }
            if (chefedin_market.length > 0) {
                chefedin_market = chefedin_market.slice(0, chefedin_market.length - 1);
                form_data += "&chefedin_market=" + chefedin_market;
            }
            if (vendor_type.length > 0) {
                vendor_type = vendor_type.slice(0, vendor_type.length - 1);
                form_data += "&vendortype=" + vendor_type;
            }
			if (vendor_term.length > 0) {
                vendor_term = vendor_term.slice(0, vendor_term.length - 1);
                form_data += "&vendorterm=" + vendor_term;
            }
			if (payment_type.length > 0) {
                payment_type = payment_type.slice(0, payment_type.length - 1);
                form_data += "&vendorPaymentType=" + payment_type;
            }
			if (delivery_type.length > 0) {
                delivery_type = delivery_type.slice(0, delivery_type.length - 1);
                form_data += "&vendorDeliveryType=" + delivery_type;
            }
            form_data += "&last_by=Self&last_on=VendorPanel";
            
            if (isValid==1){
                jQuery.blockUI({ message: null });
			    jQuery.blockUI({ css: { backgroundColor: 'none', border: 'none' }, message: '<img alt="" src="images/loaders/loader7.gif">' });
                jQuery.ajax({
                    url: API_URL + 'update_client.php',
                    data: form_data,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function (data) {console.log(data);
                        if (data.success) {

                            //jQuery.alerts.dialogClass = 'alert-inverse';
							userdata.StorePoint_vendor_Id = data.StorePoint_vendor_Id;							
							if(data.vendor_ins){
								global_realod = true;
							}	
                            if (data.age < 18) {
                                jAlert(data.underAge,'Alert Dialog',function(r){
								if(r){
									
                            var nm = jQuery('#first_name').val() + ' ' + jQuery('#last_name').val();
                            var ci = jQuery('#client_id').val();							
                            var StorePoint = jQuery('#StorePoint').val();
                            var ChefedIN = jQuery('#ChefedIN').val();
                            var StylistFN = jQuery('#StylistFN').val();
                            var DeliveryPoint = jQuery('#DeliveryPoint').val();
                            jQuery('#head_name').html(nm);
                            jQuery.post('require/session.php', { client_id: ci, name: nm, StorePoint: StorePoint, ChefedIN: ChefedIN, StylistFN: StylistFN, DeliveryPoint: DeliveryPoint,StorePointVendorID:data.StorePoint_vendor_Id }
                                                            , function () {
                                                                console.log('session updated');
                                                                var tab = jQuery('#submitStatusField').val();
                                                                var array = tab.split('-');
                                                                //console.log(tab);
                                                                //console.log(array[0]);

                                                                if (array[0] == 'status') {
												    jQuery.blockUI({ css: { backgroundColor: 'none', border: 'none' }, message: '<img alt="" src="images/loaders/loader7.gif">' });
                                                                    jAlert('Profile Updated Successfully!', 'Edit Profile', function () {
                                                                        if(global_realod){
																		  if(array.length == 2)
                                                                                 window.location.href = 'setup_editprofile.php?tab='+array[1];
                                                                            else
                                                                                location.reload();
																		//parent.location.reload();
																		//window.location.reload();															  
																		}else{
                                                                            if(array.length == 2)
                                                                                 window.location.href = 'setup_editprofile.php?tab='+array[1];
                                                                            else
                                                                                location.reload();
                                                                       // window.location.href = window.location.href;
																		}
                                                                    });
                                                                    //			window.location.href=window.location.href;
                                                                }
                                                            });

							
								}											 
								});

                            }else{
							
                            var nm = jQuery('#first_name').val() + ' ' + jQuery('#last_name').val();
                            var ci = jQuery('#client_id').val();

                            var StorePoint = jQuery('#StorePoint').val();
                            var ChefedIN = jQuery('#ChefedIN').val();
                            var StylistFN = jQuery('#StylistFN').val();
                            var DeliveryPoint = jQuery('#DeliveryPoint').val();
                            jQuery('#head_name').html(nm);
                            jQuery.post('require/session.php', { client_id: ci, name: nm, StorePoint: StorePoint, ChefedIN: ChefedIN, StylistFN: StylistFN, DeliveryPoint: DeliveryPoint,StorePointVendorID:data.StorePoint_vendor_Id }
                                                            , function () {
                                                                console.log('session updated');
                                                                var tab = jQuery('#submitStatusField').val();
                                                                var array = tab.split('-');
                                                                //console.log(array.length);
                                                                //console.log(array[0]);
                                                                if (array[0] == 'status') {
											    jQuery.blockUI({ css: { backgroundColor: 'none', border: 'none' }, message: '<img alt="" src="images/loaders/loader7.gif">' });
                                                                    jAlert('Profile Updated Successfully!', 'Edit Profile', function () {
																		if(global_realod){
                                                                            if(array.length == 2)
																			     window.location.href = 'setup_editprofile.php?tab='+array[1];
                                                                            else
                                                                                location.reload();
																		//window.location.reload();	
																		//location.reload(); 
																		}else{
                                                                            if(array.length == 2)
                                                                                 window.location.href = 'setup_editprofile.php?tab='+array[1];
                                                                            else
                                                                                location.reload();
                                                                        // window.location.href = window.location.href;
																		//location.reload(); 
																		}
                                                                    });
                                                                    //			window.location.href=window.location.href;
                                                                }
                                                            });

							}
						}
						
                        jQuery.unblockUI();
						//fillForm();
                    }
                });
            }
            //}, 
            //enableAllSteps: true, 
            //labelFinish:'Save'

            return false;
        });

        jQuery.ajax({
            url: API_URL + 'return_countryandtype.php',
            data: {},
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
                jQuery('<option>', { value: '', text: '' }).appendTo('#country, #document_country, #country_birth');
                jQuery.each(data.countries, function (i, obj) {
                    jQuery('<option>', { value: obj.id, text: obj.name }).appendTo('#country, #document_country, #country_birth');
                });

				jQuery('#country').trigger("chosen:updated");

                if (userdata) {
                    //fillForm();
					setTimeout(function () { fillForm() }, 2000);
                }
                else {
                    var t = setTimeout(function () { fillForm() }, 2000);
                }
            }
        });
			
        jQuery('#country').change(function () {
            var country_id = jQuery(this).find(":selected").val();
            getStates(country_id);
			
        });
		jQuery.ajax({
            url: API_URL + 'return_currency.php',
            data: {},
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
                jQuery('<option>', { value: '', text: '' }).appendTo('#currency');
                jQuery.each(data.currency, function (i, obj) {
                    jQuery('<option>', { value: obj.id, text: obj.code }).appendTo('#currency');
					
                });

                if (userdata) {
                    fillForm();
                }
                else {
                    var t = setTimeout(function () { fillForm() }, 2000);
                }
				

            }
        });

        jQuery('#vendorcountry').change(function () {
            var country_id = jQuery(this).find(":selected").val();
            getvendorStates(country_id);
			jQuery("#vendorcountry").chosen();
        });

        

        function getStates(country_id, state) {
			
			if(country_id>0){	
			
            if (typeof (state) === 'undefined') state = 0;
			jQuery(".chzn-results").click(function(){
				//jQuery.blockUI({css: { backgroundColor: 'none', border: 'none'}, message: '<img alt="" src="images/loaders/loader7.gif">' }); 
			});
            jQuery.ajax({
                url: API_URL + 'return_states.php',
                data: { country: country_id },
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {
                    jQuery('#state').html('');
					jQuery.unblockUI();
                    jQuery('<option>', { value: '', text: '' }).appendTo('#state');
					for(i=1; i<data.length;i++){
							
							jQuery('<option>', { value: data[i]['id'], text: data[i]['name'] }).appendTo('#state');
					}
                    //jQuery.each(data, function (i, obj) {
                        //jQuery('<option>', { value: obj.id, text: obj.name }).appendTo('#state');
                    //});
                    jQuery("#state").val(state);
                    jQuery("#state").chosen();
                    jQuery("#state").trigger("liszt:updated");
                    
                }
            });
			}
        }

        function fillForm() {
            //userdata = {"id":"1558","status":"A","email":"thisismyemail@test.com","password":"","name":"Marc Guevara","address":"This is my first address","address2":"This is my second address","city":"This is ny city","state":"763","zip":"123456","country":"3","telephone":"5558411349","longitude":"","latitude":"","neighborhood":null,"image":"","sex":"M","dob":"1985-02-15","primarydinning":"This is my primary dinning","primaryschool":"This is my primary school","Signedup":"Y","facebook":"N","facebook_id":"","twitter":null,"ping":"N","access_edu2bsales":"no","email_notifications":"N","push_notificiations":"N","salutation":"Mrs","first_name":"FName","last_name":"LName","name_suffix":null,"specialIns":null,"clientscol1":null,"language":"lang2","smoker":"N","handicap":"N","id_type":null,"id_number":null,"id_country":"3","country_birth":"3","document_type":"type1","document_issue_date":"2005-03-20","document_country":"3","client_expensetab_account_id":null,"created_on":"","date_created":"2013-04-16 13:12:17","last_by":"","last_on":"","last_datetime":"2013-05-23 10:11:57"};
            if(userdata.country!=""){
			getStates(userdata.country, userdata.state);
			}
            if (userdata.image == '') {
                jQuery("#profile_image").attr('src', 'images/default_avatar.png');
            }
            else {
                jQuery("#profile_image").attr('src', userdata.image);
            }

            if (userdata.resume != '') {
                //jQuery("#btn_view_resume").css("display", 'block');
                jQuery("#btn_view_resume").attr('href', API + "images/" + userdata.resume);
                jQuery("#old_resume").val(userdata.resume);
            }
            jQuery("#client_id").val(userdata.id);
            jQuery("#email, #current_email").val(userdata.email);

            jQuery("#salutation").val(userdata.salutation);
            jQuery("#first_name").val(userdata.first_name);
            jQuery("#last_name").val(userdata.last_name);
            jQuery("#sex").val(userdata.sex);
            jQuery("#telephone").val(userdata.telephone);
            jQuery("#mobile").val(userdata.mobile);
            jQuery("#fax").val(userdata.fax);
            jQuery("#address").val(userdata.address);
            jQuery("#address2").val(userdata.address2);
            jQuery("#city").val(userdata.city);
            jQuery("#state").val(userdata.state);
            jQuery("#zip").val(userdata.zip);
            jQuery("#region").val(userdata.region);
            jQuery("#neighborhood").val(userdata.neighborhood);
            jQuery("#country").val(userdata.country);
            //jQuery("#dob").val(userdata.dob);			
			var dates = userdata.dob.split('-');
			jQuery("#dt_YYYY").val(dates[0]);
			jQuery("#dt_MM").val(dates[1]);
			jQuery("#dt_DD").val(dates[2]);
            jQuery("#viewable").val(userdata.viewable);
            jQuery("#activities").val(userdata.activities);
            jQuery("#competencies").val(userdata.competencies);
			
			jQuery("#Previous_Job_1_Company").val(userdata.previousjob1_company);
			jQuery("#Previous_Job_1_Title").val(userdata.previousjob1_title);
			jQuery("#Previous_Job_1_Location").val(userdata.previousjob1_location);
			jQuery("#Previous_Job_1_Startdate").val(userdata.previousjob1_startdate);
			var previousjob1_startdate="";			
			if(userdata.previousjob1_startdate!="" && userdata.previousjob1_startdate!=null){
				previousjob1_startdate = userdata.previousjob1_startdate.split('-');
			}
			jQuery("#dt1SD_YYYY").val(previousjob1_startdate[0]);
			jQuery("#dt1SD_MM").val(previousjob1_startdate[1]);
			jQuery("#Previous_Job_1_Enddate").val(userdata.previousjob1_enddate);
			var previousjob1_enddate ="";			
			if(userdata.previousjob1_enddate!="" && userdata.previousjob1_enddate!=null){
				previousjob1_enddate = userdata.previousjob1_enddate.split('-');
			}
			jQuery("#dt1ED_YYYY").val(previousjob1_enddate[0]);
			jQuery("#dt1ED_MM").val(previousjob1_enddate[1]);				
			jQuery("#Previous_Job_1_Description").val(userdata.previousjob1_description);
			jQuery("#Previous_Job_2_Company").val(userdata.previousjob2_company);
			jQuery("#Previous_Job_2_Title").val(userdata.previousjob2_title);
			jQuery("#Previous_Job_2_Location").val(userdata.previousjob2_location);
			jQuery("#Previous_Job_2_Startdate").val(userdata.previousjob2_startdate);
			var previousjob2_startdate ="";
			if(userdata.previousjob2_startdate!="" && userdata.previousjob2_startdate!=null){
				previousjob2_startdate = userdata.previousjob2_startdate.split('-');
			}
			jQuery("#dt2SD_YYYY").val(previousjob2_startdate[0]);
			jQuery("#dt2SD_MM").val(previousjob2_startdate[1]);
			jQuery("#Previous_Job_2_Enddate").val(userdata.previousjob2_enddate);
			var previousjob2_enddate ="";
			if(userdata.previousjob2_enddate!="" && userdata.previousjob2_enddate!=null){
				previousjob2_enddate = userdata.previousjob2_enddate.split('-');
			}
			jQuery("#dt2ED_YYYY").val(previousjob2_enddate[0]);
			jQuery("#dt2ED_MM").val(previousjob2_enddate[1]);	
			jQuery("#Previous_Job_2_Description").val(userdata.previousjob2_description);
			jQuery("#Previous_Job_3_Company").val(userdata.previousjob3_company);
			jQuery("#Previous_Job_3_Title").val(userdata.previousjob3_title);
			jQuery("#Previous_Job_3_Location").val(userdata.previousjob3_location);
			jQuery("#Previous_Job_3_Startdate").val(userdata.previousjob3_startdate);
			var previousjob3_startdate ="";
			if(userdata.previousjob3_startdate!="" && userdata.previousjob3_startdate!=null){
				previousjob3_startdate = userdata.previousjob3_startdate.split('-');
			}
			jQuery("#dt3SD_YYYY").val(previousjob3_startdate[0]);
			jQuery("#dt3SD_MM").val(previousjob3_startdate[1]);
			jQuery("#Previous_Job_3_Enddate").val(userdata.previousjob3_enddate);
			var previousjob3_enddate ="";
			if(userdata.previousjob3_enddate!="" && userdata.previousjob3_enddate!=null){
				previousjob3_enddate = userdata.previousjob3_enddate.split('-');
			}
			jQuery("#dt3ED_YYYY").val(previousjob3_enddate[0]);
			jQuery("#dt3ED_MM").val(previousjob3_enddate[1]);	
			jQuery("#Previous_Job_3_Description").val(userdata.previousjob3_description);
			
			if(userdata.previousjob1_company){
				 jQuery(".job1").show();
				}else{
				jQuery(".job1").hide();
				}	
			if(userdata.previousjob2_company){
				 jQuery(".job2").show();
				}else{
				jQuery(".job2").hide();
				}	
			if(userdata.previousjob3_company){
				 jQuery(".job3").show();
				}else{
				jQuery(".job3").hide();
				}	
			
			
            jQuery("#education").val(userdata.education);
            jQuery("#Chefedin_Business_Name").val(userdata.Chefedin_Business_Name);			
			if(userdata.ChefedIN_image!="" && userdata.ChefedIN_image!=null){
			jQuery("#old_chefedin_image").attr('src',API+'images/'+userdata.ChefedIN_image);
			jQuery("#old_chefedin_img").val(userdata.ChefedIN_image);
			jQuery("#old_chefedin_image").show();
			}else if(userdata.image !='' && userdata.image!=null){
			jQuery("#old_chefedin_image").attr('src', API+'images/'+userdata.image);
			jQuery("#old_chefedin_image").show();
			jQuery("#old_chefedin_img").val('');
			}else{
			jQuery("#old_chefedin_image").hide();
			jQuery("#old_chefedin_img").val('');
			}
            jQuery("#StylistFN_Company").val(userdata.StylistFN_Company);
            jQuery("#StylistFN_Description").val(userdata.StylistFN_Description);
            jQuery("#StylistFN_Style").val(userdata.StylistFN_Style);
            jQuery("#StylistFN_Located").val(userdata.StylistFN_Located);
            jQuery("#StylistFN_location_id").val(userdata.StylistFN_location_id);
			jQuery("#currency").val(userdata.currency);

            if (userdata.languages != '') {
                var arr_lang = String(userdata.languages).split(",");
                var selected_languages = jQuery("#languages option:selected").map(function () { return this.value }).get();
                for (i = 0; i < arr_lang.length; i++) {
                    selected_languages.push(arr_lang[i]);
                }
                jQuery("#languages").val(selected_languages);
            }
            //jQuery("#employment_type").val(userdata.employment_type);
            if (userdata.employment_type != '') {
                var arr_emp = String(userdata.employment_type).split(",");
                var selected_employment = jQuery("#employment_type option:selected").map(function () { return this.value }).get();
                for (i = 0; i < arr_emp.length; i++) {
                    selected_employment.push(arr_emp[i]);
                }
                jQuery("#employment_type").val(selected_employment);
            }


            jQuery("#emp_position1").val(userdata.emp_position1);
            jQuery("#emp_position2").val(userdata.emp_position2);
            jQuery("#emp_position3").val(userdata.emp_position3);
            var output;
            if (userdata.facebook_status == "Inactive" || userdata.facebook_status == "Unlinked" || userdata.facebook_status == "" || userdata.facebook_status == null) {
                output = "<table style='width:100%;float:left;' class='table social'><tr><td style='width:85px'>";
                output += "<img src='images/facebook.png' style='width:80px;height:80px;'></td><td style='width:371px;font-size:16px;text-align:center;'>Link your Facebook to your account today!</td>";
                output += "<td><button type='button' class='btn btn-primary' style='width:65px;' onclick='Login();'>Link</button></td></tr></table>";
            } else {
                output = "<table style='width:100%;float:left;' class='table social'><tr><td style='width:85px'>";
                output += "<img src='images/facebook.png' style='width:80px;height:80px;'></td>";
                if (userdata.profile_image != "") {
                    output += "<td style='width:85px'><img src='" + API + "images/" + userdata.profile_image + "' style='width:80px;height:80px;'</td>";
                } else {
                    output += "<td style='width:85px'><img src='images/Default - User.png' style='width:80px;height:80px;'</td>";
                }
                output += "<td style='width:269px;font-size:16px;'>Facebook<br><a href='http://www.facebook.com' target='_blank'>www.facebook.com</td>";
                output += "<td style='width: 70px;'><button type='button' class='btn btn-primary' style='width:65px;' onclick='SMunlink(\"Facebook\");'>Unlink</button></td><td><a href='http://www.facebook.com' target='_blank'><button type='button' class='btn btn-primary' style='width:65px;'>Open</button></a></td></tr></table>";
            }
            jQuery("#facebook").html(output);

            if (userdata.google_status == "Inactive" || userdata.google_status == "Unlinked" || userdata.google_status == "" || userdata.google_status == null) {
                output = "<table style='float:left;width:100%;vertical-align:middle;' class='table social'><tr><td style='width:85px'>";
                output += "<img src='images/google-plus.png' style='width:80px;height:80px;'></td><td style='width:371px;font-size:16px;text-align:center;'>Link your Google+ to your account today!</td>";
                output += "<td><button type='button' class='btn btn-primary' style='width:65px;' onclick='open_gp();'>Link</button></td></tr></table>";
            } else {
                output = "<table style='float:left;width:100%;vertical-align:middle;' class='table social'><tr><td style='width:85px;'>";
                output += "<img src='images/google-plus.png' style='width:80px;height:80px;'></td>"
                if (userdata.google_image != "") {
                    output += "<td style='width:85px'><img src='" + API + "images/" + userdata.google_image + "' style='width:80px;height:80px;'</td>";
                } else {
                    output += "<td style='width:85px'><img src='images/Default - User.png' style='width:80px;height:80px;'</td>";
                }
                output += "<td style='width:269px;font-size:16px;'>Google+<br><a href='http://plus.google.com' target='_blank'>plus.google.com</a></td>";
                output += "<td style='width: 70px;'><button type='button'  class='btn btn-primary' style='width:65px;' onclick='SMunlink(\"Google\");'>Unlink</button></td><td><a href='http://plus.google.com' target='_blank'><button type='button' class='btn btn-primary' style='width:65px;'>Open</button></a></td></tr></table>";
            }
            jQuery("#google").html(output);

			//console.log(userdata);
            if (userdata.linkedin_status == "Inactive" || userdata.linkedin_status == "Unlinked" || userdata.linkedin_status == "" || userdata.linkedin_status == null) {
                output = "<table style='float:left;width:100%;vertical-align:middle;' class='table social'><tr><td style='width:85px'>";
                output += "<img src='images/linkedin.png' style='width:80px;height:80px;'></td><td style='width:371px;font-size:16px;text-align:center;'>Link your Linkedin to your account today!</td>";
                output += "<td><button type='button' class='btn btn-primary' style='width:65px;' onclick='open_linked();'>Link</button></td></tr></table>";
            } else {
                output = "<table style='float:left;width:100%;vertical-align:middle;' class='table social'><tr><td style='width:85px;'>";
                output += "<img src='images/linkedin.png' style='width:80px;height:80px;'></td>";
                if (userdata.linkedin_image != "") {
                    output += "<td style='width:85px'><img src='" + API + "images/" + userdata.linkedin_image + "' style='width:80px;height:80px;'</td>";
                } else {
                    output += "<td style='width:85px'><img src='images/Default - User.png' style='width:80px;height:80px;'</td>";
                }
                output += "<td style='width:269px;font-size:16px;'>Linkedin<br><a href='http://www.linkedin.com' target='_blank'>www.linkedin.com</td>";
                output += "<td style='width: 70px;'><button type='button' class='btn btn-primary' style='width:65px;' onclick='SMunlink(\"LinkedIn\");'>Unlink</button></td><td><a href='http://www.linkedin.com' target='_blank'><button type='button' class='btn btn-primary' style='width:65px;'>Open</button></a></td></tr></table>";
            }
            jQuery("#linkedin").html(output);

            if (userdata.twitter_status == "Inactive" || userdata.twitter_status == "Unlinked" || userdata.twitter_status == "" || userdata.twitter_status == null) {
                output = "<table style='float:left;width:100%;vertical-align:middle;' class='table social'><tr><td style='width:85px'>";
                output += "<img src='images/twitter.png' style='width:80px;height:80px;'></td><td style='width:371px;font-size:16px;text-align:center;'>Link your Twitter to your account today!</td>";
                output += "<td><button type='button' class='btn btn-primary' style='width:65px;' onclick='open_twitter();'>Link</button></td></tr></table>";
            } else {
                output = "<table style='float:left;width:100%;vertical-align:middle;' class='table social'><tr><td style='width:85px;'>";
                output += "<img src='images/twitter.png' style='width:80px;height:80px;'></td>"
                if (userdata.twitter_image != "") {
                    output += "<td style='width:85px'><img src='" + API + "images/" + userdata.twitter_image + "' style='width:80px;height:80px;'</td>";
                } else {
                    output += "<td style='width:85px'><img src='images/Default - User.png' style='width:80px;height:80px;'</td>";
                }
                output += "<td style='width:269px;font-size:16px;'>Twitter<br><a href='http://www.twitter.com' target='_blank'>www.twitter.com</a></td>";
                output += "<td style='width: 70px;'><button type='button' class='btn btn-primary' style='width:65px;' onclick='SMunlink(\"Twitter\");'>Unlink</button></td><td><a href='http://www.twitter.com' target='_blank'><button type='button' class='btn btn-primary' style='width:65px;'>Open</button></a></td></tr></table>";
            }
            jQuery("#twitter").html(output);
            //jQuery("#facebook_id").val(userdata.facebook_id);
            //jQuery("#google_id").val(userdata.google_id);
            //jQuery("#linkedin_id").val(userdata.linkedin_id);
            //jQuery("#twitter").val(userdata.twitter);
            //jQuery("#smprofile_image").val(userdata.profile_image);

            jQuery("#document_country").val(userdata.document_country);
            jQuery("#country_birth").val(userdata.country_birth);
            jQuery("#document_type").val(userdata.document_type);
            jQuery("#document_issue_date").val(userdata.document_issue_date);
            jQuery("#smoker").val(userdata.smoker);
            jQuery("#handicap").val(userdata.handicap);
            jQuery("#client_id").val(userdata.id);
            jQuery("#position").val(userdata.position);

            jQuery("#StorePoint").val(userdata.StorePoint);
            jQuery("#ChefedIN").val(userdata.ChefedIN);
            jQuery("#StylistFN").val(userdata.StylistFN);
            jQuery("#DeliveryPoint").val(userdata.DeliveryPoint);
            

            jQuery("#Delivery_activated_datetime").val(userdata.Delivery_activated_datetime);

            if (userdata.Delivery_trasporation != '') {
                var arr_trasporation = String(userdata.Delivery_trasporation).split(",");
                var selected_trasporation = jQuery("#Delivery_trasporation option:selected").map(function () { return this.value }).get();
                for (i = 0; i < arr_trasporation.length; i++) {
                    selected_trasporation.push(arr_trasporation[i]);
                }
                jQuery("#Delivery_trasporation").val(selected_trasporation);
            }


            jQuery("#Delivery_payment_method").val(userdata.Delivery_payment_method);



            if (userdata.DeliveryPoint == 'Yes') {
                jQuery("#DeliveryPoint_options").show();
            }
            else {
                jQuery("#DeliveryPoint_options").hide();
            }
            
            if (userdata.StorePoint_vendor_Id!="" && userdata.StorePoint_vendor_Id != 'undefined' && userdata.StorePoint_vendor_Id != 0 && userdata.StorePoint_vendor_Id!=null){
                jQuery("#StorePoint_vendor_id").attr("readonly",true).show();
				jQuery("#StorePoint_vendor_id_search").hide();
            }else{
				jQuery("#StorePoint_vendor_id_search").show();
				jQuery("#StorePoint_vendor_id").attr("readonly",false).hide();
			}
			
            
            if(userdata.StorePoint_vendor_Id == '0'){
               jQuery("#StorePoint_vendor_id").val(""); 
            }
            jQuery("#introduction").val(userdata.ChefedIN_Introduction);
            jQuery("#services").val(userdata.ChefedIN_Services);
            
            jQuery("#experience").val(userdata.ChefedIN_experience);
            jQuery("#references").val(userdata.ChefedIN_reference);
            jQuery("#website").val(userdata.ChefedIN_website);
            if (userdata.ChefedIN_market != '') {
                var arr_market = String(userdata.ChefedIN_market).split(",");
                var selected_trasporation = jQuery("#chefedin_market option:selected").map(function () { return this.value }).get();
                for (i = 0; i < arr_market.length; i++) {
                    selected_trasporation.push(arr_market[i]);
                }
                jQuery("#chefedin_market").val(selected_trasporation);
            }
            jQuery("#rate").val(userdata.ChefedIN_rate);
            jQuery("#created_by").val(userdata.created_by);
            jQuery("#created_on").val(userdata.created_on);
            jQuery("#created_datetime").val(userdata.created_datetime);
            jQuery("#last_by").val(userdata.last_by);
            jQuery("#last_on").val(userdata.last_on);
            jQuery("#last_datetime").val(userdata.last_datetime);





            jQuery("#languages, #vendortype, #vendorPaymentType, #vendorDeliveryType").chosen({ allow_single_deselect: true });
            jQuery("#employment_type").chosen({ allow_single_deselect: true });
            jQuery("#emp_position1").chosen({ allow_single_deselect: true });
            jQuery("#emp_position2").chosen({ allow_single_deselect: true });
            jQuery("#emp_position3").chosen({ allow_single_deselect: true });
            jQuery("#Delivery_trasporation").chosen({ allow_single_deselect: true });
            jQuery("#chefedin_market").chosen({ allow_single_deselect: true });

            jQuery('#dob').datepicker({ dateFormat: 'yy-mm-dd',changeMonth:true,changeYear:true,maxDate:0});
			
			jQuery('#Previous_Job_1_Startdate').datepicker({ dateFormat: 'yy-mm-dd',changeMonth:true,changeYear:true,maxDate:0});
			jQuery('#Previous_Job_1_Enddate').datepicker({ dateFormat: 'yy-mm-dd',changeMonth:true,changeYear:true,maxDate:0});
			jQuery('#Previous_Job_2_Startdate').datepicker({ dateFormat: 'yy-mm-dd',changeMonth:true,changeYear:true,maxDate:0});
			jQuery('#Previous_Job_2_Enddate').datepicker({ dateFormat: 'yy-mm-dd',changeMonth:true,changeYear:true,maxDate:0});
			jQuery('#Previous_Job_3_Startdate').datepicker({ dateFormat: 'yy-mm-dd',changeMonth:true,changeYear:true,maxDate:0});
			jQuery('#Previous_Job_3_Enddate').datepicker({ dateFormat: 'yy-mm-dd',changeMonth:true,changeYear:true,maxDate:0});
			
			
            jQuery('#document_issue_date').dateDropDowns({ dateFormat: 'YY-mm-dd' });


            //jQuery('#activities').tagsInput({ defaultText: "Add Activities", width: '270px' });
            //jQuery('#services').tagsInput({ defaultText: "add Services", width: '270px'});

            jQuery("#country,#currency,#vendorcountry,#vendorstate, #document_country, #country_birth, #salutation, #sex, #viewable").chosen();
			
			jQuery("#state,#currency,#viewable,#employment_type,#vendorcountry,#vendorstate, #document_country, #country_birth, #salutation, #sex, #viewable,#languages,#emp_position1,#emp_position2,#emp_position3,#chefedin_market,#vendortype,#vendorPaymentType,#vendorDeliveryType,#vendorterm,#Delivery_trasporation").live('change',function(){
				setTimeout(function(){									
				jQuery.unblockUI();				
				},50);
			});
            jQuery("#country_chzn, #document_country_chzn, #country_birth_chzn, #salutation_chzn, #sex_chzn, #language_chzn").css("width", "282");			
            if (userdata.StorePoint_vendor_Id != "" && userdata.StorePoint_vendor_Id != 0 && typeof userdata.StorePoint_vendor_Id !== 'undefined' && userdata.StorePoint_vendor_Id!=null) {				
                jQuery.ajax({
                    url: API_URL + 'return_vendor.php',
                    data: {
                        vendor_id: userdata.StorePoint_vendor_Id,client_id:client_id
                    },
                    type: 'POST',
                    dataType: 'JSON',
                    success: function (data) {

                            jQuery("#vendorname").val(data.name);
							jQuery("#locationLink").val(data.location_link);
							jQuery("#location_link").val(data.location_id);
							jQuery("#StorePoint_vendor_id").val(data.name+" (ID: "+userdata.StorePoint_vendor_Id+")");
							jQuery("#vendor_id").val(userdata.StorePoint_vendor_Id);
								if(data.StorePoint_image!="" && data.StorePoint_image!=null){
								jQuery("#old_store_image").attr('src',API+'images/'+data.StorePoint_image);
								jQuery("#old_store_img").val(data.StorePoint_image);
								jQuery("#old_store_image").show();
								}else if(userdata.image !='' && userdata.image!=null){
								jQuery("#old_store_image").attr('src', API+'images/'+userdata.image);
								jQuery("#old_store_image").show();
								jQuery("#old_store_img").val('');
								}else{
								jQuery("#old_store_image").hide();
								jQuery("#old_store_img").val('');
								}
							jQuery('#imagebox1').html('');
                            jQuery("#vendorstatus").val(data.status);
                            jQuery("#vendorcontact").val(data.contact);
                            jQuery("#vendortitle").val(data.title);
                            jQuery("#vendoremail").val(data.email);
                            jQuery("#vendoraddress").val(data.address);
                            jQuery("#vendoraddress2").val(data.address2);
							if(data.country!="" && data.country != null){
                            jQuery("#vendorcountry").val(data.country);
							}else{
							jQuery("#vendorcountry").val(3);
							}
							
                            jQuery("#vendorcity").val(data.city);
                            setTimeout(function(){
								jQuery("#vendorstate").val(data.state);
							},2000);
							getvendorStates(data.country,data.state);
                            jQuery("#vendorzip").val(data.zip);
                            jQuery("#vendorphone").val(data.phone);
                            jQuery("#vendorfax").val(data.fax);
                            jQuery("#vendorwebsite").val(data.website);
                            jQuery("#vendordescription").val(data.description);
                            
                            if (data.currency!="" && data.currency!=null){
                                jQuery("#vendorcurrency").val(data.currency);
                            }else jQuery('#vendorcurrency option:contains(USD)').attr("selected", true);
                            jQuery("#vendorcreatedby").val(data.created_by);
                            jQuery("#vendorcreatedon").val('TEST');
                            jQuery("#vendordatetime").val(data.created_date);
                            jQuery("#vendorlastby").val(data.last_by);
                            jQuery("#vendorlaston").val(data.last_on);
                            jQuery("#vendorlastdatetime").val(data.last_datetime);
                        

                        if (data.type != '') {
                            var arr_types = String(data.type).split(",");
                            var selected_types = jQuery("#vendortype option:selected").map(function () { return this.value }).get();
                            for (i = 0; i < arr_types.length; i++) {
                                selected_types.push(arr_types[i]);
                            }
                            if (selected_types!="" && selected_types!=null){
								
                                jQuery("#vendortype").val(selected_types);
                            }    
                            jQuery("#vendortype").chosen();
                            jQuery("#vendortype").trigger("liszt:updated");
                            // console.log(jQuery("#vendortype").val());
                        }
						if (data.terms != '') {
                            var arr_types = String(data.terms).split(",");
                            var selected_types = jQuery("#vendorterm option:selected").map(function () { return this.value }).get();
                            for (i = 0; i < arr_types.length; i++) {
                                selected_types.push(arr_types[i]);
                            }
                            if (selected_types!="" && selected_types!=null){
								
                                jQuery("#vendorterm").val(selected_types);
                            }    
                            jQuery("#vendorterm").chosen();
                            jQuery("#vendorterm").trigger("liszt:updated");
                        }
						if (data.payment_types != '') {
                            var arr_types = String(data.payment_types).split(",");
                            var selected_types = jQuery("#vendorPaymentType option:selected").map(function () { return this.value }).get();
                            for (i = 0; i < arr_types.length; i++) {
                                selected_types.push(arr_types[i]);
                            }
                            if (selected_types!="" && selected_types!=null){
								
                                jQuery("#vendorPaymentType").val(selected_types);
                            }    
                            jQuery("#vendorPaymentType").chosen();
                            jQuery("#vendorPaymentType").trigger("liszt:updated");
                        }
						if (data.delivery_types != '') {
                            var arr_types = String(data.delivery_types).split(",");
                            var selected_types = jQuery("#vendorDeliveryType option:selected").map(function () { return this.value }).get();
                            for (i = 0; i < arr_types.length; i++) {
                                selected_types.push(arr_types[i]);
                            }
                            if (selected_types!="" && selected_types!=null){
								
                                jQuery("#vendorDeliveryType").val(selected_types);
                            }    
                            jQuery("#vendorDeliveryType").chosen();
                            jQuery("#vendorDeliveryType").trigger("liszt:updated");
                        }


                        jQuery.unblockUI();
                    }
                });
            }else {
                            jQuery("#vendorname").val(userdata.first_name + " " + userdata.last_name);
                            jQuery("#vendorcontact").val(userdata.first_name + " " + userdata.last_name);
                            jQuery("#vendoremail").val(userdata.email);
                            jQuery("#vendoraddress").val(userdata.address);
                            jQuery("#vendoraddress2").val(userdata.address2);
                            jQuery("#vendorcountry").val(userdata.country);
                            jQuery("#vendorcity").val(userdata.city);
                            jQuery("#vendorstate").val(userdata.state);
                            jQuery("#vendorzip").val(userdata.zip);
                            jQuery("#vendorphone").val(userdata.telephone);
                           // jQuery('#vendorcurrency option[text="USD"').attr("selected", true);
                            jQuery('#vendorcurrency option:contains(USD)').attr("selected", true);
                        }
			jQuery('#country').trigger("liszt:updated");
        }
    }

    if (jQuery('#change_resume_option').length > 0) {
        jQuery("#change_resume_option").colorbox({
            href: 'ajax/resume-form.php',
            title: '<h4 class="widgettitle title-primary">Upload Resume</h4>',
            width: '500px',
            height: '220px',
            initialWidth: '200px',
            initialHeight: '100px',
            onComplete: function () {
                jQuery('#resume_client_id').val(client_id);
                jQuery("#old_resume").val(userdata.resume);
                jQuery('#file_upload_close').live('click', function () {
                    jQuery.fn.colorbox.close();
                });

                jQuery('#file_upload_submit').live('click', function () {
                    jQuery.blockUI({ message: null });
                    jQuery("#resumeform").ajaxForm({
                        target: '#preview',
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.code == 0) {
                                getClientInfo();
                                jQuery.alerts.dialogClass = 'alert-inverse';
                                jAlert('Your resume has been updated successfully.', 'Update Resume', function () {
                                    jQuery.alerts.dialogClass = null; // reset to default
                                    jQuery.fn.colorbox.close();
                                });
                            }
                            jQuery.unblockUI();
                        }
                    }).submit();
                });
            }
        });
    }
    jQuery('#DeliveryPoint').change(function () {
        if (jQuery(this).val() == 'Yes') {
            jQuery("#DeliveryPoint_options").show();
        }
        else {
            jQuery("#DeliveryPoint_options").hide();
        }
    });

var xar = '';
jQuery('#StorePoint_vendor_id_search').typeahead({
		minLength: 2,
		source: function (query, process) {
			if(xar!=''){
				xar.abort();
				xar = '';
			}
			return xar = jQuery.ajax({
			//url: 'ajax_autocomplete.php', //juni -> switch file as someone keeps modifying it
			 url: 'ajax/ajax_get_vendor_not_assigned.php',
				type: 'post',
				data: { query: query,  autoCompleteClassName:'autocomplete',				
				selectedClassName:'sel',
				attrCallBack:'rel',
				identifier:'estadoAll'},
				dataType: 'json',
				success: function (result) {
	
					var resultList = result.map(function (item) {
						var label=(item.label).replace("ID#","ID:");
						var aItem = { id: item.id, name: label };
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
				return item.name.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
					return '<strong>' + match + '</strong>'
				})
			},
		
			updater: function (obj) {
				var item = JSON.parse(obj);
				jQuery('#StorePoint_vendor_id').attr('value', item.id);				
				fill_vendor(item.id,client_id,userdata);
				return item.name;
			}
		});

	jQuery('#vendorcountry').trigger('change');
	jQuery('#country').trigger('change');

});


function fill_vendor(StorePoint_vendor_Id,client_id,userdata){
				jQuery.blockUI({ message: null });
                jQuery.ajax({
                    url: API_URL + 'return_vendor.php',
                    data: {
                        vendor_id:StorePoint_vendor_Id,client_id:client_id
                    },
                    type: 'POST',
                    dataType: 'JSON',
                    success: function (data) {

                            jQuery("#vendorname").val(data.name);
							jQuery("#locationLink").val(data.location_link);
							jQuery("#location_link").val(data.location_id);
								if(data.StorePoint_image!="" && data.StorePoint_image!=null){
								jQuery("#old_store_image").attr('src',API+'images/'+data.StorePoint_image);
								jQuery("#old_store_img").val(data.StorePoint_image);
								jQuery("#old_store_image").show();
								}else if(userdata.image !='' && userdata.image!=null){
								jQuery("#old_store_image").attr('src', API+'images/'+userdata.image);
								jQuery("#old_store_image").show();
								jQuery("#old_store_img").val('');
								}else{
								jQuery("#old_store_image").hide();
								jQuery("#old_store_img").val('');
								}
							jQuery('#imagebox1').html('');
                            jQuery("#vendorstatus").val(data.status);
                            jQuery("#vendorcontact").val(data.contact);
                            jQuery("#vendortitle").val(data.title);
                            jQuery("#vendoremail").val(data.email);
                            jQuery("#vendoraddress").val(data.address);
                            jQuery("#vendoraddress2").val(data.address2);
							if(data.country!="" && data.country != null){
                            jQuery("#vendorcountry").val(data.country);
							}else{
							jQuery("#vendorcountry").val(3);
							}
							
                            jQuery("#vendorcity").val(data.city);
                            setTimeout(function(){
								jQuery("#vendorstate").val(data.state);
							},2000);
							getvendorStates(data.country,data.state);
                            jQuery("#vendorzip").val(data.zip);
                            jQuery("#vendorphone").val(data.phone);
                            jQuery("#vendorfax").val(data.fax);
                            jQuery("#vendorwebsite").val(data.website);
                            jQuery("#vendordescription").val(data.description);
                            
                            if (data.currency!="" && data.currency!=null){
                                jQuery("#vendorcurrency").val(data.currency);
                            }else jQuery('#vendorcurrency option:contains(USD)').attr("selected", true);
                            jQuery("#vendorcreatedby").val(data.created_by);
                            jQuery("#vendorcreatedon").val(data.created_on);
                            jQuery("#vendordatetime").val(data.created_date);
                            jQuery("#vendorlastby").val(data.last_by);
                            jQuery("#vendorlaston").val(data.last_on);
                            jQuery("#vendorlastdatetime").val(data.last_datetime);
                        

                        if (data.type != '') {
                            var arr_types = String(data.type).split(",");
                            var selected_types = jQuery("#vendortype option:selected").map(function () { return this.value }).get();
                            for (i = 0; i < arr_types.length; i++) {
                                selected_types.push(arr_types[i]);
                            }
                            if (selected_types!="" && selected_types!=null){
								
                                jQuery("#vendortype").val(selected_types);
                            }    
                            jQuery("#vendortype").chosen();
                            jQuery("#vendortype").trigger("liszt:updated");
                            // console.log(jQuery("#vendortype").val());
                        }
						if (data.terms != '') {
                            var arr_types = String(data.terms).split(",");
                            var selected_types = jQuery("#vendorterm option:selected").map(function () { return this.value }).get();
                            for (i = 0; i < arr_types.length; i++) {
                                selected_types.push(arr_types[i]);
                            }
                            if (selected_types!="" && selected_types!=null){
								
                                jQuery("#vendorterm").val(selected_types);
                            }    
                            jQuery("#vendorterm").chosen();
                            jQuery("#vendorterm").trigger("liszt:updated");
                        }
						if (data.payment_types != '') {
                            var arr_types = String(data.payment_types).split(",");
                            var selected_types = jQuery("#vendorPaymentType option:selected").map(function () { return this.value }).get();
                            for (i = 0; i < arr_types.length; i++) {
                                selected_types.push(arr_types[i]);
                            }
                            if (selected_types!="" && selected_types!=null){
								
                                jQuery("#vendorPaymentType").val(selected_types);
                            }    
                            jQuery("#vendorPaymentType").chosen();
                            jQuery("#vendorPaymentType").trigger("liszt:updated");
                        }
						if (data.delivery_types != '') {
                            var arr_types = String(data.delivery_types).split(",");
                            var selected_types = jQuery("#vendorDeliveryType option:selected").map(function () { return this.value }).get();
                            for (i = 0; i < arr_types.length; i++) {
                                selected_types.push(arr_types[i]);
                            }
                            if (selected_types!="" && selected_types!=null){
								
                                jQuery("#vendorDeliveryType").val(selected_types);
                            }    
                            jQuery("#vendorDeliveryType").chosen();
                            jQuery("#vendorDeliveryType").trigger("liszt:updated");
                        }


                        jQuery.unblockUI();
                    }
                });
}


function loginError(e){
    var msg = '';
    switch(e){
        case 1:
            msg = 'Email is required!';
            break;
        case 2:
            msg = 'Password field is required!';
            break;
        case 3:
            msg = 'Incorrect email or password!';
            break;
        case 4:
            msg = 'User is not active!';
            break;
        case 5:
            msg = 'Incorrect password!';
            break;
    }
    if(msg != ''){
		jQuery('.alert-error').html(msg);
		jQuery('.login-alert').show();
    }
}
jQuery('#wiz1step4 p span select').live('change',function(){
	global_realod = true;													 
});
function getvendorStates(country_id, state) {
            if (typeof (state) === 'undefined') state = 0;
            //jQuery.blockUI({css: { backgroundColor: 'none', border: 'none'}, message: '<img alt="" src="images/loaders/loader6.gif">' }); 
            jQuery.ajax({
                url: API_URL + 'return_states.php',
                data: { country: country_id },
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {
                    jQuery('#vendorstate').html('');
                    jQuery('<option>', { value: '', text: '' }).appendTo('#vendorstate');
                    jQuery.each(data, function (i, obj) {
                        jQuery('<option>', { value: obj.id, text: obj.name }).appendTo('#vendorstate');
                    });
                    jQuery("#vendorstate").val(state);
                    jQuery("#vendorstate").chosen();
                    jQuery("#vendorstate").trigger("liszt:updated");
					
                    //jQuery.unblockUI();
                }
            });
        }