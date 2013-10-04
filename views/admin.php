<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */
?>
<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<!-- TODO: Provide markup for your options page here. -->

    <div>
    <?php if(!empty($detectors)): ?>
        <h4>Several e-commerce plugins detected:</h4>

        <ol>
            <?php foreach($detectors as $detector): ?>
                <li><?php echo $detector ?></li>
            <?php endforeach; ?>
        </ol>
    <?php else: ?>
        <h4>No e-commerce plugins detected.</h4>
    <?php endif; ?>
    </div>

    <div class="flofiliate-container">
        <form id="flofiliate-setup" action="" method="POST">
            <?php settings_fields( 'flofiliate-group' ); ?>
            <input type="hidden" name="__flofiliate_submit" value="1"/>

            <fieldset>
                <label> Api Url:
                    <input type="text" name="flofiliate_api_url" value="<?php echo get_option('flofiliate_api_url'); ?>"/>
                </label>

                <br/><br/><br/>
                <input class="button-primary" type="submit" value="Save"/>
            </fieldset>
        </form>
    </div>
</div>
