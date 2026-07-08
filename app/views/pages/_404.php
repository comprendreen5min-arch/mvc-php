{{ ComponentFiles::header() }}

<div class="card-body">
    <div class="row align-items-center">
        <div class="col-lg-4 ms-auto">
            <div class="ex-page-content">
                <h1 class="text-dark display-1 mt-4">404!</h1>
                <h4 class="mb-4">Whops!! Sorry, page not found</h4>
                <p class="mb-5">Please, check and try again ...</p>
                <a class="btn btn-primary mb-5 waves-effect waves-light" href="<?php print URLROOT; ?>/pages/index"><i
                        class="mdi mdi-home"></i>Home</a>
            </div>

        </div>
        <div class="col-lg-5 mx-auto">
            <img src="assets/images/error.png" alt="" class="img-fluid mx-auto d-block">
        </div>
    </div>
</div><!-- end cardbody -->
{{ ComponentFiles::footer() }}