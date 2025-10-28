<?php

namespace App\Imports;

use App\Models\Code;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UpdateCodesPriceImport implements ToCollection, WithHeadingRow
{
    /**
     * The price that should be applied to every matched code.
     */
    private readonly float $price;

    /**
     * Track how many code records were updated.
     */
    private int $updatedCount = 0;

    public function __construct(float $price)
    {
        $this->price = $price;
    }

    public function collection(Collection $rows): void
    {
        $codes = $rows
            ->pluck('code')
            ->filter()
            ->map(fn ($code) => trim((string) $code))
            ->filter()
            ->unique();

        if ($codes->isEmpty()) {
            return;
        }

        $codes->chunk(500)->each(function (Collection $chunk): void {
            $this->updatedCount += Code::whereIn('code', $chunk)->update([
                'price' => $this->price,
            ]);
        });
    }

    public function getUpdatedCount(): int
    {
        return $this->updatedCount;
    }
}
