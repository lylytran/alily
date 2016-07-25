<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Entry
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/genesis/
 */

/**
 * Restore all default post loop output by re-hooking all default functions.
 *
 * Useful in the event that you need to unhook something in a particular context, but don't want to restore it for all
 * subsequent loop instances.
 *
 * Calls `genesis_reset_loops` action after everything has been re-hooked.
 *
 * @since 1.5.0
 *
 * @global array $_genesis_loop_args Associative array for grid loop configuration
 */
function genesis_reset_loops() {

	//* HTML5 Hooks
	add_action( 'genesis_entry_header', 'genesis_do_post_format_image', 4 );
	add_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
	add_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
	add_action( 'genesis_entry_header', 'genesis_do_post_title' );
	add_action( 'genesis_entry_header', 'genesis_post_info', 12 );

	add_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
	add_action( 'genesis_entry_content', 'genesis_do_post_content' );
	add_action( 'genesis_entry_content', 'genesis_do_post_content_nav', 12 );
	add_action( 'genesis_entry_content', 'genesis_do_post_permalink', 14 );

	add_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
	add_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
	add_action( 'genesis_entry_footer', 'genesis_post_meta' );

	add_action( 'genesis_after_entry', 'genesis_do_author_box_single', 8 );
	add_action( 'genesis_after_entry', 'genesis_get_comments_template' );

	//* Pre-HTML5 hooks
	add_action( 'genesis_before_post_title', 'genesis_do_post_format_image' );
	add_action( 'genesis_post_title', 'genesis_do_post_title' );
	add_action( 'genesis_post_content', 'genesis_do_post_image' );
	add_action( 'genesis_post_content', 'genesis_do_post_content' );
	add_action( 'genesis_post_content', 'genesis_do_post_permalink' );
	add_action( 'genesis_post_content', 'genesis_do_post_content_nav' );
	add_action( 'genesis_before_post_content', 'genesis_post_info' );
	add_action( 'genesis_after_post_content', 'genesis_post_meta' );
	add_action( 'genesis_after_post', 'genesis_do_author_box_single' );

	//* Other
	add_action( 'genesis_loop_else', 'genesis_do_noposts' );
	add_action( 'genesis_after_endwhile', 'genesis_posts_nav' );

	//* Reset loop args
	global $_genesis_loop_args;
	$_genesis_loop_args = array();

	do_action( 'genesis_reset_loops' );

}

add_filter( 'post_class', 'genesis_entry_post_class' );
/**
 * Add `entry` post class, remove `hentry` post class if HTML5.
 *
 * @since 1.9.0
 *
 * @uses genesis_html5() Check for HTML5 support.
 *
 * @param array Existing post classes.
 *
 * @return array Amended post classes.
 */
function genesis_entry_post_class( $classes ) {

	//* Add "entry" to the post class array
	$classes[] = 'entry';

	//* Remove "hentry" from post class array, if HTML5
	if ( genesis_html5() )
		$classes = array_diff( $classes, array( 'hentry' ) );

	return $classes;

}

add_filter( 'post_class', 'genesis_custom_post_class', 15 );
/**
 * Add a custom post class, saved as a custom field.
 *
 * @since 1.4.0
 *
 * @uses genesis_get_custom_field() Get custom field value.
 *
 * @param array $classes Existing post classes
 * @return array Amended post classes
 */
function genesis_custom_post_class( array $classes ) {

	$new_class = genesis_get_custom_field( '_genesis_custom_post_class' );

	if ( $new_class )
		$classes[] = esc_attr( $new_class );

	return $classes;

}

add_filter( 'post_class', 'genesis_featured_image_post_class' );
/**
 * Featured Image Post Class
 *
 * @since 2.2.0
 *
 * @uses genesis_get_image() Genesis featured image
 *
 * @param array $classes Existing post classes
 * @return array Amended post classes
 *
 */
function genesis_featured_image_post_class( $classes ) {

    $image = genesis_get_image();

    if ( $image && ! is_singular() && genesis_get_option( 'content_archive_thumbnail' ) && ! in_array( 'has-post-thumbnail', $classes ) ) {
        $classes[] = 'has-post-thumbnail';
    }

    return $classes;

}

