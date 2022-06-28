@extends('superadmin.layouts.app')

@section('title')
    {{__('SuperAdmin/backend.edit_course')}}
@endsection

@section('content')
    <div class="page-header">
        <div class="card">
            <div class="card-body">
                <div style="text-align: center;">
                    <h1 class="card-title">{{__('SuperAdmin/backend.edit_course')}}</h1>
                </div>

                @include('superadmin.include.alert')
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="course-details border-bottom">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>{{__('SuperAdmin/backend.program_information')}}</h3>
                            <div id="program_information">{!! get_language() == 'en' ? $course_update->program_information : $course_update->program_information_ar !!}</div>
                        </div>
                    </div>
                    <table class="table table-bordered table-no-drawable">
                        <tbody>
                            <tr>
                                <td>{{__('SuperAdmin/backend.level_required')}}</td>
                                <td id="level_required">{{$course_update->program_level}}</td>
                            </tr>
                            <tr>
                                <td>{{__('SuperAdmin/backend.lessons_per_week')}}</td>
                                <td id="lessons_per_week">{{$course_update->lessons_per_week}}</td>
                            </tr>
                            <tr>
                                <td>{{__('SuperAdmin/backend.hours_per_week')}}</td>
                                <td id="hours_per_week">{{$course_update->hours_per_week}}</td>
                            </tr>
                            <tr>
                                @php $course_study_times = \App\Models\SuperAdmin\Choose_Study_Time::whereIn('unique_id', is_null($course_update->study_time) ? [] : $course_update->study_time)->pluck('name')->toArray(); @endphp
                                <td>{{__('SuperAdmin/backend.study_time')}}</td>
                                <td id="study_time">{{implode(", ", $course_study_times)}}</td>
                            </tr>
                            <tr>
                                @php $course_classes_days = \App\Models\SuperAdmin\Choose_Classes_Day::whereIn('unique_id', is_null($course_update->classes_day) ? [] : $course_update->classes_day)->pluck('name')->toArray(); @endphp
                                <td>{{__('SuperAdmin/backend.classes_days')}}</td>
                                <td id="classes_day">{{implode(", ", $course_classes_days)}}</td>
                            </tr>
                            <tr>
                                @php $course_start_days = \App\Models\SuperAdmin\Choose_Start_Day::whereIn('unique_id', is_null($course_update->start_date) ? []: $course_update->start_date)->pluck('name')->toArray(); @endphp
                                <td>{{__('SuperAdmin/backend.start_dates')}}</td>
                                <td id="start_date">{{implode(", ", $course_start_days)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <form method="POST" action="{{route('superadmin.course_application.course.update')}}">
                    @csrf
                    
                    <input type="hidden" name="id" value="{{ $course_application->id }}">

                    <div class="study">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <input type="hidden" name="school_id" value="{{ $school->id }}">
                                <label for="study_mode">{{__('SuperAdmin/backend.study_mode')}}:</label>
                                <select class="form-control" id="study_mode" name="study_mode" required>
                                    <option value="" selected>{{__('SuperAdmin/backend.select_mode')}}</option>
                                    @foreach ($study_modes as $study_mode)
                                        <option value="{{$study_mode->unique_id}}">{{$study_mode->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="under_age">{{__('SuperAdmin/backend.your_age')}}:</label>
                                <select name="age_selected" class="form-control" id="under_age" required>
                                    <option value="">{{__('SuperAdmin/backend.select_age')}}</option>
                                    @foreach ($ages as $age)
                                        <option value="{{$age->unique_id}}">{{$age->age}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="program_fees" class="mt-3">
                        <h3 class="section-title">{{__('SuperAdmin/backend.program_fees')}}</h3>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="program_name">{{__('SuperAdmin/backend.program_name')}}:</label>
                                <input hidden name="program_unique_id" id="program_unique_id">

                                <select class="form-control" id="get_program_name" onchange="set_program_unique_id($(this).children('option:selected').data('id')); calculateCourse('select_program');" name="program_id" required>
                                    <option value="" selected>{{__('SuperAdmin/backend.select_option')}}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="program_start_date">{{__('SuperAdmin/backend.program_start_date')}}:</label>
                                <input class="form-control datepicker" id="datepick" type="text" name="date_selected" autocomplete="off" onchange="calculateCourse('date_selected')" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="program_duration">{{__('SuperAdmin/backend.program_duration')}}:</label>
                                <select class="form-control" id="program_duration" name="program_duration" onchange="calculateCourse('duration'); discountPrice($(this).val(), '{{csrf_token()}}');" required>
                                    <option value="" selected>{{__('SuperAdmin/backend.select_option')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row" id="courier_fee" style="display: none">
                            <div class="form-group col-md-12">
                                <div class="form-check">
                                    <input name="courier_fee" type="checkbox" class="form-check-input" id="checked_courier_fee" onchange="calculateCourse('duration', $('#program_duration').val())">
                                    <label class="form-check-label mb-2" for="expressMailingCheck">
                                        {{__('SuperAdmin/backend.express_mailing')}}<i class="fa fa-question-circle pl-2" data-toggle="modal" data-target="#expressMailingModal" aria-hidden="true"></i>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="expressMailingModal" tabindex="-1" role="dialog" aria-labelledby="expressMailingModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="expressMailingModalLabel">{{__('SuperAdmin/backend.express_mailing')}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>

                                    <div class="modal-body">{{__('SuperAdmin/backend.express_mailing_description')}}</div>
                                </div>
                            </div>
                        </div>

                        <table class="table table-bordered table-no-drawable" id="program_fees_table">
                            <thead>
                                <tr>
                                    <th>{{__('SuperAdmin/backend.details')}}</th>
                                    <th>{{__('SuperAdmin/backend.amount')}} / <span class="cost_currency"></span></th>
                                    <th>{{__('SuperAdmin/backend.amount')}} / <span class="converted_currency"></span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="program_cost">
                                    <td>{{__('SuperAdmin/backend.program_cost')}}</td>
                                    <td class="cost_value">0</td>
                                    <td class="converted_value">0</td>
                                </tr>

                                <tr id="registration_fee">
                                    <td>{{__('SuperAdmin/backend.registration_fee')}}</td>
                                    <td class="cost_value">0</td>
                                    <td class="converted_value">0</td>
                                </tr>

                                <tr id="text_book_fee">
                                    <td>{{__('SuperAdmin/backend.text_book_fee')}}</td>
                                    <td class="cost_value">0</td>
                                    <td class="converted_value">0</td>
                                </tr>

                                <tr id="summer_fees">
                                    <td>{{__('SuperAdmin/backend.summer_fees')}}</td>
                                    <td class="cost_value">0</td>
                                    <td class="converted_value">0</td>
                                </tr>

                                <tr id="peak_time_fees">
                                    <td>{{__('SuperAdmin/backend.peak_time_fees')}}</td>
                                    <td class="cost_value">0</td>
                                    <td class="converted_value">0</td>
                                </tr>

                                <tr id="under_age_fees">
                                    <td>{{__('SuperAdmin/backend.under_age_fees')}}</td>
                                    <td class="cost_value">0</td>
                                    <td class="converted_value">0</td>
                                </tr>

                                <tr id="express_mail_fee">
                                    <td>{{__('SuperAdmin/backend.express_mail_fee')}}</td>
                                    <td class="cost_value">0</td>
                                    <td class="converted_value">0</td>
                                </tr>

                                <tr id="discount_fee">
                                    <td>{{__('SuperAdmin/backend.discount')}}</td>
                                    <td class="cost_value">0</td>
                                    <td class="converted_value">0</td>
                                </tr>

                                <tr id="program_total">
                                    <td>{{__('SuperAdmin/backend.total')}}</td>
                                    <td class="cost_value">0</td>
                                    <td class="converted_value">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="accommodation_fees">
                        <div class="accommodation-fees">
                            <h3 class="section-title">{{__('SuperAdmin/backend.accommodation_fees')}}</h3>
                            <input id="accommodation_id" name="accommodation_id" type="hidden" />
                            <div class="mt-3">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="input">{{__("Frontend.accommodation_type")}}:</label>
                                        <select name="accom_type" id="accom_type" class="form-control">
                                            <option value="">{{__('SuperAdmin/backend.select_option')}}</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="input">{{__('SuperAdmin/backend.room_type')}}:</label>
                                        <select name="room_type" class="form-control" id="room_type">
                                            <option value="">{{__('SuperAdmin/backend.select_option')}}</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="input">{{__('SuperAdmin/backend.meal_type')}}:</label>
                                        <select name="meal_type" class="form-control" id="meal_type">
                                            <option value="">{{__('SuperAdmin/backend.select_option')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="input">{{__('SuperAdmin/backend.accommodation_duration')}}:</label>
                                        <select name="accommodation_duration" class="form-control" id="accom_duration" onchange="calcuateAccommodation()">
                                            <option value="">{{__('SuperAdmin/backend.select')}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row" id="special_diet" style="display: none">
                                    <div class="form-group col-md-12">
                                        <div class="form-check">
                                            <input name="special_diet" type="checkbox" class="form-check-input" id="special_diet_check" onchange="calcuateAccommodation()">
                                            <label class="form-check-label mb-2" for="expressMailingCheck">
                                                {{__('SuperAdmin/backend.special_diet_fee')}}<i class="fa fa-question-circle pl-2" data-toggle="modal" data-target="#specialDietModal" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Special Diet Modal -->
                                <div class="modal fade" id="specialDietModal" tabindex="-1" role="dialog" aria-labelledby="specialDietModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="specialDietModalLabel">{{__('SuperAdmin/backend.special_diet_fee')}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Custodianship Modal -->
                                <div class="modal fade" id="custodianshipModal" tabindex="-1" role="dialog" aria-labelledby="custodianshipModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="custodianshipModalLabel">{{__('SuperAdmin/backend.custodianship')}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">{{__('SuperAdmin/backend.custodianship_help')}}</div>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-bordered table-no-drawable" id="accommodation_fees_table">
                                    <thead>
                                        <tr>
                                            <th>{{__('SuperAdmin/backend.details')}}</th>
                                            <th>{{__('SuperAdmin/backend.amount')}} / <span class="cost_currency"></span></th>
                                            <th>{{__('SuperAdmin/backend.amount')}} / <span class="converted_currency"></span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="accommodation_fee">
                                            <td>{{__('SuperAdmin/backend.accommodation_fee')}}</td>
                                            <td class="cost_value">0</td>
                                            <td class="converted_value">0</td>
                                        </tr>

                                        <tr id="accommodation_placement_fee">
                                            <td>{{__('SuperAdmin/backend.placement_fee')}}</td>
                                            <td class="cost_value">0</td>
                                            <td class="converted_value">0</td>
                                        </tr>

                                        <tr id="accommodation_special_diet_fee">
                                            <td>{{__('SuperAdmin/backend.special_diet_fee')}}</td>
                                            <td class="cost_value">0</td>
                                            <td class="converted_value">0</td>
                                        </tr>

                                        <tr id="accommodation_deposit_fee">
                                            <td>{{__('SuperAdmin/backend.deposit_fee')}}</td>
                                            <td class="cost_value">0</td>
                                            <td class="converted_value">0</td>
                                        </tr>

                                        <tr id="accommodation_summer_fees">
                                            <td>{{__('SuperAdmin/backend.summer_fees')}}</td>
                                            <td class="cost_value">0</td>
                                            <td class="converted_value">0</td>
                                        </tr>

                                        <tr id="accommodation_peak_fees">
                                            <td>{{__('SuperAdmin/backend.peak_time_fees')}}</td>
                                            <td class="cost_value">0</td>
                                            <td class="converted_value">0</td>
                                        </tr>

                                        <tr id="accommodation_christmas_fees">
                                            <td>{{ucfirst(__('SuperAdmin/backend.christmas_fees'))}}</td>
                                            <td class="cost_value">0</td>
                                            <td class="converted_value">0</td>
                                        </tr>

                                        <tr id="accommodation_under_age_fees">
                                            <td>{{__('SuperAdmin/backend.under_age_fees')}}</td>
                                            <td class="cost_value">0</td>
                                            <td class="converted_value">0</td>
                                        </tr>

                                        <tr id="accommodation_discount_fee">
                                            <td>{{__('SuperAdmin/backend.discount')}}</td>
                                            <td class="cost_value">0</td>
                                            <td class="converted_value">0</td>
                                        </tr>

                                        <tr id="accommodation_total">
                                            <td>{{__('SuperAdmin/backend.total')}}</td>
                                            <td class="cost_value">0</td>
                                            <td class="converted_value">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="other-services" id="other_services">
                        <h3 class="section-title">{{__('SuperAdmin/backend.other_services')}}</h3>
                        <div id="airport_service" class="transport mt-3">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <h5><strong>{{__('SuperAdmin/backend.transport')}}</strong></h5>
                                    <input id="airport_id" name="airport_id" type="hidden" />
                                    <input id="airport_fee_id" name="airport_fee_id" type="hidden" />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="input">{{__('SuperAdmin/backend.service_provider')}}:</label>
                                    <select class="form-control" id="airport_service_provider">
                                        <option value="">{{__('SuperAdmin/backend.select')}}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="input">{{__('SuperAdmin/backend.airport_name')}}:</label>
                                    <select name="airport_name" class="form-control" id="airport_name">
                                        <option value="">{{__('SuperAdmin/backend.select')}}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="input">{{__('SuperAdmin/backend.type_of_service')}}:</label>
                                    <select name="airport_service" class="form-control" id="airport_type_of_service">
                                        <option value="">{{__('SuperAdmin/backend.select')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="medical_service" class="medical_insurance mt-3">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <h5><strong>{{__('SuperAdmin/backend.medical_insurance')}}</strong></h5>
                                    <input id="medical_id" name="medical_id" type="hidden" />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="input">{{__('SuperAdmin/backend.company_name')}}:</label>
                                    <select class="form-control" name="company_name" id="medical_company_name">
                                        <option value="">{{__('SuperAdmin/backend.select')}}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="input">{{__('SuperAdmin/backend.deductible_up_to')}}:</label>
                                    <select class="form-control" name="deductible_up_to" id="medical_deductible_up_to">
                                        <option value="">{{__('SuperAdmin/backend.select')}}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="input">{{__('SuperAdmin/backend.duration')}}:</label>
                                    <select class="form-control" name="duration" id="medical_duration">
                                        <option value="">{{__('SuperAdmin/backend.select')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="custodian_service" class="custodian mt-3" style="display: none">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <h5><strong>{{__('SuperAdmin/backend.custodian_fee')}}</strong></h5>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="custodianship_check" onchange="calculateOtherService()">
                                        <label class="form-check-label mb-2" for="custodianshipCheck">
                                            {{__('SuperAdmin/backend.custodianship_need')}}<i class="fa fa-question-circle pl-2" data-toggle="modal" data-target="#custodianshipModal" aria-hidden="true"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <table class="table table-bordered table-no-drawable" id="other_service_fees_table">
                                    <thead>
                                        <tr>
                                            <th>{{__('SuperAdmin/backend.details')}}</th>
                                            <th>{{__('SuperAdmin/backend.amount')}} / <span class="cost_currency"></span></th>
                                            <th>{{__('SuperAdmin/backend.amount')}} / <span class="converted_currency"></span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="airport_pickup">
                                            <td>{{__('SuperAdmin/backend.airport_pickup')}}
                                                <i class="fa fa-question-circle pl-2" data-toggle="modal" data-target="#AirportPickupModal" aria-hidden="true"></i>
                                            </td>
                                            <td class="cost_value">0</td>
                                            <td class="converted_value">0</td>
                                        </tr>

                                        <tr id="medical_insurance">
                                            <td>{{__('SuperAdmin/backend.medical_insurance')}}
                                                <i class="fa fa-question-circle pl-2" data-toggle="modal" data-target="#MedicalInsuranceModal" aria-hidden="true"></i>
                                            </td>
                                            <td class="cost_value">0</td>
                                            <td class="converted_value">0</td>
                                        </tr>

                                        <tr id="custodian_fee">
                                            <td>{{__('SuperAdmin/backend.custodian_fee')}}
                                                <i class="fa fa-question-circle pl-2" data-toggle="modal" aria-hidden="true"></i>
                                            </td>
                                            <td class="cost_value">0</td>
                                            <td class="converted_value">0</td>
                                        </tr>

                                        <tr id="other_service_total">
                                            <td>{{__('SuperAdmin/backend.total')}}</td>
                                            <td class="cost_value">0</td>
                                            <td class="converted_value">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Airport Pickup Modal -->
                    <div class="modal fade" id="AirportPickupModal" tabindex="-1" role="dialog" aria-labelledby="AirportPickupModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="AirportPickupModalLabel">{{__('SuperAdmin/backend.airport_pickup_note')}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Medical Insurance Modal -->
                    <div class="modal fade" id="MedicalInsuranceModal" tabindex="-1" role="dialog" aria-labelledby="MedicalInsuranceModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="MedicalInsuranceModalLabel">{{__('SuperAdmin/backend.medical_insurance_note')}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body"></div>
                            </div>
                        </div>
                    </div>

                    <div class="total mt-3">
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <table class="table table-bordered table-no-drawable" id="total_table">
                                    <tbody>
                                        <tr>
                                            <td>{{__('SuperAdmin/backend.total_cost')}}</td>
                                            <td><span class="total_cost"></span> <span class="total_cost_currency"></span></td>
                                            <td><span class="total_converted"></span> <span class="total_converted_currency"></span></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <input hidden id="total_fees" name="total_fees">
                                <button type="submit" class="btn btn-primary px-5 py-3 pull-right">{{__('SuperAdmin/backend.register_now')}}</button>
                            </div>
                        </div>
                    </div>

                    <input hidden id="total_fees_to_save_to_db" name="total_fees_to_save_to_db">
                    <input hidden id="other_currency_to_save_to_db" name="other_currency_to_save_to_db">
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var token = "{{csrf_token()}}";

        var accomm_rooms_meals_url = "{{route('frontend.course.rooms_meals')}}";
        var accomm_meals_url = "{{route('frontend.course.meals')}}";
        var accomm_durations_url = "{{route('frontend.course.accomm_durations')}}";
        
        var calculate_url = "{{route('frontend.course.calculate')}}";
        var calculate_accommodation_url = "{{route('frontend.course.calculate.accommodation')}}";
        var calculate_discount_url = "{{route('frontend.course.calculate.discount')}}";
        var reload_calculate_url = "{{route('frontend.course.calculate.reset.program')}}";
        var reset_accommodation_url = "{{route('frontend.course.calculate.reset.accommodation')}}";
        var reset_other_service_url = "{{route('frontend.course.calculate.reset.other_service')}}";
        
        var airport_names_url = "{{route('frontend.course.airport.names')}}";
        var airport_services_url = "{{route('frontend.course.airport.services')}}";
        var airport_fee_url = "{{route('frontend.course.airport.fee')}}";
        var medical_deductibles_url = "{{route('frontend.course.medical.deductibles')}}";
        var medical_durations_url = "{{route('frontend.course.medical.durations')}}";
        var medical_fee_url = "{{route('frontend.course.medical.fee')}}";

        var other_service_fee_url = "{{route('frontend.course.other_service.fee')}}";

        $(document).ready(function () {
            setTimeout(function() {
                checkPreloader();
            }, 1000);
        });

        var fill_course_form = false;
        function checkPreloader() {
            if ($('#preloader').length) {
                setTimeout(function() {
                    checkPreloader();
                }, 1000);
            } else {
                fillCourseForm();
            }
        }

        function fillCourseForm() {
            if (!fill_course_form) {
                @if ((float)$course_application->courier_fee)
                    $('#checked_courier_fee').prop("checked", true);
                @endif
                @if ((float)$course_application->accommodation_special_diet_fee)
                    $('#special_diet_check').prop("checked", true);
                @endif
                @if (isset($course_application->study_mode) && $course_application->study_mode)
                    $('#study_mode').val('{{$course_application->study_mode}}');
                @else
                    fill_course_form = true;
                @endif
                @if (isset($course_application->age_selected) && $course_application->age_selected)
                    $('#under_age').val('');
                    $('#under_age').val('{{$course_application->age_selected}}').trigger('change');
                @else
                    fill_course_form = true;
                @endif
            }
        }

        function callbackCalculateCourse(type) {
            if (!fill_course_form) {
                if (type == 'requested_for_under_age') {
                    @if (isset($course_application->course_id) && $course_application->course_id)
                        $('#get_program_name').val('');
                        $('#get_program_name').val('{{$course_application->course_id}}').trigger('change');
                    @else
                        fill_course_form = true;
                    @endif
                } else if (type == 'select_program') {
                    @if (isset($course_application->start_date) && $course_application->start_date)
                        $('#datepick').val('');
                        $('#datepick').val('{{ $course_application->start_date->format("d-m-Y") }}').trigger('change');
                    @else
                        fill_course_form = true;
                    @endif
                } else if (type == 'date_selected') {
                    @if (isset($course_application->program_duration) && $course_application->program_duration)
                        $('#program_duration').val('');
                        $('#program_duration').val('{{$course_application->program_duration}}').trigger('change');
                    @else
                        fill_course_form = true;
                    @endif
                } else if (type == 'duration') {
                    @if (isset($course_application->accommodation_id) && $course_application->accommodation_id)
                        $('#accom_type').val('');
                        $('#accom_type').val('{{$course_application->accom_type}}').trigger('change');
                    @else
                        fill_course_form = true;
                    @endif
                    calculateOtherService();
                }
            }
        }

        function callbackChangeAccommodation(type) {
            if (!fill_course_form) {
                if (type == 'accom_type') {
                    @if (isset($course_application->room_type))
                        @if ($course_application->room_type)
                            $('#room_type').val('');
                            $('#room_type').val('{{$course_application->room_type}}').trigger('change');
                        @else
                            @if (isset($course_application->airport_provider) && $course_application->airport_provider)
                                $('#airport_service_provider').val('');
                                $('#airport_service_provider').val('{{$course_application->airport_provider}}').trigger('change');
                            @else
                                @if (isset($course_application->company_name) && $course_application->company_name)
                                    $('#medical_company_name').val('');
                                    $('#medical_company_name').val('{{$course_application->company_name}}').trigger('change');
                                @else
                                    fill_course_form = true;
                                @endif
                            @endif
                        @endif
                    @else
                        fill_course_form = true;
                    @endif
                } else if (type == 'room_type') {
                    @if (isset($course_application->meal_type))
                        @if ($course_application->meal_type)
                            $('#meal_type').val('');
                            $('#meal_type').val('{{$course_application->meal_type}}').trigger('change');
                        @else
                            @if (isset($course_application->airport_provider) && $course_application->airport_provider)
                                $('#airport_service_provider').val('');
                                $('#airport_service_provider').val('{{$course_application->airport_provider}}').trigger('change');
                            @else
                                @if (isset($course_application->company_name) && $course_application->company_name)
                                    $('#medical_company_name').val('');
                                    $('#medical_company_name').val('{{$course_application->company_name}}').trigger('change');
                                @else
                                    fill_course_form = true;
                                @endif
                            @endif
                        @endif
                    @endif
                } else if (type == 'meal_type') {
                    @if (isset($course_application->accommodation_duration) && $course_application->accommodation_duration)
                        $('#accom_duration').val('');
                        $('#accom_duration').val('{{$course_application->accommodation_duration}}').trigger('change');
                    @else
                        @if (isset($course_application->airport_provider) && $course_application->airport_provider)
                            $('#airport_service_provider').val('');
                            $('#airport_service_provider').val('{{$course_application->airport_provider}}').trigger('change');
                        @else
                            @if (isset($course_application->company_name) && $course_application->company_name)
                                $('#medical_company_name').val('');
                                $('#medical_company_name').val('{{$course_application->company_name}}').trigger('change');
                            @else
                                fill_course_form = true;
                            @endif
                        @endif
                    @endif
                } else if (type == 'calculate') {
                    @if (isset($course_application->airport_provider) && $course_application->airport_provider)
                        $('#airport_service_provider').val('');
                        $('#airport_service_provider').val('{{$course_application->airport_provider}}').trigger('change');
                    @else
                        @if (isset($course_application->company_name) && $course_application->company_name)
                            $('#medical_company_name').val('');
                            $('#medical_company_name').val('{{$course_application->company_name}}').trigger('change');
                        @else
                            fill_course_form = true;
                        @endif
                    @endif
                }
            }
        }

        function callbackChangeAirport(type) {
            if (!fill_course_form) {
                if (type == 'service_provider') {
                    @if (isset($course_application->airport_name))
                        @if ($course_application->airport_name)
                            $('#airport_name').val('');
                            $('#airport_name').val('{{$course_application->airport_name}}').trigger('change');
                        @else
                            @if (isset($course_application->company_name))
                                $('#medical_company_name').val('');
                                $('#medical_company_name').val('{{$course_application->company_name}}').trigger('change');
                            @else
                                fill_course_form = true;
                            @endif
                        @endif
                    @endif
                } else if (type == 'name') {
                    @if (isset($course_application->airport_service))
                        @if ($course_application->airport_service)
                            $('#airport_type_of_service').val('');
                            $('#airport_type_of_service').val('{{$course_application->airport_service}}').trigger('change');
                        @else
                            @if (isset($course_application->company_name))
                                $('#medical_company_name').val('');
                                $('#medical_company_name').val('{{$course_application->company_name}}').trigger('change');
                            @else
                                fill_course_form = true;
                            @endif
                        @endif
                    @endif
                }
            }
        }

        function callbackChangeMedical() {
            if (!fill_course_form) {
                if (type == 'company_name') {
                    @if (isset($course_application->deductible_up_to))
                        $('#medical_deductible_up_to').val('');
                        $('#medical_deductible_up_to').val('{{$course_application->deductible_up_to}}').trigger('change');
                    @else
                        fill_course_form = true;
                    @endif
                } else if (type == 'deductible_up_to') {
                    @if (isset($course_application->medical_duration))
                        $('#medical_duration').val('');
                        $('#medical_duration').val('{{$course_application->medical_duration}}').trigger('change');
                    @else
                        fill_course_form = true;
                    @endif
                }
            }
        }

        function callbackCalculateOtherService(type) {
            if (!fill_course_form) {
                if (type == 'airport') {
                    @if (isset($course_application->company_name))
                        $('#medical_company_name').val('');
                        $('#medical_company_name').val('{{$course_application->company_name}}').trigger('change');
                    @else
                        fill_course_form = true;
                    @endif
                } else if (type == 'medical') {
                    fill_course_form = true;
                }
            }
        }

        function set_program_unique_id(object) {
            $('#program_unique_id').val(object);
        }
    </script>
@endsection