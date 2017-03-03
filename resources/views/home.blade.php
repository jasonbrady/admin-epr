@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-epr">
                    <div class="panel-heading">Create a New User</div>

                    <div class="panel-body">

                        <form class="form-inline" action="" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="email">Username:</label>
                                <input type="text" class="form-control" id="email" name="username">
                            </div>
                            <div class="form-group">
                                <label for="pwd">Password:</label>
                                <input type="password" class="form-control" id="pwd" name="password">
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-epr">
                    <div class="panel-heading">Users</div>
                    <div class="panel-body">
                        <table class="table table-responsive table-bordered">
                            <thead>
                            <tr>
                                <th>Username</th>
                                <th>Forwarding</th>
                                <th>Address</th>
                                <th>Alias</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user => $attributes)
                                @if($attributes['enabled'] == 'true')
                                    <tr>
                                        <td class="name">{{$user}}</td>
                                        <td class="forwarding">{{$attributes['forwarding']}}</td>
                                        <td class="address">{{$attributes['address']}}</td>
                                        <td class="alias">
                                            @foreach($attributes['alias'] as $alias)
                                                {{$alias}}
                                            @endforeach
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




{{--
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-epr">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
--}}
