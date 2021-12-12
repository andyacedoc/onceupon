define(['uiComponent'], function (Component) {
    return Component.extend({
        defaults: {
            valueSecond: '',
            valueMinute: '',
            valueHour: '',
            iSecond: 0,
            iMinute: 0,
            iHour: 0,
            interval: '',
        },
        initObservable: function () {
            this._super();
            this.observe('valueSecond');
            this.observe('valueMinute');
            this.observe('valueHour');

            return this;
        },
        initialize: function () {
            this._super();
            this.valueSecond('00');
            this.valueMinute('00');
            this.valueHour('00');
        },
        clickStart: function () {
            if (!this.interval) {
                this.interval = setInterval(this.timer.bind(this), 100);
            }
            // console.log('clickStart');
        },
        clickPause: function () {
            clearInterval(this.interval);
            this.interval = '';
            // console.log('clickPause');
        },
        clickStop: function () {
            clearInterval(this.interval);
            this.interval = '';
            this.iSecond = 0;
            this.iMinute = 0;
            this.iHour = 0;
            this.valueSecond('00');
            this.valueMinute('00');
            this.valueHour('00');
            // console.log('clickStop');
        },
        timer: function () {
            ++this.iSecond;
            this.valueSecond((this.iSecond < 10) ? '0' + this.iSecond : this.iSecond);

            if (this.iSecond === 60) {
                this.iSecond = 0;
                ++this.iMinute
                this.valueMinute((this.iMinute < 10) ? '0' + this.iMinute : this.iMinute);

                if (this.iMinute === 60) {
                    this.iMinute = 0;
                    ++this.iHour
                    this.valueHour((this.iHour < 10) ? '0' + this.iHour : this.iHour);
                }
            }
        }
    })
})
