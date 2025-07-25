<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'license_number',
        'commercial_record',
        'tax_number',
        'logo',
        'description',
        'status',
        'license_expiry_date',
        'max_users',
        'main_branch_name',
        'landline', 
        'currency',
        'subscription_start_date',
        'subscription_end_date',
        'theme_color'
    ];

    protected $casts = [
        'license_expiry_date' => 'date',
    ];

    public function admin()
    {
        return $this->hasOne(User::class, 'agency_id')->whereHas('roles', function($q) {
            $q->where('name', 'agency-admin');
        });
    }

    public function users()
    {
        return $this->hasMany(User::class, 'agency_id');
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isLicenseExpired()
    {
        return $this->license_expiry_date->isPast();
    }
    
    /**
     * إنشاء دور agency-admin للوكالة
     */
    public function createAgencyAdminRole()
    {
        $role = Role::firstOrCreate([
            'name' => 'agency-admin',
            'guard_name' => 'web',
            'agency_id' => $this->id,
        ]);

        // ربط الدور بجميع الصلاحيات العامة
        $role->givePermissionTo([
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            'permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete',
            'sales.view','sales.create','sales.edit','sales.delete',
            'customers.view','customers.create','customers.edit','customers.delete',
            'providers.view','providers.create','providers.edit','providers.delete',
            'service_types.view','service_types.create','service_types.edit','service_types.delete',
            'employees.view','employees.create','employees.edit','employees.delete',
            'users.view','users.create','users.edit','users.delete',
            'roles.view','roles.create','roles.edit','roles.delete',
            'permissions.view','permissions.create','permissions.edit','permissions.delete',
            'lists.view','lists.create','lists.edit','lists.delete',
            'sequences.view','sequences.create','sequences.edit','sequences.delete',
            'agency.profile.view','agency.profile.edit',
            'currency.view','currency.edit',
            'system.settings.view','system.settings.edit',
            'theme.view','theme.edit',
            'branches.view','branches.create','branches.edit','branches.delete',
            'departments.view','departments.create','departments.edit','departments.delete',
            'positions.view','positions.create','positions.edit','positions.delete',
            'intermediaries.view','intermediaries.create','intermediaries.edit','intermediaries.delete',
            'accounts.view','accounts.create','accounts.edit','accounts.delete',
            'sales.reports.view',
        ]);

        return $role;
    }

    public function serviceTypes()
    {
        return $this->hasMany(ServiceType::class);
    }

    public function policies()
    {
        return $this->hasMany(AgencyPolicy::class);
    }
}
