<?php
  include("utils.php");

  # config
  # FIXME: config should go to config.yaml
  function config($key){
    $config = array(
      'client_url'  => 'http://reecode.eu/index.php',
      'KV_code_url'  => 'http://keevee-code.momolog.info'
      # 'KV_code_url'  => 'http://localhost:3000'
    );
    return @$config[$key];
  }

  # code
  # FIXME: global vars are evil!
  $is_code_invalid  = false;
  $code             = '';
  $email            = '';
  $download_url     = '';

  _init();

  function _init() {
    GLOBAL $is_code_invalid, $code, $email, $download_url;
    $email    = @$_REQUEST['email'];
    $action   = @$_REQUEST['action'];
    $code     = @$_REQUEST['code'];
    $invoice  = @$_REQUEST['invoice'];
    $sign     = @$_REQUEST['sign'];

    if ($invoice && $sign) {
      $invoice  = urlencode($invoice);
      $sign     = urlencode($sign);

      $product = json_from(config('KV_code_url')."/sales/$invoice/$sign/checkout.json");
      //header("Location: $product->url");
      $download_url = $product->url;

      return;
    }

    switch($action) {
      case 'a_recieve':
        if ($code) {
          $product = json_from(config('KV_code_url')."/codes/$code/use.json?email=$email");
          if ($product->reason == 'used' || $product->reason == 'unknown') {
            $is_code_invalid = $product->reason;
          } else {
            $code = '';
            header("Location: $product->url");
          }
          break;
        }
    }
  }

  function KV_header() { ?>
    <script type='text/javascript' src='mecode/vendor.js'></script>
    <script type='text/javascript' src='mecode/kv-code.js'></script>

    <link type='text/css' href='mecode/boxy/boxy.css' rel='stylesheet' />
    <link type='text/css' href='mecode/kv-code.css'    rel='stylesheet' />
    <?php
      global $download_url;
      if ($download_url) {
        echo "
          <script type='text/javascript'>
            window.downloadUrl = '$download_url';
          </script>
        ";
      }
  }

  # codes
  function KV_code() {
    list($code, $err) = KV_code_vars();

    echo "<div class='KV-code'>";

    if ($err) {
      echo "<label class='err'>$err</label>";
    }

    echo  "
      <form action='' method='post'>
        <table cellpadding='0' cellspacing='0' border='0'>
          <tr><td class='textwhite'>reesponse code</td></tr>
          <tr><td>
            <input name='code' class='reesponse code' type='text' value='$code' size='11' maxlength='50' />
            <input name='action' type='hidden' value='a_recieve' size='11' maxlength='50' />
          </td> </tr>
          <tr><td align='right'><img src='img/1x1_trans.gif' width='1' height='5' alt='' border='0' /><br />
            <input name='reeceive' type='submit' value='reeceive...!' /></td>
          </tr>
        </table>
      </form>
    ";
    echo "</div>";
  }

  # products
  function KV_product($p_id) {
    list($action,$hidden_inputs) = KV_product_vars($p_id);

    echo "
      <form class='to-paypal' action='$action'>
        $hidden_inputs
        <input name='reeceive' type='submit' value='Reecieve ...' />
      </form>
    ";
  };

  function KV_products($products) {
    foreach ($products as $p) { meproduct($p); }
  }


  function KV_code_vars(){
    GLOBAL $is_code_invalid, $code;
    $err = "";
    if ($is_code_invalid) {
      $err_msg = array(
        'used'    => 'Schade! Somebody ate the cake before!',
        'unknown' => "I don't know about this code. Sorry!"
      );
      $err = $err_msg[$is_code_invalid];
    }

    return array($code, $err);
  }

  function KV_product_vars($p_id){
    $f = json_from(config('KV_code_url')."/products/$p_id/form.json?return_url=".config('client_url'));

    return array($f->action, $f->hidden_inputs);
  }
