# IPqualityscore-API-PHP
Proxy Detection API IPqualityscore PHP

This is a simple function to perform user checks accessing a web page through a Proxy or VPN using the IPQualityScore API, to use it download the ipqualitscore.php file
drop to the directory of your website you want register at https://www.ipqualityscore.com/ and add your API KEY at the top of the file then add on all pages that the user needs to check with the following lines =


include_once "ipqualitscore.php";

if (proxycheck_function($_SERVER["HTTP_CF_CONNECTING_IP"])) {
    echo "<script>window.location.replace('https://dotcom.com/yourerrorpagetoredrectVPNSuser.html');</script>";
}

//Brasilian guy willing to help the next one, good luck!!
