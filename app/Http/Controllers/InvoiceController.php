<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Language;
use App\Models\Order;
use Session;
use PDF;
use Config;

class InvoiceController extends Controller
{
    //download invoice
    public function invoice_download($id)
    {
        if(Session::has('currency_code')){
            $currency_code = Session::get('currency_code');
        }
        else{
            $currency_code = Currency::findOrFail(get_setting('system_default_currency'))->code;
        }
        $language_code = Session::get('locale', Config::get('app.locale'));

        if(Language::where('code', $language_code)->first()->rtl == 1){
            $direction = 'rtl';
            $text_align = 'right';
            $not_text_align = 'left';
        }else{
            $direction = 'ltr';
            $text_align = 'left';
            $not_text_align = 'right';            
        }

        if($currency_code == 'BDT' || $language_code == 'bd'){
            // bengali font
            $font_family = "'Hind Siliguri','sans-serif'";
        }elseif($currency_code == 'KHR' || $language_code == 'kh'){
            // khmer font
            $font_family = "'Hanuman','sans-serif'";
        }elseif($currency_code == 'AMD'){
            // Armenia font
            $font_family = "'arnamu','sans-serif'";
        // }elseif($currency_code == 'ILS'){
        //     // Israeli font
        //     $font_family = "'Varela Round','sans-serif'";
        }elseif($currency_code == 'AED' || $currency_code == 'EGP' || $language_code == 'sa' || $currency_code == 'IQD' || $language_code == 'ir' || $language_code == 'om' || $currency_code == 'ROM' || $currency_code == 'SDG' || $currency_code == 'ILS'|| $language_code == 'jo'){
            // middle east/arabic/Israeli font
            $font_family = "'Baloo Bhaijaan 2','sans-serif'";
        }elseif($currency_code == 'THB'){
            // thai font
            $font_family = "'Kanit','sans-serif'";
        }elseif($currency_code == 'JPY' || $language_code == 'jp'){
            $font_family = "'ヒラギノ角ゴ Pro W3', 'Hiragino Kaku Gothic Pro', 'Osaka', 'メイリオ', 'Meiryo', 'ＭＳ Ｐゴシック', 'MS PGothic', 'ＭＳ ゴシック' , 'MS Gothic', 'Noto Sans CJK JP', 'TakaoPGothic', 'sans-serif'";
        }
        else{
            // general for all
            $font_family = "'Roboto','sans-serif'";
        }
        
        // $config = [
        //     'instanceConfigurator' => function($mpdf) {
        //         $mpdf->useAdobeCJK = true;
        //     },
        //     'mode' => 'ja+aCJK',
        //     "autoScriptToLang" => true,
        //     "autoLangToFont" => true,
        //     'allow_charset_conversion ' => true,
        //     'CSSselectMedia' => 'mpdf'
        //     ];
        $order = Order::findOrFail($id);
        // $pdf = PDF::loadView('backend.invoices.invoice',[
        //             'order' => $order,
        //             'font_family' => $font_family,
        //             'direction' => $direction,
        //             'text_align' => $text_align,
        //             'not_text_align' => $not_text_align
        //         ], [], $config);
        //         // dd($pdf);

        // return  $pdf->download('order-'.$order->code.'.pdf');
        return view('backend.invoices.invoice',['order' => $order,'font_family' => $font_family,'direction' => $direction,'text_align' => $text_align,'not_text_align' => $not_text_align]);
    }
}
