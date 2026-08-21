const wrapper = document.getElementById('table-results-wrapper');
const term = wrapper.dataset.term;
const icon = wrapper.dataset.icon;

const columns = [
  { key: 'episode', label: 'Episode', sortable: true },
  { key: 'date', label: 'Date', sortable: true },
  { key: 'type', label: 'Type', sortable: true },
  { key: 'stakes', label: 'Stakes', sortable: true }
];

function formatDate(dateStr) {
  // Tolerate a blank/unparseable date instead of throwing, since one bad
  // row would otherwise abort the whole page's row-building
  // (table-handler.js's buildRows has no per-row error handling).
  if (!dateStr) return '';
  const [day, month, year] = dateStr.split('/').map(Number);
  const date = new Date(year, month - 1, day);
  if (isNaN(date.getTime())) return '';
  return new Intl.DateTimeFormat('en-US', { month: 'short', day: 'numeric', year: 'numeric' }).format(date);
}

function buildEpisodeRow(row) {
  // Every row on a single results page belongs to the same platform/term,
  // so the icon (read once from the wrapper's data attribute) is reused
  // for every AJAX-appended row rather than being re-fetched per row.
  const thumb = icon
    ? '<a class="tps-streams-table__thumb" href="' + row.link + '" tabindex="-1" aria-hidden="true"><img src="' + icon + '" alt="" loading="lazy"></a>'
    : '';

  return '<tr class="tps-streams-table__row">' +
    '<td class="tps-streams-table__col-thumbnail">' + thumb + '</td>' +
    '<td class="tps-streams-table__col-stream">' +
      '<a href="' + row.link + '">' + row.title + '</a>' +
      '<div class="tps-streams-table__meta-mobile">' + formatDate(row.date) + ' · ' + row.type + ' · ' + row.stakes + '</div>' +
    '</td>' +
    '<td class="tps-streams-table__col-type">' + row.type + '</td>' +
    '<td class="tps-streams-table__col-stakes">' + row.stakes + '</td>' +
    '<td class="tps-streams-table__col-date">' + formatDate(row.date) + '</td>' +
    '</tr>';
}

const tableHandler = new TableHandler({
  tableId: 'episode-results',
  apiEndpoint: platformResultsData.root + 'custom/v1/gameshows',
  columns: columns,
  rowBuilder: buildEpisodeRow,
  params: { term: term }
});
