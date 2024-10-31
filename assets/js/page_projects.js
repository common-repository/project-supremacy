var currentProjectID   = 0;
var currentProjectName = 0;
var modal_block        = '';
var moveToProject      = false;
var activeChanges      = false;
var keywordGroupID     = false;
var postsTable;
var postsTable2;
var selectedPosts;
var pTypes;

Array.prototype.remove = function(data) {
    const dataIdx = this.indexOf(data)
    if(dataIdx >= 0) {
        this.splice(dataIdx ,1);
    }
    return this.length;
}

window.onbeforeunload = function (e) {
    var message = "Are you sure you want to leave without saving your changes?", e = e || window.event;
    // For IE and Firefox
    if (activeChanges) {
        if (e) {
            e.returnValue = message;
        }

        // For Safari
        return message;
    }
};

var cf_templates = {
    Default: {
        name: "Default",
        data: {
            volume_red: 20,
            volume_green: 100,

            cpc_red: 0.59,
            cpc_green: 1.0,

            broad_red: 249999,
            broad_green: 100000,

            phrase_red: 100000,
            phrase_green: 10000,

            intitle_red: 1000,
            intitle_green: 250,

            inurl_red: 1000,
            inurl_green: 250
        }
    },
    Affiliate: {
        name: "Affiliate",
        data: {
            volume_red: 100,
            volume_green: 1000,

            cpc_red: 1,
            cpc_green: 2,

            broad_red: 1000000,
            broad_green: 100000,

            phrase_red: 100000,
            phrase_green: 10000,

            intitle_red: 10000,
            intitle_green: 1000,

            inurl_red: 10000,
            inurl_green: 1000
        }
    },
    Local: {
        name: "Local",
        data: {
            volume_red: 10,
            volume_green: 100,

            cpc_red: 2,
            cpc_green: 5,

            broad_red: 100000,
            broad_green: 10000,

            phrase_red: 10000,
            phrase_green: 1000,

            intitle_red: 1000,
            intitle_green: 100,

            inurl_red: 1000,
            inurl_green: 100
        }
    }
};

var cf_default_template = 'Default';
var cf_template = cf_templates[cf_default_template].data;

