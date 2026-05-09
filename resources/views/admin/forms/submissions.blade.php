@extends('layouts.app')
@section('title', 'Submissions — ' . $form->title)

@section('content')
<div class="min-h-screen bg-gray-50">

    <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center gap-4">
        <a href="{{ route('admin.forms.index') }}"
           class="text-gray-400 hover:text-gray-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1 min-w-0">
            <h1 class="text-lg font-semibold text-gray-900 truncate">{{ $form->title }}</h1>
            <p class="text-xs text-gray-400">{{ $submissions->total() }} submission{{ $submissions->total() !== 1 ? 's' : '' }}</p>
        </div>
        <a href="{{ route('admin.forms.edit', $form->id) }}"
           class="text-xs text-brand-600 hover:text-brand-800 border border-brand-200 hover:border-brand-400 rounded-lg px-3 py-1.5 transition">
            Edit Form
        </a>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-8">

        @if($submissions->isEmpty())
            <div class="text-center py-24 bg-white rounded-2xl border border-dashed border-gray-300">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-gray-400 text-sm">No submissions yet</p>
                <a href="{{ route('form.show', $form->slug) }}" target="_blank"
                   class="inline-block mt-4 text-xs text-brand-600 hover:underline">
                    Share the form link ↗
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($submissions as $i => $sub)
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-mono text-gray-400">#{{ $sub->id }}</span>
                            <span class="text-xs text-gray-500">{{ $sub->created_at->format('M j, Y · g:i A') }}</span>
                        </div>
                        <span class="text-xs text-gray-400">{{ $sub->ip_address }}</span>
                    </div>
                    <div class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($form->fields as $field)
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">{{ $field->label }}</p>
                            <p class="text-sm text-gray-800 break-words">
                                @php
                                    $val = $sub->data[$field->name] ?? null;
                                @endphp
                                @if(is_array($val))
                                    {{ implode(', ', $val) }}
                                @elseif($val)
                                    {{ $val }}
                                @else
                                    <span class="text-gray-300 italic">—</span>
                                @endif
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $submissions->links() }}
            </div>
        @endif
    </main>
</div>
@endsection
