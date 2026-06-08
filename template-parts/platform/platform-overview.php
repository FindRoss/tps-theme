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
?>

     


  <div class="row">

    <div class="col-12 col-lg-8 mt-3">
      <?php echo get_field('introduction'); ?>
    </div>

    <div class="col-12 col-lg-8 mt-3">
        <!-- TABLE WITH YOUTUBE API -->
        <?php if(!empty($socials)) : ?>
        <div class="profile-table__wrapper">
          <table class="profile-table">
            <tbody class="profile-table__body">
              <tr>
                <td>Socials</td>
                <td>
                  <?php foreach($socials as $so_title => $so_link) : ?>
                    <div><a href="<?php echo $so_link; ?>" target="_blank"><?php echo $so_title ?> <i class="fa-solid fa-arrow-up-right-from-square"></i></a></div>
                  <?php endforeach; ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div><!-- .col -->


    <div class="col-12 mt-4">
      <div class="platform-row">
        <?php the_content(); ?>
      </div>
    </div>

  </div><!-- .row -->




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
      
    });
</script>

<?php endif; ?>
<!-- END YOUTUBE API CALL -->