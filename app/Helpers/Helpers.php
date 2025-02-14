<?php

use Carbon\Carbon;
use App\Models\Setting\Setting;

    function formatCode($fitureCode, $sequence) {
        //tanggal
        $year = Carbon::now()->format('y');
        $month = Carbon::now()->format('m');
        $date = Carbon::now()->format('d');
        $dateFormat = $year.$month.$date;

        $sequence = str_pad($sequence, 4, '0', STR_PAD_LEFT);

        return $fitureCode.$dateFormat.$sequence;
    }

    function gradeFormat($value) {
        if (!is_numeric($value)) {
            return $value;
        }

        $numericValue = (float)$value;
    
        if (floor($numericValue) == $numericValue) {
            return $numericValue;
        }
        
        return number_format($numericValue, 2, '.', '');
    }
    
    function numberFormat($angka) {
        return number_format($angka, 0, ',' ,'.' );
    }

	if (!function_exists('dateFormat')) {
        /**
         * @param string $timestamp
         * @param string $date_format
         * @param string $suffix
         * @return string
         */
		function dateFormat($timestamp = '', $date_format = 'j F Y', $suffix = '') {
			if (trim ($timestamp) == '') {
				$timestamp = time ();
			} elseif (!ctype_digit ($timestamp)) {
				$timestamp = strtotime ($timestamp);
			}
			# remove S (st,nd,rd,th) there are no such things in indonesia :p
			$date_format = preg_replace ("/S/", "", $date_format);
			$pattern = array (
				'/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
				'/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
				'/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
				'/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
				'/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
				'/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
				'/April/','/June/','/July/','/August/','/September/','/October/',
				'/November/','/December/',
			);
			$replace = array ( 'Mon','Tue','Wed','Thu','Fri','Sat','Sun',
				'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
				'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Okt','Nov','Des',
				'Januari','Februari','Maret','April','Juni','Juli','Agustus','September',
				'Oktober','November','Desember',
			);
			$date = date ($date_format, $timestamp);
			$date = preg_replace ($pattern, $replace, $date);
			$date = "{$date} {$suffix}";
			return trim($date);
		}
    }

	if(!function_exists('formatRupiah')) {
		/**
		 * @param $angka
		 * @return string
		 */
		function formatRupiah($angka)
		{
			return "Rp. ". number_format($angka, 0, ',' ,'.' );
		}
	}

	if(!function_exists('sendWhatsappNotification')) {
		function sendWhatsappNotification($number, $message)
		{
			$token = Setting::first()->whatsapp_token ?? 'noset';
        	$curl = curl_init();

			$data = [
				'target' => $number,
				'message' => $message
			];

			curl_setopt_array($curl, [
				CURLOPT_URL => 'https://api.fonnte.com/send',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data)),
				CURLOPT_HTTPHEADER => array(
					'Authorization: '.$token.''
			),
			]);

			$response = curl_exec($curl);

			curl_close($curl);
			// return json_decode($response);
		}
	}

    if(!function_exists('createTransactionMessage')) {
        function createTransactionMessage($user, $transaction)
        {
            $setting = Setting::first();

            if($setting) {
                $replacements = [
                    '{application_name}' => $setting->app_name ?? '-',
                    '{application_url}' =>$setting->app_url ?? '-',
                    '{name}' => $user->name ?? '-',
                    '{email}' => $user->email ?? '-',
                    '{username}' => $user->username ?? '-',
                    '{province}' => $user->student && $user->student->province ? $user->student && $user->student->province->name : '',
                    '{city}' => $user->student && $user->student->city ? $user->student && $user->student->city->name : '',
                    '{district}' => $user->student && $user->student->district ? $user->student && $user->student->province->district : '',
                    '{village}' => $user->student && $user->student->village ? $user->student && $user->student->village->name : '',
                    '{address}' => $user->student && $user->student->address ?? '-',
                    '{whatsapp_number}' => $user->student && $user->student->phone_number ?? '-',

                    '{transaction_invoice_number}' => $transaction->code,
                    '{transaction_date}' => $transaction->created_at,
                    '{transaction_description}' => $transaction->description,
                    '{transaction_payment_method}' => $transaction->payment_method,
                    '{transaction_total_payment}' => number_format($transaction->total_payment, 2, ",", "."),
                    '{transaction_status}' => strtoupper($transaction->transaction_status),
                ];
            
                return $message = str_replace(array_keys($replacements), array_values($replacements), $setting->{'transaction_'.$transaction->transaction_status.'_notification'});
           }
        }
    }

    if (!function_exists('createActivationUserMessage')) {
        function createActivationUserMessage($user, $type)
        {
            $setting = Setting::first();

            if ($setting) {
                $template = $setting->{'account_'.$type.'_notification'} ?? 'Notification template not set';
    
                $replacements = [
                    '{application_name}' => $setting->app_name ?? '-',
                    '{application_url}' => $setting->app_url ?? '-',
                    '{verification_url}' => ($setting->app_url ?? 'isi-setting-terlebih-dahulu') . "/user/" . $user->id . "/activation",
                    '{name}' => $user->name ?? '-',
                    '{email}' => $user->email ?? '-',
                    '{username}' => $user->username ?? '-',
                    '{province}' => $user->student->province->name ?? '',
                    '{city}' => $user->student->city->name ?? '',
                    '{district}' => $user->student->district->name ?? '',
                    '{village}' => $user->student->village->name ?? '',
                    '{address}' => $user->student->address ?? '-',
                    '{whatsapp_number}' => $user->student->phone_number ?? '-',
                ];
        
                return str_replace(array_keys($replacements), array_values($replacements), $template);
            }
        }
    }
    




