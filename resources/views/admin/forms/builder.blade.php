@extends('layouts.app')
@section('title', isset($form) ? 'Edit: ' . $form->title : 'New Form — Builder')

@section('content')
<div class="flex h-screen bg-gray-100 overflow-hidden">

    {{-- ══════════════════════════════════════════════
         LEFT PANEL — Field Library
    ══════════════════════════════════════════════ --}}
    <aside class="w-56 bg-white border-r border-gray-200 flex flex-col flex-shrink-0">
        <div class="px-4 py-4 border-b border-gray-100">
            <a href="{{ route('admin.forms.index') }}"
               class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-gray-700 mb-3 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                All forms
            </a>
            <h2 class="text-sm font-semibold text-gray-800">Field Library</h2>
            <p class="text-xs text-gray-400 mt-0.5">Drag onto canvas</p>
        </div>
        <div class="flex-1 overflow-y-auto py-3 px-3 space-y-1.5" id="palette">
            @foreach($fieldTypes as $ft)
            <div draggable="true"
                 class="palette-item flex items-center gap-2.5 px-3 py-2.5 border border-gray-200 rounded-xl cursor-grab
                        hover:border-brand-400 hover:bg-brand-50 transition select-none group"
                 data-id="{{ $ft->id }}"
                 data-type="{{ $ft->type }}"
                 data-label="{{ $ft->name }}"
                 data-settings="{{ json_encode($ft->default_settings) }}"
                 data-validation="{{ json_encode($ft->default_validation) }}">
                <span class="text-base">@include('admin.forms.partials.field-icon', ['type' => $ft->type])</span>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-700 group-hover:text-brand-700 truncate">{{ $ft->name }}</p>
                    <p class="text-[10px] text-gray-400 truncate">{{ $ft->type }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </aside>

    {{-- ══════════════════════════════════════════════
         CENTER — Canvas
    ══════════════════════════════════════════════ --}}
    <main class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Toolbar --}}
        <div class="bg-white border-b border-gray-200 px-5 py-3 flex items-center gap-3 flex-shrink-0">
            <input id="form_title"
                   value="{{ $form->title ?? '' }}"
                   placeholder="Untitled Form"
                   class="flex-1 text-lg font-semibold bg-transparent border-0 outline-none text-gray-900 placeholder-gray-300 min-w-0">

            <select id="form_status"
                    class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 text-gray-600 bg-white outline-none cursor-pointer">
                <option value="published" {{ (isset($form) && $form->status === 'published') ? 'selected' : '' }}>Published</option>
                <option value="draft"     {{ (isset($form) && $form->status === 'draft')     ? 'selected' : '' }}>Draft</option>
                <option value="archived"  {{ (isset($form) && $form->status === 'archived')  ? 'selected' : '' }}>Archived</option>
            </select>

            <select id="form_bg"
                    class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 text-gray-600 bg-white outline-none cursor-pointer">
                <option value="">Default BG</option>
                <option value="bg-white">White</option>
                <option value="bg-gray-50">Light Gray</option>
                <option value="bg-blue-50">Light Blue</option>
                <option value="bg-green-50">Light Green</option>
            </select>

            <select id="form_padding"
                    class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 text-gray-600 bg-white outline-none cursor-pointer">
                <option value="px-8 py-7">Normal</option>
                <option value="px-4 py-4">Compact</option>
                <option value="px-12 py-10">Spacious</option>
            </select>

            @if(isset($form))
                <a href="{{ route('form.show', $form->slug) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-brand-600 border border-gray-200 rounded-lg px-3 py-1.5 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Preview
                </a>
            @endif

            <button id="save-btn" onclick="saveForm()"
                    class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium
                           px-4 py-2 rounded-lg transition disabled:opacity-60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                </svg>
                <span id="save-label">Save Form</span>
            </button>
        </div>

        {{-- Drop Canvas --}}
        <div class="flex-1 overflow-y-auto p-6">
            <div id="canvas"
                 class="max-w-2xl mx-auto min-h-[500px] bg-white rounded-2xl border-2 border-dashed border-gray-200
                        p-5 space-y-3 transition-colors"
                 ondragover="onCanvasDragOver(event)"
                 ondrop="onCanvasDrop(event)"
                 ondragleave="onCanvasDragLeave(event)">

                {{-- Empty State --}}
                <div id="empty-state" class="flex flex-col items-center justify-center h-80 gap-3 text-gray-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    <p class="text-sm font-medium text-gray-400">Drag fields here to build your form</p>
                    <p class="text-xs text-gray-300">Fields will appear in this order on the public form</p>
                </div>
            </div>

            <p class="text-center text-xs text-gray-400 mt-4" id="field-counter">0 fields</p>
        </div>
    </main>

    {{-- ══════════════════════════════════════════════
         RIGHT PANEL — Properties
    ══════════════════════════════════════════════ --}}
    <aside class="w-64 bg-white border-l border-gray-200 flex flex-col flex-shrink-0">
        <div class="px-4 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800">Properties</h3>
        </div>
        <div class="flex-1 overflow-y-auto" id="props-panel">
            <div class="flex flex-col items-center justify-center h-48 gap-2 text-center px-4">
                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/>
                </svg>
                <p class="text-xs text-gray-400">Click a field on the canvas to edit its properties</p>
            </div>
        </div>
    </aside>
