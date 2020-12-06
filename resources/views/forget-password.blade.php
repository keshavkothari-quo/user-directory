<!DOCTYPE html>
<html lang="en">
<head>

    <title>Forget Password </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"></head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

<body >
<div>

    <div class="card-header">
        <h3>Foget Password</h3>
    </div>
    <div>
        @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    {{$errors->first()}}
                </div>
        @endif
        @if(session()->has('sendEmail'))
            <div class="alert alert-success" role="alert">
                {{session('sendEmail')}}
            </div>
        @endif
            @if(session()->has('exception'))
                <div class="alert alert-danger" role="alert">
                    {{session('exception')}}
                </div>
            @endif
    </div>
        <div class="card-body">
            <form action="{{url('forget')}}" method="POST" id="logForm">
                {{ csrf_field() }}
                <div class="form-group" id="divEmail">
                    <label  for="inputEmailAddress">Email</label>
                    <input  id="inputEmailAddress" type="email"  name="email" placeholder="Enter email address" />
                    @if ($errors->has('email'))
                        <span class="error">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="form-group d-flex align-items-center">
                    <button class="btn btn-primary" type="submit">Reset Password</button>
                </div>
            </form>
        </div>
        <div class="align-items-left card-footer text-center">
            <div class="small"><a href="{{url('register')}}">Need an account? Sign up!</a></div>
            <div class="small"><a href="{{url('login')}}">Have an account? Sign in</a></div>
        </div>

</div>
</body>
</html>
