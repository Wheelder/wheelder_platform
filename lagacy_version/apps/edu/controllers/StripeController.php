<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require $path . '/pool/libs/composer/vendor/autoload.php';

trait StripeController {
  private $stripe_secret_key = 'sk_test_51KBe3uBG6J77VsYBuVQP8Yav2DrZk3GFri8GKWJm0iv6G5CayB55YhBYZQZd7RlOFQ9JjU5vHO46gL8N1nkDqucU005pbBYCpp';
  private $stripe_publishable_key = 'sk_test_51KBe3uBG6J77VsYBuVQP8Yav2DrZk3GFri8GKWJm0iv6G5CayB55YhBYZQZd7RlOFQ9JjU5vHO46gL8N1nkDqucU005pbBYCpp';

  private $stripe_secret_key_connect = 'sk_test_51KBe3uBG6J77VsYBuVQP8Yav2DrZk3GFri8GKWJm0iv6G5CayB55YhBYZQZd7RlOFQ9JjU5vHO46gL8N1nkDqucU005pbBYCpp';
  private $stripe_publishable_key_connect = 'sk_test_51KBe3uBG6J77VsYBuVQP8Yav2DrZk3GFri8GKWJm0iv6G5CayB55YhBYZQZd7RlOFQ9JjU5vHO46gL8N1nkDqucU005pbBYCpp';

  public function stripe_connect_account($first_name, $last_name, $email, $country, $type,$year, $month, $day,$business_type){
       
      
    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->accounts->create(
        [
          'country' => $country,
          'type' => $type,
          'business_type' => $business_type,
          'individual' => [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'dob'=>[
              'day'=>$day,
              'month'=>$month,
              'year'=>$year
            ],
          ],
          'capabilities' => [
            'card_payments' => ['requested' => true],
            'transfers' => ['requested' => true],
            //'legacy_payments' => ['requested' => true],
             
            'sepa_debit_payments'=>['requested' =>true],
            'sofort_payments'=>['requested' =>true],
            'giropay_payments'=>['requested' =>true],
            'acss_debit_payments'=>['requested' =>true],
            'p24_payments'=>['requested' =>true],
            'eps_payments'=>['requested' =>true],
          ],
          
        ]
      );
    //  return json_encode($result['id']);
      return $result['id'];

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }




public function createExternalAccount($connectAccountId, $externalAccountDetails) {
    \Stripe\Stripe::setApiKey('YOUR_STRIPE_SECRET_KEY');

    try {
        $account = \Stripe\Account::retrieve($connectAccountId);
        $externalAccount = $account->external_accounts->create($externalAccountDetails);

        // External account created successfully, you can handle the response here

        return $externalAccount; // Return the created external account object
    } catch (\Stripe\Exception\CardException $e) {
        // Handle card errors
        $error = $e->getError();
        // Log or display error message
    } catch (\Stripe\Exception\RateLimitException $e) {
        // Handle rate limit errors
    } catch (\Stripe\Exception\InvalidRequestException $e) {
        // Handle invalid request errors
    } catch (\Stripe\Exception\AuthenticationException $e) {
        // Handle authentication errors
    } catch (\Stripe\Exception\ApiConnectionException $e) {
        // Handle API connection errors
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Handle generic API errors
    }

    return false; // Creating external account failed
}



    public function stripe_customer($name, $email)
    {
  
      \Stripe\Stripe::setApiKey($this->stripe_secret_key);
      try {
        $result = \Stripe\Customer::create([
          'name' => $name,
          'email' => $email
        ]);
        //return json_encode($result['id']);
        return $result['id'];
  
      } catch (Exception $e) {
        $api_error = $e->getMessage();
      }
  
    }


