var API = 'http://www.softpoint.mobi/';
var API_ROOT_URL=API +  "API2/";
var API_URL=API + "API/hotelpoint/";
var API_URL_RP=API + "API/registerpoint/";
var Item_ImageURL=API + "images/";
var apiToken="7ek286hj5d1a9i0m3lfgb4c";

/* Learn Tube */

var API_URL_LEARNTUBE=API + "API/learntube/";

/* Fill Video Quiz */
function FillVideoQuiz(video_id, que){
	var crossDomainUrl ='learntube_training_video_quiz.php';
	//alert(crossDomainUrl+"?video_id="+video_id+"&limit="+que)
	 jQuery.ajax({ 
			url: crossDomainUrl,
			data: "video_id="+video_id+"&limit="+que,
		 	dataType: 'jsonp'
	 });
}

function returnVideoQuiz(strLessonVideo){

	if(strLessonVideo!=null)
	{
		jQuery("#btnNext").show();
		jQuery("#btnSubmit").hide(100);
		jQuery("#btnQuizSubmit").hide(100);
		jQuery("#btnQuizNext").hide();

		jQuery("#hidTotQue").val(strLessonVideo[0].totQuestion);
		jQuery("#spantotQuestion").html(strLessonVideo[0].totQuestion);
		jQuery("#hidQueID").val(strLessonVideo[0].question_id);
		if(strLessonVideo[0].totQuestion==jQuery("#hidQue").val()){
			jQuery("#btnNext").hide(100);
			jQuery("#btnSubmit").hide();
			jQuery("#btnQuizNext").hide();
			jQuery("#btnQuizSubmit").show();
		}
		jQuery("#tdQuestion").html(strLessonVideo[0].question);
		if(strLessonVideo[0].multiple_choice=="MC"){
			jQuery("#trMulti").show();
			jQuery("#trText").hide(100);
			jQuery("#QueType").val("multi");
			jQuery("#txtAnswer").removeClass("Required");
			/*jQuery('input[name="rdAnswer"]')[0].val('test');
			jQuery('input[name="rdAnswer"][0][value="y"]').attr("checked", true);*/
			var myArray = ['4','1', '3', '2','4','1', '3', '2'];    
			var rand = myArray[Math.floor(Math.random() * myArray.length)];
			
			/*var b = strLessonVideo[0].correct_answer;
			var a="";
			if(rand=="1" && strLessonVideo[0].wrong_answer1!=""){
				a = strLessonVideo[0].wrong_answer1;
				strLessonVideo[0].wrong_answer1 = b;
			}else if(rand=="2" && strLessonVideo[0].wrong_answer2!=""){
				a = strLessonVideo[0].wrong_answer2;
				strLessonVideo[0].wrong_answer2 = b;
			}else if(rand=="3" && strLessonVideo[0].wrong_answer3!=""){
				a = strLessonVideo[0].wrong_answer3;
				strLessonVideo[0].wrong_answer3 = b;
			}else if(rand=="4" && strLessonVideo[0].wrong_answer4!=""){
				a = strLessonVideo[0].wrong_answer4;
				strLessonVideo[0].wrong_answer2 = b;
			}else if(rand=="5" && strLessonVideo[0].wrong_answer5!=""){
				a = strLessonVideo[0].wrong_answer5;
				strLessonVideo[0].wrong_answer1 = b;
			}else if(rand=="6" && strLessonVideo[0].wrong_answer6!=""){
				a = strLessonVideo[0].wrong_answer6;
				strLessonVideo[0].wrong_answer3 = b;
			}else if(rand=="7" && strLessonVideo[0].wrong_answer7!=""){
				a = strLessonVideo[0].wrong_answer7;
				strLessonVideo[0].wrong_answer1 = b;
			}else if(rand=="8" && strLessonVideo[0].wrong_answer8!=""){
				a = strLessonVideo[0].wrong_answer8;
				strLessonVideo[0].wrong_answer2 = b;
			}else if(rand=="9" && strLessonVideo[0].wrong_answer9!=""){
				a = strLessonVideo[0].wrong_answer9;
				strLessonVideo[0].wrong_answer3 = b;
			}else if(rand=="10" && strLessonVideo[0].wrong_answer10!=""){
				a = strLessonVideo[0].wrong_answer10;
				strLessonVideo[0].wrong_answer1 = b;
			}
			
			if(a!=""){
				strLessonVideo[0].correct_answer=a;
			}*/
			
			var mySeq = ['A','B', 'C', 'D']; 
			var s=0;
			if(strLessonVideo[0].wrong_answer1!=""){
				jQuery("#spanAnswer1").show();
				jQuery("#answer1").show();
				//jQuery("#rdAnswer1").attr("value",strLessonVideo[0].wrong_answer1);
				
				if(jQuery("#hidAnswer").val()==mySeq[s]) { //strLessonVideo[0].wrong_answer1){
					jQuery("#rdAnswer1").attr('checked',true);
				}
				jQuery("#answer1").html(mySeq[s] + ". " + strLessonVideo[0].wrong_answer1);
				jQuery("#rdAnswer1").attr('value', mySeq[s]); 
				s++;
			}
			if(strLessonVideo[0].wrong_answer2!=""){
				jQuery("#spanAnswer2").show();
				jQuery("#answer2").show();
				//jQuery("#rdAnswer2").attr("value",strLessonVideo[0].wrong_answer2);
				if(jQuery("#hidAnswer").val()==mySeq[s]) { //strLessonVideo[0].wrong_answer2){
					jQuery("#rdAnswer2").attr('checked',true);
					
					jQuery("#rdAnswer1").attr('checked',false);
					jQuery("#rdAnswer3").attr('checked',false);
					jQuery("#rdAnswer4").attr('checked',false);
					jQuery("#rdAnswer5").attr('checked',false);
				}
				jQuery("#answer2").html(mySeq[s] + ". " +  strLessonVideo[0].wrong_answer2 +"<br>");
				jQuery("#rdAnswer2").attr('value', mySeq[s]); 
				s++;
			}
			if(strLessonVideo[0].wrong_answer3!=""){
				jQuery("#spanAnswer3").show();
				jQuery("#answer3").show();
				//jQuery("#rdAnswer3").attr("value",strLessonVideo[0].wrong_answer3);
				if(jQuery("#hidAnswer").val()==mySeq[s]) { //strLessonVideo[0].wrong_answer3){
					jQuery("#rdAnswer3").attr('checked',true);
					
					jQuery("#rdAnswer1").attr('checked',false);
					jQuery("#rdAnswer2").attr('checked',false);
					jQuery("#rdAnswer4").attr('checked',false);
					jQuery("#rdAnswer5").attr('checked',false);
				}
				jQuery("#answer3").html(mySeq[s] + ". " + strLessonVideo[0].wrong_answer3 +"<br>");
				jQuery("#rdAnswer3").attr('value', mySeq[s]); 
				s++;
			}
			/*if(strLessonVideo[0].wrong_answer4!=""){
				jQuery("#spanAnswer4").show();	
				jQuery("#answer4").show();
				//jQuery("#rdAnswer4").attr("value",strLessonVideo[0].wrong_answer4);
				if(jQuery("#hidAnswer").val()==strLessonVideo[0].wrong_answer4){
					jQuery("#rdAnswer4").attr('checked',true);
					
					jQuery("#rdAnswer1").attr('checked',false);
					jQuery("#rdAnswer2").attr('checked',false);
					jQuery("#rdAnswer3").attr('checked',false);
					jQuery("#rdAnswer5").attr('checked',false);
				}
				jQuery("#answer4").html(strLessonVideo[0].wrong_answer4 +"<br>");
			}*/
			/*
			if(strLessonVideo[0].correct_answer!=""){
				jQuery("#spanAnswer5").show();	
				jQuery("#answer5").show();
				//jQuery("#rdAnswer5").attr("value",strLessonVideo[0].correct_answer);
				if(jQuery("#hidAnswer").val()==mySeq[s]) { //strLessonVideo[0].correct_answer){
					jQuery("#rdAnswer5").attr('checked',true);
					
					jQuery("#rdAnswer1").attr('checked',false);
					jQuery("#rdAnswer2").attr('checked',false);
					jQuery("#rdAnswer3").attr('checked',false);
					jQuery("#rdAnswer4").attr('checked',false);
				}
				
				jQuery("#answer5").html(mySeq[s] + ". " +  strLessonVideo[0].correct_answer +"<br>");
				jQuery("#rdAnswer5").attr('value', mySeq[s]); 
			}*/
			if(strLessonVideo[0].wrong_answer4!=""){
				jQuery("#spanAnswer5").show();	
				jQuery("#answer5").show();
				//jQuery("#rdAnswer5").attr("value",strLessonVideo[0].correct_answer);
				if(jQuery("#hidAnswer").val()==mySeq[s]) { //strLessonVideo[0].correct_answer){
					jQuery("#rdAnswer5").attr('checked',true);
					
					jQuery("#rdAnswer1").attr('checked',false);
					jQuery("#rdAnswer2").attr('checked',false);
					jQuery("#rdAnswer3").attr('checked',false);
					jQuery("#rdAnswer4").attr('checked',false);
				}
				
				jQuery("#answer5").html(mySeq[s] + ". " +  strLessonVideo[0].wrong_answer4 +"<br>");
				jQuery("#rdAnswer5").attr('value', mySeq[s]); 
			}
			/*var myArray=[strLessonVideo[0].wrong_answer1, strLessonVideo[0].wrong_answer2, strLessonVideo[0].wrong_answer3,strLessonVideo[0].wrong_answer4,strLessonVideo[0].correct_answer];
			
			var i=0;
			 jQuery(':checkbox', jQuery('#rdAnswer')[0]).each(function() {
			   jQuery(this).attr('value', myArray[i]).text();  // value=0, text="Option 1", etc.
			   i++;
			 });*/
			
		}else if(strLessonVideo[0].multiple_choice=="TF"){
			jQuery("#trMulti").show(100);
			jQuery("#trText").hide();
			jQuery("#txtAnswer").removeClass("Required");
			jQuery("#QueType").val("multi");
			
			//if(strLessonVideo[0].wrong_answer1!=""){
				jQuery("#spanAnswer1").show();
				jQuery("#answer1").show();
				jQuery("#rdAnswer1").attr('value', "True");
				//jQuery("#rdAnswer1").attr("value",strLessonVideo[0].wrong_answer1);
				if(jQuery("#hidAnswer").val()!="" && (jQuery("#hidAnswer").val()=="True")){
					jQuery("#rdAnswer1").attr('checked',true);
					jQuery("#rdAnswer2").attr('checked',false);
				}
				jQuery("#answer1").html("True");
			//}
			//if(strLessonVideo[0].correct_answer!=""){
				jQuery("#spanAnswer2").show();
				jQuery("#answer2").show();
				jQuery("#rdAnswer2").attr('value', "False");
				//jQuery("#rdAnswer2").attr("value",strLessonVideo[0].wrong_answer2);
				if(jQuery("#hidAnswer").val()!="" && (jQuery("#hidAnswer").val()=="False")){
					jQuery("#rdAnswer2").attr('checked',true);
					jQuery("#rdAnswer1").attr('checked',false);
				}
				jQuery("#answer2").html("False <br>");
			//}
		}
		else if(strLessonVideo[0].multiple_choice=="INPUT"){
			jQuery("#trMulti").hide(100);
			jQuery("#trText").show();
			jQuery("#txtAnswer").addClass("Required");
			jQuery("#txtAnswer").val(jQuery("#hidAnswer").val());
			jQuery("#QueType").val("txt");
		}
	}
}
/* End : Fill Video quiz */

