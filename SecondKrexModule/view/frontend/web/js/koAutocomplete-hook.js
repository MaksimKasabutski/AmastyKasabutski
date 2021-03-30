define(['uiComponent', 'jquery', 'mage/url'], function (Component, $, urlBuilder) {
    return function (Component) {
        return Component.extend({
            initialize: function () {
                this._super()
                this.searchText.subscribe(this.handleAutocomplete.bind(this))
            },
            handleAutocomplete: function (searchValue) {
                if (searchValue.length >= 5) {
                    $.getJSON(this.searchUrl, {
                        sku: searchValue
                    }, function (data) {
                        var items = [];
                        $.each(data, function (key, val) {
                            items.push({'title': key, 'sku': val});
                        });
                        this.searchResult(items)
                    }.bind(this))
                }
            }
        });
    }
})
