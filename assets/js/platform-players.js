const wrapper = document.getElementById('table-results-wrapper');
const term = wrapper.dataset.term;
const searchInput = document.getElementById('player-search');

const columns = [
  { key: 'player_name', label: 'Player', sortable: true },
  { key: 'appearances', label: 'Appearances', sortable: true },
  { key: 'total_winnings', label: 'Profit/Loss', sortable: true }
];

function buildPlayerRow(row, i) {
  const winnings = parseFloat(row.total_winnings);
  const isPositive = winnings >= 0;
  const textColor = isPositive ? 'text-success' : 'text-danger';
  const sign = winnings < 0 ? '-' : '';
  const amount = sign + '$' + Math.abs(winnings).toLocaleString('en-US', { maximumFractionDigits: 0 });
  const rank = (tableHandler.currentPage - 1) * tableHandler.rowsPerPage + i + 1;

  return '<tr class="tps-players-table__row">' +
    '<td class="tps-players-table__col-rank">' + rank + '</td>' +
    '<td class="tps-players-table__col-player">' +
      '<div class="tps-players-table__player-cell">' +
        '<a class="tps-players-table__avatar" href="' + row.link + '" tabindex="-1" aria-hidden="true"><img src="' + (row.player_image || '') + '" alt="" loading="lazy"></a>' +
        '<div class="tps-players-table__player-info">' +
          '<a href="' + row.link + '">' + row.player_name + '</a>' +
          '<div class="tps-players-table__meta-mobile">' + row.appearances + ' appearances · <span class="' + textColor + '">' + amount + '</span></div>' +
        '</div>' +
      '</div>' +
    '</td>' +
    '<td class="tps-players-table__col-appearances">' + row.appearances + '</td>' +
    '<td class="tps-players-table__col-profit"><span class="' + textColor + '">' + amount + '</span></td>' +
    '</tr>';
}

const tableHandler = new TableHandler({
  tableId: 'player-results',
  apiEndpoint: platformPlayersData.root + 'custom/v1/player-results',
  columns: columns,
  rowBuilder: buildPlayerRow,
  params: { term: term }
});

if (searchInput) {
  searchInput.addEventListener('input', function() {
    tableHandler.setSearchQuery(this.value);
  });
}
