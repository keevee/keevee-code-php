<?php
include("utils.php");

//config
$url = 'http://reecode.eu';

$app = 'http://mecode.momolog.info';
# $app = 'http://localhost:3000';

//code
$is_code_invalid = false;
$code = '';
$email = '';
_before();

function _before()
{
    GLOBAL $is_code_invalid, $app, $code, $email;
    $email = $_REQUEST['email'];
    if ($code = $_REQUEST['code']) {
        $product = json_from("$app/codes/$code/use.json");
        if ($product->reason == 'used' || $product->reason == 'unknown') {
            $is_code_invalid = $product->reason;
        }
        else {
            $code = '';
            header("Location: $product->url");
        }
    }

    if (isset($_REQUEST['invoice']) && isset($_REQUEST['sign'])) {
        $invoice = urlencode($_REQUEST['invoice']);
        $signature = urlencode($_REQUEST['sign']);

        $product = json_from("$app/sales/$invoice/$signature/checkout.json");
        header("Location: $product->url");
    }

}

function meheader()
{
    Global $app;
    if (isset($_REQUEST['invoice']) && isset($_REQUEST['sign'])) {
        $invoice = urlencode($_REQUEST['invoice']);
        $signature = urlencode($_REQUEST['sign']);
    }
    if ($invoice = $_REQUEST['invoice'] && $signature == $_REQUEST['sign']) {
        $code = json_from("$app/sales/$invoice/$signature/checkout.json");
//        header("Location: $product->url");
        echo ("<meta $app/sales/$invoice/$signature/checkout.json />");
    }
    echo "
        <script type='text/javascript' src='mecode/jquery.js'></script>
        <script type='text/javascript' src='mecode/mecode.js'></script>
    ";
}

//codes
function mecode()
{
    GLOBAL $is_code_invalid, $code;
    echo "<div class='mecode'>";
    if ($is_code_invalid) {
        $err_msg = array(
            'used' => 'schade! somebody ate the cake before!',
            'unknown' => "I don't know about this code. sorry!"
        );
        echo "<label class='err'>$err_msg[$is_code_invalid]</label>";
    }

    echo  "
          <form action='' method='post'>
              <table cellpadding='0' cellspacing='0' border='0'>
                    <tr><td class='textwhite'>reesponse codeffffffff</td>
                    </tr>
                    <tr><td><input name='code' class='reesponse code' type='text' value='$code' size='11' maxlength='50' /></td>
                    </tr>
                    <tr><td class='textwhite' height='17' valign='bottom'>email address</td>
                    </tr>
                    <tr><td><input name='email' type='text' value='$email' size='25' maxlength='50' /></td>
                    </tr>
                    <tr><td align='right'><img src='img/1x1_trans.gif' width='1' height='5' alt='' border='0' /><br />
                            <input name='reeceive' type='submit' value='reeceive...!' /></td>
                    </tr>
                </table>
            </form>
            ";
    echo "</div>";
}


//products
function meproduct($p_id)
{
    echo  "
          <form action='' class='me-product-form'>
            <table cellpadding='0' cellspacing='0' border='0'>
                <tr><td class='textwhite' height='17' valign='bottom'>email address</td>
                </tr>
                <tr><td><input name='email' type='text' value='$email' size='25' maxlength='50' /></td>
                </tr>
                <tr><td align='right'><img src='img/1x1_trans.gif' width='1' height='5' alt='' border='0' /><br />
                        <input name='reeceive' type='submit' value='Buy ...' /></td>
                </tr>
            </table>
          </form>
    ";
}

;
function meproducts($products)
{
    foreach ($products as $p) {
        meproduct($p);
    }
}
