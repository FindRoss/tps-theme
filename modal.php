<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog" style="top: 30%;">
    <div class="modal-content search-modal">
      <div class="modal-header">
        <button type="button" class="btn btn-dark text-white close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-inline my-2 my-lg-0 w-100" role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
          <div class="input-group input-group-lg w-100">
            <input class="form-control mr-sm-2 rounded-0" type="search" placeholder="Search" aria-label="Search" value="" name="s" id="s" type="text">
            <button id="searchsubmit" class="btn btn-dark text-white" value="Search" type="submit">Search</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div><!-- .modal  -->
