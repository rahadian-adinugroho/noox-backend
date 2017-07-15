
/**
 * This token is used to authenticate ajax request to CMS API.
 * 
 * @type {Dom}
 */
let jwt = document.head.querySelector('meta[name="jwt"]');

/**
 * Automatically instantiate DataTables object in the target element.
 * 
 * @param {String}   target
 * @param {String}   url                  Includes /cms/api/ prefix.
 * @param {Object}   additionalOptions
 * @return {Object}
 */
function dtAttacher(target, url, additionalOptions) {
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

function ucFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function getLabelType(status) {
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
                    { data: 'experience', searchable: false },
                    { data: 'email' },
                    { data: 'action', sortable: false, searchable: false }
                ],
        order: [[ 3, 'desc' ], [ 2, 'desc' ]]
        }
    );

    /**
     * All news table.
     */
    dtAttacher('#noox-news-list', 'news', 
        {columns: [
                { data: 'id' },
                { data: 'title' },
                { data: 'pubtime' },
                { data: 'author' },
                { data: 'source.source_name' },
                { data: 'action', sortable: false, searchable: false }
            ]
        }
    );

    /**
     * Reported news table.
     */
    dtAttacher('#noox-news-reported', 'news/reported', 
        {columns: [
                { data: 'id' },
                { data: 'title' },
                { data: 'reports_count', searchable: false },
                { data: 'pubtime' },
                { data: 'source.source_name' },
                { data: 'action', sortable: false, searchable: false }
            ]
        }
    );

    /**
     * Deleted news table.
     */
    dtAttacher('#noox-news-deleted', 'news/deleted', 
        {columns: [
                { data: 'id' },
                { data: 'title' },
                { data: 'deleted_at' },
                { data: 'pubtime' },
                { data: 'author' },
                { data: 'source.source_name' },
                { data: 'action', sortable: false, searchable: false }
            ]
        }
    );

    /**
     * Reported news table.
     */
    dtAttacher('#noox-comment-reported', 'news/comments/reports', 
        {columns: [
                { data: 'id' },
                { data: 'author.name' },
                { data: 'reports_count', searchable: false },
                { data: 'news.title' },
                { data: 'content' },
                { data: 'created_at' },
                { data: 'action', sortable: false, searchable: false }
            ]
        }
    );

    /**
     * Deleted news table.
     */
    dtAttacher('#noox-reports', 'reports', 
        {columns: [
                { data: 'id' },
                { data: 'reportable_type' },
                { data: 'status.name' },
                { data: 'created_at' },
                { data: 'reporter.name' },
                { data: 'content' },
                { data: 'action', sortable: false, searchable: false }
            ],
        columnDefs: [ 
                {
                    targets: 1,
                    render: function ( data, type, full, meta ) {
                      return ucFirstLetter(data);
                    }
                },
                {
                    targets: 2,
                    render: function ( data, type, full, meta ) {
                      return '<span class="label label-'+ getLabelType(data) +'">'+ ucFirstLetter(data) +'</span>';
                    }
                }
            ]
        }
    );

    /**
     * All admins table.
     */
    dtAttacher('#noox-admins', 'admins', 
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
} );