<?php

function displayPlayerEventResults($apps, $event_post_type, $is_open = false) {
  // APPS_TO_DISPLAY must match the slice offset used by the "show more"
  // handler in player.js.
  $APP_TO_DISPLAY = 8;
  $total_apps = count($apps);
  $apps_left = $total_apps - $APP_TO_DISPLAY;

  $total_winnings = 0;
  $first_ts = null;
  $last_ts = null;

  foreach ($apps as $app) {
    $total_winnings += $app['tournament_winnings'];
    if (empty($app['date'])) continue;
    $app_date = DateTime::createFromFormat('d/m/Y', $app['date']);
    if (!$app_date) continue;
    $ts = $app_date->getTimestamp();
    if ($first_ts === null || $ts < $first_ts) $first_ts = $ts;
    if ($last_ts === null || $ts > $last_ts) $last_ts = $ts;
  }

  $net_color     = $total_winnings < 0 ? 'text-danger' : 'text-success';
  $net_formatted = ($total_winnings < 0 ? '-' : '') . '$' . number_format(abs($total_winnings));

  // Item name inside the accordion doesn't need the "Results" suffix —
  // the section it lives in is already titled "Tournament results".
  $event_name = preg_replace('/\s+Results$/i', '', tps_get_event_heading_for_post_type($event_post_type));
  ?>

  <div class="tps-accordion-item<?php echo $is_open ? ' is-open' : ''; ?>">
    <button type="button" class="tps-accordion-item__header">
      <span class="tps-accordion-item__toggle" aria-hidden="true"></span>
      <span class="tps-accordion-item__name"><?php echo esc_html($event_name); ?></span>
      <span class="tps-accordion-item__summary">
        <span class="tps-accordion-item__apps"><?php echo esc_html($total_apps); ?> appearances</span>
      </span>
    </button>

    <div class="tps-accordion-item__body">
      <div class="tps-accordion-stats">
        <div class="tps-accordion-stats__item">
          <div class="tps-accordion-stats__label">Appearances</div>
          <div class="tps-accordion-stats__value"><?php echo esc_html($total_apps); ?></div>
        </div>
        <div class="tps-accordion-stats__item">
          <div class="tps-accordion-stats__label">Net profit</div>
          <div class="tps-accordion-stats__value <?php echo esc_attr($net_color); ?>"><?php echo esc_html($net_formatted); ?></div>
        </div>
        <div class="tps-accordion-stats__item">
          <div class="tps-accordion-stats__label">First appearance</div>
          <div class="tps-accordion-stats__value tps-accordion-stats__value--muted"><?php echo $first_ts ? esc_html(date('d M Y', $first_ts)) : '—'; ?></div>
        </div>
        <div class="tps-accordion-stats__item">
          <div class="tps-accordion-stats__label">Last appearance</div>
          <div class="tps-accordion-stats__value tps-accordion-stats__value--muted"><?php echo $last_ts ? esc_html(date('d M Y', $last_ts)) : '—'; ?></div>
        </div>
      </div>

      <table class="tps-streams-table" id="event-results-<?php echo esc_attr($event_post_type); ?>">
        <thead>
          <tr>
            <th class="tps-streams-table__col-stream">Event</th>
            <th class="tps-streams-table__col-date">Date</th>
            <th class="tps-streams-table__col-result">Result</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 0;
          foreach ($apps as $app) :
            if ($i >= $APP_TO_DISPLAY) break;
            $i++;

            $winnings = $app['tournament_winnings'];
            $color    = $winnings < 0 ? 'text-danger' : 'text-success';
            $amount   = ($winnings < 0 ? '-' : '') . '$' . number_format(abs($winnings));
            $date_fmt = '';
            if (!empty($app['date'])) {
              $date_ts = strtotime(str_replace('/', '-', $app['date']));
              if ($date_ts) $date_fmt = date('d M Y', $date_ts);
            }
          ?>
            <tr class="tps-streams-table__row">
              <td class="tps-streams-table__col-stream">
                <a href="<?php echo esc_url($app['permalink']); ?>"><?php echo esc_html($app['title']); ?></a>
                <div class="tps-streams-table__meta-mobile">
                  <?php echo esc_html($date_fmt); ?> · <span class="<?php echo esc_attr($color); ?>"><?php echo esc_html($amount); ?></span>
                </div>
              </td>
              <td class="tps-streams-table__col-date"><?php echo esc_html($date_fmt); ?></td>
              <td class="tps-streams-table__col-result"><span class="<?php echo esc_attr($color); ?>"><?php echo esc_html($amount); ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <?php if ($apps_left > 0) : ?>
        <div class="tps-accordion-item__more">
          <button type="button" class="tps-btn" data-show-more-event data-type="<?php echo esc_attr($event_post_type); ?>" data-table="event-results-<?php echo esc_attr($event_post_type); ?>">
            Show <?php echo esc_html($apps_left); ?> more appearances
          </button>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <?php
}
