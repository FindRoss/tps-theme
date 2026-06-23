<?php get_header(); ?>

<!-- the_search_query();  -->


<div class="container">


	<div class="mt-4" style="max-width: 756px; margin: 0 auto">
		<form class="my-2 w-100" role="search" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<div class="input-group" id="searchForm">
				<input class="form-control mr-sm-2 rounded-start" type="search" placeholder="Search" aria-label="Search input" value="<?php the_search_query(); ?>" name="s" id="searchInput" type="text">
				<button id="searchsubmit" class="btn btn-dark text-white" value="Search" type="submit" id="searchBtn" aria-label="Search button">
					<svg class="search-svg" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><path fill="white" d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
				</button>
			</div>
		</form>
	</div>

	<!-- Post Types -->
	<div class="nav-pill-container">
		<div class="nav-pill-wrapper" id="post-type-filters">
	
			<!-- Item -->
			<div class="nav-pill-item">
				<button class="btn btn-dark nav-pill rounded-pill is-active" data-post-type="all">All</button>
			</div>

			<!-- Item -->
			<div class="nav-pill-item">
				<button class="btn btn-dark nav-pill rounded-pill" data-post-type="post">News</button>
			</div>

			<!-- Item -->
			<div class="nav-pill-item">
				<button class="btn btn-dark nav-pill rounded-pill" data-post-type="player">Players</button>
			</div>

			<!-- Item -->
			<div class="nav-pill-item">
				<button class="btn btn-dark nav-pill rounded-pill" data-post-type="cash_game">Cash Games</button>
			</div>

			<!-- Item -->
			<div class="nav-pill-item">
				<button class="btn btn-dark nav-pill rounded-pill" data-post-type="tournament">Tournaments</button>
			</div>
			
		</div><!-- .nav-pill-wrapper -->
	</div><!-- .nav-pill-container --> 
</div><!-- .container --> 


<!-- Results Count -->
<div class="container">
	<div class="results-count mt-2" id="resultsCount"></div>
</div>

<div class="loading-spinner" id="spinner">
  <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 100 100" class="loading-spinner">
    <circle
      cx="50"
      cy="50"
      r="32"
      stroke-width="8"
      stroke="#3498db"
      stroke-dasharray="50 150"
      fill="none"
      stroke-linecap="round">
    </circle>
  </svg>
</div>

<!-- Results  -->
<section class="results">
	<div class="container">
		<div class="results-container" id="resultsContainer" style="min-height: 70vh"></div>
	</div>
</section>

<section>
	<div class="container" id="resultsPagination"></div>
</section>


 
<?php get_footer();?>
 




