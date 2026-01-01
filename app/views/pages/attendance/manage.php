<?php
$records = $records ?? [];
$classes = $classes ?? [];
?>

<div class="max-w-7xl mx-auto p-6">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">Manage Attendance</h1>
                    <p class="text-gray-400 text-sm mt-1">Edit or delete attendance records</p>
                </div>
            </div>
            <a href="index.php?page=attendance" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors">
                Back to Attendance
            </a>
        </div>
    </div>

    
    <?php if (!empty($records)): ?>
    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-xl font-bold text-white">Attendance Records</h3>
            <p class="text-sm text-gray-400 mt-1">Total: <?= count($records) ?> records</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-900 border-b border-gray-700">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Check In</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Check Out</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php foreach ($records as $record): ?>
                    <tr class="hover:bg-gray-700/50 transition-colors" data-id="<?= $record['id'] ?>">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-semibold">
                                    <?= strtoupper(substr($record['username'], 0, 1)) ?>
                                </div>
                                <span class="text-white font-medium"><?= htmlspecialchars($record['username']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <input type="date" class="bg-gray-900 border border-gray-700 rounded px-3 py-1 text-white w-full"
                                   value="<?= $record['date'] ?>" name="date">
                        </td>
                        <td class="px-6 py-4">
                            <input type="time" class="bg-gray-900 border border-gray-700 rounded px-3 py-1 text-white w-full"
                                   value="<?= $record['check_in'] ?>" name="check_in" step="1">
                        </td>
                        <td class="px-6 py-4">
                            <input type="time" class="bg-gray-900 border border-gray-700 rounded px-3 py-1 text-white w-full"
                                   value="<?= $record['check_out'] ?? '' ?>" name="check_out" step="1">
                        </td>
                        <td class="px-6 py-4">
                            <select name="status" class="bg-gray-900 border border-gray-700 rounded px-3 py-1 text-white w-full">
                                <option value="present" <?= $record['status'] === 'present' ? 'selected' : '' ?>>Present</option>
                                <option value="late" <?= $record['status'] === 'late' ? 'selected' : '' ?>>Late</option>
                                <option value="absent" <?= $record['status'] === 'absent' ? 'selected' : '' ?>>Absent</option>
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <button onclick="updateRecord(<?= $record['id'] ?>)" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                                    Save
                                </button>
                                <button onclick="deleteRecord(<?= $record['id'] ?>)" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-12 text-center">
        <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-indigo-600/20 flex items-center justify-center">
            <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">No Records Found</h3>
        <p class="text-gray-400">No attendance records available for the selected date range.</p>
    </div>
    <?php endif; ?>
</div>

<div id="toast" class="fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white opacity-0 transition-opacity duration-300"></div>

<script>
function showToast(message, isSuccess = true) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white transition-opacity duration-300 ${
        isSuccess ? 'bg-green-600' : 'bg-red-600'
    }`;
    toast.style.opacity = '1';
    setTimeout(() => toast.style.opacity = '0', 3000);
}

async function updateRecord(id) {
    try {
        const row = document.querySelector(`tr[data-id="${id}"]`);
        const data = {
            id: id,
            date: row.querySelector('[name="date"]').value,
            check_in: row.querySelector('[name="check_in"]').value,
            check_out: row.querySelector('[name="check_out"]').value,
            status: row.querySelector('[name="status"]').value
        };

        const response = await fetch('index.php?page=attendance_edit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: Object.entries(data)
                .map(([key, value]) => `${encodeURIComponent(key)}=${encodeURIComponent(value)}`)
                .join('&')
        });

        const result = await response.json();
        showToast(result.message, result.success);
        
        if (result.success) {
            setTimeout(() => window.location.reload(), 1500);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred while updating the record', false);
    }
}

async function deleteRecord(id) {
    if (!confirm('Are you sure you want to delete this attendance record?')) {
        return;
    }

    try {
        const response = await fetch('index.php?page=attendance_delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`
        });

        const result = await response.json();
        showToast(result.message, result.success);
        
        if (result.success) {
            setTimeout(() => window.location.reload(), 1500);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred while deleting the record', false);
    }
}
</script>