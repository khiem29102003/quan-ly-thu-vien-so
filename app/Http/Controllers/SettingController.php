<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin.or.librarian');
    }

    public function index()
    {
        $bankConfig = SystemSetting::getSetting('bank_config', [
            'bank_bin' => '970436', // Default VCB
            'account_no' => '0987654321',
            'account_name' => 'THU VIEN HQT'
        ]);

        $momoConfig = SystemSetting::getSetting('momo_config', [
            'phone' => '0987.654.321',
            'name' => 'THƯ VIỆN HQT'
        ]);

        return view('admin.settings', compact('bankConfig', 'momoConfig'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'bank_bin' => 'required|string|max:20',
            'account_no' => 'required|string|max:30',
            'account_name' => 'required|string|max:100',
            'momo_phone' => 'required|string|max:20',
            'momo_name' => 'required|string|max:100',
        ]);

        SystemSetting::setSetting('bank_config', [
            'bank_bin' => $request->bank_bin,
            'account_no' => $request->account_no,
            'account_name' => strtoupper($request->account_name)
        ]);

        SystemSetting::setSetting('momo_config', [
            'phone' => $request->momo_phone,
            'name' => mb_strtoupper($request->momo_name, 'UTF-8')
        ]);

        return back()->with('success', 'Đã cập nhật hệ thống thanh toán thành công!');
    }
}
