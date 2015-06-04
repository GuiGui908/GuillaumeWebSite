function affiche(obj){
	var id = obj.id;
	
		document.getElementById('sousmenu'+4).style.display = "none";

	
	if(document.getElementById('sous'+id)){
		document.getElementById('sous'+id).style.display = "block";
	}
}