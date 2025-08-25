@extends('../admin.dashboard')

@section('content')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="p-6  rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Specialties</h1>
        <button id="openModalBtn"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">+ Add
            Specialty</button>
    </div>

    <!-- Search -->
    <input type="text" id="searchInput" placeholder="Search specialties..."
        class="w-full mb-4 px-4 py-2 border rounded shadow-sm text-black">

    <!-- Grid -->
    <div id="specialtyGrid"
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 transition-all duration-300">
        @foreach($specialties as $specialty)
        <div class="specialty-card relative bg-gradient-to-br from-blue-100 to-blue-200 border border-blue-300 rounded-xl shadow-lg p-5 hover:shadow-2xl transition duration-300"
            data-id="{{ $specialty->id }}">
            <!-- Delete button -->
            <button
                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white text-xs px-2 py-1 rounded-full delete-btn transform transition-transform duration-200 hover:scale-125">
                ✕
            </button>
            <!-- Icon -->
            <div class="h-40 flex items-center justify-center bg-white rounded mb-4 shadow-inner">
                <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M12 20h9"></path>
                    <path d="M12 4H3v16h18V4h-9z"></path>
                    <path d="M9 4v16"></path>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-center text-gray-800">{{ $specialty->name }}</h2>
        </div>
        @endforeach
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
        <h2 class="text-xl font-bold mb-4">Add New Specialty</h2>
        <form id="addSpecialtyForm">
            @csrf
            <input type="text" name="name" id="specialtyName" required placeholder="Enter specialty name"
                class="w-full mb-4 px-4 py-2 border rounded">
            <div class="flex justify-end gap-2">
                <button type="button" id="cancelModalBtn"
                    class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Open/close modal
    document.getElementById('openModalBtn').addEventListener('click', () => {
        document.getElementById('addModal').classList.remove('hidden');
    });
    document.getElementById('cancelModalBtn').addEventListener('click', () => {
        document.getElementById('addModal').classList.add('hidden');
    });

    // Add specialty via AJAX
    document.getElementById('addSpecialtyForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const name = document.getElementById('specialtyName').value;

        fetch('{{ route("admin.specialties.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const card = `
                    <div class="specialty-card relative bg-gradient-to-br from-blue-100 to-blue-200 border border-blue-300 rounded-xl shadow-lg p-5 hover:shadow-2xl transition duration-300" data-id="${data.specialty.id}">
                        <button class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white text-xs px-2 py-1 rounded-full delete-btn transform transition-transform duration-200 hover:scale-125">✕</button>
                        <div class="h-40 flex items-center justify-center bg-white rounded mb-4 shadow-inner">
                            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 20h9"></path>
                                <path d="M12 4H3v16h18V4h-9z"></path>
                                <path d="M9 4v16"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-center text-gray-800">${data.specialty.name}</h2>
                    </div>`;
                    document.getElementById('specialtyGrid').insertAdjacentHTML('beforeend', card);
                    document.getElementById('addModal').classList.add('hidden');
                    this.reset();

                    Swal.fire({
                        icon: 'success',
                        title: 'Added!',
                        text: 'Specialty added successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error!', 'Could not add specialty.', 'error');
                }
            });
    });

    // Delete via AJAX with SweetAlert
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-btn')) {
            const card = e.target.closest('.specialty-card');
            const id = card.dataset.id;

            Swal.fire({
                title: 'Are you sure?',
                text: "This will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/specialties/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                card.remove();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Specialty has been deleted.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire('Error!', 'Failed to delete.', 'error');
                            }
                        });
                }
            });
        }
    });

    // Search filter
    document.getElementById('searchInput').addEventListener('input', function () {
        const value = this.value.toLowerCase();
        document.querySelectorAll('.specialty-card').forEach(card => {
            const text = card.querySelector('h2').innerText.toLowerCase();
            card.style.display = text.includes(value) ? 'block' : 'none';
        });
    });
</script>
@endsection
