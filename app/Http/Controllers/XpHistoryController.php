<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\XpHistory;
use App\Models\Account;

class XpHistoryController extends Controller
{

    public function index()
    {
        return response()->json(XpHistory::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|integer|exists:accounts,id',
            'xpGained' => 'required|integer',
            'category' => 'required|string',
            'date' => 'required|date',
        ]);
        
        $xp = XpHistory::create($validated);
        // Update account XP
        $account = Account::findOrFail($validated['account_id']);
        $account->xp += $validated['xpGained'];
        $account->save();
        return response()->json($xp);
    }

    public function show($id)
    {
        return response()->json(XpHistory::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $xp = XpHistory::findOrFail($id);
        $xp->update($request->all());
        return response()->json($xp);
    }

    public function destroy($id)
    {
        XpHistory::destroy($id);
        return response()->json(['deleted' => true]);
    }

}
