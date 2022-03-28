<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\CourseProgramTextBook;

use App\Models\SuperAdmin\Course;
use App\Models\SuperAdmin\School;
use App\Models\SuperAdmin\Accommodation;
use App\Models\SuperAdmin\CourseAirport;
use App\Models\SuperAdmin\CourseProgram;
use App\Models\SuperAdmin\CourseMedical;
use App\Models\SuperAdmin\CourseProgramUnderAgeFee;
use App\Models\SuperAdmin\CourseAccommodationUnderAge;

use App\Models\SuperAdmin\Choose_Accommodation_Age_Range;
use App\Models\SuperAdmin\Choose_Accommodation_Under_Age;
use App\Models\SuperAdmin\Choose_Branch;
use App\Models\SuperAdmin\Choose_Classes_Day;
use App\Models\SuperAdmin\Choose_Custodian_Under_Age;
use App\Models\SuperAdmin\Choose_Language;
use App\Models\SuperAdmin\Choose_Program_Age_Range;
use App\Models\SuperAdmin\Choose_Program_Type;
use App\Models\SuperAdmin\Choose_Program_Under_Age;
use App\Models\SuperAdmin\Choose_Start_Day;
use App\Models\SuperAdmin\Choose_Study_Mode;
use App\Models\SuperAdmin\Choose_Study_Time;

use App\Models\SuperAdmin\CurrencyExchangeRate;

use App\Services\CourseCreateService;

use Illuminate\Http\Request;

use Session;

