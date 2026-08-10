const playerNewsRow = document.querySelector('#player-news-row');
const loadMoreBtn = document.getElementById('loadMorePlayerNews');
const loadMoreBtnIcon = loadMoreBtn.querySelector('i');
const loadMorePlayerNewsContainer = document.getElementById('loadMorePlayerNewsContainer');
const spinningAnimationClass = 'spinning-icon';

// HCL Results
const hclResultsContainer = document.querySelector('.player-stream-results');
const hclResultsTableBody = hclResultsContainer.querySelector('table > tbody');

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

function newPaintAppRows(event, termID) {

  let trimmedApps = wpApiSettings[termID].slice(8);

  let btn = event.target.closest('.btn.btn-link');
  btn.classList.remove('d-flex');
  btn.classList.add('d-none');
  btn.classList.add('visually-hidden');

  let table = btn.parentElement.parentElement;
  let tbody = table.querySelector('tbody');

  let apps = '';

  trimmedApps.map(app => {
    // Format profile / loss output
    const numPositive = app.tournament_winnings > 0;
    const absoluteValue = Math.abs(app.tournament_winnings).toLocaleString();

    // Format date output
    const date = formatDate(app.date);

    apps += `
        <tr>
          <td><a href="${app.permalink}">${app.title}</a></td>
          <td>${date}</td>
          <td class="text-end">
            <div class="fw-bold ${numPositive ? 'text-success' : 'text-danger'}">
              ${numPositive ? '' : '-'}$${absoluteValue}
            </div>
          </td>
        </tr>
      `;
  });

  tbody.insertAdjacentHTML("beforeend", apps);
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






