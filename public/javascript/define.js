// Global Configuration Object
const jsConfig = {
    URLROOT: window.URLROOT || (window.location.origin + '/' + (window.location.pathname.split('/')[1] || 'ecommerce_app')),
    APPNAME: "MVC PHP",
    
    // Upload Paths
    UPLOADS : "public/uploads",

    
    // JavaScript Module Paths
    MODULES: "public/javascript/modules",
    DATATABLES: "public/javascript/modules/datatables",
    
    // UI Elements
    SPIN: '<i class="fa fa-spinner fa-spin"></i>',
    SPIN_DANGER: '<i class="fa fa-spinner fa-spin text-danger"></i>',
    SPIN_SUCCESS: '<i class="fa fa-spinner fa-spin text-success"></i>',
    SPIN_PRIMARY: '<i class="fa fa-spinner fa-spin text-primary"></i>',
    
    // Helper function to build module path
    getModulePath: function(filename) {
        return this.URLROOT + '/' + this.MODULES + '/' + filename;
    },
    
    // Helper function to build DataTable module path
    getDataTablePath: function(filename) {
        return this.URLROOT + '/' + this.DATATABLES + '/' + filename;
    }
};

// Confirmation Dialog
function confirmSubmit() {
    return confirm('Are you sure you want to delete this record?');
}

// Export to window
window.jsConfig = jsConfig;
window.confirmSubmit = confirmSubmit;