add_action( 'genesis_entry_header', 'genesis_do_post_format_image', 4 );
add_action( 'genesis_before_post_title', 'genesis_do_post_format_image' );
/**
 * Add a post format icon.
 *
 * Adds an image, corresponding to the post format, before the post title.
 *
 * @since 1.4.0
 *
 * @uses CHILD_DIR
 * @uses CHILD_URL
 *
 * @return null Return early if post formats are not supported, or `genesis-post-format-images` is not supported
 */
function genesis_do_post_format_image() {

	//* Do nothing if post formats aren't supported
	if ( ! current_theme_supports( 'post-formats' ) || ! current_theme_supports( 'genesis-post-format-images' ) )
		return;

	//* Get post format
	$post_format = get_post_format();

	//* If post format is set, look for post format image
	if ( $post_format && file_exists( sprintf( '%s/images/post-formats/%s.png', CHILD_DIR, $post_format ) ) )
		printf( '<a href="%s" rel="bookmark"><img src="%s" class="post-format-image" alt="%s" /></a>', get_permalink(), sprintf( '%s/images/post-formats/%s.png', CHILD_URL, $post_format ), $post_format );

	//* Else, look for the default post format image
	elseif ( file_exists( sprintf( '%s/images/post-formats/default.png', CHILD_DIR ) ) )
		printf( '<a href="%s" rel="bookmark"><img src="%s/images/post-formats/default.png" class="post-format-image" alt="%s" /></a>', get_permalink(), CHILD_URL, 'post' );

}

add_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
/**
 * Echo the opening structural markup for the entry header.
 *
 * @since 2.0.0
 *
 * @uses genesis_attr() Contextual attributes.
 */
function genesis_entry_header_markup_open() {
	printf( '<header %s>', genesis_attr( 'entry-header' ) );
}

add_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
/**
 * Echo the closing structural markup for the entry header.
 *
 * @since 2.0.0
 */
function genesis_entry_header_markup_close() {
	echo '</header>';
}

add_action( 'genesis_entry_header', 'genesis_do_post_title' );
add_action( 'genesis_post_title', 'genesis_do_post_title' );
/**
 * Echo the title of a post.
 *
 * The `genesis_post_title_text` filter is applied on the text of the title, while the `genesis_post_title_output`
 * filter is applied on the echoed markup.
 *
 * @since 1.1.0
 *
 * @uses genesis_html5()          Check for HTML5 support.
 * @uses genesis_get_SEO_option() Get SEO setting value.
 * @uses genesis_markup()         Contextual markup.
 *
 * @return null Return early if the length of the title string is zero.
 */
function genesis_do_post_title() {

	$title = apply_filters( 'genesis_post_title_text', get_the_title() );

	if ( 0 === mb_strlen( $title ) )
		return;

	//* Link it, if necessary
	if ( ! is_singular() && apply_filters( 'genesis_link_post_title', true ) ){
		$title = sprintf( '<a href="%s" rel="bookmark">%s</a>', get_permalink(), $title );
	}

	//* Wrap in H1 on singular pages
	$wrap = is_singular() ? 'h1' : 'h2';

	//* Also, if HTML5 with semantic headings, wrap in H1
	$wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;

	/**
	 * Entry title wrapping element
	 *
	 * The wrapping element for the entry title.
	 *
	 * @since 2.2.3
	 *
	 * @param string $wrap The wrapping element (h1, h2, p, etc.).
	 */
	$wrap = apply_filters( 'genesis_entry_title_wrap', $wrap );

	//* Build the output
	$output = genesis_markup( array(
		'html5'   => "<{$wrap} %s>",
		'xhtml'   => sprintf( '<%s class="entry-title">%s</%s>', $wrap, $title, $wrap ),
		'context' => 'entry-title',
		'echo'    => false,
	) );

	$output .= genesis_html5() ? "{$title}</{$wrap}>" : '';

	echo apply_filters( 'genesis_post_title_output', "$output \n" );

}

add_filter( 'genesis_post_info', 'do_shortcode', 20 );
add_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_before_post_content', 'genesis_post_info' );
/**
 * Echo the post info (byline) under the post title.
 *
 * By default, only does post info on posts.
 *
 * The post info makes use of several shortcodes by default, and the whole output is filtered via `genesis_post_info`
 * before echoing.
 *
 * @since 0.2.3
 *
 * @uses genesis_markup() Contextual markup.
 *
 * @return null Return early if post type lacks support.
 */
