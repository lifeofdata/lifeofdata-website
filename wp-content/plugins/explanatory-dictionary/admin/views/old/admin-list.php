<?php
/**
 * @package   Explanatory_Dictionary
 * @author    EXED internet (RJvD, BHdH) <service@exed.nl>
 * @license   GPL-2.0+
 * @link      http://www.mixcom.nl/online
 * @copyright 2014  EXED internet  (email : service@exed.nl)
 */
$class = Explanatory_Dictionary::get_instance();

if( isset( $_GET['migrate_old_data'] ) ) {
	Explanatory_Dictionary_Helpers::migrate_old_data();
	$message = 'Data has been migrated';
	$message_status = 'update';
}

$current_page = admin_url( 'edit.php?post_type=explandict&page=explanatory-dictionary-old-data' );

/* Get a count of all the entries available. */
$entries_count = $class->get_number_of_entries( true );

/* Get all of the active and inactive entries. */
$active_entries = $class->get_all_entries_by_status( 1, true );

$inactive_entries = $class->get_all_entries_by_status( 0, true );

/* Get a count of the active and inactive entries. */
$active_entries_count = count( $active_entries );
$inactive_entries_count = count( $inactive_entries );

/* If we're viewing 'active' or 'inactive' entries. */
if ( !empty( $_GET['entry_status'] ) && in_array( $_GET['entry_status'], array( 'active', 'inactive' ) ) ) {

	/* Get the role status ('active' or 'inactive'). */
	$entry_status = esc_attr( $_GET['entry_status'] );

	/* Set up the entries array. */
	$list_entries = ( ( 'active' == $entry_status ) ? $active_entries : $inactive_entries );

	/* Set the current page URL. */
	$current_page = admin_url( "admin.php?page=explanatory-dictionary&entry_status={$entry_status}" );
}

/* If viewing the regular role list table. */
else {

	/* Get the role status ('active' or 'inactive'). */
	$entry_status = 'all';

	/* Set up the entries array. */
	$list_entries = array_merge( $active_entries, $inactive_entries );

	/* Set the current page URL. */
	$current_page = $current_page = admin_url( 'admin.php?page=explanatory-dictionary' );
}

