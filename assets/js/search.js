// let resultsCount = 0;
// New York Times: https://www.nytimes.com/search?dropmab=false&query=asd&sort=best

const resultsContainer = document.getElementById('resultsContainer');
const resultsCountEl = document.getElementById('resultsCount');
let resultsCount = 0;

const profileImg = 'https://toppokerstreamers.com/wp-content/uploads/2024/08/anonymous-person-profile__100x100-jpg.webp';

function sortByRelevance(array, searchTerm) {
	return array.slice().sort((a, b) => {
		const relevanceA = calculateRelevance(a.title['rendered'], searchTerm);
		const relevanceB = calculateRelevance(b.title['rendered'], searchTerm);

		return relevanceB - relevanceA; // Sort in descending order of relevance
	});
}

function calculateRelevance(value, searchTerm) {
	const regex = new RegExp(searchTerm, 'gi');
	const matches = value.match(regex);

	return matches ? matches.length : 0;
}

function generatePageCard(page) {
	const { link, title } = page;

	return `
		<div class="col-6 col-lg-3 mt-3">
			<div class="h-100">
				<a class="tps-card-link h-100" href="${link}">
					<div class="tps-card h-100 rounded">
						<div class="card-body">
							<h3 class="tps-card__title m-0">${title.rendered}</h5>
						</div>
					</div>
				</a>
			</div>
		</div>
	`;
}

function generatePostCard(post) {
	let term = post._embedded["wp:term"][0][0]?.name;

	return `
		<div class="col-6 col-lg-3 mt-3">
			<div class="h-100">
				<a class="tps-card-link h-100" href="${post.link}">
					<div class="tps-card h-100 rounded"> 
						<div class="card-body">
							<span class="text-uppercase" style="color: #0056b3;">${term ? term : ''}</span>
							<h3 class="tps-card__title m-0">${post.title.rendered}</h5>
						</div>
					</div>
				</a>
			</div>
		</div>
	`;

}

function generatePlayerCard(player) {
	return `
		<div class="col-6 col-md-4 mt-3">
			<div class="h-100">
				<a class="tps-card-link" href="${player.link}">
					<div class="tps-card p-2 p-lg-3 rounded h-100">
						<div class="tps-card__media d-flex justify-content-center">
							${player.image ? (` 
								<img src="${player.image}" alt="${player.title}" style="width: 100px; height: 100px;" class="rounded-circle" width="100" height="100" />
							`) : ` 
								<img src="${profileImg}" alt="placeholder image" style="width: 100px; height: 100px;" class="rounded-circle" width="100" height="100" />
							`}
						</div>
						<div class="pt-3">
							<h3 class="h6 text-center tps-card__title m-0 text-break">${player.title}</h3>
						</div>
					</div>
				</a>
			</div>
		</div>	
	`;
}

async function fetchAndSort(type, searchTerm) {
	let perPage = 12;

	try {
		const response = await fetch(`${wpApiSettings.root}wp/v2/${type}?search=${searchTerm}&per_page=${perPage}&_embed`);
		const data = await response.json();

		if (data.length > 0) {
			const sortedArray = sortByRelevance(data, searchTerm);
			resultsCount += data.length;
			return sortedArray;

		} else {
			return []; // Return an empty array if no data is available
		}
	} catch (error) {
		console.error('ERROR: ', error);
		throw error; // Re-throw the error to be handled by the calling code
	}
}

async function customFetch(searchTerm) {
	try {
		const response = await fetch(`${wpApiSettings.root}custom/v1/search/?search=${searchTerm}`);
		const data = await response.json();
		resultsCount += data.length;
		return data;
	} catch (err) {
		console.error(err);
	}
}

// Fetch data
async function fetchSearch(searchTerm) {

	const [players, posts, pages] = await Promise.all([
		customFetch(searchTerm),
		fetchAndSort('posts', searchTerm),
		fetchAndSort('pages', searchTerm)
	]);

	return { players, posts, pages };
};

// Display results
async function displayResults(searchTerm) {
	const { players, posts, pages } = await fetchSearch(searchTerm);

	let output = "";

	if (pages.length !== 0) {
		output += '<h2 class="mt-4">Pages</h2>';
		output += '<div class="row">';
		pages.map(d => {
			output += generatePageCard(d);
		});
		output += '</div>';
	}

	if (players.length != 0) {
		output += '<h2 class="mt-4">Profiles</h2>';
		output += '<div class="row">';
		players.map(d => {
			output += generatePlayerCard(d);
		});
		output += '</div>';
	}

	if (posts.length !== 0) {
		output += '<h2 class="mt-4">Posts</h2>';
		output += '<div class="row">';
		posts.map(d => {
			output += generatePostCard(d);
		});
		output += '</div>';
	}

	// Update UI to show number of results	
	resultsCountEl.innerHTML = "";
	resultsCountEl.innerHTML = `<div class="results-count rounded py-2 px-3">Showing ${resultsCount.toString()} results for the search term <span class="results-count__term">${searchTerm}</span></div> `;

	// Update UI to show results
	resultsContainer.innerHTML = "";
	resultsContainer.innerHTML = output;
};

async function initSearch() {
	const searchInput = document.getElementById('searchInput');

	const placeholder = `<div class="col-12 col-md-4 mt-4"> <div class="search-result-placeholder"><span class="placeholder-circle circle-rounded"></span></div></div> `;

	if (searchInput.value.trim() !== '') {

		resultsContainer.innerHTML = `<div class="results-count rounded" style="height: 50px;"></div><div class="row">${placeholder.repeat(6)}</div>`;

		try {
			await displayResults(searchInput.value);
			// await updateInterface();
		} catch (error) {
			console.error(error);
		}

	} else {

		resultsCountEl.innerHTML = "";
		resultsCountEl.innerHTML = `<div class="results-count rounded py-2 px-3">No results found, please enter something into the sarch field.</div>`;
		resultsContainer.innerHTML = "";
	}
};

document.addEventListener('DOMContentLoaded', initSearch);