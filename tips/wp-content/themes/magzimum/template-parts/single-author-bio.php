<?php
/**
 * The template part for displaying author Bio.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Magzimum
 */
?>
<div class="authorbox <?php echo ( 1 != get_option( 'show_avatars' ) ) ? 'no-author-avatar' : ''; ?>">
    <?php if ( 1 == get_option('show_avatars') ): ?>
        <div class="author-avatar">
            <?php echo get_avatar( get_the_author_meta( 'user_email' ), '80', '' ); ?>
        </div>
    <?php endif ?>
    <div class="author-info">
        <h4 class="author-header"><?php esc_html_e( 'Written by', 'magzimum' ); ?>&nbsp;<?php  the_author_posts_link(); ?></h4>
        <div class="author-content"><?php the_author_meta('description'); ?></div>
        <?php $user_url = get_the_author_meta( 'user_url' ); ?>
        <?php if ( ! empty( $user_url ) ): ?>
        <div class="author-footer"><a href="<?php echo esc_url( $user_url ); ?>" target="_blank"><?php esc_html_e( 'Website', 'magzimum' ); ?></a></div>
        <?php endif ?>
    </div>
</div>
