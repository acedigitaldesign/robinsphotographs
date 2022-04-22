<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php 
// Only instantiates if cookie not set
if( ! isset($_COOKIE['acecookieconsent']) || $_COOKIE['acecookieconsent'] !== "accepted") :
?>

<div class="cookie-notice" data-cookie-notice data-active="false">
  <div class="container pt-5 pb-0">
    <div class="row">

      <div class="col-l-10 col-m-8 col-s-12">
        <p class="font-size-4">This website uses cookies. In compliance with data protection laws, by using this website you must agree to our use of cookies. For more information, please review our <a href="/cookie-policy/" class="link-primary">Cookie Policy</a> and <a href="/privacy-policy/" class="link-primary">Privacy Policy</a>. </p>
      </div>

      <div class="col-l-2 col-m-4 col-s-12">
        <button class="button button-primary button-full" data-cookie-notice-button>Accept</button>
      </div>

    </div>  
  </div>
</div>
<div class="cookie-notice__overlay" data-cookie-notice-overlay></div>

<?php endif; ?>