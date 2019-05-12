jQuery(document).ready(function(){
		jQuery("#report_select").change(function(){ 
			urlname = jQuery('#hiddenURL').val();
			
				show_page = jQuery("#report_select").val(); 
				if(show_page== ''){
					document.location = '//'+urlname+'/attendance_reports.php';
				}else{
					document.location = '//'+urlname+'/attendance_reports_'+show_page+'.php';
				}
						
						
		}); 
		
	jQuery("#sdate").datepicker({ dateFormat:"yy-mm-dd",changeMonth: true,changeYear: true});
	jQuery("#edate").datepicker({ dateFormat:"yy-mm-dd",changeMonth: true,changeYear: true});	
	
      var loc = window.location;
      var baseurl = loc.protocol + '//' + loc.host + loc.pathname;
      //var baseurl  = window.location.href;
    /*  jQuery("#orderfield,#ordertype,#startdate,#enddate").change(function(){ 
		  
          order_field = jQuery("#orderfield").val(); 
          order_type = jQuery("#ordertype").val(); 
		  var startDate = document.getElementById("startdate").value;
          var endDate = document.getElementById("enddate").value;
		  if (startDate > endDate) 
		  {
           alert('Start date is after end date. Please revise search.');
           return false;
          }
            document.location = baseurl+'?orderfield='+order_field+'&type='+order_type+'&startdate='+startDate+'&enddate='+endDate;
          
      });*/
	  
	  jQuery("#myfilterbut").click(function(){ 
		  
          order_field = jQuery("#orderfield").val(); 
          order_type = jQuery("#ordertype").val(); 
		  var startDate = document.getElementById("startdate").value;
          var endDate = document.getElementById("enddate").value;
		  if (startDate > endDate) 
		  {
           alert('Start date is after end date. Please revise search.');
           return false;
          }
            document.location = baseurl+'?orderfield='+order_field+'&type='+order_type+'&startdate='+startDate+'&enddate='+endDate;
          
      });
	  
      jQuery("#detail_level").change(function(){
          order_field = jQuery("#orderfield").val(); 
          order_type = jQuery("#ordertype").val(); 
		  detail_level = jQuery("#detail_level").val(); //->juni [REQ_014]  ->  04.09.2014 -> add new filter
          if(order_field != ''){
			if (detail_level!='')
				document.location = baseurl+'?orderfield='+order_field+'&type='+order_type+"&detail_level="+detail_level;
			else
				document.location = baseurl+'?orderfield='+order_field+'&type='+order_type;
          }
      }); 	
	//->juni [REQ_014]  ->  04.09.2014 -> add new filter
	jQuery("#detail_level").change(function(){
		order_field = jQuery("#orderfield").val(); 
		order_type = jQuery("#ordertype").val(); 
		detail_level = jQuery("#detail_level").val(); 
		if(order_field != ''){
			if (detail_level!='')
				document.location = baseurl+'?orderfield='+order_field+'&type='+order_type+"&detail_level="+detail_level;
			else
				document.location = baseurl+'?orderfield='+order_field+'&type='+order_type;
		} else
			document.location = baseurl+'?detail_level='+detail_level;
	}); 
	//<-juni [REQ_014]	  
   
});
function searchValidate() {
	var startDate = new Date(document.getElementById("sdate").value);
	var endDate = new Date(document.getElementById("edate").value);
	if (startDate > endDate) {
		jAlert('Start date is after end date. Please revise search!','Alert Dialog');
		return false;
	} else {
		document.forms.fdate.submit();
		return true;
	}
}   

function printDiv(divName) {
jQuery(".maincontentinner").printElement({
	 overrideElementCSS:[
		'css/style.default.css',
	{ href:'css/print.css',media:'print'}]
 });

}

function mailValidate(mailaddress){   
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/; 
	var address = mailaddress; 
	if(reg.test(address) == false){   
	 return false; 
	}else{
	 return true;
	}	
}