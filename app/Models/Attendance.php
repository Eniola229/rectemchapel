<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Notifications\Notifiable;
use App\Models\Student;


class Attendance extends Model
{
    protected $table = 'attendances';
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            // $model->id = Uuid::uuid4()->toString();
            if (empty($model->id)) {
                // $model->id = (string) Str::uuid();
                $model->id = Uuid::uuid4()->toString();
            }
        });
        
    }

    protected $fillable = [
        'student_id',
        'service',
        'time_marked',
        'is_late',
        'checked_out_at',
        'date'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}
