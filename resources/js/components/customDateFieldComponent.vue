<template>
    <div>
        <date-range-picker
                :alwaysShowCalendars="alwaysShowCalendars"
                :autoApply="autoApply"
                :direction="direction"
                :linkedCalendars="linkedCalendars"
                :localeData="localeData"
                :opens="opens"
                :ranges="ranges"
                :showDropdowns="showDropdowns"
                :showWeekNumbers="showWeekNumbers"
                :singleDatePicker="singleDatePicker"
                :timePicker="timePicker"
                :timePicker24Hour="timePicker24Hour"
                :timePickerSeconds="timePickerSeconds"
                @toggle="checkOpen"
                @update="updateValues"
                ref="picker"
                v-model="dateRange"
        >

            <div slot="input"
                 style="min-width: 70px;min-width: 70px;overflow:hidden;width:70px; height: 17px;">
                {{dateRange.startDate }} -  {{dateRange.endDate }}
            </div>


        </date-range-picker>
    </div>
</template>

<script>
    import DateRangePicker from 'vue2-daterange-picker'
    import 'vue2-daterange-picker/dist/vue2-daterange-picker.css'
    import {mixin as clickaway} from 'vue-clickaway';


    var moment = require('moment');

    export default {
        components: {
            DateRangePicker,

            moment,
            clickaway
        },


        data: function () {
            return {

                localeData: {
                    direction: 'rtl',
                    format: 'MM-DD-YYYY',
                    separator: ' - ',
                    applyLabel: 'Search',
                    cancelLabel: 'Cancel',
                    weekLabel: 'W',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: moment.weekdaysMin(),
                    monthNames: moment.monthsShort(),
                    firstDay: moment.localeData().firstDayOfWeek()
                },
                opens: 'left',
                singleDatePicker: false,
                timePicker: true,
                timePicker24Hour: true,
                timePickerSeconds: true,
                showWeekNumbers: true,
                showDropdowns: true,
                autoApply: false,
                linkedCalendars: true,
                alwaysShowCalendars: true,
                dateRange: false,
                minDate: '',
                maxDate: '',
                direction: 'rtl',
                separator: ' - ',
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
                weekLabel: 'W',
                customRangeLabel: 'Custom Range',
                daysOfWeek: moment.weekdaysMin(),
                monthNames: moment.monthsShort(),
                firstDay: moment.localeData().firstDayOfWeek(),
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'This month': [moment().startOf('month'), moment().endOf('month')],
                    'This year': [moment().startOf('year'), moment().endOf('year')],
                    'Last week': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
                    'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],

                    'Last Year': [moment().subtract(1, 'year').startOf('year'),
                        moment().subtract(1, 'year').endOf('year')],

                }
            };
        },

        created: function () {
            console.log(moment.localeData().longDateFormat('L'));
            // var now = new Date();
            // now.setMinutes(0.0); // timestamp
            // now.setSeconds(0.0); // timestamp
            // now.setHours(23); // timestamp
            //
            //
            // var time =   new Date(now); // Date object
            // // this.dateRange  = Date.now();


            // console.log(this.picker);

        },
        methods: {
            away: function () {
                console.log('clicked away');
            },
            updateValues() {

                // console.log(this.dateRange);
                //2019-10-10 15:44:26
                //2019-10-13 14:09:53
                var _startDate = new Date(this.dateRange.startDate);
                var _endDate = new Date(this.dateRange.endDate);

                // console.log(_startDate);

                var __startDate = _startDate.getFullYear() + '-' + (_startDate.getMonth() + 1) + '-' +
                    _startDate.getDate() + ' ' + _startDate.getHours() + ':' + _startDate.getMinutes()
                    + ':' + _startDate.getSeconds();

                var __endDate = _endDate.getFullYear() + '-' + (_endDate.getMonth() + 1) + '-' +
                    _endDate.getDate() + ' ' + _endDate.getHours() + ':' + _endDate.getMinutes()
                    + ':' + _endDate.getSeconds();

                this.$emit('updated', {
                    start: __startDate,
                    end: __endDate,
                });
            },


            checkOpen() {
                // console.log(this.dateRange);
            },
        }
    }
</script>

<style>
    .daterangepicker {
        box-shadow: 5px 8px 10px #777;
        display: flex !important;
        /* background: black; */
        right: -545px !important;
    }


</style>