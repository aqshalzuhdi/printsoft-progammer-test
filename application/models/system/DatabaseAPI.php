<?php

class DatabaseAPI extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    /**
     * @param $Params
     * @return mixed
     */
    public function InsertData($Params, $Koneksi = false) 
    {
        $Step = 0;

        try {
            
            $Step = 1; // Check Koneksi
            if(!$Koneksi) {
                $Koneksi = $this->db;
            }
    
            $Step = 2; //Data yang dikirim

            //pengecekan field
            $ParamsData = $this->Foglobal->CheckFieldExistInDb($Params["ParamsData"], $Params["Target"]);

            $Data       = json_decode($Params["ParamsData"], true);
            $Data       = $this->Foglobal->UnsetArrayByKey($Data, ["ip_address"]);
            $NamaTable  = $Params["Target"];
    
            $Step = 3; // Melakukan Insert Data
            $ResultDB = $Koneksi->insert($NamaTable, $Data);
    
            $Step = 4; // Mengambil last Id insert
            $LastID = $Koneksi->insert_id();
    
            $Step = 5; // Hasil Error dan Sukses
            if(!$ResultDB) {
                $Hasil["IsError"]       = true;
                $Hasil["ErrMessage"]    = $Koneksi->error()["message"];
            } else {
                $Hasil["IsError"]       = false;
                $Hasil["Output"]        = $LastID;
            }
        } catch (Exception $ex) {
            $Hasil["IsError"]           = true;
            $Hasil["ErrMessage"]        = "Insert Data gagal : ".$ex." pada Step : ".$Step;
        }

        return $Hasil;
    }

    /**
     * @param $Params
     * @return mixed
     */
    public function UpdateData($Params, $Koneksi = false){
        if(!$Koneksi) {
            $Koneksi = $this->db;
        }
        
        //pengecekan field
        $ParamsData = $this->Foglobal->CheckFieldExistInDb($Params["ParamsData"], $Params["Target"]);
        
        $Data = json_decode($Params["ParamsData"], true);
        $Data = $this->Foglobal->UnsetArrayByKey($Data, ["db", "ip_address"]);

        $NamaTabel=$Params["Target"];
        $Filter=$Params["ParamsFilter"];
        $Colums=array();

        // Looping ParamsData
        $ParamsDataArr=$Data;
        foreach ($ParamsDataArr as $key=>$value){
            $Colums[]=$key."='".$value."'";
        }

        $sql="update %s set %s where %s";
        $sql=sprintf($sql, $NamaTabel, implode(",",$Colums), $Filter);

        $query = $Koneksi->query($sql);
        $AffectedRow=$Koneksi->affected_rows();

        if(!$query){
            $Hasil["IsError"]=true;
            $Hasil["ErrMessage"]=$Koneksi->error()["message"];
            return $Hasil;
        }

        $Hasil["IsError"]=false;
        $Hasil["Output"]=$AffectedRow." row(s) affected";
        return $Hasil;

    }

    /**
     * @param $Params
     * @return mixed
     */
    public function DeleteData($Params, $Koneksi = false){
        if(!$Koneksi) {
            $Koneksi = $this->db;
        } 

        $NamaTabel=$Params["Target"];
        $Filter=$Params["ParamsFilter"];
        $Colums=array();


        $sql="delete from %s where %s";
        $sql=sprintf($sql, $NamaTabel, $Filter);

        $query = $Koneksi->query($sql);
        $AffectedRow=$Koneksi->affected_rows();

        if(!$query){
            $Hasil["IsError"]=true;
            $Hasil["ErrMessage"]=$Koneksi->error()["message"];
            return $Hasil;
        }

        $Hasil["IsError"]=false;
        $Hasil["Output"]=$AffectedRow." row(s) affected";
        return $Hasil;

    }

    /**
     * @param $Params
     * @return mixed
     */
    public function GetData($Params, $Koneksi = false){
        if(!$Koneksi) {
            $Koneksi = $this->db;
        }

        if(!empty($Params["Page"])) {
            $IsPaging=($Params["Page"]=="x"?false:true);
        } else {
            $IsPaging=false;
        }

        $NamaTabel=$Params["Target"];
        $Sql="";

        $HalKe=(!empty($Params["Page"]) and is_numeric($Params["Page"]))? $Params["Page"] : 1;


        $JumlahDataPerHal=(!empty($Params["Limit"]) and is_numeric($Params["Limit"]))?$Params["Limit"]:10;
        $JmlDataTotal=0;
        $JmlHalTotal = 0;

        // Set ParamsField to SQL
        if (empty($Params["ParamsField"]) || $Params["ParamsField"]=="*"){
            $Sql="select * from ".$NamaTabel;
        }else{
            $Sql="select ".$Params["ParamsField"]." from ".$NamaTabel;
        }

        // Set ParamsFilter to SQL
        if (!empty($Params["ParamsFilter"])){
            $Sql.=" where ".$Params["ParamsFilter"];
        }

        // Set ParamsGroupBy to SQL
        if (!empty($Params["ParamsGroupBy"])){
            $Sql.=" group by ".$Params["ParamsGroupBy"];
        }

        // Set ParamsSort to SQL
        if (!empty($Params["ParamsSort"])){
            $Sql.=" order by ".$Params["ParamsSort"];
        }

        // Jika Paging TRUE
        if($IsPaging){

            // Count ke DB untuk hitung jumlah data
            /*preg_match("/from (.*)/", $Sql, $SplitSQL);
            $SqlCountData="select count(0) as count ".$SplitSQL[0];*/
            $SqlCountData="select count(0) as count from (".$Sql.") as aliases";
            $CountDataDB = $Koneksi->query($SqlCountData);

            // Check Error DB
            if(!$CountDataDB){
                $Hasil["IsError"]=true;
                $Hasil["ErrMessage"]=$Koneksi->error()["message"];
                return $Hasil;
            }

            // Ambil jml data dan jml halaman
            $ResultDB=$CountDataDB->result();
            $JmlDataTotal=$ResultDB[0]->count;
            $JmlHalTotal = ceil($JmlDataTotal/$JumlahDataPerHal);

            //Jika minta ke halaman terakhir
            if($HalKe==-1){
                $HalKe=$JmlHalTotal;
            }

            //Set SQL finalnya
            $Limit=($HalKe-1) * $JumlahDataPerHal;
            $Sql.=" limit ". $Limit .", ". $JumlahDataPerHal;

        }

        // Access Ke DB
        $QueryDb = $Koneksi->query($Sql);

        // Check Error DB
        if(!$QueryDb){
            $Hasil["IsError"]=true;
            $Hasil["ErrMessage"]=$Koneksi->error()["message"];
            return $Hasil;
        } else if($QueryDb->result() == null) {
            $Hasil["IsError"]   = true;
            $Hasil["ErrMessage"]= "Data tidak ada";
            return $Hasil;
        }
        // else if ($QueryDb->result()==null) {
        //     $Hasil["IsError"]=true;
        //     $Hasil["ErrMessage"]="Data tidak ditemukan";
        //     return $Hasil;
        // }

        $HasilQueryDB=$QueryDb->result();

        // Set Array Paging
        $ArrayPaging=array();
        $ArrayPaging["IsPaging"]=$IsPaging;
        if($JmlDataTotal>0){
            $ArrayPaging["Total"]=(int)$JmlDataTotal;
            $ArrayPaging["Count"]=count($HasilQueryDB);
            $ArrayPaging["HalKe"]=(int)$HalKe;
            $ArrayPaging["JmlHalTotal"]=(int)$JmlHalTotal;
            $ArrayPaging["IsNext"]=($JmlDataTotal > ($JumlahDataPerHal*$HalKe)?true:false);
            $ArrayPaging["IsPrev"]=($HalKe>1?true:false);
            $ArrayPaging["DataDari"]=(($HalKe*$JumlahDataPerHal) - $JumlahDataPerHal) + (count($HasilQueryDB) > 0?1:0);
            $ArrayPaging["DataSampai"]=(count($HasilQueryDB) >= $JumlahDataPerHal? $ArrayPaging["DataDari"] + $JumlahDataPerHal -1:$ArrayPaging["DataDari"]+count($HasilQueryDB)-1);
            $ArrayPaging["InfoPage"]=self::Curency($ArrayPaging["DataDari"])." - ".self::Curency($ArrayPaging["DataSampai"])." dari ".self::Curency($ArrayPaging["Total"]);
        }

        $Hasil["IsError"]=false;
        $Hasil["Data"]=$HasilQueryDB;
        $Hasil["Paging"]=$ArrayPaging;

        return $Hasil;
    }

    /**
     * @param $Params
     * @return mixed
     */
    public function CallProsedure($Params, $Koneksi = false){
        if(!$Koneksi) {
            $Koneksi    = $this->db;
        }

        $NamaProsedur   = $Params["ParamsField"];
        $IsGetResult    = ($Params["GetLastId"] == 1 ?true:false);

        $SqlProcedure   = ($IsGetResult?"CALL":"CALL");

        $xParameter     = json_decode($Params["ParamsData"],true);

        $data           = $Koneksi->query($SqlProcedure." ". $NamaProsedur."(?" . str_repeat(",?", count($xParameter)-1) . ")",$xParameter); //($IsGetResult?" as result":"")


        // Check Sukses Akses Db
        if(!$data){
            $Hasil["IsError"]   = true;
            $Hasil["ErrMessage"]= $Koneksi->error()["message"];
            return $Hasil;
        }

        if($IsGetResult){
            $Hasil["IsError"]= false;
            $Hasil["Output"] = $Koneksi->affected_rows()."  row(s) affected";
        }else{
            $Hasil["IsError"]= false;
            $result          = $data->result_array();
            $Hasil["Data"]   = $result;
        }
        
        return $Hasil;

    }

    /**
     * Fungsi Format Angka ke Ribuan 10000 => 10.000
     * @param int $integer
     * @return int|string
     */
    private function Curency($integer=0){
        if(!is_numeric($integer)) return 0;
        return  number_format($integer, 0, ",", ".");
    }
}