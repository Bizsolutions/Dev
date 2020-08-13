<?php
/**
 * Welcome screen getting started template
 */
?>
<?php $theme_data = wp_get_theme('clearsky'); ?>
<h1 class="theme-name">
    <?php echo $theme_data->Name .'<sup class="version">' . esc_attr(  $theme_data->Version ) . '</sup>'; ?>
</h1>
<p><?php esc_html_e( 'Here you can read the documentation and know how to get the most out of your new theme.', 'clearsky' ); ?></p>
<div id="getting_started" class="panel">
    <div class="col2 evidence">
        <h3><?php printf(esc_html__('%s Premium', 'clearsky'), $theme_data->Name); ?></h3>
           <p>
                <?php printf(esc_html__('%s Premium', 'clearsky'), $theme_data->Name); ?><?php esc_html_e( ' expands the already powerful free version of this theme and gives access to our priority support service.', 'clearsky' ); ?>
            <ul>
                <li><?php esc_html_e( 'More advanced options', 'clearsky' ); ?></li>
                <li><?php esc_html_e( 'New fonts', 'clearsky' ); ?></li>
                <li><?php esc_html_e( 'Shop customizer', 'clearsky' ); ?></li>
                <li><?php esc_html_e( 'Custom widgets', 'clearsky' ); ?></li>
                <li><?php esc_html_e( 'New post and page settings', 'clearsky' ); ?></li>
                <li><?php esc_html_e( 'Premium support', 'clearsky' ); ?></li>
                <li><?php esc_html_e( 'Money back guarantee', 'clearsky' ); ?></li>
            </ul>
            <a href="<?php echo esc_url( 'https://iograficathemes.com/shop/wordpress-themes/'. $theme_data->get( 'TextDomain' ) ); ?>" target="_blank" class="button-upgrade">
                <?php esc_html_e('upgrade to premium', 'clearsky'); ?>
            </a>
        </p>
    </div>
     <div class="col2 omega">
        <h3><?php esc_html_e( 'Enjoying the theme?', 'clearsky' ); ?></h3>
        <p class="about"><?php esc_html_e( 'If you like this theme why not leave us a review on WordPress.org?  We\'d really appreciate it!', 'clearsky' ); ?></p>
        <p>
            <a href="<?php echo esc_url( 'https://wordpress.org/support/theme/'. $theme_data->get( 'TextDomain' ) .'/reviews/#new-post' ); ?>" target="_blank" class="button button-secondary"><?php esc_html_e('Add Your Review', 'clearsky'); ?></a>
        </p>
        <h3><?php esc_html_e( 'Theme Documentation', 'clearsky' ); ?></h3>
        <p class="about"><?php printf(esc_html__('Need any help to setup and configure %s? Please have a look at our documentations instructions.', 'clearsky'), $theme_data->Name); ?></p>
        <p>
            <a href="<?php echo esc_url( 'https://www.iograficathemes.com/documentation/'. $theme_data->get( 'TextDomain' ) ); ?>" target="_blank" class="button button-secondary"><?php esc_html_e('View Documentation', 'clearsky'); ?></a>
        </p>
        <h3><?php esc_html_e( 'Theme Customizer', 'clearsky' ); ?></h3>
        <p class="about"><?php printf(esc_html__('%s supports the Theme Customizer for all theme settings. Click "Customize" to start customize your site.', 'clearsky'), $theme_data->Name); ?></p>
        <p>
            <a href="<?php echo admin_url('customize.php'); ?>" class="button button-secondary"><?php esc_html_e('Start Customize', 'clearsky'); ?></a>
        </p>
    </div>

</div><!-- end ig-started -->
