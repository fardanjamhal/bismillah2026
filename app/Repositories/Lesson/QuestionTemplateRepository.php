<?php

namespace App\Repositories\Lesson;

use App\Models\Lesson\QuestionTemplate;
use App\Repositories\Contracts\Lesson\QuestionTemplateInterface;
use App\Repositories\BaseRepository;

class QuestionTemplateRepository extends BaseRepository implements QuestionTemplateInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new QuestionTemplate();
    }

    public function all($columns =  ['*'])
    {
        return $this->model->orderBy('created_at', 'ASC')->get($columns);
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $limit = (int) $limit;
        $questionTemplates = $this->model->query();

        if (isset($params->search) && !empty($params->search)) {
            $questionTemplates->where('name', 'like', '%' . $params->search . '%');
        }

        $questionTemplates = $questionTemplates->orderBy('created_at', 'ASC')->paginate($limit);

        $questionTemplates->appends([
            'search' => $params->search,
        ]);

        return $questionTemplates;
    }

}
