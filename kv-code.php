<?php

echo getcwd();
include("kv-utils.php");
include('kv-config.php');

_init();

function _init() {
  GLOBAL $is_code_invalid, $code, $email, $download_url;
  $is_code_invalid  = false;
  $code             = '';
  $email            = '';
  $download_url     = '';

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

# products
function KV_product($p_id, $submit_label) {
  list($action,$hidden_inputs) = KV_product_vars($p_id);

  echo "
    <form class='to-paypal' action='$action'>
      $hidden_inputs
      <input type='submit' value='$submit_label' />
    </form>
  ";
};

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
