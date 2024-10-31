<?php
    $id   = $tag->term_id;
    $tax  = $tag->taxonomy;
    $meta = get_option( $tax . '_' . $id);

    // Default Values
    $taxonomies  = get_option('ps_seo_taxonomies');
    $title       = '';
    $description = '';

    if (isset($taxonomies[$tax])) {
        $title       = MPRS_Seo::replaceVars($taxonomies[$tax]['title'], 0, array(
                '%%term_title%%' => $tag->name
        ));
	    $description = MPRS_Seo::replaceVars($taxonomies[$tax]['description'], 0, array(
	            '%%term_title%%' => $tag->name
	    ));
    }
?>

<tr class="form-field ps">
    <th colspan="2">
        <h2>Project Supremacy – On Page SEO</h2>
    </th>
</tr>

<input type="hidden" name="meta[taxonomy]" value="<?php echo $tax;?>"/>

<tr class="form-field ps">
    <th scope="row" valign="top"><label for="term_seo_title">SEO Title</label></th>
    <td>
        <input type="text" name="meta[term_seo_title]" placeholder="<?php echo $title; ?>" id="term_seo_title" size="40" value="<?php echo @$meta['term_seo_title'];?>">
        <p class="description">Title of taxonomy that appears on search engines.</p>

        <small class="ps-metric">

            <div class="count-seo-container-left">

                <i class="fa fa-desktop" data-uk-tooltip="" title="Desktop devices limits."></i>
                <span class="count-seo-bold">
                                    <span class="count-seo-title">0</span> / 70
                                </span>
                chars.

            </div>

            <div class="count-seo-container-right">

                <i class="fa fa-mobile" data-uk-tooltip="" title="Mobile devices limits."></i>
                <span class="count-seo-bold">
                                    <span class="count-seo-title-mobile">0</span> / 78
                                </span>
                chars.

            </div>

            <div class="clear"></div>

        </small>

    </td>
</tr>

<tr class="form-field ps">
    <th valign="top" scope="row"><label for="term_seo_description">SEO Description</label></th>
    <td>
        <textarea name="meta[term_seo_description]" placeholder="<?php echo $description; ?>" id="term_seo_description" rows="5" cols="40"><?php echo @$meta['term_seo_description'];?></textarea>
        <p class="description">Description of taxonomy that appears on search engines.</p>

        <small class="ps-metric">

            <div class="count-seo-container-left">

                <i class="fa fa-desktop" data-uk-tooltip="" title="Desktop devices limits."></i>
                <span class="count-seo-bold">
                                    <span class="count-seo-description">0</span> / 300
                                </span>
                chars.

            </div>

            <div class="count-seo-container-right">

                <i class="fa fa-mobile" data-uk-tooltip="" title="Mobile devices limits."></i>
                <span class="count-seo-bold">
                                    <span class="count-seo-description-mobile">0</span> / 120
                                </span>
                chars.

            </div>

            <div class="clear"></div>

        </small>

    </td>
</tr>

<tr class="form-field ps">
    <th valign="top" scope="row"><label>Don't Index & Follow</label></th>
    <td>
        <label for="term_seo_nofollow">
            <input type="hidden" name="meta[term_seo_nofollow]" value="0">
            <input name="meta[term_seo_nofollow]" type="checkbox" id="term_seo_nofollow" value="1" <?php echo (@$meta['term_seo_nofollow'] == TRUE) ? 'checked' : ''; ?>>
            Enable
        </label>
        <p class="description">Do not index but add meta robots follow.</p>
    </td>
</tr>