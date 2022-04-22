<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$i = ( $args['index'] ) ? $args['index'] : $args['auto-index'];

?>

<sup id="footnote-marker-<?= $i ?>" class="footnotes-marker" data-footnote-marker data-target="footnote-<?= $i ?>">[<?= $i ?>]</sup>

