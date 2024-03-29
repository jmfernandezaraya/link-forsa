<?php

namespace App\Services;

use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\CourseAccommodation;
use App\Models\CourseAccommodationUnderAge;
use App\Models\CourseAirport;
use App\Models\CourseAirportFee;
use App\Models\CourseMedical;
use App\Models\CourseMedicalFee;
use App\Models\CourseCustodian;
use App\Models\CourseProgram;
use App\Models\CourseProgramTextBookFee;
use App\Models\CourseProgramUnderAgeFee;
use App\Models\School;

use Illuminate\Http\Request;

/**
 * Class CourseCreateService
 * @package App\Services
 */
class CourseCreateService
{
    /**
     * @var
     */
    private $get_error;

    /**
     * @return mixed
     */
    public function getGetError()
    {
        return $this->get_error;
    }

    /**
     * @param Request $request
     * @return bool|\Illuminate\Support\MessageBag
     */
    public function createCourseAndProgram(Request $request)
    {
        extract($request->all());

        $rules = [
            'school_name' => 'required',
            'country_id' => 'required',
            'city_id' => 'required',
            'currency' => 'required',
            'program_level' => 'required',
            'lessons_per_week' => 'required',
            'hours_per_week' => 'required',
            'program_information' => 'required',
            'study_finance' => 'required'
        ];

        /*
         * Validation Rules Starts here
         *
         */
        $validation = \Validator::make($request->all(), $rules, [
            'school_name.required' => 'School Name required',
            'country_id.required' => 'Country required',
            'city_id.required' => 'City required',
            'language.*.required' => 'Language required',
            'study_time.*.required' => 'Study Time required',
            'study_mode.*.required' => 'Study Mode required',
            'start_date.*.required' => 'Start Date required',
            'classes_day.*.required' => 'Classes Day required',
            'program_type.*.required' => 'Program Type required',
            'courier_fee.*.required' => 'Program Courier fee required',
            'program_cost.*.required' => 'Program Cost required',
            'program_start_date.*.required' => 'Program Start Date required',
            'program_end_date.*.required' => 'Program End Date required',
        ]);

        if ($validation->fails()) {
            return $this->get_error = $validation->errors();
        }

        $course_id = (new Controller())->my_unique_id();
        $exist_course = Course::where('unique_id', $course_id)->get();
        while (count($exist_course)) {
            (new Controller())->my_unique_id(1);
            $course_id = (new Controller())->my_unique_id();
            $exist_course = Course::where('unique_id', $course_id)->get();
        }
        $course = [];
        $course['unique_id'] = $course_id;
        $course['language'] = $request->language;
        $course['program_type'] = $request->program_type;
        $course['study_mode'] = $request->study_mode;
        $course['link_fee_enable'] = $request->link_fee_enable;
        $course['school_id'] = 0;
        $language = app()->getLocale();
        $course_school = School::whereHas('name', function($query) use ($request, $language)
            { $language == 'en' ? $query->where('name', $request->school_name) : $query->where('name_ar', $request->school_name); })
            ->where('country_id', $request->country_id)->where('city_id', $request->city_id)->first();
        if ($course_school) {
            $course['school_id'] = $course_school->id;
        }
        $course['country_id'] = $request->country_id;
        $course['city_id'] = $request->city_id;
        $course['branch'] = $request->branch;
        $course['currency'] = $request->currency;
        $course['program_name'] = $request->program_name;
        $course['program_name_ar'] = $request->program_name_ar;
        $course['program_level'] = $request->program_level;
        $course['program_level_ar'] = $request->program_level_ar;
        $course['lessons_per_week'] = $request->lessons_per_week;
        $course['hours_per_week'] = $request->hours_per_week;
        $course['study_time'] = $request->study_time;
        $course['classes_day'] = $request->classes_day;
        $course['start_date'] = $request->start_date;
        $course['program_information'] = $request->program_information;
        $course['program_information_ar'] = $request->program_information_ar;
        $course['study_finance'] = $request->study_finance;
        Course::create($course);

        \Session::put('course_id', '' . $course_id);
        \Session::forget('program_ids');

        if (isset($request->program_increment)) {
            for ($count = 0; $count <= (int)$request->program_increment; $count++) {
                if (isset($request->program_id[$count])) {
                    \Session::push('program_ids', '' . $request->program_id[$count]);
                }
    
                if (isset($request->program_id[$count]) && $request->program_id[$count]) {
                    $course_program = new CourseProgram;
                    $course_program->course_unique_id = $course_id;
                    $course_program->unique_id = $request->program_id[$count];
                    $course_program->link_fee = $request->link_fee[$count];
                    $course_program->tax_percent = $request->tax_percent[$count];
                    $course_program->bank_charge_fee = $request->bank_charge_fee[$count];
                    $course_program->program_registration_fee = $request->program_registration_fee[$count];
                    $course_program->program_duration = $request->program_duration[$count] ?? null;
                    $course_program->program_age_range = $request->age_range[$count] ?? null;
                    $course_program->courier_fee = $request->courier_fee[$count];
                    $course_program->about_courier = $request->about_courier[$count] ?? null;
                    $course_program->about_courier_ar = $request->about_courier_ar[$count] ?? null;
    
                    $course_program->program_cost = $request->program_cost[$count];
                    $course_program->program_duration_start = $request->program_duration_start[$count] ?? null;
                    $course_program->program_duration_end = $request->program_duration_end[$count] ?? null;
                    $course_program->program_start_date = $request->program_start_date[$count] ?? null;
                    $course_program->program_end_date = $request->program_end_date[$count] ?? null;
    
                    $course_program->available_date = $request->available_date[$count] ?? null;
                    $course_program->select_day_week = $request->select_day_week[$count] ?? null;
                    $course_program->available_days = $request->available_days[$count] ?? null;
                    
                    $course_program->deposit = (isset($request->deposit[$count]) ? $request->deposit[$count] : "") . " " . (isset($request->deposit_symbol[$count]) ? $request->deposit_symbol[$count] : "");
                    $course_program->discount_per_week = (isset($request->discount_per_week[$count]) ? $request->discount_per_week[$count] : "") . " " . (isset($request->discount_per_week_symbol[$count]) ? $request->discount_per_week_symbol[$count] : "");
                    $course_program->discount_start_date = $request->discount_start_date[$count] ?? null;
                    $course_program->discount_end_date = $request->discount_end_date[$count] ?? null;
    
                    $course_program->christmas_start_date = $request->christmas_start_date[$count] ?? null;
                    $course_program->christmas_end_date = $request->christmas_end_date[$count] ?? null;
    
                    $course_program->x_week_selected = $request->x_week_selected[$count] ?? null;
                    $course_program->x_week_start_date = $request->x_week_start_date[$count] ?? null;
                    $course_program->x_week_end_date = $request->x_week_end_date[$count] ?? null;    
                    $course_program->how_many_week_free = $request->how_many_week_free[$count] ?? null;
    
                    $course_program->summer_fee_per_week = $request->summer_fee_per_week[$count];
                    $course_program->summer_fee_start_date = $request->summer_fee_start_date[$count];
                    $course_program->summer_fee_end_date = $request->summer_fee_end_date[$count];
    
                    $course_program->peak_time_fee_per_week = $request->peak_time_fee_per_week[$count];
                    $course_program->peak_time_start_date = $request->peak_time_start_date[$count];
                    $course_program->peak_time_end_date = $request->peak_time_end_date[$count];

                    $course_program->order = $count;

                    $course_program->save();
                }
            }
        }
        (new Controller())->my_unique_id(1);
        return true;
    }

