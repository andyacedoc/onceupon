//пример
define(['jquery', 'mage/url'], function ($, urlbulder) {
    $.widget('custom.autocomplete', {
        options: {
            minchars: null,
            availablesku: [
                '2444',
                '1234',
                '2445',
                '2447'
            ],
            resultlist: '.result-list',
            searchurl: urlbulder.build('search/ajax/suggest')
        },
        _create: function () {
            $(this.element).find('#skuid').on('keyup', this.procAutocomplete.bind(this));
        },
        procAutocomplete: function(event) {
            var querytext = $(event.target).val();
            $(this.options.resultlist).empty();

            if (querytext.length >= this.options.minchars) {
                $.getJSON(
                    this.options.searchurl,
                    {q:querytext},
                    function(data) {
                        if (data.length) {
                            var searchlist = data.map(function(item) {
                                return $('<li/>').text(item.num_results);
                            })
                            $(this.options.resultlist).append(searchlist);
                        } else {
                            $(this.options.resultlist).empty();
                        }
                    }.bind(this)
                );
            }
        }
    });

    return $.custom.autocomplete;
});


//пример
/*define(['jquery'], function ($) {
    $.widget('custom.autocomplete', {
        options: {
            minchars: null,
            availablesku: [
                '2444',
                '1234',
                '2445',
                '2447'
            ],
            resultlist: '.result-list'
        },
        _create: function () {
            $(this.element).find('#skuid').on('keyup', this.procAutocomplete.bind(this));
        },
        procAutocomplete: function(event) {
            var querytext = $(event.target).val();
            $(this.options.resultlist).empty();

            if (querytext.length >= this.options.minchars) {
                var filterscu = this.options.availablesku.filter(function(item) {
                    return item.indexOf(querytext) != -1;
                })

                if (filterscu.length) {
                    var searchlist = filterscu.map(function(item) {
                        return $('<li/>').text(item);
                    })
                    $(this.options.resultlist).append(searchlist);
                } else {
                    $(this.options.resultlist).empty();
                }
                //console.log(filterscu);
            }
        }
    });

    return $.custom.autocomplete;
});*/


//пример
// define(['jquery'], function ($) {
//     $.widget('custom.autocomplete', {
//         options: {
//             selector: null
//         },
//         _create: function () {
//             $(this.options.selector).hide();
//             $(this.element).hide();
//         }
//     });
//
//     return $.custom.autocomplete;
// });


//пример
// define(['jquery'], function ($) {
//     return function (config,element) {
//         //$('.page-footer').hide();
//         $(element).hide();
//         $(config.selector).hide();
//     }
// });
