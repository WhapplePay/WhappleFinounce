<?php

namespace App\Http\Controllers;

use App\Events\UpdateOfferChatNotification;
use App\Models\Admin;
use App\Models\SellPostChat;
use App\Models\SellPostOffer;
use App\Models\Trade;
use App\Models\TradeChat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class ChatNotificationController extends Controller
{

    public function show(Request $request, $uuid)
    {
        $trade = Trade::where('hash_slug', $uuid)
            ->firstOrFail();
        $siteNotifications = TradeChat::whereHasMorph(
            'chatable',
            [
                User::class,
                Admin::class,
            ],
            function ($query) use ($trade) {
                $query->where([
                    'trades_id' => $trade->id,
                ]);
            }
        )->with('chatable:id,username,phone,image')->get()->map(function ($item){
            return $item;
        });

        return $siteNotifications;
    }

    public function newMessage(Request $request)
    {
        $rules = [
            'trade_id' => ['required'],
            'message' => ['required']
        ];

        $req = Purify::clean($request->all());
        $validator = Validator::make($req, $rules);
        if ($validator->fails()) {
            return response(['errors' => $validator->messages()], 200);
        }
        $user = Auth::user();

        $trade = Trade::where('id', $request->trade_id)
            ->firstOrFail();


        $chat = new TradeChat();
        $chat->description = $req['message'];
        $chat->trades_id = $trade->id;
        $log = $user->chats()->save($chat);


        $data['id'] = $log->id;
        $data['chatable_id'] = $log->chatable_id;
        $data['chatable_type'] = $log->chatable_type;
        $data['chatable'] = [
            'fullname' => $log->chatable->fullname,
            'id' => $log->chatable->id,
            'image' => $log->chatable->image,
            'mobile' => $log->chatable->mobile,
            'imgPath' => $log->chatable->imgPath,
            'username' => $log->chatable->username,
        ];
        $data['description'] = $log->description;
        $data['is_read'] = $log->is_read;
        $data['is_read_admin'] = $log->is_read_admin;
        $data['formatted_date'] = $log->formatted_date;
        $data['created_at'] = $log->created_at;
        $data['updated_at'] = $log->updated_at;

        event(new \App\Events\OfferChatNotification($data, $trade->hash_slug));

        return response(['success' => true], 200);
    }


    public function showByAdmin($hashSlug)
    {
        $tradeRequest = Trade::where('hash_slug', $hashSlug)
            ->firstOrFail();


        $siteNotifications = TradeChat::whereHasMorph(
            'chatable',
            [
                User::class,
                Admin::class
            ],
            function ($query) use ($tradeRequest) {
                $query->where([
                    'trades_id' => $tradeRequest->id,
                ]);
            }
        )->with('chatable:id,username,image')->get();

        return $siteNotifications;
    }


    public function newMessageByAdmin(Request $request)
    {

        $rules = [
            'trade_id' => ['required'],
            'message' => ['required']
        ];

        $req = Purify::clean($request->all());
        $validator = Validator::make($req, $rules);
        if ($validator->fails()) {
            return response(['errors' => $validator->messages()], 200);
        }


        $user = auth::guard('admin')->user();
        $tradeOffer = Trade::where('id', $request->trade_id)
            ->firstOrFail();

        $chat = new TradeChat();
        $chat->description = $req['message'];
        $chat->trades_id = $tradeOffer->id;
        $log = $user->chats()->save($chat);


        $uuid = $tradeOffer->hash_slug;
        $data['id'] = $log->id;
        $data['chatable_id'] = $log->chatable_id;
        $data['chatable_type'] = $log->chatable_type;
        $data['chatable'] = [
            'fullname' => $log->chatable->fullname,
            'id' => $log->chatable->id,
            'image' => $log->chatable->image,
            'mobile' => $log->chatable->mobile,
            'imgPath' => $log->chatable->imgPath,
            'username' => $log->chatable->username,
        ];
        $data['description'] = $log->description;
        $data['is_read'] = $log->is_read;
        $data['is_read_admin'] = $log->is_read_admin;
        $data['formatted_date'] = $log->formatted_date;
        $data['created_at'] = $log->created_at;
        $data['updated_at'] = $log->updated_at;

        event(new \App\Events\OfferChatNotification($data, $uuid));
        return response(['success' => true], 200);
    }

    public function readAt($id)
    {
        $siteNotification = TradeChat::find($id);
        if ($siteNotification) {
            $siteNotification->delete();
            if (Auth::guard('admin')->check()) {
                event(new UpdateOfferChatNotification(Auth::guard('admin')->id()));
            } else {
                event(new UpdateOfferChatNotification(Auth::id()));
            }
            $data['status'] = true;
        } else {
            $data['status'] = false;
        }
        return $data;
    }

    public function readAllByAdmin()
    {
        $siteNotification = SellPostChat::whereHasMorph(
            'chatable',
            [Admin::class],
            function ($query) {
                $query->where([
                    'chatable_id' => Auth::guard('admin')->id()
                ]);
            }
        )->delete();

        if ($siteNotification) {
            event(new UpdateOfferChatNotification(Auth::guard('admin')->id()));
        }
        $data['status'] = true;
        return $data;
    }

    public function readAll()
    {
        $siteNotification = TradeChat::whereHasMorph(
            'chatable',
            [User::class],
            function ($query) {
                $query->where([
                    'chatable' => Auth::id()
                ]);
            }
        )->delete();

        if ($siteNotification) {
            event(new UpdateOfferChatNotification(Auth::id()));
        }

        $data['status'] = true;
        return $data;
    }
}
