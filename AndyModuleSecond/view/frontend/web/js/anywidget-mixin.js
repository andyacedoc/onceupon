define(['jquery'], function ($) {
    var widgetMixin = {
        procAutocomplete: function () {
            this.hideMenu();
            this.options.minchars = 2;
            $(this.element).find('#skuid').on('keyup', this._super.bind(this));
            //this._super();
        },
        hideMenu: function () {
            //$('.sections.nav-sections').hide();
            $('.page-footer').hide();
            $('.logo').hide();
        }
    };

    return function (targetWidget) {
        $.widget('custom.autocomplete', targetWidget, widgetMixin);

        return $.custom.autocomplete;
    }
})

