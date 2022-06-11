@extends('superadmin.layouts.app')

@section('title')
    {{__('SuperAdmin/backend.edit_front_page')}}
@endsection

@section('content')
    <div class="page-header">
        <div class="card">
            <div class="card-body">
                <div style="text-align: center;">
                    <h1 class="card-title">{{__('SuperAdmin/backend.edit_front_page')}}</h1>
                    <change>
                        <div class="english">
                            {{__('SuperAdmin/backend.in_english')}}
                        </div>
                        <div class="arabic">
                            {{__('SuperAdmin/backend.in_arabic')}}
                        </div>
                    </change>
                </div>

                <div id="menu">
                    <ul class="lang text-right current_page_itemm">
                        <li class="{{app()->getLocale() == 'en' ? 'current_page_item selected' : ''}}">
                            <a onclick="changeLanguage('english', 'arabic')"><img class="pr-2" src="{{asset('public/frontend/assets/img/eng.png')}}" alt="logo">{{__('SuperAdmin/backend.english')}}</a>
                        </li>
                        <li class="{{app()->getLocale() == 'ar' ? 'current_page_item selected' : ''}}">
                            <a onclick="changeLanguage('arabic', 'english')"><img class="pr-2" src="{{asset('public/frontend/assets/img/ar.png')}}" alt="logo">{{__('SuperAdmin/backend.arabic')}}</a>
                        </li>
                    </ul>
                </div>
                
                @include('superadmin.include.alert')
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <form id="frontPageForm" class="forms-sample" method="post" action="{{route('superadmin.setting.front_page.update', $front_page->id)}}">
                    {{csrf_field()}}
                    @method('PUT')

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="title">{{__('SuperAdmin/backend.title')}}</label>
                            <div class="english">
                                <input onchange="changeFrontPageTitle()" value="{{$front_page->title}}" name="title" type="text" class="form-control" placeholder="{{__('SuperAdmin/backend.title')}}">
                            </div>
                            <div class="arabic">
                                <input value="{{$front_page->title_ar}}" name="title_ar" type="text" class="form-control" placeholder="{{__('SuperAdmin/backend.title')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="slug">{{__('SuperAdmin/backend.slug')}}</label>
                            <input value="{{$front_page->slug}}" name="slug" type="text" class="form-control" placeholder="{{__('SuperAdmin/backend.slug')}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="content">{{__('SuperAdmin/backend.content')}}</label>
                            <div class="english">
                                <textarea id="content" name="content" class="form-control ckeditor-input" placeholder="{{__('SuperAdmin/backend.content')}}">{!! $front_page->content !!}</textarea>
                            </div>
                            <div class="arabic">
                                <textarea id="content_ar" name="content_ar" class="form-control ckeditor-input" placeholder="{{__('SuperAdmin/backend.content')}}">{!! $front_page->content_ar !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="route">{{__('SuperAdmin/backend.route')}}</label>
                            <input name="route" type="checkbox" onchange="changeFrontpageRoute()" {{$front_page->route ? 'checked' : ''}} />
                        </div>
                    </div>
                    <div class="row display" style="display: {{$front_page->route ? 'none' : 'block'}}">
                        <div class="form-group col-md-12">
                            <label for="display">{{__('SuperAdmin/backend.display')}}</label>
                            <input name="display" type="checkbox" {{$front_page->display ? 'checked' : ''}} />
                        </div>
                    </div>

                    <a class="btn btn-light" href="{{url()->previous()}}">{{__('SuperAdmin/backend.cancel')}}</a>
                    <button type="button" onclick="submitForm($(this).parents().find('#frontPageForm'))" class="btn btn-primary">{{__('SuperAdmin/backend.submit')}}</button>
                </div>
            </div>
        </form>
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