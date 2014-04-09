$(document)
    .ready(function () {

        $('.filter.menu .item')
            .tab()
        ;

        $('.ui.rating')
            .rating({
                clearable: true
            })
        ;

        $('.ui.sidebar').bind('show', function () {
            $('input', this).focus();
        })
            .sidebar('attach events', '.launch.button')
        ;

        $('.ui.dropdown')
            .dropdown()
        ;
        $('.ui .item[data-title]').popup();

    })
;