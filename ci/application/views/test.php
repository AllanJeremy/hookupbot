<?php defined('BASEPATH') OR exit('No direct script access allowed');

$ci = &get_instance();

$ci->load->model('user_model');
$user = $ci->user_model->get_user_data(TEST_CHAT_ID,TRUE);

//Testing setting user data
$data = array(
  'id' => '123453',
  'is_bot' => FALSE,
  'first_name' => 'Allan',
  'last_name' => 'Jeremy',
  'username' => 'aj',
  'language_code' => 'en-gb'
);
$ci->user_model->set_user_data($data);
?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-118596148-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-118596148-2');
</script>
