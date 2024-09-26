<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model("UserModel");
    }

    public function Login($param) {
        $Ip_Address = $this->Foglobal->ip_address();

        if(empty($param["username"])) {
            $result = $this->Foglobal->CreateArray(true, "Username wajib diisi");
            goto ResultData;
        }
        if(empty($param["password"])) {
            $result = $this->Foglobal->CreateArray(true, "Password wajib diisi");
            goto ResultData;
        }

        $param["password"] = $this->Foglobal->GeneratePassword($param["password"]);
        $result = $this->UserModel->List([
                "username"  => $param["username"],
                "password"  => $param["password"]
            ]);

		if($result["IsError"]) {
			$result = $this->Foglobal->CreateArray(true, "Username atau Password salah atau tidak ditemukan");
			goto ResultData;
		}

		ResultData:
		return $result;
    }

    public function LoginProses($param) {
        $id = ""; $rHtml = "";

        $Data = (object) $this->Login($param);
        $IsError = $Data->IsError;

        if($IsError == false) {
            if(empty($Data->Data)) {
                $IsError = true;
                $rHtml = $this->Foglobal->MakeAlert("Username atau Password salah.");

                $this->session->set_userdata("notifikasi", $rHtml);
                goto returnData;
            }

            $Data = $Data->Data[0];

            $rHtml = "success";
            $id = $Data->id;

            if(empty($this->session->set_userdata)) {
                $this->session->set_userdata(["user" => $Data, "user_login" => $param]);
            }
            else {
                $this->session->sess_destroy();
                $this->session->set_userdata(["user" => $Data, "user_login" => $param]);
            }

            if(!empty($this->session->userdata('notifikasi'))) {
                $this->session->unset_userdata('notifikasi');
            }

        } else {
            $rHtml = "error";
            $Data->IsError = true;
            $rHtml = $this->Foglobal->MakeAlert("Error: {$Data->ErrMessage}.");
            $this->session->set_userdata("notifikasi", $rHtml);
        }

        returnData:
        $ReturnData = ["IsError" => $IsError, "lsdt" => $rHtml, "id" => $id];
        return json_encode($ReturnData);
    }

    public function Register($param) {
        $Ip_Address = $this->Foglobal->ip_address();

        if(empty($param["username"])) {
            $result = $this->Foglobal->CreateArray(true, "Username wajib diisi");
            goto ResultData;
        }
        if(empty($param["password"])) {
            $result = $this->Foglobal->CreateArray(true, "Password wajib diisi");
            goto ResultData;
        }
        if(empty($param["repeat_password"])) {
            $result = $this->Foglobal->CreateArray(true, "Repeat Password wajib diisi");
            goto ResultData;
        }

        if($param["password"] != $param["repeat_password"]) {
            $result = $this->Foglobal->CreateArray(true, "Password tidak sama");
            goto ResultData;
        }

        if(!$this->config->item("register")) {
            $result = $this->Foglobal->CreateArray(true, "Register sedang tidak dibuka");
            goto ResultData;
        }

        $result = $this->UserModel->Add([
                "username"  => $param["username"],
                "password"  => $param["password"]
            ]);

		ResultData:
		return $result;
    }

    public function RegisterProses($param) {
        $id = ""; $rHtml = "";

        $Data = (object) $this->Register($param);
        $IsError = $Data->IsError;

        if($IsError == false) {
            $rHtml = "success";
            $id = $Data->Output;

            $notif = $this->Foglobal->MakeAlert("Register berhasil, silahkan login.", "success");
            $this->session->set_userdata("notifikasi", $notif);
        } else {
            $rHtml = "error";
            $Data->IsError = true;
            $rHtml = $this->Foglobal->MakeAlert("Error: {$Data->ErrMessage}.");
            $this->session->set_userdata("notifikasi", $rHtml);
        }

        returnData:
        $ReturnData = ["IsError" => $IsError, "lsdt" => $rHtml, "id" => $id];
        return json_encode($ReturnData);
    }

}
