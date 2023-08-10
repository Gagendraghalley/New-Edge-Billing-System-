<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.nam', 'NET Billing System') }}</title>
  <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
  <link rel="shortcut icon"  href="{{ asset('images/profile.png') }}">
</head>

<body class="hold-transition sidebar-mini" >
  <div class="wrapper" id="app">
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom" style="background-image: url('images/Bhutan.png');background-size: cover;background-repeat: no-repeat;">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link text-white" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
      <li class="nav-item">
        <router-link to="#" class="nav-link">
          <p><b class="text-white">Logged as </b><b class="text-info">{{session('User_details')['email']}}</b></p>
        </router-link>
      </li>
    </ul>
  <!-- notification  -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <router-link class="notification-link" to="/list_task">
          <span class="notification-icon">
            <i class="fa fa-bell" style="color: black;"></i>
          </span>
          <span class="notification-count">
            <span id="notificationcount"></span>
          </span>
      </router-link>
    </li>
  </ul>
  </nav>
    <aside class="main-sidebar elevation-4" style="background-image: url('images/Bhutan.png'); background-size: cover; background-repeat: no-repeat">
      <router-link to="/dashboard" class="brand-link " style="background-image: url('images/Bhutan.png');background-size: cover;background-repeat: no-repeat;">
        @php
          $profilePath = session('User_details')['profile_path'];
        @endphp
        <img id="profileImage" src="{{ asset('storage/' . $profilePath) }}" alt="no image" class="brand-image img-circle elevation-3" style="opacity: .8; border-radius: 50%;">
        <!-- <img src="{{ asset('/images/profile.png') }}" alt="Profile" class="brand-image img-circle elevation-3" style="opacity: .8;border-radius: 50%;"> -->
        <span class="brand-text font-weight-light text-white">{{ config('app.nam', ' NET Billing System') }}</span>
      </router-link>
      <hr>
      <div>
          @include('layouts.sidebar-menu')
      </div>
    </aside>
    <div class="content-wrapper pt-1">
      <router-view></router-view>
      <vue-progress-bar></vue-progress-bar>
    </div>
    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 1.0.0
      </div>
      <strong>&copy; <span id="currentYear"></span> <a href="#">NET Billing System Web Application</a>. All rights reserved.</strong>
    </footer>
  </div>
  <script src="{{ mix('/js/app.js') }}"></script>
</body>
<script>
  //loading default image at first
  var profileImage = document.getElementById('profileImage');
    profileImage.onerror = function() {
      this.src = "{{ asset('/images/profile.png') }}";
    };
  //loading current
  var currentYear = new Date().getFullYear();
  document.getElementById("currentYear").textContent = currentYear;  


  getNotification();
  let data = 0;

  //refereshing every 500 milisec
  setInterval(function() {
      getNotification();
    }, 3000);

  function logout() {
    Swal.fire({
      title: 'Logout Confirmation',
      text: 'Are you sure you wish to logout?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Logout',
      cancelButtonText: 'Cancel',
      customClass: {
        popup: 'logout-popup',
        title: 'logout-title',
        text: 'logout-text',
        confirmButton: 'logout-confirm-button',
        cancelButton: 'logout-cancel-button'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        window.location = "user_logout";
      }
    });
}
// pulling Notification
function getNotification() {
        axios.get('getNotification')
        .then(response => {
            let data =response.data.notificationCount;
            $('#notificationcount').html(data);
            
        });
    }
</script>
<style>
  .logout-popup {
    border-radius: 8px;
    background-color: #f5f5f5;
    border: none;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  }

.logout-title {
    font-size: 24px;
    font-weight: bold;
    color: #333;
  }

.logout-text {
    font-size: 18px;
    color: #555;
  }

.logout-confirm-button {
    background-color: #3085d6;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
  }

.logout-cancel-button {
  background-color: #d33;
  color: #fff;
  padding: 10px 20px;
  border-radius: 5px;
  font-size: 16px;
}
.notification-link {
  position: relative;
  display: inline-block;
}

.notification-icon {
  position: relative;
  display: inline-block;
}

.notification-icon i {
  font-size: 20px;
  color: #555;
}

.notification-count {
  position: absolute;
  top: -8px;
  right: -8px;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 18px;
  height: 18px;
  background-color: #e53935;
  color: #fff;
  font-size: 10px;
  font-weight: bold;
  border-radius: 50%;
}
</style>

</html>