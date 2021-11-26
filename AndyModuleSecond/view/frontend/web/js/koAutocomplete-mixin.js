define(['jquery'], function ($) {
    'use strict';
    var mixin = {
        handleAutocomplete: function (querytext) {
            $('.page-footer').hide();

            if (querytext.length >= 5) {
                var filterscu = this.availablesku.filter(function (item) {
                    return item.sku.indexOf(querytext) != -1;
                })
                this.searchResult(filterscu);
                //console.log(querytext);
            } else {
                this.searchResult([]);
            }
        }
    };

    return function (target) {

        return target.extend(mixin);
    };
});
