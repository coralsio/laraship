@extends('layouts.auth')

@section('title','Account Locked')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-4">
            <!-- Unlock Block -->
            <div class="block block-rounded block-themed mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Account Locked</h3>
                    <div class="block-options">
                        <a class="btn-block-option" href="{{url('logout')}}"
                           data-page_action="site_reload"
                           data-action="logout"
                           data-toggle="tooltip" data-placement="left" title="Sign In with another account">
                            <i class="fa fa-sign-in"></i>
                        </a>
                    </div>
                </div>
                <div class="block-content">
                    <div class="p-sm-3 px-lg-4 py-lg-5 text-center">
                        <img class="img-avatar img-avatar96" src="{{user()->picture}}" alt="">
                        <p class="font-w600 my-2">
                            {{user()->email}}
                        </p>


                        <form class="ajax-form" method="post" action="{{url('unlock-session')}}">

                            <div class="form-group py-3">
                                <input type="password" class="form-control form-control-lg form-control-alt"
                                       id="lock-password" name="password" placeholder="Password..">
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-6 col-xl-5">
                                    <button type="submit" class="btn btn-block btn-alt-primary">
                                        <i class="fa fa-fw fa-unlock mr-1"></i> Unlock
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!-- END Unlock Form -->
                    </div>
                </div>
            </div>
            <!-- END Unlock Block -->
        </div>
    </div>

@endsection
