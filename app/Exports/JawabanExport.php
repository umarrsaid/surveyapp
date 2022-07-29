<?php

namespace App\Exports;

use App\Jawaban;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;



	class JawabanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
	{
	    /**
	    * @return \Illuminate\Support\Collection
	    */
	    public function collection()
	    {
	        return Jawaban::all();
	    }

		public function headings(): array
		    {
		        return [
		            'Survey',
		            'No Responden',
		            'Nama',
		            'Telepon',
		            'Umur',
		            'Alamat',
		            'Email'
		        ];
		}

		public function registerEvents(): array
	    {
	        return [
	            AfterSheet::class    => function(AfterSheet $event) {
	                $cellRange = 'A1:W1'; // All headers
	                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
	            },
	        ];
	    }

	}


	