<?php defined('BASEPATH') OR exit('No direct script access allowed');

$ci = &get_instance();

$ci->load->model('user_model');
$user = $ci->user_model->get_user_data(TEST_CHAT_ID,TRUE);
?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-118596148-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-118596148-2');
</script>
