<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    @if (auth('superadmin')->user()->image)
                        <img src="{{ auth('superadmin')->user()->image }}" alt="profile">
                    @else
                        <img src="{{ asset('assets/images/user.png') }}" alt="profile">
                    @endif
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ app()->getLocale() == 'en' ? auth('superadmin')->user()->first_name_en . ' ' . auth('superadmin')->user()->lsst_name_en : auth('superadmin')->user()->first_name_ar . ' ' . auth('superadmin')->user()->last_name_ar }}</span>
                    <span class="text-secondary text-small mb-1">{{ auth('superadmin')->user()->email }}</span>
                    <span class="text-secondary text-small">{{ __('Admin/dashboard.' . auth('superadmin')->user()->user_type) }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('superadmin/dashboard')}}">
                <span class="menu-title">{{__('Admin/dashboard.dashboard')}}</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        @if (can_manage_blog() || can_add_blog() || can_edit_blog() || can_delete_blog())
            <li class="nav-item">
                <a class="nav-link" href="{{route('superadmin.blog.index')}}">
                    <span class="menu-title">{{__('Admin/backend.manage_blogs')}}</span>
                    <i class="mdi mdi-crosshairs-gps menu-icon"></i>
                </a>
            </li>
        @endif

        @if (can_manage_school() || can_add_school() || can_edit_school() || can_delete_school())
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#school" aria-expanded="false" aria-controls="school">
                    <span class="menu-title">{{__('Admin/dashboard.school')}}</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-crosshairs-gps menu-icon"></i>
                </a>
                <div class="collapse" id="school">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('superadmin.school.index')}}">{{__('Admin/dashboard.view')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('superadmin.school.country_city')}}">{{__('Admin/backend.countries_cities')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('superadmin.school.name')}}">{{__('Admin/backend.names')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('superadmin.school.nationality')}}">{{__('Admin/backend.nationalities')}}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @if (can_manage_course() || can_view_course() || can_add_course() || can_edit_course() || can_display_course() || can_delete_course())
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#course" aria-expanded="false" aria-controls="course">
                    <span class="menu-title">{{__('Admin/dashboard.courses')}}</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-account-school-outline menu-icon"></i>
                </a>
                <div class="collapse" id="course">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('superadmin.course.index') }}">{{__('Admin/dashboard.view')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('superadmin.course.deleted') }}">{{__('Admin/dashboard.deleted')}}</a>
                        </li>
                        @if (can_manage_course() || can_add_course() || can_edit_course() || can_delete_course())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('superadmin.course.language') }}">{{__('Admin/dashboard.language')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('superadmin.course.study_mode') }}">{{__('Admin/dashboard.study_mode')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('superadmin.course.program_type') }}">{{__('Admin/dashboard.program_type')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('superadmin.course.study_time') }}">{{__('Admin/dashboard.study_time')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('superadmin.course.classes_day') }}">{{__('Admin/dashboard.classes_day')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('superadmin.course.start_date') }}">{{__('Admin/dashboard.start_date')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('superadmin.course.age') }}">{{__('Admin/dashboard.program_age')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('superadmin.course.under_age') }}">{{__('Admin/dashboard.program_under_age')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('superadmin.course.accommodation_age') }}">{{__('Admin/dashboard.accommodation_age')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('superadmin.course.accommodation_under_age') }}">{{__('Admin/dashboard.accommodation_under_age')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('superadmin.course.custodian_under_age') }}">{{__('Admin/dashboard.custodian_under_age')}}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif

        @if (can_manage_course_application() || can_edit_course_application() || can_chanage_status_course_application() || can_payment_refund_course_application() || can_contact_student_course_application() || can_contact_school_course_application())
            <li class="nav-item">
                <a class="nav-link" href="{{route('superadmin.course_application.index')}}">
                    <span class="menu-title">{{__('Admin/backend.course_application')}}</span>
                    <i class="mdi mdi-crosshairs-gps menu-icon"></i>
                </a>
            </li>
        @endif

        @if (can_manage_review() || can_apply_review() || can_edit_review() || can_approve_review() || can_delete_review())
            <li class="nav-item">
                <a class="nav-link" href="{{route('superadmin.review.index')}}">
                    <span class="menu-title">{{__('Admin/backend.rating_review')}}</span>
                    <i class="mdi mdi-crosshairs-gps menu-icon"></i>
                </a>
            </li>
        @endif

        @if (can_manage_payment() || can_edit_payment() || can_delete_payment())
            <li class="nav-item">
                <a class="nav-link" href="{{route('superadmin.payment_received.index')}}">
                    <span class="menu-title">{{__('Admin/backend.payment_received')}}</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-crosshairs-gps menu-icon"></i>
                </a>
            </li>
        @endif

        @if (can_manage_user() || can_add_user() || can_edit_user() || can_delete_user() || can_permission_user())
           <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#users" aria-expanded="false" aria-controls="users">
                    <span class="menu-title">{{__('Admin/backend.users')}}</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-crosshairs-gps menu-icon"></i>
                </a>
                <div class="collapse" id="users">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('superadmin.user.customer.index')}}">{{__('Admin/dashboard.customers')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('superadmin.user.affiliate.index')}}">{{__('Admin/dashboard.affiliate_users')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('superadmin.user.school_admin.index')}}">{{__('Admin/dashboard.school_admin')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('superadmin.user.super_admin.index')}}">{{__('Admin/dashboard.super_admin')}}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @if (can_manage_enquiry() || can_add_enquiry() || can_edit_enquiry() || can_delete_enquiry())
            <li class="nav-item">
                <a class="nav-link" href="{{url('superadmin/enquiry')}}">
                    <span class="menu-title">{{__('Admin/backend.manage_enquiries')}}</span>
                    <i class="mdi mdi-crosshairs-gps menu-icon"></i>
                </a>
            </li>
        @endif

        @if (can_manage_form_builder() || can_add_form_builder() || can_edit_form_builder() || can_delete_form_builder())
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#visas" aria-expanded="false" aria-controls="visas">
                    <span class="menu-title">{{__('Admin/backend.manage_formbuilder')}}</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-form-select menu-icon"></i>
                </a>
                <div class="collapse" id="visas">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('superadmin/visa')}}">{{ __('Admin/dashboard.view')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('superadmin/visa/create')}}">{{ __('Admin/dashboard.formbuilder')}}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
        
        @if (can_manage_visa_application() || can_add_visa_application() || can_edit_visa_application() || can_delete_visa_application())
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#visa_application" aria-expanded="false" aria-controls="visa_application">
                    <span class="menu-title">{{__('Admin/backend.manage_visa')}}</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-card-bulleted menu-icon"></i>
                </a>
                <div class="collapse" id="visa_application">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('superadmin/visa_application/')}}">{{__('Admin/dashboard.view')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('superadmin.view_visa_forms')}}">{{__('Admin/backend.view_visa_forms')}}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @if (can_manage_currency() || can_add_currency() || can_edit_currency() || can_delete_currency())
            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.currency.index') }}">
                    <span class="menu-title">{{__('Admin/backend.currency')}}</span>
                    <i class="mdi mdi-card-bulleted menu-icon"></i>
                </a>
            </li>
        @endif

        @if (can_manage_payment_method() || can_add_payment_method() || can_edit_payment_method() || can_delete_payment_method())
            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.payment_method.index') }}">
                    <span class="menu-title">{{__('Admin/backend.payment_method')}}</span>
                    <i class="mdi mdi-card-bulleted menu-icon"></i>
                </a>
            </li>
        @endif

        @if (can_manage_coupon() || can_add_coupon() || can_edit_coupon() || can_delete_coupon())
            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.coupon.index') }}">
                    <span class="menu-title">{{__('Admin/backend.coupon')}}</span>
                    <i class="mdi mdi-card-bulleted menu-icon"></i>
                </a>
            </li>
        @endif

        @if (can_manage_email_template() || can_add_email_template() || can_edit_email_template() || can_delete_email_template())
            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.email_template.index') }}">
                    <span class="menu-title">{{__('Admin/dashboard.email_template')}}</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-card-bulleted menu-icon"></i>
                </a>
            </li>
        @endif

        @if (can_set_site() || can_set_home_page() || can_set_header_footer() || can_set_front_page())
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#settings" aria-expanded="false" aria-controls="settings">
                    <span class="menu-title">{{__('Admin/dashboard.settings')}}</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-settings menu-icon"></i>
                </a>
                <div class="collapse" id="settings">
                    <ul class="nav flex-column sub-menu">
                        @if (can_set_site())
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('superadmin.setting.site')}}">{{__('Admin/dashboard.site')}}</a>
                            </li>
                        @endif
                        @if (can_set_home_page())
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('superadmin.setting.home_page')}}">{{__('Admin/dashboard.home_page')}}</a>
                            </li>
                        @endif
                        @if (can_set_header_footer())
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('superadmin.setting.header_footer')}}">{{__('Admin/dashboard.header_footer')}}</a>
                            </li>
                        @endif
                        @if (can_set_front_page())
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('superadmin.setting.front_page.index')}}">{{__('Admin/dashboard.front_pages')}}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif
    </ul>
</nav>