var API_URL = 'ajax/proxy.php?url=';

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
            jQuery.ajax({
                url: 'setCookie.php',
                data: {
                    username: u,
                    rememberme: rememberme
                },
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {

                }
            })
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
                       jQuery.post('require/session.php', {
                            client_id: data.response.client_id,
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
                            window.location.href = 'dashboard.php';
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

            getLocationInfo();


        });
    }

    function getClientInfo() {

        jQuery.blockUI({ css: { backgroundColor: 'none', border: 'none' }, message: '<img alt="" src="images/loaders/loader6.gif">' });
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

    function getLocationInfo() {

        jQuery.blockUI({ message: null });
        jQuery.ajax({
            url: API_URL + 'return_location.php',
            data: {
                client_id: client_id
            },
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {

                if (data.success) {

                    jQuery("#LocationsAssociatedWithEmployee").html(data.LocationsAssociatedWithEmployee);
                    jQuery("#LocationsLinkedWithEmployee").html(data.LocationsLinkedWithEmployee);
                }
                else {
                    alert('Invalid data');
                }
                jQuery.unblockUI();
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
                password: { required: true },
                confirm_password: { required: true, equalTo: "#password" }
            },
            messages: {
                signup_email: {
                    required: "Please enter a email address",
                    email: "Please enter a valid email address",
                    remote: "Already exists, please choose different one"
                },
                password: { required: "Please enter password" },
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

        jQuery('#update_login_info').click(function (event) {
            event.preventDefault();
            if (jQuery("#editlogininfoform").valid()) {
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

        });
    }

    if (jQuery('#client_details_form').length > 0) {

        jQuery('#facebook_id, #google_id, #linkedin_id, #twitter').click(function (event) {
            event.preventDefault();
        });

        // Smart Wizard 	
        jQuery('#client_details_form').submit(function () {
            //onFinish: function(){
            var isValid=1;
            
            if (jQuery("#StorePoint").val()=="Yes"){
                if (jQuery("#StorePoint_vendor_id").val()==""){
                    isValid = 0;
                    jAlert("Please insert Vendor ID!");
                    jQuery("#StorePoint_vendor_id").focus();
                } else if (jQuery("#vendorstatus").val()==""){
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
                } else if (jQuery("#vendoremail").val()==""){
                    isValid = 0;
                    jAlert("Please select Vendor Email!");
                    jQuery("#vendoremail").focus();
                } else if (jQuery("#vendoraddress").val()==""){
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
            form_data += "&last_by=Self&last_on=TeamPanel";
            
            if (isValid==1){
                jQuery.blockUI({ message: null });
                jQuery.ajax({
                    url: API_URL + 'update_client.php',
                    data: form_data,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.success) {

                            //jQuery.alerts.dialogClass = 'alert-inverse';

                            if (data.age < 18) {
                                alert(data.underAge);

                            }

                            var nm = jQuery('#first_name').val() + ' ' + jQuery('#last_name').val();
                            var ci = jQuery('#client_id').val();

                            var StorePoint = jQuery('#StorePoint').val();
                            var ChefedIN = jQuery('#ChefedIN').val();
                            var StylistFN = jQuery('#StylistFN').val();
                            var DeliveryPoint = jQuery('#DeliveryPoint').val();
                            jQuery('#head_name').html(nm);
                            jQuery.post('require/session.php', { client_id: ci, name: nm, StorePoint: StorePoint, ChefedIN: ChefedIN, StylistFN: StylistFN, DeliveryPoint: DeliveryPoint }
                                                            , function () {
                                                                console.log('session updated');
                                                                if (jQuery('#submitStatusField').val() == 'status') {
                                                                    jAlert('Profile Updated Successfully!', 'Edit Profile', function () {
                                                                        window.location.href = window.location.href;
                                                                    });
                                                                    //			window.location.href=window.location.href;
                                                                }
                                                            });

                        }

                        jQuery.unblockUI();
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
                    jQuery('<option>', { value: obj.id, text: obj.description }).appendTo('#country, #document_country, #country_birth');
                });

                if (userdata) {
                    fillForm();
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

        jQuery('#vendorcountry').change(function () {
            var country_id = jQuery(this).find(":selected").val();
            getvendorStates(country_id);
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

        function getStates(country_id, state) {
            if (typeof (state) === 'undefined') state = 0;
            //jQuery.blockUI({css: { backgroundColor: 'none', border: 'none'}, message: '<img alt="" src="images/loaders/loader6.gif">' }); 
            jQuery.ajax({
                url: API_URL + 'return_states.php',
                data: { country: country_id },
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {
                    jQuery('#state').html('');
                    jQuery('<option>', { value: '', text: '' }).appendTo('#state');
                    jQuery.each(data, function (i, obj) {
                        jQuery('<option>', { value: obj.id, text: obj.name }).appendTo('#state');
                    });
                    jQuery("#state").val(state);
                    jQuery("#state").chosen();
                    jQuery("#state").trigger("liszt:updated");
                    //jQuery.unblockUI();
                }
            });
        }

        function fillForm() {
            //userdata = {"id":"1558","status":"A","email":"thisismyemail@test.com","password":"","name":"Marc Guevara","address":"This is my first address","address2":"This is my second address","city":"This is ny city","state":"763","zip":"123456","country":"3","telephone":"5558411349","longitude":"","latitude":"","neighborhood":null,"image":"","sex":"M","dob":"1985-02-15","primarydinning":"This is my primary dinning","primaryschool":"This is my primary school","Signedup":"Y","facebook":"N","facebook_id":"","twitter":null,"ping":"N","access_edu2bsales":"no","email_notifications":"N","push_notificiations":"N","salutation":"Mrs","first_name":"FName","last_name":"LName","name_suffix":null,"specialIns":null,"clientscol1":null,"language":"lang2","smoker":"N","handicap":"N","id_type":null,"id_number":null,"id_country":"3","country_birth":"3","document_type":"type1","document_issue_date":"2005-03-20","document_country":"3","client_expensetab_account_id":null,"created_on":"","date_created":"2013-04-16 13:12:17","last_by":"","last_on":"","last_datetime":"2013-05-23 10:11:57"};
            getStates(userdata.country, userdata.state);
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
            jQuery("#dob").val(userdata.dob);
            jQuery("#viewable").val(userdata.viewable);
            jQuery("#activities").val(userdata.activities);
            jQuery("#competencies").val(userdata.competencies);
            jQuery("#education").val(userdata.education);
            jQuery("#Chefedin_Business_Name").val(userdata.Chefedin_Business_Name);
            jQuery("#StylistFN_Company").val(userdata.StylistFN_Company);
            jQuery("#StylistFN_Description").val(userdata.StylistFN_Description);
            jQuery("#StylistFN_Style").val(userdata.StylistFN_Style);
            jQuery("#StylistFN_Located").val(userdata.StylistFN_Located);
            jQuery("#StylistFN_location_id").val(userdata.StylistFN_location_id);

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
                output += "<td style='width:269px;font-size:16px;'>Facebook<br>www.facebook.com</td>";
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
                output += "<td style='width:269px;font-size:16px;'>Google+<br>plus.google.com</td>";
                output += "<td style='width: 70px;'><button type='button'  class='btn btn-primary' style='width:65px;' onclick='SMunlink(\"Google\");'>Unlink</button></td><td><a href='http://plus.google.com' target='_blank'><button type='button' class='btn btn-primary' style='width:65px;'>Open</button></a></td></tr></table>";
            }
            jQuery("#google").html(output);

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
                output += "<td style='width:269px;font-size:16px;'>Linkedin<br>www.linkedin.com</td>";
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
                output += "<td style='width:269px;font-size:16px;'>Google+<br>plus.google.com</td>";
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
            
            if (userdata.StorePoint_vendor_Id!="" && !typeof userdata.StorePoint_vendor_Id === 'undefined'){
                jQuery("#StorePoint_vendor_id").attr("readonly",true);
            }
            jQuery("#StorePoint_vendor_id").val(userdata.StorePoint_vendor_Id);
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





            jQuery("#languages, #vendortype").chosen({ allow_single_deselect: true });
            jQuery("#employment_type").chosen({ allow_single_deselect: true });
            jQuery("#emp_position1").chosen({ allow_single_deselect: true });
            jQuery("#emp_position2").chosen({ allow_single_deselect: true });
            jQuery("#emp_position3").chosen({ allow_single_deselect: true });
            jQuery("#Delivery_trasporation").chosen({ allow_single_deselect: true });
            jQuery("#chefedin_market").chosen({ allow_single_deselect: true });



            jQuery('#dob').dateDropDowns({ dateFormat: 'YY-mm-dd', yearStart: '1933', yearEnd: '2013' });
            jQuery('#document_issue_date').dateDropDowns({ dateFormat: 'YY-mm-dd' });


            jQuery('#activities').tagsInput({ defaultText: "add activities", width: '270px' });
            jQuery('#services').tagsInput({ defaultText: "add Services", width: '270px'});

            jQuery("#country, #document_country, #country_birth, #salutation, #sex, #viewable").chosen();
            jQuery("#country_chzn, #document_country_chzn, #country_birth_chzn, #salutation_chzn, #sex_chzn, #language_chzn").css("width", "282");
            if (userdata.StorePoint_vendor_Id != "" && userdata.StorePoint_vendor_Id != 0 && !typeof userdata.StorePoint_vendor_Id === 'undefined') {
                jQuery.ajax({
                    url: API_URL + 'return_vendor.php',
                    data: {
                        vendor_id: userdata.StorePoint_vendor_Id
                    },
                    type: 'POST',
                    dataType: 'JSON',
                    success: function (data) {

                            jQuery("#vendorname").val(data.name); 
                            jQuery("#vendorstatus").val(data.status);
                            jQuery("#vendorcontact").val(data.contact);
                            jQuery("#vendortitle").val(data.title);
                            jQuery("#vendoremail").val(data.email);
                            jQuery("#vendoraddress").val(data.address);
                            jQuery("#vendoraddress2").val(data.address2);
                            jQuery("#vendorcountry").val(data.country);
                            jQuery("#vendorcity").val(data.city);
                            jQuery("#vendorstate").val(data.state);
                            jQuery("#vendorzip").val(data.zip);
                            jQuery("#vendorphone").val(data.phone);
                            jQuery("#vendorfax").val(data.fax);
                            jQuery("#vendorwebsite").val(data.website);
                            jQuery("#vendordescription").val(data.description);
                            jQuery("#vendorcurrency").val(data.currency);
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
                            jQuery("#vendortype").val(selected_types);
                            jQuery("#vendortype").chosen();
                            jQuery("#vendortype").trigger("liszt:updated");
                            // console.log(jQuery("#vendortype").val());
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


});

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
