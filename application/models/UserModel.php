<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {

	public function __construct() {
		parent::__construct();
    }

    public function Add($param)
    {
        $Ip_Address = $this->Foglobal->ip_address();
        if(!empty($param["ip_address"])) {
            $Ip_Address = $param["ip_address"];
        }

        $Koneksi = $this->db;

        if(empty($param["username"])) {
            $result = $this->Foglobal->CreateArray(true, "Username wajib diisi");
            goto ResultData;
        }
        if(empty($param["password"])) {
            $result = $this->Foglobal->CreateArray(true, "Password wajib diisi");
            goto ResultData;
        }

        $UsernameCheck = $this->List([
                "username" => $param["username"]
            ]);

        if(!$UsernameCheck["IsError"]) {
            $result = $this->Foglobal->CreateArray(true, "Username telah digunakan");
            goto ResultData;
        }

        $param["password"] = $this->Foglobal->GeneratePassword($param["password"]);
        $param = $this->Foglobal->UnsetArrayByKey($param, ["is_active"]);

		$data               = [];
        $data["Target"]     = "users";
        $data["ParamsData"] = json_encode($param);

		$result = $this->DatabaseAPI->InsertData($data, $Koneksi);

		ResultData:
        return $result;
    }

    public function List($param)
    {
        $Koneksi = $this->db;

		$nama_table   	= "users a ";

		$data 			= [];
		$data["Target"] = $nama_table;
		$filter 		= "";

		if(!empty($param["id"])) {
			$flt = "a.id = '".$param["id"]."'";
			$filter .= empty($filter) ? $flt : " and ".$flt;
        }
        if(!empty($param["username"])) {
			$flt = "a.username = '".$param["username"]."'";
			$filter .= empty($filter) ? $flt : " and ".$flt;
        }
        if(!empty($param["password"])) {
			$flt = "a.password = '".$param["password"]."'";
			$filter .= empty($filter) ? $flt : " and ".$flt;
        }
		if(isset($param["search"])) {
			$flt = "(a.username LIKE '%".$param["search"]."%')";
			$filter .= empty($filter) ? $flt : " and ".$flt;
		}

        if(!empty($param["Field"])) {
            $data["ParamsField"] = $param["Field"];
        } else {
            $data["ParamsField"] = "a.*";
        }

		//Bawaan
		$data["ParamsFilter"] = $filter;

		if(!empty($param["Sort"]))  $data["ParamsSort"] = $param["Sort"];
		if(!empty($param["Limit"])) $data["Limit"] 		= $param["Limit"]; 
		if(!empty($param["Page"]))  $data["Page"] 		= $param["Page"];
		if(!empty($param["Field"])) $data["ParamsField"]= $param["Field"];

		$result = $this->DatabaseAPI->GetData($data, $Koneksi);

		ResultData:
		return $result;
    }

    public function Edit($param)
    {
        $Ip_Address = $this->Foglobal->ip_address();
        if(!empty($param["ip_address"])) {
            $Ip_Address = $param["ip_address"];
        }

        $Koneksi = $this->db;

        if(empty($param["id"])) {
            $result = $this->Foglobal->CreateArray(true, "Akun User wajib dipilih");
            goto ResultData;
        }
        if(empty($param["username"])) {
            $result = $this->Foglobal->CreateArray(true, "Username wajib diisi");
            goto ResultData;
        }

        $id = $param["id"];

        $UserCheck = $this->List([
                "id" => $id,
                "Field" => "a.*"
            ]);

        if($UserCheck["IsError"]) {
            $result = $this->Foglobal->CreateArray(true, "User tidak ditemukan");
            goto ResultData;
        }

        $param["password"] = $this->Foglobal->GeneratePassword($param["password"]);
        $param = $this->Foglobal->UnsetArrayByKey($param, []);
        
        $SessionLogin = $this->Foglobal->GetSessionLogin();
        if($SessionLogin["IsError"]) {
            $result = $SessionLogin;
            goto ResultData;
        }

        $data                 = [];
        $data["Target"]       = "users";
        $data["ParamsData"]   = json_encode($param);
        $data["ParamsFilter"] = "id = ".$id;
        $result = $this->DatabaseAPI->UpdateData($data, $Koneksi);

        ResultData:
        return $result;
    }

}