</div>

{{-- ══════════════════════════════════════════════
     Toast Notification
══════════════════════════════════════════════ --}}
<div id="toast"
     class="fixed bottom-5 right-5 z-50 hidden items-center gap-2.5 text-white text-sm px-4 py-3 rounded-xl shadow-lg max-w-xs">
    <svg id="toast-icon-ok" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    <svg id="toast-icon-err" class="w-4 h-4 flex-shrink-0 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
    </svg>
    <span id="toast-msg"></span>
</div>

{{-- Pass existing form data to JS --}}
<script>
const FORM_ID = @json($form->id ?? null);
const CSRF    = document.querySelector('meta[name="csrf-token"]').content;
const FORM_BG = @json(isset($form) ? ($form->settings ?? [])['form_bg'] ?? '' : '');
const FORM_PADDING = @json(isset($form) ? ($form->settings ?? [])['form_padding'] ?? 'px-8 py-7' : 'px-8 py-7');

// Preload existing fields when editing
@php
$formFields = isset($form)
    ? $form->fields->map(fn($f) => [
        'uid'           => $f->id,
        'field_type_id' => $f->field_type_id,
        'type'          => $f->fieldType->type,
        'label'         => $f->label,
        'name'          => $f->name,
        'settings'      => $f->settings   ?? [],
        'validation'    => $f->validation  ?? [],
        'styles'        => $f->styles      ?? [],
    ])->values()->toArray()
    : [];
@endphp
let formFields = @json($formFields);

let selectedUid = null;
let dragPayload  = null;   // data from palette drag
let sortable     = null;

// ─── DOM refs ─────────────────────────────────────────────────
const canvas       = document.getElementById('canvas');
const emptyState   = document.getElementById('empty-state');
const propsPanel   = document.getElementById('props-panel');
const fieldCounter = document.getElementById('field-counter');

// ─── Palette drag start ───────────────────────────────────────
document.querySelectorAll('.palette-item').forEach(item => {
    item.addEventListener('dragstart', e => {
        dragPayload = {
            field_type_id: parseInt(item.dataset.id),
            type:          item.dataset.type,
            label:         item.dataset.label,
            settings:      JSON.parse(item.dataset.settings  || '{}'),
            validation:    JSON.parse(item.dataset.validation || '{}'),
        };
        e.dataTransfer.effectAllowed = 'copy';
    });
    item.addEventListener('dragend', () => { dragPayload = null; });
});

// ─── Canvas drop zone ─────────────────────────────────────────
function onCanvasDragOver(e) {
    if (!dragPayload) return;
    e.preventDefault();
    canvas.classList.add('border-brand-400', 'bg-brand-50');
}
function onCanvasDragLeave(e) {
    if (!canvas.contains(e.relatedTarget)) {
        canvas.classList.remove('border-brand-400', 'bg-brand-50');
    }
}
function onCanvasDrop(e) {
    e.preventDefault();
    canvas.classList.remove('border-brand-400', 'bg-brand-50');
    if (!dragPayload) return;
    addField(dragPayload);
    dragPayload = null;
}

