@extends('layouts.app')
@section('title', 'Forms — Form Builder')

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Top nav --}}
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h1 class="text-lg font-semibold text-gray-900">Form Builder</h1>
        </div>
        <a href="{{ route('admin.forms.create') }}"
           class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Form
        </a>
    </header>

    <main class="max-w-5xl mx-auto px-6 py-10">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Your Forms</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $forms->count() }} form{{ $forms->count() !== 1 ? 's' : '' }} total</p>
        </div>

        @if($forms->isEmpty())
            <div class="text-center py-24 bg-white rounded-2xl border border-dashed border-gray-300">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-base font-medium text-gray-700 mb-1">No forms yet</h3>
                <p class="text-sm text-gray-400 mb-6">Create your first form to get started</p>
                <a href="{{ route('admin.forms.create') }}"
                   class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                    Create a form
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($forms as $form)
                <div class="bg-white rounded-2xl border border-gray-200 hover:border-brand-300 hover:shadow-sm transition group flex flex-col">
                    <div class="p-5 flex-1">
                        <div class="flex items-start justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $form->status === 'published' ? 'bg-emerald-50 text-emerald-700' :
                                   ($form->status === 'draft'    ? 'bg-amber-50 text-amber-700' :
                                                                   'bg-gray-100 text-gray-600') }}">
                                {{ ucfirst($form->status) }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $form->created_at->format('M j, Y') }}</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-brand-600 transition">{{ $form->title }}</h3>
                        <p class="text-xs text-gray-500">{{ $form->fields_count }} field{{ $form->fields_count !== 1 ? 's' : '' }}
                            &nbsp;·&nbsp; {{ $form->submissions_count }} submission{{ $form->submissions_count !== 1 ? 's' : '' }}</p>

                        {{-- Public URL --}}
                        <div class="mt-3 flex items-center gap-1.5 bg-gray-50 rounded-lg px-3 py-1.5">
                            <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            <span class="text-xs text-gray-500 truncate">/form/{{ $form->slug }}</span>
                            <button onclick="copyLink('{{ route('form.show', $form->slug) }}')"
                                    class="ml-auto text-gray-400 hover:text-brand-600 transition flex-shrink-0"
                                    title="Copy link">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 px-5 py-3 flex items-center gap-2">
                        <a href="{{ route('admin.forms.edit', $form->id) }}"
                           class="text-xs text-brand-600 hover:text-brand-800 font-medium transition">Edit</a>
                        <span class="text-gray-200">|</span>
                        <a href="{{ route('admin.forms.submissions', $form->id) }}"
                           class="text-xs text-gray-500 hover:text-gray-800 font-medium transition">Submissions</a>
                        <span class="text-gray-200">|</span>
                        <a href="{{ route('form.show', $form->slug) }}" target="_blank"
                           class="text-xs text-gray-500 hover:text-gray-800 font-medium transition">Preview ↗</a>
                        <div class="ml-auto">
                            <button onclick="deleteForm({{ $form->id }}, this)"
                                    class="text-xs text-red-400 hover:text-red-600 font-medium transition">Delete</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </main>
</div>

{{-- Toast --}}
<div id="toast" class="fixed bottom-5 right-5 z-50 hidden items-center gap-2 bg-gray-900 text-white text-sm px-4 py-3 rounded-xl shadow-lg">
    <span id="toast-msg"></span>
</div>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => showToast('Link copied!'));
}

function deleteForm(id, btn) {
    if (!confirm('Delete this form and all its submissions? This cannot be undone.')) return;
    fetch(`/admin/forms/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    }).then(r => r.json()).then(data => {
        if (data.success) {
            btn.closest('.bg-white').remove();
            showToast('Form deleted.');
        }
    });
}

function showToast(msg, duration = 2500) {
    const t = document.getElementById('toast');
    document.getElementById('toast-msg').textContent = msg;
    t.classList.remove('hidden');
    t.classList.add('flex');
    setTimeout(() => { t.classList.add('hidden'); t.classList.remove('flex'); }, duration);
}
</script>
@endsection
