@extends('admin.layouts.app')

@section('title')
    {{__('Admin/backend.dashboard')}}
@endsection

@section('content')
    <div class="row mt-2 mb-5">
        <div class="col-md-12">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-home"></i>
                </span> {{__('Admin/backend.dashboard')}}
            </h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            {{__('Admin/backend.customers')}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h2 class="font-weight-normal mb-3">{{__('Admin/backend.total_customers')}} <i class="mdi mdi-chart-line mdi-24px float-right"></i></h2>
                    <h2 class="mb-5">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
                    <h2 class="font-weight-normal mb-3">{{__('Admin/backend.visa_customers')}} <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h2>
                    <h2 class="mb-5">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
                    <h2 class="font-weight-normal mb-3">{{__('Admin/backend.total_customers')}} <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h2>
                    <h2 class="mb-5">0</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            {{__('Admin/backend.course_customers')}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h2 class="font-weight-normal mb-3">{{__('Admin/backend.under_the_procedure')}} <i class="mdi mdi-chart-line mdi-24px float-right"></i></h2>
                    <h2 class="mb-5">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
                    <h2 class="font-weight-normal mb-3">{{__('Admin/backend.studying')}} <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h2>
                    <h2 class="mb-5">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
                    <h2 class="font-weight-normal mb-3">{{__('Admin/backend.completed')}} <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h2>
                    <h2 class="mb-5">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
                    <h2 class="font-weight-normal mb-3">{{__('Admin/backend.cancelled')}} <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h2>
                    <h2 class="mb-5">0</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            {{__('Admin/backend.visa_customers')}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h2 class="font-weight-normal mb-3">{{__('Admin/backend.under_the_procedure')}} <i class="mdi mdi-chart-line mdi-24px float-right"></i></h2>
                    <h2 class="mb-5">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
                    <h2 class="font-weight-normal mb-3">{{__('Admin/backend.completed')}} <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h2>
                    <h2 class="mb-5">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
                    <h2 class="font-weight-normal mb-3">{{__('Admin/backend.cancelled')}} <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h2>
                    <h2 class="mb-5">0</h2>
                </div>
            </div>
        </div>
    </div>
@endsection