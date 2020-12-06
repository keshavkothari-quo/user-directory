<!DOCTYPE html>
<html lang="en">
<head>

    <title>Reset Password Form </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"></head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

<body >
<div>

    <div class="card-header">
        <h3>Reset Password</h3>
    </div>
    @if (session()->has('resetSuccess'))
        <div class="alert alert-success" role="success">
            {{ session('resetSuccess') }}</div>
    @endif

     <div class="card-body">
           <form action="{{url('reset-password-link')}}" method="POST" id="logForm">
                {{ csrf_field() }}
               <input value="{{$userEmail}} " name="userId" hidden>
               <div class="form-group">
                   <label class="small mb-1" for="inputPassword">New Password</label>
                   <input class="form-control py-4" id="inputPassword" type="password" name="newPassword" placeholder="Enter New password" />
                   <i class="far fa-eye" id="togglePassword"></i>
                   @if ($errors->has('newPassword'))
                       <span class="error">{{ $errors->first('newPassword') }}</span>
                   @endif
               </div>
               <div class="form-group">
                   <label class="small mb-1" for="inputPassword">Confirm Password</label>
                   <input class="form-control py-4" id="inputConfirmPassword" type="password" name="confirmPassword" placeholder="Confirm password" />
                   <i class="far fa-eye" id="toggleConfirmPassword"></i>
                   @if ($errors->has('confirmPassword'))
                       <span class="error">{{ $errors->first('confirmPassword') }}</span>
                   @endif
               </div>
               <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                   <button class="btn btn-primary" type="submit">Update Password</button>
               </div>
            </form>
        </div>
        <div class="card-footer text-center">
            <div class="small"><a href="{{url('register')}}">Need an account? Sign up!</a></div>
        </div>


</div>
</body>
<script>

    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#inputPassword');

    togglePassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
    });

    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const confirmPassword = document.querySelector('#inputConfirmPassword');

    toggleConfirmPassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
    });
</script>
</html>
