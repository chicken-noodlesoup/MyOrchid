<!-- sidebar -->
<nav class="sidebar">
      <div class="menu_content">
        <ul class="menu_items">
          <div class="menu_title menu_dahsboard"></div>
          <!-- duplicate or remove this li tag if you want to add or remove navlink with submenu -->
          <!-- Start -->
          <li class="item">
            <a href="admin.php" class="nav_link">
              <span class="navlink_icon">
                <i class="bx bx-home-alt"></i>
              </span>
              <span class="navlink">Home</span>
            </a>
          </li>
          <!-- End -->

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
              <a href="admin_addorchid.php" class="nav_link sublink">Add new orchid</a>
              <a href="orchid information.php" class="nav_link sublink">Orchid information</a>
              <a href="orchid care details.php" class="nav_link sublink">Orchid care details</a>
              <a href="orchid_pest.php" class="nav_link sublink">Orchid Pest</a>
            </ul>
          </li>
          <!-- end -->

          <!-- start -->
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="bx bx-grid-alt"></i>
              </span>
              <span class="navlink">User</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="user_information.php" class="nav_link sublink">User information</a>
            </ul>
          </li>
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
              <a href="admin_discussion.php" class="nav_link sublink">Forum</a>
              <a href="admin_new_discussion.php" class="nav_link sublink">Add Topic</a>
              <a href="admin_owntopic.php" class="nav_link sublink">My Topic</a>
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