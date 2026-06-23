const resultsContainer = document.getElementById('resultsContainer');
const resultsCountEl = document.getElementById('resultsCount');
const spinner = document.getElementById('spinner');
const filterButtons = document.querySelectorAll('#post-type-filters .nav-pill');

const profileImg = 'https://toppokerstreamers.com/wp-content/uploads/2024/08/anonymous-person-profile__100x100-jpg.webp';

let activeFilter = 'all';
let currentPage = 1;

function generateCard(result) {
	const isPlayer = result.postType === 'player';

	if (isPlayer) {
		return `
			<div class="col-6 col-md-4 mt-3">
				<div class="h-100">
					<a class="tps-card-link" href="${result.link}">
						<div class="tps-card p-2 p-lg-3 rounded h-100">
							<div class="tps-card__media d-flex justify-content-center">
								${result.image ? `
									<img src="${result.image}" alt="${result.title}" style="width: 100px; height: 100px;" class="rounded-circle" width="100" height="100" />
								` : `
									<img src="${profileImg}" alt="placeholder image" style="width: 100px; height: 100px;" class="rounded-circle" width="100" height="100" />
								`}
							</div>
							<div class="pt-3">
								<h3 class="h6 text-center tps-card__title m-0 text-break">${result.title}</h3>
							</div>
						</div>
					</a>
				</div>
			</div>`;
	}

	return `
		<div class="col-6 col-lg-3 mt-3">
			<div class="h-100">
				<a class="tps-card-link h-100" href="${result.link}">
					<div class="tps-card h-100 rounded">
						<div class="card-body">
							<span class="text-uppercase" style="color: #0056b3;">${result.postType}</span>
							<h3 class="tps-card__title m-0">${result.title}</h3>
						</div>
					</div>
				</a>
			</div>
		</div>`;
}

function groupByType(results) {
	return results.reduce((groups, item) => {
		const type = item.postType;
		if (!groups[type]) groups[type] = [];
		groups[type].push(item);
		return groups;
	}, {});
}

async function fetchSearch(searchTerm, postType, page) {
	const params = new URLSearchParams({
		term: searchTerm,
		type: postType,
		page: page
	});

	const response = await fetch(`${wpApiSettings.root}custom/v2/search/?${params}`);
	return response.json();
}

async function displayResults(searchTerm, postType, page) {
	const placeholder = `<div class="col-12 col-md-4 mt-4"><div class="search-result-placeholder"><span class="placeholder-circle circle-rounded"></span></div></div>`;
	resultsContainer.innerHTML = `<div class="row">${placeholder.repeat(6)}</div>`;
	spinner.style.display = '';

	try {
		const data = await fetchSearch(searchTerm, postType, page);
		const { results, totalPosts, totalPages, currentPage: cp } = data;

		console.log(results);

		let output = '';
		if (results.length > 0) {
			const grouped = groupByType(results);
			const sectionOrder = [
				{ key: 'player', label: 'Players' },
				{ key: 'page', label: 'Pages' },
				{ key: 'post', label: 'Posts' },
			];

			sectionOrder.forEach(({ key, label }) => {
				if (grouped[key]?.length) {
					output += `<h2 class="mt-4">${label}</h2><div class="row">`;
					grouped[key].forEach(r => { output += generateCard(r); });
					output += '</div>';
					delete grouped[key];
				}
			});

			Object.keys(grouped).forEach(type => {
				output += `<h2 class="mt-4">${type}</h2><div class="row">`;
				grouped[type].forEach(r => { output += generateCard(r); });
				output += '</div>';
			});

			if (totalPages > 1) {
				output += buildPagination(cp, totalPages);
			}
		} else {
			output = '<p class="mt-4">No results found.</p>';
		}

		resultsCountEl.innerHTML = `<div class="results-count rounded py-2 px-3">Showing ${totalPosts} results for <span class="results-count__term">${searchTerm}</span></div>`;
		resultsContainer.innerHTML = output;
	} catch (error) {
		console.error(error);
		resultsContainer.innerHTML = '<p class="mt-4">Something went wrong. Please try again.</p>';
		resultsCountEl.innerHTML = '';
	}

	spinner.style.display = 'none';
}

function buildPagination(current, total) {
	let html = '<nav class="mt-4"><ul class="pagination justify-content-center">';

	if (current > 1) {
		html += `<li class="page-item"><button class="page-link" data-page="${current - 1}">&laquo; Prev</button></li>`;
	}

	for (let i = 1; i <= total; i++) {
		html += `<li class="page-item ${i === current ? 'active' : ''}"><button class="page-link" data-page="${i}">${i}</button></li>`;
	}

	if (current < total) {
		html += `<li class="page-item"><button class="page-link" data-page="${current + 1}">Next &raquo;</button></li>`;
	}

	html += '</ul></nav>';
	return html;
}

function getSearchTerm() {
	return document.getElementById('searchInput').value.trim();
}

function setActiveButton(postType) {
	filterButtons.forEach(btn => {
		btn.classList.toggle('is-active', btn.dataset.postType === postType);
	});
}

// Filter button clicks
filterButtons.forEach(btn => {
	btn.addEventListener('click', () => {
		const searchTerm = getSearchTerm();
		if (!searchTerm) return;

		activeFilter = btn.dataset.postType;
		currentPage = 1;
		setActiveButton(activeFilter);
		displayResults(searchTerm, activeFilter, currentPage);
	});
});

// Pagination clicks
resultsContainer.addEventListener('click', (e) => {
	const btn = e.target.closest('[data-page]');
	if (!btn) return;

	const searchTerm = getSearchTerm();
	if (!searchTerm) return;

	currentPage = parseInt(btn.dataset.page);
	displayResults(searchTerm, activeFilter, currentPage);
	window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Init
document.addEventListener('DOMContentLoaded', () => {
	const searchTerm = getSearchTerm();

	if (searchTerm) {
		displayResults(searchTerm, activeFilter, currentPage);
	} else {
		resultsCountEl.innerHTML = `<div class="results-count rounded py-2 px-3">No results found, please enter something into the search field.</div>`;
		spinner.style.display = 'none';
	}
});