    public function cloneCourse($id)
    {
        $course = Course::where('unique_id', $id)->first();
        $course_id = (new Controller())->my_unique_id();
        $exist_course = Course::where('unique_id', $course_id)->get();
        while (count($exist_course)) {
            (new Controller())->my_unique_id(1);
            $course_id = '' . (new Controller())->my_unique_id();
            $exist_course = Course::where('unique_id', $course_id)->get();
        }
        $new_course = [];
        $new_course['unique_id'] = $course_id;
        $new_course['language'] = $course->language;
        $new_course['program_type'] = $course->program_type;
        $new_course['study_mode'] = $course->study_mode;
        $new_course['link_fee_enable'] = $course->link_fee_enable;
        $new_course['school_id'] = $course->school_id;
        $new_course['country_id'] = $course->country_id;
        $new_course['city_id'] = $course->city_id;
        $new_course['branch'] = $course->branch;
        $new_course['currency'] = $course->currency;
        $new_course['program_name'] = $course->program_name;
        $new_course['program_name_ar'] = $course->program_name_ar;
        $new_course['program_level'] = $course->program_level;
        $new_course['program_level_ar'] = $course->program_level_ar;
        $new_course['lessons_per_week'] = $course->lessons_per_week;
        $new_course['hours_per_week'] = $course->hours_per_week;
        $new_course['study_time'] = $course->study_time ?? [];
        $new_course['classes_day'] = $course->classes_day ?? [];
        $new_course['start_date'] = $course->start_date ?? [];
        $new_course['program_information'] = $course->program_information;
        $new_course['program_information_ar'] = $course->program_information_ar;
        $new_course['study_finance'] = $course->study_finance;
        $new_course['display'] = 0;
        Course::create($new_course);

        \Session::put('course_id', $course_id);
        
        $course_programs = CourseProgram::where('course_unique_id', '' . $course->unique_id)->orderBy('order')->get();
        for ($count = 0; $count < count($course_programs); $count++) {
            $course_program = $course_programs[$count];
            $new_course_program = [];
            $course_program_id = (new Controller())->my_unique_id();
            $exist_course_program = CourseProgram::where('unique_id', $course_program_id)->get();
            while (count($exist_course_program)) {
                (new Controller())->my_unique_id(1);
                $course_program_id = (new Controller())->my_unique_id();
                $exist_course_program = CourseProgram::where('unique_id', $course_program_id)->get();
            }

            $new_course_program = new CourseProgram;
            $new_course_program->course_unique_id = $course_id;
            $new_course_program->unique_id = $course_program_id;
            $new_course_program->link_fee = $course_program->link_fee;
            $new_course_program->tax_percent = $course_program->tax_percent;
            $new_course_program->bank_charge_fee = $course_program->bank_charge_fee;
            $new_course_program->program_registration_fee = $course_program->program_registration_fee;
            $new_course_program->program_duration = $course_program->program_duration ?? null;
            $new_course_program->program_age_range = $course_program->program_age_range ?? null;
            $new_course_program->program_cost = $course_program->program_cost;
            $new_course_program->program_duration_start = $course_program->program_duration_start ?? null;
            $new_course_program->program_duration_end = $course_program->program_duration_end ?? null;
            $new_course_program->program_start_date = $course_program->program_start_date ?? null;
            $new_course_program->program_end_date = $course_program->program_end_date ?? null;

            $new_course_program->available_date = $course_program->available_date[$count] ?? null;
            $new_course_program->select_day_week = $course_program->select_day_week[$count] ?? null;
            $new_course_program->available_days = $course_program->available_days[$count] ?? null;

            $new_course_program->courier_fee = $course_program->courier_fee;
            $new_course_program->about_courier = $course_program->about_courier;
            $new_course_program->about_courier_ar = $course_program->about_courier_ar;

            $new_course_program->deposit = $course_program->deposit . " " . ($course_program->deposit_symbol ?? '');

            $new_course_program->discount_per_week = ($course_program->discount_per_week ?? '') . " " . ($course_program->discount_per_week_symbol ?? '');
            $new_course_program->discount_start_date = $course_program->discount_start_date ?? null;
            $new_course_program->discount_end_date = $course_program->discount_end_date ?? null;

            $new_course_program->christmas_start_date = $course_program->christmas_start_date ?? null;
            $new_course_program->christmas_end_date = $course_program->christmas_end_date ?? null;

            $new_course_program->x_week_selected = $course_program->x_week_selected ?? null;
            $new_course_program->x_week_start_date = $course_program->x_week_start_date ?? null;
            $new_course_program->x_week_end_date = $course_program->x_week_end_date ?? null;
            $new_course_program->how_many_week_free = $course_program->how_many_week_free ?? null;

            $new_course_program->summer_fee_per_week = $course_program->program_summer_fee_per_week;
            $new_course_program->summer_fee_start_date = $course_program->program_summer_fee_start_date;
            $new_course_program->summer_fee_end_date = $course_program->program_summer_fee_end_date;

            $new_course_program->peak_time_fee_per_week = $course_program->program_peak_time_fee_per_week;
            $new_course_program->peak_time_start_date = $course_program->program_peak_time_start_date;
            $new_course_program->peak_time_end_date = $course_program->program_peak_time_end_date;

            $new_course_program->text_book_note = $course_program->text_book_note;
            $new_course_program->text_book_note_ar = $course_program->text_book_note_ar;

            $new_course_program->order = $course_program->order;

            $new_course_program->save();

            $course_under_ages = CourseProgramUnderAgeFee::where('course_program_id', '' . $new_course_program->unique_id)->get();
            for ($under_age_count = 0; $under_age_count < count($course_under_ages); $under_age_count++) {
                $course_under_age = $course_under_ages[$under_age_count];
                $new_course_under_age = [];
                $new_course_under_age->course_program_id = $course_program_id;
                $new_course_under_age->under_age = $course_under_age->under_age;
                $new_course_under_age->under_age_fee_per_week = $course_under_age->under_age_fee_per_week;

                CourseProgramUnderAgeFee::create($new_course_under_age);
            }

            $course_text_book_fees = CourseProgramTextBookFee::where('course_program_id', '' . $new_course_program->unique_id)->get();
            for ($text_book_fee_count = 0; $text_book_fee_count < count($course_text_book_fees); $text_book_fee_count++) {
                $course_text_book_fee = $course_text_book_fees[$text_book_fee_count];
                $new_course_text_book_fee = [];
                $new_course_text_book_fee->course_program_id = $course_program_id;
                $new_course_text_book_fee->text_book_fee = $course_text_book_fee->text_book_fee;
                $new_course_text_book_fee->text_book_start_date = $course_text_book_fee->text_book_start_date;
                $new_course_text_book_fee->text_book_end_date = $course_text_book_fee->text_book_end_date;
                $new_course_text_book_fee->text_book_fee_type = $course_text_book_fee->text_book_fee_type;

                CourseProgramTextBookFee::create($new_course_text_book_fee);
            }
        }

        $course_accommodations = CourseAccommodation::where('course_unique_id', '' . $course->unique_id)->orderBy('order')->get();
        for ($count = 0; $count < count($course_accommodations); $count++) {
            $course_accomodation = $course_accommodations[$count];
            $new_course_accomodation = [];
            $accommodation_id = (new Controller())->my_unique_id();
            $exist_accomodation = CourseAccommodation::where('unique_id', $accommodation_id)->get();
            while (count($exist_accomodation)) {
                (new Controller())->my_unique_id(1);
                $accommodation_id = (new Controller())->my_unique_id();
                $exist_accomodation = CourseAccommodation::where('unique_id', $accommodation_id)->get();
            }

            $new_course_accommodation = new CourseAccommodation;
            $new_course_accommodation->unique_id = $accommodation_id;
            $new_course_accommodation->course_unique_id = $course_id;
            $new_course_accommodation->type = $course_accomodation->type ?? null;
            $new_course_accommodation->type_ar = $course_accomodation->type_ar ?? null;
            $new_course_accommodation->room_type = $course_accomodation->room_type ?? null;
            $new_course_accommodation->room_type_ar = $course_accomodation->room_type_ar ?? null;
            $new_course_accommodation->meal = $course_accomodation->meal ?? null;
            $new_course_accommodation->meal_ar = $course_accomodation->meal_ar ?? null;
            $new_course_accommodation->age_range = $course_accomodation->age_range ?? null;
            $new_course_accommodation->deposit_fee = $course_accomodation->deposit_fee ?? null;

            $new_course_accommodation->special_diet_fee = $course_accomodation->special_diet_fee ?? null;
            $new_course_accommodation->special_diet_note = $course_accomodation->special_diet_note;
            $new_course_accommodation->special_diet_note_ar = $course_accomodation->special_diet_note_ar;
            
            $new_course_accommodation->placement_fee = $course_accomodation->placement_fee ?? null;
            $new_course_accommodation->program_duration = $course_accomodation->program_duration ?? null;
            $new_course_accommodation->fee_per_week = $course_accomodation->fee_per_week ?? null;
            $new_course_accommodation->start_week = $course_accomodation->start_week ?? null;
            $new_course_accommodation->end_week = $course_accomodation->end_week ?? null;
            $new_course_accommodation->available_date = $course_accomodation->available_date ?? null;
            $new_course_accommodation->available_days = $course_accomodation->available_days ?? null;
            $new_course_accommodation->start_date = $course_accomodation->start_date ?? null;
            $new_course_accommodation->end_date = $course_accomodation->end_date ?? null;
            
            $new_course_accommodation->discount_per_week = ($course_accomodation->discount_per_week ?? '') . " " . ($course_accomodation->discount_per_week_symbol ?? '');
            $new_course_accommodation->discount_per_week_symbol = $course_accomodation->discount_per_week_symbol ?? null;
            $new_course_accommodation->discount_start_date = $course_accomodation->discount_start_date ?? null;
            $new_course_accommodation->discount_end_date = $course_accomodation->discount_end_date ?? null;

            $new_course_accommodation->summer_fee_per_week = $course_accomodation->summer_fee_per_week ?? null;
            $new_course_accommodation->summer_fee_start_date = $course_accomodation->summer_fee_start_date ?? null;
            $new_course_accommodation->summer_fee_end_date = $course_accomodation->summer_fee_end_date ?? null;
            
            $new_course_accommodation->peak_time_fee_per_week = $course_accomodation->peak_time_fee_per_week ?? null;
            $new_course_accommodation->peak_time_fee_start_date = $course_accomodation->peak_time_fee_start_date ?? null;
            $new_course_accommodation->peak_time_fee_end_date = $course_accomodation->peak_time_fee_end_date ?? null;
            
            $new_course_accommodation->christmas_fee_per_week = $course_accomodation->christmas_fee_per_week ?? null;
            $new_course_accommodation->christmas_fee_start_date = $course_accomodation->christmas_fee_start_date ?? null;
            $new_course_accommodation->christmas_fee_end_date = $course_accomodation->christmas_fee_end_date ?? null;

            $new_course_accommodation->x_week_selected = $course_accomodation->x_week_selected ?? null;
            $new_course_accommodation->x_week_start_date = $course_accomodation->x_week_start_date ?? null;
            $new_course_accommodation->x_week_end_date = $course_accomodation->x_week_end_date ?? null;
            $new_course_accommodation->how_many_week_free = $course_accomodation->how_many_week_free ?? null;
            
            $new_course_accommodation->order = $course_accomodation->order;

            $new_course_accommodation->save();
        }

        $course_airports = CourseAirport::where('course_unique_id', '' . $course->unique_id)->orderBy('order')->get();
        for ($count = 0; $count < count($course_airports); $count++) {
            $course_airport = $course_airports[$count];
            $new_course_airport = new CourseAirport;
            $new_course_airport->course_unique_id = $course_id;
            $new_course_airport->service_provider = $course_airport->service_provider ?? null;
            $new_course_airport->service_provider_ar = $course_airport->service_provider_ar ?? null;
            $new_course_airport->week_selected_fee = (double)$course_airport->week_selected_fee ?? null;
            $new_course_airport->note = $course_airport->note ?? null;
            $new_course_airport->note_ar = $course_airport->note_ar ?? null;
            $new_course_airport->order = $course_airport->order;
            $new_course_airport->save();

            $course_airport_fees = CourseAirportFee::where('course_airport_unique_id', '' . $course_airport->unique_id)->get();
            for ($count_fee = 0; $count_fee < count($course_airport_fees); $count_fee++) {
                $course_airport_fee = $course_airport_fees[$count_fee];
                $new_course_airport_fee = new CourseAirportFee;
                $new_course_airport_fee->course_airport_unique_id = $new_course_airport->unique_id;
                $new_course_airport_fee->name = $course_airport_fee->name ?? null;
                $new_course_airport_fee->name_ar = $course_airport_fee->name_ar ?? null;
                $new_course_airport_fee->service_name = $course_airport_fee->service_name ?? null;
                $new_course_airport_fee->service_name_ar = $course_airport_fee->service_name_ar ?? null;
                $new_course_airport_fee->service_fee = $course_airport_fee->service_fee ?? null;
                $new_course_airport_fee->save();
            }
        }

        $course_medicals = CourseMedical::where('course_unique_id', '' . $course->unique_id)->orderBy('order')->get();
        for ($count = 0; $count < count($course_medicals); $count++) {
            $course_medical = $course_medicals[$count];
            $new_course_medical = new CourseMedical;
            $new_course_medical->course_unique_id = $course_id;
            $new_course_medical->company_name = $course_medical->company_name ?? null;
            $new_course_medical->company_name_ar = $course_medical->company_name_ar ?? null;
            $new_course_medical->deductible = $course_medical->deductible ?? null;
            $new_course_medical->week_selected_fee = $course_medical->week_selected_fee ?? null;
            $new_course_medical->note = $course_medical->note ?? null;
            $new_course_medical->note_ar = $course_medical->note_ar ?? null;
            $new_course_medical->order = $course_medical->order;
            $new_course_medical->save();

            $course_medical_fees = CourseMedicalFee::where('course_medical_unique_id', '' . $course_medical->unique_id)->get();
            for ($count_fee = 0; $count_fee < count($course_medical_fees); $count_fee++) {
                $course_medical_fee = $course_medical_fees[$count_fee];
                $new_course_medical_fee = new CourseMedicalFee;
                $new_course_medical_fee->course_medical_unique_id = $new_course_medical->unique_id;
                $new_course_medical_fee->fees_per_week = $course_medical_fee->fees_per_week ?? null;
                $new_course_medical_fee->start_date = $course_medical_fee->start_date ?? null;
                $new_course_medical_fee->end_date = $course_medical_fee->end_date ?? null;
                $new_course_medical_fee->save();
            }
        }

        $course_custodians = CourseCustodian::where('course_unique_id', '' . $course->unique_id)->orderBy('order')->get();
        for ($count = 0; $count < count($course_custodians); $count++) {
            $course_custodian = $course_custodians[$count];
            $new_course_custodian = new CourseCustodian;
            $new_course_custodian->course_unique_id = $course_id;
            $new_course_custodian->fee = $course_custodian->fee ?? null;
            $new_course_custodian->age_range = $course_custodian->age_range ?? null;
            $new_course_custodian->condition = $course_custodian->condition ?? null;
            $new_course_custodian->order = $course_custodian->order;
            $new_course_custodian->save();
        }

        return true;
    }

