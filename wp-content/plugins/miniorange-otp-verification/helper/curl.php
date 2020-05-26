<?php

if(! defined( 'ABSPATH' )) exit;


class MocURLOTP
{

    public static function create_customer($email, $company, $password, $phone = '', $first_name = '', $last_name = '')
    {
        $url 		 = MoConstants::HOSTNAME . '/moas/rest/customer/add';
        $customerKey = MoConstants::DEFAULT_CUSTOMER_KEY;
        $apiKey 	 = MoConstants::DEFAULT_API_KEY;
        $fields = array (
            'companyName' 	 => $company,
            'areaOfInterest' => MoConstants::AREA_OF_INTEREST,
            'firstname' 	 => $first_name,
            'lastname' 		 => $last_name,
            'email' 		 => $email,
            'phone' 		 => $phone,
            'password' 		 => $password
        );
        $json = json_encode($fields);
        $authHeader = self::createAuthHeader($customerKey,$apiKey);
        $response = self::callAPI($url, $json, $authHeader);
        return $response;
    }

    public static function get_customer_key($email, $password)
    {
        $url 		 = MoConstants::HOSTNAME. "/moas/rest/customer/key";
        $customerKey = MoConstants::DEFAULT_CUSTOMER_KEY;
        $apiKey 	 = MoConstants::DEFAULT_API_KEY;
        $fields = array (
            'email' 	=> $email,
            'password'  => $password
        );
        $json = json_encode($fields);
        $authHeader = self::createAuthHeader($customerKey,$apiKey);
        $response = self::callAPI($url, $json, $authHeader);
        return $response;
    }

    public static function check_customer($email)
    {
        $url 		 = MoConstants::HOSTNAME . "/moas/rest/customer/check-if-exists";
        $customerKey = MoConstants::DEFAULT_CUSTOMER_KEY;
        $apiKey 	 = MoConstants::DEFAULT_API_KEY;
        $fields = array(
            'email' 	=> $email,
        );
        $json     = json_encode($fields);
        $authHeader  = self::createAuthHeader($customerKey,$apiKey);
        $response = self::callAPI($url, $json, $authHeader);
        return $response;
    }

    public static function mo_send_otp_token($auth_type,$email='',$phone='')
    {
        $url 		 = MoConstants::HOSTNAME . '/moas/api/auth/challenge';
        $customerKey = !MoUtility::isBlank(get_mo_option('admin_customer_key'))
            ? get_mo_option('admin_customer_key') : MoConstants::DEFAULT_CUSTOMER_KEY;
        $apiKey 	 = !MoUtility::isBlank(get_mo_option('admin_api_key'))
            ? get_mo_option('admin_api_key') : MoConstants::DEFAULT_API_KEY;
        $fields  	 = array(
            'customerKey' 	  => $customerKey,
            'email' 	  	  => $email,
            'phone' 	  	  => $phone,
            'authType' 	  	  => $auth_type,
            'transactionName' => MoConstants::AREA_OF_INTEREST
        );
        $json 		 = json_encode($fields);
        $authHeader  = self::createAuthHeader($customerKey,$apiKey);
        $response 	 = self::callAPI($url, $json, $authHeader);
        return $response;
    }

    public static function validate_otp_token($transactionId,$otpToken)
    {
        $url 		 = MoConstants::HOSTNAME . '/moas/api/auth/validate';
        $customerKey = !MoUtility::isBlank(get_mo_option('admin_customer_key'))
            ? get_mo_option('admin_customer_key') : MoConstants::DEFAULT_CUSTOMER_KEY;
        $apiKey 	 = !MoUtility::isBlank(get_mo_option('admin_api_key'))
            ? get_mo_option('admin_api_key') : MoConstants::DEFAULT_API_KEY;
        $fields 	 = array(
            'txId'  => $transactionId,
            'token' => $otpToken,
        );
        $json 		 = json_encode($fields);
        $authHeader  = self::createAuthHeader($customerKey,$apiKey);
        $response    = self::callAPI($url, $json, $authHeader);
        return $response;
    }

