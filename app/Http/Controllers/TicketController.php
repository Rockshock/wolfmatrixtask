<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::paginate(20);
        return view('tickets.index', compact('tickets'));
    }

    public function reserve(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $originalUpdatedAt = $ticket->updated_at;

        if ($ticket->is_reserved) {
            return back()->withErrors(['error' => 'Ticket already reserved.']);
        }

        // optimistic locking to prevent race conditions
        $updated = Ticket::where('id', $ticket->id)
            ->where('updated_at', $originalUpdatedAt)
            ->update([
                'is_reserved' => true,
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            return back()->withErrors(['error' => 'Race condition detected. Please try again.']);
        }

        return back()->with('success', 'Ticket reserved successfully.');
    }

    public function seedTickets(){
        Artisan::call('db:seed --class=TicketSeeder');
        return redirect()->route('tickets.index')->with('success', 'Tickets seeded successfully.');
    }
}
