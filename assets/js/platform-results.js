const wrapper = document.getElementById('table-results-wrapper');
const term = wrapper.dataset.term;

const columns = [
  { key: 'episode', label: 'Episode', sortable: true },
  { key: 'date', label: 'Date', sortable: true },
  { key: 'type', label: 'Type', sortable: true },
  { key: 'stakes', label: 'Stakes', sortable: true }
];

function formatDate(dateStr) {
  const [day, month, year] = dateStr.split('/').map(Number);
  const date = new Date(year, month - 1, day);
  return new Intl.DateTimeFormat('en-US', { month: 'short', day: 'numeric', year: 'numeric' }).format(date);
}

function buildEpisodeRow(row) {
  return '<tr>' +
    '<td><a href="' + row.link + '">' + row.title + '</a></td>' +
    '<td>' + formatDate(row.date) + '</td>' +
    '<td>' + row.type + '</td>' +
    '<td>' + row.stakes + '</td>' +
    '</tr>';
}

const tableHandler = new TableHandler({
  tableId: 'episode-results',
  apiEndpoint: platformResultsData.root + 'custom/v1/gameshows',
  columns: columns,
  rowBuilder: buildEpisodeRow,
  params: { term: term }
});
