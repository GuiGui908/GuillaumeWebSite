// JavaScript Document


function envoyer()
{
	with(window.document.post)
	{
		// Expréssions régulières de contrôle pour les champs
		var mailOK = /^[0-9a-z._-]+@{1}[0-9a-z.-]{2,}[.]{1}[a-z]{2,5}$/;
/*		ou plus complet /^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$/	*/
		var telOK = /^0\d{9}$/;			// Commence par 0 puis 9 chiffres
		var prixOK = /^[0-9 ]{1,}[,.]{0,1}[0-9]{0,2}$/;		// Contient un nombre (chiffre avec 2 décimales ou pas)
		var surfOK = /^[0-9 ]{1,}[,.]{0,1}[0-9]{0,2}$/;

		if (type.value.length == 0)	// Vérif du champ 1
		{
			alert("Type de logement manquant !");
			return false;
		}
		
		if(surf.value.length == 0)	// Verif du champ 2
		{
			alert("La surface n'est pas complétée !");
			return false;
		}
		else if(surfOK.test(surf.value) == false)
		{
			alert("La surface doit etre un nombre !");
			return false;
		}
		
		if(prix.value.length == 0)	// Verif du champ 3
		{
			alert("Le loyer n'est pas complété !");
			return false;
		}
		else if(prixOK.test(prix.value) == false)
		{
			alert("Le loyer doit etre un nombre !");
			return false;
		}
		
		if(adr1.value.length == 0)	// Verif du champ 4
		{
			alert("L'adresse n'est pas complétée !");
			return false;
		}
		
		if(desc.value.length == 0)	// Verif du champ 5
		{
			alert("Mettez une petite description svp !");
			return false;
		}
		
		if(nom.value.length == 0)	// Verif du champ 6
		{
			alert("L'identité de l'annonceur n'est pas complétée !");
			return false;
		}
		
		if(mailOK.test(mail.value) == false)		// Vérif de l'email selon l'espression régulière
		{
			alert("L'adresse mail est invalide !");
			return false;
		}
		
		if(telOK.test(tel.value) == false)	// Verif du champ 7
		{
			alert("Le numéro de téléphone semble erroné !");
			return false;
		}
	}
	return true;
}
