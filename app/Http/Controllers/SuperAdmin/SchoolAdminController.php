<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Classes\StoreClass;

use App\Http\Controllers\Controller;

use App\Mail\AdminCreated;

use App\Models\City;
use App\Models\Country;
use App\Models\User;
use App\Models\SuperAdmin\School;
use App\Models\SuperAdmin\UserSchool;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use DB;
use Image;
use Storage;

class SchoolAdminController extends Controller
{
    function index()
    {
        $school_admins = User::where('user_type', 'school_admin')->orWhere('user_type', 'branch_admin')->get();

        return view('superadmin.school_admin.index', compact('school_admins'));
    }

    function create()
    {
        $schools = School::where('is_active', true)->get();
        $choose_branches = [];
        $choose_schools = [];
        foreach ($schools as $school) {
            if (app()->getLocale() == 'en') {
                if ($school->branch_name) {
                    $choose_branches[] = $school->branch_name;
                }
                if ($school->name && $school->name->name) {
                    $choose_schools[] = $school->name->name;
                }
            } else {
                if ($school->branch_name_ar) {
                    $choose_branches[] = $school->branch_name_ar;
                }
                if ($school->name && $school->name->name_ar) {
                    $choose_schools[] = $school->name->name_ar;
                }
            }
        }
        $choose_schools = array_unique($choose_schools);

        return view('superadmin.school_admin.add', compact('choose_schools', 'choose_branches'));
    }

