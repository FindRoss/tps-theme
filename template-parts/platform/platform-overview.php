<?php
  // Social
  $socials = array();
  $facebook      = get_field('facebook_url');
  if ($facebook) { $socials['Facebook'] = $facebook; }
  $instagram     = get_field('instagram_url');
  if ($instagram) { $socials['Instagram'] = $instagram; }
  $twitter       = get_field('twitter_url');
  if ($twitter) { $socials['Twitter'] = $twitter; }
  $youtube       = get_field('youtube_url');
  if ($youtube) { $socials['YouTube'] = $youtube; }
  $twitch        = get_field('twitch_url');
  if ($twitch) { $socials['Twitch'] = $twitch; }
  $hendonmob     = get_field('hendonmob_url');
  if ($hendonmob) { $socials['Hendonmob'] = $hendonmob; }

  // Latest streams
  $streams_term = get_field('streaming_platform', get_the_ID());

  // Normalize to a real WP_Term object so get_field() can read the term-level 'icon' field
  // (ACF needs the actual term / a "term_123" string as its $post_id, not just a bare ID).
  $streams_term_obj = is_a($streams_term, 'WP_Term') ? $streams_term : get_term($streams_term, 'streams');
  $stream_icon_url  = ($streams_term_obj && !is_wp_error($streams_term_obj)) ? get_field('icon', $streams_term_obj) : '';

  $latest_streams_query = new WP_Query(array(
    'post_type'      => 'cash_game',
    'posts_per_page' => 5,
    'meta_key'       => 'date',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
    'tax_query'      => array(
      array('taxonomy' => 'streams', 'terms' => $streams_term),
    ),
  ));
  $total_streams = $latest_streams_query->found_posts;

  // $platform_results_url is set by templates/platform.php, which requires this file
  if (empty($platform_results_url)) {
    $platform_results_url = get_permalink(get_the_ID());
  }
?>

<div class="platform-overview">

  <div class="platform-overview__grid">
    <div class="platform-overview__body">
      <?php
        $introduction = get_field('introduction');
        if ($introduction) echo $introduction;
        the_content();
      ?>
    </div>

    <aside class="platform-info-card">
      <?php if (!empty($socials)) : ?>
        <div class="platform-info-card__section">
          <div class="platform-info-card__label">Socials</div>
          <div class="platform-info-card__socials">
            <?php foreach ($socials as $so_title => $so_link) : ?>
              <a class="platform-info-card__social-link" href="<?php echo esc_url($so_link); ?>" target="_blank" rel="noopener">
                <?php echo esc_html($so_title); ?> <?php echo get_svg_icon('external-link'); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="platform-info-card__divider"></div>
      <?php endif; ?>

      <div class="platform-info-card__stats">
        <div class="platform-info-card__stat">
          <div class="platform-info-card__stat-value"><?php echo esc_html($total_streams); ?></div>
          <div class="platform-info-card__stat-label">Streams</div>
        </div>
      </div>

      <!-- YouTube-stats injection target: kept in the DOM (hidden until populated) so the
           existing script's document.querySelector('.profile-table__body') keeps working. -->
      <table class="profile-table platform-info-card__youtube-table" hidden>
        <tbody class="profile-table__body"></tbody>
      </table>
    </aside>
  </div><!-- .platform-overview__grid -->

  <?php if ($latest_streams_query->have_posts()) : ?>
  <section class="platform-latest-streams">
    <div class="platform-latest-streams__head">
      <h2 class="platform-latest-streams__title">Latest streams</h2>
      <a class="platform-latest-streams__all-link" href="<?php echo esc_url($platform_results_url); ?>">
        All <?php echo esc_html($total_streams); ?> streams <?php echo get_svg_icon('external-link'); ?>
      </a>
    </div>

    <table class="tps-streams-table">
      <thead>
        <tr>
          <th class="tps-streams-table__col-thumbnail"></th>
          <th class="tps-streams-table__col-stream">Stream</th>
          <th class="tps-streams-table__col-type">Type</th>
          <th class="tps-streams-table__col-stakes">Stakes</th>
          <th class="tps-streams-table__col-date">Date</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($latest_streams_query->have_posts()) : $latest_streams_query->the_post();
          $dateString    = get_field('date');
          $formattedDate = formatTableDate($dateString);
          $display_title = get_field('display_title');
          $game_type     = get_field('game_type');
          $stakes_played = get_field('stakes_played');
          $permalink     = get_the_permalink();
        ?>
          <tr class="tps-streams-table__row">
            <td class="tps-streams-table__col-thumbnail">
              <?php if ($stream_icon_url) : ?>
                <a class="tps-streams-table__thumb" href="<?php echo esc_url($permalink); ?>" tabindex="-1" aria-hidden="true"><img src="<?php echo esc_url($stream_icon_url); ?>" alt="" loading="lazy" /></a>
              <?php endif; ?>
            </td>
            <td class="tps-streams-table__col-stream">
              <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($display_title); ?></a>
              <div class="tps-streams-table__meta-mobile">
                <?php echo esc_html($formattedDate); ?> · <?php echo esc_html($game_type); ?> · <?php echo esc_html($stakes_played); ?>
              </div>
            </td>
            <td class="tps-streams-table__col-type"><?php echo esc_html($game_type); ?></td>
            <td class="tps-streams-table__col-stakes"><?php echo esc_html($stakes_played); ?></td>
            <td class="tps-streams-table__col-date"><?php echo esc_html($formattedDate); ?></td>
          </tr>
        <?php endwhile; wp_reset_postdata(); ?>
      </tbody>
    </table>

    <div class="platform-show-more">
      <a class="tps-btn" href="<?php echo esc_url($platform_results_url); ?>">Show more streams</a>
    </div>
  </section>
  <?php endif; ?>

