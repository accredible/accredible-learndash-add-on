accredibleAjax = {};

jQuery(function(){
    accredibleAjax.loadIssuerInfo = function() {
        var post_data = {
            'action': 'accredible_learndash_ajax_load_issuer_html'
        };
        return jQuery.post(accredibledata.ajaxurl, post_data).then();
    };
});
