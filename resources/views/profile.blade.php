<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

</head>
<body class="sb-nav-fixed">
 @if($userId->email)
     <h3> Hello, Your Email : {{ $userId->email }} </h3>
 @endif
 @if($userId->mobile)
     <h3> Hello, Your mobile : {{ $userId->mobile }} </h3>
 @endif
 <div>
     <h2> Add following Detail's </h2>
     <form action="{{url('save-profile')}}" method="POST" id="logForm">
         {{ csrf_field() }}
         <input hidden name="userId" value="{{$userId->id}}">
         <div class="form-group">
             <label  for="inputName">Full Name</label>
             <input  id="inputName" type="text"  name="name" placeholder="Enter your Full Name" />
             @if ($errors->has('name'))
                 <span class="error">{{ $errors->first('name') }}</span>
             @endif
         </div>
         @if(!$userId->email)
             <div class="form-group" id="divEmail" style="visibility:visible">
                 <label  for="inputEmailAddress">Email</label>
                 <input  id="inputEmailAddress" type="email"  name="email" placeholder="Enter email address" />
                 @if ($errors->has('email'))
                     <span class="error">{{ $errors->first('email') }}</span>
                 @endif
             </div>
         @endif
         @if(!$userId->mobile)
             <div class="form-group" id="divMobile" style="visibility:hidden">
                 <label  for="inputMobile">Mobile</label>
                 <input  id="inputMobile" type="text" name="mobile" placeholder="Enter mobile" />
                 @if ($errors->has('mobile'))
                     <span class="error">{{ $errors->first('mobile') }}</span>
                 @endif
             </div>
         @endif
         <div class="form-group">
             <label  for="inputDob">Date of Birth</label>
             <input  id="inputDob" type="text"  name="dob" class="date" />
             @if ($errors->has('dob'))
                 <span class="error">{{ $errors->first('dob') }}</span>
             @endif
         </div>
         <br>
         <br>
         <br>
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
        $('#inputDob').on('click', function() {
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
        });
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
    });
</script>
</html>
