<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

</head>
<body>
<div>
    <div class="align-left">
        <i class="fa fa-sign-out"></i>
        <button class="btn btn-warning float-right">
            <a href="{{url('logout')}}">Logout</a>
        </button>
    </div>
    @if (session()->has('success'))
        <div id="successMessage" class="alert alert-success col-sm-2" role="alert">
            {{ session('success') }}
        </div>
    @endif
<form action="{{url('search-contact')}}" method="get" id="search">
    {{ csrf_field() }}
    <div class="form-group">
        <input name="userId" value="{{Auth::user()->id}}" hidden>
        @if(isset($searchData))
            <input class="form-group" type="text" name="search" id="search"  value="{{$searchData['search']}}" placeholder="Search for name" aria-label="Search" aria-describedby="basic-addon2" />
            <i id="closeSearch" onclick="closeSearch()" class="far fa-times-circle"></i>
        @else
            <input class="form-group" type="text" name="search" id="search" placeholder="Search for name" aria-label="Search" aria-describedby="basic-addon2" />
        @endif
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
    </div>
</form>
</div>
<table id="contactTable" class="table table-striped table-bordered" style="width:100%">
    <thead>
    <tr>
        <th id="name">
            @sortablelink('name')
        </th>
        <th id="email">
            @sortablelink('email')
        </th>
        <th id="mobile">@sortablelink('mobile')</th>
        <th id="dob">@sortablelink('dob')</th>
        <th id="city">City</th>
        <th id="action">Action</th>
    </tr>
    </thead>
        <tbody>
        @if(!empty($data) && $data->count())
            @foreach($data as $key => $value)
                <tr>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->email }}</td>
                    <td>{{ $value->mobile }}</td>
                    <td>{{ $value->dob }}</td>
                    @if($value->userCity)
                        <td>{{ $value->userCity->city->name }}</td>
                    @else
                        <td> - </td>
                    @endif
                    <td>
                        <button name="add-friend"  id="add-friend-{{$value->id}}" onclick="addFriend({{$value->id}})" class="btn btn-primary">
                            <i class="fa fa-plus"> Add as friend </i></button>
                        <button name="friendAdded"  id="friendAdded-{{$value->id}}" style="display: none"  disabled class="btn btn-success"> Friend Added </button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="10">There are no data.</td>
            </tr>
        @endif
        </tbody>

    </table>
    {!! $data->appends(['sort' => 'name'])->links() !!}
</body>
<script>
    function addFriend(friendId){
        var userID = {{Auth::user()->id}};
        $.ajax({
            url:"{{url('api/user-friend')}}",
            type: "POST",
            data: {
                userId : userID,
                friendId : friendId,
                _token: '{{csrf_token()}}'
            },
            dataType : 'json',
            success: function(result){
                document.getElementById('add-friend-' + friendId).style.display = 'none';
                document.getElementById('friendAdded-' + friendId).style.display = 'block';
            }
        });
    }
    function closeSearch(){
        window.location = "{{url('/contact-list')}}/{{Auth::user()->id}}"
    }
    $(function() {
        setTimeout(function () {
            $("#successMessage").hide('blind', {}, 500)
        }, 5000);
    });
</script>
@include('footer');
</html>
