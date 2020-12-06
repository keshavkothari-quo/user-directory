

<div class="card-header col-md-12">
    <div class="col-md-2 "><a href="{{url('contact-list')}}/{{Auth::user()->id }}">Contact List</a></div>
    <div class="col-md-2 "><a href="{{url('edit-profile')}}/{{Auth::user()->id }}">Edit Profile</a></div>
    <div class="col-md-2 "><a href="{{url('reset-password')}}/{{Auth::user()->id }}">Reset Password</a></div>
    <div class="col-md-2 "><a href="{{url('dashboard')}}">Dashboard</a></div>
</div>
