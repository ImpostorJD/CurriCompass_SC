<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'userid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userfname',
        'userlname',
        'usermiddle',
        'email',
        'password',
        'contact_no',
        'roleid'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function user_roles()
    {
        return $this->belongsToMany(Role::class, 'user__roles', 'userid', 'roleid');
    }

    public function student_record()
    {
        return $this->hasOne(StudentRecord::class, 'userid', 'userid');
    }

     /**
     * Checks whether user has any role
     * @param array|string
     * @return bool
     */
    public function hasAnyRole($role)
    {
        if (is_array($role)) {
            foreach ($role as $rolecheck) {
                if ($this->hasPermission($rolecheck)) {
                    return true;
                }
            }
        } else {
            if ($this->hasPermission($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks whether a user has only one role
     * @param string
     * @return bool
     */
    public function hasPermission(string $role)
    {
        if ($this->User_Roles()->where('rolename', $role)->first() != null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks whether a user has all roles
     * @param array|string
     * @return bool
     */
    public function hasRoles($role)
    {
        if (is_array($role)) {
            foreach ($role as $rolecheck) {
                if (!$this->hasPermission($rolecheck)) {
                    return false;
                }
            }
        } else {
            if (!$this->hasPermission($role)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Checks whether a user has any role unwanted
     * @param array|string
     * @return bool
     */

    public function excludeAnyRole($role)
    {
        if (is_array($role)) {
            foreach ($role as $rolecheck) {
                if ($this->hasPermission($rolecheck)) {
                    return false;
                }
            }
        } else {
            if ($this->hasPermission($role)) {
                return false;
            }
        }
        return true;
    }

}
