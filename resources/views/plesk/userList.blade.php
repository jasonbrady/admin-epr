@extends('layouts.app')

@section('content')

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
