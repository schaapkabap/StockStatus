<?php




class Woocommerce_Stock_Status_Stock_Change {




	/** @var WC_Product object  */
	private $old_product;

	/** @var WC_Product object  */
	private $new_product;



	public function __construct() {
//configure the right function
		add_action( 'woocommerce_payment_complete', array( $this, 'stockByPayment', 10, 1 ) );
		add_action( 'woocommerce_reduce_order_stock', array( $this, 'stockByReduce', 10, 1 ) );
		add_action( 'woocommerce_delete_order', array($this, 'stockByDeleteOrder', 10, 1 ));
		add_action( 'woocommerce_delete_order_item', array($this, 'stockByDeleteOrderItem', 10, 1 ));
		add_action( 'woocommerce_update_product', array($this, 'stockByUpdateProduct'));
		add_action( 'woocommerce_before_product_object_save', array($this, 'stockBeforeSaveProduct'));
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

public function stockBeforeSaveProduct($product_id){
	global $product;

	$product = wc_get_product($product_id);
	$this->old_product = $product;



}
	/**
	 * @param  $product_id
	 *
	 */
	public function stockByUpdateProduct($product_id){
	global $product;


		$product = wc_get_product($product_id);
		//error_log(print_r($this->old_product->get_stock_quantity(), TRUE));
		//error_log(print_r($product->get_stock_quantity(), TRUE));
		$type=$product->get_type();
		$change =$product->get_changes();
		$modified =$product->get_date_modified();
		$user= get_current_user_id();

/*
if($product->get_manage_stock()== NULL || $this->old_product->get_manage_stock()== $product->get_manage_stock()){

	return;
}*/

		if($type == 'simple'){
			//TODO DEFINE RETURN
			if($product->get_manage_stock() == NULL){

			}
			//TODO DEFINE RETURN
			if($product->get_stock_quantity()){

				$stock = $product->get_stock_quantity();



			}

		}
		if($type =='variable'){

		$child= $product->get_children();
			error_log(print_r($child, TRUE));
		foreach( $child as $var_id ) :

			// Get all of the custom field data in an array
//			$all_cfs = get_post_custom($var_id);

			$product_child= wc_get_product($var_id);
			// Show all of the custom fields
			error_log(print_r($product_child->get_stock_quantity(), TRUE));







		endforeach;
		}

return;

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