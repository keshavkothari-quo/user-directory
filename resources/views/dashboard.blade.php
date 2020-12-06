
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
</head>
 <!-- Navbar-->
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
@if (session()->has('profileUpdate'))
    <div class="alert alert-success" role="alert">
        {{ session('profileUpdate') }}
    </div>
@endif
@if (session()->has('passwordUpdate'))
    <div class="alert alert-success" role="alert">
        {{ session('passwordUpdate') }}
    </div>
@endif

<div>
    <h3> User Dashboard </h3>
    <span>Your profile detaits, to Edit  <a href="{{url('edit-profile')}}/{{Auth::user()->id }}">Click here</a> </span>
{{--    <form action="{{url('save-profile')}}" method="POST" id="logForm">--}}
        {{ csrf_field() }}
        <input hidden name="userId" value="{{$userId->id}}">
        <div class="form-group">
        @if($userId->name)
            <label for="inputname">Name</label>
            <span>{{$userId->name}} </span>
        @endif
        </div>
        <div class="form-group" id="divEmail">
            @if($userId->email)
                <label for="inputname">Email: </label>
                <span> {{$userId->email}} </span>
            @endif
        </div>
        <div class="form-group" id="divMobile">
            @if($userId->mobile)
                <label  for="inputname">Mobile</label>
                <span> {{$userId->mobile}}
                </span>
            @endif
        </div>
        <div class="form-group md-form md-outline input-with-post-icon datepicker">
            @if(!$userId->dob)
                <label  for="inputname">DOB</label>
                <span> {{$userId->dob}}</span>
            @endif
        </div>

        @if($userId->userCity)
            <label  for="inputname">City</label>
            <div id="userCityName">
            </div>
            <input id="userCityId" hidden value="{{$userId->userCity->city_id}}">
        @endif
        @if(!$userId->userCity)
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
        @endif
{{--    </form>--}}
</div>
</body>
@include('footer');
</body>

<script>
    $('.date').datepicker({
        autoclose: true,
        dateFormat: "yy-mm-dd",
        inline: true
    });
</script>
{{-- Code For Drop down--}}
<script>
    $(document).ready(function() {
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

    var userCity = $('#userCityId').val();
    if(userCity){
        stateCityDropDown(userCity);
    }
    function stateCityDropDown(userCity) {
        var cityId = userCity;
        $.ajax({
            url:"{{url('api/userState')}}?cityId="+cityId,
            type: "GET",
            dataType : 'json',
            success: function(result){
                var userState = result.states.userState;
                console.log(userState['name'] );
                $('#userCityName').html('<span>' + userState['name']  + '</span>');
            }
        });
    }
</script>
</html>
