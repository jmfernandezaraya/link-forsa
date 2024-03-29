@extends('admin.layouts.app')

@section('title')
    {{__('Admin/backend.edit_blog')}}
@endsection

@section('content')
    <div class="page-header">
        <div class="card">
            <div class="card-body">
                <div style="text-align: center;">
                    <h1 class="card-title">{{__('Admin/backend.edit_blog')}}</h1>
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
                <form id="blogForm" class="forms-sample" method="post" action="{{route('superadmin.blog.update', $blog->id)}}">
                    {{csrf_field()}}
                    @method('PUT') 
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="title">{{__('Admin/backend.title')}}</label>
                            <div class="english">
                                <input value="{{$blog->title_en}}" name="title_en" type="text" class="form-control" placeholder="{{__('Admin/backend.title')}}">
                            </div>
                            <div class="arabic">
                                <input value="{{$blog->title_ar}}" name="title_ar" type="text" class="form-control" placeholder="{{__('Admin/backend.title')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="description">{{__('Admin/backend.description')}}</label>
                            <div class="english">
                                <textarea id="description_en" name="description_en" class="form-control ckeditor-input" placeholder="{{__('Admin/backend.description')}}">{!! $blog->description_en !!}</textarea>
                            </div>
                            <div class="arabic">
                                <textarea id="description_ar" name="description_ar" class="form-control ckeditor-input" placeholder="{{__('Admin/backend.description')}}">{!! $blog->description_ar !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="image">{{__('Admin/backend.image')}}</label>
                            <input name="image" type="file" class="form-control" id="image" accept="image/*">
                            @if (!is_null($blog->image))
                                <img height="200px" id="image_id" width="200px" src="{{ $blog->image }}" class="img-fluid img-thumbnail" alt="Blog Image" data-src="{{ $blog->image }}">
                            @endif
                            @if ($errors->has('image'))
                                <div class="alert alert-danger">{{$errors->first('image')}}</div>
                            @endif
                        </div>
                    </div>

                    <a class="btn btn-light" href="{{url()->previous()}}">{{__('Admin/backend.cancel')}}</a>
                    <button type="button" onclick="submitForm($(this).parents().find('#blogForm'))" class="btn btn-primary">{{__('Admin/backend.submit')}}</button>
                </div>
            </div>
        </form>
    </div>

    @section('js')
        <script>
            var uploadFileOption = "{{route('superadmin.blog.upload', ['_token' => csrf_token() ])}}";
            function previewFile(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
            
                    reader.onload = function (e) {
                        $('#previewImg').attr('src', e.target.result);
                    }
            
                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                }
            }
        </script>
    @endsection
@endsection