<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FieldType;
use App\Services\FormService;
use Illuminate\Http\Request;

class FormController extends Controller
{
    protected $formService;

    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }

     // GET /admin/forms
    public function index()
    {
        $forms = $this->formService->getAllForms();
        return view('admin.forms.index', compact('forms'));
    }

    // GET /admin/forms/create
    public function create()
    {
        $fieldTypes = FieldType::all();
        return view('admin.forms.builder', compact('fieldTypes'));
    }

    // GET /admin/forms/{id}/edit
    public function edit($id)
    {
        $form = $this->formService->getFormForEdit($id);
        $fieldTypes = FieldType::all();
        return view('admin.forms.builder', compact('form', 'fieldTypes'));
    }

    // POST /admin/forms
    public function store(Request $request)
    {
        $form = $this->formService->createForm($request);

        return response()->json([
            'success'  => true,
            'id'       => $form->id,
            'slug'     => $form->slug,
            'edit_url' => route('admin.forms.edit', $form->id),
        ]);
    }

    // PUT /admin/forms/{id}
    public function update(Request $request, $id)
    {
        $this->formService->updateForm($id, $request);

        return response()->json(['success' => true]);
    }

    // DELETE /admin/forms/{id}
    public function destroy($id)
    {
        $this->formService->deleteForm($id);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.forms.index')
                         ->with('success', 'Form deleted successfully.');
    }

    // GET /admin/forms/{id}/submissions
    public function submissions($id)
    {
        $form = $this->formService->getFormForSubmissions($id);
        $submissions = $form->submissions()->latest()->paginate(20);
        return view('admin.forms.submissions', compact('form', 'submissions'));
    }
}
