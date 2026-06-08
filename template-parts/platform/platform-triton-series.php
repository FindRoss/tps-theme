<?php 

// start_date and end_date have a return format currently of 'd/m/Y'
$series = get_terms(array(
  'taxonomy'   => 'triton_series',
  'hide_empty' => false,
  'meta_key'   => 'start_date', // Replace 'your_date_field' with the name of your ACF date field
  'orderby'    => 'meta_value', // Order by the value of the date field
  'order'      => 'DESC', // Or 'DESC' for descending order
));

if ($series) { ?>
  <div class="series-cards">

    <?php foreach($series as $s) {
      $id             = $s->term_id;
      $location       = get_field('location', $s);
      $year           = get_field('year', $s);
      $landscapeImage = get_field('landscape_image', $s);
      $link           = get_term_link($s);

      // Get the dates
      $startDate = get_field('start_date', $s);
      $endDate   = get_field('end_date', $s);

      if ($startDate AND $endDate) { 

        // Create DateTime objects
        $startDateObj = DateTime::createFromFormat('d/m/Y', $startDate);
        $endDateObj   = DateTime::createFromFormat('d/m/Y', $endDate);

        // Get the UNIX timestamp
        $startDateTimestamp = $startDateObj->getTimestamp();
        $endDateTimestamp   = $endDateObj->getTimestamp();

        // Start Dates
        $startMonth   = date("F", $startDateTimestamp);
        $startDisplayDate = date("jS F", $startDateTimestamp);
        
        // End Dates
        $endMonth = date("F", $endDateTimestamp);
        $endDisplayDate = date("jS F Y", $endDateTimestamp);

        // Only show the month twice if I really need to
        if ($startMonth == $endMonth) {
          $startDisplayDate = date("jS", $startDateTimestamp);
        }
      } ?>

      <div class="series-card">
        <a class="tps-card-link" href="<?php echo $link; ?>">
          <div class="series-card__layout">
            <div class="series-card__content">
              <h2 class="tps-card__title"><?php echo $location . ' ' . $year; ?></h2>
              <div class="series-card__date tsp-card-no-change">
                <?php if ($startDate AND $endDate) { 
                  echo $startDisplayDate . ' - ' . $endDisplayDate;
                }; ?>
              </div>
            </div>
            <div class="series-card__media">
              <img class="series-card__image tps-card-image" src="<?php echo $landscapeImage['url']; ?>" alt="<?php echo $landscapeImage['alt']; ?>">
            </div>
          </div>
        </a>
      </div>

    <?php }; ?>
  </div>
<?php }; ?>
