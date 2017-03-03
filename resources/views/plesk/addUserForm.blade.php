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
@endsection
