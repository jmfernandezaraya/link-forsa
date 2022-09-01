@extends('admin.layouts.app')

@section('title')
    {{__('Admin/backend.add_coupon')}}
@endsection

@section('content')
    <div class="page-header">
        <div class="card">
            <div class="card-body">
                <div style="text-align: center;">
                    <h1 class="card-title">{{__('Admin/backend.add_coupon')}}</h1>
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
                    <ul class="lang text-right current_page_itemm">
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
                <form id="formaction" class="forms-sample" method="post" action="{{route('superadmin.coupon.store')}}">
                    {{csrf_field()}}

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name">{{__('Admin/backend.name')}}</label>
                            <div class="english">
                                <input name="name" type="text" class="form-control" placeholder="{{__('Admin/backend.name')}}">
                            </div>
                            <div class="arabic">
                                <input name="name_ar" type="text" class="form-control" placeholder="{{__('Admin/backend.name')}}">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="code">{{__('Admin/backend.code')}}</label>
                            <input name="code" type="text" class="form-control" placeholder="{{__('Admin/backend.code')}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="discount">{{__('Admin/backend.discount')}}</label>
                            <input name="discount" type="text" class="form-control" placeholder="{{__('Admin/backend.discount')}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="type">{{__('Admin/backend.type')}}</label>
                            <select class="form-control" name="type">
                                <option value="percent" selected>{{__('Admin/backend.percentage')}}</option>
                                <option value="fixed">{{__('Admin/backend.fixed_amount')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="start_date">{{__('Admin/backend.start_date')}}</label>
                            <input name="start_date" type="date" class="form-control" placeholder="{{__('Admin/backend.start_date')}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="end_date">{{__('Admin/backend.end_date')}}</label>
                            <input name="end_date" type="date" class="form-control" placeholder="{{__('Admin/backend.end_date')}}">
                        </div>
                    </div>

                    <a class="btn btn-light" href="{{route('superadmin.coupon.index')}}">{{__('Admin/backend.cancel')}}</a>
                    <button type="submit" class="btn btn-primary">{{__('Admin/backend.submit')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection