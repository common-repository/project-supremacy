var plugins;

(function( $ ) {
    'use strict';

    $(document).ready(function(){
        a.titleSelector();

        a.saveGeneralSettings();
        a.savePostTypes();
        a.saveTaxonomies();
        a.saveMiscellaneous();

        a.selectImages();

        a.openShortcodes();
        a.htmlStripslashes();

        a.fb_preview.init();
        a.fb_preview.change_event();
        a.tw_preview.init();
        a.tw_preview.change_event();

        a.loadRedirects();
        a.addNewRedirect();
        a.editNewRedirect();
        a.deleteRedirect();
    });

    var a = {
        fb_preview: {
            init : function () {
                var fb_title = $('input[name="ps_seo_title_fb"]').val();
                fb_title = a.htmlStripslashes(fb_title);
                var fb_desc = $('textarea[name="ps_seo_description_fb"]').val();
                fb_desc = a.htmlStripslashes(fb_desc);
                var fb_img = $('input[name="ps_seo_image_fb"]').val();
                $('.fb_post_preview_img a img').attr('src', fb_img);
                $('.fb_post_preview_img a').attr('href', home_url);
                $('.fb_post_preview_body .fb_post_preview_title').html(fb_title);
                $('.fb_post_preview_body .fb_post_preview_desc').html(fb_desc);
                $('.fb_post_preview_body .fb_post_preview_host').html(home_url.replace(/^https?\:\/\//i, ""));
            },
            change_event : function () {
                $(document).on('change keyup', 'input[name="ps_seo_title_fb"]', function () {
                    var title = $(this).val();
                    title = a.htmlStripslashes(title);
                    $('.fb_post_preview_body .fb_post_preview_title').html(title);
                });
                $(document).on('change keyup', 'textarea[name="ps_seo_description_fb"]', function () {
                    var desc = $(this).val();
                    desc = a.htmlStripslashes(desc);
                    $('.fb_post_preview_body .fb_post_preview_desc').html(desc);
                });
                $(document).on('change keyup', 'input[name="ps_seo_image_fb"]', function () {
                    var url = $(this).val();
                    $('.fb_post_preview_img a img').attr('src', url);
                });
            }
        },
        tw_preview: {
            init : function () {
                var tw_title = $('input[name="ps_seo_title_tw"]').val();
                tw_title = a.htmlStripslashes(tw_title);
                var tw_desc = $('textarea[name="ps_seo_description_tw"]').val();
                tw_desc = a.htmlStripslashes(tw_desc);
                var tw_img = $('input[name="ps_seo_image_tw"]').val();
                $('.tw_post_preview_img a img').attr('src', tw_img);
                $('.tw_post_preview_img a').attr('href', home_url);
                $('.tw_post_preview_body .tw_post_preview_title').html(tw_title);
                $('.tw_post_preview_body .tw_post_preview_desc').html(tw_desc);
                $('.tw_post_preview_body .tw_post_preview_host').html(home_url.replace(/^https?\:\/\//i, ""));
            },
            change_event : function () {
                $(document).on('change keyup', 'input[name="ps_seo_title_tw"]', function () {
                    var title = $(this).val();
                    title = a.htmlStripslashes(title);
                    $('.tw_post_preview_body .tw_post_preview_title').html(title);
                });
                $(document).on('change keyup', 'textarea[name="ps_seo_description_tw"]', function () {
                    var desc = $(this).val();
                    desc = a.htmlStripslashes(desc);
                    $('.tw_post_preview_body .tw_post_preview_desc').html(desc);
                });
                $(document).on('change keyup', 'input[name="ps_seo_image_tw"]', function () {
                    var url = $(this).val();
                    $('.tw_post_preview_img a img').attr('src', url);
                });
            }
        },
        htmlStripslashes: function(str=''){
            str = str.replace(/\</g, '\&lt;');
            str = str.replace(/\>/g, '\&gt;');
            return str;
        },
        openShortcodes: function(){
            $(document).on('click', '.uk-button-open-shortcodes', function(e){
                e.preventDefault();
                UIkit.modal("#shortcodes").show();
            });
        },

        titleSelector: function(){
            var separator = $('#separator');
            var value = separator.data('value');
            var inputs = separator.find('input');
            var labels = separator.find('label');
            labels.click(function(){
                labels.removeClass('checked');
                $(this).addClass('checked');
            });
            if (value != '') {
                inputs.each(function(){
                    if ($(this).val() == value) {
                        $(this).attr('checked', 'checked');
                        $(this).next().addClass('checked');
                    }
                });
            }
        },

        saveGeneralSettings: function() {
            $('.save-general').submit(function(e){
                e.preventDefault();
                var data = $(this).serialize();
                $.post(prs_data.wp_post, data, function(d){
                    if(d.status === 'error') {
                        UIkit.notify("<i class='uk-icon-close'></i> "+d.message+"", {pos:'bottom-right', status:"danger"});
                    } else {
                        UIkit.notify("<i class='uk-icon-check'></i> "+d.message+"", {pos:'bottom-right', status:"success"});
                    }
                });
            });
        },

        savePostTypes: function() {
            $('.save-posttypes').submit(function(e){
                e.preventDefault();
                var data = $(this).serialize();
                $.post(prs_data.wp_post, data, function(d){
                    if(d.status === 'error') {
                        UIkit.notify("<i class='uk-icon-close'></i> "+d.message+"", {pos:'bottom-right', status:"danger"});
                    } else {
                        UIkit.notify("<i class='uk-icon-check'></i> "+d.message+"", {pos:'bottom-right', status:"success"});
                    }
                });
            });
        },

        saveTaxonomies: function() {
            $('.save-taxonomies').submit(function(e){
                e.preventDefault();
                var data = $(this).serialize();
                $.post(prs_data.wp_post, data, function(d){
                    if(d.status === 'error') {
                        UIkit.notify("<i class='uk-icon-close'></i> "+d.message+"", {pos:'bottom-right', status:"danger"});
                    } else {
                        UIkit.notify("<i class='uk-icon-check'></i> "+d.message+"", {pos:'bottom-right', status:"success"});
                    }
                });
            });
        },

        saveMiscellaneous: function() {
            $('.save-miscellaneous').submit(function(e){
                e.preventDefault();
                var data = $(this).serialize();
                $.post(prs_data.wp_post, data, function(d){
                    if(d.status === 'error') {
                        UIkit.notify("<i class='uk-icon-close'></i> "+d.message+"", {pos:'bottom-right', status:"danger"});
                    } else {
                        UIkit.notify("<i class='uk-icon-check'></i> "+d.message+"", {pos:'bottom-right', status:"success"});
                    }
                });
            });
        },

        selectImages: function(){
            $('.imageSelect').click(function(){
                var target = $(this).data('target');
                tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
                window.send_to_editor = function(html) {
                    var img = $(html).attr('src');
                    $('#' + target).val(img);
                    tb_remove();
                }
            });

        },

        loadRedirects: function(){
            var messages = {
                empty: '<tr><td colspan="4">Can\'t find any active redirects.</td></tr>',
                loading: '<tr><td colspan="4"><i class="fa fa-refresh fa-spin"></i> Loading ...</td></tr>'
            };
            var table = $('.table-redirects');
            var tbody = table.find('tbody');

            tbody.empty().append(messages.loading);

            $.post(prs_data.wp_post, 'action=prs_get_redirects', function(d){
                if (d.status == 'success') {

                    if (d.data.length == 0) {
                        tbody.empty().append(messages.empty);
                    } else {
                        tbody.empty();

                        for (var i = 0; i < d.data.length; i++) {
                            var data = d.data[i];

                            if (!data.new.match("^http")) {
                                data.new = '/' + data.new;
                            }

                            var html = '<tr>' +
                                '<td><input type="checkbox" data-id="'+data.id+'" class="remove-selected-ids"></td>' +
                                '<td><a target="_blank" href="/'+data.old+'">/'+data.old+'</a></td>' +
                                '<td><a target="_blank" href="'+data.new+'">'+data.new+'</a></td>' +
                                '<td>'+data.date_created+'</td>' +
                                '<td>' +
                                '<button type="button" class="uk-button uk-button-mini uk-button-danger delete-redirect" data-id="'+data.id+'" title="Delete this redirect"><i class="fa fa-trash-o"></i></button>' +
                                '<button type="button" class="uk-button uk-button-mini uk-button-primary edit-redirect" style="margin-left: 4px;" data-id="'+data.id+'" data-url="'+data.new+'" title="Edit this redirect"><i class="fa fa-edit"></i></button>' +
                                '</td>' +
                                '</tr>';
                            tbody.append(html);
                        }

                    }

                } else {
                    UIkit.notify('An unknown error has occurred.', {pos:'bottom-right', status: 'danger'});
                }
            });

        },
        deleteRedirect: function(){
            $(document).on('click', '.delete-redirect', function(e){
                e.preventDefault();
                var button = $(this);
                var id = $(this).data('id');
                UIkit.modal.confirm("Are you sure that you want to delete this redirect?", function(){
                    button.disable();
                    $.post(prs_data.wp_post, 'action=prs_delete_redirect&id='+id, function(d){
                        button.disable();
                        a.loadRedirects();

                    });
                });
            });

            $(document).on('click', '.remove-selected-redirects', function(e){
                e.preventDefault();
                var button = $(this);

                var ids = [];

                $('.remove-selected-ids').each(function () {
                    if ( this.checked ) {
                        ids.push($(this).data('id'));
                    }
                });

                UIkit.modal.confirm("Are you sure that you want to delete this redirect?", function(){
                    button.disable();
                    $.post(prs_data.wp_post, 'action=prs_delete_redirect&id='+ids, function(d){
                        button.disable();
                        a.loadRedirects();

                    });
                });
            });

            $(document).on('click', '.remove-all-redirects', function (e) {
                e.preventDefault();
                var button = $(this);
                UIkit.modal.confirm("Are you sure that you want to delete all redirects?", function(){
                    button.disable();
                    $.post(prs_data.wp_post, 'action=prs_delete_all_redirects', function(d){
                        button.disable();
                        a.loadRedirects();

                    });
                });
            })
        },
        addNewRedirect: function(){
            $(document).on('click', '.add-new-redirect', function(e){
                e.preventDefault();

                var button = $(this);

                UIkit.modal.prompt("Old URL (use the /oldurl/ format):", '', function(oldURL){

                    UIkit.modal.prompt("Redirect to URL (use the /newurl/ format) (DANGER: Creating invalid redirects may result in breaking of your website):", '', function(newURL){

                        button.disable('Saving...');

                        $.post(prs_data.wp_post, 'action=prs_add_redirect&oldURL='+oldURL+'&newURL='+newURL, function(d){

                            button.disable();

                            a.loadRedirects();

                        });

                    });

                });

            });
        },
        editNewRedirect: function(){
            $(document).on('click', '.edit-redirect', function(e){
                e.preventDefault();

                var button = $(this);
                var url = button.data('url');
                var redirect_id = button.data('id');

                UIkit.modal.prompt("Editing URL: " + url, url, function(newURL){
                    button.disable('Saving...');

                    $.post(prs_data.wp_post, 'action=prs_edit_redirect&id='+redirect_id+'&newURL='+newURL, function(d){
                        button.disable();
                        a.loadRedirects();
                    });

                });

            });
        }
    };

})( jQuery );
