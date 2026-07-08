   <!-- ========== Left Sidebar Start ========== -->
   <div class="vertical-menu">

       <div data-simplebar class="h-100">

           <!--- Sidemenu -->
           <div id="sidebar-menu">
               <!-- Left Menu Start -->
               <ul class="metismenu list-unstyled" id="side-menu">
                   <li class="menu-title"><?php print $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></li>

                   <?php
                    // Home For Admin and Manager (2)
                    print Helper::renderMenuItem('Home', 'ti-home', URLROOT . '/pages/index', [], [1, 2]);

                
                    print '<hr>';


                   


                    /***   Admin Only   ***/
                   
                    // Users: Admin (1) only
                    print Helper::renderMenuItem('Users', 'ti-user', 'javascript:void(0);', [
                        ['title' => 'Users', 'url' => URLROOT . '/users/index']
                    ], [1]);


                   

                    // Support: Everyone
                    print Helper::renderMenuItem('Support', 'fa fa-video', 'javascript:void(0);', [
                        ['title' => 'Documentation', 'url' => URLROOT . '/public/docs/index.html']
                    ], [1, 2, 3, 4, 5, 6, 7, 8,]);
                    ?>



               </ul>
           </div>
           <!-- Sidebar -->
       </div>
   </div>
   <!-- Left Sidebar End -->