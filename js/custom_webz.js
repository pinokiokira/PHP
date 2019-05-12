/* Numeric Validation */
function allnumeric(inputtxt)  
{  
	if(inputtxt!=""){
	  var numbers = /^[0-9]+$/;  
	  if(inputtxt.match(numbers))  
	  {  
		return true;  
	  }  
	  else  
	  {  
		return false;  
	  }  
	}else{
		return true;
	}
}

function emailInvalid(s)
{
	if(!(s.match(/^[\w]+([_|\.-][\w]{1,})*@[\w]{2,}([_|\.-][\w]{1,})*\.([a-z]{2,4})$/i) ))
        {
		return false;
	}
	else
		return true;
}