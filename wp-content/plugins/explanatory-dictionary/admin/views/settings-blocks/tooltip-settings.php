<?php 
$pages_to_exclude = get_posts( array(
	'posts_per_page' => -1,
	'post_type' => 'page'
) );
$posts_to_exclude = get_posts( array(
	'posts_per_page' => -1,
	'post_type' => 'post'
) );
?>

<table class="form-table">
	<tr>
		<th scope="row"><label for="_exclude"><?php _e( 'Exclude tooltips from', 'explanatory-dictionary' ); ?></label></th>
		<td>
			<table>
				<tr>
					<td><?php _e( 'Pages', 'explanatory-dictionary');?></td>
					<td>
						<select id="_exclude" name="settings[_exclude][pages][]" multiple="multiple">
							<?php foreach( $pages_to_exclude as $page ):?>
								<?php $selected = '';?>
								<?php if( ! empty( $settings['_exclude']['pages'] ) && in_array( $page->ID, $settings['_exclude']['pages'] ) ) :?>
									<?php $selected = 'selected="selected"';?>
								<?php endif;?>
								<option value="<?php echo $page->ID?>" <?php echo $selected;?>><?php echo $page->post_title;?></option>
							<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td><?php _e( 'Posts', 'explanatory-dictionary');?></td>
					<td>
						<select id="_exclude" name="settings[_exclude][posts][]" multiple="multiple">
							<?php foreach( $posts_to_exclude as $post ):?>
								<?php $selected = '';?>
								<?php if( ! empty( $settings['_exclude']['posts'] ) && in_array( $post->ID, $settings['_exclude']['posts'] ) ) :?>
									<?php $selected = 'selected="selected"';?>
								<?php endif;?>
								<option value="<?php echo $post->ID?>" <?php echo $selected;?>><?php echo $post->post_title;?></option>
							<?php endforeach;?>
						</select>
					</td>
				</tr>
			</table>
			<p class="description"><?php _e( 'Select where you do not want to show the tooltip', 'explanatory-dictionary' ); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="_limit"><?php _e( 'Limit number of tooltips shown', 'explanatory-dictionary' ); ?></label></th>
		<td>
			<input id="_limit" type="number" name="settings[_limit]" min="-1" max="60" value="<?php echo esc_attr( $settings['_limit'] ); ?>">
			<p class="description"><?php _e( 'Set the limit for how many times the tooltip should be shown per page or post. (-1 shows all)', 'explanatory-dictionary' ); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="_max_width"><?php _e( 'Tooltip max width', 'explanatory-dictionary' ); ?></label></th>
		<td>
			<input type="text" class="regular-text" value="<?php echo esc_attr( $settings['_max_width'] );?>" id="_max_width" name="settings[_max_width]" />
			<p class="description"><?php _e( 'Set the max width (Example: 200 , 300 , 400 ...) of the explanation tooltip.', 'explanatory-dictionary' ); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="_min_width"><?php _e( 'Tooltip min width', 'explanatory-dictionary' ); ?></label></th>
		<td>
			<input type="text" class="regular-text" value="<?php echo esc_attr( $settings['_min_width'] );?>" id="_min_width" name="settings[_min_width]" />
			<p class="description"><?php _e( 'Set the minimal width (Example: 200 , 300 , 400 ...) of the explanation tooltip.', 'explanatory-dictionary' ); ?></p>
		</td>
	</tr>
</table>