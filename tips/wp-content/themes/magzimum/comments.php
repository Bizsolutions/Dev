<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Magzimum
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				$comments_number = get_comments_number();
				if ( 1 === $comments_number ) {
					printf( 	/* translators: %s: post title */
						_x( 'One thought on &ldquo;%s&rdquo;', 'comments title', 'magzimum' ),
						'<span>' . get_the_title() . '</span>'
					);
				} else {
					printf( // WPCS: XSS OK.
						/* translators: 1: number of comments, 2: post title */
						esc_html( _nx(
							'%1$s thought on &ldquo;%2$s&rdquo;',
							'%1$s thoughts on &ldquo;%2$s&rdquo;',
							$comments_number,
							'comments title',
							'magzimum'
						) ),
						number_format_i18n( $comments_number ),
						'<span>' . get_the_title() . '</span>'
					);
				}
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'magzimum' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'magzimum' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'magzimum' ) ); ?></div>
		</nav><!-- #comment-nav-above -->
		<?php endif; // check for comment navigation ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'magzimum' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'magzimum' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'magzimum' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'magzimum' ); ?></p>
	<?php endif; ?>

  <?php
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $comment_fields =  array(

      'author' =>
        '<div class="row"><div class="col-sm-4 comment-form-author">' .
        '<input id="author" name="author" type="text" placeholder="'.esc_attr__( 'Name', 'magzimum' ).( $req ? '*' : '' ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
        '" size="30"' . $aria_req . ' /></div>',

      'email' =>
        '<div class="col-sm-4 comment-form-email">' .
        '<input id="email" name="email" type="text" placeholder="'.esc_attr__( 'Email', 'magzimum' ).( $req ? '*' : '' ) . '" value="' . esc_attr(  $commenter['comment_author_email'] ) .
        '" size="30"' . $aria_req . ' /></div>',

      'url' =>
        '<div class="col-sm-4 comment-form-url">' .
        '<input id="url" name="url" type="text" placeholder="'.esc_attr__( 'Website', 'magzimum' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) .
        '" size="30" /></div></div><!-- .row -->',
    );
    $comm_args = array(
      'fields'        => $comment_fields,
      'comment_field' => '<div class="row">
        <div class="col-sm-12">
          <textarea id="comment" name="comment" aria-required="true" cols="45" rows="8"  placeholder="'.esc_attr__( 'Comment', 'magzimum' ).'"></textarea>
        </div><!-- .col-sm-12 -->
      </div><!-- .row -->',
    );

   ?>
	<?php comment_form( $comm_args ); ?>

</div><!-- #comments -->
