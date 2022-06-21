const accredibleSidenav = {};

jQuery(function(){
    accredibleSidenav.open = function(html, options) {
        if (!html) {
            html = '<div></div';
        }

        html = `<div>${html}</div>`;

        const sidenavRef = jQuery(html).dialog({
            minWidth: '500',
            height: jQuery('#wpwrap').innerHeight(),
            position: { 
                my: 'top', 
                at: 'right top', 
                of: '#wpcontent' 
            },
            draggable: false,
            resizable: false,
            modal: true,
            show: true,
            classes: {
                'ui-dialog': 'accredible-dialog accredible-sidenav'
            },
            close: function(event, ui) {
                jQuery(this).remove();
            },
        });

        accredibleSidenav.close = function() {
            sidenavRef.dialog('close');
        }

        // Set dialog options.
        const sidenavOptions = sidenavRef.dialog('option');
        sidenavOptions.title = options.title;
        
        if (options.showCancelAction) {
            sidenavOptions.buttons = [
                {
                    text: "Cancel",
                    class: 'accredible-button-flat-natural accredible-button-large',
                    click: function() {
                        jQuery(this).dialog("close");
                    }
                }
            ]
        }

        sidenavRef.dialog('option', sidenavOptions);
        sidenavRef.dialog('open');
    };
});
