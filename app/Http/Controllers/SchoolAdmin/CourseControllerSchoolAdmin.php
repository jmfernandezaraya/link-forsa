<?php

namespace App\Http\Controllers\SchoolAdmin;
use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\Accommodation;
use App\Models\SuperAdmin\CourseAirport;
use App\Models\SuperAdmin\Choose_Language;
use App\Models\SuperAdmin\CourseProgram;
use App\Models\SuperAdmin\CourseProgramUnderAgeFee;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SuperAdmin\Course;
use App\Models\SuperAdmin\School;
use Storage;

class CourseControllerSchoolAdmin extends Controller
{
    function index()
    {
        $schools  = School::whereId(auth()->user()->userSchool->school_id)->with('userSchool')->with('courses')->first();

        return view('schooladmin.courses.index' , compact('schools') );
    }

    // Function to get all city, country and branch with school id
    function school_get_allrequest(Request $r)
    {
        $school = new School;
        $school->setTable('schools_' . get_language());
        $school = $school->whereUniqueId($r->id)->select('country', 'city', 'branch_name')->first();

        $value = [];
        $data['branch_name'] = '';
        foreach ($school->branch_name as $branches) {
            $data['branch_name'] .= $branches;
            array_push($value, $branches);
        }

        $data['branch'] = $value;
        $data['country'] = "<option value = '$school->country'>$school->country</option>";
        $data['city'] = "<option value ='$school->city'>$school->city</option>";

        return response($data);
    }

    function create()
    {
        $schools = new School;
        $schools->setTable('schools_' . get_language());
        $schools = $schools->get();
        $choose_languages = Choose_Language::all();
        \Session::has('program_unique_id') ? \Session::forget('program_unique_id') : '';
        return view('schooladmin.courses.add', compact('schools', 'choose_languages'));
    }

