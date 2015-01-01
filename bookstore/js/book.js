var editing = 0;

function openEdit(id)
{
	if (editing == 0)
	{
		editing = 1;
		
		titleElementId = "title" + id;
		var title = document.getElementById(titleElementId).innerHTML;
		title = title.replace(/'/g, "&#39;");
		authorElementId = "author" + id;
		var authorName = document.getElementById(authorElementId).innerHTML;
		var authorCode = document.getElementById("authorCode").innerHTML;
		publisherElementId = "publisher" + id;
		var publisherName = document.getElementById(publisherElementId).innerHTML;
		var publisherCode = document.getElementById("publisherCode").innerHTML;
		priceElementId = "price" + id;
		var price = document.getElementById(priceElementId).innerHTML;
		ISBNElementId = "ISBN" + id;
		var ISBN = document.getElementById(ISBNElementId).innerHTML;
		publishedDateElementId = "published_date" + id;
		var published_date = document.getElementById(publishedDateElementId).innerHTML;
		
		// var titleHTML = new setInput("text","title",title,"size='100%'");
		
		document.getElementById(titleElementId).innerHTML="<input type='text' id='title' value='"+title+"'size='100%'>";
		document.getElementById(authorElementId).innerHTML="<select id='author'>"+authorCode;
		document.getElementById(publisherElementId).innerHTML="<select id='publisher'>"+publisherCode;
		document.getElementById(priceElementId).innerHTML="<input type='text' id='price' value='"+price+"'>";
		document.getElementById(ISBNElementId).innerHTML="<input type='text' id='ISBN' value='"+ISBN+"'>";
		document.getElementById(publishedDateElementId).innerHTML="<input type ='text' id='published_date' value='"+published_date+"'>";
		
		for (var i = 0; i < document.getElementById('author').options.length; i++)
		{
			if (document.getElementById('author').getElementsByTagName('option')[i].text == authorName)
			{
				document.getElementById('author').getElementsByTagName('option')[i].selected = 'selected';
				break;
			}
		}
		
		for (var i = 0; i < document.getElementById('publisher').options.length; i++)
		{
			if (document.getElementById('publisher').getElementsByTagName('option')[i].text == publisherName)
			{
				document.getElementById('publisher').getElementsByTagName('option')[i].selected = 'selected';
				break;
			}
		}
		
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
	var saveTitle = encodeURIComponent(document.getElementById("title").value);
	var authorX = document.getElementById("author").selectedIndex;
	var authorY = document.getElementById("author").options;
	var saveAuthor = authorY[authorX].value;
	var publisherX = document.getElementById("publisher").selectedIndex;
	var publisherY = document.getElementById("publisher").options;
	var savePublisher = publisherY[publisherX].value;
	var savePrice = document.getElementById("price").value;
	var saveISBN = document.getElementById("ISBN").value;
	var savePublishedDate = document.getElementById("published_date").value;
	
	var wlocation="index.php?controller=book&action=edit&id="+id+"&title='"+saveTitle+"'";
	wlocation+="&author_id="+saveAuthor;
	wlocation+="&publisher_id="+savePublisher;
	wlocation+="&price="+savePrice;
	wlocation+="&ISBN="+saveISBN;
	wlocation+="&published_date='"+savePublishedDate+"'";
	window.location=wlocation;
}

function openDelete(id,title)
{
	var clicked = confirm("Are you sure you wanted to delete " + title + " from the database?");
	if (clicked == true)
	{
		window.location = "index.php?controller=book&action=delete&id=" + id;
	}
}

/*
function setInput(type,id,value,attributes)
{
	this.output = "<input type = '" + type + "' id = '" + id + "' value = '" + value + "' " + attributes + ">"; 
}
*/