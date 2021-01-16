@extends('layouts.app')
@section('styles')
    <style>
        #myTab .nav-link {
            color:#f1f1f1
        }
        #myTab .nav-link.active {
            color:#333333
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <ul class="nav nav-tabs bg-secondary pl-2 pt-2" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                    aria-selected="true">Profile</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="api-settings-tab" data-toggle="tab" href="#api-settings" role="tab" aria-controls="api-settings"
                    aria-selected="false">API Settings</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="api-tab" data-toggle="tab" href="#api" role="tab" aria-controls="api"
                    aria-selected="false">API Documentations</a>
            </li>
        </ul>
        <div class="tab-content bg-white mt-2 p-2" id="myTabContent">
            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row justify-content-center ">
                    <div class="col-md-8">
                        <h2>Profile Settings</h2>
                        <hr>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" name="name" placeholder="Name" value="{{$user->name}}" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="text" name="email" placeholder="Email" value="{{$user->email}}" disabled class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">New Password</label>
                                <input type="text" name="name" placeholder="New Password" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">Confirm Password</label>
                                <input type="text" name="name" placeholder="Confirm Passwod" class="form-control">
                            </div>
                            <button class="btn btn-primary float-right">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="api-settings" role="tabpanel" aria-labelledby="api-settings-tab">.
                <div class="row justify-content-center ">
                    <div class="col-md-8">
                        <h2>API Settings</h2>
                        <hr>
                        <form action="{{route('configurations.rest_api_keys')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="">API PUBLIC KEY</label>
                                <input disabled type="text" name="api_public_key" placeholder="API PUBLIC KEY" value="{{$user->api_public_key}}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">API Private KEY</label>
                                <input disabled type="text" name="api_secret_key" placeholder="API PUBLIC KEY" value="{{$user->api_secret_key}}" class="form-control">
                            </div>
                            <button class="btn btn-primary float-right">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="api" role="tabpanel" aria-labelledby="api-tab">...</div>
        </div>
    </div>
@endsection
