var modal, actions, geocoder, renderedMap;
String.prototype.containsText = function(it) { return this.indexOf(it) != -1; };
(function( $ ) {
    'use strict';

    /**
     *  Global doc.ready function
     */
    $(document).ready(function(){

        // Init CA
        prs_ca.init();

        // Init TA
        prs_ta.init();

        // Check if permitted to run
        if (!actions.allowedToRun()) {
            return;
        }

        // Render slider parents
        actions.renderSliders();

        // Init tabs
        actions.tabInit();

        // Editor to hidden inputs
        actions.editorInit();

        // Facebook & Twitter image select
        actions.selectImages();

        // Init Chosen
        actions.renderSelect();

        // Render Meta Robots Preview
        actions.renderMetaRobots();

        actions.fb_preview.init();
        actions.fb_preview.change_event();
        actions.tw_preview.init();
        actions.tw_preview.change_event();

        actions.saveEXIF();

    });

    actions = {
        allowedToRun: function(){
            return $('#prs_seo').length;
        },
        saveEXIF: function () {
            $(document).on('click', '.ps_exif_save_button', function () {
                var btn = $(this);
                btn.parents('.compat-attachment-fields').find('textarea').change();
                btn.text('Saving...');
                btn.attr('disabled', true);
                setInterval(function () {
                    btn.text('Save');
                    btn.attr('disabled', false);
                },2000);
            });
        },
        fb_preview: {
            init : function () {
                var fb_title = $('#seo_fb_title').val();
                var fb_desc = $('#seo_fb_description').val();
                var fb_img = $('#seo_fb_image').val();
                $('.fb_post_preview_img a img').attr('src', fb_img);
                $('.fb_post_preview_img a').attr('href', window.location.protocol + "//" + window.location.hostname);
                $('.fb_post_preview_body .fb_post_preview_title').html(fb_title);
                $('.fb_post_preview_body .fb_post_preview_desc').html(fb_desc);
            },
            change_event : function () {
                $(document).on('change keyup', '#seo_fb_title', function () {
                    var title = $(this).val();
                    $('.fb_post_preview_body .fb_post_preview_title').html(title);
                });
                $(document).on('change keyup', '#seo_fb_description', function () {
                    var desc = $(this).val();
                    $('.fb_post_preview_body .fb_post_preview_desc').html(desc);
                });
                $(document).on('change keyup', '#seo_fb_image', function () {
                    var url = $(this).val();
                    $('.fb_post_preview_img a img').attr('src', url);
                });
            }
        },
        tw_preview: {
            init : function () {
                var tw_title = $('#seo_tw_title').val();
                var tw_desc = $('#seo_tw_description').val();
                var tw_img = $('#seo_tw_image').val();
                $('.tw_post_preview_img a img').attr('src', tw_img);
                 $('.tw_post_preview_img a').attr('href', window.location.protocol + "//" + window.location.hostname);
                $('.tw_post_preview_body .tw_post_preview_title').html(tw_title);
                $('.tw_post_preview_body .tw_post_preview_desc').html(tw_desc);
            },
            change_event : function () {
                $(document).on('change keyup', '#seo_tw_title', function () {
                    var title = $(this).val();
                    $('.tw_post_preview_body .tw_post_preview_title').html(title);
                });
                $(document).on('change keyup', '#seo_tw_description', function () {
                    var desc = $(this).val();
                    $('.tw_post_preview_body .tw_post_preview_desc').html(desc);
                });
                $(document).on('change keyup', '#seo_tw_image', function () {
                    var url = $(this).val();
                    $('.tw_post_preview_img a img').attr('src', url);
                });
            }
        },
        detectEditPage: function() {
            if ($('#soliloquy-header').length > 0) {
                return false;
            }
            if ($('[name="post_title"]').length == 0 && $('#post-title-0').length == 0) {
                return false;
            } else {
                return true;
            }
        },
        detectTermPage: function() {
            if ($('[name="meta[taxonomy]"]').length == 0) {
                return false;
            } else {
                return true;
            }
        },
        renderMetaRobots: function(){
            actions.metaRobotsPreview();
            $('.seo_robots_enabled').click(function(){
                setTimeout(function(){
                    actions.metaRobotsPreview();
                }, 500);
            });
            $('#seo_robots_index').change(function(){
                actions.metaRobotsPreview();
            });
            $('#seo_robots_follow').change(function(){
                actions.metaRobotsPreview();
            });
            $('#seo_robots_advanced').change(function(){
                actions.metaRobotsPreview();
            });
        },
        metaRobotsPreview: function(){
            var robots = $('#seo_robots_advanced').val();
            if (robots == null) {
                robots = [];
            }
            robots.push($('#seo_robots_index').val());
            robots.push($('#seo_robots_follow').val());

            if ($('#seo_robots_enabled').val() == 1) {
                $('.meta-robots-preview').html('&lt;meta name="robots" content="'+robots.join(',')+'"/&gt;');
            } else {
                $('.meta-robots-preview').html('--- TURN ON META ROBOTS FIRST ---');
            }
        },
        renderSelect: function(){
            var chosenElements = $('.chosen-select');
            chosenElements.each(function(){
                var selectedOptions = $(this).data('selected').split(',');
                if (selectedOptions.length < 1) return;
                for(var i = 0; i < selectedOptions.length; i++) {
                    $(this).find('option[value="'+selectedOptions[i]+'"]').attr('selected', 'selected');
                }
            });
            chosenElements.chosen();

            // Meta Robots - Index Type and Meta Robots - Follow Type are finally working!!!
            var render_select = $('.render_select');
            if ( render_select.length > 0 ) {
                render_select.each(function () {
                    var select = $(this);
                    select.val(select.attr('data-selected'));
                })
            }
        },
        /** ------------------------ **/

        renderSliders: function(){
            $('.beParent').each(function(){
                var parent = $(this).parents('.postbox');
                var clone = $(this).clone();
                clone.removeClass('beParent');
                // Fix for WordPress 4.8 - They removed class 'button-link'
                clone.insertAfter(parent.find('.handlediv'));
                $(this).remove();
            });

            // Enable sliders
            $('.prs-slider-frame .slider-button').toggle(function(){
                var attr = $(this).attr('data-element');
                $(this).addClass('on').html('ON');
                $('#' + attr).val(1);
            },function(){
                var attr = $(this).attr('data-element');
                $(this).removeClass('on').html('OFF');
                $('#' + attr).val(0);
            });
        },
        tabInit: function() {
            $(document).on('click', '.tab-button', function(){
                $('.tab-button').removeClass('activated');
                $(this).addClass('activated');

                var target = $(this).data('target');

                $('.prs-box').removeClass('activated');
                $('.' + target).addClass('activated');
            });
        },
        editorInit: function(){
            $('body').on('focus', '[contenteditable="true"]', function() {
                var $this = $(this);
                $this.data('before', $this.html());
                return $this;
            }).on('blur keyup paste input', '[contenteditable="true"]', function() {
                var $this = $(this);
                if ($this.data('before') !== $this.html()) {
                    $this.data('before', $this.html());
                    $this.trigger('change');
                }
                return $this;
            });
            $('.prs-editor').change(function(e){
                e.stopPropagation();
                var text = $(this).html();
                var id = $(this).data('target');
                text = text.replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().replace(/<\/?[^>]+(>|$)/g, "");
                $('#'+id).val(text);
            }).keydown(function(e) {
                e.stopPropagation();
            }).keyup(function(e) {
                e.stopPropagation();
            }).keypress(function(e) {
                e.stopPropagation();
            });

        },
        selectImages: function(){
            $('.imageSelect').click(function(){
                var target = $(this).data('target');
                tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
                window.send_to_editor = function(html) {
                    var img = $(html).attr('src');
                    $('#' + target).val(img).trigger('change');
                    tb_remove();
                }
            });

        }
    };

    var prs_ta = {
        init: function() {
            if (actions.detectTermPage()) {

                prs_ta.prs_ta_calculate_title_length();
                prs_ta.prs_ta_calculate_title_length_mobile();
                prs_ta.prs_ta_calculate_description_length();
                prs_ta.prs_ta_calculate_description_length_mobile();

                setTimeout(function(){prs_ta.init()}, 400);
            }
        },

        get_title: function() {
            return $('[name="meta[term_seo_title]"]').val().replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().length;
        },
        get_desc: function() {
            return $('[name="meta[term_seo_description]"]').val().replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().length;
        },

        prs_ta_calculate_title_length: function() {
            var wordCount = prs_ta.get_title();
            if (wordCount == 0) {
                wordCount = $('[name="meta[term_seo_title]"]').attr('placeholder').replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().length;
            }
            if (wordCount > 70) {
                wordCount = '<span style="color:red">' + wordCount + '</span>';
            }
            $('.count-seo-title').html(wordCount);
        },
        prs_ta_calculate_title_length_mobile: function() {
            var wordCount = prs_ta.get_title();
            if (wordCount == 0) {
                wordCount = $('[name="meta[term_seo_title]"]').attr('placeholder').replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().length;
            }
            if (wordCount > 78) {
                wordCount = '<span style="color:red">' + wordCount + '</span>';
            }
            $('.count-seo-title-mobile').html(wordCount);
        },
        prs_ta_calculate_description_length: function() {
            var wordCount = prs_ta.get_desc();
            if (wordCount == 0) {
                wordCount = $('[name="meta[term_seo_description]"]').attr('placeholder').replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().length;
            }
            if (wordCount > 300) {
                wordCount = '<span style="color:red">' + wordCount + '</span>';
            }
            $('.count-seo-description').html(wordCount);
        },
        prs_ta_calculate_description_length_mobile: function() {
            var wordCount = prs_ta.get_desc();
            if (wordCount == 0) {
                wordCount = $('[name="meta[term_seo_description]"]').attr('placeholder').replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().length;
            }
            if (wordCount > 120) {
                wordCount = '<span style="color:red">' + wordCount + '</span>';
            }
            $('.count-seo-description-mobile').html(wordCount);
        },

    };

    var prs_ca = {
        init: function() {
            if (actions.detectEditPage()) {
                prs_ca.prs_ca_calculate_content_length();
                prs_ca.prs_ca_calculate_title_length();
                prs_ca.prs_ca_calculate_title_length_mobile();
                prs_ca.prs_ca_calculate_description_length();
                prs_ca.prs_ca_calculate_description_length_mobile();

                prs_ca.prs_ca_keyword_title();
                prs_ca.prs_ca_keyword_desc();
                prs_ca.prs_ca_keyword_body();
                prs_ca.prs_ca_keyword_url();

                prs_ca.prs_ca_h1_keyword();
                prs_ca.prs_ca_h1_keyword_content();
                prs_ca.prs_ca_h2_keyword();
                prs_ca.prs_ca_h3_keyword();

                prs_ca.prs_ca_keyword_density();

                setTimeout(function(){prs_ca.init()}, 400);
            }
        },
        prs_ca_h1_keyword: function() {
            var title = ($('#title').length != 0 ? $('#title').val() : $('#post-title-0').val()).toLowerCase();
            var keyword = $('#seo_keyword').val().toLowerCase();
            if (keyword == '') {
                prs_ca.generate_li('prs_ca_h1_keyword', 'yellow', 'Your Target Keyword is not set.')
            }else if (title.contains(keyword)) {
                prs_ca.generate_li('prs_ca_h1_keyword', 'green', 'Your Target Keyword is in your Page H1.')
            } else {
                prs_ca.generate_li('prs_ca_h1_keyword', 'red', 'Your Target Keyword is <b>NOT</b> in your Page H1.')
            }
        },
        prs_ca_h1_keyword_content: function() {
            var content = prs_ca.get_content();
            if (content == '') content = '<div></div>';
            var tempDom = $('<div>').append($.parseHTML(content));

            var h1s = tempDom.find('h1');

            var contains = false;
            var keyword = $('#seo_keyword').val().toLowerCase();

            if (h1s.length > 0) {
                h1s.each(function(){
                    var text = $(this).html().toLowerCase();
                    if (text.contains(keyword)) {
                        contains = true;
                    }
                });
            }

            if (keyword == '') {
                prs_ca.generate_li('prs_ca_h1_keyword', 'yellow', 'Your Target Keyword is not set.')
            }else if (h1s.length < 1) {

            }else if (contains == true) {
                prs_ca.generate_li('prs_ca_h1_keyword', 'green', 'Your Target Keyword is in your Page H1.')
            } else {
                prs_ca.generate_li('prs_ca_h1_keyword', 'red', 'Your Target Keyword is <b>NOT</b> in your Page H1.')
            }
        },
        prs_ca_h2_keyword: function() {
            var content = prs_ca.get_content();
            if (content == '') content = '<div></div>';
            var tempDom = $('<div>').append($.parseHTML(content));

            var h2s = tempDom.find('h2');

            var contains = false;
            var keyword = $('#seo_keyword').val().toLowerCase();

            if (h2s.length > 0) {
                h2s.each(function(){
                    var text = $(this).html().toLowerCase();
                    if (text.contains(keyword)) {
                        contains = true;
                    }
                });
            }

            if (keyword == '') {
                prs_ca.generate_li('prs_ca_h2_keyword', 'yellow', 'Your Target Keyword is not set.')
            }else if (h2s.length < 1) {
                prs_ca.generate_li('prs_ca_h2_keyword', 'yellow', 'H2 Tags are not found in your Page.')
            }else if (contains == true) {
                prs_ca.generate_li('prs_ca_h2_keyword', 'green', 'Your Target Keyword is in your Page H2.')
            } else {
                prs_ca.generate_li('prs_ca_h2_keyword', 'red', 'Your Target Keyword is <b>NOT</b> in your Page H2.')
            }
        },
        prs_ca_h3_keyword: function() {
            var content = prs_ca.get_content();
            if (content == '') content = '<div></div>';
            var tempDom = $('<div>').append($.parseHTML(content));

            var h2s = tempDom.find('h3');

            var contains = false;
            var keyword = $('#seo_keyword').val().toLowerCase();

            if (h2s.length > 0) {
                h2s.each(function(){
                    var text = $(this).html().toLowerCase();
                    if (text.contains(keyword)) {
                        contains = true;
                    }
                });
            }

            if (keyword == '') {
                prs_ca.generate_li('prs_ca_h3_keyword', 'yellow', 'Your Target Keyword is not set.')
            }else if (h2s.length < 1) {
                prs_ca.generate_li('prs_ca_h3_keyword', 'yellow', 'H3 Tags are not found in your Page.')
            }else if (contains == true) {
                prs_ca.generate_li('prs_ca_h3_keyword', 'green', 'Your Target Keyword is in your Page H3.')
            } else {
                prs_ca.generate_li('prs_ca_h3_keyword', 'red', 'Your Target Keyword is <b>NOT</b> in your Page H3.')
            }
        },
        prs_ca_keyword_density: function() {
            var keyword = $('#seo_keyword').val().toLowerCase();
            if (keyword == '') {
                $('.count-seo-density').html('0.0%');
                return false;
            }

            var content = prs_ca.get_content('text').replace(/\!/g, ' ').replace(/\?/g, ' ').toLowerCase();
            var reg = new RegExp(keyword, "g");
            var occurrences = (content.match(reg) || []).length;

            var words = prs_ca.get_words(content, true);

            var totalWords = words.length;
            var a = occurrences;
            var b = totalWords;
            var c = a/b;
            var wordCount = c*100;
            $('.count-seo-density').html(wordCount.toFixed(2) + '%');
        },
        prs_ca_keyword_title: function() {
            var title = prs_ca.get_title_value().toLowerCase();
            var keyword = $('#seo_keyword').val().toLowerCase();
            if (keyword == '') {
                prs_ca.generate_li('prs_ca_keyword_title', 'yellow', 'Your Target Keyword is not set.')
            }else if (title.contains(keyword)) {
                prs_ca.generate_li('prs_ca_keyword_title', 'green', 'Your Target Keyword is in your Page Title.')
            } else {
                prs_ca.generate_li('prs_ca_keyword_title', 'red', 'Your Target Keyword is <b>NOT</b> in your Page Title.')
            }
        },
        prs_ca_keyword_desc: function() {
            var title = prs_ca.get_desc_value().toLowerCase();
            var keyword = $('#seo_keyword').val().toLowerCase();
            if (keyword == '') {
                prs_ca.generate_li('prs_ca_keyword_desc', 'yellow', 'Your Target Keyword is not set.')
            }else if (title.contains(keyword)) {
                prs_ca.generate_li('prs_ca_keyword_desc', 'green', 'Your Target Keyword is in your Page Description.')
            } else {
                prs_ca.generate_li('prs_ca_keyword_desc', 'red', 'Your Target Keyword is <b>NOT</b> in your Page Description.')
            }
        },
        prs_ca_keyword_body: function() {
            var body = prs_ca.get_content().toLowerCase();
            var keyword = $('#seo_keyword').val().toLowerCase();
            if (keyword == '') {
                prs_ca.generate_li('prs_ca_keyword_body', 'yellow', 'Your Target Keyword is not set.')
            }else if (body.contains(keyword)) {
                prs_ca.generate_li('prs_ca_keyword_body', 'green', 'Your Target Keyword is in your Page Body.')
            } else {
                prs_ca.generate_li('prs_ca_keyword_body', 'red', 'Your Target Keyword is <b>NOT</b> in your Page Body.')
            }
        },
        prs_ca_keyword_url: function() {
            var url = $('#prs-url').html().toLowerCase();
            url = url.trim();
            var keyword = $('#seo_keyword').val().toLowerCase().replace(/\ /g, '');
            var keyword_crte = $('#seo_keyword').val().toLowerCase().replace(/\ /g, '-');
            keyword = keyword.trim();
            keyword_crte = keyword_crte.trim();
            if (keyword == '') {
                prs_ca.generate_li('prs_ca_keyword_url', 'yellow', 'Your Target Keyword is not set.')
            }else if (url.contains(keyword) || url.contains(keyword_crte)) {
                prs_ca.generate_li('prs_ca_keyword_url', 'green', 'Your Target Keyword is in your Page URL.')
            } else {
                prs_ca.generate_li('prs_ca_keyword_url', 'red', 'Your Target Keyword is <b>NOT</b> in your Page URL.')
            }
        },
        prs_ca_calculate_content_length: function() {
            var wordCount = prs_ca.get_words(prs_ca.get_content('text'));
            $('.count-seo-words').html(wordCount);
        },
        prs_ca_calculate_title_length: function() {
            var wordCount = prs_ca.get_title();
            if (wordCount == 0) {
                wordCount = $('#prs-title').attr('placeholder').replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().length;
            }
            if (wordCount > 70) {
                wordCount = '<span style="color:red">' + wordCount + '</span>';
            }
            $('.count-seo-title').html(wordCount);
        },
        prs_ca_calculate_title_length_mobile: function() {
            var wordCount = prs_ca.get_title();
            if (wordCount == 0) {
                wordCount = $('#prs-title').attr('placeholder').replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().length;
            }
            if (wordCount > 78) {
                wordCount = '<span style="color:red">' + wordCount + '</span>';
            }
            $('.count-seo-title-mobile').html(wordCount);
        },
        prs_ca_calculate_description_length: function() {
            var wordCount = prs_ca.get_desc();
            if (wordCount == 0) {
                wordCount = $('#prs-description').attr('placeholder').replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().length;
            }
            if (wordCount > 300) {
                wordCount = '<span style="color:red">' + wordCount + '</span>';
            }
            $('.count-seo-description').html(wordCount);
        },
        prs_ca_calculate_description_length_mobile: function() {
            var wordCount = prs_ca.get_desc();
            if (wordCount == 0) {
                wordCount = $('#prs-description').attr('placeholder').replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().length;
            }
            if (wordCount > 120) {
                wordCount = '<span style="color:red">' + wordCount + '</span>';
            }
            $('.count-seo-description-mobile').html(wordCount);
        },


        /** Utils **/
        generate_li: function(id, color, text) {
            var icon = '';
            if (color == 'green') icon = 'fa-check';
            if (color == 'yellow') icon = 'fa-warning';
            if (color == 'red') icon = 'fa-close';
            $('#' + id).html('<i class="fa '+icon+' '+color+'"></i> ' + text);
        },
        get_title: function() {
            return $('#prs-title').html().replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().length;
        },
        get_title_value: function() {
            var title = $('#prs-title').html();
            if (title == '') {
                title = $('#prs-title').attr('placeholder');
            }
            return title;
        },
        get_desc: function() {
            return $('#prs-description').html().replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().length;
        },
        get_desc_value: function() {
            return $('#prs-description').html();
        },
        get_content: function(format) {
            var html = '';
            if (typeof format == 'undefined') {
                format = 'html';
            }
            try {
                html = tinyMCE.get('content').getContent({format : format});
            } catch (error) {
                var wpeditor = jQuery('#content-textarea-clone');
                if (wpeditor.length > 0) {
                    if (format == 'html') {
                        html = wpeditor.text().replace(/\[.*?\]/g, "");
                    } else {
                        var content = wpeditor.text();
                        var rex = /(<([^>]+)>)/ig;
                        html = content.replace(rex , "").replace(/\[.*?\]/g, "");
                    }
                } else {
                    html = '';
                }
            }
            if (html == '') {
                if (typeof thriveBody != 'undefined') {
                    if (thriveBody != '') {
                        html = ps_read_body;
                        if (format == 'html') {
                            html = html.replace(/\[.*?\]/g, "");
                        } else {
                            var rex = /(<([^>]+)>)/ig;
                            html = html.replace(rex , "").replace(/\[.*?\]/g, "");
                        }
                    }
                }
            }
            if (html == '') {
                if ($('.mce-content-body').length != 0) {
                    html = $('.mce-content-body').html();
                    if (format == 'html') {
                        html = html.replace(/\[.*?\]/g, "");
                    } else {
                        var rex = /(<([^>]+)>)/ig;
                        html = html.replace(rex , "").replace(/\[.*?\]/g, "");
                    }
                }
            }
            return html;
        },
        get_words: function (s, b) {
            s = s.replace(/(^\s*)|(\s*$)/gi,"");//exclude  start and end white-space
            s = s.replace(/[ ]{2,}/gi," ");//2 or more space to 1
            s = s.replace(/\n /,"\n"); // exclude newline with a start spacing
            if (typeof b == 'undefined') {
                return s.split(' ').length;
            } else {
                return s.split(' ');
            }
        }
    };

})( jQuery );

String.prototype.contains = function(it) { return this.indexOf(it) != -1; };

// Disable Element
jQuery.fn.extend({
    disable: function (message) {
        return this.each(function () {
            var i = jQuery(this).find('i');
            if (typeof jQuery(this).attr('disabled') == 'undefined') {
                if (i.length > 0) {
                    i.attr('class-backup', i.attr('class'));
                    i.attr('class', 'fa fa-refresh fa-spin');
                }
                if (typeof message != 'undefined') {
                    jQuery(this).attr('text-backup', jQuery(this).text());
                    jQuery(this).text(' ' + message);
                    jQuery(this).prepend(i);
                }
                jQuery(this).attr('disabled', 'disabled');
            } else {
                jQuery(this).removeAttr('disabled');
                if (i.length > 0) i.attr('class', i.attr('class-backup'));
                if (typeof jQuery(this).attr('text-backup') != 'undefined') {
                    jQuery(this).text(' ' + jQuery(this).attr('text-backup'));
                    jQuery(this).prepend(i);
                }
            }
        });
    }
});