// ─── Add field ────────────────────────────────────────────────
function addField(payload) {
    const uid = Date.now() + Math.random();
    const slug = slugify(payload.label) + '_' + formFields.length;

    formFields.push({
        uid,
        field_type_id: payload.field_type_id,
        type:          payload.type,
        label:         payload.label,
        name:          slug,
        settings:      payload.settings  || {},
        validation:    payload.validation || {},
        styles:        { width: 'full' },
    });

    renderCanvas();
    selectField(uid);
}

// ─── Render canvas ────────────────────────────────────────────
function renderCanvas() {
    // Remove existing field cards
    canvas.querySelectorAll('.field-card').forEach(el => el.remove());

    if (formFields.length === 0) {
        emptyState.classList.remove('hidden');
        fieldCounter.textContent = '0 fields';
        return;
    }

    emptyState.classList.add('hidden');
    fieldCounter.textContent = formFields.length + ' field' + (formFields.length !== 1 ? 's' : '');

    formFields.forEach(field => {
        const card = buildFieldCard(field);
        canvas.appendChild(card);
    });

    // Init / re-init SortableJS
    if (sortable) sortable.destroy();
    sortable = new Sortable(canvas, {
        animation:  150,
        handle:     '.drag-handle',
        filter:     '#empty-state',
        onEnd() {
            // Sync order from DOM back to formFields array
            const order = [...canvas.querySelectorAll('.field-card')].map(el => el.dataset.uid);
            formFields.sort((a, b) => order.indexOf(String(a.uid)) - order.indexOf(String(b.uid)));
        }
    });
}

