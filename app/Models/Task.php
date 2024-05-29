<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Task extends Model implements Auditable
{
    use HasFactory, AuditingAuditable, SoftDeletes;

    protected $guarded = [];

    // SoftDeletes
    protected $dates = ['deleted_at'];

    protected $casts = [
        'start_time' => 'datetime:Y-m-d H:i:s',
        'complete_time' => 'datetime:Y-m-d H:i:s',
        'due_date' => 'datetime:Y-m-d H:i:s',
        'notification_time' => 'datetime:Y-m-d H:i:s',
    ];

    protected function setAuditInclude()
    {
        // Get all columns from the model's table
        $columns = $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());

        // Set the $auditInclude property to include all columns
        $this->auditInclude = $columns;
    }

    /**
     * Relationship with Admin Table
     */
    public function assignee()
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }

    /**
     * Relationship with Admin Table
     */
    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Relationship with Task Label Table
     */
    public function label()
    {
        return $this->belongsTo(TaskLabel::class, 'task_label_id');
    }
}
