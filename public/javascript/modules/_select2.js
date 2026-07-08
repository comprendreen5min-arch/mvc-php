(function($) {
    'use strict';
    
    function initSelect2() {
        $('.select2').select2();
    }
    
    window.AppModules = window.AppModules || {};
    window.AppModules.select2 = {
        init: initSelect2
    };
    
})(jQuery);