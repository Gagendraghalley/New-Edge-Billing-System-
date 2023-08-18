<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon"  href="{{ asset('images/netlogo.png') }}">
    <title>NET ERP System</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
</head>

<body class="hold-transition login-page" style="background-image: url('images/background.jpg'); background-size: cover; background-repeat: no-repeat; backdrop-filter: blur(3px);">
    <div class="title m-b-md">
        <span style="color:white"><h3><b> NET ERP System</b></h3></span>
    </div>
    <div class="login-box">
        <div class="login-logo bg-secondary text-white">
            <b>Registration</b>
        </div>
        <div class="card">
            <div class="card-body login-card-body col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- <p class="login-box-msg"><strong><u></u></strong></p> -->
                <form action="{{ url('save_new_registration') }}" method="POST" id="registration_form">
                    @csrf
                        @if($Invalid!=null)
                            <div class="alert alert-danger col-12"> {{ $Invalid }}</div>
                        @endif
                        @foreach ($errors->all() as $error)
                            <span class="text-danger">{{ $error }}</span> <br>
                        @endforeach
                        
                        <div class="form-group">
                            <input type="name" id="name" :class="{ 'is-invalid': form.errors.has('name') }" name="name" placeholder="Name" class="form-control" required>
                            <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="input-group mb-3">
                            <input type="contact" id="contact" :class="{ 'is-invalid': form.errors.has('contact') }" name="contact" placeholder="Mobile Number" class="form-control" minlength="8" maxlength="8" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-phone"></span>
                                </div>
                            </div>
                            <has-error :form="form" field="contact"></has-error>
                        </div>
                        <div class="input-group mb-3">
                            <input type="email" id="emails" :class="{ 'is-invalid': form.errors.has('email') }" name="email" placeholder="Email" class="form-control" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password"  :class="{ 'is-invalid': form.errors.has('password') }" name="password" placeholder="Password"  class="form-control" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <has-error :form="form" field="password"></has-error>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password"  :class="{ 'is-invalid': form.errors.has('confirm_password') }" name="confirm_password" placeholder="Confirm Password"  class="form-control" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <has-error :form="form" field="confirm_password"></has-error>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 align-items-center">
                                <button type="submit" id="new_register" class="btn btn-secondary btn-block">Register</button>
                            </div>
                        </div>
                </form>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 home">
                <a href="{{ url('/') }}"><i class="fa fa-arrow-left">Home</i></a>
            </div>
        </div>
    </div>
    <style>
        .home{
            text-align: center;
        }
        .login-box {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            background-color: #f8f9fa;
        }
        .card{
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            background-color: #f8f9fa;
        }
        .login-logo{
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 5px;
            background-color: #f8f9fa;
            font-size: 22px;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
            animation: glow 1.5s ease-in-out infinite alternate;
        }
        @keyframes glow {
            0% {
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
            }
            100% {
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.9), 0 0 30px rgba(255, 255, 255, 0.7), 0 0 40px rgba(255, 255, 255, 0.5);
            }
        }

        .custom-form .btn-primary {
        border-radius: 20px;
        }
    </style>
    <script>
        function register(id){
            $('#'+id).prop('disabled',true);
            $('#registration_form').submit();

        }
    </script>
</body>

</html>