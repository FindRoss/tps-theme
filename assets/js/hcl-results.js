const tableControlBtns = document.querySelectorAll('.table-control-btn');
const tableEl = document.getElementById('hcl-results-table');
const tableBodyEl = tableEl.querySelector('tbody');
const tableHeadEl = tableEl.querySelector('thead');
const btnPrev = document.getElementById('table-page-prev');
const btnNext = document.getElementById('table-page-next');
const selectRows = document.getElementById('rows-per-page');
const pageCountDisplay = document.getElementById('table-page-count');
const searchPlayer = document.getElementById('hcl-search-player');
const searchPlayerInput = searchPlayer.querySelector('input');

const iconUp = '<svg xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><path d="M182.6 137.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"/></svg>';
const iconDown = '<svg xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/></svg>';

// State
let currentTable = 'episodes';
let currentPage = 1;
let rowsPerPage = 25;

// Dont Mutate State
let data = {
  episodes: wpApiSettings.episodes,
  players: wpApiSettings.players
}

// Dont Mutate State
let numberOf = {
  episodes: wpApiSettings.episodes.length,
  players: wpApiSettings.players.length
}

// Mutate State ?
let tableData = data[currentTable];
let totalNumberPages = Math.ceil(numberOf[currentTable] / rowsPerPage);

// Updating the total amount of pages on page load
pageCountDisplay.innerHTML = `Page 1 of ${totalNumberPages}`;


function formatDate(inputDate) {
  const [day, month, year] = inputDate.split('/').map(Number);
  const formattedDate = new Date(year, month - 1, day);

  const options = { month: 'short', day: 'numeric', year: 'numeric' };
  const formattedString = new Intl.DateTimeFormat('en-US', options).format(formattedDate);

  return formattedString;
}

function sortTableData(tableCol, colDir) {
  if (tableCol === 'date') {
    return [...data[currentTable]].sort(function (a, b) {
      const dateA = new Date(a[tableCol].split('/').reverse().join('/'));
      const dateB = new Date(b[tableCol].split('/').reverse().join('/'));

      return (colDir === "DESC") ? dateB - dateA : dateA - dateB;
    });
  } else if (typeof [...data[currentTable]][0][tableCol] === "number") {
    return [...data[currentTable]].sort(function (a, b) {
      return (colDir === "DESC") ? a[tableCol] - b[tableCol] : b[tableCol] - a[tableCol];
    });
  } else if (typeof [...data[currentTable]][0][tableCol] === "string") {
    return [...data[currentTable]].sort(function (a, b) {
      return (colDir === "DESC") ? a[tableCol].localeCompare(b[tableCol]) : b[tableCol].localeCompare(a[tableCol]);
    });
  }
}

function handleColClick(th) {
  return function () {
    let tableCol = th.dataset.tablecol;

    if (th.dataset.coldir) {
      // const sortByEpisode = sortTableData(tableCol, th.dataset.coldir);
      // tableData = [...sortByEpisode];
      // Dont think this mutates anything?
      tableData = sortTableData(tableCol, th.dataset.coldir);

      paintTable(rowsPerPage, currentPage);

      th.dataset.coldir = (th.dataset.coldir === "DESC") ? "ASC" : "DESC";

      document.querySelectorAll('th').forEach(th => th.classList.remove('active'));
      th.classList.add('active');
    }
  };
}

function paintHeader() {
  let output = '';

  if (currentTable === 'episodes') {
    output += `
      <tr>
        <th data-tablecol="episode" data-coldir="DESC" class="active">
          <div>
            <span>Episode</span>
            <span class="table-col-icon">
              <span class="table-col-icon-box">${iconUp}</span>
              <span class="table-col-icon-box">${iconDown}</span>
            </span>
          </div>
        </th>
        <th data-tablecol="date" data-coldir="DESC">
          <div>
            <span>Date</span>
            <span class="table-col-icon">
              <span class="table-col-icon-box">${iconUp}</span>
              <span class="table-col-icon-box">${iconDown}</span>
            </span>
          </div>
        </th>
        <th data-tablecol="type" data-coldir="DESC">
          <div>
            <span>Game Type</span>
            <span class="table-col-icon">
              <span class="table-col-icon-box">${iconUp}</span>
              <span class="table-col-icon-box">${iconDown}</span>
            </span>
          </div>
        </th>
        <th data-tablecol="stakes" data-coldir="DESC">
          <div>
            <span>Stakes</span>
            <span class="table-col-icon">
              <span class="table-col-icon-box">${iconUp}</span>
              <span class="table-col-icon-box">${iconDown}</span>
            </span>
          </div>
        </th>
      </tr>
    `;
  } else if (currentTable === 'players') {
    output += `
      <tr>
        <th data-tablecol="title" data-coldir="DESC">
          <div>
            <span>Player</span>  
            <span class="table-col-icon">
              <span class="table-col-icon-box">${iconUp}</span>
              <span class="table-col-icon-box">${iconDown}</span>
            </span>
          </div>
        </th>
        <th data-tablecol="appearances" data-coldir="DESC">
          <div>
            <span>Appearances</span>
            <span class="table-col-icon">
              <span class="table-col-icon-box">${iconUp}</span>
              <span class="table-col-icon-box">${iconDown}</span>
            </span>
          </div>
        </th>
        <th data-tablecol="winnings" data-coldir="DESC">
          <div>
            <span>Profile/Loss </span>
            <span class="table-col-icon">
              <span class="table-col-icon-box">${iconUp}</span>
              <span class="table-col-icon-box">${iconDown}</span>
            </span>
          </div>
        </th>
      </tr>
    `;
  } else {
    console.log('currentTable not found');
    return;
  }

  tableHeadEl.innerHTML = '';
  tableHeadEl.innerHTML = output;
}


