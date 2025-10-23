@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Add New Marketing Lead</h2>

    <form action="{{ route('marketing.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="client_name" class="form-label">Client Name</label>
            <input type="text" name="client_name" id="client_name" class="form-control">
        </div>

        <div class="mb-3">
            <label for="project_name" class="form-label">Project Name</label>
            <input type="text" name="project_name" id="project_name" class="form-control">
        </div>

        <div class="mb-3">
            <label for="staff" class="form-label">Assigned Staff</label>
            <select name="staff" id="staff" class="form-select">
                @foreach($staffOptions as $staff)
                    <option value="{{ $staff }}">{{ $staff }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="Lead">Lead</option>
                <option value="Follow Up">Follow Up</option>
                <option value="On Progress">On Progress</option>
                <option value="Closed">Closed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('marketing.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
