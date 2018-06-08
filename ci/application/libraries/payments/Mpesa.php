<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mpesa
{
    public $ci;

    private static $_consumer_key = '8gs6AytyESXeJCOH9TAv3OmIQ9rIJFIU';//TODO: move into config file
    private static $_private_key = 'JGCFMUGaG1n9iCbx';//TODO: Move into config file
    protected $access_token;

    //Mpesa result codes
    private static $_result_codes = array(
        0 => 'Success',
        1 => 'Insufficient Funds',
        2 => 'Less Than Minimum Transaction Value',
        3 => 'More Than Maximum Transaction Value',
        4 => 'Would Exceed Daily Transfer Limit',
        5 => 'Would Exceed Minimum Balance',
        6 => 'Unresolved Primary Party',
        7 => 'Unresolved Receiver Party',
        8 => 'Would Exceed Maxiumum Balance',
        11 => 'Debit Account Invalid',
        12 => 'Credit Account Invaliud',
        13 => 'Unresolved Debit Account',
        14 => 'Unresolved Credit Account',
        15 => 'Duplicate Detected',
        17 => 'Internal Failure',
        20 => 'Unresolved Initiator',
        26 => 'Traffic blocking condition in place'
    );

    //Test credentials for use in sandbox during development #TODO: Move to config file
    private static $test_credentials = array(
        'shortcode1' => '600614',
        'initiator_name' => 'TestInit614',
        'security_credential' => '1234qwer',
        'gen_security_credential' => 'g5julf8A3kYJGyk2emA0trfl44QkwL3T+32975KJzm5tXjLrLLOIOaPE78ZRXa3vIY1OeHt9ME4tuKVFqkB7+rR9dmS6J49kGgFEOTQvMZaclJmOuFCZdn0y2TlJT1NtjpuUIJKseiad9ImAdozg8IGClW4BghGlJ07kB9iucrflTPeO+uyvJxbEP52WjQOjh281xLwg+cPGX0JN2TNqX8gV7rC9IBJ5swPFzSzIy7A04rn45eIKo5F5uAjtuvUa93A1bWv8FJQP+Nr6N7KQrsOshFZcdgX60IBEOu3xLtrovwM7ETOIITRhGiqDpGDYvTb+ikWyfQYQI2pjqy9xbg==',
        'shortcode2' => '600000',
        'phone_number' => '254708374149',
        'expiry_date' => '2018-05-30T15:30:32+03:00',
        'lipa_na_mpesa_shortcode' => '174379',
        'lipa_na_mpesa_passkey' => 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919
        '
    );

    //Constructor
    function __construct()
    {
        $this->ci = &get_instance();
        
        $auth = $this->auth();
        $this->access_token = $auth['access_token'];
    }

    // Authenticate the request
    protected function auth()
    {
        $oauth_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();# Curl handler
        curl_setopt($curl,CURLOPT_URL,$oauth_url);

        $credentials = base64_encode(self::$_consumer_key.':'.self::$_private_key);

        curl_setopt($curl,CURLOPT_HEADER,array('Authorization: Basic '.$credentials));
        curl_setopt($curl,CURLOPT_HEADER,TRUE);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);

        $curl_response = curl_exec($curl);
        return json_decode($curl_response);
    }

    /* 
        COMMANDS
        TransactionReversal - Reversal for an erroneous C2B transaction.
        SalaryPayment - Used to send money from an employer to employees e.g. salaries
        BusinessPayment - Used to send money from business to customer e.g. refunds
        PromotionPayment - Used to send money when promotions take place e.g. raffle winners
        AccountBalance - Used to check the balance in a paybill/buy goods account (includes utility, MMF, Merchant, Charges paid account).
        CustomerPayBillOnline - Used to simulate a transaction taking place in the case of C2B Simulate Transaction or to initiate a transaction on behalf of the customer (STK Push).
        TransactionStatusQuery - Used to query the details of a transaction.
        CheckIdentity - Similar to STK push, uses M-Pesa PIN as a service.
        BusinessPayBill - Sending funds from one paybill to another paybill
        BusinessBuyGoods - sending funds from buy goods to another buy goods.
        DisburseFundsToBusiness - Transfer of funds from utility to MMF account.
        BusinessToBusinessTransfer - Transferring funds from one paybills MMF to another paybills MMF account.
        BusinessTransferFromMMFToUtility - Transferring funds from paybills MMF to another paybills utility account.
    */
    //
}