function genesis_post_info() {

	if ( ! post_type_supports( get_post_type(), 'genesis-entry-meta-before-content' ) ) {
		return;
	}

	$filtered = apply_filters( 'genesis_post_info', '[post_date] ' . __( 'by', 'genesis' ) . ' [post_author_posts_link] [post_comments] [post_edit]' );
	if ( empty( $filtered ) ) {
		return;
	}

	$output = genesis_markup( array(
		'html5'   => '<p %s>',
		'xhtml'   => '<div class="post-info">',
		'context' => 'entry-meta-before-content',
		'echo'    => false,
	) );

	$output .= $filtered;
	$output .= genesis_html5() ? '</p>' : '</div>';

	echo $output;

}

add_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_post_content', 'genesis_do_post_image' );
/**
 * Echo the post image on archive pages.
 *
 * If this an archive page and the option is set to show thumbnail, then it gets the image size as per the theme
 * setting, wraps it in the post permalink and echoes it.
 *
 * @since 1.1.0
 *
 * @uses genesis_get_option() Get theme setting value.
 * @uses genesis_get_image()  Return an image pulled from the media library.
 * @uses genesis_parse_attr() Return contextual attributes.
 */
function genesis_do_post_image() {

	if ( ! is_singular() && genesis_get_option( 'content_archive_thumbnail' ) ) {
		$img = genesis_get_image( array(
			'format'  => 'html',
			'size'    => genesis_get_option( 'image_size' ),
			'context' => 'archive',
			'attr'    => genesis_parse_attr( 'entry-image', array ( 'alt' => get_the_title() ) ),
		) );

		if ( ! empty( $img ) )
			printf( '<a href="%s" aria-hidden="true">%s</a>', get_permalink(), $img );
	}

}

add_action( 'genesis_entry_content', 'genesis_do_post_content' );
add_action( 'genesis_post_content', 'genesis_do_post_content' );
/**
 * Echo the post content.
 *
 * On single posts or pages it echoes the full content, and optionally the trackback string if enabled. On single pages,
 * also adds the edit link after the content.
 *
 * Elsewhere it displays either the excerpt, limited content, or full content.
 *
 * Applies the `genesis_edit_post_link` filter.
 *
 * @since 1.1.0
 *
 * @uses genesis_get_option() Get theme setting value.
 * @uses the_content_limit()  Limited content.
 *
 */
function genesis_do_post_content() {

	if ( is_singular() ) {
		the_content();

		if ( is_single() && 'open' === get_option( 'default_ping_status' ) && post_type_supports( get_post_type(), 'trackbacks' ) ) {
			echo '<!--';
			trackback_rdf();
			echo '-->' . "\n";
		}

		if ( is_page() && apply_filters( 'genesis_edit_post_link', true ) )
			edit_post_link( __( '(Edit)', 'genesis' ), '', '' );
	}
	elseif ( 'excerpts' === genesis_get_option( 'content_archive' ) ) {
		the_excerpt();
	}
	else {
		if ( genesis_get_option( 'content_archive_limit' ) )
			the_content_limit( (int) genesis_get_option( 'content_archive_limit' ), genesis_a11y_more_link( __( '[Read more...]', 'genesis' ) ) );
		else
			the_content( genesis_a11y_more_link( __( '[Read more...]', 'genesis' ) ) );
	}

}

add_action( 'genesis_entry_content', 'genesis_do_post_content_nav', 12 );
add_action( 'genesis_post_content', 'genesis_do_post_content_nav' );
/**
 * Display page links for paginated posts (i.e. includes the <!--nextpage--> Quicktag one or more times).
 *
 * @since 2.0.0
 *
 * @uses genesis_markup() Contextual markup.
 * @uses genesis_html5()  Check for HTML5 support.
 */
function genesis_do_post_content_nav() {

	wp_link_pages( array(
		'before' => genesis_markup( array(
				'html5'   => '<div %s>',
				'xhtml'   => '<p class="pages">',
				'context' => 'entry-pagination',
				'echo'    => false,
			) ) . __( 'Pages:', 'genesis' ),
		'after'  => genesis_html5() ? '</div>' : '</p>',
	) );

}