    public function create_payment_method($type, $number, $exp_month, $exp_year, $cvc, $name, $email, $phone, $address_line1, $address_line2, $address_city, $address_state, $address_country, $address_zip)
  {
    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->paymentMethods->create([
        'type' => $type,

        'card' => [
          'number' => $number,
          'exp_month' => $exp_month,
          'exp_year' => $exp_year,
          'cvc' => $cvc,
        ],
        'billing_details' => [
          'name' => $name,
          'address' => [
            'line1' => $address_line1,
            'line2' => $address_line2,
            'postal_code' => $address_zip,
            'city' => $address_city,
            'state' => $address_state,
            'country' => $address_country,
          ],
          'email' => $email, 
          'phone' => $phone,

        ],

      ]);
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }
  }
  
  

  public function payment_method_details($payment_id)
  {
    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->paymentMethods->retrieve(
        $payment_id,
        []
      );
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }
  }


  ////////////////////////////////////////////////////////////////////Above Tested and Working methods//////////////////////////////////////////


    public function list_connected_accounts()
  {
    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);

    //  $result = $stripe->accounts->all();
      $result = $stripe->accounts->all(['limit' => 3]);

      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }
  }

  //create a bank for a source
  public function create_bank_account($customer_id, $source_id)
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);

      $result = $stripe->customers->createSource(
        $customer_id,
        ['source' => $source_id]
      );

      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }
  //vtify a bank for a source
  public function verify_bank_account($customer_id, $bank_account_id, $amount1, $amount2)
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);

      $result = $$stripe->customers->verifySource(
        $customer_id,
        $bank_account_id,
        ['amounts' => [$amount1, $amount2]]

      );
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }

  

  
  public function checkup_balance()
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->balance->retrieve([]);
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }

  public function customer_balance($customer_id)
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->customers->allBalanceTransactions($customer_id, []);
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }

  public function credit_to_customer($customer_id)
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->customers->createBalanceTransaction(
        $customer_id,
        [
          'amount' => 60,
          'currency' => 'cad',
        ]
      );
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }




  public function create_token_cards($number, $exp_month, $exp_year, $cvc)
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->tokens->create([
        'card' => [
          'number' => $number,
          'exp_month' => $exp_month,
          'exp_year' => $exp_year,
          'cvc' => $cvc,
        ],
      ]);
      return $result['id'];

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }

  public function create_token_bank($country, $currency, $account_holder_name, $account_holder_type, $routing_number, $account_number)
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->tokens->create([
        'bank_account' => [
          'country' => $country,
          'currency' => $currency,
          'account_holder_name' => $account_holder_name,
          'account_holder_type' => $account_holder_type,
          'routing_number' => $routing_number,
          'account_number' => $account_number,
        ],
      ]);
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }

  public function attach_payment_method_customer($payment_id, $customer_id)
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->paymentMethods->attach(
        $payment_id,
        ['customer' => $customer_id]
      );
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }

  //Create  a payment method
  public function add_payment_method($type, $card_number, $exp_month, $exp_year, $cvc)
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->paymentMethods->create([
        'type' => $type,
        'card' => [
          'number' => $card_number,
          'exp_month' => $exp_month,
          'exp_year' => $exp_year,
          'cvc' => $cvc,
        ],
      ]);
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }

  //Attach a payment method to a connected account

  public function attach_payment_method_connected_account($payment_id, $connected_account_id)
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->paymentMethods->attach(
        $payment_id,
        ['stripe_account' => $connected_account_id]
      );
    
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }

  public function detach_payment_method($payment_id)
  {

    try {

      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);

      $result = $stripe->paymentMethods->detach(
        $payment_id,
        []
      );
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();
    }

  }

  public function create_source($currency, $email)
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);

      $result = $stripe->sources->create([
        "type" => null,
        "currency" => $currency,
        "owner" => [
          "email" => $email
        ]
      ]);

      return $result['id'];

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }

  public function retrieve_source($source_id)
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->sources->retrieve(
        $source_id,
        []
      );

      return json_encode($result);
    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();
    }

  }

  public function attach_source($source_id, $customer_id)
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->customers->createSource(
        $customer_id,
        [
          'source' => $source_id,
        ]
      );
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();
    }

  }

  public function payout_normal($amount,$destination ,$currency)
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->payouts->create([
      'amount' => $amount, 
      'destination' => $destination,
      'currency' => $currency
    ]);

      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();
    }

  }

  public function external_bank_account($stripe_connect_id,$token_id){

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->accounts->createExternalAccount(
        $stripe_connect_id,
        [
          'external_account' => $token_id
      
        ]
      );

      return $result['id'];

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();
    }





  }

  public function list_customer_payment_methods($customer_id)
  {

    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->paymentMethods->all([
        'customer' => $customer_id,
        'type' => 'card',
      ]);
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }



  public function retrieve_customer_payment_method($payment_id, $customer_id)
  {
    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->customers->retrievePaymentMethod(
        $customer_id,
        $payment_id,
        []
      );
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }
  }

  public function update_payment_method()
  {
    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }
  }

  //Retrieve a connect account
  public function retrieve_connect_account($account_id)
  {
    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->accounts->retrieve(
        $account_id,
        []
      );
      return json_encode($result);
    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }
  }

  public function login_link_express_account($account_id)
  {
    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->accounts->createLoginLink(
        $account_id,
        []
      );
      return json_encode($result);
    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }
  }

  public function create_destination_charge($account_id,$amount)
  {
    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->charges->create([
        'amount' => $amount,
        'currency' => 'usd',
        'destination' => [
          'account' => $account_id,
        ],
      ]);
      return json_encode($result);
    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }
  }

  public function create_cardholder($type, $name, $email, $phone_number, $line1, $city, $state, $country, $postal_code)
  {
    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->issuing->cardholders->create([
        'type' => $type,
        'name' => $name,
        'email' => $email,
        'phone_number' => $phone_number,
        'billing' => [
          'address' => [
            'line1' => $line1,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'postal_code' => $postal_code,
          ],
        ],
      
      ]);
      return json_encode($result);
    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }
  }

  public function create_card($cardholder_id, $currency, $type)
  {
    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->issuing->cards->create([
        'cardholder' => $cardholder_id,
        'currency' => $currency,
        'type' => $type,
      ]);
      return json_encode($result);
    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }
  }
  
  public function instant_payout($amount, $currency ,$method, $source_type, $stripe_account)
  {
    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->payouts->create(
        [
          'amount' => $amount,
          'currency' => $currency,
          'method' => $method,
          'source_type' => $source_type,
        ],
        ['stripe_account' => $stripe_account]
      );

    

      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }
  }

  public function create_stripe_connect_accounts($first_name, $last_name, $email, $country, $type, $business_type)
  {


    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = $stripe->accounts->create(
        [
          'country' => $country,
          'type' => $type,
          'business_type' => $business_type,
          'individual' => [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
          ],
          'capabilities' => [
            'card_payments' => ['requested' => true],
            'transfers' => ['requested' => true],
            'legacy_payments'=>['requested' =>true],
            'crypto_transfers'=>['requested' =>true],
            'sepa_debit_payments'=>['requested' =>true],
            'sofort_payments'=>['requested' =>true],
            'giropay_payments'=>['requested' =>true],
            'acss_debit_payments'=>['requested' =>true],
            'p24_payments'=>['requested' =>true],
            'eps_payments'=>['requested' =>true],
          ],
          
        ]
      );
      $res = array(json_encode($result['id']), $email);
      return $res[0];

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }

  public function account_cap($account_id){
    try {
      $stripe = new \Stripe\StripeClient($this->stripe_secret_key);
      $result = 
      $stripe->accounts->retrieveCapability(
        $account_id,
        'card_payments',
        []
      );
      
      return json_encode($result);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }
  }

  public function payout($user_stripe_id, $amount, $currency)
  {


    try {

      // Set your secret key. Remember to switch to your live secret key in production.
      // See your keys here: https://dashboard.stripe.com/apikeys
      \Stripe\Stripe::setApiKey($this->stripe_secret_key);

      $transfer = \Stripe\Transfer::create([
        "amount" => $amount,
        "currency" => $currency,
        "destination" => $user_stripe_id,
      ]);
      //return json_encode($transfer);
      //transfer status

      if($transfer){
        return true;
      }else{
        return false;
      }

    

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }

  public function normal_payout($amount, $currency,$user_stripe_id)
  {


    try {

      // Set your secret key. Remember to switch to your live secret key in production.
      // See your keys here: https://dashboard.stripe.com/apikeys
      \Stripe\Stripe::setApiKey($this->stripe_secret_key);

      $transfer = \Stripe\Transfer::create([
        "amount" => $amount,
        "currency" => $currency,
        "destination" => $user_stripe_id,
      ]);
      return json_encode($transfer);

    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }

  public function charge_payment($token, $amount, $currency, $description)
  {


    try {

      \Stripe\Stripe::setApiKey($this->stripe_secret_key);

      $charge = \Stripe\Charge::create([
        'amount' => $amount,
        'currency' => $currency,
        'description' => $description,
        'source' => $token,
      ]);

      return json_encode($charge);



    } catch (Error $e) {
      echo 'Error: ' . $e->getMessage();

    }

  }

  public function charge_amount($itemPrice, $currency, $itemName)
  {

    $itemPriceCents = round($itemPrice * 100);

    try {

      \Stripe\Stripe::setApiKey($this->stripe_secret_key);
      // Create PaymentIntent with amount and currency 
      $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $itemPriceCents,
        'currency' => $currency,
        'description' => $itemName,
        'payment_method_types' => [
          'card'
        ]
      ]);

      $output = [
        'id' => $paymentIntent->id,
        'clientSecret' => $paymentIntent->client_secret
      ];

      echo json_encode($output);
    } catch (Error $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }


  }

  public function create_customer($name, $email)
  {

    \Stripe\Stripe::setApiKey($this->stripe_secret_key);
    try {
      $result = \Stripe\Customer::create([
        'name' => $name,
        'email' => $email
      ]);
      return json_encode($result);

    } catch (Exception $e) {
      $api_error = $e->getMessage();
    }

  }


  public function connect_account_external_payment_method($connect_id, $external_account)
  {

    \Stripe\Stripe::setApiKey($this->stripe_secret_key);
    try {
      $result = $external_account = \Stripe\Account::createExternalAccount(
        $connect_id,
        [
          'external_account' => $external_account,
        ]
      );
      return json_encode($result);

    } catch (Exception $e) {
      $api_error = $e->getMessage();
    }

  }

  public function subscription($price, $currency, $planInterval, $planName)
  {

    try {
      $plan_priceCents = round($price * 100);
      \Stripe\Stripe::setApiKey($this->stripe_secret_key);
      // Create price with subscription info and interval 
      $result = \Stripe\Price::create([
        'unit_amount' => $plan_priceCents,
        'currency' => $currency,
        'recurring' => ['interval' => $planInterval],
        
        'product_data' => ['name' => $planName],

      ]);
      return json_encode($result);

    } catch (Exception $e) {
      $api_error = $e->getMessage();
    }

  }





  public function user_fp($user_id,$email,$stripe_connect_id,$stripe_customer_id,$source_id,$card_token){
      
    $sql="INSERT INTO financial_profile (user_id,email,stripe_connect_id,stripe_customer_id,stripe_source_id,card_token) 
    values ('$user_id','$email','$stripe_connect_id','$stripe_customer_id','$source_id','$card_token')";
    $result=$this->run_query($sql);
    if($result){
       return true;
    }else{
      return false;
    }
  }


  


    
    }



  




?>