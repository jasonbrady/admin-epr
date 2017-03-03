@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-epr">
                    <div class="panel-heading">Results of update</div>

                    <div class="panel-body">
                        <table class="table table-responsive table-bordered">
                            <tr>
                                <th>Username</th>
                                <th>Result</th>
                            </tr>
                            @foreach($results as $result)
                                <tr>
                                    <td class="name">{{$result['name']}}</td>
                                    <td class="status">{{$result['status']}}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
