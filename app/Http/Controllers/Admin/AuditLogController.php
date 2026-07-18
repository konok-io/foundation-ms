<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('audit-logs.view');

        $query = AuditLog::with('user');

        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->latest('created_at')->paginate(30);

        return view('admin.audit-logs.index', [
            'title' => 'Audit Logs',
            'page_title' => 'Activity & Audit Logs',
            'logs' => $logs,
        ]);
    }

    public function show(AuditLog $log)
    {
        $this->authorize('audit-logs.view');

        return view('admin.audit-logs.show', [
            'title' => 'Audit Log Details',
            'page_title' => 'Log Details',
            'log' => $log,
        ]);
    }

    public function destroy(AuditLog $log)
    {
        $this->authorize('audit-logs.delete');

        $log->delete();

        return redirect()->route('admin.audit-logs.index')
            ->with('success', 'Log entry deleted.');
    }

    public function export(Request $request)
    {
        $this->authorize('audit-logs.view');

        $query = AuditLog::with('user');

        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->latest('created_at')->get();

        $csvContent = "ID,User,Action,Model,Model ID,IP Address,Date,Time\n";
        foreach ($logs as $log) {
            $csvContent .= "{$log->id},{$log->user?->name},{$log->action},{$log->model_type},{$log->model_id},{$log->ip_address},{$log->created_at->format('Y-m-d')},{$log->created_at->format('H:i:s')}\n";
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="audit_logs_' . date('Y-m-d') . '.csv"');
    }
}
