<?php
  include("utils.php");

  # config
  # FIXME: config should go to config.yaml
  function config($key){
    $config = array(
      'client_url'  => 'http://reecode.eu/index.php',
      # 'client_url'  => 'http://reecode.local/index.php',
      'kvcode_url'  => 'http://keevee-code.momolog.info'
      # 'kvcode_url'  => 'http://localhost:3000'
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

      $product = json_from(config('kvcode_url')."/sales/$invoice/$sign/checkout.json");
      //header("Location: $product->url");
      $download_url = $product->url;

      return;
    }

    switch($action) {
      case 'a_recieve':
        if ($code) {
          $product = json_from(config('kvcode_url')."/codes/$code/use.json?email=$email");
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

  function meheader() { ?>
    <script type='text/javascript' src='mecode/vendor.js'></script>
    <script type='text/javascript' src='mecode/mecode.js'></script>

    <link type='text/css' href='mecode/boxy/boxy.css' rel='stylesheet' />
    <link type='text/css' href='mecode/mecode.css'    rel='stylesheet' />
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
  function mecode() {
    GLOBAL $is_code_invalid, $code;

    echo "<div class='mecode'>";

    if ($is_code_invalid) {
      $err_msg = array(
        'used'    => 'Schade! Somebody ate the cake before!',
        'unknown' => "I don't know about this code. Sorry!"
      );
      echo "<label class='err'>$err_msg[$is_code_invalid]</label>";
    }

    echo  "
      <form action='' method='post'>
        <table cellpadding='0' cellspacing='0' border='0'>
          <tr><td class='textwhite'>reesponse code</td></tr>
          <tr><td>
            <input name='code'                  value='$code'     size='11' maxlength='50' class='reesponse code' />
            <input name='action'  type='hidden' value='a_recieve' size='11' maxlength='50' />
          </td></tr>
          <tr><td class='textwhite'>email</td></tr>
          <tr><td><input name='email'                 value='$email'    size='25' maxlength='50' /></td></tr>
          <tr>
            <td align='right'>
              <img src='img/1x1_trans.gif' width='1' height='5' alt='' border='0' /><br />
              <input name='reeceive' type='submit' value='reeceive...!' />
            </td>
          </tr>
        </table>
      </form>
    ";
    echo "</div>";
  }

  # products
  function meproduct($p_id) {
    $f = json_from(config('kvcode_url')."/products/$p_id/form.json?return_url=".config('client_url'));

    echo "
      <form class='to-paypal' action='$f->action'>
        $f->hidden_inputs
        <input name='reeceive' type='submit' value='Reecieve ...' />
      </form>
    ";
  };

  function meproducts($products) {
    foreach ($products as $p) { meproduct($p); }
  }