    /**
     * @param Request $request
     * @return bool|\Illuminate\Support\MessageBag
     */
    public function createProgramUnderAgeAndTextBook(Request $request)
    {
        $rules = [
            'program_id.*' => 'required',
            'text_book_fee_start_date.*' => 'required',
            'text_book_fee_end_date.*' => 'required',
        ];

        /*
         * Validation Rules Starts here
         * */
        $validation = \Validator::make($request->all(), $rules, [
            'program_id.*.required' => "Program ID is required",
            'text_book_fee_start_date.*.required' => "Text Book Fee Start Date is required",
            'text_book_fee_end_date.*.required' => "Text Book Fee End Date is required",
        ]);

        if ($validation->fails()) {
            return $this->get_error = $validation->errors();
        }

        \DB::transaction(function () use ($request) {
            extract($request->all());

            for ($program_index = 0; $program_index < count($program_id); $program_index++) {
                $course_program = CourseProgram::where('unique_id', $program_id[$program_index])->first();
                $course_program->text_book_note = $text_book_note[$program_index] ?? null;
                $course_program->text_book_note_ar = $text_book_note_ar[$program_index] ?? null;
                $course_program->save();

                for ($under_age_index = 0; $under_age_index <= $underagefeeincrement; $under_age_index++) {
                    $new_course_program_under_age_fee = new CourseProgramUnderAgeFee();
                    $new_course_program_under_age_fee->course_program_id = $program_id[$program_index];
                    $new_course_program_under_age_fee->under_age = $under_age[$under_age_index] ?? null;
                    $new_course_program_under_age_fee->under_age_fee_per_week = $under_age_fee_per_week[$under_age_index] ?? null;
                    $new_course_program_under_age_fee->save();
                }
    
                for ($text_book_fee_index = 0; $text_book_fee_index <= $textbookfeeincrement; $text_book_fee_index++) {
                    $new_course_program_text_book = new CourseProgramTextBookFee();
                    $new_course_program_text_book->course_program_id = $program_id[$program_index];
                    $new_course_program_text_book->text_book_fee = $text_book_fee[$text_book_fee_index];
                    $new_course_program_text_book->text_book_start_date = $text_book_fee_start_date[$text_book_fee_index];
                    $new_course_program_text_book->text_book_end_date = $text_book_fee_end_date[$text_book_fee_index];
                    $new_course_program_text_book->text_book_fee_type = $text_book_fee_type[$text_book_fee_index] ?? null;
                    $new_course_program_text_book->save();
                }
            }
        });

        return true;
    }

