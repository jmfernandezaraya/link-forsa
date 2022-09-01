<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Classes\StoreClass;

use App\Http\Controllers\Controller;

use App\Mail\EmailTemplate;

use App\Models\User;
use App\Models\SuperAdmin\Country;
use App\Models\SuperAdmin\City;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Carbon\Carbon;

use DB;
use Image;
use Storage;

class AffiliateController extends Controller
{
    function index()
    {
        $affiliates = User::where('user_type', 'affiliate')->get();

        return view('superadmin.affiliate.index', compact('affiliates'));
    }

    function create()
    {
        $countries = Country::with('cities')->get();

        return view('superadmin.affiliate.add', compact('countries'));
    }

    function store(Request $request)
    {
        $rules = [
            'first_name_en' => 'required',
            'first_name_ar' => 'sometimes',
            'last_name_en' => 'required',
            'last_name_ar' => 'sometimes',
            'email' => 'required|unique:users',
            'password' => 'required',
            'telephone' => 'required',
            'mobile' => 'sometimes',
            'another_mobile' => 'sometimes',
            'image' => 'mimes:jpg,jpeg,png,bmp',
            'instagram' => 'sometimes',
            'twitter' => 'sometimes',
            'snapchat' => 'sometimes',
            'tiktok' => 'sometimes',
            'facebook' => 'sometimes',
            'youtube' => 'sometimes',
            'pinterest' => 'sometimes',
            'skype' => 'sometimes',
            'linkedin' => 'sometimes',
            'address' => 'required',
            'post_code' => 'required',
            'country' => 'sometimes',
            'city' => 'sometimes',
            'commission_rate' => 'required',
            'commission_rate_type' => 'required',
            'tax_id' => 'required',
            'bank_name' => 'required',
            'bank_address' => 'required',
            'iban' => 'required',
            'swift_code' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
        ];
        $validator = \Validator::make($request->all(), $rules, [
            'first_name_en.required' => __('Admin/backend.errors.first_name_english'),
            'first_name_ar.required' => __('Admin/backend.errors.first_name_arabic'),
            'last_name_ar.required' => __('Admin/backend.errors.last_name_arabic'),
            'last_name_en.required' => __('Admin/backend.errors.last_name_english'),
            'email.required' => __('Admin/backend.errors.email_required'),
            'password.required' => __('Admin/backend.errors.password_required'),
            'telephone.required' => __('Admin/backend.errors.telephone_required'),
            'mobile.required' => __('Admin/backend.errors.mobile_required'),
            'image.required' => __('Admin/backend.errors.image_required'),
            'image.mimes' => __('Admin/backend.errors.image_must_be_in'),
            'address.required' => __('Admin/backend.errors.address_required'),
            'post_code.required' => __('Admin/backend.errors.post_code_required'),
            'country.required' => __('Admin/backend.errors.country_required'),
            'city.required' => __('Admin/backend.errors.city_required'),
            'commission_rate.required' => __('Admin/backend.errors.commission_rate_required'),
            'commission_rate_type.required' => __('Admin/backend.errors.commission_rate_type_required'),
            'tax_id.required' => __('Admin/backend.errors.tax_id_required'),
            'bank_name.required' => __('Admin/backend.errors.bank_name_required'),
            'bank_address.required' => __('Admin/backend.errors.bank_address_required'),
            'iban.required' => __('Admin/backend.errors.iban_required'),
            'swift_code.required' => __('Admin/backend.errors.swift_code_required'),
            'account_name.required' => __('Admin/backend.errors.account_name_required'),
            'account_number.required' => __('Admin/backend.errors.account_number_required'),
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validate->errors());
        }

        $requested_save = $validator->validated();
        unset($requested_save['password']);
        unset($requested_save['image']);

        $image_name = null;
        if ($request->has('image')) {
            $image = $request->file('image');

            $filename = time() . rand(00, 99) . \Str::random(10) . '.webp';
            Image::make($image)->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp', 90)->save(public_path('images/user_images/' . $filename));
            $image_name = 'public/images/user_images/' . $filename;
        }
        \DB::transaction(function () use ($request, $requested_save, $image_name) {
            if ($image_name) {
                $user = User::create($requested_save + ['user_type' => 'affiliate', 'image' => $image_name, 'password' => \Hash::make($request->password), 'email_verified_at' => Carbon::now()->toDate()]);
            } else {
                $user = User::create($requested_save + ['user_type' => 'affiliate', 'password' => \Hash::make($request->password), 'email_verified_at' => Carbon::now()->toDate()]);
            }
            if (can_manage_user() || can_permission_user()) {
                $user->permission()->create([]);
            }

            $mail_data['user'] = $user;
            $mail_data['email'] = $request->email;
            $mail_data['password'] = $request->password;
            $mail_data['dashbaord_link'] = route('frontend.dashboard');
            $mail_data['change_password_link'] = route('password.reset', ['token' => \Password::createToken($user)]) . '/?email=' . $user->email;

            sendEmail('affiliate_created', $user->email, (object)$mail_data, app()->getLocale());
        });

        toastr()->success(__('Admin/backend.data_saved_successfully'));

        return redirect()->route('superadmin.affiliate.index');
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
            toastr()->success(__('Admin/backend.deleted_successfully'));
            return back();
        }
    }

    function edit($id)
    {
        $affiliate = User::with('permission')->find($id);
        $countries = Country::with('cities')->get();

        return view('superadmin.affiliate.edit', compact('affiliate', 'countries'));
    }

    function update(Request $request, $id)
    {
        $user = User::find($id);
        
        $rules = [
            'first_name_en' => 'required',
            'first_name_ar' => 'sometimes',
            'last_name_en' => 'required',
            'last_name_ar' => 'sometimes',
            'email' => 'required',
            // 'password' => 'required',
            'telephone' => 'required',
            'mobile' => 'sometimes',
            'another_mobile' => 'sometimes',
            'image' => 'mimes:jpg,jpeg,png,bmp',
            'instagram' => 'sometimes',
            'twitter' => 'sometimes',
            'snapchat' => 'sometimes',
            'tiktok' => 'sometimes',
            'facebook' => 'sometimes',
            'youtube' => 'sometimes',
            'pinterest' => 'sometimes',
            'skype' => 'sometimes',
            'linkedin' => 'sometimes',
            'address' => 'required',
            'post_code' => 'required',
            'country' => 'sometimes',
            'city' => 'sometimes',
            'commission_rate' => 'required',
            'commission_rate_type' => 'required',
            'tax_id' => 'required',
            'bank_name' => 'required',
            'bank_address' => 'required',
            'iban' => 'required',
            'swift_code' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
        ];
        $validator = \Validator::make($request->all(), $rules, [
            'first_name_en.required' => __('Admin/backend.errors.first_name_english'),
            'first_name_ar.required' => __('Admin/backend.errors.first_name_arabic'),
            'last_name_ar.required' => __('Admin/backend.errors.last_name_arabic'),
            'last_name_en.required' => __('Admin/backend.errors.last_name_english'),
            'email.required' => __('Admin/backend.errors.email_required'),
            'password.required' => __('Admin/backend.errors.password_required'),
            'telephone.required' => __('Admin/backend.errors.telephone_required'),
            'mobile.required' => __('Admin/backend.errors.mobile_required'),
            'image.required' => __('Admin/backend.errors.image_required'),
            'image.mimes' => __('Admin/backend.errors.image_must_be_in'),
            'address.required' => __('Admin/backend.errors.address_required'),
            'post_code.required' => __('Admin/backend.errors.post_code_required'),
            'country.required' => __('Admin/backend.errors.country_required'),
            'city.required' => __('Admin/backend.errors.city_required'),
            'commission_rate.required' => __('Admin/backend.errors.commission_rate_required'),
            'commission_rate_type.required' => __('Admin/backend.errors.commission_rate_type_required'),
            'tax_id.required' => __('Admin/backend.errors.tax_id_required'),
            'bank_name.required' => __('Admin/backend.errors.bank_name_required'),
            'bank_address.required' => __('Admin/backend.errors.bank_address_required'),
            'iban.required' => __('Admin/backend.errors.iban_required'),
            'swift_code.required' => __('Admin/backend.errors.swift_code_required'),
            'account_name.required' => __('Admin/backend.errors.account_name_required'),
            'account_number.required' => __('Admin/backend.errors.account_number_required'),
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validate->errors());
        }
        $password = $request->has('password') ? \Hash::make($request->password) : null;
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        $requested_save = $validator->validated();
        unset($requested_save['password']);
        unset($requested_save['image']);

        $image_name = null;
        if ($request->has('image')) {
            $image = $request->file('image');

            $filename = time() . rand(00, 99) . \Str::random(10) . '.webp';
            Image::make($image)->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp', 90)->save(public_path('images/user_images/' . $filename));
            $image_name = 'public/images/user_images/' . $filename;
        }

        if ($request->has('password')) {
            if ($image_name) {
                $user->fill($requested_save + ['user_type' => 'affiliate', 'image' => $image_name, 'password' => $password])->save();
            } else {
                $user->fill($requested_save + ['user_type' => 'affiliate', 'password' => $password])->save();
            }
        } else {
            if ($image_name) {
                $user->fill($requested_save + ['user_type' => 'affiliate', 'image' => $image_name])->save();
            } else {
                $user->fill($requested_save + ['user_type' => 'affiliate'])->save();
            }
        }

        toastr()->success(__('Admin/backend.data_saved_successfully'));

        return redirect()->route('superadmin.affiliate.index');
    }
}