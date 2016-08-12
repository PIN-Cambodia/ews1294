@extends('layouts.master')
@section('content')
<!-- Opening a form -->
{!! Form::open(array('route' =>'auth.login', 'method'=>'post')) !!}
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-2 padingtop">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th> # </th>
                <th> User </th>
                <th> Actions </th>
              </tr>
            </thead>
            <tr>
              <td> 1 </td>
              <td> 1 </td>
              <td> {{ $test }} </td>
            </tr>
          </table>
        </div>
    </div>
</div>
<!-- closing form -->
{!! Form::close() !!}
@endsection
