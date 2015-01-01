function registerValidate(){
  if (document.getElementById("username").value.length < 8){
    alert("Username must be at least 8 characters");
    return false;
  }
  else if(document.getElementById("password1").value !== document.getElementById("password2").value){
    alert("Passwords do not match");
    return false;
  }
  else if(document.getElementById("password1").value.length < 8){
    alert("Password must be at least 8 characters");
    return false;
  }
  else{
    return true;
  }
}

function viewComments(){
	var xmlhttp;
	if (window.XMLHttpRequest){
  		xmlhttp=new XMLHttpRequest();
  	}
	else{
  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	xmlhttp.onreadystatechange=function(){
  		if (xmlhttp.readyState==4 && xmlhttp.status==200){
  			var obj = JSON.parse(xmlhttp.responseText);
  			var comments="";
  			for (var i in obj.messages){
  				comments += obj.messages[i].message + "<br><br>";
  			}
    		document.getElementById("comments").innerHTML=comments;
   		}
	}
	xmlhttp.open("GET","/json/messages.json",true);
	xmlhttp.send();
}