<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\CourseApplication;
use App\Models\Message;
use App\Models\Review;
use App\Models\School;
use App\Models\User;

use DB;
use PDF;
use Image;
use Storage;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function __construct()
    {
    }
    
    public function index()
    {
        return view('frontend.customer.index');
    }
    
    public function loginPassword()
    {
        $user = User::find(auth()->user()->id);
        if (app()->getLocale() == 'en') {
            $first_name = $user->first_name_en;
            $last_name = $user->last_name_en;
        } else {
            $first_name = $user->first_name_ar;
            $last_name = $user->last_name_ar;
        }
        $email = $user->email;
        $telephone = $user->telephone;
        return view('frontend.customer.login_password', compact('first_name', 'last_name', 'email', 'telephone'));
    }
    
    public function verifyEmail()
    {
        $user = User::whereId(auth()->user()->id)->first();

        sendEmail('verify_email', $user->email, $user, app()->getLocale());

        $data['data'] = __('Frontend.verify_email_sent');
        $data['success'] = true;
        return response()->json($data);
    }
    
    public function verifyPhone()
    {
        $data['data'] = __('Frontend.verify_phone_sent');
        $data['success'] = true;
        return response()->json($data);
    }

    public function updateLoginPassword(Request $request)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'sometimes',
            'email' => 'required|email',
            'telephone' => 'sometimes',
            'password' => 'sometimes',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        $data['success'] = true;
        if ($validator->fails()) {
            $data['success'] = false;
            $data['errors'] = $validator->errors();
        } else {
            $data['data'] = __('Frontend.data_saved');

            $user = User::whereId(auth()->user()->id)->first();
            if (app()->getLocale() == 'en') {
                $user->first_name_en = $request->first_name;
                $user->last_name_en = $request->last_name;
            } else {
                $user->first_name_ar = $request->first_name;
                $user->last_name_ar = $request->last_name;
            }
            if (isset($request->telephone) && $request->telephone) {
                $user->telephone = $request->telephone;
            }
            if (isset($request->password) && $request->password) {
                $user->password = \Hash::make($request->password);
            }

            $user->save();
        }

        return response()->json($data);
    }
    
    public function courseApplication()
    {
        $booked_courses = CourseApplication::where('user_id', auth()->user()->id)->where('paid', 1)->with('courseApplicationApprove')->get();

        return view('frontend.customer.course_applications', compact('booked_courses'));
    }
    
    public function detailCourseApplication($id)
    {
        $data = getCourseApplicationPrintData($id, auth()->user()->id);
        $test = '';
        return view('frontend.customer.course_application', $data, compact('test'));
    }

    public function printCourseApplication(Request $request)
    {
        $rules = [
            'id' => 'required',
            'section' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        $data['success'] = true;
        if ($validator->fails()) {
            $data['success'] = false;
            $data['errors'] = $validator->errors();
            return response($data);
        } else {
            $pdf_data = getCourseApplicationPrintData($request->id, auth()->user()->id);
            $pdf_data['logo'] = asset('public/frontend/assets/img/logo.png');
            
            if ($request->section == 'reservation') {
                $pdf = PDF::loadView('pdf.course_application.reservation', $pdf_data);
                Storage::disk('public')->put('pdf/course_application/reservation_' . $request->id . '.pdf', $pdf->output());
                $pdf_file = storage_path('app/public/pdf/course_application/reservation_' . $request->id  . '.pdf');
            } else if ($request->section == 'registration') {
                $pdf = PDF::loadView('pdf.course_application.registration', $pdf_data);
                Storage::disk('public')->put('pdf/course_application/registration_' . $request->id . '.pdf', $pdf->output());
                $pdf_file = storage_path('app/public/pdf/course_application/registration_' . $request->id  . '.pdf');
            } else if ($request->section == 'registration_cancellation') {
                $pdf = PDF::loadView('pdf.course_application.registration_cancellation', $pdf_data);
                Storage::disk('public')->put('pdf/course_application/registration_cancellation_' . $request->id . '.pdf', $pdf->output());
                $pdf_file = storage_path('app/public/pdf/course_application/registration_cancellation_' . $request->id  . '.pdf');
            } else if ($request->section == 'payments_refunds') {
                $pdf = PDF::loadView('pdf.course_application.payments_refunds', $pdf_data);
                Storage::disk('public')->put('pdf/course_application/payments_refunds_' . $request->id . '.pdf', $pdf->output());
                $pdf_file = storage_path('app/public/pdf/course_application/payments_refunds_' . $request->id  . '.pdf');
            }
        }

        return response()->download($pdf_file);
    }

    public function sendCourseApplicationMessage(Request $request)
    {
        $data['success'] = true;

        $rules = [
            'attachment.*' => 'sometimes|mimes:doc,docx,pdf,jpg,jpeg,bmp,png',
            'subject' => 'required',
            'message' => 'required',
            'type' => 'required',
            'type_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $data['success'] = false;
            $data['errors'] = $validator->errors();
        } else {
            $request_save = $request->only('subject', 'message', 'type', 'type_id');

            $attachments = [];
            $send_files = [];
            if ($request->has("attachment")) {
                foreach ($request->file('attachment') as $attachment) {
                    $attachment_file = $attachment->getClientOriginalName();
                    $attachment_file_name = pathinfo($attachment_file, PATHINFO_FILENAME);
                    $attachment_file_ext = pathinfo($attachment_file, PATHINFO_EXTENSION);
                    $attachment_file = $attachment_file_name . '_' . time() . '.' . $attachment_file_ext;
                    $request_save['attachments'][] = '/public/attachments/' . $attachment_file;
                    $send_files[] = $attachment->move('public/attachments', $attachment_file);
                }
            }
            
            $course_application = CourseApplication::whereId($request->type_id)->first();
            $request_save['from_user'] = auth()->user()->id;
            $request_save['to_user'] = [];
            $school_admins = User::where('user_type', 'school_admin')->get();
            foreach ($school_admins as $school_admin) {
                if ($school_admin->account_active) {
                    $request_save['to_user'][] = $school_admin->id;
    
                    $mail_pdf_data = array();
                    $mail_pdf_data['subject'] = $request_save['subject'];
                    $mail_pdf_data['message'] = $request_save['message'];
                    $mail_pdf_data['user'] = $mail_pdf_data['to_user'] = \App\Models\User::find($school_admin->id);
                    $mail_pdf_data['from_user'] = \App\Models\User::find(auth()->user()->id);
                    $mail_pdf_data['locale'] = app()->getLocale();
                    sendEmail('send_to_school_admin', $school_admin->email, $request_save, app()->getLocale(), $send_files);
                }
            }
            Message::create($request_save);

            $data['message'] = __('Frontend.message_sent_thank_you');
        }
        
        return response($data);
    }

    public function reviews()
    {
        $course_applications = CourseApplication::with('school', 'review')->where('user_id', auth()->user()->id)->get();

        return view('frontend.customer.reviews', compact('course_applications'));
    }

    public function review($id)
    {
        $course_application = CourseApplication::with('school', 'review')->where('id', $id)->first();

        return view('frontend.customer.review', compact('id', 'course_application'));
    }

    public function reviewBooking(Request $request, $id)
    {
        $course_book_review = Review::where('course_application_id', $id)->first();

        if ($course_book_review) {
            $course_book_review->review = $request->review;
            $course_book_review->quality_teaching = $request->quality_teaching;
            $course_book_review->school_facilities = $request->school_facilities;
            $course_book_review->social_activities = $request->social_activities;
            $course_book_review->school_location = $request->school_location;
            $course_book_review->satisfied_teaching = $request->satisfied_teaching;
            $course_book_review->level_cleanliness = $request->level_cleanliness;
            $course_book_review->distance_accommodation_school = $request->distance_accommodation_school;
            $course_book_review->satisfied_accommodation = $request->satisfied_accommodation;
            $course_book_review->airport_transfer = $request->airport_transfer;
            $course_book_review->city_activities = $request->city_activities;
            $course_book_review->recommend_this_school = $request->recommend_this_school;
            $course_book_review->use_full_name = $request->use_full_name;

            $course_application = CourseApplication::find($course_book_review->course_application_id);
            $review_point_count = 6;
            if ($course_application && $course_application->accommodation_id) {
                $review_point_count = $review_point_count + 3;
            }
            if ($course_application && $course_application->airport_id) {
                $review_point_count = $review_point_count + 1;
            }
            $course_book_review->average_point = ($course_book_review->quality_teaching + $course_book_review->school_facilities + $course_book_review->social_activities
                + $course_book_review->school_location + $course_book_review->satisfied_teaching + $course_book_review->level_cleanliness
                + $course_book_review->distance_accommodation_school + $course_book_review->satisfied_accommodation + $course_book_review->airport_transfer
                + $course_book_review->city_activities) / $review_point_count;
            $course_book_review->save();
        } else {
            $new_course_book_review = new Review;
            $new_course_book_review->author_id = auth()->user()->id;
            $new_course_book_review->course_application_id = $id;
            $new_course_book_review->review = $request->review;
            $new_course_book_review->quality_teaching = $request->quality_teaching;
            $new_course_book_review->school_facilities = $request->school_facilities;
            $new_course_book_review->social_activities = $request->social_activities;
            $new_course_book_review->school_location = $request->school_location;
            $new_course_book_review->satisfied_teaching = $request->satisfied_teaching;
            $new_course_book_review->level_cleanliness = $request->level_cleanliness;
            $new_course_book_review->distance_accommodation_school = $request->distance_accommodation_school;
            $new_course_book_review->satisfied_accommodation = $request->satisfied_accommodation;
            $new_course_book_review->airport_transfer = $request->airport_transfer;
            $new_course_book_review->city_activities = $request->city_activities;
            $new_course_book_review->recommend_this_school = $request->recommend_this_school;
            $new_course_book_review->use_full_name = $request->use_full_name;

            $course_application = CourseApplication::find($new_course_book_review->course_application_id);
            $review_point_count = 6;
            if ($course_application && $course_application->accommodation_id) {
                $review_point_count = $review_point_count + 3;
            }
            if ($course_application && $course_application->airport_id) {
                $review_point_count = $review_point_count + 1;
            }
            $new_course_book_review->average_point = ($new_course_book_review->quality_teaching + $new_course_book_review->school_facilities + $new_course_book_review->social_activities
                + $new_course_book_review->school_location + $new_course_book_review->satisfied_teaching + $new_course_book_review->level_cleanliness
                + $new_course_book_review->distance_accommodation_school + $new_course_book_review->satisfied_accommodation + $new_course_book_review->airport_transfer
                + $new_course_book_review->city_activities) / $review_point_count;
            $new_course_book_review->save();
        }

        return back();
    }
    
    public function payments()
    {
        $course_applications = CourseApplication::with('transaction')->where('user_id', auth()->user()->id)->get();
        return view('frontend.customer.payments', compact('course_applications'));
    }

    public function payment($id)
    {
        $course_application = CourseApplication::with('transaction')->where('id', $id)->first();

        return view('frontend.customer.payment', compact('id', 'course_application'));
    }

    public function affiliate()
    {
        $affiliate = auth()->user();

        return view('frontend.customer.affiliate', compact('affiliate'));
    }

    public function coupons()
    {
        $coupons = Coupon::where('affiliate_id', auth()->user()->id)->get();

        return view('frontend.customer.coupons', compact('coupons'));
    }

    public function couponUsage($id)
    {
        $coupon_usages = CouponUsage::with('course_application.course.school')->where('coupon_id', $id)->get();

        return view('frontend.customer.coupon_usage', compact('coupon_usages'));
    }

    public function transactions()
    {
        $user = User::with('transactions')->where('user_type', 'affiliate')->where('id', auth()->user()->id)->first();
        $transactions = [];
        if ($user) {
            $transactions = $user->transactions;
            foreach ($transactions as $transaction) {
                $course_application = CourseApplication::where('order_id', $transaction->cart_id)->first();
                $transaction->course_application = $course_application;
            }
        }

        return view('frontend.customer.transactions', compact('transactions'));
    }
}