    public static function submit_contact_us(  $q_email, $q_phone, $query  )
    {
        $current_user 	= wp_get_current_user();
        $url    	  	= MoConstants::HOSTNAME . "/moas/rest/customer/contact-us";
        $query  		= '['.MoConstants::AREA_OF_INTEREST.']: ' . $query;
        $customerKey 	= !MoUtility::isBlank(get_mo_option('admin_customer_key'))
            ? get_mo_option('admin_customer_key') : MoConstants::DEFAULT_CUSTOMER_KEY;
        $apiKey 	 	= !MoUtility::isBlank(get_mo_option('admin_api_key'))
            ? get_mo_option('admin_api_key') : MoConstants::DEFAULT_API_KEY;
        $fields = array(
            'firstName'	=> $current_user->user_firstname,
            'lastName'	=> $current_user->user_lastname,
            'company' 	=> $_SERVER['SERVER_NAME'],
            'email' 	=> $q_email,
            'ccEmail'   => MoConstants::FEEDBACK_EMAIL,
            'phone'		=> $q_phone,
            'query'		=> $query
        );
        $field_string = json_encode( $fields );
        $authHeader  = self::createAuthHeader($customerKey,$apiKey);
        $response 	 = self::callAPI($url, $field_string, $authHeader);
        return true;
    }

    public static function forgot_password($email)
    {
        $url 		 = MoConstants::HOSTNAME . '/moas/rest/customer/password-reset';
        $customerKey = get_mo_option('admin_customer_key');
        $apiKey 	 = get_mo_option('admin_api_key');

        $fields 	 = array(
            'email' => $email
        );

        $json 		 = json_encode($fields);
        $authHeader  = self::createAuthHeader($customerKey,$apiKey);
        $response    = self::callAPI($url, $json, $authHeader);
        return $response;
    }


    public static function check_customer_ln($customerKey,$apiKey)
    {
        $url = MoConstants::HOSTNAME . '/moas/rest/customer/license';
        $fields = array(
            'customerId' => $customerKey,
            'applicationName' => MoConstants::APPLICATION_NAME,
            'licenseType' => !MoUtility::micr() ? 'DEMO' : 'PREMIUM',
        );

        $json 		 = json_encode($fields);
        $authHeader  = self::createAuthHeader($customerKey,$apiKey);
        $response    = self::callAPI($url, $json, $authHeader);
        return $response;
    }

    private static function createAuthHeader($customerKey, $apiKey)
    {
        $currentTimestampInMillis = self::getTimestamp();
        if(MoUtility::isBlank($currentTimestampInMillis))
        {
            $currentTimestampInMillis = round(microtime(true) * 1000);
            $currentTimestampInMillis = number_format($currentTimestampInMillis, 0, '', '');
        }
        $stringToHash = $customerKey . $currentTimestampInMillis . $apiKey;
        $authHeader = hash("sha512", $stringToHash);

         $header = [
            "Content-Type"  => "application/json",
            "Customer-Key"  => $customerKey,
            "Timestamp"     => $currentTimestampInMillis,
            "Authorization" => $authHeader
        ];
        return $header;
    }

    private static function getTimestamp()
    {
        $url = MoConstants::HOSTNAME . '/moas/rest/mobile/get-timestamp';
        return self::callAPI($url,null,null);
    }

    

    private static function callAPI($url, $json_string, $headers = ["Content-Type" => "application/json"],$method='POST')
    {
        $args = [
            'method'        => $method,
            'body'          => $json_string,
            'timeout'       => '10000',
            'redirection'   => '10',
            'httpversion'   => '1.0',
            'blocking'      => true,
            'headers'       => $headers,
            'sslverify'     => MO_TEST_MODE ? false: true,
        ];

        $response = wp_remote_post( $url, $args );
        if ( is_wp_error( $response ) ) {
            wp_die("Something went wrong: <br/> {$response->get_error_message()}");
        }
        return wp_remote_retrieve_body($response);
    }

	

	
	public static function send_notif(NotificationSettings $settings)
	{
		$url 		 = MoConstants::HOSTNAME . '/moas/api/notify/send';
		$customerKey = get_mo_option('admin_customer_key');
		$apiKey 	 = get_mo_option('admin_api_key');

		$fields 	 = [
		    'customerKey' => $customerKey,
            'sendEmail' => $settings->sendEmail,
            'sendSMS' => $settings->sendSMS,
            'email' => [
                'customerKey' => $customerKey,
                'fromEmail' => $settings->fromEmail,
                'bccEmail' => $settings->bccEmail,
                'fromName' => $settings->fromName,
                'toEmail' => $settings->toEmail,
                'toName' => $settings->toEmail,
                'subject' => $settings->subject,
                'content' => $settings->message
            ],
            'sms' => [
                'customerKey' => $customerKey,
                'phoneNumber' => $settings->phoneNumber,
                'message' => $settings->message
            ]
        ];

		$json 		 = json_encode ( $fields );
		$authHeader  = self::createAuthHeader($customerKey,$apiKey);
		$response 	 = self::callAPI($url, $json, $authHeader);
		return $response;
	}
}