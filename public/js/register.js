// Example starter JavaScript for disabling form submissions if there are invalid fields
function validaUser() {
	var validazione1=0;

	//make an AJAX call to retrieve the file with ingredients
	$.ajax({
		type: "GET",
		url: "/validate-username?user="+$("#validationCustomUsername").val(),
		dataType: "text",
		//function called when the AJAX call is completed
		complete: function (XHR, textStatus) {
			//If the AJAX call has no success there is an error
			if(textStatus!="success") { 
				alert("Error on retrieving data");
			}//if 
			//else the AJAX call is successful
			else { 
				//if there are no documents retrieved, we have an error on loading the SMIL document: it was not well formatted, or the path to it was wrong
				if(XHR == null) {
					//display an error message
					alert("Error on retrieving data");
					return false;
				} // if
				//normalize the XML document retreived, erasing white spaces and tabs
				validazione1=XHR.responseText;
				if(validazione1==0) document.getElementById("validationCustomUsername").setCustomValidity('Username already used');
				else document.getElementById("validationCustomUsername").setCustomValidity('');
			} // else
		} // complete

	});//ajax
	
}

function validaMail() {
	var validazione1=0;

	//make an AJAX call to retrieve the file with ingredients
	$.ajax({
		type: "GET",
		url: "/validate-email?mail="+$("#validationCustom03").val(),
		dataType: "text",
		//function called when the AJAX call is completed
		complete: function (XHR, textStatus) {
			//If the AJAX call has no success there is an error
			if(textStatus!="success") { 
				alert("Errore on retrieving data");
			}//if 
			//else the AJAX call is successful
			else { 
				//if there are no documents retrieved, we have an error on loading the SMIL document: it was not well formatted, or the path to it was wrong
				if(XHR == null) {
					//display an error message
					alert("Errore on retrieving data");
					return false;
				} // if
				//normalize the XML document retreived, erasing white spaces and tabs
				validazione1=XHR.responseText;
				if(validazione1==0) {
					document.getElementById("validationCustom03").setCustomValidity('Email already used');
					document.getElementById("validamailtext").innerHTML='Email already used';
				}
				else if(validazione1==1) document.getElementById("validationCustom03").setCustomValidity('');
				else if(validazione1==2) { 
				document.getElementById("validationCustom03").setCustomValidity('Email disabled, please contact the administrator to enable it');
				document.getElementById("validamailtext").innerHTML='Email disabled, please contact the administrator to enable it';
				}
			} // else
		} // complete

	});//ajax
	
}

(() => {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      var validata=false;
	  if($("#validationCustom04").val()==$("#validationCustom05").val()) validata=true;
	  if(validata==false) {
		  document.getElementById("validationCustom04").setCustomValidity('Passwords are different');
		  document.getElementById("validationCustom05").setCustomValidity('Passwords are different');
	  }
	  else {
		  document.getElementById("validationCustom04").setCustomValidity('');
		  document.getElementById("validationCustom05").setCustomValidity('');
	  }
	  if (!form.checkValidity() || validata==false) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()