add_action( 'genesis_entry_content', 'genesis_do_post_permalink', 14 );
add_action( 'genesis_post_content', 'genesis_do_post_permalink' );
/**
 * Show permalink if no title.
 *
 * If the entry has no title, this is a way to display a link to the full post.
 *
 * Applies the `genesis_post_permalink` filter.
 *
 * @since 2.0.0
 */
function genesis_do_post_permalink() {

	//* Don't show on singular views, or if the entry has a title
	if ( is_singular() || get_the_title() )
		return;

	$permalink = get_permalink();

	echo apply_filters( 'genesis_post_permalink', sprintf( '<p class="entry-permalink"><a href="%s" rel="bookmark">%s</a></p>', esc_url( $permalink ), esc_html( $permalink ) ) );

}

add_action( 'genesis_loop_else', 'genesis_do_noposts' );
/**
 * Echo filterable content when there are no posts to show.
 *
 * The applied filter is `genesis_noposts_text`.
 *
 * @since 1.1.0
 */
function genesis_do_noposts() {

	printf( '<div class="entry"><p>%s</p></div>', apply_filters( 'genesis_noposts_text', __( 'Sorry, no content matched your criteria.', 'genesis' ) ) );

}

add_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
/**
 * Echo the opening structural markup for the entry footer.
 *
 * @since 2.0.0
 *
 * @uses genesis_attr() Contextual attributes.
 */
function genesis_entry_footer_markup_open() {

	if ( post_type_supports( get_post_type(), 'genesis-entry-meta-after-content' ) ) {
		printf( '<footer %s>', genesis_attr( 'entry-footer' ) );
	}

}

add_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
/**
 * Echo the closing structural markup for the entry footer.
 *
 * @since 2.0.0
 */
function genesis_entry_footer_markup_close() {

	if ( post_type_supports( get_post_type(), 'genesis-entry-meta-after-content' ) ) {
		echo '</footer>';
	}

}

add_filter( 'genesis_post_meta', 'do_shortcode', 20 );
add_action( 'genesis_entry_footer', 'genesis_post_meta' );
add_action( 'genesis_after_post_content', 'genesis_post_meta' );
/**
 * Echo the post meta after the post content.
 *
 * By default, only does post meta on posts.
 *
 * The post info makes use of a couple of shortcodes by default, and the whole output is filtered via
 * `genesis_post_meta` before echoing.
 *
 * @since 0.2.3
 *
 * @uses genesis_markup() Contextual markup.
 *
 * @return null Return early if post type lacks support.
 */
function genesis_post_meta() {

	if ( ! post_type_supports( get_post_type(), 'genesis-entry-meta-after-content' ) ) {
		return;
	}

	$filtered = apply_filters( 'genesis_post_meta', '[post_categories] [post_tags]' );
	if ( empty( $filtered ) ) {
		return;
	}

	$output = genesis_markup( array(
		'html5'   => '<p %s>',
		'xhtml'   => '<div class="post-meta">',
		'context' => 'entry-meta-after-content',
		'echo'    => false,
	) );

	$output .= $filtered;
	$output .= genesis_html5() ? '</p>' : '</div>';

	echo $output;

}

add_action( 'genesis_after_entry', 'genesis_do_author_box_single', 8 );
add_action( 'genesis_after_post', 'genesis_do_author_box_single' );
/**
 * Conditionally add the author box after single posts or pages.
 *
 * @since 1.0.0
 *
 * @uses genesis_author_box() Echo the author box.
 *
 * @return null Return early if not a single post or page.
 */
function genesis_do_author_box_single() {

	if ( ! is_single() )
		return;

	if ( get_the_author_meta( 'genesis_author_box_single', get_the_author_meta( 'ID' ) ) )
		genesis_author_box( 'single' );

}

/**
 * Echo the the author box and its contents.
 *
 * The title is filterable via `genesis_author_box_title`, and the gravatar size is filterable via
 * `genesis_author_box_gravatar_size`.
 *
 * The final output is filterable via `genesis_author_box`, which passes many variables through.
 *
 * @since 1.3.0
 *
 * @uses genesis_html5() Check for HTML5 support.
 * @uses genesis_attr()  Contextual attributes.
 *
 * @global WP_User $authordata Author (user) object.
 *
 * @param string $context Optional. Allows different author box markup for different contexts, specifically 'single'.
 *                        Default is empty string.
 * @param bool   $echo    Optional. If true, the author box will echo. If false, it will be returned.
 *
 * @return string HTML for author box.
 */
