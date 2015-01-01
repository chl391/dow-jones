var editing = 0;

function openEdit(id)
{
	if (editing == 0)
	{
		editing = 1;
		
		nameElementId = "name" + id;
		var name = document.getElementById(nameElementId).innerHTML;
		name = name.replace(/'/g, "&#39;");
		document.getElementById(nameElementId).innerHTML="<input type='text' id='name' value='"+name+"'>";
		
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
	var saveName = encodeURIComponent(document.getElementById("name").value);
	var wlocation="index.php?controller=publisher&action=edit&id="+id+"&name='"+saveName+"'";
	window.location=wlocation;
}

function openDelete(id,name)
{
	alert("WARNING: Deleting a publisher will also delete any books published by the publisher.");
	var clicked = confirm("Are you sure you wanted to delete " + name + " from the database?");
	if (clicked == true)
	{
		window.location = "index.php?controller=publisher&action=delete&id=" + id;
	}
}