    function store(Request $r)
    {
        extract($r->all());

        $course_id = 1615713555;

        for($count=0; $count <= $program_increment; $count++) {
            $program = new CourseProgram;
            $program->course_unique_id = $course_id;

            \Session::push('program_unique_id', $program_id[$count]);

            $program->unique_id =$program_id[$count];
            $program->program_cost = $r->program_cost[$count];

            $program->program_cost = $r->program_cost[$count];
            $program->program_duration_start = $r->program_duration_start[$count];
            $program->program_duration_end = $r->program_duration_end[$count];
            $program->program_start_date = $r->program_start_date[$count];
            $program->program_end_date = $r->program_end_date[$count];
            $program->discount_per_week = $r->discount_per_week[$count];
            $program->discount_start_date = $r->discount_start_date[$count];
            $program->discount_end_date = $r->discount_end_date[$count];
            $program->x_week_selected = $r->x_week_selected[$count];
            $program->x_week_start_date = $r->x_week_start_date[$count];
            //$program->how_many_week_free = $r->how_many_week_free[$count];
            $program->summer_fee_per_week = $r->program_summer_fee_per_week[$count];
            $program->summer_fee_start_date = $r->program_summer_fee_start_date[$count];
            $program->summer_fee_end_date = $r->program_summer_fee_end_date[$count];
            $program->peak_time_fee_per_week = $r->program_peak_time_fee_per_week[$count];

            $program->peak_time_start_date = $r->program_peak_time_start_date[$count];
            $program->peak_time_end_date = $r->program_peak_time_end_date[$count];

            /*$program->text_book_fee = $r->text_book_fee[$count];
            $program->text_fee_start_week = $r->text_book_fee_start_date[$count];
            $program->text_fee_end_week = $r->text_book_fee_end_date[$count];
            $program->text_book_note = $r->text_book_note[0];*/

            $program->save();

            //dd($)
            /*$courseunderage = new CourseProgramUnderAgeFee;
            $courseunderage->under_age = $r->under_age;
            $courseunderage->underage_fee_per_week = $r->underage_fee_per_week[0];*/
        }
        $data['data'] = 'Data Saved Successfully';

        return response()->json($data);

        $dd  = '';
        $course_id = 1615713555;
        for($count = 0; $count < $r->program_increment; $count++)
        {
            $program = new CourseProgram;
            $program->course_unique_id = $course_id;
            $program->unique_id =rand(0000, 9999) . $count;
            $program->program_cost = $r->program_cost[$count];

            $program->program_cost = $r->program_cost[$count];
            $program->program_duration_start = $r->program_duration_start[$count];
            $program->program_duration_end = $r->program_duration_end[$count];
            $program->program_start_date = $r->program_start_date[$count];
            $program->program_end_date = $r->program_end_date[$count];
            $program->discount_per_week = $r->discount_per_week[$count];
            $program->discount_start_date = $r->discount_start_date[$count];
            $program->discount_end_date = $r->discount_end_date[$count];
            $program->x_week_selected = $r->x_week_selected[$count];
            $program->x_week_start_date = $r->x_week_start_date[$count];
            $program->how_many_week_free = $r->how_many_week_free[$count];
            $program->summer_fee_per_week = $r->program_summer_fee_per_week[$count];
            $program->summer_fee_start_date = $r->program_summer_fee_start_date[$count];
            $program->summer_fee_end_date = $r->program_summer_fee_end_date[$count];
            $program->peak_time_fee_per_week = $r->program_peak_time_fee_per_week[$count];

            $program->peak_time_start_date = $r->program_peak_time_start_date[$count];
            $program->peak_time_end_date = $r->program_peak_time_end_date[$count];

            $program->text_book_fee = $r->text_book_fee[$count];
            $program->text_fee_start_week = $r->text_book_fee_start_date[$count];
            $program->text_fee_end_week = $r->text_book_fee_end_date[$count];
            $program->text_book_note = $r->text_book_note[0];
            $courseunderage = new CourseProgramUnderAgeFee;
            $courseunderage->under_age = $r->under_age;
            $courseunderage->underage_fee_per_week = $r->underage_fee_per_week[0];
            $program->save();
            $program->courseUnderAge()->save($courseunderage);
        }

        $data['request'] = $under_age;
        $unique_id = $this->my_unique_id();
        $courses = new Course;
        $courses = $courses->setTable('courses_en');
        $courses->unique_id = $unique_id;
        $courses->language = $language;
        $courses->branch = $branch;
        $courses->program_type = $program_type;
        $courses->study_mode = $study_mode;
        $courses->school_id = $school_id;
        $courses->currency = $currency;
        $courses->program_name = $program_name;
        $courses->lessons_per_week = $lessons_per_week;
        $courses->program_level = $program_level;
        $courses->hours_per_week = $hours_per_week;
        $courses->study_time = $study_time;
        $courses->every_day = $every_day;
        $courses->about_program = $about_program;
        $courses->program_registration_fee = $program_registration_fee;
        $courses->program_duration = $program_duration;
        $courses->age_range = $age_range;
        $courses->courier_fee = $courier_fee;
        $courses->about_courier = $about_courier;

        $accom_unique_id = $unique_id + 1;

        $accomodation = new Accommodation;
        $accomodation->setTable('course_accommodations_en');
        $accomodation->unique_id = $accom_unique_id;
        $accomodation->course_unique_id = $unique_id;
        $accomodation->type = $type;
        $accomodation->room_type = $room_type;
        $accomodation->meal = $meal;
        $accomodation->age_range = $age_range;
        $accomodation->placement_fee = $placement_fee;
        $accomodation->program_duration = $program_duration;
        $accomodation->deposit_fee = $deposit_fee;
        $accomodation->custodian_fee = $custodian_fee;
        $accomodation->custodian_age_range = $age_range_for_custodian;
        $accomodation->special_diet_fee = $special_diet_fee;
        $accomodation->special_diet_note = $special_diet_note;
        $accomodation->fee_per_week = $fee_per_week;
        $accomodation->start_week = $start_week;
        $accomodation->end_week = $end_week;
        $accomodation->start_date = $start_date;
        $accomodation->end_date = $end_date;
        $accomodation->peak_time_fee_start_date = $accomodation_peak_time_fee_start_date;
        $accomodation->peak_time_fee_end_date = $accomodation_peak_time_fee_end_date;
        $accomodation->christmas_fee_per_week = $christmas_fee_per_week;
        $accomodation->christmas_fee_start_date = $christmas_fee_start_date;
        $accomodation->christmas_fee_end_date = $christmas_fee_end_date;
        $accomodation->under_age = $under_age;
        $accomodation->under_age_fee_per_week = $under_age_fee_per_week;
        $accomodation->discount_per_week = $discount_per_week . " " . $discount_per_week_symbol;
        $accomodation->discount_per_week_symbol = $discount_per_week_symbol;
        $accomodation->discount_start_date = $discount_start_date;
        $accomodation->discount_end_date = $discount_end_date;
        $accomodation->summer_fee_per_week = $summer_fee_per_week;
        $accomodation->summer_fee_start_date = $summer_fee_start_date;
        $accomodation->summer_fee_end_date = $summer_fee_end_date;
        $accomodation->peak_time_fee_per_week = $accomodation_peak_time_fee_per_week;

        $airport_unique_id = $unique_id + 3;

        $airport = new CourseAirport;
        $airport = $airport->setTable('course_airport_fees');
        $airport->course_unique_id = $unique_id;
        $airport->unique_id = $airport_unique_id;
        $airport->airport_name = $airport_name;
        $airport->airport_service_name = $airport_service_name;
        $airport->service_fee = $service_fee;
        $airport->week_selected_fee = $week_selected_fee;
        $airport->fees_per_week = $medical_fees_per_week;
        $airport->insurance_duration = $medical_insurance_duration;
        $airport->medical_insurance_note = $medical_insurance_note;

        $program = new CourseProgram;
        $program->setTable('courses_program_' . get_language());
        $program->course_unique_id = $unique_id;
        $program->unique_id = $unique_id + 66;

        $program->program_cost = $program_cost;
        $program->program_duration_start = $program_duration_start;
        $program->program_duration_end = $program_duration_end;
        $program->program_start_date = $program_start_date;
        $program->program_end_date = $program_end_date;
        $program->discount_per_week = $discount_per_week . " " . $discount_per_week_symbol;
        $program->discount_start_date = $discount_start_date;
        $program->discount_end_date = $discount_end_date;
        $program->x_week_selected = $x_week_selected;
        $program->x_week_end_date = $x_week_end_date;
        $program->x_week_start_date = $x_week_start_date;
        $program->how_many_week_free = $how_many_week_free;
        $program->summer_fee_per_week = $program_summer_fee_per_week;
        $program->summer_fee_start_date = $program_summer_fee_start_date;
        $program->summer_fee_end_date = $program_summer_fee_end_date;
        $program->peak_time_fee_per_week = $program_peak_time_fee_per_week;
        $program->peak_time_start_date = $program_peak_time_start_date;
        $program->peak_time_end_date = $program_peak_time_end_date;

        $program->text_book_fee = $text_book_fee;
        $program->text_fee_start_week = $text_book_fee_start_date;
        $program->text_fee_end_week = $text_book_fee_end_date;
        $program->text_book_note = $text_book_note;

        $this->my_unique_id(1);
        $data['success'] = true;

        \DB::transaction(function () use ($courses, $airport, $accomodation, $program, $under_age, $underage_fee_per_week) {
            $courses->save();
            $airport->save();
            $accomodation->save();
            $program->save();
            $courseunderage = new CourseProgramUnderAgeFee;
            $courseunderage->under_age = $under_age;
            $courseunderage->underage_fee_per_week = $underage_fee_per_week;
            $program->courseUnderAge()->save($courseunderage);

        });
        /*} catch (\Exception $e) {
            $data['catch_error'] = $e->getMessage();
            // $data['all']  =$r->all();

        }*/
        $data['data'] = 'Data Saved Successfully';
        return response()->json($data);
        //$this->validate($r, $rules);
    }

