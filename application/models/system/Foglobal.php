<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Foglobal extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function CreateArray($IsError, $msg)
	{
		if($IsError == true) {
			$ReturnData = ["IsError" => $IsError, "ErrMessage" => $msg];
		} else {
			if(is_array($msg)) {
				$ReturnData = ["IsError" => $IsError, "Data" => $msg];
			} else {
				$ReturnData = ["IsError" => $IsError, "Output" => $msg];
			}
		}

		return $ReturnData;
	}

    public function array_sort_by_column(&$arr, $col, $dir = "ASC") {
        $sort_col = array(); $dir = ($dir == "ASC") ? SORT_ASC : SORT_DESC;
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }
    
        array_multisort($sort_col, $dir, $arr);
    }

    public function UnsetArrayByKey($data, $unset_data) {
        if(!is_array($data)) return $data;

    	foreach ($data as $key => $value) {
    		if(in_array($key, $unset_data, true)) {
    			unset($data[$key]);
    		}
    	}
    	return $data;
    }

    public function CreateToken()
    {
    	$key = $this->RandomWord(30);
    	return $key;
    }

    //check field is exist in table
	public function CheckFieldExistInDb($param, $table)
	{
		$Params = json_decode($param, TRUE);
		foreach ($Params as $key => $value) {
			$query	= $this->db->query("SHOW COLUMNS FROM ".$table." LIKE '".$key."'");
			$exists = ($query->num_rows()) ? TRUE:FALSE;
			if(!$exists) $Params = $this->UnsetArrayByKey($Params, [$key]);
		}

		return json_encode($Params);
	}

    //Escaping string. supaya gak kena sql_injection
	public function CleanParamSqlInjection()
	{
		foreach($_POST as $key => $value) {
			$_POST[$key] = $this->db->escape_str($_POST[$key]);
		}

		return true;
	}

    public function GeneratePassword($string) {
		$salt = "mbqJdsQNDLDZF22BSVpxud_FLSTWBj5gHTiTPVZo";
		return hash_hmac("gost", base64_encode(hash_hmac("sha512", base64_encode(substr(md5($string), 13, 6)), $salt, true)), $salt);
	}

    public function encrypt($string) {
        return hash("ripemd160", md5($string));
    }

    public function RandomWord($len = 5) {
        $word = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($word);
        return substr(implode($word), 0, $len);
    }

    public function MakeAlert($message, $type = "warning") {
        return "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>{$message}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }

    public function date_abs($tgl) {
        $bulan  = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
        $string = explode("-", $tgl);

        $stgl   = ($string[2] <= 9) ? "0".$string[2]: $string[2];
        $sbulan = ($string[1] - 1);
        $sbulan = (array_key_exists($sbulan, $bulan)) ? $bulan[$sbulan]: "Bulan tidak valid";
        $stahun = $string[0];

        return $stgl." ".$sbulan." ".$stahun; 
    }

    public function IDtoMonth($id) {
        $data = [1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                    7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"];
        return (array_key_exists($id, $data)) ? $data[$id]: "Bulan tidak valid";
    }

    public function CheckSessionLogin() {
        if(empty($this->session->userdata("user"))) {
            $rHtml = $this->MakeAlert("Silahkan login dahulu untuk melanjutkan.");
            $this->session->set_userdata("notifikasi", $rHtml);

            redirect("auth/login");
            return;
        }
    }

    public function CheckAppMaintenance() {
        if($this->config->item("app_maintenance")) {
            redirect("project-discontinue");
            return;
        }
    }

    public function GetSessionLogin()
    {
        if(empty($this->session->userdata("user"))) {
            return $this->CreateArray(true, "Sesi login telah berakhir, silahkan login kembali");
        }

        return $this->CreateArray(false, $this->session->userdata("user"));
    }

    public function StatusToHtml($status)
    {
        return ($status=='1') ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
    }

    public function formatAngka($angka, $precision = 0) { //contoh format sebelum di convert 1000000 
        if(is_numeric($angka)) {
            return number_format($angka, $precision, ",", ".");
        }
        return $angka;
    }

    public function ParseGambar($url) {
        if(preg_match("!(http|https)!", $url)) {
            $url = str_replace("https", "http", $url);
            return $url;
        } else {
            $url = str_replace("1|", "", $url);
            return $this->config->item("base_url")."/upload/images/".$url;
        }
    }

    public function ip_address() {
		$ipaddress = '';
		
		if (getenv('HTTP_CLIENT_IP'))						$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))					$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))				$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))					$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR')) 						$ipaddress = getenv('REMOTE_ADDR');
		else 												$ipaddress = 'UNKNOWN';
		
		return $ipaddress;
	}
}