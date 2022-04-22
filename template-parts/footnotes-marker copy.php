<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Set defaults
$default_args = array(
  "index" => null,
);

// Merge passed-in args with defaults
$footnote_marker = wp_parse_args($args, $default_args);

?>

<sup class="footnotes-marker">
  <a class="footnotes-marker__link" href="#footnote-<?= $footnote_marker['index'] ?>"><?= $footnote_marker['index'] ?></a>
</sup>