 
(function() {

    tinymce.create('tinymce.plugins.wpdm_tinyplugin', {

        init : function(ed, url){            
          
            ed.addCommand('mcedonwloadmanager', function() {
                                ed.windowManager.open({
                                        title: 'Download Manager',
                                        file : 'admin.php?wpdm_action=wpdm_tinymce_button',
                                        height: 600,
                                        width:560,
                                        inline : 1
                                }, {
                                        plugin_url : url, // Plugin absolute URL
                                        some_custom_arg : 'custom arg' // Custom argument
                                });
                        });
            
            ed.addButton('wpdm_tinyplugin', {
                title : 'Download Manager',
                cmd : 'mcedonwloadmanager',
                icon: ' dashicons-before dashicons-download wpdm-mce-ico'
                //image:  url + "/img/donwloadmanager.png"
            });
            
            
        },

        getInfo : function() {
            return {
                longname : 'WPDM - TinyMCE Button Add-on',
                author : 'Shaon',
                authorurl : 'http://www.wpdownloadmanager.com',
                infourl : 'http://www.wpdownloadmanager.com',
                version : "1.0"
            };
        }

    });

    tinymce.PluginManager.add('wpdm_tinyplugin', tinymce.plugins.wpdm_tinyplugin);
    
})();
