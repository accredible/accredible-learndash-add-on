const accredibleSidenav = {};

jQuery(function(){
    accredibleSidenav.open = function(html, title) {
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
            classes: {
                'ui-dialog': 'accredible-dialog accredible-sidenav'
            },
            buttons: [
                {
                    text: "Cancel",
                    class: 'accredible-button-flat-natural accredible-button-large',
                    click: function() {
                        jQuery(this).dialog("close");
                    }
                }
            ],
            show: {
                effect: 'blind', // TODO(MartinN) - should slide right.
                // direction: 'right',
                duration: 800
            },
        });

        if (title) {
            sidenavRef.dialog('option', 'title', title);
        }

        sidenavRef.dialog('open');
    };
});
