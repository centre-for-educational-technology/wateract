let map;
let latitude = 58.079;
let longitude = 26.054;

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: latitude, lng: longitude },
        zoom: 7,
    });

    map.data.loadGeoJson(
        "springs_json"
    );

   // var infoWindow = new google.maps.InfoWindow;
    var infowindow = new google.maps.InfoWindow();
    const iconBase =
        //"https://developers.google.com/maps/documentation/javascript/examples/full/images/";
        "http://maps.google.com/mapfiles/ms/icons/";
    const icons = {
        confirmed: {
            icon: iconBase + "blue-dot.png",
        },
        unconfirmed: {
            icon: iconBase + "orange-dot.png",
        },
        featured: {
            icon: iconBase + "red-dot.png",
        },
    };

// When the user clicks, open an infowindow
    map.data.addListener('click', function(event) {
        var myHTML = event.feature.getProperty("title");
        var id = event.feature.getProperty("id");
        infowindow.setContent("<div style='width:150px;'><a href='springs/"+id+"'>"+myHTML+"</a></div>");
        // position the infowindow on the marker
        infowindow.setPosition(event.feature.getGeometry().get());
        // anchor the infowindow on the marker
        infowindow.setOptions({pixelOffset: new google.maps.Size(0,-30)});
        infowindow.open(map);
    });
    map.data.setStyle(function(feature) {
        return {
            icon:icons[feature.getProperty('status')].icon,
        };
    });

}
