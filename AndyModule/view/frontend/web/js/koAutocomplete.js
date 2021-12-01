define(['uiComponent', 'jquery', 'mage/url'], function (Component, $, urlbulder) {
    return Component.extend({
        defaults: {
            searchText: '',
            searchResult: [],
            availableSku: [],
            searchurl: urlbulder.build('anypage/search')
        },
        initObservable: function () {
            this._super();
            this.observe(['searchText', 'searchResult']);

            return this;
        },
        initialize: function () {
            this._super();
            this.searchText.subscribe(this.handleAutocomplete.bind(this));
        },
        handleAutocomplete: function (querytext) {
            if (querytext.length >= 3) {
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
    })
})


//пример
/*
define(['uiComponent'], function (Component) {
    return Component.extend({
        defaults: {
            searchText: '',
            searchResult: [],
            availablesku: [
                '2444',
                '1234',
                '2445',
                '2447'
            ]
        },
        initObservable: function () {
            this._super();
            this.observe(['searchText', 'searchResult']);

            return this;
        },
        initialize: function () {
            this._super();
            this.searchText.subscribe(this.handleAutocomplete.bind(this));
        },
        handleAutocomplete: function (querytext) {
            if (querytext.length >= 3) {
                var filterscu = this.availablesku.filter(function(item) {
                    return item.indexOf(querytext) != -1;
                })
                this.searchResult(filterscu);
                //console.log(querytext);
            } else {
                this.searchResult([]);
            }
        }
    })
})*/
