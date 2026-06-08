<?php 

/**
 * Generates HTML for a BBC card.
 *
 * @param array $data An array containing data for the BBC card.
 *                    - 'title' (string): The title of the card.
 *                    - 'description' (string): The description of the card.
 *                    - 'link' (string): The URL link for the card.
 *                    - 'image' (string): The URL of the image for the card.
 * @return void
 */

function generateBBCCardHTML($data = array()) { 
  
  if (!isset($data['title']) OR !isset($data['link']) OR !isset($data['image'])) return;
  
  ?>

   <div class="bbc-card mt-3">
      <a class="tps-card-link" href="<?php echo $data['link']; ?>">
        <div class="bbc-card__layout">
          <div class="bbc-card__content d-flex flex-column justify-content-center align-items-start">
            <h3 class="h2 bbc-card__title tps-card__title"><?php echo $data['title']; ?></h3>
            <p><?php echo $data['description']; ?></p>
            <button class="tps-btn tps-btn__outline">Read more</button>
          </div>
          <div class="bbc-card__media">
            <img class="tps-card-image bbc-card__image" src="<?php echo $data['image']; ?>" alt="Poker plays playing on Hustler Casino Live"> 
          </div>
        </div>
      </a>
    </div>

<?php }