    /**
     * @param Request $request
     * @return bool|\Illuminate\Support\MessageBag
     */
    public function createAccommodation(Request $request)
    {
        \Session::forget('accom_ids');

        $rules = ['accommodation_id.*' => 'required', 'type.*' => 'required',
            'room_type.*' => 'required', 'meal.*' => 'required',
            'age_range.*' => 'required',
        ];

        /*
         * Validation Rules Starts here
         * */
        $validation = \Validator::make($request->all(), $rules, [
            'accommodation_id.*.required' => 'Accommodation ID is required',
            'type.*.required' => 'Accommodation Type is required',
            'room_type.*.required' => 'Room Type is required',
            'meal.*.required' => 'Meal required',
            'age_range.required' => 'Age Range Duration required',
            'placement_fee.*.required' => 'Accommodation Placement Fee required',
            'program_duration.*.required' => "Accommodation Program Duration required",
            'deposit_fee.*.required' => "Deposit Fee is required",

            'fee_per_week.*.required' => 'Accommodation fee is required',
            'end_week.*.required' => 'Accommodation End Week is required',

            'start_week.required' => 'Accommodation Start Week required',
            'start_date.*.required' => 'Accommodation Start Date required',
            'end_date.*.required' => "Accommodation End Date required",
        ]);

        if ($validation->fails()) {
            return $this->get_error = $validation->errors();
        }

        extract($request->all());

        for ($accom = 0; $accom < count($accommodation_id); $accom++) {
            if (isset($accom)) {
                $new_course_accommodation = new CourseAccommodation;
                $new_course_accommodation->unique_id = $accommodation_id[$accom] ?? null;
                $new_course_accommodation->course_unique_id = '' . \Session::get('course_id');
                $new_course_accommodation->type = $type[$accom] ?? null;
                $new_course_accommodation->type_ar = $type_ar[$accom] ?? null;
                $new_course_accommodation->room_type = $room_type[$accom] ?? null;
                $new_course_accommodation->room_type_ar = $room_type_ar[$accom] ?? null;
                $new_course_accommodation->meal = $meal[$accom] ?? null;
                $new_course_accommodation->meal_ar = $meal_ar[$accom] ?? null;
                $new_course_accommodation->age_range = $age_range[$accom] ?? null;
                $new_course_accommodation->placement_fee = $placement_fee[$accom] ?? null;
                $new_course_accommodation->program_duration = $program_duration[$accom] ?? null;
                $new_course_accommodation->deposit_fee = $deposit_fee[$accom] ?? null;

                $new_course_accommodation->special_diet_fee = $special_diet_fee[$accom] ?? null;
                $new_course_accommodation->special_diet_note = $special_diet_note[$accom] ?? null;
                $new_course_accommodation->special_diet_note_ar = $special_diet_note_ar[$accom] ?? null;
                
                $new_course_accommodation->fee_per_week = $fee_per_week[$accom] ?? null;
                $new_course_accommodation->start_week = $start_week[$accom] ?? null;
                $new_course_accommodation->end_week = $end_week[$accom] ?? null;
                $new_course_accommodation->available_date = $available_date[$accom] ?? null;
                $new_course_accommodation->available_days = $available_days[$accom] ?? null;
                $new_course_accommodation->start_date = $start_date[$accom] ?? null;
                $new_course_accommodation->end_date = $end_date[$accom] ?? null;
                
                $new_course_accommodation->discount_per_week = (isset($discount_per_week[$accom]) ? $discount_per_week[$accom] : '') . " " . (isset($discount_per_week_symbol[$accom]) ? $discount_per_week_symbol[$accom] : '');
                $new_course_accommodation->discount_per_week_symbol = (isset($discount_per_week_symbol[$accom]) ? $discount_per_week_symbol[$accom] : null);
                $new_course_accommodation->discount_start_date = $discount_start_date[$accom] ?? null;
                $new_course_accommodation->discount_end_date = $discount_end_date[$accom] ?? null;
                
                $new_course_accommodation->summer_fee_per_week = $summer_fee_per_week[$accom] ?? null;
                $new_course_accommodation->summer_fee_start_date = $summer_fee_start_date[$accom] ?? null;
                $new_course_accommodation->summer_fee_end_date = $summer_fee_end_date[$accom] ?? null;
                
                $new_course_accommodation->peak_time_fee_per_week = $peak_time_fee_per_week[$accom] ?? null;
                $new_course_accommodation->peak_time_fee_start_date = $peak_time_fee_start_date[$accom] ?? null;
                $new_course_accommodation->peak_time_fee_end_date = $peak_time_fee_end_date[$accom] ?? null;
                
                $new_course_accommodation->christmas_fee_per_week = $christmas_fee_per_week[$accom] ?? null;
                $new_course_accommodation->christmas_fee_start_date = $christmas_fee_start_date[$accom] ?? null;
                $new_course_accommodation->christmas_fee_end_date = $christmas_fee_end_date[$accom] ?? null;

                $new_course_accommodation->x_week_selected = $x_week_selected[$accom] ?? null;
                $new_course_accommodation->x_week_start_date = $x_week_start_date[$accom] ?? null;
                $new_course_accommodation->x_week_end_date = $x_week_end_date[$accom] ?? null;
                $new_course_accommodation->how_many_week_free = $how_many_week_free[$accom] ?? null;

                $new_course_accommodation->order = $accom;
                
                $new_course_accommodation->save();

                \Session::push('accom_ids', '' . $accommodation_id[$accom]);
            }
        }

        return true;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createAccommodationUnderAge(Request $request)
    {
        return \DB::transaction(function () use ($request) {
            $rules = [
                'accom_id.*' => 'required',
            ];

            /*
             * Validation Rules Starts here
             * */
            $validation = \Validator::make($request->all(), $rules, [
                'accom_id.*.required' => "Accommodation ID is required",
            ]);

            if ($validation->fails()) {
                return $this->get_error = $validation->errors();
            }

            extract($request->all());

            for ($accom_index = 0; $accom_index < count($accom_id); $accom_index++) {
                for ($i = 0; $i < (int)$accomunderageincrement; $i++) {
                    $new_course_accommodation_under_age = new CourseAccommodationUnderAge();
                    $new_course_accommodation_under_age->accom_id = $accom_id[$accom_index];
                    $new_course_accommodation_under_age->under_age = $under_age[$i] ?? null;
                    $new_course_accommodation_under_age->under_age_fee_per_week = $under_age_fee_per_week[$i] ?? null;
                    $new_course_accommodation_under_age->save();
                }
            }

            return true;
        });
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createOtherServiceFee(Request $request)
    {
        return \DB::transaction(function () use ($request) {
            $rules = [
                'airport_service_provider.*' => 'required',
                'airport_service_provider_ar.*' => 'required',
                'airport_note.*' => 'required',
                'airport_note_ar.*' => 'required',
                // 'medical_company_name.*' => 'required',
                // 'medical_deductible.*' => 'required',
                // 'medical_note.*' => 'required',
            ];

            /*
             * Validation Rules Starts here
             * */
            $validation = \Validator::make($request->all(), $rules, [
                'airport_service_provider.*.required' => "Airport Service Provider in English required",
                'airport_service_provider_ar.*.required' => "Airport Service Provider in Arabic required",
                'airport_note.*.required' => "Airport Note in English required",
                'airport_note_ar.*.required' => "Airport Note in Arabic required",
                // 'medical_company_name.*.required' => "Medical Company Name required",
                // 'medical_deductible.*.required' => "Medical Deductible required",
                // 'medical_note.*.required' => "Medical Note required",
            ]);

            if ($validation->fails()) {
                return $this->get_error = $validation->errors();
            }

            for ($i = 0; $i <= $request->airportincrement; $i++) {
                $airport_save = new CourseAirport();
                $airport_save->course_unique_id = '' . \Session::get('course_id');
                $airport_save->service_provider = $request->airport_service_provider[$i] ?? null;
                $airport_save->service_provider_ar = $request->airport_service_provider_ar[$i] ?? null;
                $airport_save->week_selected_fee = $request->airport_week_selected_fee[$i] ?? null;
                $airport_save->note = $request->airport_note[$i] ?? null;
                $airport_save->note_ar = $request->airport_note_ar[$i] ?? null;
                $airport_save->order = $i;
                $airport_save->save();
            
                for ($j = 0; $j <= $request->airportfeeincrement[$i]; $j++) {
                    if (isset($request->airport_name[$i][$j]) && ($request->airport_name[$i][$j] || $request->airport_name_ar[$i][$j]) && ($request->airport_service_name[$i][$j] && $request->airport_service_name_ar[$i][$j])) {
                        $airport_fee_save = new CourseAirportFee();
                        $airport_fee_save->course_airport_unique_id = $airport_save->unique_id;
                        $airport_fee_save->name = $request->airport_name[$i][$j] ?? null;
                        $airport_fee_save->name_ar = $request->airport_name_ar[$i][$j] ?? null;
                        $airport_fee_save->service_name = $request->airport_service_name[$i][$j] ?? null;
                        $airport_fee_save->service_name_ar = $request->airport_service_name_ar[$i][$j] ?? null;
                        $airport_fee_save->service_fee = (double)$request->airport_service_fee[$i][$j] ?? null;
                        $airport_fee_save->save();
                    }
                }
            }

            for ($k = 0; $k <= $request->medicalincrement; $k++) {
                $medical_fee_count = 0;
                for ($l = 0; $l <= $request->medicalfeeincrement[$k]; $l++) {
                    if ($request->medical_fees_per_week[$k][$l]) {
                        $medical_fee_count = $medical_fee_count + 1;
                    }
                }
                
                if ($medical_fee_count && isset($request->medical_company_name[$k]) && ($request->medical_company_name[$k] || $request->medical_company_name_ar[$k] || $request->medical_deductible[$k] || $request->medical_week_selected_fee[$k])) {
                    $medical_save = new CourseMedical();
                    $medical_save->course_unique_id = '' . \Session::get('course_id');
                    $medical_save->company_name = $request->medical_company_name[$k] ?? null;
                    $medical_save->company_name_ar = $request->medical_company_name_ar[$k] ?? null;
                    $medical_save->deductible = $request->medical_deductible[$k] ?? null;
                    $medical_save->week_selected_fee = $request->medical_week_selected_fee[$k] ?? null;
                    $medical_save->note = $request->medical_note[$k] ?? null;
                    $medical_save->note_ar = $request->medical_note_ar[$k] ?? null;
                    $medical_save->order = $k;
                    $medical_save->save();
                    
                    for ($l = 0; $l <= $request->medicalfeeincrement[$k]; $l++) {
                        if (isset($request->medical_fees_per_week[$k][$l]) && $request->medical_fees_per_week[$k][$l]) {
                            $medical_fee_save = new CourseMedicalFee();
                            $medical_fee_save->course_medical_unique_id = $medical_save->unique_id;
                            $medical_fee_save->fees_per_week = (double)$request->medical_fees_per_week[$k][$l] ?? null;
                            $medical_fee_save->start_date =$request->medical_start_date[$k][$l] ?? null;
                            $medical_fee_save->end_date = $request->medical_end_date[$k][$l] ?? null;
                            $medical_fee_save->save();
                        }
                    }
                }
            }

            for ($m = 0; $m <= $request->custodianincrement; $m++) {
                if (isset($request->custodian_fee[$m]) && $request->custodian_fee[$m]) {
                    $custodian_save = new CourseCustodian();
                    $custodian_save->course_unique_id = '' . \Session::get('course_id');
                    $custodian_save->fee = $request->custodian_fee[$m] ?? null;
                    $custodian_save->age_range = $request->custodian_age_range[$m] ?? null;
                    $custodian_save->condition = $request->custodian_condition[$m] ?? null;
                    $custodian_save->order = $m;
                    $custodian_save->save();
                }
            }

            return true;
        });
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function updateCourseAndProgram(Request $request, $id)
    {
        return \DB::transaction(function () use ($request, $id) {
            extract($request->all());

            $rules = [
                'school_name' => ['required',],
                'country_id' => ['required',],
                'city_id' => ['required',],
                'language' => ['required', ],
                'study_mode' => ['required',],
                'program_type' => ['required',],
                'currency' => ['required',],
                'program_name' => ['required',],
                'lessons_per_week' => ['required',],
                'hours_per_week' => ['required',],
                'study_time' => ['required',],
                'program_information' => ['required',],
                'study_finance' => ['required',],
                'program_cost.*' => 'required',
                'program_start_date.*' => 'required',
                'program_end_date.*' => 'required',
            ];

            /*
             * Validation Rules Starts here
             * */
            $validation = \Validator::make($request->all(), $rules, [
                'program_start_date.*.required' => 'Program Start Date required',
                'program_end_date.*.required' => 'Program End Date required',
            ]);

            if ($validation->fails()) {
                return $this->get_error = $validation->errors();
            }

            $course = Course::where('unique_id', $id)->first();
            $course->language = $request->language;
            $course->program_type = $request->program_type ?? [];
            $course->study_mode = $request->study_mode;
            $course->link_fee_enable = $request->link_fee_enable;
            $course->school_id = 0;
            $language = app()->getLocale();
            $course_school = School::whereHas('name', function($query) use ($request, $language)
                { $language == 'en' ? $query->where('name', $request->school_name) : $query->where('name_ar', $request->school_name); })
                ->where('country_id', $request->country_id)->where('city_id', $request->city_id)->first();
            if ($course_school) {
                $course->school_id = $course_school->id;
            }
            $course->country_id = $request->country_id;
            $course->city_id = $request->city_id;
            $course->branch = $request->branch;
            $course->currency = $request->currency;
            $course->program_name = $request->program_name;
            $course->program_name_ar = $request->program_name_ar;
            $course->program_level = $request->program_level;
            $course->program_level_ar = $request->program_level_ar;
            $course->lessons_per_week = $request->lessons_per_week;
            $course->hours_per_week = $request->hours_per_week;
            $course->study_time = $request->study_time ?? [];
            $course->classes_day = $request->classes_day ?? [];
            $course->start_date = $request->start_date ?? [];
            $course->program_information = $request->program_information;
            $course->program_information_ar = $request->program_information_ar;
            $course->study_finance = $request->study_finance;
            $course->save();

            $course_id = $course->unique_id;
            \Session::put('course_id', '' . $course_id);
            
            \Session::forget('program_id');
            \Session::forget('program_ids');

            $course_program_ids = [];
            if (isset($request->program_increment)) {
                for ($count = 0; $count <= (int)$request->program_increment; $count++) {
                    $course_program = null;
                    if (isset($request->program_id[$count]) && $request->program_id[$count]) {
                        $course_program = CourseProgram::where('unique_id', $request->program_id[$count])->first();
                    }
                    if (!$course_program) {
                        $course_program = new CourseProgram;
                        if (isset($request->program_id[$count]) && $request->program_id[$count]) {
                            $course_program->unique_id = $request->program_id[$count];
                        } else {
                            (new Controller())->my_unique_id(1);
                            $course_program->unique_id = (new Controller())->my_unique_id();
                        }
                    }
                    $course_program->course_unique_id = '' . $course_id;
                    $course_program->link_fee = $request->link_fee[$count];
                    $course_program->tax_percent = $request->tax_percent[$count];
                    $course_program->bank_charge_fee = $request->bank_charge_fee[$count];
                    $course_program->program_registration_fee = $request->program_registration_fee[$count];
                    $course_program->program_duration = $request->program_duration[$count] ?? null;
                    $course_program->program_age_range = $request->age_range[$count] ?? null;
                    $course_program->courier_fee = $request->courier_fee[$count];
                    $course_program->about_courier = $request->about_courier[$count] ?? null;
                    $course_program->about_courier_ar = $request->about_courier_ar[$count] ?? null;
                    $course_program->program_cost = $request->program_cost[$count];

                    $course_program->program_duration_start = $request->program_duration_start[$count] ?? null;
                    $course_program->program_duration_end = $request->program_duration_end[$count] ?? null;
                    $course_program->program_start_date = $request->program_start_date[$count] ?? null;
                    $course_program->program_end_date = $request->program_end_date[$count] ?? null;

                    $course_program->available_date = $request->available_date[$count] ?? null;
                    $course_program->select_day_week = $request->select_day_week[$count] ?? null;
                    $course_program->available_days = $request->available_days[$count] ?? null;

                    $course_program->deposit = (isset($request->deposit[$count]) ? $request->deposit[$count] : '') . " " . (isset($request->deposit_symbol[$count]) ? $request->deposit_symbol[$count] : '');
                    $course_program->discount_per_week = (isset($request->discount_per_week[$count]) ? $request->discount_per_week[$count] : '') . " " . (isset($request->discount_per_week_symbol[$count]) ? $request->discount_per_week_symbol[$count] : '');
                    $course_program->discount_start_date = $request->discount_start_date[$count] ?? null;
                    $course_program->discount_end_date = $request->discount_end_date[$count] ?? null;

                    $course_program->christmas_start_date = $request->christmas_start_date[$count] ?? null;
                    $course_program->christmas_end_date = $request->christmas_end_date[$count] ?? null;

                    $course_program->x_week_selected = $request->x_week_selected[$count] ?? null;
                    $course_program->x_week_start_date = $request->x_week_start_date[$count] ?? null;
                    $course_program->x_week_end_date = $request->x_week_end_date[$count] ?? null;
                    $course_program->how_many_week_free = $request->how_many_week_free[$count] ?? null;

                    $course_program->summer_fee_per_week = $request->summer_fee_per_week[$count];
                    $course_program->summer_fee_start_date = $request->summer_fee_start_date[$count];
                    $course_program->summer_fee_end_date = $request->summer_fee_end_date[$count];

                    $course_program->peak_time_fee_per_week = $request->peak_time_fee_per_week[$count];
                    $course_program->peak_time_start_date = $request->peak_time_start_date[$count];
                    $course_program->peak_time_end_date = $request->peak_time_end_date[$count];

                    $course_program->order = $count;

                    $course_program_ids[] = $course_program->unique_id;
                    $course_program->save();
                }
            }
            
            $course = Course::with('coursePrograms')->where('unique_id', '' . $course_id)->first();
            foreach ($course->coursePrograms as $course_program) {
                if (!in_array($course_program->unique_id, $course_program_ids)) {
                    $course_program->delete();
                } else {
                    \Session::push('program_ids', '' . $course_program->unique_id);
                }
            }
            return true;
        });
    }

    /**
     * @param Request $request
     * @param null $id
     * @return bool|\Illuminate\Support\MessageBag
     */
    public function updateProgramUnderAgeAndTextBook(Request $request, $id = null)
    {
        $rules = [
            'program_id' => 'required',
            'text_book_fee_start_date.*' => 'required',
            'text_book_fee_end_date.*' => 'required',
        ];

        /*
         * Validation Rules Starts here
         * */
        $validation = \Validator::make($request->all(), $rules, [
            'program_id.required' => "Program ID is required",
            'text_book_fee_start_date.*.required' => "Text Book Fee Start Date is required",
            'text_book_fee_end_date.*.required' => "Text Book Fee End Date is required",
        ]);

        if ($validation->fails()) {
            return $this->get_error = $validation->errors();
        }

        extract($request->all());
        
        $course_program = CourseProgram::where('unique_id', $program_id)->first();
        $course_program->text_book_note = $text_book_note;
        $course_program->text_book_note_ar = $text_book_note_ar;
        $course_program->save();

        $program_under_age_fee_ids = [];
        for ($under_age_fee_index = 0; $under_age_fee_index <= (int)$underagefeeincrement; $under_age_fee_index++) {
            $program_under_age_fee = null;
            if (isset($request->under_age_id[$under_age_fee_index]) && $request->under_age_id[$under_age_fee_index]) {
                $program_under_age_fee = CourseProgramUnderAgeFee::find($request->under_age_id[$under_age_fee_index]);
            }
            if (!$program_under_age_fee) {
                $program_under_age_fee = new CourseProgramUnderAgeFee;
                $program_under_age_fee->course_program_id = $program_id;
            }
            $program_under_age_fee->under_age = $under_age[$under_age_fee_index] ?? null;
            $program_under_age_fee->under_age_fee_per_week = $under_age_fee_per_week[$under_age_fee_index] ?? null;
            $program_under_age_fee->save();
            $program_under_age_fee_ids [] = $program_under_age_fee->id;
        }
        $program_under_age_fees = CourseProgramUnderAgeFee::where('course_program_id', $program_id)->get();
        foreach ($program_under_age_fees as $program_under_age_fee) {
            if (!in_array($program_under_age_fee->id, $program_under_age_fee_ids)) {
                $program_under_age_fee->delete();
            }
        }

        $program_text_book_fee_ids = [];
        for ($text_book_fee_index = 0; $text_book_fee_index <= (int)$textbookfeeincrement; $text_book_fee_index++) {
            $program_text_book_fee = null;
            if (isset($request->textbook_id[$text_book_fee_index]) && $request->textbook_id[$text_book_fee_index]) {
                $program_text_book_fee = CourseProgramTextBookFee::find($request->textbook_id[$text_book_fee_index]);
            }
            if (!$program_text_book_fee) {
                $program_text_book_fee = new CourseProgramTextBookFee;
                $program_text_book_fee->course_program_id = $program_id;
            }
            $program_text_book_fee->text_book_fee = $text_book_fee[$text_book_fee_index];
            $program_text_book_fee->text_book_start_date = $text_book_fee_start_date[$text_book_fee_index];
            $program_text_book_fee->text_book_end_date = $text_book_fee_end_date[$text_book_fee_index];
            $program_text_book_fee->text_book_fee_type = $text_book_fee_type[$text_book_fee_index] ?? null;
            $program_text_book_fee->save();
            $program_text_book_fee_ids[] = $program_text_book_fee->id;
        }
        $program_text_book_fees = CourseProgramTextBookFee::where('course_program_id', $program_id)->get();
        foreach ($program_text_book_fees as $program_text_book_fee) {
            if (!in_array($program_text_book_fee->id, $program_text_book_fee_ids)) {
                $program_text_book_fee->delete();
            }
        }

        return true;
    }

    /**
     * @param Request $request
     * @return bool|\Illuminate\Support\MessageBag
     */
    public function updateAccommodation(Request $request)
    {
        $rules = ['accommodation_id.*' => 'required', 'type.*' => 'required',];

        /*
         * Validation Rules Starts here
         * */
        $validation = \Validator::make($request->all(), $rules, [
            'accommodation_id.*.required' => 'Accommodation ID is required',
            'type.*.required' => 'Accommodation Type is required',
            'room_type.*.required' => 'Room Type is required',
            'meal.*.required' => 'Meal required',
            'age_range.required' => 'Age Range Duration required',
            'placement_fee.*.required' => 'Accommodation Placement Fee required',
            'program_duration.*.required' => "Accommodation Program Duration required",
            'deposit_fee.*.required' => "Deposit Fee is required",

            'fee_per_week.*.required' => 'Accommodation Fee is required',
            'end_week.*.required' => 'Accommodation End Week is required',

            'start_week.required' => 'Accommodation Start Week required',
            'start_date.*.required' => 'Accommodation Start Date required',
            'end_date.*.required' => "Accommodation End Date required",
        ]);

        if ($validation->fails()) {
            return $this->get_error = $validation->errors();
        }

        extract($request->all());

        $course_accommodation_ids = [];
        for ($accom = 0; $accom < count($accommodation_id); $accom++) {
            $course_accomodation = null;
            if (isset($accommodation_id[$accom]) && $accommodation_id[$accom]) {
                $course_accomodation = CourseAccommodation::where('unique_id', $accommodation_id[$accom])->first();
            }
            if (!$course_accomodation) {
                $course_accomodation = new CourseAccommodation;
                if (isset($accommodation_id[$accom]) && $accommodation_id[$accom]) {
                    $course_accomodation->unique_id = $accommodation_id[$accom];
                } else {
                    (new Controller())->my_unique_id(1);
                    $course_accomodation->unique_id = (new Controller())->my_unique_id();
                }
                $course_accomodation->course_unique_id = '' . \Session::get('course_id');
            }
            $course_accomodation->type = $type[$accom] ?? null;
            $course_accomodation->type_ar = $type_ar[$accom] ?? null;
            $course_accomodation->room_type = $room_type[$accom] ?? null;
            $course_accomodation->room_type_ar = $room_type_ar[$accom] ?? null;
            $course_accomodation->meal = $meal[$accom] ?? null;
            $course_accomodation->meal_ar = $meal_ar[$accom] ?? null;
            $course_accomodation->age_range = $age_range[$accom] ?? null;
            $course_accomodation->placement_fee = $placement_fee[$accom] ?? null;
            $course_accomodation->program_duration = $program_duration[$accom] ?? null;
            $course_accomodation->deposit_fee = $deposit_fee[$accom] ?? null;
            
            $course_accomodation->special_diet_fee = $special_diet_fee[$accom] ?? null;
            $course_accomodation->special_diet_note = $special_diet_note[$accom] ?? null;
            $course_accomodation->special_diet_note_ar = $special_diet_note_ar[$accom] ?? null;
            
            $course_accomodation->fee_per_week = $fee_per_week[$accom] ?? null;
            $course_accomodation->start_week = $start_week[$accom] ?? null;
            $course_accomodation->end_week = $end_week[$accom] ?? null;

            $course_accomodation->available_date = $available_date[$accom] ?? null;
            $course_accomodation->available_days = $available_days[$accom] ?? null;
            $course_accomodation->start_date = $start_date[$accom] ?? null;
            $course_accomodation->end_date = $end_date[$accom] ?? null;
            
            $course_accomodation->discount_per_week = (isset($discount_per_week[$accom]) ? $discount_per_week[$accom] : "") . " " . (isset($discount_per_week_symbol[$accom]) ? $discount_per_week_symbol[$accom] : "") ?? null;
            $course_accomodation->discount_per_week_symbol = (isset($discount_per_week_symbol[$accom]) ? $discount_per_week_symbol[$accom] : null);
            $course_accomodation->discount_start_date = $discount_start_date[$accom] ?? null;
            $course_accomodation->discount_end_date = $discount_end_date[$accom] ?? null;
            
            $course_accomodation->summer_fee_per_week = $summer_fee_per_week[$accom] ?? null;
            $course_accomodation->summer_fee_start_date = $summer_fee_start_date[$accom] ?? null;
            $course_accomodation->summer_fee_end_date = $summer_fee_end_date[$accom] ?? null;
            
            $course_accomodation->peak_time_fee_per_week = $peak_time_fee_per_week[$accom] ?? null;
            $course_accomodation->peak_time_fee_start_date = $peak_time_fee_start_date[$accom] ?? null;
            $course_accomodation->peak_time_fee_end_date = $peak_time_fee_end_date[$accom] ?? null;
            
            $course_accomodation->christmas_fee_per_week = $christmas_fee_per_week[$accom] ?? null;
            $course_accomodation->christmas_fee_start_date = $christmas_fee_start_date[$accom] ?? null;
            $course_accomodation->christmas_fee_end_date = $christmas_fee_end_date[$accom] ?? null;

            $course_accomodation->x_week_selected = $x_week_selected[$accom] ?? null;
            $course_accomodation->x_week_start_date = $x_week_start_date[$accom] ?? null;
            $course_accomodation->x_week_end_date = $x_week_end_date[$accom] ?? null;
            $course_accomodation->how_many_week_free = $how_many_week_free[$accom] ?? null;

            $course_accomodation->order = $accom;

            $course_accommodation_ids[] = $course_accomodation->unique_id;
            $course_accomodation->save();
        }
        $course_accomodations = CourseAccommodation::where('course_unique_id', '' . \Session::get('course_id'))->get();
        foreach ($course_accomodations as $course_accomodation) {
            if (!in_array($course_accomodation->unique_id, $course_accommodation_ids)) {
                $course_accomodation->delete();
            } else {
                \Session::push('accom_ids', '' . $course_accomodation->unique_id);
            }
        }

        return true;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateAccommodationUnderAge(Request $request)
    {
        return \DB::transaction(function () use ($request) {
            $rules = [
                'accom_id' => 'required',
            ];

            /*
             * Validation Rules Starts here
             * */
            $validation = \Validator::make($request->all(), $rules, [
                'accom_id.required' => "Accommodation ID is required",
            ]);

            if ($validation->fails()) {
                return $this->get_error = $validation->errors();
            }

            extract($request->all());

            $accommodation_under_age_ids = [];
            for ($i = 0; $i <= (int)$accomunderageincrement; $i++) {
                $accommodation_under_age = null;
                if (isset($accom_under_age_id[$i]) && $accom_under_age_id[$i]) {
                    $accommodation_under_age = CourseAccommodationUnderAge::find($accom_under_age_id[$i]);
                }
                if (!$accommodation_under_age) {
                    $accommodation_under_age = new CourseAccommodationUnderAge;
                    $accommodation_under_age->accom_id = $accom_id;
                }
                $accommodation_under_age->under_age = $under_age[$i] ?? null;
                $accommodation_under_age->under_age_fee_per_week = $under_age_fee_per_week[$i] ?? null;
                $accommodation_under_age->save();
                $accommodation_under_age_ids[] = $accommodation_under_age->id;
            }
            $program_under_age_fees = CourseAccommodationUnderAge::where('accom_id', $accom_id)->get();
            foreach ($program_under_age_fees as $program_under_age_fee) {
                if (!in_array($program_under_age_fee->id, $accommodation_under_age_ids)) {
                    $program_under_age_fee->delete();
                }
            }

            return true;
        });
    }

    /**
     * @param Request $request
     * @param null $id
     * @return mixed
     */
    public function updateOtherServiceFee(Request $request, $id=null)
    {
        return \DB::transaction(function () use ($request, $id) {
            $rules = [
                'airport_service_provider.*' => 'required',
                'airport_service_provider_ar.*' => 'required',
                'airport_note.*' => 'required',
                'airport_note_ar.*' => 'required',
                // 'medical_company_name.*' => 'required',
                // 'medical_deductible.*' => 'required',
                // 'medical_note.*' => 'required',
            ];

            /*
             * Validation Rules Starts here
             * */
            $validation = \Validator::make($request->all(), $rules, [
                'airport_service_provider.*.required' => "Airport Service Provider in English required",
                'airport_service_provider_ar.*.required' => "Airport Service Provider in Arabic required",
                'airport_note.*.required' => "Airport Note in English required",
                'airport_note_ar.*.required' => "Airport Note in Arabic required",
                // 'medical_company_name.*.required' => "Medical Company Name required",
                // 'medical_deductible.*.required' => "Medical Deductible required",
                // 'medical_note.*.required' => "Medical Note required",
            ]);

            if ($validation->fails()) {
                return $this->get_error = $validation->errors();
            }

            $course_airport_ids = [];
            for ($i = 0; $i <= $request->airportincrement; $i++) {
                $course_airport = null;
                if (isset($request->airport_id[$i]) && $request->airport_id[$i]) {
                    $course_airport = CourseAirport::where('unique_id', $request->airport_id[$i])->first();
                }
                if (!$course_airport) {
                    $course_airport = new CourseAirport;
                    $course_airport->course_unique_id = $id;
                }
                $course_airport->service_provider = $request->airport_service_provider[$i] ?? null;
                $course_airport->service_provider_ar = $request->airport_service_provider_ar[$i] ?? null;
                $course_airport->week_selected_fee = $request->airport_week_selected_fee[$i] ?? null;
                $course_airport->note = $request->airport_note[$i] ?? null;
                $course_airport->note_ar = $request->airport_note_ar[$i] ?? null;
                $course_airport->order = $i;
                $course_airport->save();
                if (!$course_airport->unique_id) {
                    $course_airport_ids[] = CourseAirport::orderBy('unique_id')->last()->unique_id;
                } else {
                    $course_airport_ids[] = $course_airport->unique_id;
                }

                $course_airport_fee_ids = [];
                for ($j = 0; $j <= $request->airportfeeincrement[$i]; $j++) {
                    if (isset($request->airport_name[$i][$j]) && ($request->airport_name[$i][$j] || $request->airport_name_ar[$i][$j]) && ($request->airport_service_name[$i][$j] || $request->airport_service_name_ar[$i][$j])) {
                        $course_airport_fee = null;
                        if (isset($request->airport_fee_id[$i][$j]) && $request->airport_fee_id[$i][$j]) {
                            $course_airport_fee = CourseAirportFee::where('unique_id', $request->airport_fee_id[$i][$j])->first();
                        }
                        if (!$course_airport_fee) {
                            $course_airport_fee = new CourseAirportFee;
                            $course_airport_fee->course_airport_unique_id = $course_airport->unique_id;
                        }
                        $course_airport_fee->name = $request->airport_name[$i][$j] ?? null;
                        $course_airport_fee->name_ar = $request->airport_name_ar[$i][$j] ?? null;
                        $course_airport_fee->service_name = $request->airport_service_name[$i][$j] ?? null;
                        $course_airport_fee->service_name_ar = $request->airport_service_name_ar[$i][$j] ?? null;
                        $course_airport_fee->service_fee = (double)$request->airport_service_fee[$i][$j] ?? null;
                        $course_airport_fee->save();
                        if (!$course_airport_fee->unique_id) {
                            $course_airport_fee_ids[] = CourseAirportFee::orderBy('unique_id')->last()->unique_id;
                        } else {
                            $course_airport_fee_ids[] = $course_airport_fee->unique_id;
                        }
                    }
                }
                $course_airport_fees = CourseAirportFee::where('course_airport_unique_id', $course_airport->unique_id)->get();
                foreach ($course_airport_fees as $course_airport_fee) {
                    if (!in_array($course_airport_fee->unique_id, $course_airport_fee_ids)) {
                        $course_airport_fee->delete();
                    }
                }
            }
            $course_airports = CourseAirport::where('course_unique_id', $id)->get();
            foreach ($course_airports as $course_airport) {
                if (!in_array($course_airport->unique_id, $course_airport_ids)) {
                    $course_airport->delete();
                }
            }

            $course_medical_ids = [];
            for ($k = 0; $k <= $request->medicalincrement; $k++) {
                $medical_fee_count = 0;
                for ($l = 0; $l <= $request->medicalfeeincrement[$k]; $l++) {
                    if (isset($request->medical_fees_per_week[$k][$l]) && $request->medical_fees_per_week[$k][$l]) {
                        $medical_fee_count = $medical_fee_count + 1;
                    }
                }
                
                if ($medical_fee_count && isset($request->medical_company_name[$k]) && ($request->medical_company_name[$k] || $request->medical_company_name_ar[$k] || $request->medical_deductible[$k] || $request->medical_week_selected_fee[$k])) {
                    $course_medical = null;
                    if (isset($request->medical_id[$k]) && $request->medical_id[$k]) {
                        $course_medical = CourseMedical::where('unique_id', $request->medical_id[$k])->first();
                    }
                    if (!$course_medical) {
                        $course_medical = new CourseMedical;
                        $course_medical->course_unique_id = $id;
                    }
                    $course_medical->company_name = $request->medical_company_name[$k] ?? null;
                    $course_medical->company_name_ar = $request->medical_company_name_ar[$k] ?? null;
                    $course_medical->deductible = $request->medical_deductible[$k] ?? null;
                    $course_medical->week_selected_fee = $request->medical_week_selected_fee[$k] ?? null;
                    $course_medical->note = $request->medical_note[$k] ?? null;
                    $course_medical->note_ar = $request->medical_note_ar[$k] ?? null;
                    $course_medical->order = $k;
                    $course_medical->save();
                    if (!$course_medical->unique_id) {
                        $course_medical_ids[] = CourseMedical::orderBy('unique_id')->last()->unique_id;
                    } else {
                        $course_medical_ids[] = $course_medical->unique_id;
                    }

                    $course_medical_fee_ids = [];
                    for ($l = 0; $l <= $request->medicalfeeincrement[$k]; $l++) {
                        if (isset($request->medical_fees_per_week[$k][$l]) && $request->medical_fees_per_week[$k][$l]) {
                            $course_medical_fee = null;
                            if (isset($request->medical_fee_id[$k][$l]) && $request->medical_fee_id[$k][$l]) {
                                $course_medical_fee = CourseMedicalFee::where('unique_id', $request->medical_fee_id[$k][$l])->first();
                            }
                            if (!$course_medical_fee) {
                                $course_medical_fee = new CourseMedicalFee;
                                $course_medical_fee->course_medical_unique_id = $course_medical->unique_id;
                            }
                            $course_medical_fee->fees_per_week = (double)$request->medical_fees_per_week[$k][$l] ?? null;
                            $course_medical_fee->start_date = $request->medical_start_date[$k][$l] ?? null;
                            $course_medical_fee->end_date = $request->medical_end_date[$k][$l] ?? null;
                            $course_medical_fee->save();
                            if (!$course_medical_fee->unique_id) {
                                $course_medical_fee_ids[] = CourseMedicalFee::orderBy('unique_id')->last()->unique_id;
                            } else {
                                $course_medical_fee_ids[] = $course_medical_fee->unique_id;
                            }
                        }
                    }
                    $course_medical_fees = CourseMedicalFee::where('course_medical_unique_id', $course_medical->unique_id)->get();
                    foreach ($course_medical_fees as $course_medical_fee) {
                        if (!in_array($course_medical_fee->unique_id, $course_medical_fee_ids)) {
                            $course_medical_fee->delete();
                        }
                    }
                }
            }
            $course_medicals = CourseMedical::where('course_unique_id', $id)->get();
            foreach ($course_medicals as $course_medical) {
                if (!in_array($course_medical->unique_id, $course_medical_ids)) {
                    $course_medical->delete();
                }
            }

            $course_custodian_ids = [];
            for ($m = 0; $m <= $request->custodianincrement; $m++) {
                if (isset($request->custodian_fee[$m]) && $request->custodian_fee[$m]) {
                    $course_custodian = null;
                    if (isset($request->custodian_id[$m]) && $request->custodian_id[$m]) {
                        $course_custodian = CourseCustodian::where('unique_id', $request->custodian_id[$m])->first();
                    }
                    if (!$course_custodian) {
                        $course_custodian = new CourseCustodian;
                        $course_custodian->course_unique_id = $id;
                    }
                    $course_custodian->fee = $request->custodian_fee[$m] ?? null;
                    $course_custodian->age_range = $request->custodian_age_range[$m] ?? null;
                    $course_custodian->condition = $request->custodian_condition[$m] ?? null;
                    $course_custodian->order = $m;
                    $course_custodian->save();
                    if (!$course_custodian->unique_id) {
                        $course_custodian_ids[] = CourseCustodian::orderBy('unique_id')->last()->unique_id;
                    } else {
                        $course_custodian_ids[] = $course_custodian->unique_id;
                    }
                }
            }
            $course_custodians = CourseCustodian::where('course_unique_id', $id)->get();
            foreach ($course_custodians as $course_custodian) {
                if (!in_array($course_custodian->unique_id, $course_custodian_ids)) {
                    $course_custodian->delete();
                }
            }

            return true;
        });
    }
}