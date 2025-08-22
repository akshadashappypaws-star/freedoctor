<div id="bulk-message" class="mb-5">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Bulk Message Sender</h4>
        </div>
        <div class="card-body">
            <form id="bulk-message-form" method="POST" action="{{ route('admin.whatsapp.bulk-messages.send') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="excel_file">Upload Excel File <small>(Columns: name [optional], phone [required])</small></label>
                    <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv" required>
                </div>
                <div class="form-group">
                    <label for="template">Select Whatsapp Template</label>
                    <select class="form-control" id="template" name="template" required>
                        <option value="">-- Select Template --</option>
                        <!-- Example options, replace with dynamic -->
                        <option value="greeting">Greeting</option>
                        <option value="reminder">Reminder</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Send</button>
            </form>
            <div id="live-status" class="mt-4">
                <h5>Live Status</h5>
                <ul id="status-list" class="list-group"></ul>
            </div>
            <div id="failed-messages" class="mt-4">
                <h5>Failed Messages</h5>
                <ul id="failed-list" class="list-group"></ul>
            </div>
        </div>
    </div>
</div>
<script>
// Example JS for live status (to be replaced with real-time logic)
document.getElementById('bulk-message-form').onsubmit = function(e) {
    e.preventDefault();
    const statusList = document.getElementById('status-list');
    const failedList = document.getElementById('failed-list');
    statusList.innerHTML = '';
    failedList.innerHTML = '';
    // Simulate sending one by one
    const fakeData = [
        {name: 'John', phone: '1234567890'},
        {name: 'Jane', phone: '9876543210'},
        {name: 'Foo', phone: '1112223333'}
    ];
    let i = 0;
    function sendNext() {
        if (i >= fakeData.length) return;
        const item = fakeData[i];
        const li = document.createElement('li');
        li.className = 'list-group-item';
        li.textContent = `Sending to ${item.name || ''} (${item.phone}) ...`;
        statusList.appendChild(li);
        setTimeout(() => {
            const success = Math.random() > 0.3;
            li.textContent = `${item.name || ''} (${item.phone}): ` + (success ? 'Success' : 'Failed');
            li.className = 'list-group-item ' + (success ? 'list-group-item-success' : 'list-group-item-danger');
            if (!success) {
                const failLi = document.createElement('li');
                failLi.className = 'list-group-item list-group-item-danger';
                failLi.textContent = `${item.name || ''} (${item.phone})`;
                failedList.appendChild(failLi);
            }
            i++;
            sendNext();
        }, 1000);
    }
    sendNext();
};
</script>
