<?php

namespace App\Services;

use App\Models\Form;
use App\Repositories\FormRepository;
use Illuminate\Http\Request;

class FormService
{
    protected $formRepository;

    public function __construct(FormRepository $formRepository)
    {
        $this->formRepository = $formRepository;
    }

    public function getAllForms()
    {
        return $this->formRepository->getAllWithCounts();
    }

    public function getFormForEdit($id)
    {
        return $this->formRepository->findWithFields($id);
    }

    public function getFormForSubmissions($id)
    {
        return $this->formRepository->findWithFieldsOnly($id);
    }

    public function createForm(Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'fields' => 'array',
        ]);

        $slug = $this->formRepository->generateUniqueSlug($request->title);

        $settings = [
            'success_message' => 'Thank you! Your submission has been received.',
        ];

        if ($request->has('form_bg')) {
            $settings['form_bg'] = $request->form_bg;
        }

        if ($request->has('form_padding')) {
            $settings['form_padding'] = $request->form_padding;
        }

        $form = $this->formRepository->create([
            'title'     => $request->title,
            'slug'      => $slug,
            'status'    => $request->status ?? 'published',
            'framework' => 'tailwind',
            'settings'  => $settings,
        ]);

        $this->formRepository->syncFields($form, $request->fields ?? []);

        return $form;
    }

    public function updateForm($id, Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'fields' => 'array',
        ]);

        $form = $this->formRepository->findWithFields($id);

        $settings = $form->settings ?? [];

        if ($request->has('form_bg')) {
            $settings['form_bg'] = $request->form_bg;
        }

        if ($request->has('form_padding')) {
            $settings['form_padding'] = $request->form_padding;
        }

        $form = $this->formRepository->update($id, [
            'title'  => $request->title,
            'status' => $request->status ?? $form->status,
            'settings' => $settings,
        ]);

        $this->formRepository->syncFields($form, $request->fields ?? []);

        return $form;
    }

    public function deleteForm($id)
    {
        return $this->formRepository->delete($id);
    }
}