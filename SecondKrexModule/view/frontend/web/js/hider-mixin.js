define(['jquery'], function ($) {
    var widgetMixin = {
        hideElement: function () {
            this._super()
            this.hideMenu()
        },
        hideMenu:  function () {
            $('.sections.nav-sections').hide()
        }
    }

    return function (targetWidget) {
        $.widget('mynamespace.krexwidget', targetWidget, widgetMixin)
        return $.mynamespace.krexwidget
    }
})
