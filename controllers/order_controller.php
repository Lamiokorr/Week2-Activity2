<?php

require_once '../classes/order_class.php';

function create_order_ctr($customer_id, $invoice_no, $order_date, $order_status = 'Pending'){
    $order = new Order();
    return $order->createOrder($customer_id, $invoice_no, $order_date, $order_status = 'Pending');
}

function add_order_detail_ctr($order_id, $product_id, $qty){
    $order = new Order();
    return $order->add_order_detail($order_id, $product_id, $qty);
}

function record_payment_ctr($pay_id, $amt, $customer_id, $order_id, $currency, $payment_date){
    $order = new Order();
    return $order->record_payment($pay_id, $amt, $customer_id, $order_id, $currency, $payment_date);
}

function get_user_orders_ctr($customer_id){
    $order = new Order();
    return $order->get_user_orders($customer_id);
}

?>