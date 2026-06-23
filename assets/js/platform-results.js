const btnPrev = document.getElementById('table-page-prev');
const btnNext = document.getElementById('table-page-next');
const pageCountDisplay = document.getElementById('table-page-count');
const selectRows = document.getElementById('rows-per-page');
const tableBody = document.getElementById('table-body');
const loaderBar = document.getElementById('loader-bar');
const wrapper = document.getElementById('table-results-wrapper');

const term = wrapper.dataset.term;

let currentPage = 1;
let rowsPerPage = 10;
let totalPages = Math.ceil(parseInt(document.getElementById('episode-results').dataset.totalResults) / rowsPerPage);

function formatDate(dateStr) {
  const [day, month, year] = dateStr.split('/').map(Number);
  const date = new Date(year, month - 1, day);
  return new Intl.DateTimeFormat('en-US', { month: 'short', day: 'numeric', year: 'numeric' }).format(date);
}

function updateControls() {
  btnPrev.disabled = currentPage === 1;
  btnNext.disabled = currentPage >= totalPages;
  pageCountDisplay.textContent = currentPage + ' of ' + totalPages;
}

function buildRows(results) {
  return results.map(function (row) {
    return '<tr>' +
      '<td><a href="' + row.link + '">' + row.title + '</a></td>' +
      '<td>' + formatDate(row.date) + '</td>' +
      '<td>' + row.type + '</td>' +
      '<td>' + row.stakes + '</td>' +
      '</tr>';
  }).join('');
}

function fetchPage() {
  loaderBar.style.display = 'block';

  var url = platformResultsData.root + 'custom/v1/gameshows?page=' + currentPage + '&rows=' + rowsPerPage + '&term=' + term;

  fetch(url)
    .then(function (res) { return res.json(); })
    .then(function (data) {
      totalPages = data.total_pages;
      tableBody.innerHTML = buildRows(data.results);
      updateControls();
    })
    .catch(function (err) {
      console.error('Failed to fetch results:', err);
    })
    .finally(function () {
      loaderBar.style.display = 'none';
    });
}

btnPrev.addEventListener('click', function () {
  if (currentPage > 1) {
    currentPage--;
    fetchPage();
  }
});

btnNext.addEventListener('click', function () {
  if (currentPage < totalPages) {
    currentPage++;
    fetchPage();
  }
});

selectRows.addEventListener('change', function () {
  rowsPerPage = parseInt(selectRows.value);
  currentPage = 1;
  fetchPage();
});

updateControls();
