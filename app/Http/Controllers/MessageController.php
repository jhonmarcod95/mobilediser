<?php

namespace App\Http\Controllers;

use App\MessageHeader;
use App\MessageItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function show()
    {
        $messages = DB::table('vw_merchandiser_message_header')
            ->get()
            ->sortByDesc('created_at');

        return view('message.show', compact(
            'messages'
        ));
    }

    public function chat($id)
    {
        $message_id = $id;
        session(['message_id' => $message_id]);

        #update message header
        $messageHeader = MessageHeader::find($message_id);
        $messageHeader->seen_by_receiver = 'yes';
        $messageHeader->save();

        $chats = DB::table('vw_merchandiser_message_items')
            ->where('message_id', $message_id)
            ->get()
            ->sortBy('created_at');


        return view('message.chat', compact(
            'chats'
        ));
    }

    public function addChat(Request $request)
    {
        $validation = $request->validate([
            'message' => 'required|max:255',
        ]);


        $message_id = session('message_id');

        #save message items or chats
        $messageItem = new MessageItem();
        $messageItem->message_id = $message_id;
        $messageItem->merchandiser_id = Auth::user()->merchandiser_id;
        $messageItem->message = $request->message;
        $messageItem->save();

        #update message header
        $messageHeader = MessageHeader::find($message_id);
        $messageHeader->seen_by_sender = 'no';
        $messageHeader->seen_by_receiver = 'yes';
        $messageHeader->save();

        return redirect(redirect()->back()->getTargetUrl());
    }

    public function closeMessage(Request $request)
    {
        $message_id = session('message_id');

        #update message status
        $messageHeader = MessageHeader::find($message_id);
        $messageHeader->status = '003';
        $messageHeader->save();

//        alert()->success('Status has been updated', ''); not working

        return redirect('/message');
    }
}