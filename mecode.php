<?php
include("utils.php");

//config
$url = 'http://reecode.eu';

$app = 'http://mecode.momolog.info';
# $app = 'http://localhost:3000';

$products = array(
    "2"
//, "2"
);


//code
$is_code_invalid = false;
$code = '';
_before();

function _before()
{
    GLOBAL $is_code_invalid, $app, $code;
    if ($code = $_REQUEST['code']) {
        $product = json_decode(get_data("$app/codes/$code/use.json"));
        if ($product->reason == 'used' || $product->reason == 'unknown') {
            $is_code_invalid = $product->reason;
        }
        else {
            $code = '';
            header("Location: $product->url");
        }
    }

    if ( isset($_REQUEST['invoice']) && isset($_REQUEST['sign']) ) {
        $invoice = urlencode($_REQUEST['invoice']);
        $signature = urlencode($_REQUEST['sign']);

        $product = json_decode(get_data("$app/sales/$invoice/$signature/checkout.json"));
        header("Location: $product->url");
    }

}

function meheader()
{
    Global $app;
    if ( isset($_REQUEST['invoice']) && isset($_REQUEST['sign']) ) {
        $invoice = urlencode($_REQUEST['invoice']);
        $signature = urlencode($_REQUEST['sign']);
        echo "<pre>";
        print_r($_REQUEST);
        echo "</pre>";
        echo  ("<pre> Location: $app/sales/<$invoice>/<$signature>/checkout.json </pre>");
    }
    if ($invoice = $_REQUEST['invoice'] && $signature == $_REQUEST['sign']){
        $code = json_decode(get_data("$app/sales/$invoice/$signature/checkout.json"));
//        header("Location: $product->url");
        echo ("<meta $app/sales/$invoice/$signature/checkout.json />");
    }
    echo "
        <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
        <script src='mecode/mecode.js'></script>
    ";
}

//    <script type='text/javascript'>
//      if (typeof jQuery == 'undefined') {
//          document.write(unescape("%3Cscript src='/js/jquery.min.js' type='text/javascript'%3E%3C/script%3E'));
//    </script>
//codes
function mecode()
{
    GLOBAL $is_code_invalid, $code;
    echo "<div class='mecode'>";
        if ($is_code_invalid) {
            $err_msg = array (
                'used' => 'shade! somebody ate the cake before!',
                'unknown' => "I don't know about this code. sorry!"
            );
            echo "<label class='err'>$err_msg[$is_code_invalid]</label>";
        }

        echo  "
            <form action='' method='post'>
              Code: <input type='text' name='code' class='code' value='$code'/>
              <input type='submit' />
            </form>";
    echo "</div>";
}


//products
function product_form($p_id)
{
    Global $url, $app;
    return get_data("$app/products/$p_id/form?return_url=$url");
}

function meproducts($products)
{
    foreach ($products as $p) {
        echo product_form($p);
    }
}
?>
