<div id="sidebarnav" class="fluid-sidebar sidebar" role="complementary">

	<?php if ( is_active_sidebar( 'sidebarnav' ) ) : ?>

		<?php
			$pages = get_pages( array(
				    	'sort_order' => 'ASC',
				    	'sort_column' => 'post_title',
				    	'parent' => 0 )
						);
			?>

			<?php
				if ($post->post_parent)
					//I am a subpage
					$id = $post->post_parent;
				else
					//I am a page
					$id = $post->ID;

				$subpages = get_pages(array("child_of"=>$id));
			?>
		<ul>
		<?php foreach ($pages as $page):?>
			
			<li <?php if ($page->ID == $post->ID) echo 'class="current-page-item"'?>>

				<a href="<?php echo get_permalink($page->ID); ?> "><?php echo $page->post_title ?></a>
				<?php
				if ( ($page->ID == $post->ID) || ($post->post_parent == $page->ID) ):	

				?>
				<ul>
					<?php
						foreach($subpages as $subpage):
					?>
					
					<li <?php if ($subpage->ID == $post->ID) echo 'class="current-page-item"'?>>
					
						<a href="<?php echo get_permalink($subpage->ID); ?> "><?php echo $subpage->post_title ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</li>

		<?php endforeach;?>
		</ul>

		<?php dynamic_sidebar( 'sidebarnav' ); ?>

	<?php else : ?>

		<!-- This content shows up if there are no widgets defined in the backend. -->
		
		<div class="alert alert-message">
		
			<p><?php _e("Please activate some Widgets","wheniwasbad"); ?>.</p>
		
		</div>

	<?php endif; ?>

</div>