function innerHTML() {
  // This HTML was on the page
  // <div class="row justify-content-center">
  //   <div class="col-12 col-md-6 col-lg-4">
  //     <button class="btn btn-lg btn-dark text-white text-center w-100 mt-4" id="players-more-button" hidden>Show More</button>
  //   </div>
  // </div>
};


let playerContainer = document.querySelector('.players-section');
let moreBtn = document.querySelector('#players-more-button');
let filterBtn = document.querySelector('#filter-btn');
let postsPerPage = 9;
let postCount = 9;

function showMoreClick() {
  console.log('clicked the button!');

  fetch(`<?php echo get_site_url(); ?>/wp-json/wp/v2/player/?offset=${postCount}&per_page=${postsPerPage}&_embed&orderby=title&order=asc&streams=<?php echo $streaming_platform; ?>`)
    .then(response => response.json())
    .then(data => {


      // Check if this is the last page.
      if (data.length === postsPerPage) {
        postCount += 9;

      } else {
        // Else remove the show more button.
        moreBtn.hidden = true;
      }

      // Display the content
      data.map(d => {
        let playerCard = `
  <div class="col-6 col-lg-4 mt-3">
    <div class="h-100">
      <a class="tps-card-link" href="${d.link}">
        <div class="tps-card p-2 p-lg-3 rounded h-100">
          <div class="d-flex justify-content-center">
            <img src="${d._embedded['wp:featuredmedia'][0].source_url}" alt="${d.title.rendered}" style="width: 100px; height: 100px; object-fit: cover;" class="rounded-circle border" width="100" height="100" />
          </div>
          <div class="pt-3">
            <h3 class="h6 text-center tps-card__title m-0 text-break">${d.title.rendered}</h3>
          </div>
        </div>
      </a>
    </div>
  </div>
  `;
        playerContainer.insertAdjacentHTML('beforeend', playerCard);
      });
    });
}


// On Page Load
document.addEventListener("DOMContentLoaded", function () {
  fetch('<?php echo get_site_url(); ?>/wp-json/wp/v2/player/?per_page=10&orderby=title&order=asc&streams=<?php echo $streaming_platform; ?>')
    .then(response => response.json())
    .then(data => {
      if (data.length > postsPerPage) {
        moreBtn.addEventListener('click', showMoreClick);
        moreBtn.hidden = false;
      }
    });
});
