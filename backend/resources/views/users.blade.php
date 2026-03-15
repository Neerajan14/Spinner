<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            padding: 16px 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .card-header-left h2 {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .card-header-left span {
            font-size: 14px;
            color: #6b7280;
        }

        .btn-export {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            background-color: #059669;
            color: white;
            font-weight: 500;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.15s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            border: none;
            cursor: pointer;
        }

        .btn-export:hover {
            background-color: #047857;
        }

        .btn-export svg {
            width: 20px;
            height: 20px;
            margin-right: 8px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f9fafb;
        }

        thead th {
            padding: 12px 24px;
            text-align: left;
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tbody {
            background: white;
        }

        tbody tr {
            border-top: 1px solid #e5e7eb;
            transition: background-color 0.15s;
        }

        tbody tr:hover {
            background-color: #f9fafb;
        }

        tbody td {
            padding: 16px 24px;
            font-size: 14px;
            color: #1f2937;
        }

        .text-gray-500 {
            color: #6b7280;
        }

        .font-medium {
            font-weight: 500;
        }

        .whitespace-nowrap {
            white-space: nowrap;
        }

        .text-center {
            text-align: center;
        }

        .link {
            color: #3b82f6;
            text-decoration: none;
            transition: color 0.15s;
            margin-right: 12px;
        }

        .link:hover {
            color: #1e40af;
        }

        .link-download {
            color: #059669;
        }

        .link-download:hover {
            color: #047857;
        }

        .resume-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-footer {
            padding: 16px 24px;
            border-top: 1px solid #e5e7eb;
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            text-decoration: none;
            color: #374151;
            font-size: 14px;
            transition: all 0.15s;
        }

        .pagination a:hover {
            background-color: #f3f4f6;
        }

        .pagination .active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .pagination .disabled {
            color: #9ca3af;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px 16px;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-export {
                width: 100%;
                justify-content: center;
            }

            thead th,
            tbody td {
                padding: 12px 16px;
                font-size: 12px;
            }

            .card-header-left h2 {
                font-size: 18px;
            }

            .resume-actions {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .link {
                margin-right: 0;
            }
        }

        @media (max-width: 640px) {
            .table-wrapper {
                border-radius: 0;
            }

            thead th,
            tbody td {
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <h2>All Users</h2>
                    <span>Total: {{ $users->total() }}</span>
                </div>
                <a href="{{ route('users.export') }}" class="btn-export">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download CSV
                </a>
            </div>

            <div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Phone Number</th>
                <th>Email Address</th>
                <th>Interested In</th>
                <th>Address</th>
                <th>Resume</th>
                <th>Joined</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
            <tr>
                <td class="whitespace-nowrap">{{ $index + 1 }}</td>
                <td class="whitespace-nowrap font-medium">{{ $user->name }}</td>
                <td class="whitespace-nowrap text-gray-500">{{ $user->number }}</td>
                <td class="whitespace-nowrap text-gray-500">{{ $user->email }}</td>
                <td class="whitespace-nowrap text-gray-500">{{ $user->interested }}</td>
                <td class="text-gray-500">{{ $user->address ?? 'N/A' }}</td>
                <td class="whitespace-nowrap text-gray-500">
                    @if($user->resume_file_name)
                        <div class="resume-actions">
                            <a href="{{ asset('storage/resumes/' . $user->resume_file_name) }}" target="_blank" class="link">
                                View
                            </a>
                            <a href="{{ asset('storage/resumes/' . $user->resume_file_name) }}" download="{{ $user->name }}_Resume" class="link link-download">
                                Download
                            </a>
                        </div>
                    @else
                        N/A
                    @endif
                </td>
                <td class="whitespace-nowrap text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-gray-500">No users found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

            <div class="card-footer">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</body>
</html>