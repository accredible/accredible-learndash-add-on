jQuery( function(){
    if (jQuery('#accredible_learndash_dialog').length ) {
        jQuery('#accredible_learndash_dialog').dialog({
            modal: true,
            draggable: false,
            minWidth: 400,
            classes: {
                'ui-dialog': 'accredible-dialog'
            },
            buttons: [
                {
                    text: "Cancel",
                    class: 'accredible-button-flat-natural accredible-button-small',
                    click: function() {
                        jQuery(this).dialog("close");
                    }
                },
                {
                    text: "Delete",
                    class: 'button accredible-button-primary accredible-button-small',
                    click: function() {
                        jQuery(this).dialog("close");
                    }
                }
            ]
        });
    }
});