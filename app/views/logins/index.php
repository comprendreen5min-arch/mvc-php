<?php

 $session_check = new SessionHelper();
 if($session_check->isLoggedIn()){
   Helper::redirect('pages/index');
 }

?>
{{ComponentFiles::_header()}}



    <div class="wrapper-page account-page-full">
        <div class="card shadow-none">
            <div class="card-block">
                <div class="account-box">
                    <div class="card-box shadow-none p-4">
                        <div class="p-2">
                            <div class="text-center mt-4">
                                <a href="{{ URLROOT }}/logins/index" class="logo logo-dark">
                                    <span class="logo-lg">
                                        <img src="{{ URLROOT }}/{{ UPLOADS }}/logo-dark.png" alt="" height="17">
                                    </span>
                                </a>
                                <a href="{{ URLROOT }}/logins/index" class="logo logo-light">
                                    <span class="logo-lg">
                                        <img src="{{ URLROOT }}/{{ UPLOADS }}/logo-dark.png" alt="" height="18">
                                    </span>
                                </a>
                            </div>

                            <h4 class="font-size-18 mt-5 text-center"> Welcome </h4>
                            <p class="text-muted text-center">Sign in to continue to Booklix.</p>
                            <form class="mt-4" action="{{ URLROOT }}/logins/index" method="POST">
                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="text" class="form-control text-center" id="email" name="email" placeholder="Example@example.com" value="nouaman@webmaster.com">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="userpassword">Password</label>
                                    <input type="password" class="form-control text-center" name="password" placeholder="Enter password" value="Nou@man001">
                                </div>

                                <div class="mb-3 row">
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Login</button>
                                    </div>
                                </div>
                            </form>
                                    {{ Helper::flash_message('login_message') }}

                            <div class="mt-5 pt-4 text-center">
                                <p>© <script>
                                    document.write(new Date().getFullYear())
                                    </script>. Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://9-shen.com">9-SHEN LTD</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   {{ComponentFiles::_footer()}}
