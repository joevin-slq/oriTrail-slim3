/*
 * Personalisation JavaScript / JQuery
 */

 // désactive l'appui sur la touche entrée
 $('html').bind('keypress', function(e)
 {
    if(e.keyCode == 13) {
       return false;
    }
 });

// Carte Google Map
function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 44.3493, lng: 2.5759},
    zoom: 10
  });
  var input = document.getElementById('map-input');

  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.bindTo('bounds', map);

  var infowindow = new google.maps.InfoWindow();
  var marker = new google.maps.Marker({
    map: map,
    anchorPoint: new google.maps.Point(0, -29)
  });

  autocomplete.addListener('place_changed', function() {
    infowindow.close();
    marker.setVisible(false);
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      // Lorsqu'aucun lieu n'est trouvé on sors
      return;
    }

    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);
    }
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);

    var address = '';
    if (place.address_components) {
      address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
      ].join(' ');
    }

    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
    infowindow.open(map, marker);

    var coords  = place.geometry.location;
    document.getElementById('longitude').value = coords.lng();
    document.getElementById('latitude').value = coords.lat();

    var numero = rue = ville = cp = '';

    for (var i = 0; i < place.address_components.length; i++) {
      for (var j = 0; j < place.address_components[i].types.length; j++) {
        if (place.address_components[i].types[j] == "street_number") {
          numero = place.address_components[i].long_name;
        }
        if (place.address_components[i].types[j] == "route") {
          rue = place.address_components[i].long_name;
        }
        if (place.address_components[i].types[j] == "locality") {
          ville = place.address_components[i].long_name;
        }
        if (place.address_components[i].types[j] == "postal_code") {
          cp = place.address_components[i].long_name;
        }
      }
    }
    document.getElementById('adresse').value = numero + ' ' + rue;
    document.getElementById('ville').value   = ville;
    document.getElementById('cp').value      = cp;
  });
}
