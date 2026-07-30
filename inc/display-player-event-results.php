<?php

function displayPlayerEventResults($apps, $event_post_type) {
  ?>
  <div class="row">
    <div class="col-12 col-lg-8">

      <?php
        $APP_TO_DISPLAY = 8;
        $appsLeftToDisplay = (count($apps) - $APP_TO_DISPLAY);
        $loopCount = 1;

        $statAppearances = count($apps);
        $statTotalWinnings = 0;

        // Only seeded from rows that actually have a parseable date
        $firstDateTimestamp = null;
        $lastDateTimestamp = null;
      ?>

      <div class="tps-table-results player-event-results">
        <table class="table">
          <tbody>

            <?php foreach ($apps as $app) : ?>

              <?php if ($loopCount <= $APP_TO_DISPLAY) { ?>

                <?php
                  $event_winnings = $app['tournament_winnings'];
                  $winnings_to_positive = abs($event_winnings);
                  $winnings_formatted = number_format(intval($winnings_to_positive));

                  $app_title = get_the_title($app['id']);
                  $app_permalink = get_permalink($app['id']);

                  $app_formatted_date = '';
                  if (!empty($app['date'])) {
                    $app_timestamp = strtotime(str_replace('/', '-', $app['date']));
                    if ($app_timestamp) {
                      $app_formatted_date = date('d M Y', $app_timestamp);
                    }
                  }
                  ?>

                  <tr>
                    <td><a href="<?php echo $app_permalink; ?>"><?php echo $app_title; ?></a></td>
                    <td><?php echo $app_formatted_date; ?></td>
                    <td class="text-end">
                      <div class="fw-bold <?php echo ($event_winnings < 0) ? 'text-danger' : 'text-success'; ?>">
                        <?php echo ($event_winnings < 0) ? '-' : ''; ?>$<?php echo $winnings_formatted; ?>
                      </div>
                    </td>
                  </tr>

              <?php } ?>

              <?php
              $statTotalWinnings += $app['tournament_winnings'];

              if (!empty($app['date'])) {
                $appDateObj = DateTime::createFromFormat('d/m/Y', $app['date']);
                if ($appDateObj) {
                  $appDateTimestamp = $appDateObj->getTimestamp();

                  if ($firstDateTimestamp === null || $appDateTimestamp < $firstDateTimestamp) {
                    $firstDateTimestamp = $appDateTimestamp;
                  }
                  if ($lastDateTimestamp === null || $appDateTimestamp > $lastDateTimestamp) {
                    $lastDateTimestamp = $appDateTimestamp;
                  }
                }
              }
              ?>

              <?php $loopCount++; ?>
            <?php endforeach; ?>

          </tbody>
        </table>

        <?php if ($appsLeftToDisplay > 0) { ?>
          <div class="d-flex justify-content-center" id="eventResultsBtn-<?php echo esc_attr($event_post_type); ?>">
            <button type="button" class="btn btn-link" onclick="newPaintAppRows(event, '<?php echo $event_post_type; ?>')">Show <?php echo $appsLeftToDisplay; ?> more appearances <i class="fa-solid fa-arrow-down"></i></button>
          </div>
        <?php }; ?>

      </div>
    </div><!-- .col -->

    <div class="col-12 col-lg-4">

      <?php
        $stats = array(
          array(
            'title' => 'Appearances',
            'data'  => $statAppearances
          ),
          array(
            'title' => 'Net Profit',
            'data'  => ($statTotalWinnings < 0 ? '-' : '') . '$' . (number_format(abs($statTotalWinnings)))
          ),
          array(
            'title' => 'First Appearance',
            'data'  => $firstDateTimestamp !== null ? date('d M Y', $firstDateTimestamp) : 'Unknown'
          ),
          array(
            'title' => 'Last Appearance',
            'data'  => $lastDateTimestamp !== null ? date('d M Y', $lastDateTimestamp) : 'Unknown'
          )
        );

        foreach ($stats as $stat) { ?>
          <div class="results-info-box">
            <div class="results-info-box__title"><?php echo $stat['title']; ?></div>
            <div class="results-info-box__data"><?php echo $stat['data']; ?></div>
          </div>
        <?php } ?>
    </div>

  </div><!-- .row -->
  <?php
}