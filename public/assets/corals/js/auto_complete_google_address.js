var autocomplete;

function initAutocomplete() {


    var options = {
        types: ['geocode']
    };

    if (corals.utility_google_address_country) {
        options[componentRestrictions] = {
            country: corals.utility_google_address_country
        }
    }

    autocomplete = new google.maps.places.Autocomplete((document.getElementById('_autocomplete')), options);

    autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();

    $('#lat').val(place.geometry.location.lat());
    $('#long').val(place.geometry.location.lng());
    $('#address_street').val(addresComponent('street_address', place, false));
    let city = addresComponent('locality', place, false);
    if (!city) {
        city = addresComponent('administrative_area_level_2', place, false);
    }
    $('#address_city').val(city);
    $('#address_state').val(addresComponent('administrative_area_level_1', place, false));
    $('#address_country').val(addresComponent('country', place, false));


}

function addresComponent(type, geocodeResponse, shortName) {
    for (var i = 0; i < geocodeResponse.address_components.length; i++) {
        for (var j = 0; j < geocodeResponse.address_components[i].types.length; j++) {
            if (geocodeResponse.address_components[i].types[j] == type) {
                if (shortName) {
                    return geocodeResponse.address_components[i].short_name;
                } else {
                    return geocodeResponse.address_components[i].long_name;
                }
            }
        }
    }
    return '';
}

var googleSrc = `https://maps.googleapis.com/maps/api/js?key=${corals.utility_google_address_api_key}&libraries=places&callback=initAutocomplete`;

document.write('<script src="' + googleSrc + '" async defer><\/script>');
