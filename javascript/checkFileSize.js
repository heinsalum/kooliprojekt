window.onload = function(){
	document.getElementById("submitPhoto").disabled = true;
	document.getElementById("fileToUpload").addEventListener("change", checkSize);	
}

function checkSize(){
	var fileToUpload = document.getElementById("fileToUpload").files[0];
	if (fileToUpload.size <= 2097152){
		document.getElementById("submitPhoto").disabled = false;
		document.getElementById("fileSizeError").innerHTML = "";
	} else {
		document.getElementById("fileSizeError").innerHTML = "Valitud fail on liiga suur! Valige pilt mahuga kuni 2MB!";
		document.getElementById("submitPhoto").disabled = true;
	}
}	