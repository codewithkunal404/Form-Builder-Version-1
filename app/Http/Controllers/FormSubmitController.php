<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

class FormSubmitController extends Controller
{
    // GET /form/{slug}
    public function show(string $slug)
    {
        $form = Form::with('fields.fieldType')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('form.show', compact('form'));
    }

    // POST /forms/{slug}/submit
    public function submit(Request $request, string $slug)
    {
        $form = Form::with('fields.fieldType')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Build dynamic validation rules from field definitions
        $rules = [];
        $messages = [];

        foreach ($form->fields as $field) {
            $validation = $field->validation ?? [];
            $fieldRules = [];
            $type = $field->fieldType->type;

            if (!empty($validation['required'])) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            // Type-based rules
            if ($type === 'email') {
                $fieldRules[] = 'email';
            }
            if ($type === 'url') {
                $fieldRules[] = 'url';
            }
            if ($type === 'number') {
                $fieldRules[] = 'numeric';
                if (isset($validation['min']))
                    $fieldRules[] = 'min:' . $validation['min'];
                if (isset($validation['max']))
                    $fieldRules[] = 'max:' . $validation['max'];
            }
            if ($type === 'file') {
                $fieldRules[] = 'file';
                $fieldRules[] = 'max:10240'; // 10MB
            }

            if (!empty($validation['min_length'])) {
                $fieldRules[] = 'min:' . $validation['min_length'];
            }
            if (!empty($validation['max_length'])) {
                $fieldRules[] = 'max:' . $validation['max_length'];
            }

            $rules[$field->name] = $fieldRules;
            $messages[$field->name . '.required'] = $field->label . ' is required.';
            $messages[$field->name . '.email'] = $field->label . ' must be a valid email address.';
        }

        $validated = $request->validate($rules, $messages);

        // Handle file uploads
        $data = [];
        foreach ($form->fields as $field) {
            if ($field->fieldType->type === 'file' && $request->hasFile($field->name)) {
                $path = $request->file($field->name)->store('form-uploads', 'public');
                $data[$field->name] = $path;
            } elseif (isset($validated[$field->name])) {
                // checkbox arrays come as arrays
                $data[$field->name] = is_array($validated[$field->name])
                    ? $validated[$field->name]
                    : $validated[$field->name];
            } else {
                $data[$field->name] = null;
            }
        }

        FormSubmission::create([
            'form_id' => $form->id,
            'data' => $data,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->expectsJson()) {
            $successMessage = $form->settings['success_message']
                ?? 'Thank you! Your submission has been received.';

            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return back()->with('success', $form->settings['success_message']
            ?? 'Thank you! Your submission has been received.');
    }
}
