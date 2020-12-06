<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

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

             <div class="form-group">
             <label  for="inputName">Full Name</label>
                 @if($userId->name)
                     <input  id="inputName" type="text"  name="name" placeholder="Enter your Full Name" value="{{$userId->name}}" />
                 @else
                     <input  id="inputName" type="text"  name="name" placeholder="Enter your Full Name" />
                 @endif
             @if ($errors->has('name'))
                 <span class="error">{{ $errors->first('name') }}</span>
             @endif
         </div>

             <div class="form-group" id="divEmail">
                 <label  for="inputEmailAddress">Email</label>
                 @if($userId->email)
                    <input  id="inputEmailAddress" type="email"  name="email" placeholder="Enter email address" value="{{$userId->email}}" />
                 @else
                     <input  id="inputEmailAddress" type="email"  name="email" placeholder="Enter email address" />
                 @endif
                 @if ($errors->has('email'))
                     <span class="error">{{ $errors->first('email') }}</span>
                 @endif
             </div>
             <div class="form-group" id="divMobile" >
                 <label  for="inputMobile">Mobile</label>
                 @if($userId->mobile)
                    <input  id="inputMobile" type="text" name="mobile" placeholder="Enter mobile" value="{{$userId->mobile}}" />
                 @else
                     <input  id="inputMobile" type="text" name="mobile" placeholder="Enter mobile" />
                 @endif
                 @if ($errors->has('mobile'))
                     <span class="error">{{ $errors->first('mobile') }}</span>
                 @endif
             </div>

         <div class="form-group">
             <label  for="inputDob">Date of Birth</label>
             @if($userId->dob)
                <input  id="inputDob" type="text"  name="dob" class="date" value="{{$userId->dob}}"/>
             @else
                 <input  id="inputDob" type="text"  name="dob" class="date" value="{{$userId->dob}}"/>
             @endif
             @if ($errors->has('dob'))
                 <span class="error">{{ $errors->first('dob') }}</span>
             @endif
         </div>
         <br>
         <br>
         <br>
         @if($userId->userCity)
             <input id="checkCity" hidden value="{{$userId->userCity->city_id}}" \>
         @endif
         <div class="form-group">
             <label for="state">State</label>
             <select class="form-control" id="state-dropdown" name="state">
                 <option value="">Select State</option>
             </select>
         </div>
         <div class="form-group">
             <label for="city">City</label>
             <select class="form-control" id="city-dropdown" name="city">
                 <option value="">Select City</option>

             </select>
         </div>
         <div class="form-group">
             <button class="btn btn-primary" type="submit">Save</button>
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
                    $('#city-dropdown').html('<option value="'+cityId+'">'+ userState['name'] +'</option>');
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
