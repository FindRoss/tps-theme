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
    this.sortHeaders = document.querySelectorAll('[data-tablecol]');

    // State
    this.currentPage = 1;
    this.rowsPerPage = 10;
    this.totalPages = Math.ceil(parseInt(this.table.dataset.totalResults) / this.rowsPerPage);
    this.sortColumn = null;
    this.sortDir = 'DESC';
    this.searchQuery = '';

    this.init();
  }

  init() {
    if (this.btnPrev) this.btnPrev.addEventListener('click', () => this.prevPage());
    if (this.btnNext) this.btnNext.addEventListener('click', () => this.nextPage());
    if (this.selectRows) this.selectRows.addEventListener('change', () => this.onRowsChange());
    if (this.sortHeaders) {
      this.sortHeaders.forEach(header => {
        header.addEventListener('click', () => this.onColumnSort(header));
      });
    }
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

  onColumnSort(header) {
    const col = header.dataset.tablecol;
    const dir = header.dataset.coldir;

    // Toggle direction if clicking same column
    if (this.sortColumn === col) {
      this.sortDir = this.sortDir === 'ASC' ? 'DESC' : 'ASC';
    } else {
      this.sortColumn = col;
      this.sortDir = dir || 'DESC';
    }

    // Update header UI
    this.updateSortUI();
    this.currentPage = 1;
    this.fetchData();
  }

  updateSortUI() {
    this.sortHeaders.forEach(header => {
      const isActive = header.dataset.tablecol === this.sortColumn;
      header.classList.toggle('active', isActive);

      const iconBoxes = header.querySelectorAll('.table-col-icon-box');
      if (iconBoxes.length >= 2) {
        const upIcon = iconBoxes[0];
        const downIcon = iconBoxes[1];

        upIcon.style.display = 'none';
        downIcon.style.display = 'none';

        if (isActive) {
          const iconBox = this.sortDir === 'ASC' ? upIcon : downIcon;
          iconBox.style.display = 'block';
        }
      }
    });
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
    return results.map(row => this.rowBuilder(row)).join('');
  }

  updateControls() {
    if (this.btnPrev) this.btnPrev.disabled = this.currentPage === 1;
    if (this.btnNext) this.btnNext.disabled = this.currentPage >= this.totalPages;
    if (this.pageCountDisplay) {
      this.pageCountDisplay.textContent = this.currentPage + ' of ' + this.totalPages;
    }
  }
}
