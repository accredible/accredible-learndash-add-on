const accredibleToast = {};

jQuery(function(){
    function _escapeHTML(html) {
        var escape = document.createElement('textarea');
        escape.textContent = html;
        return escape.innerHTML;
    }

    function _open(type, message, duration) {
        const toastHTML = `
        <div class="accredible-toast-message">
            <div class="alert-icon toast-${type}"></div>
            <p>${_escapeHTML(message)}</p>
        </div>`;

        const dialogRef = jQuery(toastHTML).dialog({
            draggable: false,
            minWidth: 400,
            minHeight: 48,
            autoOpen: false,
            classes: {
                'ui-dialog': 'accredible-toast'
            },
            position: { my: 'bottom', at: 'center bottom', of: '.accredible-learndash-admin' },
            buttons: [
                {
                    class: 'accredible-toast-close',
                    click: function() {
                        jQuery(this).dialog("close");
                    }
                }
            ],
        });

        if (duration && !isNaN(Number(duration))) {
            dialogRef.dialog('option', 'open', function(event, ui) {
                const toastRef = jQuery(this); 
                setTimeout(function(){
                    toastRef.dialog('close');
                }, duration);
            });
        }

        dialogRef.dialog('open');
    };

    accredibleToast.info = function(message, duration) {
        _open('info', message, duration);
    }

    accredibleToast.success = function(message, duration) {
        _open('success', message, duration);
    }

    accredibleToast.error = function(message, duration) {
        _open('error', message, duration);
    }
});