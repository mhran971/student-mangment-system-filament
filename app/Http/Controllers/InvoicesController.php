<?php

namespace App\Http\Controllers;

use App\Models\Student;
 use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Seller;
use LaravelDaily\Invoices\Invoice;

class InvoicesController extends Controller
{
    public function generatePdf(Student $student){

        $student = new Buyer([
            'name'          => $student->name,
            'custom_fields' => [
                'email' => $student->email,
            ],
        ]);

        $seller = new Seller();
        $seller->name = 'Mahran Abo Dakka';
        $seller->address = 'Syria-Damascus';


        $item = InvoiceItem::make('Service 1')->pricePerUnit(2);

        $invoice = Invoice::make()
            ->buyer($student)
            ->seller($seller)
            ->discountByPercent(10)
            ->taxRate(15)
            ->shipping(1.99)
            ->addItem($item);

        return $invoice->stream();
    }
}
