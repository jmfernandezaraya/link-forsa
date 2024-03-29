@extends('admin.layouts.app')

@section('content')

@section('js')
    <script>

        $(document).ready(function () {
            $('#menu ul li a').click(function (ev) {
                $('#menu ul li').removeClass('selected');
                $(ev.currentTarget).parent('li').addClass('selected');
            });
        });
        var addbranchadminurl = "{{route('schooladmin.branch_admin.store')}}";
        var in_arabic = "{{__('Admin/backend.in_arabic')}}";
        var in_english = "{{__('Admin/backend.in_english')}}";
    </script>
@endsection

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <form id="SchoolAdminForm" class="forms-sample" method="post">
            {{csrf_field()}}

            <div id="form1">
                <div class="card-body">
                    <center>
                        <h4 class="card-title">{{__('Admin/backend.add_branch_admin')}}</h4>
                        <change>{{__('Admin/backend.in_english')}}</change>
                    </center>

                    @include('common.include.alert')

                    <div id="menu">
                        <ul class="lang text-right current_page_itemm">
                            <li class="current_page_item selected">
                                <a class="" href="#" onclick="changeLanguage('english', 'arabic')">
                                   <img class="pr-2" src="{{asset('public/frontend/assets/img/eng.png')}}" alt="logo">
                                   {{__('Admin/backend.english')}}
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" onclick="changeLanguage('arabic', 'english')"; fillForm('form1', 'form2')">
                                    <img class="pr-2" src="{{asset('public/frontend/assets/img/ar.png')}}" alt="logo">
                                    {{__('Admin/backend.arabic')}}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for="first_name_en">{{__('Admin/backend.first_name')}}</label>
                        <input name="first_name_en" type="text" class="form-control" id="first_name_en" placeholder="First Name" value="{{old('first_name')}}">
                    </div>

                    <div class="form-group">
                        <label for="last_name_en">{{__('Admin/backend.last_name')}}</label>
                        <input value="{{old('last_name_en')}}" name="last_name_en" type="text" class="form-control" id="last_name_en" placeholder="Last Name">
                    </div>

                    <div class="form-group">
                        <label for="email">{{__('Admin/backend.email_address')}}</label>
                        <input value="{{old('email')}}" name="email" type="email" class="form-control" id="email" placeholder="Email address">
                    </div>

                    <div class="form-group">
                        <label for="password">{{__('Admin/backend.enter_password')}}</label>
                        <input name="password" type="password" class="form-control" id="password" value="{{old('password')}}" placeholder="Set Password">
                    </div>

                    <div class="form-group">
                        <label for="telephone">{{__('Admin/backend.telephone')}}</label>
                        <input value="{{old('telephone')}}" name="telephone" class="form-control" id="telephone" placeholder="School Contact Number" type="number">
                    </div>

                    @livewire('school-admin.school-dependent-dropdown', ['schools' => $schools])

                    <div class="form-group">
                        <div class="form-check">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="can_add_course" type="checkbox" class="form-check-input" id="can_add_course" value='1'>
                            <label for="can_add_course">@lang('Admin/backend.can_add_course')</label>
                        </div>
                        <div class="form-check">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="can_edit_course" type="checkbox" class="form-check-input" id="can_edit_course" value='1'>
                            <label for="can_edit_course">@lang('Admin/backend.can_edit_course')</label>
                        </div>
                        <div class="form-check">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="can_delete_course" type="checkbox" class="form-check-input" id="can_delete_course" value='1'>
                            <label for="can_delete_course">@lang('Admin/backend.can_delete_course')</label>
                        </div>
                    </div>
                    <img src="{{ asset('/assets/images/no-image.jpg') }}" id="previewImg" alt="Uploaded Image Preview Holder" width="550px" height="250px" style="border-radius:3px;border:5px;"/>

                    <div class="form-group">
                        <label>{{__('Admin/backend.profile_image_if_any')}}</label>
                        <input type="file" onchange="previewFile(this)" class="form-control" name="image">
                    </div>

                    <a class="btn btn-light" href="{{url()->previous()}}">{{__('Admin/backend.cancel')}}</a>
                </div>
            </div>
            <div id="show_form"></div>

            <div id="form2">
                <div class="card-body">
                    <center><h4 class="card-title">{{__('Admin/backend.add_branch_admin')}}</h4>
                        <change>{{__('Admin/backend.in_arabic')}}</change>
                    </center>
                    @include('common.include.alert')
                    <div id="menu">
                        <ul class="lang text-right current_page_itemm">
                            <li class="current_page_item selected">
                                <a class="" href="#" onclick="changeLanguage('english', 'arabic')">
                                   <img class="pr-2" src="{{asset('public/frontend/assets/img/eng.png')}}" alt="logo">
                                   {{__('Admin/backend.english')}}
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" onclick="changeLanguage('arabic', 'english')"; fillForm('form1', 'form2')">
                                    <img class="pr-2" src="{{asset('public/frontend/assets/img/ar.png')}}" alt="logo">
                                    {{__('Admin/backend.arabic')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <label for="first_name_ar">{{__('Admin/backend.first_name')}}</label>
                        <input name="first_name_ar" type="text" class="form-control" id="first_name_ar" placeholder="First Name" value="{{old('first_name_ar')}}">
                    </div>

                    <div class="form-group">
                        <label for="last_name_ar">{{__('Admin/backend.last_name')}}</label>
                        <input value="{{old('last_name_ar')}}" name="last_name_ar" type="text" class="form-control" id="last_name_ar" placeholder="Last Name">
                    </div>

                    <button onclick="submitForm($(this).parents().find('#SchoolAdminForm'))" type="button" class="btn btn-gradient-primary mr-2">{{__('Admin/backend.submit')}}</button>
                    <a class="btn btn-light" href="{{url()->previous()}}">{{__('Admin/backend.cancel')}}</a>
                </div>
            </div>
        </form>
    </div>

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
@endsection