    function delete($id)
    {
        if (User::find($id)->delete()) {
            toastr()->success('Deleted Successfully');
            return back();
        }
    }

    function edit($id)
    {
        $school = User::find($id)->first();
        return view('schooladmin.school_admin.edit', compact('school'));
    }

    function update(Request $r, $id)
    {
        $rules = [
            'email' => 'required',
            'first_name' => 'required',
        ];

        $this->validate($r, $rules);

        $input = $r->except('_token');
        $school_update = User::whereId($id)->first();
        $file = $school_update->image;

        $input['image'] = $file;
        $lastname = $r->has("last_name") && $r->last_name != null ? $r->last_name : '';
        $first_name = array($r->first_name, $lastname);
        $name = implode(", ", $first_name);

        $input['name'] = $name;
        if (isset($r->image) && $r->image != null) {
            Storage::disk('local')->delete('public/school_admin_images/' . $file);
            $image_parts = explode(";base64,", $r->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file = time() . "." . $image_type;

            Storage::disk('local')->put('public\school_admin_images\\' . $file, $image_base64);
            $input['image'] = $file;
        }

        if ($r->has('password'))
            $input['password'] = \Hash::make($r->password);

        $save = $school_update->fill($input)->save();
        if ($save) {
            toastr()->success('Data has been updated successfully!');
            return redirect()->route('school_admin.index');
        }
    }


    public function programSessionSave(Request $request){

        \Session::push('program_cost_save', $request->all());
        $session = \Session::get('program_cost_save');
        return response($session);
    }
}