@extends('admin.layouts.app')

@section('title')
    {{__('Admin/backend.add_front_page')}}
@endsection

@section('content')
    <div class="page-header">
        <div class="card">
            <div class="card-body">
                <div style="text-align: center;">
                    <h1 class="card-title">{{__('Admin/backend.add_front_page')}}</h1>
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
                <form id ="frontPageForm" class="forms-sample" method="post" action="{{route('superadmin.setting.front_page.store')}}">
                    {{csrf_field()}}

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="title">{{__('Admin/backend.title')}}</label>
                            <div class="english">
                                <input onchange="changeFrontPageTitle()" name="title" type="text" class="form-control" placeholder="{{__('Admin/backend.title')}}">
                            </div>
                            <div class="arabic">
                                <input name="title_ar" type="text" class="form-control" placeholder="{{__('Admin/backend.title')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="title">{{__('Admin/backend.slug')}}</label>
                            <input name="slug" type="text" class="form-control" placeholder="{{__('Admin/backend.slug')}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="content">{{__('Admin/backend.content')}}</label>
                            <div class="english">
                                <textarea id="content" name="content" class="form-control ckeditor-input" placeholder="{{__('Admin/backend.content')}}">{!! old('content') !!}</textarea>
                            </div>
                            <div class="arabic">
                                <textarea id="content_ar" name="content_ar" class="form-control ckeditor-input" placeholder="{{__('Admin/backend.content')}}">{!! old('content_ar') !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="route">{{__('Admin/backend.route')}}</label>
                            <input name="route" type="checkbox" onchange="changeFrontpageRoute()" />
                        </div>
                    </div>
                    <div class="row display">
                        <div class="form-group col-md-12">
                            <label for="display">{{__('Admin/backend.display')}}</label>
                            <input name="display" type="checkbox" checked />
                        </div>
                    </div>

                    <a class="btn btn-light" href="{{url()->previous()}}">{{__('Admin/backend.cancel')}}</a>
                    <button type="button" onclick="submitForm($(this).parents().find('#frontPageForm'))" class="btn btn-primary">{{__('Admin/backend.submit')}}</button>
                </form>
            </div>
        </div>
    </div>

    @section('js')
        <script>
            var uploadFileOption = "{{route('superadmin.setting.front_page.upload', ['_token' => csrf_token() ])}}";
            function previewFile(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#previewImg').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                }
            }

            function changeFrontPageTitle() {
                var title = $('[name="title"]').val();
                $('[name="slug"]').val(generateSlug(title));
            }

            function changeFrontpageRoute() {
                if ($('[name="route"]').is(':checked')) {
                    $('.display').hide();
                } else {
                    $('.display').show();
                }
            }
        </script>
    @endsection
@endsection