function createBodyRow(row) {

  if (currentTable === 'episodes') {
    const { link, episode, date, type, stakes } = row;
    let formattedDate = formatDate(date);
    return `
        <tr>
          <td><a href="${link}">${episode}</a></td>
          <td>${formattedDate}</td>
          <td>${type}</td>
          <td>${stakes}</td>
        </tr>
      `;
  } else if (currentTable === 'players') {

    const { title, link, thumbnail, winnings, appearances } = row;

    // Solve the problem of no player being associated with a result. If the player has no name then simple return nothing.
    if (title === '') return '';

    const numPositive = winnings > 0;
    const absoluteValue = Math.abs(winnings);
    const formattedWinnings = absoluteValue.toLocaleString();

    return `
      <tr>
        <td>
          <a href="${link}" class="d-flex align-items-center hcl-title-link">
            ${thumbnail ? `<img src="${thumbnail}" width="75" height="75" class="me-2">` : ''}
            ${title}
          </a>
        </td>
        <td>${appearances}</td>
        <td>
          <div class="fw-bold ${numPositive ? 'text-success' : 'text-danger'}">
            ${numPositive ? '' : '-'}$${formattedWinnings}
          </div>
        </td>
      </tr>
    `;

  } else {
    console.log('currentTable not found.')
    return;
  }
}

function paintTable(rows = 25, page = 1) {
  // Create table header - table state
  let output = '';

  // * doing this twice. can i remove from here?
  // totalNumberPages = Math.ceil(numberOf[currentTable] / rows);
  totalNumberPages = Math.ceil(tableData.length / rows);

  // updateUI BTNs
  (page === 1) ? btnPrev.disabled = true : btnPrev.disabled = false;
  (page === totalNumberPages) ? btnNext.disabled = true : btnNext.disabled = false;

  let indexStart = (rows * (page - 1));
  let indexEnd = ((rows * (page - 1)) + rows) - 1;

  let filteredData = [...tableData].filter((value, index) => index >= indexStart && index <= indexEnd);

  // Create table rows
  filteredData.map(ep => output += createBodyRow(ep));

  tableBodyEl.innerHTML = "";
  tableBodyEl.innerHTML = output;
  pageCountDisplay.innerHTML = `Page ${currentPage} of ${totalNumberPages}`;
};

/////////////////////////////////////////
// Events & event listeners
function updatePage(delta) {
  currentPage += delta;

  paintTable(rowsPerPage, currentPage);
}
btnPrev.addEventListener('click', () => updatePage(-1));
btnNext.addEventListener('click', () => updatePage(1));

selectRows.addEventListener("change", function () {
  // This function will be called when the selected option changes
  rowsPerPage = parseInt(selectRows.value);

  // * Doing this twitch, also in paintTable function.
  totalNumberPages = Math.ceil(numberOf[currentTable] / rowsPerPage);

  if (currentPage > totalNumberPages) currentPage = 1;

  paintTable(rowsPerPage, currentPage);
});

// extract the function used on this event listener and call it here with its own function name. I need to reuse this function elsewhre: 
tableHeadEl.querySelectorAll('th').forEach(th => {
  if (th.dataset.tablecol) {
    th.addEventListener('click', handleColClick(th));
  }
});

function onTableChange(e) {
  // Break if current table is pressed
  if (e.target.dataset.hcltable === currentTable) return;

  // Chanage the state
  currentTable = e.target.dataset.hcltable;

  tableData = [...data[currentTable]];
  rowsPerPage = 25;
  currentPage = 1;

  // Update the table header
  paintHeader();
  // Update the table body
  paintTable(rowsPerPage, currentPage);

  // Add event listeners to header ths
  tableHeadEl.querySelectorAll('th').forEach(th => {
    if (th.dataset.tablecol) {
      th.addEventListener('click', handleColClick(th));
    }
  });

  // Change the active button
  // tableControlBtns.forEach(btn => {
  //   if (btn.classList.contains('tps-btn-outline')) {
  //     btn.classList.remove('tps-btn-outline');
  //     btn.classList.add('tps-btn-primary');
  //     btn.classList.add('active');
  //   } else {
  //     btn.classList.remove('tps-btn-primary');
  //     btn.classList.add('tps-btn-outline');
  //     btn.classList.remove('active');
  //   }
  // });

  tableControlBtns.forEach(btn => {
    if (!btn.classList.contains('active')) {
      btn.classList.add('active');
    } else {
      btn.classList.remove('active');
    }
  });

  // Show or hide the search input depending what page we are on.
  if (currentTable === 'players') {
    searchPlayer.style.visibility = 'inherit';
    searchPlayer.style.display = 'block';
  } else {
    searchPlayer.style.visibility = 'hidden';
    searchPlayer.style.display = 'none';
  }
};

tableControlBtns.forEach(btn => btn.addEventListener('click', onTableChange));

function showSearchResults(query) {
  const queryToLowerCase = query.toLowerCase();

  tableData = [...data[currentTable]].filter(item => item.title.toLowerCase().includes(queryToLowerCase));
  paintTable();
};

// Add event listener for 'input' event
searchPlayerInput.addEventListener('input', function () {
  const searchQuery = this.value;

  if (searchQuery !== '') {
    showSearchResults(searchQuery);
  } else {
    // Maybe need to do this when the searchQuery is empty    
    tableData = [...data[currentTable]];
    paintTable()
  }
});