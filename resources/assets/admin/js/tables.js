/**
 * Loading the DataTable dependencies.
 */

require('./datatable');

/**
 * This token is used to authenticate ajax request to CMS API.
 * 
 * @type {String}
 */
var apiToken = 'Bearer ' + window.Noox.apiToken;

/**
 * Automatically instantiate DataTables object in the target element.
 * 
 * @param  string target
 * @param  string url      Includes /cms/api/ prefix.
 * @return Object
 */
function dtAttacher(target, url) {
    if (typeof target !== 'undefined' && typeof url !== 'undefined') {
        return $(target).DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url : window.location.origin + '/cms/api/' + url,
                type: 'GET',
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", apiToken);
                }
            }
        });
    }
}

$(document).ready(function() {
    dtAttacher('#example', 'users');
} );