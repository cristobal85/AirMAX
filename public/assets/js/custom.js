/*
 =========================================
 |                                       |
 |           Scroll To Top               |
 |                                       |
 =========================================
 */
$('.scrollTop').click(function () {
    $("html, body").animate({scrollTop: 0});
});


$('.navbar .dropdown.notification-dropdown > .dropdown-menu, .navbar .dropdown.message-dropdown > .dropdown-menu ').click(function (e) {
    e.stopPropagation();
});

/*
 =========================================
 |                                       |
 |       Multi-Check checkbox            |
 |                                       |
 =========================================
 */

function checkall(clickchk, relChkbox) {

    var checker = $('#' + clickchk);
    var multichk = $('.' + relChkbox);


    checker.click(function () {
        multichk.prop('checked', $(this).prop('checked'));
    });
}


/*
 =========================================
 |                                       |
 |           MultiCheck                  |
 |                                       |
 =========================================
 */

/*
 This MultiCheck Function is recommanded for datatable
 */

function multiCheck(tb_var) {
    tb_var.on("change", ".chk-parent", function () {
        var e = $(this).closest("table").find("td:first-child .child-chk"), a = $(this).is(":checked");
        $(e).each(function () {
            a ? ($(this).prop("checked", !0), $(this).closest("tr").addClass("active")) : ($(this).prop("checked", !1), $(this).closest("tr").removeClass("active"))
        })
    }),
            tb_var.on("change", "tbody tr .new-control", function () {
                $(this).parents("tr").toggleClass("active")
            })
}

/*
 =========================================
 |                                       |
 |           MultiCheck                  |
 |                                       |
 =========================================
 */

function checkall(clickchk, relChkbox) {

    var checker = $('#' + clickchk);
    var multichk = $('.' + relChkbox);


    checker.click(function () {
        multichk.prop('checked', $(this).prop('checked'));
    });
}

/*
 =========================================
 |                                       |
 |               Tooltips                |
 |                                       |
 =========================================
 */

$('.bs-tooltip').tooltip();

/*
 =========================================
 |                                       |
 |               Popovers                |
 |                                       |
 =========================================
 */

$('.bs-popover').popover();


/*
 ================================================
 |                                              |
 |               Rounded Tooltip                |
 |                                              |
 ================================================
 */

$('.t-dot').tooltip({
    template: '<div class="tooltip status rounded-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
})


/*
 ================================================
 |            IE VERSION Dector                 |
 ================================================
 */

function GetIEVersion() {
    var sAgent = window.navigator.userAgent;
    var Idx = sAgent.indexOf("MSIE");

    // If IE, return version number.
    if (Idx > 0)
        return parseInt(sAgent.substring(Idx + 5, sAgent.indexOf(".", Idx)));

    // If IE 11 then look for Updated user agent string.
    else if (!!navigator.userAgent.match(/Trident\/7\./))
        return 11;

    else
        return 0; //It is not IE
}

/*
 =========================================
 |                                       |
 |           Delete element CRUD         |
 |                                       |
 =========================================
 */
$('.prompt-item').click(function (e) {
    e.preventDefault();
    var el = $(this);
    var dataDomAction = el.attr('data-dom-action');

    swal.queue([{
            title: el.attr('data-title'),
            type: el.attr('data-type'),
            text: el.attr('data-message'),
            showCancelButton: true,
            confirmButtonText: el.attr('data-confirm-text'),
            cancelButtonText: 'Cancelar',
            padding: '2em',
            showLoaderOnConfirm: true,

            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        url: el.attr('data-url'),
                        type: el.attr('data-method'),
                    }).done(function (result) {
                        if (dataDomAction === 'delete') {
                            el.closest('tr').remove();
                        }
                        swal.insertQueueStep({
                            type: 'success',
                            title: el.attr('data-title'),
                            text: result.message,
                        });
                        resolve();
                    }).fail(function (error) {
                        var response = error.responseJSON;
                        console.log(response);
                        swal.insertQueueStep({
                            type: 'error',
                            title: el.attr('data-title'),
                            text: response.message,
                        });
                        resolve();
                    });
                });

            }
        }]);
});