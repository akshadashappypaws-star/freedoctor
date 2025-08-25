@extends('admin.pages.whatsapp.layouts.whatsapp')

@section('title', 'Create Workflow')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create New Workflow</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp.dashboard') }}">WhatsApp</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp.workflows') }}">Workflows</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Workflow Scenario</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.whatsapp.workflows.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Workflow Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Scenario Template</label>
                                    <select class="form-control" name="template" required>
                                        <option value="">Select Template</option>
                                        @if(isset($scenarioTemplates))
                                            @foreach($scenarioTemplates as $templateId => $template)
                                                <option value="{{ $templateId }}">{{ $template['name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.whatsapp.workflows') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Workflow</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
