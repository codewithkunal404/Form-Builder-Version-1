<?php

namespace App\Repositories;

use App\Models\Form;
use Illuminate\Support\Str;

class FormRepository
{
    public function getAllWithCounts()
    {
        return Form::withCount(['fields', 'submissions'])->latest()->get();
    }

    public function findWithFields($id)
    {
        return Form::with('fields.fieldType')->findOrFail($id);
    }

    public function findWithFieldsOnly($id)
    {
        return Form::with('fields')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Form::create($data);
    }

    public function update($id, array $data)
    {
        $form = Form::findOrFail($id);
        $form->update($data);
        return $form;
    }

    public function delete($id)
    {
        $form = Form::findOrFail($id);
        $form->delete();
        return $form;
    }

    public function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (Form::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    public function syncFields(Form $form, array $fields): void
    {
        $form->fields()->delete();

        foreach ($fields as $i => $f) {
            $form->fields()->create([
                'field_type_id' => $f['field_type_id'],
                'label'         => $f['label'] ?? 'Field',
                'name'          => $f['name'] ?? Str::slug($f['label'] ?? 'field') . '_' . $i,
                'order'         => $i,
                'settings'      => $f['settings'] ?? [],
                'validation'    => $f['validation'] ?? [],
                'styles'        => $f['styles'] ?? ['width' => 'full'],
            ]);
        }
    }
}