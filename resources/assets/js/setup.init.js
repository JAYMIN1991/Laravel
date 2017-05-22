/**
 * Created by flinnt-php-6 on 24/1/17.
 */

// Set the authorization header and csrf token to every ajax request
;$(function () {
    $( document ).ajaxSend(function( event, jqXHR, ajaxOptions ) {
        jqXHR.setRequestHeader('Accept', "application/json");
        jqXHR.setRequestHeader('Authorization', "Bearer " + Cookies.get('FA'));
        jqXHR.setRequestHeader('X-CSRF-TOKEN', $("meta[name='csrf']").attr("content"))
    });
});
