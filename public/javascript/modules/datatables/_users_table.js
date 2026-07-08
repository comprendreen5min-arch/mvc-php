(function($) {
    'use strict';
    


    function initUsersDataTable() {
        
        let table = $('#all-users').DataTable({
        "processing": true,
        "serverSide": true,
         "pageLength": 10,
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
        "order": [[5, 'asc']],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>',
            "emptyTable": "No users found",
            "zeroRecords": "No matching users found"
        },
        "ajax": {
            "url": jsConfig.URLROOT + "/users/all_users",
            "type": "POST",
            "error": function(xhr, error, thrown) {
                console.error('DataTables error:', error, thrown);
                alert('Error loading data. Please refresh the page.');
            }
        },
        "columns": [
            { "data": "first_name" },
            { "data": "last_name" },
            { "data": "email" },
            { "data": "phone" },
            { 
                "data": "user_role",
                "render": function(data, type, row) {
                    let roles = {
                        1: '<span class="badge bg-primary px-2 py-1">Admin</span>',
                        2: '<span class="badge bg-lavender px-2 py-1">Manager</span>',
                        3: '<span class="badge bg-peach px-2 py-1">Partner</span>',
                        4: '<span class="badge bg-lightgreen px-2 py-1">Transporter</span>',
                        5: '<span class="badge bg-mint px-2 py-1">Driver</span>',
                        6: '<span class="badge bg-sky px-2 py-1">Pilot</span>',
                        7: '<span class="badge bg-softgray px-2 py-1">Accounter</span>',
                        8: '<span class="badge bg-darktext px-2 py-1">Greeter</span>'
                    };
                    return roles[data] || '<span class="badge bg-secondary">Unknown</span>';
                },
                "className": "text-center"
            },
            { 
                "data": "user_status",
                "render": function(data, type, row) {
                    return data == 1 
                        ? '<label class="badge bg-success py-1 px-2">Active</label>' 
                        : '<label class="badge bg-danger py-1 px-2">Inactive</label>';
                },
                "className": "text-center"
            },
            {
                "data": "id",
                "orderable": false,
                "searchable": false,
                "render": function(data, type, row) {
                    if(data == 1){
                        return '<span class="badge bg-pink px-2 py-1"><i class="fa fa-ban"></i>  Super Admin</span>';
                    }
                    return `
                        <div class="me-2" role="group">
                            <a href="${jsConfig.URLROOT}/users/edit/${data}" 
                               class="btn btn-sm btn-primary" 
                               title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="${jsConfig.URLROOT}/users/password/${data}" 
                               class="btn btn-sm btn-warning" 
                               title="Change Password">
                                <i class="fa fa-key"></i>
                            </a>
                            <form method="POST" 
                                  action="${jsConfig.URLROOT}/users/user_status/${data}" 
                                  style="display:inline;"
                                  onsubmit="return confirm('Toggle user status?');">
                                <button type="submit" class="btn btn-sm btn-info" title="Toggle Status">
                                    <i class="fa fa-toggle-on"></i>
                                </button>
                            </form>
                            <form method="POST" 
                                  action="${jsConfig.URLROOT}/users/delete/${data}" 
                                  style="display:inline;"
                                  onsubmit="return confirm('Are you sure you want to delete this user?');">
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    `;
                },
                "className": "text-center"
            }
        ]
       
    });
    }
    
    window.DataTableModules = window.DataTableModules || {};
    window.DataTableModules.users = {
        // init: initUsersDataTable,
        init: function() {
            initUsersDataTable();
        }
    };
    
})(jQuery);