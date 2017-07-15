/**
 * Helper functions to attach DataTable object to an element.
 */

/**
 * This token is used to authenticate ajax request to CMS API.
 * 
 * @type {Dom}
 */
var jwt = document.head.querySelector('meta[name="jwt"]');

/**
 * Attach DataTable to a given element.
 * 
 * @param  string target              target object selector
 * @param  string url                 api endpoint
 * @param  object additionalOptions   additional DT options
 * @return object                     DOM object
 */
window.attachDT =  function(target, url, additionalOptions) {
    if ((typeof target !== 'undefined') && (typeof url !== 'undefined') && jwt && (jwt.content.length > 0)) {
        var options = {
            responsive: true,
            // processing: true,
            serverSide: true,
            ajax: {
                url : window.location.origin + '/cms/api/' + url,
                type: 'GET',
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", 'Bearer ' + jwt.content);
                }
            }
        };
        if (typeof additionalOptions === "object") {Object.assign(options, additionalOptions);}
        return $(target)
                .on( 'processing.dt', function ( e, settings, processing ) {
                    if (typeof NProgress !== 'undefined') {
                        processing ? NProgress.start() : NProgress.done() ;
                    }
                }).DataTable(options);
    }
}

window.getReportLabelType = function (status) {
    switch (status) {
        case 'open':
            return 'danger';
        case 'investigating':
            return 'warning';
        case 'closed':
            return 'default';
        default:
            return 'success';
    }
}