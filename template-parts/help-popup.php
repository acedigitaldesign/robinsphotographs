<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="help-popup-container container" data-help-popup-container>
  <div class="help-popup" data-help-popup>

      <div class="help-popup__header sub-container">
        <?php image([
          "id"=>8, 
          "width"=>170, 
          "height"=>"auto", 
          "border"=>false, 
          "lightbox"=>false,
          "wrapper-class"=>"mb-0"
          ]);?>
        <div class="help-popup__close" data-close></div>
      </div>

      <div class="help-popup__slides">
        <!-- Slide 0 -->
        <div class="help-popup__slide sub-container" data-slide>
          <h2>Welcome!</h2>
          <p>This is our digital photo restoration order form which you'll use to send us the photographs you'd like restoring. It's <strong>quick and easy</strong> to use and only takes about <strong>90 seconds</strong> to complete.</p>
          <p>If this is your first time using it, we recommend reading through the following guidelines to familiarise yourself with the service.</p>
        </div>

        <!-- Slide 1 -->
        <div class="help-popup__slide sub-container" data-slide>
          <h2>Uploading</h2><span class="font-size-4 ml-3 opacity-7">(1/2)</span>
          <ul class="numbered-list mb-0">
            <li>You can upload a maximum of <strong>10 photos per form submission</strong>. (This is to mitigate errors when uploading lots of large files). However, if you have more than 10 photos, you can complete this form as many times as you need. A new line item is simply made for each submission.</li>
            <li>The maximum file size per uploaded image is <strong>10MB</strong></li>
            <li>You can upload multiple photos at once by holding SHIFT or CMD/CTRL on your keyboard while selecting them</li>
            <li>For the best results, make sure the photos you upload have been scanned at a <strong>minimum of 300dpi</strong> (and ideally no more than 600dpi)</li>
          </ul>
        </div>

        <!-- Slide 2 -->
        <div class="help-popup__slide sub-container" data-slide>
          <h2>Uploading</h2><span class="font-size-4 ml-3 opacity-7">(2/2)</span>
          <p>Note: Any options that you select throughout this form (ie. <?= __('colourising', 'robinsphotographs') ?>, Photo Prints or Priorty Processing) will apply to <strong>ALL photos</strong> uploaded in the current submission. If you have a photo with specific requirements, please upload it separately in a subsequent form submission.</p>
          <p>For example, if you have 5 photos that you would like restoring but only one of them <?= __('colourising', 'robinsphotographs') ?>, you will need to complete this form twice: once for the 4 photos which all share the same requirements (ie. restoration only) and once for the individual photo that requires <?= __('colourising', 'robinsphotographs') ?>.</p>
        </div>

        <!-- Slide 3 -->
        <div class="help-popup__slide sub-container" data-slide>
          <h2><?= __('Colourising', 'robinsphotographs') ?></h2>
          <p>In addition to restoring your photos, we can also turn black and white photos into <?= __('color', 'robinsphotographs') ?>. It's an incredibly popular service!</p>
          <div class="float-s-right ml-s-6 mb-6" data-help-before-after-img>
            <?= do_shortcode('[bafg id="308"]') ?>
          </div>
          <p>If you select this option, you will be asked to share any <?= __('color', 'robinsphotographs') ?> information you know about the photo. For example, the <?= __('color', 'robinsphotographs') ?> of hair, eyes, clothing or uniforms etc.</p>
          <p>This is optional but the more information you can provide helps our restorers provide the most accurate <?= __('color', 'robinsphotographs') ?> treatment.</p>
        </div>

        <!-- Slide 4 -->
        <div class="help-popup__slide sub-container" data-slide>
          <h2>Photo Prints</h2>
          <p>In addition to a digital download of your restored photographs, you can also choose to order a set of photo prints. We currently offer photo prints in the following sizes:</p>
            <ul class="dash-list">
              <li><strong>4 x 6 inches</strong> (10 x 15 cm)</li>
              <li><strong>8 x 10 inches</strong> (20 x 25 cm)</li>
            </ul>
          <p>For each available print size you can order a maximum of 5 photo prints. And remember: your selection will apply to <strong>ALL photos</strong> uploaded in the current submission.</p>
          <p>For example, if you've uploaded 5 photos and choose to order 5 sets of 4 x 6" photo prints, you will receive 25 photo prints in total - 5 for each photo you have uploaded. If you only require photo prints for one particular photograph, please upload that photo on its own in a separate form submission.</p>
        </div>

        <!-- Slide 5 -->
        <div class="help-popup__slide sub-container" data-slide>
          <h2>Processing Speed</h2>
          <p>Restoration takes time, but if you're in a hurry we can prioritise your order. The same amount of care and time will be taken over your order, but you're effectively paying to jump the queue!</p>
          <p>Please note: <strong>Processing speed is not the same as shipping speed</strong>.</p>
          <p>Processing speed is the time it takes for us to digitally restore your photographs and send you a preview via email. Shipping speed is different in that it only applies to orders with photo prints and is the time it takes to receive these prints in the mail.</p>
          <p>If applicable, you can preview the shipping speed on the checkout page.</p>
        </div>

        <!-- Slide 6 -->
        <div class="help-popup__slide sub-container" data-slide>
          <h2>Need Help?</h2>
          <p>If you have large volumes of photographs to restore which are not feasible to send via this form or you have any questions, please contact us at <a class="link-primary" href="mailto:support@robinsphotographs.com">support@robinsphotographs.com</a>. We're very friendly and happy to offer assistance wherever possible.</p>
          <p>Get started with your restoration today!</p>
        </div>

      </div>

      <div class="help-popup__footer sub-container" data-help-popup-footer></div>
    
  </div> 
</div>
