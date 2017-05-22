
/**
 * This token is used to authenticate ajax request to CMS API.
 * 
 * @type {String}
 */
var apiToken = 'Bearer ' + window.Noox.JWTToken;

/**
 * Automatically instantiate DataTables object in the target element.
 * 
 * @param {String}   target
 * @param {String}   url                  Includes /cms/api/ prefix.
 * @param {Object}   additionalOptions
 * @return {Object}
 */
function dtAttacher(target, url, additionalOptions) {
    if (typeof target !== 'undefined' && typeof url !== 'undefined') {
        var options = {
            responsive: true,
            // processing: true,
            serverSide: true,
            ajax: {
                url : window.location.origin + '/cms/api/' + url,
                type: 'GET',
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", apiToken);
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

$(document).ready(function() {
    /**
     * All users table.
     */
    dtAttacher('#noox-users', 'users', 
        {columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'email' },
                { data: 'created_at' },
                { data: 'updated_at' },
                { data: 'action', sortable: false, searchable: false }
            ]
        }
    );

    /**
     * Reported users table.
     */
    dtAttacher('#noox-users-reported', 'users/reported', 
        {
        columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'reports_count', searchable: false },
                    { data: 'email' },
                    { data: 'action', sortable: false, searchable: false }
                ],
        order: [[ 2, 'desc' ], [ 1, 'asc' ]]
        }
    );

    /**
     * Users ranking table.
     */
    dtAttacher('#noox-users-ranking', 'users/ranking', 
        {
        columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'level' },
                    { data: 'xp', searchable: false },
                    { data: 'email' },
                    { data: 'action', sortable: false, searchable: false }
                ],
        order: [[ 3, 'desc' ], [ 2, 'desc' ]]
        }
    );
} );