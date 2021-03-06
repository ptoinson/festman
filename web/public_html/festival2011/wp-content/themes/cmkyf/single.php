<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header();
?>

<!-- single -->
	<div id="content" class="widecolumn clear" role="main">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
		<div class="navigation">
			<div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
			<div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
			<div class="clear"></div>
		</div>
			
		<div class="post-panel c1-style">
			
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<div class="page-panel-header c-border c-bg corners-top">
					<table class="panel-content">
						<tr>
							<td class="post-title"><h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2></td>
							<td class="post-sep"></td>
							<td class="post-time"><?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> --></td>
						</tr>
					</table>
				</div>
				
				<div class="page-panel-body c-border corners-bottom">
					<div class="entry panel-content">
						<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
		
						<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
						<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>
		
						<p class="postmetadata alt">
							<small>
								This entry was posted
								<?php /* This is commented, because it requires a little adjusting sometimes.
									You'll need to download this plugin, and follow the instructions:
									http://binarybonsai.com/wordpress/time-since/ */
									/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
								on <?php the_time('l, F jS, Y') ?> at <?php the_time() ?>
								and is filed under <?php the_category(', ') ?>.
								You can follow any responses to this entry through the <?php post_comments_feed_link('RSS 2.0'); ?> feed.
		
								<?php if ( comments_open() && pings_open() ) {
									// Both Comments and Pings are open ?>
									You can <a href="#respond">leave a response</a>, or <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> from your own site.
		
								<?php } elseif ( !comments_open() && pings_open() ) {
									// Only Pings are Open ?>
									Responses are currently closed, but you can <a href="<?php trackback_url(); ?> " rel="trackback">trackback</a> from your own site.
		
								<?php } elseif ( comments_open() && !pings_open() ) {
									// Comments are open, Pings are not ?>
									You can skip to the end and leave a response. Pinging is currently not allowed.
		
								<?php } elseif ( !comments_open() && !pings_open() ) {
									// Neither Comments, nor Pings are open ?>
									Both comments and pings are currently closed.
		
								<?php } edit_post_link('Edit this entry','','.'); ?>
		
							</small>
						</p>
		
					</div>
				</div>
			</div>

		</div>
		
	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

	</div>

<?php get_footer(); ?>