class CourseController extends Controller
{
    function _getChooseFields($courses)
    {
        $choose_fields = [
            'languages' => [],
            'program_types' => [],
            'study_modes' => [],
            'school_names' => [],
            'school_cities' => [],
            'school_countries' => [],
            'branch_names' => [],
            'currencies' => []
        ];
        foreach($courses as $course) {
            if (is_null($course->language)) {
                if (!in_array('-', $choose_fields['languages'])) {
                    array_push($choose_fields['languages'], '-');
                }
            } else {
                if (is_array($course->language)) {
                    $choose_fields['languages'] = array_unique(array_merge($choose_fields['languages'], $course->language));
                } else {
                    if (!in_array($course->language, $choose_fields['languages'])) {
                        array_push($choose_fields['languages'], $course->language);
                    }
                }
            }

            if (is_null($course->program_type)) {
                if (!in_array('-', $choose_fields['program_types'])) {
                    array_push($choose_fields['program_types'], '-');
                }
            } else {
                if (is_array($course->program_type)) {
                    $choose_fields['program_types'] = array_unique(array_merge($choose_fields['program_types'], $course->program_type));
                } else {
                    if (!in_array($course->program_type, $choose_fields['program_types'])) {
                        array_push($choose_fields['program_types'], $course->program_type);
                    }
                }
            }

            if (is_null($course->study_mode)) {
                if (!in_array('-', $choose_fields['study_modes'])) {
                    array_push($choose_fields['study_modes'], '-');
                }
            } else {
                if (is_array($course->study_mode)) {
                    $choose_fields['study_modes'] = array_unique(array_merge($choose_fields['study_modes'], $course->study_mode));
                } else {
                    if (!in_array($course->study_mode, $choose_fields['study_modes'])) {
                        array_push($choose_fields['study_modes'], $course->study_mode);
                    }
                }
            }

            if (get_language() == 'en') {
                if ($course->school->name) {
                    if (!in_array($course->school->name, $choose_fields['school_names'])) {
                        array_push($choose_fields['school_names'], $course->school->name);
                    }
                } else {
                    if (!in_array('-', $choose_fields['school_names'])) {
                        array_push($choose_fields['school_names'], '-');
                    }
                }
            } else {
                if ($course->school->name_ar) {
                    if (!in_array($course->school->name_ar, $choose_fields['school_names'])) {
                        array_push($choose_fields['school_names'], $course->school->name_ar);
                    }
                } else {
                    if (!in_array('-', $choose_fields['school_names'])) {
                        array_push($choose_fields['school_names'], '-');
                    }
                }
            }

            if ($course->city) {
                if (!in_array($course->city, $choose_fields['school_cities'])) {
                    array_push($choose_fields['school_cities'], $course->city);
                }
            } else {
                if (!in_array('-', $choose_fields['school_cities'])) {
                    array_push($choose_fields['school_cities'], '-');
                }
            }

            if ($course->country) {
                if (!in_array($course->country, $choose_fields['school_countries'])) {
                    array_push($choose_fields['school_countries'], $course->country);
                }
            } else {
                if (!in_array('-', $choose_fields['school_countries'])) {
                    array_push($choose_fields['school_countries'], '-');
                }
            }

            if (is_null($course->branch)) {
                if (!in_array('-', $choose_fields['branch_names'])) {
                    array_push($choose_fields['branch_names'], '-');
                }
            } else {
                if (is_array($course->branch)) {
                    $choose_fields['branch_names'] = array_unique(array_merge($choose_fields['branch_names'], $course->branch));
                } else {
                    if (!in_array($course->branch, $choose_fields['branch_names'])) {
                        array_push($choose_fields['branch_names'], $course->branch);
                    }
                }
            }

            if ($course->getCurrency) {
                if (!in_array($course->getCurrency->name, $choose_fields['currencies'])) {
                    array_push($choose_fields['currencies'], $course->getCurrency->name);
                }
            } else {
                if (!in_array('-', $choose_fields['currencies'])) {
                    array_push($choose_fields['currencies'], '-');
                }
            }
        }
        $choose_fields['languages'] = Choose_Language::whereIn('unique_id', $choose_fields['languages'])->pluck('name')->toArray();
        $choose_fields['program_types'] = Choose_Program_Type::whereIn('unique_id', $choose_fields['program_types'])->pluck('name')->toArray();
        $choose_fields['study_modes'] = Choose_Study_Mode::whereIn('unique_id', $choose_fields['study_modes'])->pluck('name')->toArray();
        $choose_fields['branch_names'] = Choose_Branch::whereIn('unique_id', $choose_fields['branch_names'])->pluck('name')->toArray();
        
        return $choose_fields;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function index()
    {
        $courses = Course::with('school')->where('deleted', false)->get();
        $choose_fields = self::_getChooseFields($courses);

        return view('superadmin.courses.index', compact('courses', 'choose_fields'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function deleted()
    {
        $courses = Course::with('school')->where('deleted', true)->get();
        $choose_fields = self::_getChooseFields($courses);

        return view('superadmin.courses.deleted', compact('courses', 'choose_fields'));
    }

    // Function to get all city, country and branch with school id

    /**
     * @param Request $r
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    function school_get_allrequest(Request $r)
    {
        $select = __('SuperAdmin/backend.select_option');
        $school = School::find($r->id);
        $value = [];
        $data['branch_name'] = "<option value = ''>$select</option>";

        foreach ($school->getCityCountryState()->getBranch() as $branches) {
            $data['branch_name'] .= $branches;
            array_push($value, $branches);
        }

        $data['branch'] = $value;
        $data['country']  = "<option value = ''>$select</option>";
        foreach ($school->getCityCountryState()->getCountry() as $country) {
            $data['country'] .= "<option value = '$country'>$country</option>";
        }
        $data['city'] = "<option value = ''>$select</option>";
        foreach ($school->getCityCountryState()->getCity() as $city) {
            $data['city'] .= "<option value ='$city'>$city</option>";
        }

        return response($data);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function create()
    {
        $schools = School::all()->unique('name')->values()->all();

        $choose_languages = Choose_Language::all();
        $choose_study_times = Choose_Study_Time::all();
        $choose_study_modes = Choose_Study_Mode::all();
        $choose_classes_days = Choose_Classes_Day::all();
        $choose_start_days = Choose_Start_Day::all();
        $choose_program_age_ranges = Choose_Program_Age_Range::orderBy('age', 'asc')->get();
        $choose_program_types = Choose_Program_Type::all();
        $choose_branches = Choose_Branch::all();
        $currencies = CurrencyExchangeRate::all();

        \Session::has('programs_id') ? \Session::forget('programs_id') : '';
        \Session::has('accom_ids') ? \Session::forget('accom_ids') : '';
        \Session::has('has_accommodation') ? \Session::forget('has_accommodation') : '';

        return view('superadmin.courses.add', compact('schools', 'choose_languages', 'choose_study_times', 'choose_study_modes',
            'choose_classes_days', 'choose_start_days', 'choose_program_age_ranges', 'choose_program_types', 'choose_branches',
            'currencies'));
    }

    /**
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    function store(Request $r)
    {
        try {
            $coursecreate = new CourseCreateService();
            if ($r->has('language')) {
                $coursecreate->createCourseAndProgram($r);
                if ($r->has('accommodation')) {
                    Session::put('has_accommodation', $r->accommodation);
                }
            } else if ($r->has('fees_under_age')) {
                $coursecreate->createProgramUnderAgeAndTextBook($r);
            } elseif ($r->has('type')) {
                $coursecreate->createAccommodation($r);
            } elseif ($r->has('accom_increment')) {
                $coursecreate->createAccommodationUnderAge($r);
            } elseif ($r->has('airportincrement')) {
                $coursecreate->createAirportMedicalFee($r);
            }

            $data['data'] = 'Data Not Saved';
            $data['success'] = 'failed';
            if ($coursecreate->getGetError() == '') {
                $data['data'] = 'Data Saved Successfully';
                $data['success'] = 'success';
            } else {
                $data['errors'] = $coursecreate->getGetError();
            }

            return response()->json($data);
        }
        catch(\Exception $e)
        {
            return response()->json(['catch_error' => $e->getMessage()]);
        }
    }

    /**
     * @param $course_id
     * @return \Illuminate\Http\RedirectResponse
     */
    function delete($course_id)
    {
        $db = \DB::transaction(function() use ($course_id) {
            $course = \App\Models\SuperAdmin\CourseUpdate\Course::where('unique_id', $course_id)->first();
            $course->deleted = true;
            $course->save();
            return true;
        });
        if($db){
            toastr()->success(__('SuperAdmin/backend.data_removed_successfully'));
        }
        return back();
    }

    /*
     * function couse edit
     *
     * @param $id
     *
     * @return view
     *
     *
     * */
    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function edit($id)
    {
        $schools = School::all();

        $choose_languages = Choose_Language::all();
        $choose_study_times = Choose_Study_Time::all();
        $choose_classes_days = Choose_Classes_Day::all();
        $choose_start_days = Choose_Start_Day::all();
        $choose_program_age_ranges = Choose_Program_Age_Range::orderBy('age', 'asc')->get();
        $choose_study_modes = Choose_Study_Mode::all();
        $choose_program_types = Choose_Program_Type::all();
        $choose_branches = Choose_Branch::all();
        $currencies = CurrencyExchangeRate::all();

        $courses = Course::whereUniqueId($id)->with('school')->with('coursePrograms')->first();

        return view('superadmin.courses.edit', compact('courses', 'schools', 'choose_languages', 'choose_study_times', 'choose_study_modes',
            'choose_classes_days', 'choose_start_days', 'choose_program_age_ranges', 'choose_program_types', 'choose_branches',
            'currencies'));
    }

    /**
     * @param Request $r
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    function update(Request $r, $id = null)
    {
        $coursecreate = new CourseCreateService();
        if ($r->has('language')) {
            $coursecreate->updateCourseAndProgram($r, $id);
        } elseif ($r->has('fees_under_age')) {
            $coursecreate->updateProgramUnderAgeAndTextBook($r);
        } elseif ($r->has('type')) {
            $coursecreate->updateAccommodation($r, $id);
        } elseif ($r->has('accom_increment')) {
            $coursecreate->updateAccommodationUnderAge($r, $id);
        } elseif ($r->has('airportincrement')) {
            $coursecreate->updateAirportMedicalFee($r, $id);
        }

        $data['data'] = 'Data Not Saved';
        $data['success'] = 'failed';
        if ($coursecreate->getGetError() == '') {
            $data['data'] = 'Data Saved Successfully';
            $data['success'] = 'success';
        } else {
            $data['errors'] = $coursecreate->getGetError();
        }

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function programSessionSave(Request $request)
    {
        \Session::push('program_cost_save', $request->all());
        $session = \Session::get('program_cost_save');
        return response($session);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulk(Request $r)
    {
        $request_action = $r->action;
        $request_ids = $r->ids;
        if ($request_ids) {
            $course_ids = explode(",", $request_ids);
    
            foreach ($course_ids as $course_id) {
                if ($course_id) {
                    if ($request_action == "clone") {
                        $this->clone($course_id);
                    } else if ($request_action == "play") {
                        $this->play($course_id);
                    } else if ($request_action == "pause") {
                        $this->pause($course_id);
                    } else if ($request_action == "delete") {
                        $this->delete($course_id);
                    } else if ($request_action == "restore") {
                        $this->restore($course_id);
                    } else if ($request_action == "destroy") {
                        $this->destroy($course_id);
                    }
                }
            }
        }
        return back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clone($course_id)
    {
        $coursecreate = new CourseCreateService();
        if($coursecreate->cloneCourse($course_id)) {
            toastr()->success(__('SuperAdmin/backend.data_removed_successfully'));
        }
        return back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pause($course_id)
    {
        $db = \DB::transaction(function() use ($course_id) {
            $course = \App\Models\SuperAdmin\CourseUpdate\Course::where('unique_id', $course_id)->first();
            if ($course) {
                $course->display = false;
                $course->save();
                return true;
            }
        });
        if($db){
            toastr()->success(__('SuperAdmin/backend.data_removed_successfully'));
        }
        return back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function play($course_id)
    {
        $db = \DB::transaction(function() use ($course_id) {
            $course = \App\Models\SuperAdmin\CourseUpdate\Course::where('unique_id', $course_id)->first();
            if ($course) {
                $course->display = true;
                $course->save();
                return true;
            }
        });
        if($db){
            toastr()->success(__('SuperAdmin/backend.data_removed_successfully'));
        }
        return back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($course_id)
    {
        $db = \DB::transaction(function() use ($course_id) {
            $course = \App\Models\SuperAdmin\CourseUpdate\Course::where('unique_id', $course_id)->first();
            if ($course) {
                $course->deleted = false;
                $course->save();
                return true;
            }
        });
        if($db){
            toastr()->success(__('SuperAdmin/backend.data_removed_successfully'));
        }
        return back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $db = \DB::transaction(function() use ($id) {
            $course = Course::where('unique_id', $id)->first();
            if ($course) {
                $course->coursePrograms()->delete();
                $course->accomodations()->delete();
                $course->airports()->delete();
                $course->medicals()->delete();
                Course::where('unique_id', $id)->delete();
                return true;
            }
        });
        if($db){
            toastr()->success(__('SuperAdmin/backend.data_removed_successfully'));
        }
        return back();
    }

    public function programUnderAge()
    {
        $course_programs = [];
        $course_programs = CourseProgram::whereIn('unique_id', is_array(\Session::get('programs_id')) ? \Session::get('programs_id') : [])->get();
        $program_under_ages = Choose_Program_Under_Age::orderBy('age', 'asc')->get()->collect()->unique('age')->values()->all();
        $has_accommodation = !\Session::has('has_accommodation') || (\Session::has('has_accommodation') && \Session::get('has_accommodation') == 'yes');

        return view('superadmin.courses.add.program_under_age', compact('course_programs','program_under_ages','has_accommodation'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function editProgramUnderAge()
    {
        if (\Session::get('course_id')) {
            $course_id = \Session::get('course_id');

            $program_under_age_fees_with_text_books = CourseProgramUnderAgeFee::whereIn('course_program_id', is_array(\Session::get('programs_id')) ? \Session::get('programs_id') : [])->with('cousreTextBooks')->get()->collect()->unique('course_program_id')->values()->all();
            $program_under_age_fee_with_text_book_first = CourseProgramUnderAgeFee::whereIn('course_program_id', is_array(\Session::get('programs_id')) ? \Session::get('programs_id') : [])->with('cousreTextBooks')->get()->collect()->unique('course_program_id')->values()->first();

            $program_under_ages = Choose_Program_Under_Age::orderBy('age', 'asc')->get()->collect()->unique('age')->values()->all();
            if (isset($program_under_age_fee_with_text_book_first->course_program_id)) {
                $under_age_fees = CourseProgramUnderAgeFee::where('course_program_id', $program_under_age_fee_with_text_book_first->course_program_id)->get();
                $text_book_fees = CourseProgramTextBook::where('program_id', $program_under_age_fee_with_text_book_first->course_program_id)->get();
                
                return view('superadmin.courses.edit.program_under_age', compact('course_id','program_under_ages','program_under_age_fees_with_text_books','program_under_age_fee_with_text_book_first','under_age_fees','text_book_fees'));
            } else {
                return redirect()->route('superadmin.course.accommodation.edit');
            }
        } else {            
            return redirect()->route('superadmin.course.program_under_age');
        }
    }

    public function accommodation()
    {
        $custodian_under_ages = Choose_Custodian_Under_Age::orderBy('age', 'asc')->get()->collect()->unique('age')->values()->all();
        $accommodation_age_ranges = Choose_Accommodation_Age_Range::orderBy('age', 'asc')->get()->collect()->unique('age')->values()->all();

        return view('superadmin.courses.add.accommodation', compact('custodian_under_ages','accommodation_age_ranges'));
    }
    

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function editAccommodation()
    {
        \Session::forget('accom_ids');

        if (\Session::get('course_id')) {
            $course_id = \Session::get('course_id');
            
            $accomodations = Accommodation::where('course_unique_id', '' . \Session::get('course_id'))->get();
            if (isset($accomodations)) {
                foreach($accomodations as $accom) {
                    \Session::push('accom_ids', $accom->unique_id);
                }
                
                $custodian_under_ages = Choose_Custodian_Under_Age::orderBy('age', 'asc')->get()->collect()->unique('age')->values()->all();
                $accommodation_age_ranges = Choose_Accommodation_Age_Range::orderBy('age', 'asc')->get()->collect()->unique('age')->values()->all();
                
                return view('superadmin.courses.edit.accommodation', compact('course_id','accomodations','custodian_under_ages','accommodation_age_ranges'));
            } else {
                return redirect()->route('superadmin.course.accomm_under_age.edit');
            }
        } else {
            return redirect()->route('superadmin.course.accommodation');
        }
    }

    public function accommodationUnderAge()
    {
        $accomodations = Accommodation::whereIn('unique_id', \Session::get('accom_ids'))->get();
        $accomodation_under_ages = Choose_Accommodation_Under_Age::orderBy('age', 'asc')->get()->collect()->unique('age')->values()->all();
        return view('superadmin.courses.add.accommodation_under_age', compact('accomodations','accomodation_under_ages'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editAccommodationUnderAge()
    {
        if(!\Session::has('accom_ids') && !\Session::has("accom_id")) {
            return back();
        }
        
        $accomodation_under_ages = CourseAccommodationUnderAge::whereIn('accom_id', \Session::get('accom_ids'))->get()->collect()->unique('accom_id')->values()->all();
        if(empty($accomodation_under_ages)) {
            return redirect()->route('superadmin.course.airport_medical.edit');
        }

        if(!\Session::has('accom_ids')) {
            $accomodation_under_age = CourseAccommodationUnderAge::whereIn('accom_id', \Session::get('accom_ids'))->get()->collect()->unique('course_program_id')->values()->first();
            if ($accomodation_under_age) {
                $accomodation_under_all_ages = CourseAccommodationUnderAge::where('accom_id', $accomodation_under_age->accom_id)->get();
            }
        }
        if(!\Session::has('accom_id')) {
            $accomodation_under_age = CourseAccommodationUnderAge::where('accom_id', \Session::get('accom_id'))->get()->collect()->unique('course_program_id')->values()->first();
            if ($accomodation_under_age) {
                $accomodation_under_all_ages = CourseAccommodationUnderAge::where('accom_id', $accomodation_under_age->accom_id)->get();
            }
        }
        if (!empty($accomodation_under_age)) {
            $choose_accomodation_under_ages = Choose_Accommodation_Under_Age::orderBy('age', 'asc')->get()->collect()->unique('age')->values()->all();
    
            return view('superadmin.courses.edit.accommodation_under_age', compact('accomodation_under_ages','accomodation_under_age','accomodation_under_all_ages','choose_accomodation_under_ages'));
        } else {
            return redirect()->route('superadmin.course.airport_medical.edit');
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function airportMedical()
    {
        return view('superadmin.courses.add.airport_medical');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editAirportMedical()
    {
        $course_id = '' . \Session::get('course_id');
        $airports = CourseAirport::with('fees')->where('course_unique_id', $course_id)->get();
        $medicals = CourseMedical::with('fees')->where('course_unique_id', $course_id)->get();

        if (!empty($course_id) && !empty($airports) && !empty($medicals)) {
            return view('superadmin.courses.edit.airport_medical', compact('course_id','airports','medicals'));
        } else {
            return redirect()->route('superadmin.courses.index');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function fetchProgramUnderAgePage(Request $request)
    {
        \Session::put('programs_id', $request->value);

        $data['url'] = route('superadmin.course.program_under_age.edit');

        return response($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function fetchAccommodationUnderAgePage(Request $request)
    {
        \Session::put('accoms_id', $request->value);

        $data['url'] = route('superadmin.course.accomm_under_age.edit');

        return response($data);
    }
}