/* Submit Video Quiz */
function SubmitVideoQuiz(){
	var crossDomainUrl ='learntube_video_submit_quiz.php';
	var hidAnswer=jQuery("#hidAnswer").val();
	var hidVideoID=jQuery("#hidVideoID").val();
	var hidLessonID=jQuery("#hidLessonID").val();
	var hidEMPID=jQuery("#hidEMPID").val();
    //alert(crossDomainUrl+"?hidAnswer="+hidAnswer+"&video_id="+hidVideoID+"&lesson_id="+hidLessonID+"&emp_id="+hidEMPID);
	jQuery.ajax({	
			url: crossDomainUrl+"?hidAnswer="+hidAnswer+"&video_id="+hidVideoID+"&lesson_id="+hidLessonID+"&emp_id="+hidEMPID,
			data: "",
			type: "GET",
			dataType: 'json',
			error: function (req, stat, err) { alert(stat); },
			success: function (strMessage) {
				//alert(strMessage.result);
				if(strMessage.result=="Yes"){
					jQuery("#QuizResult").html("<span><b style='color:green;'>You Pass</b></span>");
					jQuery("#tdFail").html("");
					jQuery("#hidResult").val("Pass");
				}else{
					jQuery("#QuizResult").html("<span><b style='color:red;'>You Fail</b></span>");
					jQuery("#tdFail").html("Please watch the video again and retake this questionaire.");
					jQuery("#hidResult").val("Fail");
				}
				
				jQuery("#QuizQuestion").html(strMessage.correctanswer + " out of " + strMessage.totalquestion);
			}
	 });
}

