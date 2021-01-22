$("#get-coords").click(function (e) {
    e.preventDefault();
    var self = $(this);
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            $('#' + self.attr('data-latitude-id')).val(position.coords.latitude);
            $('#' + self.attr('data-longitude-id')).val(position.coords.longitude);
        });
    } else {
        alert("La geolocalización no está habilitada en este dispositivo.");
    }

});