/**
 * DataTable core and DataTable responsive plugin.
 */

try {
    var dataTable   = require('datatables.net-bs');
    var responsive  = require('datatables.net-responsive-bs');
    
    $.fn.DataTable  = dataTable;
    $.fn.responsive = responsive;
} catch(e) {}