    function store(Request $request)
    {
        $rules = [
            'first_name_en' => 'required',
            'first_name_ar' => 'required',
            'last_name_en' => 'required',
            'last_name_ar' => 'required',
            'password' => 'required',
            'school_name' => 'required',
            'email' => 'required|unique:users',
            'contact' => 'required',
            'image' => 'mimes:jpg,jpeg,png,bmp',
            'country' => 'sometimes',
            'city' => 'sometimes',
        ];

        $validator = \Validator::make($request->all(), $rules, [
            'first_name_en.required' => __('Admin/backend.errors.first_name_english'),
            'first_name_ar.required' => __('Admin/backend.errors.first_name_arabic'),
            'last_name_ar.required' => __('Admin/backend.errors.last_name_arabic'),
            'last_name_en.required' => __('Admin/backend.errors.last_name_english'),
            'password.required' => __('Admin/backend.errors.password_required'),
            'image.required' => __('Admin/backend.errors.image_required'),
            'contact.required' => __('Admin/backend.errors.contact_required'),
            'email.required' => __('Admin/backend.errors.email_required'),
            'image.mimes' => __('Admin/backend.errors.image_must_be_in'),
            'school_name.required' => 'School Name is Required']);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $requested_save = $validator->validated();
        unset($requested_save['password']);
        unset($requested_save['image']);
        unset($requested_save['school_name']);
        unset($requested_save['school_permission']);
        unset($requested_save['school_add']);
        unset($requested_save['school_edit']);
        unset($requested_save['course_permission']);
        unset($requested_save['course_add']);
        unset($requested_save['course_edit']);
        unset($requested_save['course_display']);
        unset($requested_save['course_delete']);
        unset($requested_save['course_application_permission']);
        unset($requested_save['course_application_edit']);
        unset($requested_save['course_application_chanage_status']);
        unset($requested_save['course_application_payment_refund']);
        unset($requested_save['course_application_contact_student']);
        unset($requested_save['course_application_contact_school']);

        $user_type = 'school_admin';
        if (!is_null($request->city) && !is_null($request->country) && !is_null($request->branch)) {
            // $user_type = "branch_admin";
        }
        $image_name = null;
        if ($request->has('image')) {
            $image = $request->file('image');

            $filename = time() . rand(00, 99) . \Str::random(10) . '.webp';
            Image::make($image)->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp', 90)->save(public_path('images/user_images/' . $filename));
            $image_name = 'public/images/user_images/' . $filename;
        }
        
        $language = app()->getLocale();
        $school_ids = School::where('is_active', true)->whereHas('name', function($query) use ($request, $language)
            { $language ? $query->where('name', $request->school_name) : $query->where('name_ar', $request->school_name); })->pluck('id')->toArray();
        $requested_save['school'] = $school_ids;
        if ($language == 'en') {
            $requested_save['branch'] = $request->branch ?? [];
            $requested_save['branch_ar'] = School::whereIn('branch_name', $request->branch ?? [])->pluck('branch_name_ar')->toArray();
        } else {
            $requested_save['branch_ar'] = $request->branch ?? [];
            $requested_save['branch'] = School::whereIn('branch_name_ar', $request->branch ?? [])->pluck('branch_name')->toArray();
        }
        \DB::transaction(function () use ($request, $requested_save, $image_name, $user_type) {
            if ($image_name) {
                $user = User::create($requested_save + ['user_type' => $user_type, 'image' => $image_name, 'password' => \Hash::make($request->password), 'email_verified_at' => Carbon::now()->toDate()]);
            } else {
                $user = User::create($requested_save + ['user_type' => $user_type, 'password' => \Hash::make($request->password), 'email_verified_at' => Carbon::now()->toDate()]);
            }
            if (can_manage_user() || can_permission_user()) {
                $user->permission()->create([
                    'school_manager' => $request->school_permission == 'manager',
                    'school_add' => ($request->school_permission == 'subscriber' && $request->school_add) ?? 0,
                    'school_edit' => ($request->school_permission == 'subscriber' && $request->school_edit) ?? 0,
                    'course_manager' => $request->course_permission == 'manager',
                    'course_add' => ($request->course_permission == 'subscriber' && $request->course_add) ?? 0,
                    'course_edit' => ($request->course_permission == 'subscriber' && $request->course_edit) ?? 0,
                    'course_display' => ($request->course_permission == 'subscriber' && $request->course_display) ?? 0,
                    'course_delete' => ($request->course_permission == 'subscriber' && $request->course_delete) ?? 0,
                    'course_application_manager' => $request->course_application_permission == 'manager',
                    'course_application_edit' => ($request->course_application_permission == 'subscriber' && $request->course_application_edit) ?? 0,
                    'course_application_chanage_status' => ($request->course_application_permission == 'subscriber' && $request->course_application_chanage_status) ?? 0,
                    'course_application_payment_refund' => ($request->course_application_permission == 'subscriber' && $request->course_application_payment_refund) ?? 0,
                    'course_application_contact_student' => ($request->course_application_permission == 'subscriber' && $request->course_application_contact_student) ?? 0,
                    'course_application_contact_school' => ($request->course_application_permission == 'subscriber' && $request->course_application_contact_school) ?? 0,
                    'review_manager' => $request->review_permission == 'manager',
                    'review_edit' => ($request->review_permission == 'subscriber' && $request->review_edit) ?? 0,
                    'review_delete' => ($request->review_permission == 'subscriber' && $request->review_delete) ?? 0,
                    'review_approve' => ($request->review_permission == 'subscriber' && $request->review_approve) ?? 0,
                    'enquiry_manager' => $request->enquiry_permission == 'manager',
                    'enquiry_add' => ($request->enquiry_permission == 'subscriber' && $request->enquiry_add) ?? 0,
                    'enquiry_edit' => ($request->enquiry_permission == 'subscriber' && $request->enquiry_edit) ?? 0,
                    'enquiry_delete' => ($request->enquiry_permission == 'subscriber' && $request->enquiry_delete) ?? 0,
                ]);
            }
            if ($user->school && is_array($user->school)) {
                foreach ($user->school as $user_school_id) {
                    $user_school = UserSchool::where('user_id', $user->id)->where('school_id', $user_school_id)->first();
                    if (!$user_school) {
                        $new_user_school = new UserSchool;
                        $new_user_school->user_id = $user->id;
                        $new_user_school->school_id = $user_school_id;
                        $new_user_school->save();
                    }
                }
            }
            $user_schools = UserSchool::where('user_id', $user->id)->get();
            foreach ($user_schools as $user_school) {
                if (!in_array($user_school->id, is_array($user->school) ? $user->school : [])) {
                    $user_school->delete();
                }
            }

            $mail_data['name'] = app()->getLocale() == 'en' ? $user->first_name_en . ' ' . $user->last_name_en : $user->first_name_ar . ' ' . $user->last_name_ar;
            $mail_data['email'] = $request->email;
            $mail_data['password'] = $request->password;
            $mail_data['dashbaord_link'] = route('schooladmin.dashboard');
            $mail_data['go_page'] = route('password.reset', ['token' => \Password::createToken($user)]) . '/?email=' . $user->email;
            \Mail::to($user->email)->send(new AdminCreated($mail_data));
        });

        $saved = __('Admin/backend.data_saved_successfully');
        return response()->json(['success' => true, 'data' => $saved]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    function destroy($id)
    {
        $deleted = User::find($id);
        if (file_exists($deleted->image)) {
            unlink($deleted->image);
        }

        $deleted = $deleted->delete();
        if ($deleted) {
            toastr()->success('Deleted Successfully');
            return back();
        }
    }

    function edit($id)
    {
        $school_admin = User::find($id);
        
        $schools = School::where('is_active', true)->get();
        $choose_schools = [];
        foreach ($schools as $school) {
            $school_country_ids[] = $school->country_id;
            $school_city_ids[] = $school->city_id;
            if (app()->getLocale() == 'en') {
                if ($school->name && $school->name->name) {
                    $choose_schools[] = $school->name->name;
                }
            } else {
                if ($school->name && $school->name->name_ar) {
                    $choose_schools[] = $school->name->name_ar;
                }
            }
        }
        $choose_schools = array_unique($choose_schools);
        $school_country_ids = [];
        $school_city_ids = [];
        $choose_branches = [];
        $school_name = '';
        $schools = School::where('is_active', true)->whereIn('id', $school_admin->school ?? [])->get();
        foreach ($schools as $school) {
            $school_country_ids[] = $school->country_id;
            $school_city_ids[] = $school->city_id;
            if (app()->getLocale() == 'en') {
                if ($school->name && $school->name->name) {
                    $school_name = $school->name->name;
                }
                if ($school->branch_name) {
                    $choose_branches[] = $school->branch_name;
                }
            } else {
                if ($school->name && $school->name->name) {
                    $school_name = $school->name->name;
                }
                if ($school->branch_name_ar) {
                    $choose_branches[] = $school->branch_name_ar;
                }
            }
        }
        $choose_countries = Country::whereIn('id', $school_country_ids)->orderBy('id', 'asc')->get();
        $choose_cities = City::whereIn('id', $school_city_ids)->orderBy('id', 'asc')->get();

        return view('superadmin.school_admin.edit', compact('school_admin', 'choose_schools', 'school_name', 'choose_countries', 'choose_cities', 'choose_branches'));
    }

    function update(Request $request, $id)
    {
        $user = User::find($id);
        
        $rules = [
            'first_name_en' => 'required',
            'first_name_ar' => 'required',
            'last_name_en' => 'required',
            'last_name_ar' => 'required',
            'email' => 'required',
            'contact' => 'required',
            'image' => 'mimes:jpg,jpeg,png,bmp',
            'school_name' => 'required',
            'country' => 'sometimes',
            'city' => 'sometimes',
        ];

        $validator = \Validator::make($request->all(), $rules, [
            'first_name_en.required' => __('Admin/backend.errors.first_name_english'),
            'first_name_ar.required' => __('Admin/backend.errors.first_name_arabic'),
            'last_name_ar.required' => __('Admin/backend.errors.last_name_arabic'),
            'last_name_en.required' => __('Admin/backend.errors.last_name_english'),
            'image.required' => __('Admin/backend.errors.image_required'),
            'contact.required' => __('Admin/backend.errors.contact_required'),
            'email.required' => __('Admin/backend.errors.email_required'),
            'image.mimes' => __('Admin/backend.errors.image_must_be_in'),
            'school_name.required' => 'School Name is Required']);

        $password = $request->has('password') ? \Hash::make($request->password) : null;
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        $requested_save = $validator->validated();
        unset($requested_save['password']);
        unset($requested_save['image']);
        unset($requested_save['school_name']);
        unset($requested_save['school_permission']);
        unset($requested_save['school_add']);
        unset($requested_save['school_edit']);
        unset($requested_save['course_permission']);
        unset($requested_save['course_add']);
        unset($requested_save['course_edit']);
        unset($requested_save['course_display']);
        unset($requested_save['course_delete']);
        unset($requested_save['course_application_permission']);
        unset($requested_save['course_application_edit']);
        unset($requested_save['course_application_chanage_status']);
        unset($requested_save['course_application_payment_refund']);
        unset($requested_save['course_application_contact_student']);
        unset($requested_save['course_application_contact_school']);

        $image_name = null;
        if ($request->has('image')) {
            $image = $request->file('image');

            $filename = time() . rand(00, 99) . \Str::random(10) . '.webp';
            Image::make($image)->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp', 90)->save(public_path('images/user_images/' . $filename));
            $image_name = 'public/images/user_images/' . $filename;
        }

        $language = app()->getLocale();
        $school_ids = School::where('is_active', true)->whereHas('name', function($query) use ($request, $language)
            { $language ? $query->where('name', $request->school_name) : $query->where('name_ar', $request->school_name); })->pluck('id')->toArray();
        $requested_save['school'] = $school_ids;
        if ($language == 'en') {
            $requested_save['branch'] = $request->branch ?? [];
            $requested_save['branch_ar'] = School::whereIn('branch_name', $request->branch ?? [])->pluck('branch_name_ar')->toArray();
        } else {
            $requested_save['branch_ar'] = $request->branch ?? [];
            $requested_save['branch'] = School::whereIn('branch_name_ar', $request->branch ?? [])->pluck('branch_name')->toArray();
        }

        if ($request->has('password')) {
            if ($image_name) {
                $user->fill($requested_save + ['user_type' => 'school_admin', 'image' => $image_name, 'password' => $password])->save();
            } else {
                $user->fill($requested_save + ['user_type' => 'school_admin', 'password' => $password])->save();
            }
        } else {
            if ($image_name) {
                $user->fill($requested_save + ['user_type' => 'school_admin', 'image' => $image_name])->save();
            } else {
                $user->fill($requested_save + ['user_type' => 'school_admin'])->save();
            }
        }
        if (can_manage_user() || can_permission_user()) {
            $user->permission()->updateOrCreate(['user_id' => $user->id], [
                'school_manager' => $request->school_permission == 'manager',
                'school_add' => ($request->school_permission == 'subscriber' && $request->school_add) ?? 0,
                'school_edit' => ($request->school_permission == 'subscriber' && $request->school_edit) ?? 0,
                'course_manager' => $request->course_permission == 'manager',
                'course_add' => ($request->course_permission == 'subscriber' && $request->course_add) ?? 0,
                'course_edit' => ($request->course_permission == 'subscriber' && $request->course_edit) ?? 0,
                'course_display' => ($request->course_permission == 'subscriber' && $request->course_display) ?? 0,
                'course_delete' => ($request->course_permission == 'subscriber' && $request->course_delete) ?? 0,
                'course_application_manager' => $request->course_application_permission == 'manager',
                'course_application_edit' => ($request->course_application_permission == 'subscriber' && $request->course_application_edit) ?? 0,
                'course_application_chanage_status' => ($request->course_application_permission == 'subscriber' && $request->course_application_chanage_status) ?? 0,
                'course_application_payment_refund' => ($request->course_application_permission == 'subscriber' && $request->course_application_payment_refund) ?? 0,
                'course_application_contact_student' => ($request->course_application_permission == 'subscriber' && $request->course_application_contact_student) ?? 0,
                'course_application_contact_school' => ($request->course_application_permission == 'subscriber' && $request->course_application_contact_school) ?? 0,
                'review_manager' => $request->review_permission == 'manager',
                'review_edit' => ($request->review_permission == 'subscriber' && $request->review_edit) ?? 0,
                'review_delete' => ($request->review_permission == 'subscriber' && $request->review_delete) ?? 0,
                'review_approve' => ($request->review_permission == 'subscriber' && $request->review_approve) ?? 0,
                'enquiry_manager' => $request->enquiry_permission == 'manager',
                'enquiry_add' => ($request->enquiry_permission == 'subscriber' && $request->enquiry_add) ?? 0,
                'enquiry_edit' => ($request->enquiry_permission == 'subscriber' && $request->enquiry_edit) ?? 0,
                'enquiry_delete' => ($request->enquiry_permission == 'subscriber' && $request->enquiry_delete) ?? 0,
            ]);
        }
        if ($user->school && is_array($user->school)) {
            foreach ($user->school as $user_school_id) {
                $user_school = UserSchool::where('user_id', $user->id)->where('school_id', $user_school_id)->first();
                if (!$user_school) {
                    $new_user_school = new UserSchool;
                    $new_user_school->user_id = $user->id;
                    $new_user_school->school_id = $user_school_id;
                    $new_user_school->save();
                }
            }
        }
        $user_schools = UserSchool::where('user_id', $user->id)->get();
        foreach ($user_schools as $user_school) {
            if (!in_array($user_school->school_id, is_array($user->school) ? $user->school : [])) {
                $user_school->delete();
            }
        }
        $saved = __('Admin/backend.data_saved_successfully');
        return response()->json(['success' => true, 'data' => $saved]);
    }
}