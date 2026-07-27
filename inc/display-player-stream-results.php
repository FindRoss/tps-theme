<?php 

function displayPlayerStreamResults($apps, $term_id) { ?>
  <div class="row">
    <div class="col-12 col-lg-8">

      <?php 
        // APPS_TO_DISPLAY variable must match number on Player.js
        // This number is related to the Javascript to show more function
        $APP_TO_DISPLAY = 8;
        $appsLeftToDisplay = (count($apps) - $APP_TO_DISPLAY);
        $loopCount = 1;

        // Stats
        $statAppearances = count($apps);
        $statFirstAppearance = '';
        $statLastAppearance = '';
        $statTotalWinnings = 0; 

        // Need these variable to later compare to the other dates to work out what is first / last
        $firstAppDateObject = DateTime::createFromFormat('d/m/Y', $apps[0]['date']);
        $firstDateTimestamp = $lastDateTimestamp = $firstAppDateObject->getTimestamp();
      ?>

      <!-- HCL RESULTS -->
      <div class="tps-table-results player-stream-results">
        <table class="table">
          <tbody>

            <?php foreach ($apps as $app) : ?>
              
              <?php if ($loopCount <= $APP_TO_DISPLAY) { ?>

                <?php  
                  $hcl_winnings = $app['tournament_winnings'];
                  
                  $winnings_to_positive = abs($hcl_winnings); 
                  $winnings_formatted = number_format(intval($winnings_to_positive));
                  
                  $app_title = get_the_title($app['id']);
                  $app_permalink = get_permalink($app['id']);

                  // Convert the date string to a UNIX timestamp using strtotime
                  $app_timestamp = strtotime(str_replace('/', '-', $app['date']));

                  // Format the timestamp as 'd M Y' (e.g., '30 Oct 2023')
                  $app_formatted_date = date('d M Y', $app_timestamp);
                  ?>

                  <tr>
                    <td><a href="<?php echo $app_permalink; ?>"><?php echo $app_title; ?></a></td>
                    <td><?php echo $app_formatted_date; ?></td>
                    <td class="text-end">
                      <div class="fw-bold <?php echo ($hcl_winnings < 0) ? 'text-danger' : 'text-success'; ?>">
                        <?php echo ($hcl_winnings < 0) ? '-' : ''; ?>$<?php echo $winnings_formatted; ?>
                      </div>
                    </td>
                  </tr>
              
              <?php } ?>
              
              <?php 
              // Total Winnings 
              $statTotalWinnings += $app['tournament_winnings']; 

              // Calculate First and Latest Appearance
              $appDateObj = DateTime::createFromFormat('d/m/Y', $app['date']);
              $appDateTimestamp = $appDateObj->getTimestamp();

              if ($appDateTimestamp < $firstDateTimestamp) {
                  $firstDateTimestamp = $appDateTimestamp;
              } 

              if ($appDateTimestamp > $lastDateTimestamp) {
                  $lastDateTimestamp = $appDateTimestamp;
              }

              ?>
              
              <?php $loopCount++; ?>
            <?php endforeach; ?>

          </tbody>
        </table>

        <?php if ($appsLeftToDisplay > 0) { ?>
          <div class="d-flex justify-content-center" id="hclResultsBtn">
            <button type="button" class="btn btn-link" onclick="newPaintAppRows(event, '<?php echo $term_id; ?>')">Show <?php echo $appsLeftToDisplay; ?> more appearances <i class="fa-solid fa-arrow-down"></i></button>
          </div>
        <?php }; ?>

      </div>
      <!-- /HCL RESULTS --> 
    </div><!-- .col --> 
    
    <!-- HCL STATS --> 
    <div class="col-12 col-lg-4">

      <?php 
        $stats = array(
          array(
            'title' => 'Appearancecs',
            'data'  => $statAppearances
          ),
          array (
            'title' => 'Net Profit',
            'data'  => ($statTotalWinnings < 0 ? '-' : '') . '$' . (number_format(abs($statTotalWinnings)))
          ),
          array (
            'title' => 'First Appearance',
            'data'  => date('d M Y', $firstDateTimestamp)
          ),
          array (
            'title' => 'Last Appearance',
            'data'  => date('d M Y', $lastDateTimestamp)
          )
          );

        foreach($stats as $stat) { ?>
          <div class="results-info-box">
            <div class="results-info-box__title"><?php echo $stat['title']; ?></div>
            <div class="results-info-box__data"><?php echo $stat['data']; ?></div>
          </div>
        <?php } ?>
    </div>

  </div><!-- .row --> 

<?php }