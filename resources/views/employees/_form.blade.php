@php
    $employee = $employee ?? null;
@endphp

<div class="row g-4">

    <div class="col-12">
        <label class="label-mono form-label d-block mb-2">Profile photo</label>
        <div class="d-flex align-items-center gap-3">
            <div class="photo-preview" id="photo-preview">
                @if (isset($employee) && $employee->profile_image_url)
                    <img src="{{ $employee->profile_image_url }}" alt="{{ $employee->name }}">
                @else
                    <span>{{ isset($employee) ? strtoupper(substr($employee->name, 0, 1)) : '+' }}</span>
                @endif
            </div>
            <div>
                <label for="profile_image" class="btn btn-outline-ink btn-sm mb-1">Choose photo</label>
                <input id="profile_image" type="file" name="profile_image" accept="image/png,image/jpeg,image/webp"
                    class="d-none" onchange="previewPhoto(this)">
                <p class="text-slate small mb-0">JPG, PNG or WEBP · max 2MB</p>
            </div>
        </div>
    </div>

    <div class="col-12">
        <hr class="border-dashed my-1">
    </div>

    <div class="col-12">
        <label for="name" class="label-mono form-label">Full name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $employee->name ?? '') }}" required
            class="form-control" placeholder="Enter Your Full Name">
    </div>

    <div class="col-md-6">
        <label for="email" class="label-mono form-label">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $employee->email ?? '') }}" required
            class="form-control" placeholder="you@example.com">
    </div>

    <div class="col-md-6">
        <label for="phone" class="label-mono form-label">Phone</label>
        <input id="phone" type="text" name="phone" value="{{ old('phone', $employee->phone ?? '') }}"
            class="form-control" placeholder="Enter Your Mobile Number">
    </div>

    <div class="col-md-6">
        <label for="department_id" class="label-mono form-label">Department</label>
        <select id="department_id" name="department_id" required class="form-select">
            <option value="">Select department</option>
            @foreach ($departments as $dept)
                <option value="{{ $dept->id }}" @selected(old('department_id', $employee->department_id ?? '') == $dept->id)>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label for="designation" class="label-mono form-label">Designation</label>
        <input id="designation" type="text" name="designation" value="{{ old('designation', $employee->designation ?? '') }}" required
            class="form-control" placeholder="Software Engineer">
    </div>

    <div class="col-md-4">
        <label for="salary" class="label-mono form-label">Salary</label>
        <div class="input-group">
            <span class="input-group-text">Rs</span>
            <input id="salary" type="number" step="0.01" min="0" name="salary" value="{{ old('salary', $employee->salary ?? '') }}" required
                class="form-control" placeholder="85000">
        </div>
    </div>

    <div class="col-md-4">
        <label for="joining_date" class="label-mono form-label">Joining date</label>
        <input id="joining_date" type="date" name="joining_date"
            value="{{ old('joining_date', isset($employee->joining_date) ? $employee->joining_date->format('Y-m-d') : '') }}" required
            class="form-control">
    </div>

    <div class="col-md-4">
        <label for="status" class="label-mono form-label">Status</label>
        <select id="status" name="status" required class="form-select">
            <option value="active" @selected(old('status', $employee->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $employee->status ?? '') === 'inactive')>Inactive</option>
        </select>
    </div>
</div>

<script>
    function previewPhoto(input) {
        const preview = document.getElementById('photo-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
