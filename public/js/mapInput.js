
let map;
let marker;
let latitude = 57.779;
let longitude = 26.054;

function initMap() {
    let lat_value = document.getElementById('latitude').value;
    if (lat_value) {
        latitude = parseFloat(lat_value);
    }
    let lng_value = document.getElementById('longitude').value;
    if (lng_value) {
        longitude = parseFloat(lng_value);
    }

    map = new google.maps.Map(document.getElementById("address-map"), {
        center: { lat: latitude, lng: longitude },
        //center: { lat: 37.783, lng: -122.403 },
        zoom: 8,
        mapTypeId: 'terrain'
    });

    if (lat_value && lng_value) {
        const latlng = {
            lat: latitude,
            lng: longitude,
        };
        map.setZoom(12);
        marker = new google.maps.Marker({
            position: latlng,
            map: map,
        });

    }

    const geocoder = new google.maps.Geocoder();

    map.addListener('click', function(mapsMouseEvent) {
        if (marker) {
            marker.setMap(null);
        }
        //console.log(mapsMouseEvent);
        let latitude = mapsMouseEvent.latLng.lat();
        let longitude = mapsMouseEvent.latLng.lng();
        document.getElementById("latitude").value = latitude;
        document.getElementById("longitude").value = longitude;
        map.panTo(new google.maps.LatLng(latitude, longitude))
        geocodeLatLng(geocoder, latitude, longitude);

    });


    // WMS
    var wmsOptions = {
        getTileUrl : WMSGetTileUrl2 ,
        tileSize : new google.maps.Size(256 , 256)
    };
    //wmsMapType = new google.maps.ImageMapType( wmsOptions ) ;
    //map . overlayMapTypes . insertAt (0 , wmsMapType ) ;

}

function geocodeLatLng(geocoder, latitude, longitude) {
    const latlng = {
        lat: latitude,
        lng: longitude,
    };
    geocoder.geocode(
        {
            location: latlng,
        },
        (results, status) => {
            if (status === "OK") {
                if (results[0]) {

                    let address_components = results[0].address_components;
                    console.log(address_components);

                    map.setZoom(12);
                    marker = new google.maps.Marker({
                        position: latlng,
                        map: map,
                    });
                    console.log(results[0]);

                    let address = getAddressObject(address_components);
                    document.getElementById("county").value = address.county;
                    document.getElementById("municipality").value = address.municipality;

                    //infowindow.setContent(results[0].formatted_address);
                    //infowindow.open(map, marker);
                } else {
                    console.log("No results found");
                }
            } else {
                console.log("Geocoder failed due to: " + status);
            }
        }
    );
};

function getAddressObject(address_components) {
    let ShouldBeComponent = {
        county: [
            "administrative_area_level_1",
            "administrative_area_level_2",
            "administrative_area_level_3",
            "administrative_area_level_4",
            "administrative_area_level_5"
        ],
        municipality: [
            "locality",
            "sublocality",
            "sublocality_level_1",
            "sublocality_level_2",
            "sublocality_level_3",
            "sublocality_level_4"
        ],
        country: ["country"]
    };

    var address = {
        county: "",
        municipality: "",
        country: ""
    };
    address_components.forEach(component => {
        for (var shouldBe in ShouldBeComponent) {
            if (ShouldBeComponent[shouldBe].indexOf(component.types[0]) !== -1) {
                if (shouldBe === "country") {
                    address[shouldBe] = component.short_name;
                } else {
                    address[shouldBe] = component.long_name;
                }
            }
        }
    });
    return address;
};

function WMSGetTileUrl2 (coord , zoom ) {
    var proj = map.getProjection();
    var zfactor = Math.pow(2, zoom);
    var top = proj.fromPointToLatLng(
        new google.maps.Point(coord.x * 256 /
            zfactor, coord.y * 256 / zfactor));
    var bot = proj.fromPointToLatLng(
        new google.maps.Point((coord.x + 1) * 256 /
            zfactor, (coord.y + 1) * 256 / zfactor)
    );
    var deltaX = 0.0013;
    var deltaY = 0.00058;
    var bbox = (top.lng() + deltaX) + "," + (bot.lat()
        + deltaY) + "," + (bot.lng() + deltaX) + "," + (
        top.lat() + deltaY);
    var url = 'http://kaart.maaamet.ee/wms/alus-geo?';
    url += '&SERVICE=WMS'; // WMS teenus
    url += '&REQUEST=GetMap';
    url += '&VERSION=1.1.1'; // WMS versioon
    url += '&LAYERS=EESTIFOTO'; // WMS kiht  //reljeef
    url += '&FORMAT=image/jpeg'; // WMS formaat
    url += '&SRS=EPSG:4326';
    url += '&BBOX=' + bbox;
    url += '&WIDTH=256 ';
    url += '&HEIGHT=256 ';
    console.log(url);
    return url;
};

