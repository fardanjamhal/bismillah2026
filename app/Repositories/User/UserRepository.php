<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Contracts\User\UserInterface;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use App\Models\MasterData\Student;
use Illuminate\Support\Arr;
use Ramsey\Uuid\Uuid;
use Auth;

class UserRepository extends BaseRepository implements UserInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $users = $this->model->query();
        if (isset($params->search)) {
            $searchTerm = '%' . $params->search . '%';

            $users->where(function ($query) use ($searchTerm) {
                $query->where('code', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('name', 'like', $searchTerm)
                      ->orWhere('username', 'like', $searchTerm);
            });
        }
        if(isset($params->level)) $users->where('level', $params->level);
        if(isset($params->member_type)) $users->where('member_type', $params->member_type);
        if(isset($params->status_active)) $users->where('is_active', (int) $params->status_active);


        $users = $users->orderBy('created_at', 'ASC')->paginate($limit);

        $users->appends([
            'search' => $params->search,
            'level' => $params->level,
            'status_active' => $params->status_active,
            'member_type' => $params->member_type,
        ]);

        return $users;
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 20)
    {
        $users = $this->model->query();

        if (isset($params->search)) {
            $searchTerm = '%' . $params->search . '%';
            $users->where(function ($query) use ($searchTerm) {
                $query->where('code', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('name', 'like', $searchTerm)
                      ->orWhere('username', 'like', $searchTerm);
            });
        }

        $users = $users->where('level', 2)->orderBy('created_at', 'ASC')->simplePaginate($limit);
        return $users;
    }

    public function getAllWithParams($params)
    {
        $users = $this->model->query();

        if (isset($params->search)) {
            $searchTerm = '%' . $params->search . '%';
            $users->where(function ($query) use ($searchTerm) {
                $query->where('code', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('name', 'like', $searchTerm)
                      ->orWhere('username', 'like', $searchTerm);
            });
        }

        $users = $users->where('level', 2)->orderBy('created_at', 'ASC')->get();
        return $users;
    }

    public function getAllUserStudentSimplePaginatedWithParams($params, $limit = 10)
    {
        $users = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $users->where('name', 'like', '%' . $params->search . '%');
        $users = $users->with(['student'])->where('level', 2)->orderBy('created_at', 'ASC')->paginate($limit);
        return $users;
    }

    public function create($attributes)
    {
        $attributes = $attributes->all();
        $attributes['password'] = bcrypt($attributes['password']);
    
        $user = $this->model->create($attributes);

        $attributes['user_id'] = $user->id;
        $attributes['is_member'] = $attributes['member_type'] == 1 ? 1 : 0;
        Student::create($attributes);

        return $user;
    }

    public function update($attributes, $id)
    {
        $user = $this->model->find($id);

        $attributes = $attributes->all();

        if (!empty($attributes['password'])) {
            $attributes['password'] = bcrypt($attributes['password']);
        } else {
            $attributes = Arr::except($attributes,array('password'));
        }

        $attributes['expiry_verification_date'] = $attributes['is_active'] == 0 ? null : $user->is_active;
        $user->update($attributes);

        Student::updateOrInsert(
            ['user_id' => $user->id],
            [
                'id' => Uuid::uuid4()->toString(),
                'province_id' => $attributes['province_id'],
                'city_id' => $attributes['city_id'],
                'district_id' => $attributes['district_id'],
                'village_id' => $attributes['village_id'],
                'address' => $attributes['address'],
                'phone_number' => $attributes['phone_number'],
                'gender' => $attributes['gender'],
                'is_member' => $attributes['member_type'] == 1 ? 1 : 0
            ]
        );

        return $user;
    }

    public function find($id)
    {
        return $this->model->with([
            'referrer',
            'memberCategoryUser.category',
            'memberCategoryUser.memberCategory',
            'student.province',
            'student.city',
            'student.district',
            'student.village',
            'userAccess.access',
            'userAccess.access.category',
        ])->find($id);
    }
    
    public function getTotalStudent()
    {
        return $this->model->where('level', 2)->count();
    }

    public function getTotalStudentActive()
    {
        return $this->model->where(['level' => 2, 'is_active' => 1])->count();
    }

    public function getTotalStudentNonActive()
    {
        return $this->model->where(['level' => 2, 'is_active' => 0])->count();
    }

    public function getTotalStudentMember()
    {
        return $this->model->where('level', 2)->whereHas('student', function(Builder $query) {
            $query->where('is_member', 1);
        })->count();
    }

    public function getUserCommision($userId)
    {
        return $this->model->with('userCommission')->withCount('commission')->find($userId);
    }

    public function referrals()
    {
        return $this->model->where('referrer_id', Auth::user()->id)->withCount('transactionReferrals')->paginate(10);
    }
}
