<div id="flow-data" class="mb-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Whatsapp Flow Data</h4>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.whatsapp.flow-data') }}">
                <div class="form-group">
                    <label for="template">Select Whatsapp Template</label>
                    <select class="form-control" id="template" name="template" required>
                        <option value="">-- Select Template --</option>
                        @foreach($templates ?? [] as $template)
                            <option value="{{ $template->id }}" {{ request('template') == $template->id ? 'selected' : '' }}>
                                {{ $template->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Get Data</button>
            </form>
            <div class="mt-4">
                <h5>Submitted Data</h5>
                <!-- Example table, replace with dynamic -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Template</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($flowData ?? [] as $data)
                        <tr>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->phone }}</td>
                            <td>{{ $data->template_name }}</td>
                            <td>
                                <span class="badge badge-{{ $data->status === 'success' ? 'success' : 'danger' }}">
                                    {{ ucfirst($data->status) }}
                                </span>
                            </td>
                            <td>{{ $data->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No flow data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
