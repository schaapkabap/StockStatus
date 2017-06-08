<?php

class Woocommerce_Stock_Status_Stock_Meta {

	public function __construct() {

		add_action( 'load-post.php', array( $this, 'stock_status_post_meta_boxes_setup' ) );
		add_action( 'load-post-new.php', array( $this, 'stock_status_post_meta_boxes_setup' ) );

	}

	public function stock_status_post_meta_boxes_setup() {

		/* Add meta boxes on the 'add_meta_boxes' hook. */
		add_action( 'add_meta_boxes', 'stock_status_add_post_meta_boxes' );
		/* Save post meta on the 'save_post' hook. */
		add_action( 'save_post', 'stock_status_save_post_class_meta', 10, 2 );
		/* Save the meta box's post metadata. */
		function stock_status_save_post_class_meta( $post_id, $post ) {

			/* Verify the nonce before proceeding. */
			if ( ! isset( $_POST['stock_status_post_class_nonce'] ) || ! wp_verify_nonce( $_POST['stock_status_post_class_nonce'],
					basename( __FILE__ ) )
			) {
				return $post_id;
			}

			/* Get the post type object. */
			$post_type = get_post_type_object( $post->post_type );

			/* Check if the current user has permission to edit the post. */
			if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
				return $post_id;
			}

			/* Get the posted data and sanitize it for use as an HTML class. */
			$new_meta_value = ( isset( $_POST['stock-status-post-class'] ) ? sanitize_html_class( $_POST['stock-status-post-class'] ) : '' );

			/* Get the meta key. */
			$meta_key = 'stock_status_post_class';

			/* Get the meta value of the custom field key. */
			$meta_value = get_post_meta( $post_id, $meta_key, true );

			/* If a new meta value was added and there was no previous value, add it. */
			if ( $new_meta_value && '' == $meta_value ) {
				add_post_meta( $post_id, $meta_key, $new_meta_value, true );
			} /* If the new meta value does not match the old value, update it. */
			elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
				update_post_meta( $post_id, $meta_key, $new_meta_value );
			} /* If there is no new meta value but an old value exists, delete it. */
			elseif ( '' == $new_meta_value && $meta_value ) {
				delete_post_meta( $post_id, $meta_key, $meta_value );
			}
		}

		/* Filter the post class hook with our custom post class function. */
		add_filter( 'post_class', 'stock_status_post_class' );


		function stock_status_post_class( $classes ) {

			/* Get the current post ID. */
			$post_id = get_the_ID();

			/* If we have a post ID, proceed. */
			if ( ! empty( $post_id ) ) {

				/* Get the custom post class. */
				$post_class = get_post_meta( $post_id, 'smashing_post_class', true );

				/* If a post class was input, sanitize it and add it to the post class array. */
				if ( ! empty( $post_class ) ) {
					$classes[] = sanitize_html_class( $post_class );
				}
			}

			return $classes;
		}

		function stock_status_add_post_meta_boxes() {

			add_meta_box(
				'stock-status-post-class',      // Unique ID
				esc_html__( 'Stock Status', 'example' ),    // Title
				'stock_status_post_class_meta_box',   // Callback function
				'product',         // Admin page (or post type)
				'normal',         // Context
				'default'         // Priority
			);

		}


		function stock_status_post_class_meta_box( $post ) { ?>

			<?php wp_nonce_field( basename( __FILE__ ), 'smashing_post_class_nonce' ); ?>
			<table style="width:100%">
				<thead>
				<tr>
					<th>WijzigingsDatum</th>
					<th>Aantal</th>
					<th>Gebruiker</th>
					<th>Variatie</th>
				</tr>
				</thead>

				<?php
				$post_id = get_post()->ID;
				$product = wc_get_product( $post_id );
				if ( metadata_exists( 'post', $post_id, 'stock_status_history' ) != null ) {
					$stock_history_parent = get_post_meta( $post_id, 'stock_status_history', true );
				} else {
					$stock_history_parent = array();
				}

				if ( $product->has_child() ) {
					$childs   = $product->get_children();
					$children = array();

					foreach ( $childs as $child ) {
						$child_product       = wc_get_product( $child );
						$stock_history_child = get_post_meta( $child, 'stock_status_history', true );
						$child_product->get_formatted_name();/*
				foreach($stock_history_child as $child){
					$child['variatie']= $child_product->get_formatted_variation_attributes();
					$children= $children+$child;
				}*/

						$stock_history_parent = array_merge( $stock_history_parent, $stock_history_child );
						error_log( "Stock history parent and childs " . print_r( $stock_history_parent, true ) );
						//error_log("Stock history parent and childs ". print_r($stock_history_parent, TRUE));
					}
					//error_log("Stock history parent and childs". print_r($stock_history_parent, TRUE));

				}
				//error_log("Stock history parent and childs". print_r($stock_history_parent, TRUE));

				if ( $stock_history_parent == null ) {
					return;
				}


				foreach ( $stock_history_parent as $item ) {

					?>
					<tr>
						<td>
							<?php
							echo $item["modified"];
							?></td>
						<td>

							<?php
							echo $item["stock_quantity"];
							?>
						</td>
						<td>

							<?php
							$user_info = get_userdata( $item["user"] );
							echo $user_info->user_login . "\n";
							?>
						</td>
						<td>

							<?php

							if ( isset( $item["variation"] ) ):
								foreach ( $item["variation"] as $key => $value ):
									if ( $value ):
										echo $key . ": " . $value . ",";
									endif;
								endforeach;

							endif
							?>
						</td>
					</tr>
					<?php
				}

				?>


			</table>

			<label
				</label>
			<br/>

		<?php }

	}


}