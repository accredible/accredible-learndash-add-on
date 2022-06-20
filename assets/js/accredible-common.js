accredibleAjax = {};

jQuery(function(){
    accredibleAjax.loadIssuerInfo = function() {
        var post_data = {
            action: 'accredible_learndash_ajax_load_issuer_html'
        };
        return jQuery.post(accredibledata.ajaxurl, post_data).then();
    };

    accredibleAjax.doAutoIssuanceAction = function(formData) {
        var post_data = {
            action: 'accredible_learndash_ajax_handle_auto_issuance_action',
        };
        post_data = Object.assign(post_data, formData);
        return jQuery.post(accredibledata.ajaxurl, post_data).then(function(res){
            try {
                return typeof res === 'object' ? res : JSON.parse(res);
            } catch (error) {
                // handle wp_die messsages
                const response = { success: false, data: res };
                if(typeof res === 'string' && res.match(/error/i) !== null) {
                    response.data = 'Failed to perform requested action. Please try again later.';
                }
                return response;
           }
        });
    };

    accredibleAjax.loadAutoIssuanceListInfo = function(currentPage) {
        var post_data = {
            action: 'accredible_learndash_ajax_load_auto_issuance_list_html',
            page_num: currentPage
        };
        return jQuery.post(accredibledata.ajaxurl, post_data).then();
    }
});
