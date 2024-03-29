@extends('admin.layouts.app')

@section('title')
    {{__('Admin/backend.add_customer')}}
@endsection

@section('content')
    <div class="page-header">
        <div class="card">
            <div class="card-body">
                <div style="text-align: center;">
                    <h1 class="card-title">{{__('Admin/backend.add_customer')}}</h1>
                    <change>
                        <div class="english">
                            {{__('Admin/backend.in_english')}}
                        </div>
                        <div class="arabic">
                            {{__('Admin/backend.in_arabic')}}
                        </div>
                    </change>
                </div>
                
                <div id="menu">
                    <ul class="lang text-right">
                        <li class="{{app()->getLocale() == 'en' ? 'current_page_item selected' : ''}}">
                            <a onclick="changeLanguage('english', 'arabic')"><img class="pr-2" src="{{asset('public/frontend/assets/img/eng.png')}}" alt="logo">{{__('Admin/backend.english')}}</a>
                        </li>
                        <li class="{{app()->getLocale() == 'ar' ? 'current_page_item selected' : ''}}">
                            <a onclick="changeLanguage('arabic', 'english')"><img class="pr-2" src="{{asset('public/frontend/assets/img/ar.png')}}" alt="logo">{{__('Admin/backend.arabic')}}</a>
                        </li>
                    </ul>
                </div>

                @include('common.include.alert')
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" method="POST" action="{{ route('superadmin.user.customer.store') }}" id="customerForm">
                    {{csrf_field()}}
                    
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>{{__('Admin/backend.first_name')}}:</label>
                            <div class="english">
                                <input class="form-control" type="text" name="first_name_en" placeholder="{{__('Admin/backend.first_name')}}">
                            </div>
                            <div class="arabic">
                                <input class="form-control" type="text" name="first_name_ar" placeholder="{{__('Admin/backend.first_name')}}">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{__('Admin/backend.last_name')}}:</label>
                            <div class="english">
                                <input class="form-control" type="text" name="last_name_en" placeholder="{{__('Admin/backend.last_name')}}">
                            </div>
                            <div class="arabic">
                                <input class="form-control" type="text" name="last_name_ar" placeholder="{{__('Admin/backend.last_name')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>{{__('Admin/backend.email')}}:</label>
                            <input class="form-control" type="email" name="email" placeholder="{{__('Admin/backend.email')}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{__('Admin/backend.telephone')}}:</label>
                            <input class="form-control" type="text" name="telephone" placeholder="{{__('Admin/backend.telephone')}}">
                        </div>
                    </div>

                    <button class="btn btn-primary pull-right" type="submit">{{__('Admin/backend.submit')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection