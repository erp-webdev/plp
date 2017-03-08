@extends('errors.layout')
@section('content')
    <style type="text/css">
       /* body, pre, .wrapper{
            background-color: #B70606;
            color: #fff;
        }*/
    </style>
    <div class="content">
        <!-- Icon -->
        <div class="col-xs-12 col-sm-3 col-md-3">
            <img src="{{ url('/man.png') }}" class="img-responsive">
        </div>
        <!-- Message -->
        <div class="col-xs-12 col-sm-9 col-md-9">
            <div style="padding-left:5px">
                <p class="title">Ooops!</p>
                <h4>Site is on Maintenance Mode</h4>
                <p>Sorry, this website is on maintenance.<br> 
                We'll be back soon.
                    </p>
            </div>
        </div>
    </div>
@endsection