/* Sort the entries array into alphabetical order. */
ksort( $list_entries );
?>
<div class="wrap">

	<?php screen_icon(); ?>
	<h2>
		<?php echo esc_html( get_admin_page_title() ); ?>
	</h2>

	<?php if ( isset( $message ) ) : ?>
		<?php Explanatory_Dictionary_Helpers::admin_message( $message, $message_status );?>
	<?php endif;?>
	
	<div>
	<?php $migrate_url = add_query_arg( array( 'migrate_old_data' => true) );?>
		<a href="<?php echo $migrate_url;?>">Click here to migrate the old data into the new custom post type</a>
	</div>
	
	<div id="poststuff">

		<form id="explanatory-dictionary" action="<?php echo $current_page; ?>" method="post">

			<?php wp_nonce_field( Explanatory_Dictionary_Helpers::get_nonce( 'list-dictionary' ) ); ?>

			<ul class="subsubsub">
				<li><a <?php if ( 'all' == $entry_status ) echo 'class="current"'; ?> href="<?php echo admin_url( esc_url( 'admin.php?page=explanatory-dictionary' ) ); ?>"><?php _e( 'All', 'explanatory-dictionary' ); ?> <span class="count">(<span id="all_count"><?php echo $entries_count; ?></span>)</span></a> | </li>
				<li><a <?php if ( 'active' == $entry_status ) echo 'class="current"'; ?> href="<?php echo admin_url( esc_url( 'admin.php?page=explanatory-dictionary&amp;entry_status=active' ) ); ?>"><?php _e( 'Active', 'explanatory-dictionary' ); ?> <span class="count">(<span id="active_count"><?php echo $active_entries_count; ?></span>)</span></a> | </li>
				<li><a <?php if ( 'inactive' == $entry_status ) echo 'class="current"'; ?> href="<?php echo admin_url( esc_url( 'admin.php?page=explanatory-dictionary&amp;entry_status=inactive' ) ); ?>"><?php _e( 'Inactive', 'explanatory-dictionary' ); ?> <span class="count">(<span id="inactive_count"><?php echo $inactive_entries_count; ?></span>)</span></a></li>
			</ul><!-- .subsubsub -->

			<div class="tablenav">

				<div class='tablenav-pages one-page'>
					<!-- <span class="displaying-num"><?php printf( _n( '%s item', '%s items', count( 1 ), 'explanatory-dictionary' ), count( 1 ) ); ?></span> -->
				</div>

				<br class="clear" />
			</div><!-- .tablenav -->

			<table class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th class='check-column'><input type='checkbox' /></th>
						<th class='name-column'><?php _e( 'Word (words expression, sentence)', 'explanatory-dictionary' ); ?></th>
						<th><?php _e( 'Synonyms and forms', 'explanatory-dictionary' ); ?></th>
						<th><?php _e( 'Explanation', 'explanatory-dictionary' ); ?></th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<th class='check-column'><input type='checkbox' /></th>
						<th class='name-column'><?php _e( 'Word (words expression, sentence)', 'explanatory-dictionary' ); ?></th>
						<th><?php _e( 'Synonyms and forms', 'explanatory-dictionary' ); ?></th>
						<th><?php _e( 'Explanation', 'explanatory-dictionary' ); ?></th>
					</tr>
				</tfoot>

				<tbody id="users" class="list:user user-list plugins">

				<?php foreach ( $list_entries as $key => $entry ) : ?>

					<tr valign="top" class="<?php echo ( isset( $active_entries[$key] ) ? 'active' : 'inactive' ); ?>">

						<th class="manage-column column-cb check-column">
							<input type="checkbox" name="entries[<?php echo esc_attr( $entry->id ); ?>]" id="<?php echo esc_attr( $entry->id ); ?>" value="<?php echo esc_attr( $entry->id ); ?>" />
						</th><!-- .manage-column .column-cb .check-column -->

						<td class="plugin-title">
							<?php $edit_url = admin_url( "edit.php?post_type=explandict&page=explanatory-dictionary-old-data&action=view&item={$entry->id}" );?>
							<a href="<?php echo esc_url( $edit_url ); ?>" title="<?php _e( 'Edit this etry', 'explanatory-dictionary' ); ?>">
								<strong><?php echo esc_html( $entry->word ); ?></strong>
							</a>
							<div class="row-actions">
								<span class="edit">
									<a href="<?php echo esc_url( $edit_url ); ?>" title="<?php _e( 'Edit this etry', 'explanatory-dictionary' ); ?>">
										<?php _e( 'View', 'explanatory-dictionary' ); ?>
									</a>
								</span>
							</div><!-- .row-actions -->
						</td><!-- .plugin-title -->

						<td class="desc">
							<p><?php echo $class->synonyms_output( $entry->synonyms_and_forms, true ); ?></p>
						</td><!-- .desc -->

						<td class="desc">
							<p><?php echo esc_html( $entry->explanation ); ?></p>
						</td><!-- .desc -->

					</tr><!-- .active .inactive -->

				<?php endforeach; ?>

				</tbody><!-- #users .list:user .user-list .plugins -->

			</table><!-- .widefat .fixed -->

			<div class="tablenav">
				<div class='tablenav-pages one-page'>
					<!-- <span class="displaying-num"><?php printf( _n( '%s item', '%s items', count( 1 ), 'explanatory-dictionary' ), count( 1 ) ); ?></span> -->
				</div>
				<br class="clear" />
			</div><!-- .tablenav -->
		</form><!-- #entries -->
	</div><!-- #poststuff -->
</div>