function genesis_author_box( $context = '', $echo = true ) {

	global $authordata;

	$authordata    = is_object( $authordata ) ? $authordata : get_userdata( get_query_var( 'author' ) );
	$gravatar_size = apply_filters( 'genesis_author_box_gravatar_size', 70, $context );
	$gravatar      = get_avatar( get_the_author_meta( 'email' ), $gravatar_size );
	$description   = wpautop( get_the_author_meta( 'description' ) );

	//* The author box markup, contextual
	if ( genesis_html5() ) {

		$title = __( 'About', 'genesis' ) . ' <span itemprop="name">' . get_the_author() . '</span>';

		/**
		 * Author box title filter.
		 *
		 * Allows you to filter the title of the author box. $context passed as second parameter to allow for contextual filtering.
		 *
		 * @since unknown
		 *
		 * @param string $title Assembled Title.
		 * @param string $context Context.
		 */
		$title = apply_filters( 'genesis_author_box_title', $title, $context );

		if ( 'single' === $context && ! genesis_get_seo_option( 'semantic_headings' ) ) {
			$heading_element = 'h4';
		} elseif ( genesis_a11y( 'headings' ) || get_the_author_meta( 'headline', (int) get_query_var( 'author' ) ) ) {
			$heading_element = 'h4';
		} else {
			$heading_element = 'h1';
		}

		$pattern  = sprintf( '<section %s>', genesis_attr( 'author-box' ) );
		$pattern .= '%s<' . $heading_element . ' class="author-box-title">%s</' . $heading_element . '>';
		$pattern .= '<div class="author-box-content" itemprop="description">%s</div>';
		$pattern .= '</section>';

	} else {

		$title = apply_filters( 'genesis_author_box_title', sprintf( '<strong>%s %s</strong>', __( 'About', 'genesis' ), get_the_author() ), $context );

		if ( 'single' === $context || get_the_author_meta( 'headline', (int) get_query_var( 'author' ) ) ) {
			$pattern = '<div class="author-box"><div>%s %s<br />%s</div></div>';
		} else {
			$pattern = '<div class="author-box">%s<h1>%s</h1><div>%s</div></div>';
		}

	}

	$output = sprintf( $pattern, $gravatar, $title, $description );

	/**
	 * Author box output filter.
	 *
	 * Allows you to filter the full output of the author box.
	 *
	 * @since unknown
	 *
	 * @param string $output Assembled output.
	 * @param string $context Context.
	 * @param string $pattern (s)printf pattern.
	 * @param string $context Gravatar.
	 * @param string $context Title.
	 * @param string $context Description.
	 */
	$output = apply_filters( 'genesis_author_box', $output, $context, $pattern, $gravatar, $title, $description );

	if ( $echo )
		echo $output;
	else
		return $output;

}

add_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
/**
 * Display after-entry widget area on the genesis_after_entry action hook.
 *
 * @since 2.1.0
 *
 * @uses genesis_widget_area() Output widget area.
 */
function genesis_after_entry_widget_area() {

	if ( ! is_singular( 'post' ) || ! current_theme_supports( 'genesis-after-entry-widget-area' ) ) {
		return;
	}

	genesis_widget_area( 'after-entry', array(
		'before' => '<div class="after-entry widget-area">',
		'after'  => '</div>',
	) );

}

add_action( 'genesis_after_endwhile', 'genesis_posts_nav' );
/**
 * Conditionally echo archive pagination in a format dependent on chosen setting.
 *
 * This is shown at the end of archives to get to another page of entries.
 *
 * @since 0.2.3
 *
 * @uses genesis_get_option()            Get theme setting value.
 * @uses genesis_prev_next_posts_nav()   Prev and Next links.
 * @uses genesis_numeric_posts_nav()     Numbered links.
 */
function genesis_posts_nav() {

	if ( 'numeric' === genesis_get_option( 'posts_nav' ) )
		genesis_numeric_posts_nav();
	else
		genesis_prev_next_posts_nav();

}

/**
 * Echo archive pagination in Previous Posts / Next Posts format.
 *
 * Applies `genesis_prev_link_text` and `genesis_next_link_text` filters.
 *
 * @since 0.2.2
 */
