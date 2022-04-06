jQuery( function(){
    const dialog = jQuery('<div></div>').dialog({
        appendTo: '#wpcontent',
        modal: true,
        draggable: false,
        autoOpen: false,
        minWidth: 400,
        classes: {
            'ui-dialog': 'accredible-dialog'
        },
        buttons: [
            {
                text: "Cancel",
                class: 'accredible-button-flat-natural accredible-button-large',
                click: function() {
                    jQuery(this).dialog("close");
                }
            }
        ]
    });

    jQuery('[data-accredible-dialog]').on('click', function(event){
        const element = this;
        if (jQuery(element).data('accredibleDialog')) {
            event.preventDefault();

            // Set dialog options.
            const options = dialog.dialog('option');
            dialog.html('<p>Are you sure you want to delete this auto issuance?</p>');
            options.title = 'Delete auto issuance';
            options.buttons[1] = {
                text: "Delete",
                class: 'button accredible-button-primary accredible-button-large',
                click: function() {
                    jQuery(this).dialog("close");
                }
            };
            
            if (element.tagName === 'A') {
                const href = element.href;
                options.buttons[1].click = function() {
                    window.location.href = href;
                }
            }

            dialog.dialog('option', options);
            dialog.dialog('open');
        }
    });
});