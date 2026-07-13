@php
    $department = $department ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <label for="name" class="label-mono form-label">Department name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $department->name ?? '') }}" required
            class="form-control" placeholder="Engineering">
    </div>

    <div class="col-md-4">
        <label for="code" class="label-mono form-label">Short code</label>
        <input id="code" type="text" name="code" value="{{ old('code', $department->code ?? '') }}" required
            class="form-control text-uppercase" placeholder="ENG" maxlength="20">
    </div>

    <div class="col-12">
        <label for="description" class="label-mono form-label">Description</label>
        <textarea id="description" name="description" rows="3" class="form-control"
            placeholder="What this department is responsible for">{{ old('description', $department->description ?? '') }}</textarea>
    </div>
</div>