function buildFieldCard(field) {
    const isSelected = field.uid === selectedUid;
    const div = document.createElement('div');
    div.className = `field-card flex items-center gap-3 px-4 py-3 rounded-xl border cursor-pointer transition
        ${isSelected
            ? 'border-brand-500 bg-brand-50 ring-2 ring-brand-200'
            : 'border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50'}`;
    div.dataset.uid = field.uid;

    div.innerHTML = `
        <span class="drag-handle cursor-grab text-gray-300 hover:text-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 8h16M4 16h16"/>
            </svg>
        </span>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-800 truncate">
                ${escHtml(field.label)}
                ${field.validation?.required ? '<span class="text-red-500 ml-0.5">*</span>' : ''}
            </p>
            <p class="text-xs text-gray-400 truncate">${field.type} · ${escHtml(field.name)}</p>
        </div>
        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full flex-shrink-0">${field.type}</span>
        <button class="delete-btn text-gray-300 hover:text-red-500 transition flex-shrink-0"
                onclick="deleteField('${field.uid}', event)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;

    div.addEventListener('click', e => {
        if (e.target.closest('.delete-btn') || e.target.closest('.drag-handle')) return;
        selectField(field.uid);
    });

    return div;
}

// ─── Select field → show properties ──────────────────────────
function selectField(uid) {
    selectedUid = uid;
    renderCanvas(); // refresh selected state

    const field = formFields.find(f => f.uid === uid);
    if (!field) return;

    const hasOptions = ['select', 'checkbox', 'radio'].includes(field.type);
    const optionsValue = (field.settings?.options || []).join('\n');

    propsPanel.innerHTML = `
    <div class="p-4 space-y-4">

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Label</label>
            <input id="p-label" value="${escHtml(field.label)}"
                   class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Field Name <span class="text-gray-400 font-normal">(slug)</span></label>
            <input id="p-name" value="${escHtml(field.name)}"
                   class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100 font-mono">
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Placeholder</label>
            <input id="p-placeholder" value="${escHtml(field.settings?.placeholder || '')}"
                   class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
        </div>

        ${hasOptions ? `
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Options <span class="text-gray-400 font-normal">(one per line)</span></label>
            <textarea id="p-options" rows="4"
                      class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100 resize-none font-mono">${escHtml(optionsValue)}</textarea>
        </div>
        ` : ''}

        ${field.type === 'number' ? `
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Min</label>
                <input id="p-min" type="number" value="${field.settings?.min ?? ''}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Max</label>
                <input id="p-max" type="number" value="${field.settings?.max ?? ''}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-400">
            </div>
        </div>
        ` : ''}

        <div class="pt-1 border-t border-gray-100">
            <label class="flex items-center gap-2.5 cursor-pointer">
                <input id="p-required" type="checkbox" ${field.validation?.required ? 'checked' : ''}
                       class="w-4 h-4 accent-brand-600 rounded">
                <span class="text-sm text-gray-700">Required field</span>
            </label>
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Regex Pattern</label>
            <input id="p-regex" value="${escHtml(field.validation?.regex || '')}"
                   class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100 font-mono">
        </div>

        <div class="pt-1">
            <p class="text-xs text-gray-400">Type: <span class="font-mono">${field.type}</span></p>
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Width</label>
            <select id="p-width" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-400">
                <option value="full" ${field.styles?.width === 'full' ? 'selected' : ''}>Full Width</option>
                <option value="half" ${field.styles?.width === 'half' ? 'selected' : ''}>Half Width</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Background Color</label>
            <select id="p-bg-color" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-400">
                <option value="" ${!field.styles?.background ? 'selected' : ''}>Default</option>
                <option value="bg-gray-50" ${field.styles?.background === 'bg-gray-50' ? 'selected' : ''}>Light Gray</option>
                <option value="bg-blue-50" ${field.styles?.background === 'bg-blue-50' ? 'selected' : ''}>Light Blue</option>
                <option value="bg-green-50" ${field.styles?.background === 'bg-green-50' ? 'selected' : ''}>Light Green</option>
                <option value="bg-yellow-50" ${field.styles?.background === 'bg-yellow-50' ? 'selected' : ''}>Light Yellow</option>
                <option value="bg-red-50" ${field.styles?.background === 'bg-red-50' ? 'selected' : ''}>Light Red</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Text Color</label>
            <select id="p-text-color" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-400">
                <option value="" ${!field.styles?.text_color ? 'selected' : ''}>Default</option>
                <option value="text-gray-700" ${field.styles?.text_color === 'text-gray-700' ? 'selected' : ''}>Dark Gray</option>
                <option value="text-blue-700" ${field.styles?.text_color === 'text-blue-700' ? 'selected' : ''}>Blue</option>
                <option value="text-green-700" ${field.styles?.text_color === 'text-green-700' ? 'selected' : ''}>Green</option>
                <option value="text-red-700" ${field.styles?.text_color === 'text-red-700' ? 'selected' : ''}>Red</option>
                <option value="text-purple-700" ${field.styles?.text_color === 'text-purple-700' ? 'selected' : ''}>Purple</option>
            </select>
        </div>

        <div class="pt-2 border-t border-gray-100">
            <button onclick="deleteField('${field.uid}')"
                    class="w-full text-xs text-red-500 hover:text-red-700 border border-red-200 hover:border-red-400
                           hover:bg-red-50 rounded-lg px-3 py-2 transition font-medium">
                Remove Field
            </button>
        </div>
    </div>`;

    // Wire up live property changes
    document.getElementById('p-label').addEventListener('input', e => {
        field.label = e.target.value;
        renderCanvas();
    });
    document.getElementById('p-name').addEventListener('input', e => {
        field.name = e.target.value.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
        e.target.value = field.name;
        renderCanvas();
    });
    document.getElementById('p-placeholder').addEventListener('input', e => {
        field.settings = { ...(field.settings || {}), placeholder: e.target.value };
    });
    document.getElementById('p-required').addEventListener('change', e => {
        field.validation = { ...(field.validation || {}), required: e.target.checked };
        renderCanvas();
    });
    document.getElementById('p-regex').addEventListener('input', e => {
        field.validation = { ...(field.validation || {}), regex: e.target.value };
    });
    if (hasOptions) {
        document.getElementById('p-options').addEventListener('input', e => {
            const opts = e.target.value.split('\n').map(s => s.trim()).filter(Boolean);
            field.settings = { ...(field.settings || {}), options: opts };
        });
    }
    if (field.type === 'number') {
        document.getElementById('p-min').addEventListener('input', e => {
            field.settings = { ...(field.settings || {}), min: e.target.value };
        });
        document.getElementById('p-max').addEventListener('input', e => {
            field.settings = { ...(field.settings || {}), max: e.target.value };
        });
    }
    document.getElementById('p-width').addEventListener('change', e => {
        field.styles = { ...(field.styles || {}), width: e.target.value };
    });
    document.getElementById('p-bg-color').addEventListener('change', e => {
        field.styles = { ...(field.styles || {}), background: e.target.value };
    });
    document.getElementById('p-text-color').addEventListener('change', e => {
        field.styles = { ...(field.styles || {}), text_color: e.target.value };
    });
}

// ─── Delete field ─────────────────────────────────────────────
function deleteField(uid, e) {
    if (e) e.stopPropagation();
    uid = parseFloat(uid);  // Convert string to number for comparison
    formFields = formFields.filter(f => f.uid !== uid);
    if (selectedUid === uid) {
        selectedUid = null;
        propsPanel.innerHTML = `
            <div class="flex flex-col items-center justify-center h-48 gap-2 text-center px-4">
                <p class="text-xs text-gray-400">Click a field on the canvas to edit its properties</p>
            </div>`;
    }
    renderCanvas();
}

// ─── Save form ────────────────────────────────────────────────
async function saveForm() {
    const btn   = document.getElementById('save-btn');
    const label = document.getElementById('save-label');
    const title = document.getElementById('form_title').value.trim();

    if (!title) {
        showToast('Please enter a form title.', 'error');
        document.getElementById('form_title').focus();
        return;
    }

    btn.disabled = true;
    label.textContent = 'Saving…';

    const payload = {
        title:  title,
        status: document.getElementById('form_status').value,
        form_bg: document.getElementById('form_bg').value,
        form_padding: document.getElementById('form_padding').value,
        fields: formFields.map((f, i) => ({
            field_type_id: f.field_type_id,
            label:         f.label,
            name:          f.name,
            order:         i,
            settings:      f.settings   || {},
            validation:    f.validation  || {},
            styles:        f.styles      || {},
        })),
    };

    const url    = FORM_ID ? `/admin/forms/${FORM_ID}` : '/admin/forms';
    const method = FORM_ID ? 'PUT' : 'POST';

    try {
        const res  = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept':       'application/json',
            },
            body: JSON.stringify(payload),
        });

        const data = await res.json();

        if (res.ok && data.success) {
            showToast('Form saved successfully!', 'success');
            // Redirect to edit URL after first save so subsequent saves use PUT
            if (!FORM_ID && data.edit_url) {
                setTimeout(() => { window.location.href = data.edit_url; }, 800);
            }
        } else {
            const errors = data.errors ? Object.values(data.errors).flat().join(' ') : 'Save failed.';
            showToast(errors, 'error');
        }
    } catch {
        showToast('Network error. Please try again.', 'error');
    } finally {
        btn.disabled = false;
        label.textContent = 'Save Form';
    }
}

// ─── Helpers ──────────────────────────────────────────────────
function slugify(str) {
    return str.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
}

function escHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function showToast(msg, type = 'success') {
    const t    = document.getElementById('toast');
    const ok   = document.getElementById('toast-icon-ok');
    const err  = document.getElementById('toast-icon-err');
    document.getElementById('toast-msg').textContent = msg;

    if (type === 'error') {
        t.className = t.className.replace(/bg-\w+-\d+/, '');
        t.classList.add('bg-red-600');
        ok.classList.add('hidden');
        err.classList.remove('hidden');
    } else {
        t.className = t.className.replace(/bg-\w+-\d+/, '');
        t.classList.add('bg-emerald-700');
        ok.classList.remove('hidden');
        err.classList.add('hidden');
    }

    t.classList.remove('hidden');
    t.classList.add('flex');
    setTimeout(() => { t.classList.add('hidden'); t.classList.remove('flex'); }, 3000);
}

// ─── Initial render ───────────────────────────────────────────
renderCanvas();
if (formFields.length > 0) selectField(formFields[0].uid);

// ─── Form settings ────────────────────────────────────────────
document.getElementById('form_bg').value = FORM_BG;
document.getElementById('form_padding').value = FORM_PADDING;

document.getElementById('form_bg').addEventListener('change', e => {
    // Update canvas background if needed
    const canvas = document.getElementById('canvas');
    canvas.className = canvas.className.replace(/bg-\w+-\d+/g, '');
    if (e.target.value) canvas.classList.add(e.target.value);
});

document.getElementById('form_padding').addEventListener('change', e => {
    // Update canvas padding
    const canvas = document.getElementById('canvas');
    canvas.className = canvas.className.replace(/px-\d+ py-\d+/g, e.target.value);
});
</script>
@endsection
