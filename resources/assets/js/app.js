
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
global.moment = require('moment');
require('tempusdominus-bootstrap-4');

shareIt = {} || shareIt;
window.Vue = require('vue');

shareIt.icons = {
    time: 'oi oi-clock-o',
    date: 'oi oi-calendar',
    up: 'oi oi-arrow-thick-top',
    down: 'oi oi-arrow-thick-bottom',
    previous: 'oi oi-chevron-left',
    next: 'oi oi-chevron-right',
    today: 'oi oi-calendar-check-o',
    clear: 'oi oi-delete',
    close: 'oi oi-times'
};

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});

$(function () {
    $('form.del-btn').submit(function(e){
        e.preventDefault();
        rtn = confirm('Are you sure you want to delete this?');
        return rtn;
    });

    $('a.del-btn').click(function(e){
        e.preventDefault();
        rtn = confirm('Are you sure you want to delete this?');
        return rtn;
    });

    $("#reservation-type").change(function(e){
        repeatableReservation();
    });

    $("[name='reservation-is-date-based']").click(function(e){
        adjustDateBased();
    });

    $('.create-reservations').ready(function(e){
        repeatableReservation();
        adjustDateBased();
    });

    $('#reservation-date').datetimepicker({
        format: 'YYYY-MM-DD',
        icons: shareIt.icons
    });

    $('#reservation-start-date').datetimepicker({
        format: 'YYYY-MM-DD',
        icons: shareIt.icons
    });

    $('#reservation-end-date').datetimepicker({
        format: 'YYYY-MM-DD',
        icons: shareIt.icons
    });

    $('#reservation-from').datetimepicker({
        format: 'HH:mm',
        icons: shareIt.icons
    });

    $('#reservation-to').datetimepicker({
        format: 'HH:mm',
        icons: shareIt.icons
    });


    $("#reservation-from").on("change.datetimepicker", function (e) {
        $('#reservation-to').datetimepicker('minDate', e.date);
    });
    $("#reservation-to").on("change.datetimepicker", function (e) {
        $('#reservation-from').datetimepicker('maxDate', e.date);
    });
});

function repeatableReservation(){
    res_type = $('#reservation-type').val();
    if(res_type == 2){
        $('.repeatable').show();
    } else {
        $('.repeatable').hide();
    }
}

function adjustDateBased(){
    date_based = $('#reservation-is-date-based-yes').prop("checked");
    weekly = $('#reservation-weekly').prop("checked");
    if(date_based){
        if(weekly){
            $('#reservation-monthly').prop("checked", true);
        }
        $('.notDateBase').hide();
    }
    else {
        $('.notDateBase').show();
    }
}