(function ($) {
    'use strict';

    $(document).ready(function () {

        actions.loadCfTemplates();
        actions.changeCfTemplate();
        actions.saveCfTemplate();
        actions.applyCfTemplate();
        actions.deleteCfTemplate();
        actions.cfValidation();
        actions.newKeyword();
        actions.deleteKeywords();
        actions.createPagePost();
        actions.deleteGroup();
        actions.deleteGroups();
        actions.createPagePostMulti();
        actions.updateGroup();
        actions.newGroup();
        actions.newProject();
        actions.removeProject();
        actions.renameProject();
        actions.loadProjects();
        actions.loadProject();
        actions.backToProjects();
        actions.editGroupSettings();
        actions.selectAllKeywords();

        actions.exportProject();
        actions.importProject();

        actions.selectKeyword();
        actions.minimizeGroup();
        actions.loadRedirects();
        actions.addNewRedirect();
        actions.deleteRedirect();
        actions.onURLEdit();
        actions.goToPagePost();

        actions.attachToPagePost();
        actions.loadPostTypes();
        actions.changePostTypes();

        actions.onDragChangeCursor();
        actions.moveToProject();

        actions.addGroupFromExisting();

        actions.keywordInputKeypress();
        actions.selectAllPagePosts();
        actions.filterByPostType();
        actions.expandCollapseFunctions();
        actions.formatSEO();

        actions.wordCountCloud();

        $.tablesorter.addParser({
            id: "fancyNumber",
            is: function(s) {
                // return false so this parser is not auto detected
                return false;
            },
            format: function(s) {
                return $.tablesorter.formatFloat( s.replace(/,/g,'') );
            },
            type: "numeric"
        });
    });


    var actions = {
        wordCountCloud: function () {

            $(document).on('click', '.wordCloud', function () {

                var cloudBoxTemplate = $('.cloud.template.hide').clone();
                cloudBoxTemplate.removeClass('hide').show().addClass('seen');

                var btn = $(this);

                if ( btn.hasClass('open') ) {
                    btn.removeClass('open');
                    btn.find('i').removeClass().addClass('fa fa-cloud');
                    btn.parents('.uk-panel-box').children('.updateKeywords').find('.keywordInput[data-target="keyword"]').unhighlight();
                    var thisCont = btn.parents('.uk-panel-box').children('.cloud.template.seen.jqcloud');
                    if ( thisCont.length > 0 ) {
                        thisCont.jQCloud('destroy');
                        thisCont.slideUp("normal", function() { $(this).remove(); actions.updateGrid(); } );
                    }
                } else{
                    btn.addClass('open');
                    var group_id = btn.parents('.uk-panel-box').children('.updateGroup').find('input[name="group_id"]').val();
                    var project_id = btn.parents('.uk-panel-box').children('.updateGroup').find('input[name="project_id"]').val();

                    btn.disable();
                    $.post(prs_data.wp_post, 'action=prs_getGroup&project_id=' + project_id + '&group_id=' + group_id, function(d){
                        btn.disable();
                        if ( d.hasOwnProperty('keywords')) {
                            var keywords = d.keywords;
                            if ( keywords.length > 0 ) {
                                var temp = [];
                                for ( var i = 0; i < keywords.length; i++) {
                                    temp.push(keywords[i].keyword);
                                }

                                btn.find('i').removeClass().addClass('fa fa-arrow-up');
                                btn.parents('.uk-panel-box').children('.updateGroup').after(cloudBoxTemplate);
                                cloudBoxTemplate.jQCloud(actions.calculateAndTrim(temp), {
                                    colors: ["#13bfff",
                                        "#26c5ff",
                                        "#3acaff",
                                        "#4ecfff",
                                        "#61d4ff",
                                        "#75daff",
                                        "#89dfff",
                                        "#9ce4ff",
                                        "#b0eaff",
                                        "#c3efff",
                                        "#d7f4ff",
                                        "#ebfaff",
                                        "#feffff"],
                                    autoResize: true,
                                    fontSize: {
                                        from: 0.1,
                                        to: 0.03
                                    }
                                });

                                actions.updateGrid();
                            } else {
                                btn.removeClass('open');
                                UIkit.notify('No keywords for this group', {pos:'bottom-right', status: 'warning'});
                            }
                        } else {
                            btn.removeClass('open');
                            UIkit.notify('No keywords for this group', {pos:'bottom-right', status: 'warning'});
                        }

                    })
                }


            });
        },
        expandCollapseFunctions: function () {
            $(document).on('click', '.collapseAllGroups', function (e) {
                e.preventDefault();
                actions.collapseKeywordGroups();
                actions.collapseSettingsBody();
                actions.updateGrid();
            });

            $(document).on('click', '.expandAllGroups', function (e) {
                e.preventDefault();
                actions.expandKeywordGroups();
                actions.expandSettingsBody();
                actions.updateGrid();
            });

            $(document).on('click', '.expandKeywordGroups', function (e) {
                e.preventDefault();
                actions.expandKeywordGroups();
                actions.updateGrid();
            });

            $(document).on('click', '.collapseKeywordGroups', function (e) {
                e.preventDefault();
                actions.collapseKeywordGroups();
                actions.updateGrid();
            });
        },
        expandKeywordGroups: function () {
            $('.updateKeywords').each(function(){
                $(this).removeClass('hidden');
            });
        },
        collapseKeywordGroups: function () {
            $('.updateKeywords').each(function(){
                $(this).removeClass('hidden').addClass('hidden');
            });
        },
        expandSettingsBody: function () {
            $('.groupSettingsTbody').each(function () {
                $(this).css('display', 'table-row-group');
            });
        },
        collapseSettingsBody: function () {
            $('.groupSettingsTbody').each(function () {
                $(this).css('display', 'none');
            });
        },
        keywordInputKeypress: function(){
            $(document).on('keypress', '.keywordInput', function(){
                activeChanges = true;
            });
        },
        addGroupFromExisting: function () {

            $(document).on('click', '.addGroupFromExisting', function(e){
                e.preventDefault();

                selectedPosts = [];
                $('.selected-posts').find('.value').html(0);

                var addGroupModal = $('#addGroupFromExistingModal');
                UIkit.modal( addGroupModal ).show();
            });

            $(document).on('change', '.select-post', function(){

                let checked = $(this).is(':checked');
                if (checked) {
                    selectedPosts.push($(this).val());
                } else {
                    selectedPosts.remove($(this).val());
                }

                $('.selected-posts').find('.value').html(selectedPosts.length);
            });

            $(document).on('change', '.select-posts-all', function(){

                let checked = $(this).is(':checked');

                $('.postsTable2').find('.select-post').each(function(){
                    $(this).prop('checked', checked);
                    $(this).trigger('change');
                });

            });

            $(document).on('click', '.add-group-from-existing', function (e) {
                e.preventDefault();

                var btn = $(this);
                btn.disable();

                if (selectedPosts.length < 1) {
                    UIkit.notify('You must first select some posts first!', {pos:'bottom-right', status: 'error'});
                    return;
                }

                $.post(prs_data.wp_post, 'action=prs_make_groups&ids='+selectedPosts.join(',')+'&project_id=' + currentProjectID, function(d){

                    UIkit.modal('#addGroupFromExistingModal').hide();
                    btn.disable();
                    UIkit.notify(d.message, {pos:'bottom-right', status: 'success'});
                    actions.loadProjectManually();

                });

            });

        },
        selectAllPagePosts: function () {
            $(document).on('click', '.select-all-page-posts', function () {

                var btn = $(this);

                if ( btn.hasClass('selected') ) {
                    $("#posts_pages > option").removeAttr("selected").trigger("change");
                    btn.removeClass('uk-button-danger selected').addClass('uk-button-success');
                    btn.html('<i class="fa fa-plus"></i> Select All');
                } else {
                    $('#posts_pages > option').prop("selected","selected").trigger("change");
                    btn.removeClass('uk-button-success').addClass('uk-button-danger selected');
                    btn.html('<i class="fa fa-minus"></i> Deselect All');
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
                        actions.loadRedirects();

                    });
                });
            });
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

                            actions.loadRedirects();

                        });

                    });

                });

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
                            var html = '<tr>' +
                                '<td><a target="_blank" href="/'+data.old+'">/'+data.old+'</a></td>' +
                                '<td><a target="_blank" href="/'+data.new+'">/'+data.new+'</a></td>' +
                                '<td><button type="button" class="uk-button uk-button-mini uk-button-danger delete-redirect" data-id="'+data.id+'" title="Delete this redirect"><i class="fa fa-trash-o"></i></button></td>' +
                                '</tr>';
                            tbody.append(html);
                        }

                    }

                } else {
                    UIkit.notify('An unknown error has occurred.', {pos:'bottom-right', status: 'danger'});
                }
            });

        },
        minimizeGroup: function(){
            $(document).on('click', '.minimizeGroup', function(){
                var i = $(this).find('i');
                var kw = $(this).parents('.group').find('.updateKeywords');
                i.toggleClass('fa-chevron-up fa-chevron-down');
                kw.toggleClass('hidden');
                actions.updateGrid();
            });
        },
        selectKeyword: function(){
            $(document).on('change', '.keyword-selection', function(){
                var tr = $(this).parents('tr');
                if (!tr.hasClass('selected')) {
                    tr.addClass('selected');
                }
            });
        },
        moveToProject: function(){
            $(document).on('click', '.moveToProject', function(e){
                e.preventDefault();
                let input    = $('#moveToProjectInput');
                let group_id = $(this).parents('.updateGroup').find('input[name="group_id"]').val();
                input.data('group-id', group_id);
                $.post(prs_data.wp_post, 'action=prs_get_projects', function(d){

                    input.empty();

                    input.append('<option value="">--- Select a Project ---</option>');

                    for(let i = 0; i < d.aaData.length; i++) {
                        let o = d.aaData[i];
                        input.append('<option value="'+o.id+'">'+o.project_name+'</option>');
                    }

                    moveToProject = UIkit.modal($('#moveToProjectGroup'))
                    moveToProject.show();
                });

                //
            });
            $(document).on('submit', '#moveToProjectForm', function(e){
                e.preventDefault();
                let form     = $(this);
                let group_id = $('#moveToProjectInput').data('group-id');
                $.post(prs_data.wp_post, 'action=prs_moveToProject&' + form.serialize() + '&group_id=' + group_id, function(d){

                    UIkit.notify(d.message, {pos:'bottom-right', status: d.status});

                    if (d.status === 'success') {
                        moveToProject.hide();
                        actions.loadProjectManually();
                    }

                });


            });
        },
        /*CF Templates*/
        loadCfTemplates: function(){
            $.post(prs_data.wp_post, 'action=prs_getCfTemplates', function(d){
                if(d.status == 'success') {
                    cf_templates = $.extend(cf_templates, d.data)
                }

                var template = cf_templates[d.default];

                // Set default template globally
                cf_template = template.data;
                cf_default_template = d.default;

                var template_names = '<option value="new">---</option>';
                for (var key in cf_templates) {
                    if (key == d.default) {
                        // Star if it's default template
                        template_names += '<option value="'+key+'">'+key+' *</option>';
                    } else {
                        template_names += '<option value="'+key+'">'+key+'</option>';
                    }
                }
                $('#cf-templates').html(template_names);

                $('#cf-templates').val(template.name);
                for (var key in template.data) {
                    $('#' + key).val(template.data[key]);
                    $('.' + key).val(template.data[key]);
                }
            },'json');

        },
        changeCfTemplate: function() {
            $( "#cf-templates" ).change(function() {
                var templateName = $(this).val();
                if (templateName == 'new') {
                    $('#conditional-formatting-local-form').find("input").val("");
                    $('#applyCfTemplate').attr('disabled', true);
                    $('#deleteCfTemplate').attr('disabled', true);
                } else {
                    for (var key in cf_templates[templateName].data) {
                        $('#' + key).val(cf_templates[templateName].data[key]);
                        $('.' + key).val(cf_templates[templateName].data[key]);
                    }
                    $('#applyCfTemplate').attr('disabled', false);
                    $('#deleteCfTemplate').attr('disabled', false);
                }
            });
        },
        saveCfTemplate: function() {
            $(document).on('click', '#saveCfTemplate', function(e){
                // Disable button to prevent multiple sending
                var btn = $(this);
                btn.attr('disabled', true);
                e.preventDefault();

                var data = $('#conditional-formatting-local-form').serialize();

                var selected_template = $('#cf-templates').val();

                if(selected_template == 'new'){
                    UIkit.modal.prompt('<span style="font-size: 20px;"><i class="fa fa-save"></i> Please enter name for new template:</span>', '', function(newvalue){

                        var new_name = newvalue;

                        if(new_name.length < 1){
                            UIkit.notify('<i class="fa fa-exclamation-circle"></i> Please enter a name for new template!', {pos:'bottom-right', status:'danger'});
                            btn.attr('disabled', false);
                            return false;
                        }

                        $.post(prs_data.wp_post, 'action=prs_createCfTemplate&' + data + '&name=' + new_name, function(d){

                            btn.attr('disabled', false);
                            $('#applyCfTemplate').attr('disabled', false);
                            if(d.status == 'error'){
                                UIkit.notify(d.message, {pos:'bottom-right', status:'danger'});
                                return false;
                            }else{
                                UIkit.notify(d.message, {pos:'bottom-right', status:d.status});
                            }
                            // Update CF Templates data
                            actions.loadCfTemplates();

                        },'json');
                    });

                    return false;
                }

                $.post(prs_data.wp_post, 'action=prs_saveCfTemplate&' + data + '&name=' + selected_template, function(d){
                    UIkit.notify(d.message, {pos:'bottom-right', status:d.status});
                    // Update CF Templates data
                    cf_templates = d.data;
                    cf_template = cf_templates[cf_default_template].data;
                    // When saving is done, enable button again
                    btn.attr('disabled', false);
                },'json');

            });
        },
        applyCfTemplate: function() {
            $(document).on('click', '#applyCfTemplate', function(e){
                e.preventDefault();
                var btn = $(this);
                btn.attr('disabled', true);

                $.post(prs_data.wp_post, 'action=prs_applyCfTemplate&templateName=' + $('#cf-templates').val(), function(d){
                    UIkit.notify(d.message, {pos:'bottom-right', status:d.status});
                    // When saving is done, enable button again
                    btn.attr('disabled', false);
                    actions.loadCfTemplates();
                    actions.loadProjectManually();
                },'json');
            })
        },
        deleteCfTemplate: function () {
            $(document).on('click', '#deleteCfTemplate', function(e){
                e.preventDefault();
                var btn = $(this);
                btn.attr('disabled', true);
                var template_name = $('#cf-templates').val();

                UIkit.modal.confirm("Are you sure that you want to delete selected template?", function () {
                    $.post(prs_data.wp_post, 'action=prs_deleteCfTemplate&templateName=' + template_name, function(d){
                        UIkit.notify(d.message, {pos:'bottom-right', status:d.status});
                        // When saving is done, enable button again
                        btn.attr('disabled', false);
                        actions.loadCfTemplates();
                    },'json');
                }, function () {
                    btn.attr('disabled', false);
                });
            })

        },
        cfValidation: function () {
            var inputs  = ['volume', 'cpc'];
            var inputs2 = ['broad', 'phrase', 'intitle', 'inurl'];

            $.each(inputs, function(index, value){
                var input_type = value;

                $('#'+input_type+'_red').change(function(){
                    var value1 = $(this).val();
                    var value2 = $('#'+input_type+'_green').val();
                    value1 = parseFloat(value1);
                    value2 = parseFloat(value2);
                    if (value1 >= value2) {
                        UIkit.notify('Please input correct condition!', {pos:'bottom-right', status:'warning'});
                        $(this).val('');
                        $(this).focus();
                        return false;
                    }

                    $('.'+input_type+'_yellow_1').val(value1);
                    $('.'+input_type+'_yellow_2').val(value2);
                });

                $('#'+input_type+'_green').change(function(){
                    var value1 = $(this).val();
                    var value2 = $('#'+input_type+'_red').val();
                    value1 = parseFloat(value1);
                    value2 = parseFloat(value2);
                    if (value1 <= value2) {
                        UIkit.notify('Please input correct condition!', {pos:'bottom-right', status:'warning'});
                        $(this).val('');
                        $(this).focus();
                        return false;
                    }

                    $('.'+input_type+'_yellow_1').val(value2);
                    $('.'+input_type+'_yellow_2').val(value1);
                });
            });

            $.each(inputs2, function(index, value){
                var input_type = value;

                $('#'+input_type+'_red').change(function(){
                    var value1 = $(this).val();
                    var value2 = $('#'+input_type+'_green').val();
                    value1 = parseFloat(value1);
                    value2 = parseFloat(value2);
                    if (value1 <= value2) {
                        UIkit.notify('Please input correct condition!', {pos:'bottom-right', status:'warning'});
                        $(this).val('');
                        $(this).focus();
                        return false;
                    }

                    $('.'+input_type+'_yellow_1').val(value1);
                    $('.'+input_type+'_yellow_2').val(value2);
                });

                $('#'+input_type+'_green').change(function(){
                    var value1 = $(this).val();
                    var value2 = $('#'+input_type+'_red').val();
                    value1 = parseFloat(value1);
                    value2 = parseFloat(value2);
                    if (value1 >= value2) {
                        UIkit.notify('Please input correct condition!', {pos:'bottom-right', status:'warning'});
                        $(this).val('');
                        $(this).focus();
                        return false;
                    }

                    $('.'+input_type+'_yellow_1').val(value2);
                    $('.'+input_type+'_yellow_2').val(value1);
                });
            });
        },

        /*Munja Menu*/
        newKeyword: function () {
            $(document).on('click', '.add-keywords', function(e){
                e.preventDefault();

                let keywords = $('#keywords-input').val();

                if (keywords === '') {
                    UIkit.notify("<i class='uk-icon-close'></i> You must insert some keywords first.", {pos:'bottom-right', status:"error"});
                    return;
                }

                $.post(prs_data.wp_post, 'action=prs_addKeyword&group_id=' + keywordGroupID + '&keywords=' + keywords, function(d){

                    UIkit.modal("#addKeywords").hide();

                    UIkit.notify("<i class='uk-icon-check'></i> Successfully added keywords.", {pos:'bottom-right', status:"success"});
                    actions.loadProjectManually();

                },'json');
            });
            $(document).on('click', '.addKeyword', function(e){
                e.preventDefault();
                let group = $(this).parents('.group');
                keywordGroupID = group.find('[name="group_id"]').val();
                let modal = UIkit.modal("#addKeywords");
                modal.show();
            });
        },
        deleteKeywords: function () {
            $(document).on('click', '.deleteKeywords', function (e) {
                e.preventDefault();
                var keyword_ids = $(this).parents('.uk-panel-box').find('.updateKeywords').serialize();
                var keywords_length = $(this).parents('.uk-panel-box').find('.updateKeywords').serializeArray().length;

                if(keywords_length < 1){
                    UIkit.notify("<i class='uk-icon-close'></i> Please select some keywords!", {pos:'bottom-right', status:"danger"});
                    return false;
                }

                UIkit.modal.confirm("Are you sure that you want to remove <b>"+keywords_length+"</b> keywords permanently?", function(){
                    $.post(prs_data.wp_post, 'action=prs_deleteKeywords&' + keyword_ids, function (d){
                        UIkit.notify("<i class='uk-icon-check'></i> Keywords successfully deleted.", {pos:'bottom-right', status:"success"});
                        actions.loadProjectManually();
                    })
                });
            })
        },
        createPagePost: function () {
            /*Create New Page or Post*/
            $(document).on('click', '.createNewPagePost', function(e){
                e.preventDefault();
                var btn = $(this);
                var btn_type = btn.attr('data-type');
                var form = btn.parents('form.updateGroup');
                var form_post = form.serialize().replace('action=prs_updateGroup&', '');

                var block_template = $('.creating_block').clone().removeClass('block_template');

                block_template.find('.creating').html('Creating ' + btn_type);
                var waiting = UIkit.modal.blockUI(block_template.html());

                $.post(prs_data.wp_post, 'action=prs_create_page_post&type=' + btn_type + '&' + form_post, function (d){

                    waiting.hide();
                    if(d.status == 'error'){
                        UIkit.notify("<i class='uk-icon-close'></i> " + d.message, {pos:'bottom-right', status:"danger"});
                        return false;
                    }
                    
                    var modal_template = $('#resultsPagePost');
                    
                    if(d.status == 'warning'){
                        modal_template.find('.pagePostResultsMessage').html('<i class="fa fa-warning"></i> Page is already created, you can access it below!')
                    }

                    modal_template.find('.edit_page_post_link').html('<a href="'+d.data+'" target="_blank">'+d.data+'</a>');

                    UIkit.modal(modal_template).show();

                })
            });
        },
        deleteGroup: function () {
            $(document).on('click', '.deleteGroup', function (e) {
                e.preventDefault();
                var group      = $(this).parents('.group');
                var group_id   = group.find('[name="group_id"]').val();

                UIkit.modal.confirm("Are you sure that you want to remove <b>"+group.find('.groupInput[name="group_name"]').val()+"</b> group permanently?", function(){
                    $.post(prs_data.wp_post, 'action=prs_deleteGroup&group_id=' + group_id, function (d){
                        UIkit.notify("<i class='uk-icon-check'></i> Group successfully deleted.", {pos:'bottom-right', status:"success"});
                        actions.loadProjectManually();
                    })
                });
            })
        },
        deleteGroups: function(){
            $(document).on('click', '.deleteGroups', function (e) {
                e.preventDefault();

                let group_names = [];
                let ids         = [];
                $('.groupSelect:checked').each(function(){
                    let group = $(this).parents('.group');
                    group_names.push('<li>' + group.data('name') + '</li>');
                    ids.push(group.find('[name="group_id"]').val());
                });

                UIkit.modal.confirm("Are you sure that you want to remove following groups: <ul class='groupDelete'>"+group_names.join('')+"</ul>", function(){
                    $.post(prs_data.wp_post, 'action=prs_deleteGroups&group_ids=' + ids.join(','), function (d){
                        UIkit.notify("<i class='uk-icon-check'></i> Groups successfully deleted.", {pos:'bottom-right', status:"success"});
                        actions.loadProjectManually();
                    })
                });
            })
        },
        selectAllKeywords: function() {
            $(document).on('click', '.select-all', function(){
                var table = $(this).parents('table.keywords');
                table.find('.keyword-selection').each(function(){
                    $(this).prop("checked", !$(this).prop("checked"));
                });
            });
        },
        newGroup: function () {
            $(document).on('click', '.addGroup', function(e){
                e.preventDefault();

                UIkit.modal.prompt("New Group Name:", '', function (groupName) {
                    if (groupName == '') {
                        UIkit.modal.alert("Group Name cannot be empty!");
                    } else {
                        $.post(prs_data.wp_post, 'action=prs_newGroup&project_id='+currentProjectID+'&group_name=' + groupName, function(d){
                            UIkit.notify("<i class='uk-icon-check'></i> Group '"+groupName+"' has been created.", {pos:'bottom-right', status:"success"});
                            actions.loadProjectManually();
                        },'json');
                    }
                });
            });
        },
        updateGroup: function () {
            $(document).on('submit', '.updateGroup', function(e){
                e.preventDefault();

                var button     = $(this).find('.saveGroup');
                var group_id   = $(this).find('[name="group_id"]').val();
                var project_id = $(this).find('[name="project_id"]').val();
                var data       = $(this).serialize();
                var kw_data    = $(this).parents('.group').find('.keywords-data');

                button.disable();

                // First update the group settings
                $.post(prs_data.wp_post, data, function(d){

                    button.disable();

                    // Now update all keywords
                    var keywords = [];
                    var position = 1;
                    kw_data.find('tr').each(function(){
                        var keyword = {};
                        keyword['id'] = $(this).data('id');
                        keyword['position'] = position;
                        position++;
                        var allNull = true;
                        $(this).find('td div.keywordInput').each(function(){
                            var value = $(this).text();
                            if (value != '') {
                                keyword[$(this).data('target')] = value;
                                allNull = false;
                            }
                        });
                        if (!allNull) keywords.push(keyword);
                    });

                    var data = [{
                        name: 'action',
                        value: 'prs_updateKeywords'
                    },{
                        name: 'group_id',
                        value: group_id
                    },{
                        name: 'keywords',
                        value: encodeURIComponent(JSON.stringify(keywords))
                    }];

                    $.post(prs_data.wp_post, data, function(d){
                        activeChanges = false;
                        UIkit.notify("<i class='uk-icon-check'></i> Changes saved successfully.", {pos:'bottom-right', status:"success"});
                    });

                },'json');
            });
        },
        editGroupSettings: function(){
            $(document).on('click', '.editGroupSettings', function(e){
                e.preventDefault();

                var groupSettings = $(this).parents('.groupSettings');
                var tbody = groupSettings.find('tbody.groupSettingsTbody');

                tbody.toggle();

                actions.updateGrid();
            });
        },
        renderSliders: function () {
            // Enable sliders
            $('.prs-slider-frame .slider-button').toggle(function () {
                $(this).addClass('on');
            }, function () {
                $(this).removeClass('on');
            });
        },
        newProject: function () {
            $(document).on('click', '.new-project', function (e) {
                e.preventDefault();

                UIkit.modal.prompt("New Project Name:", '', function (projectName) {
                    if (projectName == '') {
                        UIkit.modal.alert("Project Name cannot be empty!");
                    } else {
                        $.post(prs_data.wp_post, 'action=prs_new_project&project_name=' + projectName, function(d){
                            UIkit.notify("<i class='uk-icon-check'></i> Project '"+projectName+"' has been created.", {pos:'bottom-right', status:"success"});
                            actions.loadProjects();
                        },'json');
                    }
                });
            });
        },
        updateGrid: function(){
            // Fix UIkit positions
            UIkit.$html.trigger('changed.uk.dom');
        },
        updateElements: function() {
            // Table sorting
            $(".keywords").tablesorter(
                {
                    headers: {
                        0 : {
                            sorter: false
                        },
                        2 : {
                            sorter: 'fancyNumber'
                        },
                        3 : {
                            sorter: 'fancyNumber'
                        },
                        4 : {
                            sorter: 'fancyNumber'
                        },
                        5 : {
                            sorter: 'fancyNumber'
                        },
                        6 : {
                            sorter: 'fancyNumber'
                        },
                        7 : {
                            sorter: 'fancyNumber'
                        },
                        8 : {
                            sorter: 'fancyNumber'
                        }
                    }
                }
            );

            var kw_data = $('.keywords-data');

            kw_data.multisortable({
                items: "tr",
                selectedClass: "selected"
            });

            // Drag and Drop
            kw_data.sortable({
                connectWith: ".uk-sortable",
                cancel: "input,textarea,button,select,option,[contenteditable]"
            }).on( "sortreceive", function( event, ui ) {

                var target         = $(this);
                var original_group = $(ui.sender).parents('.group').find('[name="group_id"]').val();
                var target_group   = target.parents('.group').find('[name="group_id"]').val();

                setTimeout(function(){
                    var keyword_ids    = [];
                    target.find('tr.selected').each(function(){
                        var id = $(this).data('id');
                        keyword_ids.push(id);
                    });

                    $.post(prs_data.wp_post, 'action=prs_keywordChangeGroup&keyword_ids=' + keyword_ids.join(',') + '&original_group_id=' + original_group + '&target_group_id=' + target_group, function(d){
                        actions.updateGrid();

                        UIkit.notify("<i class='uk-icon-check'></i> Group change successful.", {pos:'bottom-right', status:"success"});
                    },'json');
                }, 500);
            } );
        },
        prepareURL: function(url) {
            if (url == null || url == '') {
                return {
                    pre: '/',
                    name: ''
                };
            }
            var hasSlash = 2;
            if ( url.substr(-1) !== '/' ) {
                hasSlash = 1;
            }

            url = url.split('/');
            var name = url[url.length - hasSlash];
            var cat  = url.slice(0, -hasSlash).join('/') + '/';
            return {
                pre: cat,
                name: name
            };
        },
        onDragChangeCursor: function () {
            $(document).on('click', '.drag-cursor', function () {

            })
        },
        changePostTypes: function(){
            $(document).on('change', '#PostsType', function(e){

                postsTable.fnDraw();

            });
            $(document).on('change', '#PostsType2', function(e){

                postsTable2.fnDraw();

            });
        },
        filterByPostType: function(){
            $(document).on('change', '#filterPostTypes', function(){

                let value = $(this).val() !== '' ? ' (<b>' + $(this).val().charAt(0).toUpperCase() + $(this).val().slice(1) + 's' + ')</b>' : '';

                $(this).prev().html('<i class="fa fa-folder-open-o"></i> Filter by Post Type' + value);

               actions.loadProjectManually();
            });
        },
        loadPostTypes: function(){

            $.post(prs_data.wp_post, 'action=prs_get_post_types', function(d){

                if (d.status === 'success') {

                    pTypes = d.data;

                    var postTypes = [];
                    for (var i = 0; i < pTypes.length; i++) {
                        var type = pTypes[i];
                        postTypes.push(
                            "<option value='"+type+"'>"+type.charAt(0).toUpperCase() + type.slice(1)+"s</option>"
                        );
                    }
                    pTypes = postTypes.join('');

                    // Insert into filters
                    $('#filterPostTypes').append(pTypes);

                    // Load the Datatable for posts
                    actions.loadPostsPages();
                }

            });
        },
        loadPostsPages: function(){

            postsTable  = $('.postsTable').dataTable({
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search posts...",
                    processing: "Loading Posts...",
                    emptyTable: "No posts found on this website.",
                    info:           "_START_ to _END_ of _TOTAL_ results",
                    infoEmpty:      "0 to 0 of 0 results",
                    infoFiltered:   "(from _MAX_ total results)",
                },
                "dom": '<"posts-actions"<"uk-float-right"fl>>rt<"posts-actions-bottom"ip<"uk-clearfix">>',
                "bDestroy": true,
                "searchDelay": 350,
                "bPaginate": true,
                "bAutoWidth": false,
                "bFilter": true,
                "bProcessing": true,
                "sServerMethod": "POST",
                "bServerSide": true,
                "sAjaxSource": prs_data.wp_post,
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 50, 100], [5, 10, 50, 100]],
                "aaSorting": [[1, 'desc']],
                "aoColumns": [
                    {
                        "sClass": "text-left",
                        "bSortable": false,
                        "mData": 'ID',
                        "mRender": function (data, type, row) {
                            return '<span class="post-id">'+data+'</span>';
                        }
                    },
                    {
                        "sClass": "text-left",
                        "bSortable": true,
                        "mData": 'post_title',
                        "mRender": function (data, type, row) {
                            return "<b class='post-title'>"+data+"</b>"
                                   + "<div class='row-actions'>"
                                   + "<a href='#' data-id='"+row.ID+"' class='attach-to-page-post uk-text-success'>Attach</a>"

                                   + " <span>|</span> "

                                   + "<a href='"+ prs_data.wp_admin + 'post.php?post='+row.ID+'&action=edit' +"' target='_blank' class='edit'>Edit</a>"

                                   + " <span>|</span> "

                                   + "<a href='"+row.guid+"' target='_blank' class='view'>View</a>"
                                   + "</div>";
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSortable": true,
                        "mData": 'post_date',
                        "mRender": function (data, type, row) {
                            return '<b>' + row.post_status.charAt(0).toUpperCase() + row.post_status.slice(1) + 'ed</b>'
                                   + '<br>'
                                   + '<abbr title="'+data+'">' + new Date(data).toUTCString().split(' ').splice(0, 4).join(' ') + '</abbr>';
                        },
                        "asSorting": ["desc", "asc"]
                    },
                ],
                "fnServerParams": function (aoData) {

                    aoData.push({
                        name: 'action',
                        value: 'prs_get_posts'
                    });

                    if ($('#PostsType').length > 0) {

                        aoData.push({
                            name: 'PostsType',
                            value: $('#PostsType').val()
                        });

                    }
                },
                "fnCreatedRow": function ( row, data, index ) {
                    var modal = UIkit.modal("#attachToPagePost");
                    var value = modal.find('[name="post_id"]').val();

                    if (data.ID == value) {
                        $(row).addClass('attached');
                    }
                },

                fnInitComplete: function(){

                    $('.posts-actions').append(

                        '<div class="uk-float-left">'

                        + '<select class="form-control table-actions-input" id="PostsType">'
                        + '<option value="">Post Type</option>'
                        + pTypes
                        + '</select>'

                        + '<select class="form-control table-actions-input" id="AttachType">'
                        + '<option value="">Import ...</option>'
                        + '<option value="page">Post fields</option>'
                        + '<option value="group" selected>Group fields</option>'
                        + '</select>'

                        + '</div>'

                        + '<div class="uk-clearfix"></div>'

                    );
                }

            });
            postsTable2 = $('.postsTable2').dataTable({
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search posts...",
                    processing: "Loading Posts...",
                    emptyTable: "No posts found on this website.",
                    info:           "_START_ to _END_ of _TOTAL_ results",
                    infoEmpty:      "0 to 0 of 0 results",
                    infoFiltered:   "(from _MAX_ total results)",
                },
                "dom": '<"posts-actions2"<"uk-float-right"fl>>rt<"posts-actions-bottom2"ip<"uk-clearfix">>',
                "bDestroy": true,
                "searchDelay": 350,
                "bPaginate": true,
                "bAutoWidth": false,
                "bFilter": true,
                "bProcessing": true,
                "sServerMethod": "POST",
                "bServerSide": true,
                "sAjaxSource": prs_data.wp_post,
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 50, 100], [5, 10, 50, 100]],
                "aaSorting": [[1, 'desc']],
                "aoColumns": [
                    {
                        "sClass": "text-left",
                        "bSortable": false,
                        "bSearchable": false,
                        "mRender": function (data, type, row) {
                            let checked = '';

                            if ($.inArray(row.ID, selectedPosts) !== -1) {
                                checked = 'checked';
                            }

                            return '<input '+checked+' class="select-post" type="checkbox" value="'+row.ID+'">';
                        }
                    },
                    {
                        "sClass": "text-left",
                        "bSortable": false,
                        "mData": 'ID',
                        "mRender": function (data, type, row) {
                            return '<span class="post-id">'+data+'</span>';
                        }
                    },
                    {
                        "sClass": "text-left",
                        "bSortable": true,
                        "mData": 'post_title',
                        "mRender": function (data, type, row) {
                            return "<b class='post-title'>"+data+"</b>"
                                   + "<div class='row-actions'>"

                                   + "<a href='"+ prs_data.wp_admin + 'post.php?post='+row.ID+'&action=edit' +"' target='_blank' class='edit'>Edit</a>"

                                   + " <span>|</span> "

                                   + "<a href='"+row.guid+"' target='_blank' class='view'>View</a>"
                                   + "</div>";
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSortable": true,
                        "mData": 'post_date',
                        "mRender": function (data, type, row) {
                            return '<b>' + row.post_status.charAt(0).toUpperCase() + row.post_status.slice(1) + 'ed</b>'
                                   + '<br>'
                                   + '<abbr title="'+data+'">' + new Date(data).toUTCString().split(' ').splice(0, 4).join(' ') + '</abbr>';
                        },
                        "asSorting": ["desc", "asc"]
                    },
                ],
                "fnServerParams": function (aoData) {

                    aoData.push({
                        name: 'action',
                        value: 'prs_get_posts'
                    });

                    if ($('#PostsType2').length > 0) {

                        aoData.push({
                            name: 'PostsType',
                            value: $('#PostsType2').val()
                        });

                    }
                },

                fnInitComplete: function(){

                    $('.posts-actions2').append(

                        '<div class="uk-float-left">'

                        + '<select class="form-control table-actions-input" id="PostsType2">'
                        + '<option value="">Post Type</option>'
                        + pTypes
                        + '</select>'

                        + '</div>'

                        + '<div class="uk-clearfix"></div>'

                    );
                }

            });

        },
        attachToPagePost: function(){

            $(document).on('click', '.attach-to-page-post', function(e){
                e.preventDefault();
                var button   = $(this);
                var modal    = UIkit.modal("#attachToPagePost");
                var post_id  = button.data('id');
                var attach_t = $('#AttachType').val();
                var group_id = modal.find('[name="group_id"]').val();

                button.disable('Attaching ...');
                $.post(prs_data.wp_post, 'action=prs_attach_to_page_post&group_id=' + group_id + '&post_id=' + post_id + '&attach_type=' + attach_t, function(d){
                    button.disable();
                    if (d.status == 'success') {
                        UIkit.notify("<i class='fa fa-check'></i> " + d.message, {pos:'bottom-right', status:d.status});
                        actions.loadProjectManually();
                        modal.hide();
                    } else {
                        UIkit.notify("<i class='fa fa-warning'></i> Something went wrong. Cannot attach group to this page/post.", {pos:'bottom-right', status:"error"});
                    }
                });

            });

            $(document).on('click', '.attachToPagePost', function(e){
                e.preventDefault();
                var group_id = $(this).parents('.updateGroup').find('input[name="group_id"]').val();
                var post_id  = $(this).data('post-id');
                var modal    = UIkit.modal("#attachToPagePost");

                modal.find('[name="group_id"]').val(group_id);
                modal.find('[name="post_id"]').val(post_id);

                postsTable.fnDraw();

                modal.show();
            });
        },
        goToPagePost: function(){
            $(document).on('click', '.goToPagePost', function(e){
                if ($(this).attr('href') == '#') {
                    e.preventDefault();
                    UIkit.notify("<i class='fa fa-warning'></i> You must first attach a page in order to use Go to Page/Post.", {pos:'bottom-right', status:"warning"});
                }
            });
        },
        onURLEdit: function(){
            $(document).on('focus', '[contenteditable="true"]', function() {
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
            $(document).on('change', '.url-edit', function(e){
                var cont = $(this).parents('.url-container');

                var pre  = $(this).prev('.pre-url').html();
                var name = $(this).html().replace(/\//g, '');
                var post = $(this).next('.post-url').html();

                cont.find('[name="url"]').val(pre + name + post);
            });
            $(document).on('click', '.pre-url', function(e){
                e.preventDefault();
                $(this).next().focus().select();
            });
            $(document).on('click', '.post-url', function(e){
                e.preventDefault();
                $(this).prev().focus().select();
            });
        },
        parseNumber: function(num) {
            if (num === null || num === "") {
                return '';
            } else {
                return parseInt(num).toLocaleString();
            }
        },
        loadProjectManually: function(){
            if (currentProjectID === 0) return;
            $.post(prs_data.wp_post, 'action=prs_getGroups&project_id=' + currentProjectID + '&post_type=' + $('#filterPostTypes').val(), function(d){
                var project_dashboard = $('.project-dashboard');
                var projects_table    = $('.projects-table');
                var project_groups    = $('.project-groups');
                var project_empty     = $('.project-empty');

                project_dashboard.find('.project-name').html("<i class='fa fa-file-text-o'></i> #" + currentProjectID + ": " + currentProjectName);
                if (d.length > 0) {
                    project_empty.hide();
                    project_groups.show();

                    var data = project_groups.find('.data');

                    // Remove old loaded groups
                    data.empty();

                    // Render new groups
                    for(var i = 0; i < d.length; i++) {

                        var row = d[i];
                        var template = $('.group.template').clone();
                        template.removeClass('template');

                        // Set the Post Type
                        if (row.post_type !== false) {
                            template.addClass('hasAttachedPost');
                            template.attr('data-post-type', row.post_type);
                        }

                        // Append the Group ID
                        template.find('[name="group_id"]').val(row.id);
                        template.find('[name="project_id"]').val(currentProjectID);

                        // Change the Group Name
                        template.find('[name="group_name"]').val(row.group_name);
                        template.attr('data-name', row.group_name);

                        // Prepare the URL
                        var pURL = actions.prepareURL(row.url);

                        template.find('.attachToPagePost').attr('data-post-id', row.id_page_post);

                        // Go to Page/Post
                        if (row.id_page_post != null && row.id_page_post != '') {
                            template.find('.goToPagePost').attr('href', prs_data.wp_admin + "post.php?post="+row.id_page_post+"&action=edit");
                            template.find('.attachToPagePost').html('<i class="fa fa-bullseye"></i> Attach to Page/Post &nbsp;&nbsp; (<i title="Attached to an existing Page/Post already." class="uk-text-success fa fa-check"></i>)');
                            template.find('.attachToPagePost').attr('data-group-id', row.id);
                        }

                        // Change the rest of the Group Settings
                        template.find('[name="title"]').val(row.title != null ? row.title : '');
                        template.find('[name="description"]').val(row.description != null ? row.description : '');
                        template.find('[name="notes"]').val(row.notes != null ? row.notes : '');
                        template.find('[name="h1"]').val(row.h1 != null ? row.h1 : '');
                        template.find('[name="url"]').val(row.url != null ? row.url : '');
                        template.find('.pre-url').html(pURL.pre);
                        template.find('.url-edit').html(pURL.name);
                        template.find('.post-url').html('/');
                        template.find('[name="oriUrl"]').val(row.url != null ? row.url : '');

                        template.find('[data-target="title"]').text(row.title != null ? row.title : '');
                        template.find('[data-target="description"]').text(row.description != null ? row.description : '');

                        // Calculate Counting
                        template.find('.count-seo-title').text(row.title != null ? row.title.length : 0);
                        template.find('.count-seo-title-mobile').text(row.title != null ? row.title.length : 0);

                        template.find('.count-seo-description').text(row.description != null ? row.description.length : 0);
                        template.find('.count-seo-description-mobile').text(row.description != null ? row.description.length : 0);

                        // Go through keywords
                        if (row.keywords.length > 0) {

                            var kwData = template.find('.keywords-data');
                            kwData.empty();
                            for(var k = 0; k < row.keywords.length; k++) {
                                var keyword = row.keywords[k];

                                // remove null values
                                for(var key in keyword) {
                                    if (keyword.hasOwnProperty(key)) {
                                        if (keyword[key] == null) {
                                            keyword[key] = '';
                                        }
                                    }
                                }

                                /**
                                 *
                                 *     CONDITIONAL FORMATTING
                                 *
                                 */

                                var volume_color, cpc_color, broad_color, phrase_color, intitle_color, inurl_color;

                                if (keyword.volume == "") {
                                    volume_color = '';
                                } else if (parseFloat(cf_template.volume_red) > parseFloat(keyword.volume)) {
                                    volume_color = 'tr_red';
                                } else if (parseFloat(cf_template.volume_red) < parseFloat(keyword.volume) && parseFloat(cf_template.volume_green) > parseFloat(keyword.volume)) {
                                    volume_color = 'tr_yellow';
                                } else if (parseFloat(cf_template.volume_green) < parseFloat(keyword.volume)) {
                                    volume_color = 'tr_green';
                                }

                                if (keyword.cpc == "") {
                                    cpc_color = '';
                                } else if (parseFloat(cf_template.cpc_red) > parseFloat(keyword.cpc)) {
                                    cpc_color = 'tr_red';
                                } else if (parseFloat(cf_template.cpc_red) < parseFloat(keyword.cpc) && parseFloat(cf_template.cpc_green) > parseFloat(keyword.cpc)) {
                                    cpc_color = 'tr_yellow';
                                } else if (parseFloat(cf_template.cpc_green) < parseFloat(keyword.cpc)) {
                                    cpc_color = 'tr_green';
                                }

                                if (keyword.broad == "") {
                                    broad_color = '';
                                } else if (parseFloat(cf_template.broad_red) < parseFloat(keyword.broad)) {
                                    broad_color = 'tr_red';
                                } else if (parseFloat(cf_template.broad_red) > parseFloat(keyword.broad) && parseFloat(cf_template.broad_green) < parseFloat(keyword.broad)) {
                                    broad_color = 'tr_yellow';
                                } else if (parseFloat(cf_template.broad_green) > parseFloat(keyword.broad)) {
                                    broad_color = 'tr_green';
                                }

                                if (keyword.phrase == "") {
                                    phrase_color = '';
                                } else if (parseFloat(cf_template.phrase_red) < parseFloat(keyword.phrase)) {
                                    phrase_color = 'tr_red';
                                } else if (parseFloat(cf_template.phrase_red) > parseFloat(keyword.phrase) && parseFloat(cf_template.phrase_green) < parseFloat(keyword.phrase)) {
                                    phrase_color = 'tr_yellow';
                                } else if (parseFloat(cf_template.phrase_green) > parseFloat(keyword.phrase)) {
                                    phrase_color = 'tr_green';
                                }

                                if (keyword.intitle == "") {
                                    intitle_color = '';
                                } else if (parseFloat(cf_template.intitle_red) < parseFloat(keyword.intitle)) {
                                    intitle_color = 'tr_red';
                                } else if (parseFloat(cf_template.intitle_red) > parseFloat(keyword.intitle) && parseFloat(cf_template.intitle_green) < parseFloat(keyword.intitle)) {
                                    intitle_color = 'tr_yellow';
                                } else if (parseFloat(cf_template.intitle_green) > parseFloat(keyword.intitle)) {
                                    intitle_color = 'tr_green';
                                }

                                if (keyword.inurl == "") {
                                    inurl_color = '';
                                } else if (parseFloat(cf_template.inurl_red) < parseFloat(keyword.inurl)) {
                                    inurl_color = 'tr_red';
                                } else if (parseFloat(cf_template.inurl_red) > parseFloat(keyword.inurl) && parseFloat(cf_template.inurl_green) < parseFloat(keyword.inurl)) {
                                    inurl_color = 'tr_yellow';
                                } else if (parseFloat(cf_template.inurl_green) > parseFloat(keyword.inurl)) {
                                    inurl_color = 'tr_green';
                                }

                                /**
                                 *
                                 *     CONDITIONAL FORMATTING
                                 *
                                 */


                                var tr = $('<tr data-queued="'+keyword.queued+'" data-id="'+keyword.id+'"></tr>');
                                tr.append('<td><div class="drag-cursor"><i class="fa fa-ellipsis-v" aria-hidden="true" style="margin-right: 1px;"></i><i class="fa fa-ellipsis-v" aria-hidden="true"></i></div> <input type="checkbox" class="keyword-selection" value="'+keyword.id+'" name="keywords[]" /></td>');
                                tr.append('<td><div contenteditable="true" class="keywordInput" data-target="keyword">' + keyword.keyword + '</div></td>');
                                tr.append('<td class="'+volume_color+'"><div contenteditable="true" class="keywordInput" data-target="volume">' + actions.parseNumber(keyword.volume) + '</div></td>');
                                tr.append('<td class="'+cpc_color+'"><div contenteditable="true" class="keywordInput" data-target="cpc">' + keyword.cpc + '</div></td>');

                                if (keyword.queued == 1) {
                                    tr.append('<td data-target="broad" class="uk-text-center" title="This value is currently under analysis. Please check back later to see the results."><i class="fa fa-refresh fa-spin"></i></td>');
                                    tr.append('<td data-target="phrase" class="uk-text-center" title="This value is currently under analysis. Please check back later to see the results."><i class="fa fa-refresh fa-spin"></i></td>');
                                    tr.append('<td data-target="intitle" class="uk-text-center" title="This value is currently under analysis. Please check back later to see the results."><i class="fa fa-refresh fa-spin"></i></td>');
                                    tr.append('<td data-target="inurl" class="uk-text-center" title="This value is currently under analysis. Please check back later to see the results."><i class="fa fa-refresh fa-spin"></i></td>');
                                } else {
                                    tr.append('<td data-target="broad" class="'+broad_color+'"><div contenteditable="true" class="keywordInput" data-target="broad">' + actions.parseNumber(keyword.broad) + '</div></td>');
                                    tr.append('<td data-target="phrase" class="'+phrase_color+'"><div contenteditable="true" class="keywordInput" data-target="phrase">' + actions.parseNumber(keyword.phrase) + '</div></td>');
                                    tr.append('<td data-target="intitle" class="'+intitle_color+'"><div contenteditable="true" class="keywordInput" data-target="intitle">' + actions.parseNumber(keyword.intitle) + '</div></td>');
                                    tr.append('<td data-target="inurl" class="'+inurl_color+'"><div contenteditable="true" class="keywordInput" data-target="inurl">' + actions.parseNumber(keyword.inurl) + '</div></td>');
                                }

                                kwData.append(tr);
                            }
                        }

                        data.append(template);
                    }

                } else {
                    project_empty.show();
                    project_groups.hide();
                }

                projects_table.slideUp("fast", function(){
                    actions.updateElements();
                    actions.updateGrid();
                });
                project_dashboard.slideDown();

            });
        },
        loadProject: function () {
            $(document).on('click', '.load_project', function (e) {
                e.preventDefault();
                currentProjectID   = $(this).data('id');
                currentProjectName = $(this).data('name');

                actions.importKeywordPlanner();
                actions.loadProjectManually();
            });
        },
        backToProjects: function(){
            $(document).on('click', '.closeProject', function (e) {
                var project_dashboard = $('.project-dashboard');
                var projects_table    = $('.projects-table');

                projects_table.slideDown();
                project_dashboard.slideUp();
                currentProjectID = 0;
            });
        },
        renameProject: function(){
            $(document).on('click', '.rename_project', function(e){
                e.preventDefault();

                let project_id   = $(this).data('id');
                let project_name = $(this).data('name');

                UIkit.modal.prompt("New Project Name:", project_name, function (projectName) {
                    if (projectName == '') {
                        UIkit.modal.alert("Project Name cannot be empty!");
                    } else {
                        $.post(prs_data.wp_post, 'action=prs_rename_project&project_id='+project_id+'&project_name=' + projectName, function(d){
                            UIkit.notify("<i class='uk-icon-check'></i> Project '"+project_name+"' has been renamed to '"+projectName+"'", {pos:'bottom-right', status:"success"});
                            actions.loadProjects();
                        },'json');
                    }
                });
            });
        },
        removeProject: function () {
            $(document).on('click', '.remove_project', function (e) {
                e.preventDefault();

                var id = $(this).data('id');

                UIkit.modal.confirm("Are you sure that you want to remove this project permanently?", function () {
                    $.post(prs_data.wp_post, 'action=prs_remove_project&project_id=' + id, function(d){
                        UIkit.notify("<i class='uk-icon-check'></i> Project has been removed.", {pos:'bottom-right', status:"success"});
                        actions.loadProjects();
                    },'json');
                });
            });
        },
        loadProjects: function () {
            $('.pTable').dataTable({
                "dom": '<"actions"><"top"lf>rt<"bottom"ip><"actions"><"clear">',
                "bDestroy": true,
                "bPaginate": true,
                "bAutoWidth": false,
                "bFilter": true,
                "sServerMethod": "POST",
                "sAjaxSource": prs_data.wp_post,
                "iDisplayLength": 10,
                "language" : {
                    "emptyTable": "<a href='#' class='uk-button uk-button-success new-project' style='margin: 15px;'><i class='fa fa-plus'></i> Create My First Project</a>" +
                                  "<a href='#importProject' data-uk-modal class='uk-button uk-button-success'><i class='fa fa-download'></i> Import Existing Project</a>"
                },
                "aLengthMenu": [[5, 10, 50, 100, -1], [5, 10, 50, 100, "All"]],
                "aaSorting": [[0, 'desc']],
                "aoColumns": [
                    {
                        "sClass": "text-left",
                        "bSortable": true,
                        "mData": "id",
                        "mRender": function (data, type, row) {
                            return data;
                        }
                    },
                    {
                        "sClass": "text-left",
                        "bSortable": true,
                        "mData": "project_name",
                        "mRender": function (data, type, row) {
                            return "<i title='Rename Project' class='fa fa-edit rename_project' data-id='"+row.id+"' data-name='"+data+"'></i> <b>" + data + "</b>";
                        }
                    },
                    {
                        "sClass": "text-left",
                        "bSortable": true,
                        "mData": "date_created",
                        "mRender": function (data, type, row) {
                            return new Date(data).toDateString();
                        }
                    },
                    {
                        "sClass": "text-left",
                        "bSortable": false,
                        "mRender": function (data, type, row) {
                            var buttons = '<button data-name="'+row.project_name+'" data-id="'+row.id+'" title="Load this project" type="button" class="uk-button uk-button-primary uk-button-mini load_project"><i class="fa fa-upload"></i> Load</button> ';
                            buttons += '<button data-id="'+row.id+'" title="Export this project" type="button" class="uk-button uk-button-success uk-button-mini export_project"><i class="fa fa-download"></i> Export</button> ';
                            buttons += '<button data-id="'+row.id+'" title="Remove this project permanently" type="button" class="uk-button uk-button-danger uk-button-mini remove_project"><i class="fa fa-trash-o"></i> Remove</button> ';
                            return buttons;
                        }
                    }
                ],
                "fnServerParams": function (aoData) {
                    aoData.push({
                        name: 'action',
                        value: 'prs_get_projects'
                    });
                }
            });
        },

        /*Export Import Projects*/
        exportProject: function () {
            $(document).on('click', '.export_project', function () {
                var project_id = $(this).attr('data-id');
                window.location = prs_data.wp_post + '?action=prs_export_project&project_id=' + project_id;
            })
        },
        importProject: function () {
            $('#importProject').uploader(
                'action=prs_import_project',
                'csv',
                actions.loadProjects
            );
        },
        importKeywordPlanner: function () {
            $('#importKeywordPlanner').uploader(
                'action=prs_import_keyword_planner&project=' + currentProjectID,
                'csv',
                actions.loadProjectManually
            );
        },
        createPagePostMulti: function () {
            $(document).on('click', '.createPagesPosts', function (e) {
                e.preventDefault();

                var table = $('.pagePostAllTableTemplate.uk-hidden').clone().removeClass('uk-hidden');
                var tr = table.find('.tr_template');
                var body = table.find('.body_template').html('');

                table.find('.body_template').html('');
                $('.project-groups .updateGroup').each(function () {

                    var group_name = $(this).find('input[name="group_name"]').val();
                    var group_id = $(this).find('input[name="group_id"]').val();
                    tr.find('.group_name').html(group_name).attr('data-id', group_id);
                    body.append('<tr>' + tr.html() + '</tr>');

                });

                var mod = $('#pagePostMulti');
                mod.find('.table_holder_all').html(table);
                UIkit.modal(mod, {bgclose: false, keyboard:false}).show();
            });

            $(document).on('click', '.pagePostMultiBtn', function (e) {
                e.preventDefault();

                var form = $(this).parents('#pagePostMulti');
                var table = form.find('.pagePostAllTableTemplate');
                var tr = table.find('.body_template tr');

                table.find('.createMultiResults').html('<i class="fa fa-gear fa-spin fa-2x"></i>');

                tr.each(function () {
                    var current_tr = $(this);

                    var group_id = current_tr.find('.group_name').attr('data-id');
                    var type = current_tr.find('button[aria-checked="true"]').attr('data-type');

                    var data = {
                        action: 'prs_create_page_post',
                        group_id: group_id,
                        type: type
                    };

                    $.ajaxq ("pagePostMulti", {
                        url: prs_data.wp_post,
                        type: 'post',
                        data: data,
                        cache: false,
                        success: function(d) {

                            var group_id = d.group_id;

                            var icon = '';
                            var info_class = '';
                            if(d.status == 'error'){
                                icon = '<i class="fa fa-warning uk-text-warning"></i>';
                                info_class = 'tr_danger';
                            }

                            var url = '';
                            if(d.status == 'success'){
                                icon = '<i class="fa fa-check uk-text-success"></i>';
                                url  = '<br><a href="'+d.data+'" target="_blank">' + d.data + '</a>';
                                info_class = 'tr_check';
                            }
                            if(d.status == 'warning'){
                                icon = '<i class="fa fa-warning uk-text-warning"></i>';
                                url  = '<br><a href="'+d.data+'" target="_blank">' + d.data + '</a>';
                                info_class = 'tr_danger';
                            }

                            $('td[data-id="'+group_id+'"]').parents('tr').addClass(info_class).find('.createMultiResults').html(icon + ' ' +d.message + url );
                        }
                    });
                });
            });

            $(document).on('click', 'div[data-uk-button-radio] button[aria-checked]', function (e) {
                e.preventDefault();
            });
        },
        calculateAndTrim : function(t) {
            let words_split = [];
            for ( let i = 0; i < t.length; i++) {
                words_split.push(t[i].split(' '));
            }
            words_split = [].concat.apply([], words_split);
            let words = [];

            for (let i = 0; i < words_split.length; i++) {
                let check = 0;
                let final = {
                    text: '',
                    weight: 0,
                    html: {
                        title: 0,
                        'data-uk-tooltip': ''
                    },
                    handlers: {
                        click: function (e) {
                            $(this).parents('.uk-panel-box').children('.updateKeywords').find('.keywordInput[data-target="keyword"]').unhighlight().highlight($(this).text());
                            $(this).parents('.cloud.template.seen.jqcloud').children('span').removeClass('highlightWordInCloud');
                            $(this).addClass('highlightWordInCloud');
                        }
                    }
                };
                for (let j = 0; j < words.length; j++) {
                    if (words_split[i] === words[j].text) {
                        check = 1;
                        ++words[j].weight;
                        ++words[j].html.title;
                    }
                }
                if (check === 0) {
                    final.text = words_split[i];
                    final.weight = 1;
                    final.html.title = 1;
                    words.push(final);
                }
                check = 0;
            }

            return words;
        },
        formatSEO: function (t) {
            $(document).on('change', '.prs-title', function (e) {
                $(this).prev('input').val($(this).text());

                let wordCount = $(this).html().replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().length;

                if ( wordCount > 70 ) {
                    $(this).parents('td').find('.count-seo-title').html('<span style="color:red">' + wordCount + '</span>');
                } else {
                    $(this).parents('td').find('.count-seo-title').html( wordCount );
                }

                if ( wordCount > 78 ) {
                    $(this).parents('td').find('.count-seo-title-mobile').html('<span style="color:red">' + wordCount + '</span>');
                } else {
                    $(this).parents('td').find('.count-seo-title-mobile').html( wordCount );
                }

            });

            $(document).on('change', '.prs-description', function (e) {
                $(this).prev('input').val($(this).text());

                let wordCount = $(this).html().replace(/\&nbsp\;/g, ' ').replace(/\s+/g,' ').trim().length;

                if ( wordCount > 300 ) {
                    $(this).parents('td').find('.count-seo-description').html('<span style="color:red">' + wordCount + '</span>');
                } else {
                    $(this).parents('td').find('.count-seo-description').html( wordCount );
                }

                if ( wordCount > 120 ) {
                    $(this).parents('td').find('.count-seo-description-mobile').html('<span style="color:red">' + wordCount + '</span>');
                } else {
                    $(this).parents('td').find('.count-seo-description-mobile').html( wordCount );
                }
            });
        },


    };

})(jQuery);
