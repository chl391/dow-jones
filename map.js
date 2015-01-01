var dataset = dataset;

var minZoom = 4;

var map = new google.maps.Map(document.getElementById('map-canvas'), {
    zoom: minZoom,
    center: new google.maps.LatLng(37.9797, -100.843),
});

var strictBounds = new google.maps.LatLngBounds(
    // Find exact coordinates later
    new google.maps.LatLng(37.9797, -100.843), 
    new google.maps.LatLng(37.9797, -100.843)
);

google.maps.event.addListener(map, 'dragend', function() {
    if (strictBounds.contains(map.getCenter())) return;

    var c = map.getCenter(),
    x = c.lng(),
    y = c.lat(),
    maxX = strictBounds.getNorthEast().lng(),
    maxY = strictBounds.getNorthEast().lat(),
    minX = strictBounds.getSouthWest().lng(),
    minY = strictBounds.getSouthWest().lat();

    if (x < minX) x = minX;
    if (x > maxX) x = maxX;
    if (y < minY) y = minY;
    if (y > maxY) y = maxY;

    map.setCenter(new google.maps.LatLng(y, x));
});

google.maps.event.addListener(map, 'zoom_changed', function() {
    if (map.getZoom() < minZoom) {
    	map.setZoom(minZoom)
    };
});

for (var i = 0; i < dataset.length; i++){
	var infowindow = new google.maps.InfoWindow();
	var myLatlng = new google.maps.LatLng(dataset[i][1]["data"],dataset[i][2]["data"]);
	
	var marker = new google.maps.Marker({
	    position: myLatlng,
	    map: map,
	    title: dataset[i][0]["text"],
        loc1: dataset[i][2]["data"],
        loc2: dataset[i][1]["data"]
	});

	marker.content = '<div id="content">'+
	'<p>City/Region Name: '+dataset[i][0]["text"]+'<br>'+
	'Value: '+dataset[i][3]["text"]+'<br>';

	google.maps.event.addListener(marker, 'click', function() {
        var url = 'http://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?location='+this.loc1+'%2C'+this.loc2+'&f=pjson';
        var subregion = "null";
        var country = "null";
        var address = "null";
        var type = "null";
        jQuery.ajax({
             type: "GET",
             url: url,
             dataType: "json",
             cache: false,
             async: false,
             crossDomain: true,
             processData: true,

             success: function (data) {
                 //alert(JSON.stringify(data));
                 if (!data["error"]){
                    subregion = data["address"]["Subregion"];
                    country = data["address"]["CountryCode"];
                    address = data["address"]["Address"];
                 }
             },

             error: function (XMLHttpRequest, textStatus, errorThrown) {
                 alert("Error");
             }
         });
        
        var url2 = 'http://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/find?location='+this.loc1+'%2C'+this.loc2+'&f=pjson'; 
        jQuery.ajax({
             type: "GET",
             url: url2,
             dataType: "json",
             cache: false,
             async: false,
             crossDomain: true,
             processData: true,

             success: function (data) {
                 if (data["locations"].length > 3){
                    type = data["locations"][3]["Addr_Type"];
                 }
             },

             error: function (XMLHttpRequest, textStatus, errorThrown) {
                 alert("Error");
             }
         });

        this.content += 'Subregion: '+subregion+'<br>Country: '+country+'<br>Address: '+address+'<br>Type: '+type+'</p></div>';
        infowindow.setContent(this.content);
        infowindow.open(this.getMap(), this);
 	});

}
