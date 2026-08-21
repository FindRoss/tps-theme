// These only exist when the player has a News section — guard against
// null so a player with no news doesn't crash the rest of this script.
const playerNewsRow = document.querySelector('#player-news-row');
const loadMoreBtn = document.getElementById('loadMorePlayerNews');
const loadMoreBtnIcon = loadMoreBtn ? loadMoreBtn.querySelector('i') : null;
const loadMorePlayerNewsContainer = document.getElementById('loadMorePlayerNewsContainer');
const spinningAnimationClass = 'spinning-icon';

let usedPosts = [];
let postsToUse = wpApiSettings.posts.map(String);
let availablePosts = [];

function formatDate(dateStr) {
  if (!dateStr) return '';

  // Split the date string into day, month, and year
  const [day, month, year] = dateStr.split('/');

  // Create a new Date object with the year, month, and day (months are 0-indexed)
  const date = new Date(year, month - 1, day);

  // Format the date as 'dd Mon yyyy' using toLocaleDateString() with appropriate options
  const formattedDate = date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });

  // Return the formatted date
  return formattedDate;
}

function formatPostDate(dateString) {
  const date = new Date(dateString);
  const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  const month = monthNames[date.getMonth()];
  const day = date.getDate();

  return `${month} ${day}`;
}

function generateMarkup(d) {

  const hasImage = Array.isArray(d._embedded["wp:featuredmedia"]);


  const imageEl = hasImage ? `<img src="${d._embedded["wp:featuredmedia"][0].source_url}" width="900" height="600" class="w-100 h-auto" alt="${d.title.rendered}"></img>` : '';
  const excerptEl = !hasImage ? `<div class="mt-2">${d.excerpt.rendered}</div>` : '';

  console.log(d);

  return `
    <div class="col-12 col-sm-6 col-md-4 mt-3 player-news-col" data-post-id="${d.id}">
      <div class="h-100">
        <a class="tps-card-link h-100" href="${d.link}">
          <div class="tps-card h-100 rounded">
            ${imageEl}
            <div class="card-body">
              <h3 class="tps-card__title">${d.title.rendered}</h3>
              ${excerptEl}
              <div class="tps-card-meta">${formatPostDate(d.date)} | ${d._embedded["wp:term"][0][0].name}</div>
            </div>
          </div>
        </a>
      </div>
    </div>
  `;
};

function findUsedPosts() {
  const playerNewsCols = document.querySelectorAll('.player-news-col');
  playerNewsCols.forEach(col => {
    // Access the data-post-id attribute using the dataset property
    const postId = col.dataset.postId;
    // Push the postId to the array
    usedPosts.push(postId);
  });

  // removed usedPosts from postsToFind
  availablePosts = postsToUse.filter(string => !usedPosts.includes(string))
}

async function fetchPosts() {
  // Disable the button before anything else. 
  loadMoreBtn.disabled = true;
  // Add spinning animation
  loadMoreBtnIcon.classList.add(spinningAnimationClass);

  findUsedPosts();

  let output = '';
  try {
    const response = await fetch(`${wpApiSettings.root}wp/v2/posts?include=${availablePosts}&per_page=6&_embed`);
    const data = await response.json();
    data.map(d => {
      output += generateMarkup(d);
      usedPosts.push(d.id.toString());
    });
    playerNewsRow.insertAdjacentHTML('beforeend', output);

  } catch (error) {
    // Hide the button if there is an error.
    loadMoreBtn.style.display = "none";
    console.error(error);
    throw error;
  }

  if (availablePosts.length < 7) loadMorePlayerNewsContainer.classList.add('d-none');

  // I only get here if there are no errors. Sooo. 
  loadMoreBtnIcon.classList.remove(spinningAnimationClass);
  loadMoreBtn.disabled = false;
}

async function pageInit() {
  // handleMoreApps()

  // If there are no posts in the array or it would be less than 6 then dont do anything
  if (wpApiSettings.posts.length <= 6) return;
  // Because there are more posts to show then show the button
  loadMorePlayerNewsContainer.classList.remove('d-none');
  // register that are alrady on the page.
  loadMoreBtn.addEventListener('click', fetchPosts);
}

pageInit();

// Cash game + tournament results accordions (expand/collapse + "show more"
// per platform/event type). Both sections share the same markup/behavior,
// the only difference being cash games have a Stakes column and events don't.
document.querySelectorAll('.tps-accordion-item__header').forEach(function(header) {
  header.addEventListener('click', function() {
    header.closest('.tps-accordion-item').classList.toggle('is-open');
  });
});

function buildResultRow(app, hasStakes) {
  const winnings = app.tournament_winnings;
  const color = winnings < 0 ? 'text-danger' : 'text-success';
  const amount = (winnings < 0 ? '-' : '') + '$' + Math.abs(winnings).toLocaleString();
  const date = formatDate(app.date);
  const stakes = hasStakes ? (app.stakes || '') : '';
  const metaParts = (hasStakes ? [stakes] : []).concat([date]);

  return '<tr class="tps-streams-table__row">' +
    '<td class="tps-streams-table__col-stream">' +
      '<a href="' + app.permalink + '">' + app.title + '</a>' +
      '<div class="tps-streams-table__meta-mobile">' + metaParts.join(' · ') + ' · <span class="' + color + '">' + amount + '</span></div>' +
    '</td>' +
    (hasStakes ? '<td class="tps-streams-table__col-stakes">' + stakes + '</td>' : '') +
    '<td class="tps-streams-table__col-date">' + date + '</td>' +
    '<td class="tps-streams-table__col-result"><span class="' + color + '">' + amount + '</span></td>' +
    '</tr>';
}

document.querySelectorAll('[data-show-more-cashgame], [data-show-more-event]').forEach(function(btn) {
  btn.addEventListener('click', function() {
    const hasStakes = btn.hasAttribute('data-show-more-cashgame');
    const key = hasStakes ? btn.dataset.term : btn.dataset.type;
    const allApps = wpApiSettings[key] || [];
    const table = document.getElementById(btn.dataset.table);
    const tbody = table.querySelector('tbody');
    const alreadyShown = tbody.querySelectorAll('tr').length;
    const remainingApps = allApps.slice(alreadyShown);

    tbody.insertAdjacentHTML('beforeend', remainingApps.map(function(app) {
      return buildResultRow(app, hasStakes);
    }).join(''));
    btn.remove();
  });
});






