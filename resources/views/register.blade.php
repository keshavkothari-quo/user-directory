<!DOCTYPE html>
<html lang="en">
<head>

    <title>Register Form </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"></head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

<body >
<div>

    <div class="card-header">
        <h3>Register</h3>
    </div>
    <div>
        @if (session()->has('failLogin'))
            <div class="alert alert-danger" role="alert">
                {{ session('failLogin') }}</div>
        @endif
        @if (session()->has('exceptionLogin'))
                <div class="alert alert-danger" role="alert">
                    {{ session('exceptionLogin') }}
                </div>

        @endif
        @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    {{$errors->first()}}
                </div>
        @endif
    </div>
    <div class="card-body">
        Email <input type="radio" onclick="javascript:emailMobileCheck();" name="emailMobile" id="email" value="email" checked>
        Mobile <input type="radio" onclick="javascript:emailMobileCheck();" name="emailMobile" id="mobile" value="mobile">
        <br>
        <form action="{{url('post-register')}}" method="POST" id="logForm">
            {{ csrf_field() }}
            <div class="form-group" id="divEmail" >
                <label  for="inputEmailAddress">Email</label>
                <input  id="inputEmailAddress" type="email"  name="email" placeholder="Enter email address" />
                @if ($errors->has('email'))
                    <span class="error">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div class="form-group" id="divMobile" style="display: none">
                <label  for="inputMobile">Mobile</label>
                <input  id="inputMobile" type="text" name="mobile" placeholder="Enter mobile" />
                @if ($errors->has('mobile'))
                    <span class="error">{{ $errors->first('mobile') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label class="small mb-1" for="inputPassword">Password</label>
                <input id="inputPassword" type="password" name="password" placeholder="Enter password" />
                <i class="far fa-eye" id="togglePassword"></i>
                @if ($errors->has('password'))
                    <span class="error">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="form-group d-flex align-items-center">
                <button class="btn btn-primary" type="submit">Register</button>
            </div>
        </form>
    </div>
    <div class="card-footer text-center">
        <div class="small"><a href="{{url('login')}}">Have an account? Sign in!</a></div>
    </div>


</div>
</body>
<script>
    function emailMobileCheck() {
        if (document.getElementById('email').checked) {
            document.getElementById('divEmail').style.display = 'block';
            document.getElementById('divMobile').style.display = 'none';
            document.getElementById('inputMobile').value = '';

        }
        if (document.getElementById('mobile').checked) {
            document.getElementById('divEmail').style.display = 'none';
            document.getElementById('divMobile').style.display = 'block';
            document.getElementById('inputEmailAddress').value = '';
        }

    }
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#inputPassword');

    togglePassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
    });
</script>
</html>
