$('#showLog').click(function (e) {
    var self = $(this);
    var timeOut;
    $('#' + self.attr('data-modal')).modal();
    (function showLog() {
        $.get(self.attr('data-url'))
                .done(function (response) {
                    $('#logModal #logContent').text(response);
                })
                .fail(function (response) {
                    location.href = location.href;
                })
                ;
        timeOut = setTimeout(showLog, 10000);
    })();

    $('#' + self.attr('data-modal')).on('hidden.bs.modal', function (e) {
        clearTimeout(timeOut);
    });
});

$('.showIp').on('click', function (e) {
    var el = $(this);
    swal.queue([{
            title: 'Acceso remoto',
            confirmButtonText: 'Mostrar enlace',
            text: 'Obtener enlace para acceso remoto.',
            showLoaderOnConfirm: true,
            preConfirm: function () {

                return new Promise(function (resolve, reject) {
                    $.ajax({
                        url: el.attr('data-url')
                    }).done(function (result) {
                        var link = '<a target="_blank" href="' + result.url + '">' + result.url + '</a>';
                        swal.insertQueueStep({
                            type: 'success',
                            title: el.attr('data-title'),
                            html: link
                        });
                        resolve();
                    }).fail(function (error) {
                        var response = error.responseJSON;
                        var message = 'No se pudo obtener el enlace de acceso remoto. Compruebe el LOG del DHCP para intentar obtenerlo.';
                        if (response.message) {
                            message = response.message;
                        }
                        swal.insertQueueStep({
                            type: 'error',
                            title: 'Error al obtener enlace',
                            text: message
                        });
                        resolve();
                    });
                });

            }
        }]);

});