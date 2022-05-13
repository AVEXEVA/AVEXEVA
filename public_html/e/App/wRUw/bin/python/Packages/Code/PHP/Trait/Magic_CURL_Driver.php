<?php
if(!trait_exists('magic_curl_driver')){
  trait magic_curl_driver {
    function getAccess()
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_URL            => 'https://nouveauillinois.my.salesforce.com/' . '/services/oauth2/token',
                CURLOPT_POST           => TRUE,
                CURLOPT_POSTFIELDS     => http_build_query(
                    array(
                        'grant_type'    => 'password',
                        'client_id'     => '3MVG9KsVczVNcM8xCD3tVC6SIsm6I80kMrqHv6ddeq4hMTfn6QON_v1cccL.737rFBcgYyXPglLQXaMyYiacf',
                        'client_secret' => '94443208BD4749FE794C459A32CEF128C00F04879E0E064DF927683ABB7AABDA',
                        'username'      => 'psperanza@nouveauillinois.com',
                        'password'      => '$imboy89' . 'Ud69giyrJIQYCt2eRveMkBNQy'
                    )
                )
            )
        );

        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        $access_token = (isset($response->access_token) && $response->access_token != '') ? $response->access_token : die('Error - access token missing from response!');
        $instance_url = (isset($response->instance_url) && $response->instance_url != '') ? $response->instance_url : die('Error - instance URL missing from response!');

        return array(
            'accessToken' => $access_token,
            'instanceUrl' => $instance_url
        );
    }
    function salesforce_exec($url)
     {
         $credentials = getAccess();

         $curl = curl_init($credentials['instanceUrl'].$url);
         curl_setopt($curl, CURLOPT_HEADER, false);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: OAuth '.$credentials['accessToken']));

         $json_response = curl_exec($curl);
         curl_close($curl);

         return $json_response;
     }
  }
}
?>
