<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="submission-review sub-container">
  <table>

    <thead>
      <tr>
        <th><strong>Service</strong></th>
        <th><strong>Price</strong> <span class="submission-review__th-qualifier">(per photo)</span></th>
      </tr>
    </thead>

    <tbody>
      <tr class="submission-review__item">
        <td class="submission-review__item-name">
          <dl class="variation">
            <dt class="variation-Colorise" data-restoration-label></dt>
          </dl>
        </td>
        <td class="submission-review__item-total" data-restoration-price></td>
      </tr>
      <tr class="submission-review__item">
        <td class="submission-review__item-name">
          <dl class="variation">
            <dt class="variation-Colorise" data-colour-label></dt>
            <dd class="variation-Colorise"><p data-colour-option></p></dd>
          </dl>
        </td>
        <td class="submission-review__item-total" data-colour-price></td>
      </tr>
      <tr class="submission-review__item">
        <td class="submission-review__item-name">
          <dl class="variation">
            <dt class="variation-Colorise" data-prints-label></dt>
            <dd class="variation-Colorise"><p data-prints-option></p></dd>
            <div data-sizes>
              <dt class="variation-Colorise" data-prints-sizes-label></dt>
              <dd class="variation-Colorise"><p data-prints-sizes-value></p></dd>
            </div>

          </dl>
        </td>
        <td class="submission-review__item-total" data-prints-price></td>
      </tr>
      <tr class="submission-review__item">
        <td class="submission-review__item-name">
          <dl class="variation">
            <dt class="variation-Colorise" data-speed-label>Processing Speed:</dt>
            <dd class="variation-Colorise"><p data-speed-option></p></dd>
          </dl>
        </td>
        <td class="submission-review__item-total" data-speed-price></td>
      </tr>
    </tbody>
    <tfoot>
      <tr class="submission-review__subtotal">
        <th><strong data-subtotal-label>Subtotal</strong> <span class="submission-review__subtotal-qualifier"> (per photo)</span></th>
        <td data-subtotal-price></td>
      </tr>
      <tr class="submission-review__quantity">
        <th data-quantity-label>Photos Uploaded</th>
        <td data-quantity-value></td>
      </tr>
      <tr class="submission-review__order-total">
        <th data-total-label>Total</th>
        <td><strong data-total-price></strong></td>
      </tr>
    </tfoot>
  </table>
</div>