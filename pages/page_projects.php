<?php
// Set the domain name
$domain = parse_url(admin_url());
$domain = $domain['host'];
$domain = str_replace('www.', '', $domain);
?>

<!-- HTML STARTS HERE -->
<script>
    var domain = "<?php echo $domain ?>";
</script>
<div class="wrap prs">

    <h2 class="logo-title">
        <img class="logo-image" src="<?= PRS_URL; ?>/assets/img/logo.png"/>
        Project Supremacy - Projects
        -
        <small class="hand">Manage your projects...</small>

	    <!-- This is the container enabling the JavaScript -->
	    <div class="uk-button-dropdown settings-button" data-uk-dropdown="{mode:'click'}">

		    <!-- This is the button toggling the dropdown -->
		    <button class="uk-button uk-button-success"><i class="fa fa-gears"></i> Manage <i class="fa fa-caret-down"></i></button>

		    <!-- This is the dropdown -->
		    <div class="uk-dropdown uk-dropdown-small">
			    <ul class="uk-nav uk-nav-dropdown">
				    <li><a href="#" class="new-project uk-dropdown-close"><i class="fa fa-plus"></i> Create a new Project</a></li>
				    <li><a href="#importProject" data-uk-modal class="uk-dropdown-close"><i class="fa fa-download"></i> Import existing Project</a></li>
				    <li class="uk-nav-divider"></li>
				    <li><a href="#conditional-formatting" data-uk-modal class="uk-dropdown-close"><i class="fa fa-paint-brush"></i> Conditional Formatting</a></li>
				    <li class="uk-nav-divider"></li>
				    <li><a href="#redirects" data-uk-modal class="uk-dropdown-close"><i class="fa fa-refresh"></i> Manage Redirects</a></li>
			    </ul>
		    </div>

	    </div>
    </h2>

	<p class="logo-paragraph">
		<b><i class="fa fa-question-circle"></i> What should you do here?</b> Here you can maintain your Project Supremacy projects.
	</p>

    <div class="cloud template hide" style="display: none;"></div>

	<!-- Projects Table -->
	<div class="projects-table">
		<table class="uk-table uk-table-hover uk-table-striped pTable" cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><i class="fa fa-reorder"></i> ID</th>
				<th><i class="fa fa-folder"></i> Project Name</th>
				<th><i class="fa fa-clock-o"></i> Created Date</th>
				<th><i class="fa fa-gears"></i> Actions</th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th><i class="fa fa-reorder"></i> ID</th>
				<th><i class="fa fa-folder"></i> Project Name</th>
				<th><i class="fa fa-clock-o"></i> Created Date</th>
				<th><i class="fa fa-gears"></i> Actions</th>
			</tr>
			</tfoot>
			<tbody>

			</tbody>
		</table>
	</div>

	<!-- Project Dashboard -->
	<div class="project-dashboard" style="display: none">
		<h1 class="project-name">
			[Project Name]
		</h1>
		<div class="project-actions">

            <div class="uk-float-left">

                <!-- Go back to Projects -->
                <button class="uk-button closeProject"><i class="fa fa-arrow-left"></i> Back to Projects</button>

                <!-- Project Actions -->
                <div class="uk-button-dropdown actions-button" data-uk-dropdown="{mode:'click'}">

                    <!-- This is the button toggling the dropdown -->
                    <button class="uk-button uk-button-success proj_management_actions"><i class="fa fa-gears"></i> Actions <i class="fa fa-caret-down"></i></button>

                    <!-- This is the dropdown -->
                    <div class="uk-dropdown uk-dropdown-small">
                        <ul class="uk-nav uk-nav-dropdown">
                            <li><a href="#" class="addGroup uk-dropdown-close"><i class="fa fa-plus"></i> Add a new Group</a></li>
                            <li><a href="#" class="addGroupFromExisting uk-dropdown-close"><i class="fa fa-plus"></i> Create Group(s) from existing Page/Post</a></li>
                            <li class="uk-nav-divider"></li>
                            <li><a href="#" class="deleteGroups uk-dropdown-close"><i class="fa fa-trash-o"></i> Delete selected Groups</a></li>
                            <li class="uk-nav-divider"></li>
                            <li><a href="#importKeywordPlanner" data-uk-modal class="uk-dropdown-close"><i class="fa fa-download"></i> Import Groups from Keyword Planner CSV</a></li>
                            <li class="uk-nav-divider"></li>
                            <li><a href="#" class="createPagesPosts uk-dropdown-close"><i class="fa fa-bolt"></i> Create Pages/Posts from current Groups</a></li>
                            <li class="uk-nav-divider"></li>
                            <li><a href="#" class="expandKeywordGroups uk-dropdown-close"><i class="fa fa-expand"></i> Expand Keyword Groups</a></li>
                            <li><a href="#" class="collapseKeywordGroups uk-dropdown-close"><i class="fa fa-compress"></i> Collapse Keyword Groups</a></li>
                            <li><a href="#" class="expandAllGroups uk-dropdown-close"><i class="fa fa-expand"></i> Expand All</a></li>
                            <li><a href="#" class="collapseAllGroups uk-dropdown-close"><i class="fa fa-compress"></i> Collapse All</a></li>
                        </ul>
                    </div>

                </div>

            </div>

            <!-- Filter by Post Types -->
            <div class="uk-float-right">

                <div class="uk-button uk-form-select" data-uk-form-select>
                    <span><i class="fa fa-folder-open-o"></i> Filter by Post Type</span>
                    <select id="filterPostTypes">
                        <option value="">All Types</option>
                    </select>
                </div>

            </div>

            <div class="uk-clearfix"></div>

		</div>

		<div class="project-groups">


            <ul id="groupSort" class="uk-subnav uk-subnav-pill">
                <li class="uk-active" data-uk-sort="name"><a href="#">Ascending</a></li>
                <li data-uk-sort="name:desc"><a href="#">Descending</a></li>
            </ul>

			<div class="uk-grid-width-small-1-1 uk-grid-width-medium-1-2 uk-grid-width-medium-1-2 data" data-uk-grid="{gutter: 20, controls: '#groupSort'}">

			</div>

		</div>

		<div class="project-empty" style="display: none">
			<h3><i class="fa fa-warning"></i> No created groups</h3>
			<p>To get started, please <button class="uk-button uk-button-primary uk-button-mini addGroup"><i class="fa fa-plus"></i> Add a Group</button> to this project.</p>
		</div>
	</div>

	<!-- Redirects -->
	<div id="redirects" class="uk-modal">
		<div class="uk-modal-dialog uk-modal-lg">
			<button type="button" class="uk-modal-close uk-close"></button>
			<div class="uk-modal-header">
				<h2><i class="fa fa-refresh"></i> Manage Redirects</h2>
			</div>

			<table class="uk-table uk-table-hover table-redirects">
				<thead>
					<tr>
						<th><i class="fa fa-globe"></i> URL</th>
						<th><i class="fa fa-refresh"></i> Redirects to</th>
						<th><i class="fa fa-gears"></i></th>
					</tr>
				</thead>
				<tbody>
					<tr><td colspan="4">Can't find any active redirects.</td></tr>
				</tbody>
				<tfoot>
					<tr>
						<th><i class="fa fa-globe"></i> URL</th>
						<th><i class="fa fa-refresh"></i> Redirects to</th>
						<th><i class="fa fa-gears"></i></th>
					</tr>
				</tfoot>
			</table>

			<div class="uk-modal-footer uk-text-right">
				<button type="button" class="uk-button uk-button-success add-new-redirect"><i class="fa fa-plus"></i> Add New Redirect</button>
				<button type="button" class="uk-button uk-modal-close">Cancel</button>
			</div>
		</div>
	</div>

    <!-- addGroupFromExisting -->
    <div id="addGroupFromExistingModal" class="uk-modal">
        <div class="uk-modal-dialog uk-modal-no-padding">
            <button type="button" class="uk-modal-close uk-close"></button>

            <div class="uk-modal-header">
                <h2><i class="fa fa-refresh"></i> Create Group(s) From Existing Pages/Posts</h2>
                <p><i class="fa fa-info-circle"></i>
                    Select posts/pages you want to create groups from and press Create Groups.
                </p>
            </div>

            <table class="wp-list-table widefat fixed striped postsTable2" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td class="check-column"><input class="select-posts-all" type="checkbox"></td>
                    <th style="width: 40px">ID</th>
                    <th>Title</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="4" class="uk-text-center"><i class="fa fa-spin fa-refresh"></i> Loading</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td class="check-column"><input class="select-posts-all" type="checkbox"></td>
                    <th style="width: 40px">ID</th>
                    <th>Title</th>
                    <th>Date</th>
                </tr>
                </tfoot>
            </table>

            <div class="uk-modal-footer">
                <div class="uk-float-left">
                    <button style="margin-top: 10px;" type="button" class="uk-button uk-modal-close"><i class="fa fa-close"></i> Close</button>
                </div>
                <div class="uk-float-right">
                    <span class="selected-posts"><b class="value">0</b> entries selected</span>
                    <button type="submit" class="uk-button uk-button-success add-group-from-existing"><i class="fa fa-plus"></i> Create Groups from Selected</button>
                </div>
                <div class="uk-clearfix"></div>
            </div>

        </div>

    </div>

	<!-- Conditional Formatting Modal -->
	<div id="conditional-formatting" class="uk-modal">
		<div class="uk-modal-dialog">
			<button type="button" class="uk-modal-close uk-close"></button>
			<div class="uk-modal-header">
				<h2><i class="fa fa-paint-brush"></i> Conditional Formatting Setup</h2>
			</div>

			<div class="uk-grid">

				<div class="uk-grid">
					<div class="uk-width-medium-3-10">

						<ul class="uk-nav uk-nav-side" data-uk-switcher="{connect:'#conditional-formatting-local',animation: 'fade'}">
							<li><a href="#">Search Volume</a></li>
							<li><a href="#">Cost per Click</a></li>
							<li><a href="#">Broad Results</a></li>
							<li><a href="#">Phrase Results</a></li>
							<li><a href="#">InTitle Results</a></li>
							<li><a href="#">InURL Results</a></li>
						</ul>

						<div class="cf-templates">
							<label for="cf-templates">Templates:</label>
							<select class="uk-width-1-1" id="cf-templates">
								<option value="new">---</option>
								<option value="Default">Default</option>
							</select>
						</div>
					</div>
					<div class="uk-width-medium-6-10">
						<div>
							<form class="uk-form" id="conditional-formatting-local-form">
								<ul id="conditional-formatting-local" class="uk-switcher">
									<!-- Search Volume -->
									<li>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-danger"><i class="fa fa-close"></i></div>
											<h3 class="uk-panel-title">RED</h3>
											below
											<input type="number" pattern="[0-9]" name="volume_red" id="volume_red" class="uk-width-1-1" placeholder="eg. 20" required>
										</div>
										<hr>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-warning"><i class="fa fa-warning"></i></div>
											<h3 class="uk-panel-title">YELLOW</h3>
											between
											<input type="number" readonly class="uk-width-1-1 volume_yellow_1 volume_red" placeholder="eg. 20">
											and
											<input type="number" readonly class="uk-width-1-1 volume_yellow_2 volume_green" placeholder="eg. 100">
										</div>
										<hr>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-success"><i class="fa fa-check"></i></div>
											<h3 class="uk-panel-title">GREEN</h3>
											from
											<input type="number" pattern="[0-9]" name="volume_green" id="volume_green" class="uk-width-1-1" placeholder="eg. 100">
										</div>
									</li>
									<!-- Cost Per Click -->
									<li>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-danger"><i class="fa fa-close"></i></div>
											<h3 class="uk-panel-title">RED</h3>
											below
											<input type="number" pattern="[0-9]" name="cpc_red" id="cpc_red" class="uk-width-1-1" placeholder="eg. 20">
										</div>
										<hr>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-warning"><i class="fa fa-warning"></i></div>
											<h3 class="uk-panel-title">YELLOW</h3>
											between
											<input type="number" readonly class="uk-width-1-1 cpc_yellow_1 cpc_red" placeholder="eg. 20">
											and
											<input type="number" readonly class="uk-width-1-1 cpc_yellow_2 cpc_green" placeholder="eg. 100">
										</div>
										<hr>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-success"><i class="fa fa-check"></i></div>
											<h3 class="uk-panel-title">GREEN</h3>
											from
											<input type="number" pattern="[0-9]" name="cpc_green" id="cpc_green" class="uk-width-1-1" placeholder="eg. 100">
										</div>
									</li>
									<!-- Broad Results -->
									<li>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-danger"><i class="fa fa-close"></i></div>
											<h3 class="uk-panel-title">RED</h3>
											above
											<input type="number" pattern="[0-9]" name="broad_red" id="broad_red" class="uk-width-1-1" placeholder="eg. 20">
										</div>
										<hr>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-warning"><i class="fa fa-warning"></i></div>
											<h3 class="uk-panel-title">YELLOW</h3>
											between
											<input type="number" readonly class="uk-width-1-1 broad_yellow_1 broad_red" placeholder="eg. 20">
											and
											<input type="number" readonly class="uk-width-1-1 broad_yellow_2 broad_green" placeholder="eg. 100">
										</div>
										<hr>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-success"><i class="fa fa-check"></i></div>
											<h3 class="uk-panel-title">GREEN</h3>
											below
											<input type="number" pattern="[0-9]" name="broad_green" id="broad_green" class="uk-width-1-1" placeholder="eg. 100">
										</div>
									</li>
									<!-- Phrase Results -->
									<li>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-danger"><i class="fa fa-close"></i></div>
											<h3 class="uk-panel-title">RED</h3>
											above
											<input type="number" pattern="[0-9]" name="phrase_red" id="phrase_red" class="uk-width-1-1" placeholder="eg. 20">
										</div>
										<hr>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-warning"><i class="fa fa-warning"></i></div>
											<h3 class="uk-panel-title">YELLOW</h3>
											between
											<input type="number" readonly class="uk-width-1-1 phrase_yellow_1 phrase_red" placeholder="eg. 20">
											and
											<input type="number" readonly class="uk-width-1-1 phrase_yellow_2 phrase_green" placeholder="eg. 100">
										</div>
										<hr>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-success"><i class="fa fa-check"></i></div>
											<h3 class="uk-panel-title">GREEN</h3>
											below
											<input type="number" pattern="[0-9]" name="phrase_green" id="phrase_green" class="uk-width-1-1" placeholder="eg. 100">
										</div>
									</li>
									<!-- InTitle Results -->
									<li>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-danger"><i class="fa fa-close"></i></div>
											<h3 class="uk-panel-title">RED</h3>
											above
											<input type="number" pattern="[0-9]" name="intitle_red" id="intitle_red" class="uk-width-1-1" placeholder="eg. 20">
										</div>
										<hr>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-warning"><i class="fa fa-warning"></i></div>
											<h3 class="uk-panel-title">YELLOW</h3>
											between
											<input type="number" readonly class="uk-width-1-1 intitle_yellow_1 intitle_red" placeholder="eg. 20">
											and
											<input type="number" readonly class="uk-width-1-1 intitle_yellow_2 intitle_green" placeholder="eg. 100">
										</div>
										<hr>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-success"><i class="fa fa-check"></i></div>
											<h3 class="uk-panel-title">GREEN</h3>
											below
											<input type="number" pattern="[0-9]" name="intitle_green" id="intitle_green" class="uk-width-1-1" placeholder="eg. 100">
										</div>
									</li>
									<!-- InURL Results -->
									<li>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-danger"><i class="fa fa-close"></i></div>
											<h3 class="uk-panel-title">RED</h3>
											above
											<input type="number" pattern="[0-9]" name="inurl_red" id="inurl_red" class="uk-width-1-1" placeholder="eg. 20">
										</div>
										<hr>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-warning"><i class="fa fa-warning"></i></div>
											<h3 class="uk-panel-title">YELLOW</h3>
											between
											<input type="number" readonly class="uk-width-1-1 inurl_yellow_1 inurl_red" placeholder="eg. 20">
											and
											<input type="number" readonly class="uk-width-1-1 inurl_yellow_2 inurl_green" placeholder="eg. 100">
										</div>
										<hr>
										<div class="uk-panel">
											<div class="uk-panel-badge uk-badge uk-badge-success"><i class="fa fa-check"></i></div>
											<h3 class="uk-panel-title">GREEN</h3>
											below
											<input type="number" pattern="[0-9]" name="inurl_green" id="inurl_green" class="uk-width-1-1" placeholder="eg. 100">
										</div>
									</li>
								</ul>
							</form>
						</div>
					</div>
				</div>
			</div>

			<div class="uk-modal-footer uk-text-right">
				<button type="button" class="uk-button uk-button-danger" id="deleteCfTemplate" title="Delete Template"><i class="fa fa-trash"></i></button>
				<button type="button" class="uk-button uk-button-primary" id="saveCfTemplate"><i class="fa fa-save"></i> Save Template</button>
				<button type="button" class="uk-button uk-button-success" id="applyCfTemplate"><i class="fa fa-check"></i> Apply Template</button>
			</div>
		</div>
	</div>

	<!-- Templates -->
	<div data-name="Group Name" class="group template" data-post-type="">

		<div class="uk-panel-box">

            <button type="button" class="uk-button uk-button-mini wordCloud" data-uk-tooltip title="Group Word Cloud"><i class="fa fa-cloud"></i></button>
			<!-- Minimize -->
			<button type="button" class="uk-button uk-button-mini minimizeGroup"><i class="fa fa-chevron-up"></i></button>

			<!-- Group Settings -->
			<form class="updateGroup">
				<input type="hidden" name="action" value="prs_updateGroup"/>
				<input type="hidden" name="group_id" value="0"/>
				<input type="hidden" name="project_id" value="0"/>
				<input type="hidden" name="request_type" value="single"/>
				<input type="hidden" name="oriUrl" value=""/>
				<table class="uk-table groupSettings">
					<thead>
					<tr>
						<td>
							<!-- Settings Button -->
							<button type="button" class="uk-button uk-button-mini editGroupSettings"><i class="fa fa-gear"></i></button>

							<!-- Actions Button -->
							<div class="uk-button-dropdown groupSettings-button" data-uk-dropdown="{mode:'click'}">

								<button type="button" class="uk-button uk-button-mini groupSettings"><i class="fa fa-bolt"></i></button>

								<div class="uk-dropdown uk-dropdown-small">
									<ul class="uk-nav uk-nav-dropdown">

                                        <li class="uk-nav-header">
                                            <i class="fa fa-search"></i> KEYWORD MANAGEMENT
                                        </li>

                                        <li><a href="#" class="addKeyword uk-dropdown-close"><i class="fa fa-plus"></i> Add new Keyword(s)</a></li>
                                        <li><a href="#" class="deleteKeywords uk-dropdown-close"><i class="fa fa-trash-o"></i> Delete Keyword(s)</a></li>

                                        <li class="uk-nav-header">
                                            <i class="fa fa-folder-open-o"></i> GROUP MANAGEMENT
                                        </li>

										<li><a href="#" class="createNewPagePost uk-dropdown-close" data-type="page"><i class="fa fa-file-code-o"></i> Create New Page From Group</a></li>
										<li><a href="#" class="createNewPagePost uk-dropdown-close" data-type="post"><i class="fa fa-file-code-o"></i> Create New Post From Group</a></li>
                                        <li><a href="#" class="attachToPagePost uk-dropdown-close"><i class="fa fa-bullseye"></i> Attach to Page/Post</a></li>
										<li><a href="#" class="goToPagePost uk-dropdown-close" target="_blank"><i class="fa fa-arrow-right"></i> Go to attached Page/Post</a></li>
                                        <li><a href="#" class="moveToProject uk-dropdown-close" target="_blank"><i class="fa fa-copy"></i> Move to a different Project</a></li>
                                        <li><a href="#" class="deleteGroup uk-dropdown-close"><i class="fa fa-trash-o"></i> Delete Group</a></li>
									</ul>
								</div>

							</div>

							<!-- Save Button -->
							<button type="submit" class="uk-button uk-button-success uk-button-mini saveGroup"><i class="fa fa-save"></i></button>

                            <!-- Bulk Manage Groups -->
                            <input type="checkbox" class="groupSelect"/>

                        </td>
						<td>
							<!-- Group Name -->
							<input type="text" class="groupInput" placeholder="eg. My Group" value="" name="group_name"/>
						</td>
					</tr>
					</thead>

					<tbody class="groupSettingsTbody" style="display: none;">


                    <tr>
                        <td colspan="2"  style="padding: 0px 0px 0px 2px;">
                            <div class="tab-box displayed uk-grid uk-grid-collapse">

                                <div class="uk-width-large-1-1 uk-width-medium-1-1 uk-width-small-1-1">
                                    <div class="prs-serp left">
                                        <!-- SEO Title -->
                                        <input type="hidden" name="title" class="groupInput" value=""/>
                                        <div class="prs-editor prs-title" data-target="title" contenteditable="true" placeholder="SEO Title"></div>

                                        <!-- SEO URL -->
                                        <div class="url-container">
                                            <input type="hidden" name="url" value=""/>
                                            <div class="prs-editor url-container-inner" contenteditable="false">
                                                <label class="host-url"><?php echo site_url();?> </label>
                                                <label class="pre-url"></label>
                                                <div spellcheck="false" class="url-edit" contenteditable="true"></div>
                                                <label class="post-url"></label>
                                            </div>
                                        </div>

                                        <!-- SEO Description -->
                                        <input type="hidden" name="description" class="groupInput" value=""/>
                                        <div class="prs-editor prs-description" data-target="description" contenteditable="true" placeholder="SEO Description"></div>
                                    </div>
                                </div>

                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2"  style="padding: 0px 0px 0px 2px;">

                            <div class="tab-box displayed uk-grid uk-grid-collapse">
                                <div class="uk-width-large-1-2 uk-width-medium-1-2 uk-width-small-1-1">
                                    <div class="prs-serp right">
                                        <label class="ps-label ps-label-block">Title Length <i class="fa fa-info-circle" data-uk-tooltip="" title="Displays the current number of characters in SEO Title."></i></label>
                                        <small class="ps-metric">
                                            <div class="count-seo-container-left">
                                                <i class="fa fa-desktop" data-uk-tooltip="" title="Desktop devices limits."></i>
                                                <span class="count-seo-bold"><span class="count-seo-title">0</span> / 70</span> chars.
                                            </div>
                                            <div class="count-seo-container-right">
                                                <i class="fa fa-mobile" data-uk-tooltip="" title="Mobile devices limits."></i>
                                                <span class="count-seo-bold"><span class="count-seo-title-mobile">0</span> / 78</span> chars.
                                            </div>
                                        </small>
                                    </div>
                                </div>

                                <div class="uk-width-large-1-2 uk-width-medium-1-2 uk-width-small-1-1">
                                    <div class="prs-serp right">
                                        <label class="ps-label ps-label-block">Description Length <i class="fa fa-info-circle" data-uk-tooltip="" title="Displays the current number of characters in SEO Description."></i></label>
                                        <small class="ps-metric">
                                            <div class="count-seo-container-left">
                                                <i class="fa fa-desktop" data-uk-tooltip="" title="Desktop devices limits."></i>
                                                <span class="count-seo-bold"><span class="count-seo-description">0</span> / 300</span> chars.
                                            </div>
                                            <div class="count-seo-container-right">
                                                <i class="fa fa-mobile" data-uk-tooltip="" title="Mobile devices limits."></i>
                                                <span class="count-seo-bold"><span class="count-seo-description-mobile">0</span> / 120</span> chars.
                                            </div>
                                        </small>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>

					<tr>
						<td>Page/Post H1</td>
						<td>
							<input type="text" class="groupInput" placeholder="eg. My Header" value="" name="h1"/>
						</td>
					</tr>
                    <tr>
                        <td>Notes</td>
                        <td>
                            <textarea rows="3" class="groupInput" placeholder="eg. Notes about this project" name="notes"></textarea>
                        </td>
                    </tr>
					</tbody>
				</table>
			</form>

			<!-- Keywords -->
			<form class="updateKeywords">
				<table class="uk-table uk-table-striped uk-table-hover keywords">
					<thead>
					<tr>
						<th class="select-all uk-text-center"><i class="fa fa-asterisk"></i></th>
						<th>Keyword</th>
						<th>Volume</th>
						<th>CPC ($)</th>
						<th>Broad</th>
						<th>Phrase</th>
						<th>inTitle</th>
						<th>inURL</th>
					</tr>
					</thead>
					<tbody class="keywords-data uk-sortable">
					<tr>
						<td colspan="9"><i class="fa fa-warning"></i> No added keywords yet... </td>
					</tr>
					</tbody>
				</table>
			</form>

		</div>
	</div>

    <!-- Add Keywords -->
    <div id="addKeywords" class="uk-modal">

        <div class="uk-modal-dialog">

            <div>
                <div class="uk-modal-content uk-form"><b>Insert keywords separated by a new line:</b></div>

                <div class="uk-margin-small-top uk-modal-content uk-form">
                    <p>
                        <textarea id="keywords-input" rows="6" class="uk-width-1-1"></textarea>
                    </p>
                </div>

                <div class="uk-modal-footer uk-text-right">
                    <button type="button" class="uk-button uk-modal-close"><i class="fa fa-close"></i> Cancel</button>
                    <button type="button" class="uk-button uk-button-primary add-keywords"><i class="fa fa-plus"></i> Add</button>
                </div>
            </div>

        </div>

    </div>

	<!-- Attach to Page/Post -->
	<div id="attachToPagePost" class="uk-modal">

		<input type="hidden" name="group_id" value="0"/>
		<input type="hidden" name="post_id" value="0"/>

		<div class="uk-modal-dialog uk-modal-no-padding">
			<button type="button" class="uk-modal-close uk-close"></button>

			<div class="uk-modal-header">
				<h2><i class="fa fa-bullseye"></i> Attach to Page/Post</h2>
				<p><i class="fa fa-info-circle"></i>
					Attach specified group to pre-existing Page or Post.
				</p>
			</div>

            <table class="wp-list-table widefat fixed striped postsTable" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th style="width: 40px">ID</th>
                    <th>Title</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="3" class="uk-text-center"><i class="fa fa-spin fa-refresh"></i> Loading</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th style="width: 40px">ID</th>
                    <th>Title</th>
                    <th>Date</th>
                </tr>
                </tfoot>
            </table>

			<div class="uk-modal-footer uk-text-left">
				<button type="button" class="uk-button uk-modal-close"><i class="fa fa-close"></i> Close</button>
			</div>

		</div>
	</div>

	<!-- Import Project Modal -->
	<div id="importProject" class="uk-modal">
		<div class="uk-modal-dialog">
			<button type="button" class="uk-modal-close uk-close"></button>

			<div class="uk-modal-header">
				<h2><i class="fa fa-cloud-download"></i> Project Importer</h2>
				<p><i class="fa fa-info-circle"></i>
					Import a previously exported project.
				</p>
			</div>

			<div class="uk-placeholder upload-drop">
				<i class="fa fa-cloud-upload"></i> Drag your CSV here or browse
				<a class="uk-form-file">
					by selecting a file
					<input class="file-import" name="file-import" type="file" required>
				</a>.
			</div>

			<div class="uk-progress uk-hidden">
				<div class="uk-progress-bar" style="width: 0%;">...</div>
			</div>
		</div>
	</div>

	<!-- Import Keyword Planner Modal -->
	<div id="importKeywordPlanner" class="uk-modal">
		<div class="uk-modal-dialog">
			<button type="button" class="uk-modal-close uk-close"></button>

			<div class="uk-modal-header">
				<h2><i class="fa fa-cloud-download"></i> Keyword Planner Importer</h2>
				<p><i class="fa fa-info-circle"></i>
					Import a CSV file from Keyword Planner.
				</p>
			</div>

			<div class="uk-placeholder upload-drop">
				<i class="fa fa-cloud-upload"></i> Drag your CSV here or browse
				<a class="uk-form-file">
					by selecting a file
					<input class="file-import" name="file-import" type="file" required>
				</a>.
			</div>

			<div class="uk-progress uk-hidden">
				<div class="uk-progress-bar" style="width: 0%;">...</div>
			</div>
		</div>
	</div>

    <!-- Move Group -->
    <div id="moveToProjectGroup" class="uk-modal">
        <div class="uk-modal-dialog uk-modal-dialog-small">
            <div class="uk-modal-header">
                <h2><i class="fa fa-copy"></i> Move Group to a Project</h2>
            </div>

            <div class="uk-grid">
                <form id="moveToProjectForm" class="uk-form uk-width-1-1">
                    <fieldset>
                        <div class="uk-form-row">
                            <label class="uk-form-label" for="moveToProjectInput"><i class="fa fa-folder"></i> Destination Project:</label>
                            <select id="moveToProjectInput" name="project_id" class="uk-width-1-1" style="margin-top: 4px" required>

                            </select>
                        </div>
                        <div class="uk-form-row" style="margin-top: 10px">
                            <button class="uk-button uk-button-success" type="submit"><i class="fa fa-check"></i> Move</button>
                            <button class="uk-button uk-modal-close" type="button" style="float: right"><i class="fa fa-close"></i> Cancel</button>
                        </div>
                    </fieldset>
                </form>

            </div>
        </div>
    </div>

	<!--New Page Post From ALL Modal-->
	<div id="pagePostMulti" class="uk-modal">
		<div class="uk-modal-dialog">
			<form id="pagePostMultiPost">
				<input type="hidden" name="request_type" value="multi"/>
				<h2 style="margin-top: 2px;text-align: center;">Create All Pages/Posts</h2>
				<div class="uk-overflow-container table_holder_all">

				</div>
				<div class="postMultiButtons">
					<button class="uk-button uk-button-success pagePostMultiBtn" type="submit"><i class="fa fa-check"></i> Create All</button>
					<button class="uk-button uk-modal-close" type="button" style="float: right"><i class="fa fa-close"></i> Cancel</button>
				</div>
			</form>
		</div>
	</div>

	<table class="uk-table uk-table-striped pagePostAllTableTemplate uk-hidden">
		<thead>
		<tr>
			<th class="uk-text-center">Group Name</th>
			<th class="uk-text-center">Page or Post</th>
		</tr>
		</thead>
		<tbody class="uk-text-center body_template">
		<tr class="tr_template">
			<td class="group_name" data-id=""></td>
			<td class="createMultiResults">
				<div data-uk-button-radio="">
					<button class="uk-button uk-button-success uk-active" aria-checked="true" data-type="page"><i class="fa fa-file-o"></i> Page</button>
					<button class="uk-button uk-button-primary" aria-checked="false" data-type="post"><i class="fa fa-file-text-o"></i> Post</button>
				</div>
			</td>
		</tr>
		</tbody>
	</table>

	<!--New Page Post Results Modal-->
	<div id="resultsPagePost" class="uk-modal">
		<div class="uk-modal-dialog">
			<button type="button" class="uk-modal-close uk-close"></button>

			<div class="uk-modal-header">
				<h2><i class="fa fa-info-circle"></i> Create Page/Post from Group</h2>
			</div>

			<p class="pagePostResultsMessage">
				Successfully created page/post. You can click below to edit page right away
			</p>
			<p class="edit_page_post_link"></p>

			<div class="uk-modal-footer uk-text-right">
				<button type="button" class="uk-button uk-modal-close">Close</button>
			</div>
		</div>
	</div>

	<div class="creating_block block_template">
		<h1 class="creating">Creating Page</h1>
		<p class="creating_please_wait">Please Wait ...</p>
		<div class="creating_gears">
			<i class="one fa fa-gear fa-spin fa-3x"></i>
			<i class="two fa fa-gear fa-spin fa-2x"></i>
			<i class="three fa fa-gear fa-spin fa-2x"></i>
		</div>
	</div>

</div> <!-- .wrap -->