/*function returnVideoSubmitQuiz(strMessage){
	//alert(strMessage[0].result);
	if(strMessage[0].result=="Yes"){
		jQuery("#QuizResult").html("<span style='color:green;'><b>You Pass</b></span>");
		jQuery("#tdFail").html("");
		jQuery("#hidResult").val("Pass");
	}else{
		jQuery("#QuizResult").html("<span color='red'><b>You Fail</b></span>");
		jQuery("#tdFail").html("Please watch the video again and retake this questionaire.");
		jQuery("#hidResult").val("Fail");
	}
	
	jQuery("#QuizQuestion").html(strMessage[0].correctanswer + " of " + strMessage[0].totalquestion);
}*/
/* End : Submit Video Quiz */


function UpdateLessonStartTime(){
	var intRandNum=Math.floor(Math.random()*5411);
	data={"intRandNum":intRandNum,"hidEmpID":jQuery("#hidEmpID").val(),"hidLessonID":jQuery("#hidLessonID").val()};
	//alert('updatevideostarttime.php?hidEmpID='+jQuery("#hidEmpID").val()+'hidLessonID='+jQuery("#hidLessonID").val());
	jQuery.ajax({	
			url: "learntube_updatevideostarttime.php",
			data: data, 
			type: "POST",
		 	dataType: 'json',
			//error: function (req, stat, err) { alert(stat); },
			success: function (a) {
				
		  }
	 });
}

function UpdateVideoViews(strFlag){
	var intRandNum=Math.floor(Math.random()*5411);
	data={"intRandNum":intRandNum,"intVideoID":jQuery("#hidVideoID").val(),"strFlag":strFlag};
	//alert('updatevideoview.php?intVideoID='+jQuery("#hidVideoID").val()+'&strFlag='+strFlag);
	jQuery.ajax({	
			url: "learntube_updatevideoview.php",
			data: data, 
			type: "POST",
		 	dataType: 'json',
			//error: function (req, stat, err) { alert(stat); },
			success: function (a) {
				
		  }
	 });
}

/* End : Learn Tube */