</div><!-- .platform-overview -->

<!-- YOUTUBE API CALL -->
<?php if ( get_field('youtube_id') ) : ?>

<?php $youtube_id = get_field('youtube_id'); ?>

<script type="text/javascript">

  const username = '';

  fetch('https://youtube.googleapis.com/youtube/v3/channels?key=AIzaSyCoZeOJ32QTo_6P7fh6b8vnaLf0pJ-qEw0&id=<?php echo $youtube_id; ?>&part=statistics,snippet,id')
    .then(response => response.json())
    .then(data => {

      // PublishedAt
      // console.log(data.items[0].snippet.publishedAt);

      const {
        subscriberCount,
        viewCount,
        videoCount
      } = data.items[0].statistics;

      const {
        publishedAt
      } = data.items[0].snippet;

      // Can use id for videos
      const id = data.items[0].id;

      const options = { year: 'numeric', month: 'long' };
      const publishedDate = new Date(publishedAt);
      const publishedDateFormatted = publishedDate.toLocaleDateString("en-US", options);

      // Get table
      const table = document.querySelector('.profile-table__body');

      // Create element: subscriberCount
      let trSub = document.createElement('tr');
      let tdSubOne = document.createElement('td');
      let tdSubTwo = document.createElement('td');

      let tdSubOneContent = document.createTextNode('YouTube Subscribers');
      let tdSubTwoContent = document.createTextNode(Number(subscriberCount).toLocaleString());

      tdSubOne.appendChild(tdSubOneContent);
      tdSubTwo.appendChild(tdSubTwoContent);

      trSub.appendChild(tdSubOne);
      trSub.appendChild(tdSubTwo);

      table.appendChild(trSub);

      // Create element: viewCount
      let trView = document.createElement('tr');
      let tdViewOne = document.createElement('td');
      let tdViewTwo = document.createElement('td');

      let tdViewOneContent = document.createTextNode('YouTube Views');
      let tdViewTwoContent = document.createTextNode(Number(viewCount).toLocaleString());

      tdViewOne.appendChild(tdViewOneContent);
      tdViewTwo.appendChild(tdViewTwoContent);

      trView.appendChild(tdViewOne);
      trView.appendChild(tdViewTwo);

      table.appendChild(trView);

      // Create element: videoCount
      let trVid = document.createElement('tr');
      let tdVidOne = document.createElement('td');
      let tdVidTwo = document.createElement('td');

      let tdVidOneContent = document.createTextNode('YouTube Videos');
      let tdVidTwoContent = document.createTextNode(Number(videoCount).toLocaleString());

      tdVidOne.appendChild(tdVidOneContent);
      tdVidTwo.appendChild(tdVidTwoContent);

      trVid.appendChild(tdVidOne);
      trVid.appendChild(tdVidTwo);

      table.appendChild(trVid);

      // Published Date
      let trPub = document.createElement('tr');
      let tdPubOne = document.createElement('td');
      let tdPubTwo = document.createElement('td');

      let tdPubOneContent = document.createTextNode('YouTube Channel Created');
      let tdPubTwoContent = document.createTextNode(publishedDateFormatted);

      tdPubOne.appendChild(tdPubOneContent);
      tdPubTwo.appendChild(tdPubTwoContent);

      trPub.appendChild(tdPubOne);
      trPub.appendChild(tdPubTwo);

      table.appendChild(trPub);

      // Reveal the (previously hidden) card now that it's populated
      table.closest('.platform-info-card__youtube-table').removeAttribute('hidden');

    });
</script>

<?php endif; ?>
<!-- END YOUTUBE API CALL -->
