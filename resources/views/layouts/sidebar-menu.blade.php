<div> 
    <nav class="container-fluid">
        <div class="loader_bg">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent mainmenu " data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <router-link to="/dashboard"  class="nav-link screen_nav text-white top_menu_hover">
                        <i class="nav-icon fas fa-tachometer-alt text-white"></i>
                        <p class= "text-white">
                            <b>Dashboard</b>
                        </p>
                    </router-link>
                </li>
                <!-- Admin menu -->
                @if(session('User_details')['system_role']!=null && session('User_details')['system_role']=='Admin')
                    <li class="nav-item">
                        <router-link to="/userprofile" class="nav-link">
                            <i class="fa fa-user nav-icon text-white"></i>
                            <p class="text-white"><b>Profile</b></p>
                        </router-link>  
                    </li>
                    <li class="nav-item">
                        <router-link to="/organization_index" class="nav-link">
                            <i class="fa fa-user nav-icon text-white"></i>
                            <p class="text-white"><b>Organization Details</b></p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/users_index" class="nav-link">
                            <i class="fa fa-user nav-icon text-white"></i>
                            <p class="text-white"><b>Manage System Users</b></p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/chage_password" class="nav-link">
                            <i class="fa fa-users nav-icon text-white"></i>
                            <p class="text-white"><b>Change your password</b></p>
                        </router-link>
                    </li>
                
                    <li class="nav-item">
                        <router-link to="/passReset" class="nav-link">
                            <i class="fa fa-user nav-icon text-white"></i>
                            <p class="text-white"><b>Password Reset</b></p>
                        </router-link>
                    </li>
                @endif
            <!-- General Menu for all Task Management user -->
                @if(session('User_details')['system_role']!=null && session('User_details')['system_role']=='User')
                    <li class="nav-item">
                        <router-link to="/userprofile" class="nav-link">
                            <i class="fa fa-user nav-icon text-white"></i>
                            <p class="text-white"><b>Profile</b></p>
                        </router-link>  
                    </li>
                    <li class="nav-item">
                        <router-link to="/task_index" class="nav-link">
                            <i class="fa fa-users nav-icon text-white"></i>
                            <p class="text-white"><b>Manage Your Tasks</b></p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/chage_password" class="nav-link">
                            <i class="fa fa-lock nav-icon text-white"></i>
                            <p class="text-white"><b>Change Your Password</b></p>
                        </router-link>
                    </li>
                @endif
                    <li class="nav-item">
                        <a href="#" onclick="logout()" class="nav-link">
                            <i class="fa fa-sign-out-alt nav-icon text-white"></i>
                            <p class= "text-white"><b>Sign out</b></p>
                        </a>
                    </li>
            </ul>
        </div>
    </nav>
</div>  

