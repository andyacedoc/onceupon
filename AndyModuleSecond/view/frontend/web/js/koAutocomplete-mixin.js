define(['jquery'], function ($) {
    'use strict';
    var mixin = {
        handleAutocomplete: function (querytext) {
            $('.page-footer').hide();

            if (querytext.length >= 5) {
                $.ajax({
                    method: 'GET',
                    url: this.searchurl,
                    data: {q:querytext},
                    datatype: 'json'
                }).success(
                    function(data){
                        this.searchResult(data);
                        //console.log(data)
                    }.bind(this));
            } else {
                this.searchResult([]);
            }
        }
    };

    return function (target) {

        return target.extend(mixin);
    };
});
