<?php 
/**
 * Testimonial Block template.
 *
 * @param array $block The block settings and attributes.
 */

 // ACF fields
 $link = get_field('link');
 $text = get_field('text');
 $style = get_field('style');

 $buttonStyle = 'tps-btn__primary';
 
 if ($style == 'Primary') $buttonStyle = 'tps-btn__primary';
 else if ($style == 'Secondary') $buttonStyle = 'tps-btn__secondary';
 else if ($style == 'Outline') $buttonStyle = 'tps-btn__outline';
 


 // Need both $link and $text ACF fields
 if ($link AND $text) :

  // Support custom "anchor" values.
  $anchor = '';
  if ( ! empty( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
  }

  // Create class attribute allowing for custom "className" and "align" values.
  $class_name = 'tps-button-block';
  if ( ! empty( $block['className'] ) ) {
      $class_name .= ' ' . $block['className'];
  }
  if ( ! empty( $block['align'] ) ) {
      $class_name .= ' align-' . $block['align'];
  }
  // alignleft
  // aligncenter
?>

<!-- Button block here - really nothing else going on? -->
<div class="mb-4 <?php echo $class_name; ?>" <?php echo $anchor; ?>>
  <a class="tps-btn <?php echo $buttonStyle; ?>" href="<?php echo $link; ?>"><?php echo $text; ?></a>
</div>


<?php endif; ?>