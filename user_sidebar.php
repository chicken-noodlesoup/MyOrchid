<!-- sidebar -->
<nav class="sidebar">
      <div class="menu_content">
        <ul class="menu_items">
          <div class="menu_title menu_dahsboard"></div>
          <ul class="menu_items">
          
          <!-- Start -->
          <li class="item">
            <a href="user_dashboard.php" class="nav_link">
              <span class="navlink_icon">
                <i class="bx bx-home-alt"></i>
              </span>
              <span class="navlink">Home</span>
            </a>
          </li>
          <!-- End -->

          <!-- duplicate or remove this li tag if you want to add or remove navlink with submenu -->
          <!-- start -->
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="bx bx-grid-alt"></i>
              </span>
              <span class="navlink">My Plant</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="user_myplant.php" class="nav_link sublink">My plant</a>
              <a href="user_addplant.php" class="nav_link sublink">Add plant</a>
            </ul>
          </li>
          <!-- end -->

          <!-- duplicate this li tag if you want to add or remove  navlink with submenu -->
          <!-- start -->
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="bx bx-grid-alt"></i>
              </span>
              <span class="navlink">Orchid</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="user_orchidinfo.php" class="nav_link sublink">Orchid information</a>
              <a href="user_orchidpest.php" class="nav_link sublink">Orchid common pest</a>
              <a href="user_saved_orchid.php" class="nav_link sublink">Saved Orchid</a>
            </ul>

            
          </li>
          <!-- end -->

          <!-- start -->
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="bx bx-grid-alt"></i>
              </span>
              <span class="navlink">Discussion</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="user_discussion.php" class="nav_link sublink">Forum</a>
              <a href="user_new_discussion.php" class="nav_link sublink">Add Topic</a>
              <a href="user_owntopic.php" class="nav_link sublink">My Topic</a>
            </ul>
          </li>
          <!-- end -->
        </ul>


        <!-- Sidebar Open / Close -->
        <div class="bottom_content">
          <div class="bottom expand_sidebar">
            <span> Expand</span>
            <i class='bx bx-log-in' ></i>
          </div>
          <div class="bottom collapse_sidebar">
            <span> Collapse</span>
            <i class='bx bx-log-out'></i>
          </div>
        </div>
      </div> 
    </nav>