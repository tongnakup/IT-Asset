<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ItAsset;

class AssetSearch extends Component
{
    public $query = '';
    public $assets = [];

    // Method นี้จะทำงานทุกครั้งที่ User พิมพ์ในช่องค้นหา
    public function updatedQuery()
    {
        // ปรับให้เริ่มค้นหาเมื่อพิมพ์ 2 ตัวอักษรขึ้นไปเพื่อประสิทธิภาพที่ดีขึ้น
        if (strlen($this->query) >= 2) {
            $this->assets = ItAsset::with('employee') // โหลดข้อมูล employee มาพร้อมกัน
                ->where(function ($query) {
                    // ค้นหาจาก Asset Number, Type, Brand
                    $query->where('asset_number', 'like', '%' . $this->query . '%')
                        ->orWhere('type', 'like', '%' . $this->query . '%')
                        ->orWhere('brand', 'like', '%' . $this->query . '%')
                        // ค้นหาจากชื่อพนักงาน (First name หรือ Last name)
                        ->orWhereHas('employee', function ($q) {
                            $q->where('first_name', 'like', '%' . $this->query . '%')
                                ->orWhere('last_name', 'like', '%' . $this->query . '%');
                        });
                })
                ->take(5) // แสดงผลลัพธ์สูงสุด 5 รายการ
                ->get();
        } else {
            $this->assets = [];
        }
    }

    // Method นี้จะทำงานเมื่อ User คลิกเลือก Asset จากรายการ
    public function selectAsset($assetNumber)
    {
        $this->query = $assetNumber;
        $this->assets = []; // ซ่อนรายการผลลัพธ์
    }

    public function render()
    {
        return view('livewire.asset-search');
    }
}
