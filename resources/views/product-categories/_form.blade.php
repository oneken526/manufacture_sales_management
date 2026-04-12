@php $productCategory ??= null; @endphp

@if($errors->any())
<div class="mb-4 flex items-start gap-3 bg-red-50 border-l-4 border-red-400 rounded-lg px-4 py-3 text-sm text-red-700">
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">
        カテゴリ名 <span class="text-red-500">*</span>
    </label>
    <input type="text" name="name"
           value="{{ old('name', $productCategory?->name) }}"
           maxlength="100"
           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('name') ? 'border-red-400' : 'border-slate-300' }}">
    @error('name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
