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
            <b>Login</b>
        </div>
        <div class="card">
            <div class="card-body login-card-body col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                <div id="loginsection">
                    <form action="/userlogin" method="get">
                        @csrf
                        <!-- session out message -->
                        @if(session('session_expired'))
                            <div class="alert alert-warning">
                                {{ session('session_expired') }}
                            </div>
                        @endif

                        @if($Invalid!=null)
                        <div class="alert alert-success col-12"> {{ $Invalid }}</div>
                        @endif
                        @foreach ($errors->all() as $error)
                        <span class="text-danger">{{ $error }}</span> <br>
                        @endforeach
                        <div class="input-group mb-3">
                            <input type="text" id="emails" :class="{ 'is-invalid': form.errors.has('email') }" name="email" placeholder="Username" class="form-control">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 align-items-center" >
                                <button type="submit" class="btn btn-secondary btn-block">Sign In</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="forgotpassword" style="display:none">
                    <p class="login-box-msg">Forget Password</p>
                    <form action="userlogin">
                        <span id="emailerr" class="text-danger"></span>
                        <div class="input-group mb-3">
                            <input type="text" id="emailforget" class="form-control" placeholder="Student Code">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <span class="text-danger" id="dob_err"></span>
                        <div class="input-group mb-3">
                            <input type="date" id="dob" :class="{ 'is-invalid': form.errors.has('dob') }" name="dob" placeholder="Date of Birthr" class="form-control">
                        </div>
                        <span id="response_error" class="text-danger"></span>
                        <div class="row">
                            <div class="col-12">
                                <button type="button" id="btnreset" onclick="passwordsend()" class="btn btn-primary btn-block">Get password </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <a href="{{ url('/') }}"><i class="fa fa-arrow-left">Home</i></a>
            </div>
        </div>
    </div>
    <style>
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
        .glow-input:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.8);
            animation: glow 1.5s ease-in-out infinite alternate;
        }

        .custom-form .btn-primary {
        border-radius: 20px;
        }
    </style>
    <script>
        function showgorget() {
            $('#forgotpassword').show();
            $('#loginsection').hide();
        }

        function validateform() {
            let regtype = true;
            if ($('#emailforget').val() == "") {
                $('#emailerr').html('Please provide email');
                regtype = false;
            }
            if ($('#dob').val() == "") {
                $('#dob_err').html('Please enter DOB');
                regtype = false;
            }
            return regtype;
        }

        function passwordsend() {
            if (validateform()) {
                $('#btnreset').prop('disabled', true);
                $.ajax({
                    type: "GET",
                    url: 'resetportalpassword/' + $('#dob').val() + '/' + $('#emailforget').val(),
                    dataType: "json",
                    success: function(response) {
                        if (response.includes('Error')) {
                            $('#response_error').html(response.split('__')[1]);
                        } else {
                            alert(response);
                            Swal.fire({
                                text: response,
                                icon: 'info',
                                confirmButtonText: 'OK',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        }
                    }
                });
            }
        }
    </script>
</body>

</html>