<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('documents.view');

        $query = Document::with(['member', 'uploader']);

        if ($request->has('document_type') && $request->document_type) {
            $query->where('document_type', $request->document_type);
        }

        if ($request->has('is_verified')) {
            $query->where('is_verified', $request->is_verified);
        }

        if ($request->has('search') && $request->search) {
            $query->whereHas('member', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('member_id', 'like', '%' . $request->search . '%');
            });
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.documents.index', [
            'title' => 'Documents',
            'page_title' => 'Document Management',
            'documents' => $documents,
        ]);
    }

    public function memberDocuments(Member $member)
    {
        $this->authorize('documents.view');

        $documents = $member->documents()->get();

        return view('admin.documents.member-documents', [
            'title' => 'Member Documents',
            'page_title' => $member->name . ' - Documents',
            'member' => $member,
            'documents' => $documents,
        ]);
    }

    public function create(Member $member)
    {
        $this->authorize('documents.create');

        return view('admin.documents.create', [
            'title' => 'Upload Document',
            'page_title' => 'Upload Document for ' . $member->name,
            'member' => $member,
        ]);
    }

    public function store(Request $request, Member $member)
    {
        $this->authorize('documents.create');

        $request->validate([
            'document_type' => 'required|in:' . implode(',', array_keys(Document::DOCUMENT_TYPES)),
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
            'notes' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents/' . $member->member_id, 'public');

        Document::create([
            'member_id' => $member->id,
            'document_type' => $request->document_type,
            'title' => $request->title,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.documents.member', $member)
            ->with('success', 'Document uploaded successfully.');
    }

    public function show(Document $document)
    {
        $this->authorize('documents.view');

        return view('admin.documents.show', [
            'title' => 'Document Details',
            'page_title' => $document->title,
            'document' => $document,
        ]);
    }

    public function destroy(Document $document)
    {
        $this->authorize('documents.delete');

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }

    public function verify(Document $document)
    {
        $this->authorize('documents.verify');

        $document->update([
            'is_verified' => !$document->is_verified,
            'verified_by' => $document->is_verified ? null : auth()->id(),
            'verified_at' => $document->is_verified ? null : now(),
        ]);

        return redirect()->back()->with('success', 'Document verification status updated.');
    }

    public function download(Document $document)
    {
        $this->authorize('documents.view');

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download(
            $document->file_path,
            $document->file_name
        );
    }
}
