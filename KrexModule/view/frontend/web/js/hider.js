define(['jquery'], function ($) {
    $.widget('mynamespace.krexwidget', {
        options: {
            selector: null
        },
        _create: function () {
            this.hideElement()
        },
        hideElement: function () {
            $(this.options.selector).hide()
        }
    })
    return $.mynamespace.krexwidget
})
