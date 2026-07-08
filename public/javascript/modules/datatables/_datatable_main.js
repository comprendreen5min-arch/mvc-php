(function($) {
    'use strict';
    
    function loadScript(src) {
        return new Promise(function(resolve, reject) {
            const script = document.createElement('script');
            script.src = src;
            script.onload = resolve;
            script.onerror = function() {
                console.error('Failed to load DataTable module:', src);
                reject(new Error('Script load failed: ' + src));
            };
            document.head.appendChild(script);
        });
    }
    
    function initAllDataTables() {
        // Use jsConfig to build paths
        const dtPath = jsConfig.URLROOT + '/' + jsConfig.DATATABLES;
        // Load all DataTable sub-modules
        Promise.all([
            loadScript(dtPath + '/_users_table.js'),
           
        ]).then(function() {
            // Initialize all DataTable modules
            $(document).ready(function() {
                if (window.DataTableModules) {
                    if (DataTableModules.users) DataTableModules.users.init();
                    
                    
                    console.log('All DataTables initialized successfully');
                }
            });
        }).catch(function(error) {
            console.error('DataTable modules loading failed:', error);
        });
    }
    
    window.AppModules = window.AppModules || {};
    window.AppModules.datatables = {
        init: initAllDataTables
    };
    
})(jQuery);