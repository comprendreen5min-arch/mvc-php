(function($) {
    'use strict';
    
    // Load all module scripts
    function loadScript(src) {
        return new Promise(function(resolve, reject) {
            const script = document.createElement('script');
            script.src = src;
            script.onload = resolve;
            script.onerror = function() {
                console.error('Failed to load:', src);
                reject(new Error('Script load failed: ' + src));
            };
            document.head.appendChild(script);
        });
    }
    // Use jsConfig to build paths
    const basePath = jsConfig.URLROOT + '/' + jsConfig.MODULES;

    // Load all modules
    Promise.all([
        loadScript(basePath + '/_select2.js'),
        loadScript(basePath + '/_password.js'),
        loadScript(basePath + '/_users.js'),
        loadScript(basePath + '/datatables/_datatable_main.js'),

    ]).then(function() {
        // All scripts loaded, now initialize on document ready
        $(document).ready(function() {
            if (window.AppModules) {
                // Initialize each module
                if (AppModules.select2) AppModules.select2.init();
                // Password
                if (AppModules.password) AppModules.password.init();
                // Users
                if (AppModules.users) AppModules.users.init();
                // DataTables
                if (AppModules.datatables) AppModules.datatables.init();
                
                console.log('All modules initialized successfully');
            }
        });
    }).catch(function(error) {
        console.error('Module loading failed:', error);
        alert('Failed to load application modules. Please refresh the page.');
    });
    
})(jQuery);

