<?php

  /*

  * A PHP Function which checks if the IP Address specified is a Proxy Server utilising the API provided by https://proxycheck.io

  * This function is covered under an MIT License.

  */

  function proxycheck_function($ip) {



    // ------------------------------

    // SETTINGS

    // ------------------------------



    $API_Key = "8073x7-r5g10d-3f3499-61593t"; // Supply your API key between the quotes if you have one (Create account on proxycheck.io)

    $VPN = "1"; // Change this to 1 if you wish to perform VPN Checks on your visitors

    $TAG = "1"; // Change this to 1 to enable tagging of your queries (will show within your dashboard)

    $ASN = "1"; // Change this to 1 to enable check ASN on your visitors 

    // If you would like to tag this traffic with a specific description place it between the quotes.

    // Without a custom tag entered below the domain and page url will be automatically used instead.

    $Custom_Tag = ""; // Example: $Custom_Tag = "My Forum Signup Page";

    // Array of Insecure Proxy or Provider
    
    $PROV = array("Microsoft Corporation", "Yandex Oy", "Facebook, Inc.", "Google LLC", "RWTH Aachen University", "China Internet Network Infomation Center", "Internet Archive", "Serverion LLC", "AT&T Services, Inc.");
    
    // Array of Allowed Countryes
    
    $ALLOWCOUNTRY = array("Brazil", "Portugal");
    
    // ------------------------------

    // END OF SETTINGS

    // ------------------------------

    // By default the tag used is your querying domain and the webpage being accessed

    // However you can supply your own descriptive tag or disable tagging altogether above.

    if ( $TAG == 1 && $Custom_Tag == "" ) {

      $Post_Field = "tag=" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

    } else if ( $TAG == 1 && $Custom_Tag != "" ) {

      $Post_Field = "tag=" . $Custom_Tag;

    } else {

      $Post_Field = "";

    }

    

    // Performing the API query to proxycheck.io/v2/ using cURL

    $ch = curl_init('https://proxycheck.io/v2/' . $ip . '?key=' . $API_Key . '&vpn=' . $VPN . '&asn=' . $ASN);

    

    $curl_options = array(

      CURLOPT_CONNECTTIMEOUT => 30,

      CURLOPT_POST => 1,

      CURLOPT_POSTFIELDS => $Post_Field,

      CURLOPT_RETURNTRANSFER => true

    );


    curl_setopt_array($ch, $curl_options);

    $API_JSON_Result = curl_exec($ch);

    curl_close($ch);

    

    // Decode the JSON from our API

    $Decoded_JSON = json_decode($API_JSON_Result);

    //Receive the provider from api
    
    $PROVRES = $Decoded_JSON->$ip->provider ;
    
    //Receive the country from api
    
    $COUNTRY = $Decoded_JSON->$ip->country ;
  
    //List of tags can used from api to perform checks =
    //"asn": 
    //"provider": 
    //"organisation":
    //"continent":
    //"country": 
    //"isocode": 
    //"region": 
    //"regioncode":
    //"city":
    //"latitude":
    //"longitude":
    //"proxy":
    //"type":

    //OPTIONAL , CHECK IP-WHITELIST FROM YOUR DATABASE TO PERFORM A CUSTOM CHECK TO BYPASS API
    
    $con = mysqli_connect('DBHOST', 'DBUSER', 'DBPASS', 'DB');
    
    $sql = "SELECT * FROM ipw WHERE ip= ? ";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('s',$ip);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result)<=0){

        // Check if the IP we're testing is a proxy server

    if (isset($Decoded_JSON->$ip->proxy) && $Decoded_JSON->$ip->proxy == "yes" ) {

        // A proxy has been detected, Block
        
        return true;

      } else {
        
        // If not a VPN perform other checks (optional)
        
        if(in_array($COUNTRY, $PROV)){
          
          //provider not allowed has been detected, Block 
          
          return true;
            
        } else {

            if (in_array($PROVRES, $ALLOWCOUNTRY)) {
              
              // Country Allowed found, bypass.
              
              return false;
              
            } else {
                      
              // No Allowed country found, block.
              return true;
              
            }
        }

      }
      
    } else {

        return false;

    }

    

    

  }



?>
