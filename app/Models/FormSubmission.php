<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class FormSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'extra_data',
        'status',
        'audited_by',
        'audited_at',
        'audit_comment',
        'finance_by',
        'finance_at',
        'finance_comment',
    ];

    /**
     * Cast dates to Carbon instances.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'audited_at' => 'datetime',
        'finance_at'  => 'datetime',
    ];

    /**
     * The form belongs to the submitting user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The form was audited by this user.
     */
    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'audited_by');
    }

    public function financier(): BelongsTo
{
    return $this->belongsTo(User::class, 'finance_by');
}
}
