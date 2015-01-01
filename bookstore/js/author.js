var editing = 0;

function openEdit(id)
{
	if (editing == 0)
	{
		editing = 1;
		
		firstNameElementId = "firstName" + id;
		var firstName = document.getElementById(firstNameElementId).innerHTML;
		firstName = firstName.replace(/'/g, "&#39;");
		lastNameElementId = "lastName" + id;
		var lastName = document.getElementById(lastNameElementId).innerHTML;
		lastName = lastName.replace(/'/g, "&#39;");
		
		document.getElementById(firstNameElementId).innerHTML="<input type='text' id='firstName' value='"+firstName+"'>";
		document.getElementById(lastNameElementId).innerHTML="<input type='text' id='lastName' value='"+lastName+"'>";
		
		editToSave(id);
	}
	else
	{
		alert("Already editing an entry. Please save first.");
	}
}

function editToSave(id)
{
	document.getElementById("edit" + id).innerHTML="<a href='javascript:void(0)' id='' onclick ='save("+id+")'>Save</a>|<a href='' id''>Cancel</a>";
}

function save(id)
{
	var saveFirstName = encodeURIComponent(document.getElementById("firstName").value);
	var saveLastName = encodeURIComponent(document.getElementById("lastName").value);
	
	var wlocation="index.php?controller=author&action=edit&id="+id+"&firstName='"+saveFirstName+"'";
	wlocation+="&LastName='"+saveLastName+"'";
	window.location=wlocation;
}

function openDelete(id,name)
{
	alert("WARNING: Deleting an author will also delete any books written by the author.");
	var clicked = confirm("Are you sure you wanted to delete " + name + " from the database?");
	if (clicked == true)
	{
		window.location = "index.php?controller=author&action=delete&id=" + id;
	}
}