;$(document).ready(function (e) {
    $(function () {
        if (typeof $.fn.select2 != 'undefined') {

            var select2 = $('.select2-el');
            if (select2.length) {
                select2.select2();
            }
        }
        //$('[data-tooltip="true"]').tooltip();
        // $('body').tooltip({ selector: '[data-tooltip="true"]' });
    });

    $(function () {
        // Javascript localization using i18next library.
        // It will generate the `initialized` event after loading the default language json files.
        // Developer can use that event to perform necessary action on their page level scripts.
        // @see http://i18next.com/docs/api/#on-loaded
        window.i18next
            .use(i18nextXHRBackend)
            .init({
                load: 'currentOnly',
                lng: 'en',
                fallbackLng: 'en',
                ns : ['message', 'config'],
                backend: {
                    loadPath: '/lang/{{lng}}/{{ns}}.json'
                }
            });
    });
});

/**
 * @TODO :: change this function to append options to select element
 * Currently not using this method
 */

function addOptionsToSelect(element, text, value, defaultSelected, selected) {
    if (text.constructor === Array) {
        for(var option in text) {
            element.append(new Option(option[value[0]], option[value[1]]));
        }
    } else {
        if (typeof text === "string" || typeof text === "number" || typeof value === "string"
            || typeof value === "number") {
            element.append(text, value, defaultSelected, selected);
        }
    }
}

function dateWrapper(date, format, strict, local) {
    return moment(date, format, strict, local);
}