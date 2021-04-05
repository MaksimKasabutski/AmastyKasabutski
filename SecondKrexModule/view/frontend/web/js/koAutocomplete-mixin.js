define(['uiComponent', 'jquery', 'mage/url'], function (Component, $, urlBuilder) {
    return function (Component) {
        return Component.extend({
            initialize: function () {
                this._super();
                this.ajax = null;
                this.searchText.subscribe(this.handleAutocomplete.bind(this));
            },
            handleAutocomplete: function (searchValue) {
                if (this.ajax !== null) {
                    this.ajax.abort();
                }
                if (searchValue.length >= 5) {
                    this.ajax = $.getJSON(this.searchUrl, {
                        sku: searchValue
                    }, function (data) {
                        let items = [];
                        $.each(data, function (key, val) {
                            items.push({'title': key, 'sku': val});
                        });
                        this.searchResult(items);
                    }.bind(this));
                }
            }
        });
    }
})
