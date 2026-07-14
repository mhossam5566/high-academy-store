<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'whatsapp_number' => Setting::get('whatsapp_number', '201550234324'),
            'whatsapp_channel' => Setting::get('whatsapp_channel', 'https://www.whatsapp.com/channel/0029VbAlwWH8fewxAkAdCZ23'),
        ];

        return view('dashboard.pages.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'whatsapp_number' => 'required|string|max:50',
            'whatsapp_channel' => 'nullable|url|max:255',
        ], [
            'whatsapp_number.required' => 'رقم الواتساب مطلوب',
            'whatsapp_channel.url' => 'يجب أن يكون رابط القناة رابطاً صحيحاً',
        ]);

        Setting::set('whatsapp_number', $request->whatsapp_number);
        Setting::set('whatsapp_channel', $request->whatsapp_channel);

        return redirect()->back()->with('success', 'تم تحديث إعدادات الموقع بنجاح');
    }
}
