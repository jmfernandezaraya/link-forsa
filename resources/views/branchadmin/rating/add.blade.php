@extends('branchadmin.layouts.app')

@section('css')
@endsection

@section('content')
    <div class="col-12 grid-margin stretch-card">
        <form id ="formaction" class="forms-sample" method="post" action = "{{route('blogs.store')}}">
            {{csrf_field()}}
            <div class="card">
                <div class="card-body">
                    <center>
                        <h4 class="card-title">@lang('SuperAdmin/backend.add_blog')</h4>
                        <change>{{__('SuperAdmin/backend.in_english')}}</change>
                    </center>
                    @include('branchadmin.include.alert')
                    <div id="menu">
                        <ul class="lang text-right current_page_itemm">
                            <li class="current_page_item selected">
                                <a class="" href="#" onclick="changeLanguage('english', 'arabic')">
                                    <img class="pr-2" src="{{asset('public/frontend/assets/img/eng.png')}}" alt="logo">{{__('SuperAdmin/backend.english')}}
                                </a>
                            </li>
                            <li>
                                <a href="#" onclick="changeLanguage('arabic', 'english')">
                                    <img class="pr-2" src="{{asset('public/frontend/assets/img/ar.png')}}" alt="logo">{{__('SuperAdmin/backend.arabic')}}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div id="show_form"></div>
                    @csrf
                    <div id="form1">
                        <div class="form-group">
                            <label for="exampleInputName1">{{__('SuperAdmin/backend.blog_title')}}</label>
                            <input value="{{old('title_en')}}" name="title_en" type="text" class="form-control" id="exampleInputName1" placeholder="{{__('SuperAdmin/backend.blog_title')}}" value="{{old('blog_title_en')}}">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail3">{{__('SuperAdmin/backend.blog_description')}}</label>
                            <textarea value="{{old('description_en')}}" name="description_en" class="form-control" id="textarea_en" placeholder="{{__('SuperAdmin/backend.blog_description')}}"></textarea>
                        </div>

                        <img src="//desk87.com/assets/images/preview-not-available.jpg" id="previewImg" alt="Uploaded Image Preview Holder" width="550px" height="250px" style="border-radius:3px;border:5px;"/>

                        <div class="form-group">
                            <label>{{__('SuperAdmin/backend.blog_image')}}</label>
                            <input onchange="previewFile(this)" type="file" class="form-control" name="image">
                        </div>

                        <a class="btn btn-light" href="{{url()->previous()}}">{{__('SuperAdmin/backend.cancel')}}</a>
                    </div>

                    <div id="form2" class="forms-sample">
                        <div class="form-group">
                            <label for="exampleInputName1">{{__('SuperAdmin/backend.blog_title')}}</label>
                            <input value="{{old('title_ar')}}" name="title_ar" type="text" class="form-control" id="exampleInputName1" placeholder="{{__('SuperAdmin/backend.blog_title')}}" value="{{old('first_name')}}">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail3">{{__('SuperAdmin/backend.blog_description')}}</label>
                            <textarea value="{{old('description_ar')}}" name="description_ar" class="form-control" id="textarea_ar" placeholder="Last Name"></textarea>
                        </div>

                        <a class="btn btn-light" href="{{url()->previous()}}">{{__('SuperAdmin/backend.cancel')}}</a>
                        <button type="button" onclick="submitCommonForBlogForm($(this).parents().find('#formaction').attr('action'))" class="btn btn-primary">@lang('SuperAdmin/backend.submit')
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @section('js')
        <script>
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
        <script>
            $(document).ready(function () {
                $('textarea.form-control').summernote({
                        'height': 250
                    }
                );

                $('#menu ul li a').click(function (ev) {
                    $('#menu ul li').removeClass('selected');
                    $(ev.currentTarget).parent('li').addClass('selected');
                });
            });
            var addschooladminurl = "{{route('branch_admin.store')}}";
            var in_arabic = "{{__('SuperAdmin/backend.in_arabic')}}";
            var in_english = "{{__('SuperAdmin/backend.in_english')}}";
        </script>
    @endsection
@endsection