function genesis_prev_next_posts_nav() {

	$prev_link = get_previous_posts_link( apply_filters( 'genesis_prev_link_text', '&#x000AB; ' . __( 'Previous Page', 'genesis' ) ) );
	$next_link = get_next_posts_link( apply_filters( 'genesis_next_link_text', __( 'Next Page', 'genesis' ) . ' &#x000BB;' ) );

	$prev = $prev_link ? '<div class="pagination-previous alignleft">' . $prev_link . '</div>' : '';
	$next = $next_link ? '<div class="pagination-next alignright">' . $next_link . '</div>' : '';

	$nav = genesis_markup( array(
		'html5'   => '<div %s>',
		'xhtml'   => '<div class="navigation">',
		'context' => 'archive-pagination',
		'echo'    => false,
	) );

	$nav .= $prev;
	$nav .= $next;
	$nav .= '</div>';

	if ( $prev || $next )
		echo $nav;
}

/**
 * Echo archive pagination in page numbers format.
 *
 * Applies the `genesis_prev_link_text` and `genesis_next_link_text` filters.
 *
 * The links, if needed, are ordered as:
 *
 *  * previous page arrow,
 *  * first page,
 *  * up to two pages before current page,
 *  * current page,
 *  * up to two pages after the current page,
 *  * last page,
 *  * next page arrow.
 *
 * @since 0.2.3
 *
 * @global WP_Query $wp_query Query object.
 *
 * @return null Return early if on a single post or page, or only one page present.
 */
function genesis_numeric_posts_nav() {

	if( is_singular() )
		return;

	global $wp_query;

	//* Stop execution if there's only 1 page
	if( $wp_query->max_num_pages <= 1 )
		return;

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );

	//* Add current page to the array
	if ( $paged >= 1 )
		$links[] = $paged;

	//* Add the pages around the current page to the array
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	genesis_markup( array(
		'html5'   => '<div %s>',
		'xhtml'   => '<div class="navigation">',
		'context' => 'archive-pagination',
	) );

	$before_number = genesis_a11y( 'screen-reader-text' ) ? '<span class="screen-reader-text">' . __( 'Page ', 'genesis' ) .  '</span>' : '';

	echo '<ul>';

	//* Previous Post Link
	if ( get_previous_posts_link() )
		printf( '<li class="pagination-previous">%s</li>' . "\n", get_previous_posts_link( apply_filters( 'genesis_prev_link_text', '&#x000AB; ' . __( 'Previous Page', 'genesis' ) ) ) );

	//* Link to first page, plus ellipses if necessary
	if ( ! in_array( 1, $links ) ) {

		$class = 1 == $paged ? ' class="active"' : '';

		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), $before_number . '1' );

		if ( ! in_array( 2, $links ) ) {
			echo '<li class="pagination-omission">&#x02026;</li>' . "\n";
		}

	}

	//* Link to current page, plus 2 pages in either direction if necessary
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active"  aria-label="' . __( 'Current page', 'genesis' ) . '"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $before_number . $link );
	}

	//* Link to last page, plus ellipses if necessary
	if ( ! in_array( $max, $links ) ) {

		if ( ! in_array( $max - 1, $links ) )
			echo '<li class="pagination-omission">&#x02026;</li>' . "\n";

		$class = $paged == $max ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $before_number . $max );

	}

	//* Next Post Link
	if ( get_next_posts_link() )
		printf( '<li class="pagination-next">%s</li>' . "\n", get_next_posts_link( apply_filters( 'genesis_next_link_text', __( 'Next Page', 'genesis' ) . ' &#x000BB;' ) ) );

	echo '</ul></div>' . "\n";

}

/**
 * Display links to previous and next post, from a single post.
 *
 * @since 1.5.1
 *
 * @return null Return early if not a post.
 */
function genesis_prev_next_post_nav() {

	if ( ! is_singular( 'post' ) )
		return;

	genesis_markup( array(
		'html5'   => '<div %s>',
		'xhtml'   => '<div class="navigation">',
		'context' => 'adjacent-entry-pagination',
	) );

	echo '<div class="pagination-previous alignleft">';
	previous_post_link();
	echo '</div>';

	echo '<div class="pagination-next alignright">';
	next_post_link();
	echo '</div>';

	echo '</div>';

}
