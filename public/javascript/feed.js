$(document)
    .ready(function () {

        $('.filter.menu .item')
            .tab()
        ;

        $('.ui.sidebar').sidebar({
            onShow: function() {
                $('input', this).focus();
            }
        })
            .sidebar('attach events', '.launch.button')
        ;

        $('.ui.dropdown')
            .dropdown()
        ;
        $('.ui .item[data-title]').popup();

    })
;