const wrapper = document.getElementById('table-results-wrapper');
const term = wrapper.dataset.term;
const searchInput = document.getElementById('player-search');

const columns = [
  { key: 'player_name', label: 'Player', sortable: true },
  { key: 'appearances', label: 'Appearances', sortable: true },
  { key: 'total_winnings', label: 'Profit/Loss', sortable: true }
];

function buildPlayerRow(row) {
  const winnings = parseFloat(row.total_winnings);
  const isPositive = winnings >= 0;
  const textColor = isPositive ? 'text-success' : 'text-danger';
  const sign = winnings < 0 ? '-' : '';

  return '<tr>' +
    '<td><a href="#" class="d-flex align-items-center hcl-title-link">' +
      '<img src="' + (row.player_image || '') + '" width="75" height="75" class="me-2">' +
      row.player_name +
    '</a></td>' +
    '<td>' + row.appearances + '</td>' +
    '<td><div class="fw-bold ' + textColor + '">' + sign + '$' + Math.abs(winnings).toLocaleString('en-US', {maximumFractionDigits: 0}) + '</div></td>' +
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
