<?php

chdir(ROOT.'..');
define('DRUPAL_ROOT', getcwd());
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

require ROOT.'config.php';

function getOrderCartHelper($id)
{
 
  // get all open orders
  if($id == NULL)
  {
    $efq = new EntityFieldQuery();
    $result = $efq->entityCondition('entity_type', 'uc_order')
      ->propertyCondition('order_status', array('processing', 'pending', 'in_checkout'), 'IN')
      ->execute();
      
    return $result['uc_order'];
  }
  // get single 
  else
  {
    return uc_order_load($id);

  }
  return FALSE;
  
}
// response_code Q=pending, P=complete
function getOrderWithStatusFromCartHelper($id, $response_code)
{
	$response = getOrderCartHelper($id);
  $orders = array();
	if ($response){
    if($id === NULL)
    {

      // loop over all orders
        foreach($response as $orderResponse){
           $order = uc_order_load($orderResponse->order_id);
           $valid = FALSE;
           if($response_code === 'P')
           {
            if($order->order_status === 'completed')
            {
              $valid = TRUE;
            }
            
           }
           else if($response_code === 'Q')
           {
            if($order->order_status === 'processing' || $order->order_status === 'pending'  || $order->order_status === 'in_checkout')
            {
              $valid = TRUE;
            }       
           }         
          if($valid)
           {
           
              $ret = array (
                "order_id" => $order->order_id,
	              "currency" =>	$order->currency,
	              "order_total" =>	$order->order_total
	            );
              array_push($orders, $ret);
            }
      }
      
      
    }
    else 
    {

      if($response->order_id == $id)
      {

       $valid = FALSE;
       if($response_code === 'P')
       {
        if($response->order_status === 'completed')
        {
          $valid = TRUE;
        }
        
       }
       else if($response_code === 'Q')
       {
        if($response->order_status === 'processing' || $response->order_status === 'pending'  || $response->order_status === 'in_checkout')
        {
          $valid = TRUE;
        }       
       }
       if($valid)
       {
       
          $ret = array (
            "order_id" => $id,
	          "currency" =>	$response->currency,
	          "order_total" =>	$response->order_total
	        );
          array_push($orders, $ret);
        }
      }
    }  
  }
	return $orders;
}

function sendToCart($order_id, $statusCode, $total)
{
  $response = array();
  $order = uc_order_load($order_id);

  if($order) 
  {
    if($statusCode === 'P')
    {
        uc_order_update_status($order_id, 'completed');
        // mark the payment
        
        uc_payment_enter($order_id, $order->payment_method,$order->order_total, $order->uid, NULL, '', REQUEST_TIME); 
        // note the payment confirmation
        uc_order_comment_save($order_id, 0, t("Customer's bitshares payment has confirmed."), 'admin', 'completed');



        $response['url'] =  baseURL.'user/'.$order->uid.'/orders';
         uc_cart_empty($order->uid);
	  }
    else if($statusCode === 'C')
    {
        uc_order_update_status($order_id, 'canceled');
        uc_order_comment_save($order_id, 0, t("Customer cancelled this order from the checkout form."), 'admin', 'canceled');
        $response['url'] = baseURL . 'cart/checkout';
    }
  }
  else
  {
    $response['error'] = 'Could not find this order in the system, please review the Order ID and Memo';
  }
	
  
	return $response;
}
function getOpenOrdersUser()
{

	$openOrderList = array();
  // find open orders status id (not paid)
	$result = getOrderWithStatusFromCartHelper(NULL, 'Q');
  foreach ($result as $responseOrder) {
		$newOrder = array();
		$total = $responseOrder['order_total'];
		$total = number_format((float)$total,2);		
		$newOrder['total'] = $total;
		$newOrder['currency_code'] = $responseOrder['currency'];
		$newOrder['order_id'] = $responseOrder['order_id'];
		$newOrder['date_added'] = 0;
		array_push($openOrderList,$newOrder);    
	}
	return $openOrderList;
}
function isOrderCompleteUser($memo, $order_id)
{
  // find orders with id order_id and status id (completed)
	$result = getOrderWithStatusFromCartHelper($order_id, 'P');
	foreach ($result as $responseOrder) {
			$total = $responseOrder['order_total'];
			$total = number_format((float)$total,2);
			$asset = btsCurrencyToAsset($responseOrder['currency']);
			$hash =  btsCreateEHASH(accountName,$order_id, $total, $asset, hashSalt);
			$memoSanity = btsCreateMemo($hash);		
			if($memoSanity === $memo)
			{	
				return TRUE;
			}
	}
	return FALSE;	
}
function doesOrderExistUser($memo, $order_id)
{
  // find orders with id order_id and status id (not paid)
	$result = getOrderWithStatusFromCartHelper($order_id, 'Q');
	foreach ($result as $responseOrder) {
			$total = $responseOrder['order_total'];
			$total = number_format((float)$total,2);
			$asset = btsCurrencyToAsset($responseOrder['currency']);
      $hash =  btsCreateEHASH(accountName,$order_id, $total, $asset, hashSalt);
      $memoSanity = btsCreateMemo($hash);
			if($memoSanity === $memo)
			{	
				$order = array();
				$order['order_id'] = $order_id;
				$order['total'] = $total;
				$order['asset'] = $asset;
				$order['memo'] = $memo;	
				return $order;
			}
	}
	return FALSE;
}

function completeOrderUser($order)
{

  $response = sendToCart($order['order_id'], 'P', $order['total']);  
	return $response;
}
function cancelOrderUser($order)
{
  $response = sendToCart($order['order_id'], 'C', $order['total']);  
	return $response;
}
function cronJobUser()
{
	return 'Success!';
}
function createOrderUser()
{
	$order_id    = $_REQUEST['order_id'];
	$asset = btsCurrencyToAsset($_REQUEST['code']);
	$total = number_format((float)$_REQUEST['total'],2);
	$hash =  btsCreateEHASH(accountName,$order_id, $total, $asset, hashSalt);

	$memo = btsCreateMemo($hash);
	$ret = array(
		'accountName'     => accountName,
		'order_id'     => $order_id,
		'memo'     => $memo
	);
	
	return $ret;	
}

?>