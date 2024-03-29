<?php

namespace App\Models;

use App\Models\Review;

use App\Models\CourseAccommodation;
use App\Models\CourseAirport;
use App\Models\Course;
use App\Models\CourseProgram;
use App\Models\School;
use App\Models\CourseApplicationApprove;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;

use TelrGateway\Transaction;

/**
 * Class CourseApplication
 * @package App\Models
 */
class CourseApplication extends Model
{
    use HasFactory;
    use Notifiable;

    /**
     * @var string[]
     */
    protected $casts = ['heard_where' => 'array', 'start_date' => 'date'];
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return array
     */
    public function getAttributesName()
    {
        $attribute = $this->getAttributes();

        unset($attribute['id']);
        unset($attribute['user_id']);
        unset($attribute['created_at']);
        unset($attribute['updated_at']);

        return $attribute;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function User()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courseApplicationApproves()
    {
        return $this->hasMany(CourseApplicationApprove::class, 'user_course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function courseApplicationApprove()
    {
        return $this->hasOne(CourseApplicationApprove::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'unique_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function airport()
    {
        return $this->belongsTo(CourseAirport::class, 'airport_id', 'unique_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accomodation()
    {
        return $this->belongsTo(CourseAccommodation::class, 'accommodation_id', 'unique_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function review()
    {
        return $this->belongsTo(Review::class, 'id', 'course_application_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courseApplicationStatusus()
    {
        return $this->hasMany(CourseApplicationStatus::class, 'course_application_id');
    }

    /**
     * @param $status
     * @return string
     */
    public function getCourseApplicationStatus($status)
    {
        $created_at = '-';
        $coursebookedstatus = $this->courseApplicationStatusus()->where('status', $status)->first();
        if ($coursebookedstatus) {
            $created_at = $coursebookedstatus->created_at->format('d M Y');
        }

        return $created_at;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'order_id', 'cart_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getCourseProgram()
    {
        return $this->belongsTo(CourseProgram::class, 'course_program_id', 'unique_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userCourseFee()
    {
        return $this->hasOne(CourseApplicationFee::class);
    }
}