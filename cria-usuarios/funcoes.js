
function doPost(formName, actionName){

	var hiddenControl = document.getElementById('action');
	var theForm = document.getElementById(formName);

	hiddenControl.value = actionName;
	theForm.submit();
}

function validateForm() {
    var x = document.forms["cpf"]["cpf"].value;
    if (x == "") {
        alert("Preencher o Campo");
        return false;
    }
}

