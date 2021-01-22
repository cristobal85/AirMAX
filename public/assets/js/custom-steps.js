var form = $("#install-form");

$.validator.addMethod('connected', function (value, el, param) {
    return value === param;
});

form.validate({
    errorPlacement: function errorPlacement(error, element) {
        element.before(error);
    },
    rules: {
        // ---- DB
        dbConnected: {
            connected: 'ok',
        },
        // ---- DHCP
        dhcpConnect: {
            connected: 'ok',
            required: true
        }
    },
    messages: {
        // ---- DB
        dbConnected: {
            connected: 'Debe conectar a la base de datos antes de continuar.'
        },
        // ---- DHCP
        dhcpConnect: {
            connected: 'Debe conectar al servidor DHCP antes de continuar.'
        }
    }
});

form.children("div").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    cssClass: 'circle wizard',
    labels: {
        finish: "Fin",
        next: "Siguiente",
        previous: "Anterior",
        loading: "Cargando ..."
    },
    onStepChanging: function (event, currentIndex, newIndex)
    {
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    },
    onFinishing: function (event, currentIndex)
    {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
    },
    onFinished: function (event, currentIndex)
    {
        $('#install-loader').toggleClass('d-none');
        form.submit();
    }
});

// ---- DB
$('#check-db-connection').click(function (e) {
    e.preventDefault();
    form.validate().settings.ignore = ":disabled,:hidden,#dbConnected";
    if (!form.valid()) {
        return form.valid();
    }
    
    $('.spinner').toggleClass('d-none');
    
    dbForm = $('#db_form :input').serialize();
    $.post("/install/check-bd", dbForm)
            .done(function (data) {
                swal({
                    type: 'success',
                    title: 'Base de datos',
                    text: data.message,
                    padding: '2em'
                });
                $('#dbConnected').val('ok');
            })
            .fail(function (error) {
                swal({
                    type: 'error',
                    title: 'Base de datos',
                    text: error.responseText,
                    padding: '2em'
                });
                $('#dbConnected').val('false');
                console.log(error.responseText);
            })
            .always(function () {
                $('.spinner').toggleClass('d-none');
            });
});

// ---- DHCP
$('#check-dhcp-connection').click(function (e) {
    e.preventDefault();
    form.validate().settings.ignore = ":disabled,:hidden,#dhcpConnect";
    if (!form.valid()) {
        return form.valid();
    }
    $('.spinner').toggleClass('d-none');
    
    dhcpForm = $('#dhcp_config_form :input').serialize();
    $.post("/install/check-dhcp", dhcpForm).done(function (data) {
        swal({
            type: 'success',
            title: 'Servidor DHCP',
            text: data.message,
            padding: '2em'
        });
        $('#dhcpConnect').val('ok');
    }).fail(function (error) {
        swal({
            type: 'error',
            title: 'Servidor DHCP',
            text: error.responseText,
            padding: '2em'
        });
        $('#dhcpConnect').val('false');
        console.log(error.responseText);
    }).always(function () {
        $('.spinner').toggleClass('d-none');
    });
});