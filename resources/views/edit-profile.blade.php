<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>

</head>
<body class="sb-nav-fixed">
<div class="align-left">
    <i class="fa fa-sign-out"></i>
    <button class="btn btn-warning float-right">
        <a href="{{url('logout')}}">Logout</a>
    </button>
</div>
@if($errors->any())
    <div class="alert alert-danger" role="alert">
        {{$errors->first()}}
    </div>
@endif
<div>
     <h2> Edit following Detail's </h2>
     <form action="{{url('edit-profile')}}" method="POST" id="logForm">
         {{ csrf_field() }}
         <input hidden name="userId" value="{{$userId->id}}">
             <div class="form-group row">
                <label class="col-form-label col-sm-1" for="inputName">Full Name</label>
                 <div class="col-sm-2">
                 @if($userId->name)
                     <input class="form-control-plaintext" id="inputName" type="text"  name="name" placeholder="Enter your Full Name" value="{{$userId->name}}" />
                 @else
                     <input class="form-control-plaintext" id="inputName" type="text"  name="name" placeholder="Enter your Full Name" />
                 @endif
                 @if ($errors->has('name'))
                     <span class="error">{{ $errors->first('name') }}</span>
                 @endif
                 </div>
             </div>

             <div class="form-group row" id="divEmail">
                 <label class="col-form-label col-sm-1"   for="inputEmailAddress">Email</label>
                 <div class="col-sm-2">
                 @if($userId->email)
                    <input class="form-control-plaintext" id="inputEmailAddress" type="email"  name="email" placeholder="Enter email address" value="{{$userId->email}}" />
                 @else
                     <input class="form-control-plaintext" id="inputEmailAddress" type="email"  name="email" placeholder="Enter email address" />
                 @endif
                 @if ($errors->has('email'))
                     <span class="error">{{ $errors->first('email') }}</span>
                 @endif
                 </div>
             </div>
             <div class="form-group row" id="divMobile" >
                 <label class="col-form-label col-sm-1"   for="inputMobile">Mobile</label>
                 <div class="col-sm-2">
                 @if($userId->mobile)
                    <input class="form-control-plaintext" id="inputMobile" type="text" name="mobile" placeholder="Enter mobile" value="{{$userId->mobile}}" />
                 @else
                     <input class="form-control-plaintext" id="inputMobile" type="text" name="mobile" placeholder="Enter mobile" />
                 @endif
                 @if ($errors->has('mobile'))
                     <span class="error">{{ $errors->first('mobile') }}</span>
                 @endif
                 </div>
             </div>

         <div class="form-group row">
             <label class="col-form-label col-sm-1"   for="inputDob">Date of Birth</label>
             <div class="col-sm-2">
             @if($userId->dob)
                <input class="form-control-plaintext" id="inputDob" type="text"  name="dob" class="date" value="{{$userId->dob}}"/>
             @else
                 <input class="form-control-plaintext" id="inputDob" type="text"  name="dob" class="date" value="{{$userId->dob}}"/>
             @endif
             @if ($errors->has('dob'))
                 <span class="error">{{ $errors->first('dob') }}</span>
             @endif
             </div>
         </div>
         <br>
         <br>
         <br>
         @if($userId->userCity)
             <input class="form-control-plaintext" id="checkCity" hidden value="{{$userId->userCity->city_id}}" \>
         @endif
         <div class="form-group row">
             <label class="col-form-label col-sm-1"  for="state">State</label>
             <select class="form-control col-sm-3" id="state-dropdown" name="state">
                 <option value="">Select State</option>
             </select>
         </div>
         <div class="form-group row">
             <label class="col-form-label col-sm-1"  for="city">City</label>
             <select class="form-control col-sm-2" id="city-dropdown" name="city">
                 <option value="">Select City</option>
             </select>
         </div>
         <div class="form-group row">
             <button class="btn btn-primary" type="submit">Save</button>
             <a href="{{url('/dashboard')}}">
             <button class="btn btn-danger" type="button"> Cancel </button>
             </a>
         </div>

     </form>
 </div>
@include('footer');

</body>

<script>
    $('.date').datepicker({
        autoclose: true,
        dateFormat: "yy-mm-dd"
    });
</script>
{{-- Code For Drop down--}}
<script>
    $(document).ready(function() {
        $('#state-dropdown').on('change', function() {
            var state_id = this.value;
            $("#city-dropdown").html('');
            $.ajax({
                url:"{{url('api/city')}}",
                type: "POST",
                data: {
                    state_id: state_id,
                    _token: '{{csrf_token()}}'
                },
                dataType : 'json',
                success: function(result){
                    $.each(result.cities,function(key,value){
                        $("#city-dropdown").append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }
            });
        });
        var userCity = $('#checkCity').val();
        if(userCity){
            stateCityDropDown(userCity);
        }
        else{
            stateDropDown();
        }
        function stateCityDropDown(userCity) {
            $('#state-dropdown').html('<option value="">Select State</option>');
            $.ajax({
                url:"{{url('api/userState')}}?cityId=" + userCity,
                type: "GET",
                dataType : 'json',
                success: function(result){
                    var userState = result.states.userState;
                    $.each(result.states,function(key,value){
                        $("#state-dropdown").append('<option value="'+value.id+'">'+value.name+' </option>');
                    });
                    $('#state-dropdown option[value=userState]').attr("selected",true);
                    $('#state-dropdown').val(userState['id']);
                    $('#city-dropdown').html('<option value="'+userCity+'">'+ userState['name'] +'</option>');
                }
            });
        }
        function stateDropDown(){
            $('#state-dropdown').html('<option value="">Select State</option>');
            $.ajax({
                url:"{{url('api/state')}}",
                type: "GET",
                dataType : 'json',
                success: function(result){
                    $.each(result.states,function(key,value){
                        $("#state-dropdown").append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                    $('#city-dropdown').html('<option value="">Select State First</option>');
                }
            });
        }
    });
</script>
</html>
