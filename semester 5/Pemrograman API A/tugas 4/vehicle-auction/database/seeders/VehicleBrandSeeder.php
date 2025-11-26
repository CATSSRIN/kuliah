<?php

namespace Database\Seeders;

use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehicleBrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'car' => [
                'Toyota' => ['Avanza', 'Innova', 'Fortuner', 'Camry', 'Corolla', 'Yaris', 'Rush', 'Alphard', 'Kijang', 'Agya'],
                'Honda' => ['Brio', 'Jazz', 'City', 'Civic', 'HR-V', 'CR-V', 'BR-V', 'Accord', 'Mobilio'],
                'Suzuki' => ['Ertiga', 'Swift', 'Baleno', 'Ignis', 'XL7', 'APV', 'Carry', 'Jimny'],
                'Daihatsu' => ['Xenia', 'Terios', 'Ayla', 'Sigra', 'Rocky', 'Gran Max'],
                'Mitsubishi' => ['Pajero Sport', 'Xpander', 'Outlander', 'Triton', 'Eclipse Cross'],
                'Nissan' => ['Grand Livina', 'X-Trail', 'March', 'Juke', 'Terra', 'Serena'],
                'Mazda' => ['CX-3', 'CX-5', 'CX-9', 'Mazda 2', 'Mazda 3', 'Mazda 6'],
                'BMW' => ['320i', '520i', 'X1', 'X3', 'X5'],
                'Mercedes-Benz' => ['C-Class', 'E-Class', 'S-Class', 'GLA', 'GLC'],
                'Hyundai' => ['Creta', 'Stargazer', 'Santa Fe', 'Palisade', 'Ioniq'],
                'Wuling' => ['Confero', 'Almaz', 'Cortez', 'Air EV'],
            ],
            'motorcycle' => [
                'Honda' => ['Beat', 'Vario', 'Scoopy', 'PCX', 'ADV', 'CBR150R', 'CBR250RR', 'CRF150L', 'Revo', 'Supra'],
                'Yamaha' => ['NMAX', 'Aerox', 'Mio', 'Fazzio', 'R15', 'R25', 'MT-15', 'WR155', 'Vixion', 'Jupiter'],
                'Suzuki' => ['Nex II', 'Address', 'GSX-R150', 'GSX-S150', 'Satria F150'],
                'Kawasaki' => ['Ninja 250', 'Ninja ZX-25R', 'Z250', 'W175', 'KLX150'],
                'TVS' => ['Apache RTR', 'Dazz', 'XL100'],
                'Vespa' => ['Sprint', 'Primavera', 'GTS', 'S125', 'LX'],
            ],
        ];

        foreach ($brands as $type => $brandList) {
            foreach ($brandList as $brandName => $models) {
                $brand = VehicleBrand::create([
                    'name' => $brandName,
                    'slug' => Str::slug($brandName),
                    'type' => $type === 'car' ?  'car' : ($type === 'motorcycle' ? 'motorcycle' : 'both'),
                ]);

                foreach ($models as $modelName) {
                    VehicleModel::create([
                        'brand_id' => $brand->id,
                        'name' => $modelName,
                        'slug' => Str::slug($modelName),
                        'type' => $type,
                    ]);
                }
            }
        }
    }
}