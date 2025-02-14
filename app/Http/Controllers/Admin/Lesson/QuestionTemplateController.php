<?php

namespace App\Http\Controllers\Admin\Lesson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Lesson\QuestionTemplateRepository;
use App\Repositories\Lesson\QuestionTitleRepository;
use App\Http\Requests\Lesson\QuestionTemplateRequest;
use Session;

class QuestionTemplateController extends Controller
{
    protected $questionTemplateRepository;
    protected $questionTitleRepository;

    public function __construct(QuestionTemplateRepository $questionTemplateRepository, QuestionTitleRepository $questionTitleRepository)
    {
        $this->questionTemplateRepository = $questionTemplateRepository;
        $this->questionTitleRepository = $questionTitleRepository;
    }

    private function findQuestionTitle($questionTitleId)
    {
        $questionTitle = $this->questionTitleRepository->find($questionTitleId);
        if (!$questionTitle) {
            abort(404, 'uppss....');
        }
        return $questionTitle;
    }

    public function index($questionTitleId, Request $request)
    {
        $questionTitle = $this->findQuestionTitle($questionTitleId);
        Session::put('queryStringQuestionTemplates', $request->getQueryString());

        return inertia('Admin/Lesson/QuestionTemplate/Index', [
            'questionTemplates' => $this->questionTemplateRepository->getAllPaginatedWithParams($request),
            'questionTitle' => $questionTitle
        ]);
    }

    public function create($questionTitleId)
    {
        $questionTitle = $this->findQuestionTitle($questionTitleId);

        return inertia('Admin/Lesson/QuestionTemplate/Create', [
            'questionTitle' => $questionTitle,
        ]);
    }

    public function store($questionTitleId, QuestionTemplateRequest $request)
    {
        try {
            $this->findQuestionTitle($questionTitleId);
            $this->questionTemplateRepository->create($request->all());

            return redirect()->route('admin.question-titles.question-templates.index', $questionTitleId);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    public function edit($questionTitleId, $id)
    {
        $questionTitle = $this->findQuestionTitle($questionTitleId);
        $questionTemplate = $this->questionTemplateRepository->find($id);
        if (!$questionTemplate) {
            abort(404, 'uppss....');
        }

        return inertia('Admin/Lesson/QuestionTemplate/Edit', [
            'questionTitle' => $questionTitle,
            'questionTemplate' => $questionTemplate,
        ]);
    }

    public function update($questionTitleId, QuestionTemplateRequest $request, $id)
    {
        try {
            $this->findQuestionTitle($questionTitleId);
            $question = $this->questionTemplateRepository->find($id);
            if (!$question) {
                abort(404, 'uppss....');
            }

            $this->questionTemplateRepository->update($request->all(), $id);
            $queryString = Session::get('queryStringQuestionTemplates');

            return redirect(route('admin.question-titles.question-templates.index', $questionTitleId) . (!empty($queryString) ? '?' . $queryString : ''));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine());

            return redirect()->back()->withInput($request->all());
        }
    }

    public function destroy($questionTitleId, $id)
    {
        try {
            $this->findQuestionTitle($questionTitleId);
            $question = $this->questionTemplateRepository->find($id);
            if (!$question) {
                abort(404, 'uppss....');
            }

            $this->questionTemplateRepository->delete($id);

            return redirect()->route('admin.question-titles.question-templates.index', $questionTitleId);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage() . ' in file :' . $e->getFile() . ' line: ' . $e->getLine());

            return redirect()->back();
        }
    }
}
