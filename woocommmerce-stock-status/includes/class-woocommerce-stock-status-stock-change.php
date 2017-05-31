<?php




class Woocommerce_Stock_Status_Stock_Change {

	private $order;

	private $product;


	public function __construct() {
//configure the right function
		add_action( 'woocommerce_payment_complete', array( $this, 'stockByPayment', 10, 1 ) );
		add_action( 'woocommerce_reduce_order_stock', array( $this, 'stockByReduce', 10, 1 ) );
		add_action( 'woocommerce_delete_order', array($this, 'stockByDeleteOrder', 10, 1 ));
		add_action( 'woocommerce_delete_order_item', array($this, 'stockByDeleteOrderItem', 10, 1 ));
		add_action( 'woocommerce_update_product', array($this, 'stockByUpdateProduct', 10, 1 ));
		add_action( 'woocommerce_restore_order_stock', array($this, 'stockByRestoreOrderStock', 10, 1 ));

	}

	/**
	 * Gets the products after an order
	 *
	 * @param $order_id
	 *
	 * @return array $products_changed
	 */
	private function stockByPayment($order_id) {

		$products_changed = array();
		$order= new WC_Order( $order_id );


		foreach ( $order->get_items() as $key => $lineItem ) {


			array_push( $products_change, $lineItem['product_id'] );

		}
		return $products_changed;


	}

	/**
	 * @param $order_id
	 */
	private function stockByReduce($order_id) {
		$order = wc_get_order($order_id);


	}

	private function stockByDeleteOrder(){

	}
private function stockByDeleteOrderItem(){

}

	/**
	 * @param $order_id
	 */
	private function stockByUpdateProduct($order_id){
	$orders= wc_get_order($order_id);
	$order =$orders->get_items();
	foreach ( $orders->get_items() as $key => $lineItem ) {


		$product_id =$lineItem['product_id'] ;
		/**
		 * 	@param WC_Product $product
		 *
		 */
		$product= wc_get_product($product_id);
		$product->get_meta_data()
		/**
		 * @param WC_Product::get_changes $changes
		 */
		$changes =$product->get_changes();
		var_dump($changes);

	}


}
private function stockByRestoreOrderStock(){


}








//TODO MOVE TO ANOTHER CLASS
//TODO ADD ANOTHER FUCNTION FOR UPDATE
	/**
	 *
	 * Updates the stockstatus in the database
	 *
	 * @param array $products
	 */
	private function updateStockStatus($products) {


		foreach ( $products as $product_id ) {


			$product = new WC_Product( $product_id );
			$product->get_stock_status();


		}

	}

}