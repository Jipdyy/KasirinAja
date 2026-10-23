<?php

namespace App\Http\Requests\Reports;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SalesReportRequest extends FormRequest
{
    private const MAX_DAYS = 366;

    public function authorize(): bool
    {
        return $this->user()->can('reports.view');
    }

    public function rules(): array
    {
        return [
            'start' => ['nullable', 'date'],
            'end'   => [
                'nullable',
                'date',
                Rule::when($this->filled('start'), ['after_or_equal:start']),
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Lewati kalau format tanggal sudah salah, supaya Carbon::parse tidak error.
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            [$start, $end] = $this->range();

            if ($start > $end) {
                $validator->errors()->add('start', 'Tanggal mulai tidak boleh setelah tanggal akhir.');
                return;
            }

            if (Carbon::parse($start)->diffInDays(Carbon::parse($end)) > self::MAX_DAYS) {
                $validator->errors()->add('end', 'Rentang laporan maksimal ' . self::MAX_DAYS . ' hari.');
            }
        });
    }

    /** Rentang efektif: default bulan berjalan kalau tidak diisi. */
    public function range(): array
    {
        $end = $this->filled('end')
            ? Carbon::parse($this->input('end'))
            : now();

        $start = $this->filled('start')
            ? Carbon::parse($this->input('start'))
            : $end->copy()->startOfMonth();

        return [$start->toDateString(), $end->toDateString()];
    }
}