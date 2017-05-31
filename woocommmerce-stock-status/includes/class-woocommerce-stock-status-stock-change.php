<?php
/**
 * Created by PhpStorm.
 * User: webrooters
 * Date: 31-5-17
 * Time: 11:19
 */
class Woocommerce_Stock_Status_Stock_Change{

	private $order;

	private $product;


	public function __construct(){

		$this->getStockChange();


	}

private function getStockChange(){



	add_action('woocommerce_payment_complete', 'custom_process_order', 10, 1);


	function custom_process_order($order_id) {

		$order = new WC_Order( $order_id );
		$myuser_id = (int)$order->user_id;

		$items = $order->get_items();
		foreach ($items as $item) {

					}
		return $order_id;
	}

}




}