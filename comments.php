<?php
/******************************************
Comments

 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 * @package Robins Photographs
******************************************/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// If post is password protected, no comments loaded
if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments section">

	<header class="comments__header">
		<div class="comments__title-container">
			<h2 class="comments__title">
				Share your thoughts!
			</h2>
		</div>
		<div class="comments__comment-count-container">
			<?php 
				printf( // WPCS: XSS OK.
					esc_html( _nx( '1 comment', '%1$s comments', get_comments_number(), 'comments title', 'robinsphotographs' ) ),
					number_format_i18n( get_comments_number() )
				);
			?>
		</div>
	</header>


		<?php 
		/******************************************
		Comments List
		******************************************/ 
		if ( have_comments() ) : ?>
			<ol class="comment-list">
				<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
					'callback' => 'theme_comment' // Function in: assets/inc/commentFormListItems.php
				) );
				?>
			</ol>

			<?php
			// the_comments_navigation();

			// If comments are closed and there are comments, let's leave a little note, shall we?
			if ( ! comments_open() ) :
				?>
				<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'robinsphotographs' ); ?></p>
				<?php
			endif;

		endif; 

		
		/******************************************
		Comment Form
		******************************************/ 
		// Definitions for author, email, URL and cookie notice fields... 
		$fields = array(
			// Author field
			'author' => 
				'<p class="comment-form-author">
					<label class="screen-reader-text" for="author">' . __( "Name", "text-domain" ) . '</label>
					<input required id="author" class="js-comment-author-input comment-form__author-input" name="author" type="text" placeholder="' . esc_attr__( "Name", "text-domain" ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
				'" size="30" />
				</p>',
			
			// Message asking a guest commenter if they would like the browser to save their details for next time....
			'cookies' => ''
		);


		// 'Commenting as' user notice beneath comment button
		$commentingAsUserNotice = is_user_logged_in() ? 	
			// Message for logged in users 
			sprintf(
				'<span class="comment-form__login-status -is-logged-in">You are commenting as <span class="comment-form__login-status-value"><a class="comment-form__login-link" href="%1$s">%2$s</a></span>. <a class="comment-form__login-link" href="%3$s" title="Log out">Log out</a>?' , admin_url( 'profile.php' ), getUserName(), site_url('/wp-login.php?action=logout&redirect_to=' . get_permalink() ) ) : 

			// Message for logged out / unregistered users 
			sprintf(
					'<span class="comment-form__login-status">You are commenting as a <span class="comment-form__login-status-value">guest</span>.</span>
					<span class="comment-form__login-links"><a class="comment-form__login-link" href="%1$s">Log in</a> or <a class="comment-form__login-link" href="%2$s">register</a>?</span>', site_url('/wp-login.php?&redirect_to=' . get_permalink() ), site_url('/wp-login.php?action=register&redirect_to=' . get_permalink() ) ) ;


		// Ajax submission array needs value for author field. When user logged in, no author field visible. So this is used to make a hidden author field when user is logged in with the value of their registered name. 
		$loggedInHiddenAuthorField = is_user_logged_in() ? 
			// Hidden field for when logged in (for use in JS ajax submission)
			'<p class="comment-form-author -is-hidden">
			<label class="screen-reader-text" for="author">' . __( "Name", "text-domain" ) . '</label>
			<input id="author" class="js-comment-author-input comment-form__author" name="author" type="text" placeholder="' . esc_attr__( "Name", "text-domain" ) . '" value="' . getUserName() .
			'" size="30" />
			</p>' :

			// Else if not logged in, display nothing as regular author field will be visible
			"";

		// All other form definitions
		$args = array(

			// Form class
			'class_form' => 'js-comment-form comment-form row',

			// Comment form title 
			'title_reply'=>
				'<span class="js-reply-title">Leave a comment...</span>',

			'cancel_reply_before' => '<div class="comment-form__cancel-reply-container">',
			'cancel_reply_link' => ' ',
			'cancel_reply_after' => '</div>',

			// Comment notes (Default: 'You email address will not be published') 
			'comment_notes_before' => ' ',

			// Comment textarea
			'comment_field' => 
				sprintf(
					'<p class="comment-form-comment">
					<label class="screen-reader-text" for="comment">' . __( "Comment", "text-domain" ) . '</label>
					<div class="comment-form__comment-container">
						<div class="comment-form-avatar">
							<img class="comment-form-avatar__img" src="%1$s">
						</div>
						<textarea required id="comment" class="js-comment-text-input comment-form__comment-area" name="comment" placeholder="' . esc_attr__( "Write your comment here...", "text-domain" ) . '" cols="45" rows="8" aria-required="true"></textarea>
					</div>
				</p>%2$s' , get_avatar_url(wp_get_current_user()->ID), $loggedInHiddenAuthorField),

			// Author field (as defined in field array)
			'fields' => apply_filters( 'comment_form_default_fields', $fields),

			// Comment button area
			'submit_field' => 
				'<p class="form-submit comment-form__submit-btn-container">%1$s %2$s</p>
				<div class="comment-form__login-info-container">' . $commentingAsUserNotice . '</div>',

			// Comment button 
			'submit_button' => 
						// '<input name="%1$s" type="submit" id="%2$s" class="js-comment-submit-btn spinner %3$s comment-form__submit-btn" value="Comment" />',
						'<button name="%1$s" type="submit" id="%2$s" class="js-comment-submit-btn %3$s comment-form__submit-btn" value="Comment">Comment</button>',

			// Default 'logged in as' notice 
			'logged_in_as' => ' '
					
		);



		// Compiled comment form:
		ob_start();
		comment_form($args);
		$str = ['class="comment-respond"','<form'];
		$rplc =['class="comment-respond container"','<form data-url="' . admin_url('admin-ajax.php') .'"'];
		echo str_replace($str,$rplc, ob_get_clean() );
		?>

</section>
<!-- onsubmit="validateAndSubmitCommentForm(event)" -->