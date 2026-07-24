<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks CRUD</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, sans-serif; background: #0f172a; color: #e2e8f0; padding: 40px 20px; }
        .container { max-width: 700px; margin: 0 auto; }
        h1 { font-size: 24px; margin-bottom: 24px; color: #38bdf8; }
        .alert { padding: 10px 16px; background: rgba(74,222,128,0.15); color: #4ade80; border-radius: 8px; margin-bottom: 16px; font-size: 13px; }
        /* Form */
        .add-form { background: #1e293b; padding: 20px; border-radius: 12px; margin-bottom: 24px; border: 1px solid #334155; }
        .add-form h3 { margin-bottom: 12px; font-size: 14px; }
        .form-row { display: flex; gap: 10px; flex-wrap: wrap; }
        input, select, textarea { padding: 10px 14px; background: #0f172a; border: 1px solid #334155; border-radius: 8px; color: #e2e8f0; font-size: 13px; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #38bdf8; }
        input[type="text"] { flex: 1; min-width: 200px; }
        textarea { width: 100%; margin-top: 10px; min-height: 60px; resize: vertical; }
        button { padding: 10px 20px; background: #38bdf8; color: #0f172a; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px; }
        button:hover { background: #0ea5e9; }
        .btn-danger { background: #f87171; }
        .btn-danger:hover { background: #ef4444; }
        .btn-sm { padding: 6px 12px; font-size: 11px; }
        /* Table */
        .task-list { background: #1e293b; border: 1px solid #334155; border-radius: 12px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { padding: 12px 16px; text-align: left; font-size: 11px; color: #64748b; text-transform: uppercase; background: #0f172a; }
        td { padding: 12px 16px; border-top: 1px solid #334155; font-size: 13px; }
        .badge { padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-pending { background: rgba(251,191,36,0.15); color: #fbbf24; }
        .badge-in_progress { background: rgba(56,189,248,0.15); color: #38bdf8; }
        .badge-completed { background: rgba(74,222,128,0.15); color: #4ade80; }
        .actions { display: flex; gap: 6px; }
        .empty { padding: 40px; text-align: center; color: #64748b; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Tasks</h1>

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <!-- Add Task Form -->
        <div class="add-form">
            <h3>Add New Task</h3>
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="form-row">
                    <input type="text" name="title" placeholder="Task title" required>
                    <select name="status">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                    <button type="submit">Add Task</button>
                </div>
                <textarea name="description" placeholder="Description (optional)"></textarea>
            </form>
        </div>

        <!-- Task List -->
        <div class="task-list">
            @if($tasks->count())
            <table>
                <thead>
                    <tr><th>#</th><th>Title</th><th>Description</th><th>Status</th><th>Created</th><th>Actions</th></tr>
                </thead>
                <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td><strong>{{ $task->title }}</strong></td>
                        <td>{{ Str::limit($task->description, 50) ?: '-' }}</td>
                        <td><span class="badge badge-{{ $task->status }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span></td>
                        <td>{{ $task->created_at->format('d M Y') }}</td>
                        <td class="actions">
                            <!-- Edit (inline status change) -->
                            <form action="{{ route('tasks.update', $task) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="title" value="{{ $task->title }}">
                                <input type="hidden" name="description" value="{{ $task->description }}">
                                @if($task->status !== 'completed')
                                    <input type="hidden" name="status" value="completed">
                                    <button class="btn-sm" title="Mark complete">✓</button>
                                @else
                                    <input type="hidden" name="status" value="pending">
                                    <button class="btn-sm" title="Reopen">↺</button>
                                @endif
                            </form>
                            <!-- Delete -->
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?')">
                                @csrf @method('DELETE')
                                <button class="btn-sm btn-danger">✕</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
                <div class="empty">No tasks yet. Add one above!</div>
            @endif
        </div>
    </div>
</body>
</html>
