@extends('layouts.app')

@section('content')
<style type="text/css">
  
    .login-box{
        -webkit-box-shadow: 0px 0px 30px -5px rgba(0,0,0,0.75);
        -moz-box-shadow: 0px 0px 30px -5px rgba(0,0,0,0.75);
        box-shadow: 0px 0px 30px -5px rgba(0,0,0,0.75);
    }

    body{
       /* background: #49a2f5;
        background: -moz-radial-gradient(center, ellipse cover, #49a2f5 0%, #004a8f 100%);
        background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, #49a2f5), color-stop(100%, #004a8f));
        background: -webkit-radial-gradient(center, ellipse cover, #49a2f5 0%, #004a8f 100%);
        background: -o-radial-gradient(center, ellipse cover, #49a2f5 0%, #004a8f 100%);
        background: -ms-radial-gradient(center, ellipse cover, #49a2f5 0%, #004a8f 100%);
        background: radial-gradient(ellipse at center, #49a2f5 0%, #004a8f 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#49a2f5', endColorstr='#004a8f', GradientType=1 );
*/
        background: url(background.jpg) no-repeat center center fixed; 
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;

    }
    .login-title{
        font-family: "Segoe UI Light", Georgia, Serif;
        font-size: 25px;
        color: #004a8f;
    }
    .align-center{
            display: block;
            margin: auto;
    }
    </style>
<div class="container" style="margin-top:5%;">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="login-title login-box panel" style="text-align:center; background-color: #ffffff;">
                <p style="text-align: center; margin-top: 15px; margin-bottom: 0px"><strong>{{ env('APP_NAME') }}</strong></p>
                <p style="text-align: center; font-size: 10px">(v1.0)</p>
            </div>
            <div class="panel login-box">
                <div style="margin-top: 20px;">
                    <img src="{{ url('nav-brand.png') }}" width="175px" height="60px" class="img-responsive align-center" alt="Responsive image">
                </div>
               
                <div class="panel-body" ng-app="login" ng-controller="LoginCtrl">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('login') }}">
                        <input type="hidden" id="verify_url" value="{{ url('/verify/employee') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <div class="input-group">
                                  <span class="input-group-addon" id="basic-addon1"><i class="fa fa-user"></i></span>
                                  <input id="username" class="form-control" name="employee_id" placeholder="Employee ID" value="{{ old('employee_id') }}" aria-describedby="basic-addon1" style="text-transform:uppercase" ng-change="check_employee()" ng-model="EmpID">
                                </div>
                                @if ($errors->has('employee_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('employee_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                     
                        <div id="pwd" style="display: none" class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <div class="input-group">
                                  <span class="input-group-addon" id="basic-addon2"><i class="fa fa-key"></i></span>
                                  <input id="password" type="password" class="form-control" name="password" placeholder="Password" aria-describedby="basic-addon2">
                                </div>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label style="font-weight: normal"><input type="checkbox" name="remember"> Keep me signed in</label>
                            </div>
                        </div>

                        <div  class="form-group">
                            <div class="col-md-12">
                                <button id="submitBtn" type="submit" class="btn btn-success btn-block" disabled>
                                    <i id="btnUI" class="fa fa-btn fa-sign-in"></i> Login
                                </button>
                                <span class="help-block"><small>Note: Login credentials is the same as your login with SSEP.</small></span>
                            </div>
                            <div class="col-md-12" style="border-top: 1px solid #ccc; padding: 5px; padding-bottom: 0px">
                                <p style="font-size: 10px; text-align: center;">©<?php echo date("Y"); ?> Megaworld Corporation ISM Department, All Rights Reserved</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-8">
        </div>
    </div>
</div>
<script type="text/javascript">
    var app = angular.module('login', []);

    app.controller('LoginCtrl', function($scope, $http) {
        
        $scope.check_employee = function (){
            $("#submitBtn").html('<i class="fa fa-btn fa-spin fa-spinner"></i> Verifying Employee. Please wait! ');
            $("#submitBtn").removeClass('btn-success');
            $("#submitBtn").addClass('btn-default');

            $http({
                method : "GET",
                url : $('#verify_url').val() + "?employee_id=" + $scope.EmpID,
            }).then(function mySucces(response) {
                if(response.data == 0){
                    $("#pwd").attr("style", "display:none");
                    $("#submitBtn").attr('disabled', 'disabled');
                    $("#submitBtn").html('<i class="fa fa-btn fa-times"></i> Employee not found! ');
                    $("#submitBtn").removeClass('btn-default');
                    $("#submitBtn").addClass('btn-danger');
                }else if(response.data == 3){
                    $("#pwd").attr("style", "display:none");
                    $("#submitBtn").attr('disabled', 'disabled');
                    $("#submitBtn").html('<i class="fa fa-btn fa-times"></i> Incorrect Email Address! Please contact your HR BP!');
                    $("#submitBtn").removeClass('btn-default');
                    $("#submitBtn").addClass('btn-danger');
                }else{
                    $("#pwd").removeAttr("style");
                    $("#submitBtn").removeAttr('disabled');
                    $("#submitBtn").html('<i class="fa fa-btn fa-sign-in"></i> Login ');
                    $("#submitBtn").removeClass('btn-danger');
                    $("#submitBtn").addClass('btn-success');

                }
            }, function myError(response) {
                $scope.myWelcome = response.statusText;
            });
        }
    });
</script>
@endsection
