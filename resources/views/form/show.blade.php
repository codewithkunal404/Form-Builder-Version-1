<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $form->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe',
                            400: '#818cf8', 500: '#6366f1', 600: '#4f46e5',
                            700: '#4338ca', 800: '#3730a3',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-12 px-4">

<div class="max-w-xl mx-auto">

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="bg-brand-600 px-8 py-7">
            <h1 class="text-xl font-bold text-white">{{ $form->title }}</h1>
            @if($form->description)
                <p class="text-brand-200 text-sm mt-1">{{ $form->description }}</p>
            @endif
        </div>

        {{-- Success State (hidden initially) --}}
        <div id="success-state" class="hidden px-8 py-12 text-center">
            <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Submitted!</h2>
            <p id="success-msg" class="text-gray-500 text-sm">
                {{ $form->settings['success_message'] ?? 'Thank you! Your response has been recorded.' }}
            </p>
        </div>

        {{-- Form --}}
        @php
            $formSettings = $form->settings ?? [];
            $formBg = $formSettings['form_bg'] ?? '';
            $formPadding = $formSettings['form_padding'] ?? 'px-8 py-7';
        @endphp
        <form id="public-form" class="{{ $formPadding }} space-y-5 {{ $formBg }}" novalidate>
            @csrf

            @foreach($form->fields as $field)
            @php
                $settings   = $field->settings   ?? [];
                $validation = $field->validation  ?? [];
                $styles     = $field->styles     ?? [];
                $type       = $field->fieldType->type;
                $required   = !empty($validation['required']);
                $placeholder = $settings['placeholder'] ?? '';
                $widthClass = ($styles['width'] ?? 'full') === 'half' ? 'md:w-1/2 md:inline-block md:align-top md:mr-4' : '';
                $inputClass = 'w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800
                               focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100
                               placeholder-gray-400 transition bg-gray-50 focus:bg-white';
                $additionalInputClasses = ($styles['background'] ?? '') . ' ' . ($styles['text_color'] ?? '');
                $inputClass .= ' ' . trim($additionalInputClasses);
            @endphp

            <div class="field-group {{ $widthClass }}" data-field="{{ $field->name }}">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    {{ $field->label }}
                    @if($required)
                        <span class="text-red-500 ml-0.5">*</span>
                    @endif
                </label>

                @if($type === 'textarea')
                    <textarea name="{{ $field->name }}"
                              rows="{{ $settings['rows'] ?? 4 }}"
                              placeholder="{{ $placeholder }}"
                              {{ $required ? 'required' : '' }}
                              class="{{ $inputClass }} resize-none"></textarea>

                @elseif($type === 'select')
                    <select name="{{ $field->name }}"
                            {{ $required ? 'required' : '' }}
                            class="{{ $inputClass }} cursor-pointer">
                        <option value="">{{ $placeholder ?: 'Select an option...' }}</option>
                        @foreach($settings['options'] ?? [] as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>

                @elseif($type === 'checkbox')
                    <div class="space-y-2">
                        @foreach($settings['options'] ?? [] as $option)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox"
                                       name="{{ $field->name }}[]"
                                       value="{{ $option }}"
                                       class="w-4 h-4 accent-brand-600 rounded">
                                <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>

                @elseif($type === 'radio')
                    <div class="space-y-2">
                        @foreach($settings['options'] ?? [] as $option)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="radio"
                                       name="{{ $field->name }}"
                                       value="{{ $option }}"
                                       {{ $required ? 'required' : '' }}
                                       class="w-4 h-4 accent-brand-600">
                                <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>

                @elseif($type === 'file')
                    <div class="border-2 border-dashed border-gray-200 rounded-xl px-4 py-5 text-center hover:border-brand-300 transition cursor-pointer"
                         onclick="document.getElementById('file-{{ $field->name }}').click()">
                        <svg class="w-6 h-6 text-gray-400 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <p class="text-xs text-gray-400">Click to upload a file</p>
                        <input type="file"
                               id="file-{{ $field->name }}"
                               name="{{ $field->name }}"
                               {{ $required ? 'required' : '' }}
                               accept="{{ $settings['accept'] ?? '*' }}"
                               {{ !empty($settings['multiple']) ? 'multiple' : '' }}
                               class="hidden">
                    </div>

                @else
                    <input type="{{ $type }}"
                           name="{{ $field->name }}"
                           placeholder="{{ $placeholder }}"
                           {{ $required ? 'required' : '' }}
                           @if(!empty($validation['regex'])) pattern="{{ $validation['regex'] }}" @endif
                           @if(!empty($settings['min'])) min="{{ $settings['min'] }}" @endif
                           @if(!empty($settings['max'])) max="{{ $settings['max'] }}" @endif
                           class="{{ $inputClass }}">
                @endif

                {{-- Inline error message placeholder --}}
                <p class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>
            @endforeach

            <div class="pt-2">
                <button type="submit" id="submit-btn"
                        class="w-full bg-brand-600 hover:bg-brand-700 text-white font-medium text-sm
                               py-3 rounded-xl transition disabled:opacity-60 flex items-center justify-center gap-2">
                    <span id="submit-label">Submit</span>
                    <svg id="submit-spinner" class="hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <p class="text-center text-xs text-gray-400 mt-5">Powered by Form Builder</p>
</div>

<script>
document.getElementById('public-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const btn     = document.getElementById('submit-btn');
    const label   = document.getElementById('submit-label');
    const spinner = document.getElementById('submit-spinner');

    // Clear previous errors
    document.querySelectorAll('.field-error').forEach(el => {
        el.textContent = '';
        el.classList.add('hidden');
    });
    document.querySelectorAll('input, textarea, select').forEach(el => {
        el.classList.remove('border-red-400', 'ring-red-100');
    });

    btn.disabled  = true;
    label.textContent = 'Submitting…';
    spinner.classList.remove('hidden');

    const formData = new FormData(this);

    try {
        const res  = await fetch('{{ route("form.submit", $form->slug) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept':       'application/json',
            },
            body: formData,
        });

        const data = await res.json();

        if (res.ok && data.success) {
            // Show success state
            document.getElementById('public-form').classList.add('hidden');
            document.getElementById('success-state').classList.remove('hidden');
            if (data.message) {
                document.getElementById('success-msg').textContent = data.message;
            }
        } else if (res.status === 422 && data.errors) {
            // Show validation errors inline
            Object.entries(data.errors).forEach(([name, messages]) => {
                const group = document.querySelector(`[data-field="${name}"]`);
                if (!group) return;
                const errEl = group.querySelector('.field-error');
                const input = group.querySelector('input, textarea, select');
                if (errEl) {
                    errEl.textContent = messages[0];
                    errEl.classList.remove('hidden');
                }
                if (input) {
                    input.classList.add('border-red-400', 'ring-1', 'ring-red-100');
                }
            });
        } else {
            alert('Something went wrong. Please try again.');
        }
    } catch {
        alert('Network error. Please check your connection and try again.');
    } finally {
        btn.disabled = false;
        label.textContent = 'Submit';
        spinner.classList.add('hidden');
    }
});
</script>
</body>
</html>
