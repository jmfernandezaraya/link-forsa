@extends('frontend.layouts.app')

@section('title')
    @if ($course_application->review)
        {{__('Frontend.edit_the_review')}}
    @else
        {{__('Frontend.rate_write_a_review')}}
    @endif
@endsection

@section('breadcrumbs')
    <div class="breadcrumb-head">
        <a href="{{route('frontend.dashboard')}}" class="breadcrumb-home">
            <i class="bx bxs-dashboard"></i>&nbsp;
        </a>
        <h1>
            @if ($course_application->review)
                {{__('Frontend.edit_the_review')}}
            @else
                {{__('Frontend.rate_write_a_review')}}
            @endif
        </h1>
    </div>
@endsection

@section('content')
    <div class="dashboard">
        <div class="container" data-aos="fade-up">
            <div class="row" data-aos="zoom-in" data-aos-delay="100">
                <div class="col-md-12">
                    <form class="review-form" method="POST" action="{{route('frontend.dashboard.review.booking', $id)}}" id="ReviewForm">
                        {{csrf_field()}}

                        <div class="row">
                            <div class="col-md-4">
                                <h3>{{__('Frontend.school_and_teaching')}}</h3>

                                <div class="form-group">
                                    <label for="quality_of_teaching" class="col-form-label">{{__('Frontend.quality_of_teaching')}}</label>
                                    <input type="hidden" name="quality_teaching" value="{{ $course_application->review ? $course_application->review->quality_teaching : 0 }}" />
                                    <div class="rating-input quality-teaching" data-name="quality_teaching" data-value="{{ $course_application->review ? floatval($course_application->review->quality_teaching) : 0 }}"></div>
                                </div>
                                <div class="form-group">
                                    <label for="school_facilities" class="col-form-label">{{__('Frontend.school_facilities')}}</label>
                                    <input type="hidden" name="school_facilities" value="{{ $course_application->review ? $course_application->review->school_facilities : 0 }}" />
                                    <div class="rating-input school-facilities" data-name="school_facilities" data-value="{{ $course_application->review ? floatval($course_application->review->school_facilities) : 0 }}"></div>
                                </div>
                                <div class="form-group">
                                    <label for="social_activities" class="col-form-label">{{__('Frontend.social_activities')}}</label>
                                    <input type="hidden" name="social_activities" value="{{ $course_application->review ? $course_application->review->social_activities : 0 }}" />
                                    <div class="rating-input social-activities" data-name="social_activities" data-value="{{ $course_application->review ? floatval($course_application->review->social_activities) : 0 }}"></div>
                                </div>
                                <div class="form-group">
                                    <label for="school_location" class="col-form-label">{{__('Frontend.school_location')}}</label>
                                    <input type="hidden" name="school_location" value="{{ $course_application->review ? $course_application->review->school_location : 0 }}" />
                                    <div class="rating-input school-location" data-name="school_location" data-value="{{ $course_application->review ? floatval($course_application->review->school_location) : 0 }}"></div>
                                </div>
                                <div class="form-group">
                                    <label for="satisfied_teaching" class="col-form-label">{{__('Frontend.satisfied_teaching')}}</label>
                                    <input type="hidden" name="satisfied_teaching" value="{{ $course_application->review ? $course_application->review->satisfied_teaching : 0 }}" />
                                    <div class="rating-input satisfied-teaching" data-name="satisfied_teaching" data-value="{{ $course_application->review ? floatval($course_application->review->satisfied_teaching) : 0 }}"></div>
                                </div>
                            </div>

                            @if ($course_application && $course_application->accommodation_id)
                                <div class="col-md-4">
                                    <h3>{{__('Frontend.accommodation')}}</h3>

                                    <div class="form-group">
                                        <label for="level_of_cleanliness" class="col-form-label">{{__('Frontend.level_of_cleanliness')}}</label>
                                        <input type="hidden" name="level_cleanliness" value="{{ $course_application->review ? $course_application->review->level_cleanliness : 0 }}" />
                                        <div class="rating-input level-of-cleanliness" data-name="level_cleanliness" data-value="{{ $course_application->review ? floatval($course_application->review->level_cleanliness) : 0 }}"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="distance_accommodation_school" class="col-form-label">{{__('Frontend.distance_accommodation_school')}}</label>
                                        <input type="hidden" name="distance_accommodation_school" value="{{ $course_application->review ? $course_application->review->distance_accommodation_school : 0 }}" />
                                        <div class="rating-input distance-accommodation-school" data-name="distance_accommodation_school" data-value="{{ $course_application->review ? floatval($course_application->review->distance_accommodation_school) : 0 }}"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="satisfied_accommodation" class="col-form-label">{{__('Frontend.satisfied_accommodation')}}</label>
                                        <input type="hidden" name="satisfied_accommodation" value="{{ $course_application->review ? $course_application->review->satisfied_accommodation : 0 }}" />
                                        <div class="rating-input satisfied-accommodation" data-name="satisfied_accommodation" data-value="{{ $course_application->review ? floatval($course_application->review->satisfied_accommodation) : 0 }}"></div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-4">
                                <h3>{{__('Frontend.others')}}</h3>
                                
                                @if ($course_application && $course_application->airport_id)
                                    <div class="form-group">
                                        <label for="airport_transfer" class="col-form-label">{{__('Frontend.airport_transfer')}}</label>
                                        <input type="hidden" name="airport_transfer" value="{{ $course_application->review ? $course_application->review->airport_transfer : 0 }}" />
                                        <div class="rating-input airport-transfer" data-name="airport_transfer" data-value="{{ $course_application->review ? floatval($course_application->review->airport_transfer) : 0 }}"></div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="city_activities" class="col-form-label">{{__('Frontend.city_activities')}}</label>
                                    <input type="hidden" name="city_activities" value="{{ $course_application->review ? $course_application->review->city_activities : 0 }}" />
                                    <div class="rating-input city-activities" data-name="city_activities" data-value="{{ $course_application->review ? floatval($course_application->review->city_activities) : 0 }}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="would_you_recommend_this_school" class="col-form-label">{{__('Frontend.would_you_recommend_this_school')}}</label>
                                    <input type="radio" value="1" name="recommend_this_school" {{ $course_application->review ? ($course_application->review->recommend_this_school ? 'checked' : '') : 'checked' }}>
                                    <label for="recommend_this_school_yes">
                                        {{__('Frontend.yes')}}
                                    </label>
                                    <input type="radio" value="0" name="recommend_this_school" {{ $course_application->review ? ($course_application->review->recommend_this_school ? '' : 'checked') : '' }}>
                                    <label for="recommend_this_school_no">
                                        {{__('Frontend.no')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="write_a_review" class="col-form-label">{{__('Frontend.write_a_review')}}</label>
                                    <textarea name="review" class="form-control" rows="5">{{ $course_application->review ? $course_application->review->review : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="use_my_full_name_for_the_rating_and_review" class="col-form-label">{{__('Frontend.use_my_full_name_for_the_rating_and_review')}}</label>
                                    <input type="radio" value="1" name="use_full_name" {{ $course_application->review ? ($course_application->review->use_full_name ? 'checked' : '') : 'checked' }}>
                                    <label for="use_full_name_yes">
                                        {{__('Frontend.yes')}}
                                    </label>
                                    <input type="radio" value="0" name="use_full_name" {{ $course_application->review ? ($course_application->review->use_full_name ? '' : 'checked') : '' }}>
                                    <label for="use_full_name_no">
                                        {{__('Frontend.no')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary float-right mt-3">{{__('Frontend.submit')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection