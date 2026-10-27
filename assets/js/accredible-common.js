accredibleAjax = {};

jQuery(function(){
    const SESSION_EXPIRED_MESSAGE = 'Your session has expired. Please reload the page.';

    function post(post_data) {
        return jQuery.post(accredibledata.ajaxurl, post_data).fail(function(xhr) {
            if (xhr.status === 403 && typeof accredibleToast !== 'undefined') {
                accredibleToast.error(SESSION_EXPIRED_MESSAGE, 5000);
            }
        });
    }

    accredibleAjax.loadIssuerInfo = function() {
        var post_data = {
            action: 'accredible_learndash_ajax_load_issuer_html',
            nonce: accredibledata.nonce
        };
        return post(post_data);
    };

    accredibleAjax.loadIssuanceSidenav = function(data) {
        const post_data = {
            action: 'accredible_learndash_ajax_load_issuance_form_html',
            nonce: accredibledata.nonce
        };
        if (data) {
            Object.assign(post_data, data);
        }
        return post(post_data);
    };

    accredibleAjax.doAutoIssuanceAction = function(formData) {
        var post_data = {
            action: 'accredible_learndash_ajax_handle_auto_issuance_action',
            nonce: accredibledata.nonce
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
        }, function(xhr){
            // Callers toast res.data on failure, so return the same shape instead of the jqXHR.
            return {
                success: false,
                data: xhr.status === 403
                    ? SESSION_EXPIRED_MESSAGE
                    : 'Failed to perform requested action. Please try again later.'
            };
        });
    };

    accredibleAjax.loadAutoIssuanceListInfo = function(currentPage) {
        var post_data = {
            action: 'accredible_learndash_ajax_load_auto_issuance_list_html',
            nonce: accredibledata.nonce,
            page_num: currentPage
        };
        return post(post_data);
    }

    accredibleAjax.getGroup = function(groupId) {
        var post_data = {
            action: 'accredible_learndash_ajax_get_group',
            nonce: accredibledata.nonce,
            group_id: groupId
        };
        return post(post_data);
    }

    accredibleAjax.getLessons = function(courseId) {
        var post_data = {
            action: 'accredible_learndash_ajax_get_lessons',
            nonce: accredibledata.nonce,
            course_id: courseId
        };
        return post(post_data);
    }
});
