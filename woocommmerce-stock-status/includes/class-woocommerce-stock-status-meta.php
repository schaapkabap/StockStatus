<?php

class Woocommerce_Stock_Status_Stock_Meta{

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
	}

	public function add_meta_box( $post_type ) {
		$post_types = array('product');     //limit meta box to certain post types
		global $post;
		global $product;
		$product = wc_get_product( $post->ID );


		if ( in_array( $post_type, $post_types ) && ($product->is_type('simple') ) ) {
			add_meta_box(
				'wf_child_letters'
				,__( 'Picture Preview', 'woocommerce' )
				,array( $this, 'render_meta_box_content' )
				,$post_type
				,'advanced'
				,'high'
			);
		}
	}
	public function render_meta_box_content(){


	}

}