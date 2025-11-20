<?php

require_once '../classes/order_class.php';

function create_order_ctr($customer_id, $order_total, $order_status){
    $order = new Order();
    return $order->createOrder($customer_id, $order_total, $order_status);
}

function add_order_detail_ctr($order_id, $product_id, $qty){
    $order = new Order();
    return $order->add_order_detail($order_id, $product_id, $qty);
}

function record_payment_ctr($pay_id, $amt, $customer_id, $order_id, $currency, $payment_date){
    $order = new Order();
    return $order->record_payment($pay_id, $amt, $customer_id, $order_id, $currency, $payment_date);
}

function get_user_orders_ctr($order_id){
    $order = new Order($order_id);
    return $order->get_user_orders($order_id);
}

?>