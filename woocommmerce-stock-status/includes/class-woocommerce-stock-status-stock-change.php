<?php


class Woocommerce_Stock_Status_Stock_Change {


	/** @var WC_Product object */
	private $old_product;

	/** @var WC_Product object */
	private $new_product;

	/** @var  WC_Product[] */
	private $old_product_childs;

	public function __construct() {
//configure the right function
		add_action( 'woocommerce_payment_complete', array( $this, 'stockByPayment', 10, 1 ) );
		add_action( 'woocommerce_reduce_order_stock', array( $this, 'stockByReduce', 10, 1 ) );
		add_action( 'woocommerce_delete_order', array( $this, 'stockByDeleteOrder', 10, 1 ) );
		add_action( 'woocommerce_delete_order_item', array( $this, 'stockByDeleteOrderItem', 10, 1 ) );
		add_action( 'woocommerce_update_product', array( $this, 'stockByUpdateProduct' ) );
		add_action( 'woocommerce_save_variations',array( $this, 'stockByUpdateProductVariation' ));
		add_action( 'woocommerce_before_product-variable_object_save',array( $this, 'stockByUpdateProductVariation' ));
		add_action( 'woocommerce_before_product_object_save', array( $this, 'stockBeforeSaveProduct' ) );
		add_action( 'woocommerce_restore_order_stock', array( $this, 'stockByRestoreOrderStock', 10, 1 ) );

	}

	/**
	 * Gets the products after an order
	 *
	 * @param $order_id
	 *
	 * @return array $products_changed
	 */
	private function stockByPayment( $order_id ) {

		$products_changed = array();
		$order            = new WC_Order( $order_id );


		foreach ( $order->get_items() as $key => $lineItem ) {


			array_push( $products_change, $lineItem['product_id'] );

		}

		return $products_changed;


	}

	public function stockByUpdateProductVariation() {
		error_log( print_r( "Variation update" ) );

	}

	/**
	 * @param $order_id
	 */
	private function stockByReduce( $order_id ) {
		$order = wc_get_order( $order_id );


	}

	private function stockByDeleteOrder() {

	}

	private function stockByDeleteOrderItem() {

	}

	public function stockBeforeSaveProduct( $product_id ) {


		$product           = wc_get_product( $product_id );
		$this->old_product = $product;

		if ( is_object($product_id)) {
			return;
		}
		/*if(!property_exists($product,'get_children')){
			return;
		}*/
		$childs = $product->get_children();

		foreach ( $childs as $child ) {
			$list          = array();
			$product_child = wc_get_product( $child );
			array_push( $list, $product_child );
		}
		$this->old_product_childs = $list;


	}


	/**
	 * @param int $product_id ,This the product_id of an product WC_Product
	 * @param int $user
	 * @param DateTime $modified
	 * @param $stock_quantity int
	 *
	 */
	private function stockHistory( $product_id, $user, $modified, $stock_quantity ) {
		$meta_key = 'stock_status_history';

		$variation = wc_get_product($product_id);
		$atributes=$variation->get_attributes();





		$product_stock_history = array(
			"user"           => $user,
			"modified"       => $modified,
			"stock_quantity" => $stock_quantity,
			"variation"      => $atributes

		);
		if ( metadata_exists( 'post', $product_id, 'stock_status_history' ) == null ) {
			$meta_value[] = $product_stock_history;
			add_post_meta( $product_id, $meta_key, $meta_value );
		} else {
			$meta_value = get_post_meta( $product_id, 'stock_status_history', true );
			$last_meta_value=end($meta_value);
				if($product_stock_history["stock_quantity"]== $last_meta_value["stock_quantity"]){
					return;
				}
			array_push( $meta_value, $product_stock_history );
			update_post_meta( $product_id, $meta_key, $meta_value );
		}

		return;
	}

	/**
	 * @param  $product_id
	 *
	 */
	public function stockByUpdateProduct( $product_id ) {
		global $product;


		$product = wc_get_product( $product_id );
		//error_log(print_r($this->old_product->get_stock_quantity(), TRUE));
		//error_log(print_r($product->get_stock_quantity(), TRUE));

		$type = $product->get_type();

		$modified       =  new WC_DateTime();
		$user           = get_current_user_id();
		$stock_quantity = $product->get_stock_quantity();


		if ( $product->get_manage_stock() != null || $this->old_product->get_manage_stock() != $product->get_manage_stock() ) {
			if ( $type == 'simple' ) {

				if ( $product->get_manage_stock() == null ) {
					//error_log( print_r( "manage_stock is null", true ) );

					return;
				} else {

					$this->stockHistory( $product_id, $user, $modified, $stock_quantity );
					//error_log(print_r("History is updated", TRUE));
					//error_log(print_r($product, TRUE));
				}
			}
		}


		//TODO find solution to check whats the value before
		if ( $type == 'variable' ) {


			$child     = $product->get_children();
			$old_child = $this->old_product_childs;

			//error_log( print_r( $old_child, true ) );

			foreach ( $child as $var_id ) :

				$product_child = wc_get_product( $var_id );
				foreach ( $this->old_product_childs as $old_product_child ) :
					if ( $product_child->get_manage_stock() == null || $old_product_child->get_manage_stock() == $product_child->get_manage_stock() ) {
						return;
					}
				endforeach;
				$modified       = $product_child->get_date_modified();
				$stock_quantity = $product_child->get_stock_quantity();
				$user           = get_current_user_id();

				// Show all of the custom fields
				$this->stockHistory( $var_id, $user, $modified, $stock_quantity );


			endforeach;
		}

		return;

	}

	private
	function stockByRestoreOrderStock() {


	}

}