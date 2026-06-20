<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(): View
    {
        $notes = Note::query()
            ->latest()
            ->get();

        return view('notes.index', [
            'notes' => $notes,
            'totalNotes' => $notes->count(),
            'completedNotes' => $notes->where('completed', true)->count(),
        ]);
    }

    public function store(StoreNoteRequest $request): RedirectResponse
    {
        Note::query()->create($request->validated());

        return to_route('notes.index')->with('status', 'Catatan tersimpan ke MySQL.');
    }

    public function update(UpdateNoteRequest $request, Note $note): RedirectResponse
    {
        $note->update([
            'completed' => $request->boolean('completed'),
        ]);

        return to_route('notes.index')->with('status', 'Status catatan diperbarui.');
    }

    public function destroy(Note $note): RedirectResponse
    {
        $note->delete();

        return to_route('notes.index')->with('status', 'Catatan dihapus.');
    }
}

