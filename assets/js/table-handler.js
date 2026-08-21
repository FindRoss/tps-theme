class TableHandler {
  constructor(config) {
    this.tableId = config.tableId;
    this.apiEndpoint = config.apiEndpoint;
    this.columns = config.columns; // array of { key, label, sortable }
    this.rowBuilder = config.rowBuilder; // function to build row HTML
    this.params = config.params || {}; // additional API params (e.g., term)

    // DOM elements
    this.table = document.getElementById(this.tableId);
    this.tableBody = document.getElementById('table-body');
    this.btnPrev = document.getElementById('table-page-prev');
    this.btnNext = document.getElementById('table-page-next');
    this.pageCountDisplay = document.getElementById('table-page-count');
    this.selectRows = document.getElementById('rows-per-page');
    this.loaderBar = document.getElementById('loader-bar');
    this.wrapper = document.getElementById('table-results-wrapper');
    this.showingText = document.getElementById('table-showing-text'); // optional, not every table has this
    this.selectSort = document.getElementById('sort-by'); // optional, not every table has this

    // State
    this.currentPage = 1;
    this.rowsPerPage = 10;
    this.totalPages = Math.ceil(parseInt(this.table.dataset.totalResults) / this.rowsPerPage);
    // Seed from the sort dropdown's pre-selected option, so it matches
    // whatever order PHP already rendered on load.
    if (this.selectSort) {
      const [col, dir] = this.selectSort.value.split(':');
      this.sortColumn = col;
      this.sortDir = dir;
    } else {
      this.sortColumn = null;
      this.sortDir = 'DESC';
    }
    this.searchQuery = '';

    this.init();
  }

  init() {
    if (this.btnPrev) this.btnPrev.addEventListener('click', () => this.prevPage());
    if (this.btnNext) this.btnNext.addEventListener('click', () => this.nextPage());
    if (this.selectRows) this.selectRows.addEventListener('change', () => this.onRowsChange());
    if (this.selectSort) this.selectSort.addEventListener('change', () => this.onSortChange());
    this.updateControls();
  }

  prevPage() {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.fetchData();
    }
  }

  nextPage() {
    if (this.currentPage < this.totalPages) {
      this.currentPage++;
      this.fetchData();
    }
  }

  onRowsChange() {
    this.rowsPerPage = parseInt(this.selectRows.value);
    this.currentPage = 1;
    this.totalPages = Math.ceil(parseInt(this.table.dataset.totalResults) / this.rowsPerPage);
    this.fetchData();
  }

  onSortChange() {
    const [col, dir] = this.selectSort.value.split(':');
    this.sortColumn = col;
    this.sortDir = dir;
    this.currentPage = 1;
    this.fetchData();
  }

  setSearchQuery(query) {
    this.searchQuery = query;
    this.currentPage = 1;
    this.fetchData();
  }

  buildUrl() {
    const params = new URLSearchParams();
    params.append('page', this.currentPage);
    params.append('rows', this.rowsPerPage);

    if (this.searchQuery) {
      params.append('search', this.searchQuery);
    }

    if (this.sortColumn) {
      params.append('sort', this.sortColumn);
      params.append('dir', this.sortDir);
    }

    // Add custom params
    Object.keys(this.params).forEach(key => {
      params.append(key, this.params[key]);
    });

    return this.apiEndpoint + '?' + params.toString();
  }

  fetchData() {
    if (this.loaderBar) this.loaderBar.style.display = 'block';

    const url = this.buildUrl();

    fetch(url)
      .then(res => res.json())
      .then(data => {
        this.totalPages = data.total_pages;
        this.table.dataset.totalResults = data.total_players || data.total_posts || 0;
        this.tableBody.innerHTML = this.buildRows(data.results);
        this.updateControls();
      })
      .catch(err => {
        console.error('Failed to fetch table data:', err);
      })
      .finally(() => {
        if (this.loaderBar) this.loaderBar.style.display = 'none';
      });
  }

  buildRows(results) {
    // Index is passed through for row builders that need a running rank
    // (e.g. platform-players.php); builders that only take `row` just
    // ignore the second argument.
    return results.map((row, i) => this.rowBuilder(row, i)).join('');
  }

  updateControls() {
    if (this.btnPrev) this.btnPrev.disabled = this.currentPage === 1;
    if (this.btnNext) this.btnNext.disabled = this.currentPage >= this.totalPages;
    if (this.pageCountDisplay) {
      this.pageCountDisplay.textContent = this.currentPage + ' of ' + this.totalPages;
    }
    if (this.showingText) {
      const total = parseInt(this.table.dataset.totalResults) || 0;
      if (total === 0) {
        this.showingText.textContent = 'No results';
      } else {
        const start = (this.currentPage - 1) * this.rowsPerPage + 1;
        const end = Math.min(this.currentPage * this.rowsPerPage, total);
        this.showingText.textContent = 'Showing ' + start + '–' + end + ' of ' + total;
      }
    }
  }
}
