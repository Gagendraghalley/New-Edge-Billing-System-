<div> 
    <nav class="container-fluid">
        <div class="loader_bg">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent mainmenu " data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <router-link to="/dashboard" class="nav-link screen_nav text-white top_menu_hover">
                        <i class="nav-icon fas fa-chart-bar text-white"></i>
                        <span class="menu-text">Dashboard</span>
                    </router-link>
                </li>

                <!-- Admin menu -->
                @if(session('User_details')['system_role']!=null && session('User_details')['system_role']=='Admin')
                    <!-- <li class="nav-item">
                        <router-link to="/userprofile" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-user nav-icon text-white"></i>
                            <p class="text-white">Profile</span>
                        </router-link>  
                    </li> -->
                    <li class="nav-item">
                        <router-link to="/invoice_index" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-file-invoice nav-icon text-white"></i>
                            <span class="menu-text">Create Billing Invoice</span>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/bill_index" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-file-invoice-dollar nav-icon text-white"></i>
                            <span class="menu-text">Bill Update</span>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/purchase_order_index" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-file-signature nav-icon text-white"></i>
                            <span class="menu-text">Create Purchase Order</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/update_purchase_order" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-pencil-alt nav-icon text-white"></i>
                            <span class="menu-text">Update Purchase Order</span>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/users_index" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-cogs nav-icon text-white"></i>
                            <span class="menu-text">Manage System Users</span>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/change_password" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-key nav-icon text-white"></i>
                            <span class="menu-text">Change your password</span>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/passReset" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-sync-alt nav-icon text-white"></i>
                            <span class="menu-text">Password Reset</span>
                        </router-link>
                    </li>

                @endif
                <!-- General Menu for all Task Management user -->
                @if(session('User_details')['system_role']!=null && session('User_details')['system_role']=='User')
                    <!-- <li class="nav-item">
                        <router-link to="/userprofile" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-user nav-icon text-white"></i>
                            <span class="menu-text">Profile</span>
                        </router-link>  
                    </li> -->
                    <li class="nav-item">
                        <router-link to="/invoice_index" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-file-invoice nav-icon text-white"></i>
                            <span class="menu-text">Create Billing Invoice</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/bill_index" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-file-invoice-dollar nav-icon text-white"></i>
                            <span class="menu-text">Bill Update</span>
                        </router-link>
                    </li>
                    
                    <li class="nav-item">
                        <router-link to="/change_password" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-key nav-icon text-white"></i>
                            <span class="menu-text">Change your password</span>
                        </router-link>
                    </li>
                @endif
                @if(session('User_details')['system_role']!=null && session('User_details')['system_role']=='View')
                    <!-- <li class="nav-item">
                        <router-link to="/userprofile" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-user nav-icon text-white"></i>
                            <span class="menu-text">Profile</span>
                        </router-link>  
                    </li> -->
                    <li class="nav-item">
                        <router-link to="/bill_index" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-file-invoice nav-icon text-white"></i>
                            <span class="menu-text">Bills with status</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/update_purchase_order" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-list-alt nav-icon text-white"></i>
                            <span class="menu-text">Purchase Order list</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/change_password" class="nav-link screen_nav text-white top_menu_hover">
                            <i class="fa fa-key nav-icon text-white"></i>
                            <span class="menu-text">Change your password</span>
                        </router-link>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="#" onclick="logout()" class="nav-link screen_nav text-white top_menu_hover">
                        <i class="fa fa-sign-out-alt nav-icon text-white"></i>
                        <span class="menu-text">Sign out</span>
                    </a>
                </li>

            </ul>
        </div>
    </nav>
</div>  

