Proxy Detection API proxycheck PHP

This is a simple function to perform user checks accessing a web page through a Proxy or VPN using the PROXYCHECK API with a custom tags and checks. 

To use it =

1 = Download the proxycheck.php file;

2 = Drop to the directory of your website you want;

3 = Register at https://proxycheck.io/ and GET your API KEY;
//The API from https://proxycheck.io Grants you to made 1.000 free checks per day.//

4 = Put the API key on the top of file = "YOURAPIKEY" ;

5 = Add in all pages that need to verify the user the following lines (OR JUST PUT IN INDEX.PHP) =


include_once "proxycheck.php";

if (proxycheck_function($_SERVER["REMOTE_ADDR"])) {
    //SEND USER TO A ERROR PAGE IF IS A RETURN FALSE FROM FILE
    echo "<script>window.location.replace('https://dot.com/yourerrorpage');</script>";
}


//Brasilian guy willing to help the next one, good luck!!
