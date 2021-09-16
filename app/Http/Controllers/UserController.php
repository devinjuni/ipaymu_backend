<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TbUser;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    public function __construct()
    {
        $this->usermodel = new TbUser();
    }

    public function get_all_data_user(Request $request){
        $search = $request->input("search");
        $data=$this->usermodel->get_all_data_user($search);
        $data_array=array();
        foreach($data as $key){
             $tgl=explode("-",$key->tgl_lahir);
             $tanggal=$tgl[2];
             $ref_date=strtotime($key->tgl_lahir); //strtotime ini mengubah varchar menjadi format time
             $week_of_year=date( 'W', $ref_date ); //mengetahui minggu ke berapa dari tahun

            $result =array(
                "id_user"=>$key->id_user,
                "user_key"=>$key->user_key,
                "nama"=>$key->nama,
                "pekerjaan"=>$key->pekerjaan,
                "tgl_lahir"=>$key->tgl_lahir,
                "tgl_lahir_full"=>$key->tgl_lahir_full,
                "tipe_tanggal"=>($tanggal%2==0) ? "Genap" : "Ganjil",
                "minggu_ke"=>$week_of_year,
                "tipe_minggu"=>($week_of_year%2==0) ? "Genap" : "Ganjil"


            );

            $data_array[]=$result;
        }

        $output=array(
            "data"=>$data_array
        );
        echo json_encode($output);
    }

    public function delete_data_user(Request $request){
        $id_user = $request->input("id_user");

        try {
            $this->usermodel->delete_data_user($id_user);
            $pesan="success";
            $variant="danger";
            $title="Delete Data";
            $body="Delete Data Success";
        } catch (\Throwable $th) {
            $pesan="error";
            $variant="";
            $title="";
            $body="";
        }

        $output=array(
            "pesan"=>$pesan,
            "variant"=>$variant,
            "title"=>$title,
            "body"=>$body,
        );
        echo json_encode($output);
    }

    public function save_data_user(Request $request){
        $nama = $request->input("nama");
        $pekerjaan = $request->input("pekerjaan");
        $tgl_lahir = $request->input("tgl_lahir");
        $user_key=Uuid::uuid1();
        $updated = Carbon::now();
        $created = Carbon::now();

        try {

            $data=array(
                "user_key"=> $user_key,
                "nama"=>$nama,
                "pekerjaan"=>$pekerjaan,
                "tgl_lahir"=>$tgl_lahir,
                "updated_at"=>$updated,
                "created_at"=>$created,
            );
            $this->usermodel->save_data_user($data);
            $pesan="success";
            $variant="success";
            $title="Insert Data";
            $body="Insert Data Success";
        } catch (\Throwable $th) {
            $pesan="error";
            $variant="";
            $title="";
            $body="";
        }

        $output=array(
            "pesan"=>$pesan,
            "variant"=>$variant,
            "title"=>$title,
            "body"=>$body,
        );
        echo json_encode($output);
    }

    public function update_data_user(Request $request){
        $id_user = $request->input("id_user");
        $nama = $request->input("nama");
        $pekerjaan = $request->input("pekerjaan");
        $tgl_lahir = $request->input("tgl_lahir");
        $updated = Carbon::now();

        try {

            $data=array(
                "nama"=>$nama,
                "pekerjaan"=>$pekerjaan,
                "tgl_lahir"=>$tgl_lahir,
                "updated_at"=>$updated,
            );

            $where=array(
                "id_user"=>$id_user,
            );
            $this->usermodel->update_data_user($data,$where);
            $pesan="success";
            $variant="success";
            $title="Update Data";
            $body="Update Data Success";
        } catch (\Throwable $th) {
            $pesan="error";
            $variant="";
            $title="";
            $body="";
        }

        $output=array(
            "pesan"=>$pesan,
            "variant"=>$variant,
            "title"=>$title,
            "body"=>$body,
        );
        echo json_encode($output);
    }


    public function redirect_payment(Request $request){
        $va           = '0000005858682919'; //get on iPaymu dashboard
        $secret       = 'SANDBOXE9167ABF-ACAF-493D-8FC2-AFBB2F96DE83-20210916081136'; //get on iPaymu dashboard
        $sessionId = "";
        $url          = 'https://sandbox.ipaymu.com/api/v2/payment'; //url
        $method       = 'POST'; //method

        $nama= $request->input("nama");
        $phone= $request->input("phone");
        $email= $request->input("email");
        $address= $request->input("address");
        $area= $request->input("area");
        $method_pembayaran= $request->input("method");


        //Request Body//
        $body['product']    = ['Baju','Celana'];
        $body['qty']        = ['1', '3'];
        $body['price']      = ['1000', '2000'];
        $body['description']= ['1000', '2000'];
        $body['returnUrl']  = 'https://mywebsite.com/thankyou';
        $body['cancelUrl']  = 'https://mywebsite.com/cancel';
        $body['notifyUrl']  = 'https://mywebsite.com/notify';
        $body['referenceId']  = 'ID1234';
        $body['weight']  = array('1', '1');
        $body['dimension']  = array('1:1:1', '1:1:1');
        $body['buyerName']  = $nama;
        $body['buyerEmail']  = $email;
        $body['buyerPhone']  = $phone;
        $body['pickupArea']  = $area;
        $body['pickupAddress']  = $address;
        $body['paymentMethod']  = $method_pembayaran;
        //End Request Body//

        //Generate Signature
        // *Don't change this
        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $secret;
        $signature    = hash_hmac('sha256', $stringToSign, $secret);
        $timestamp    = Date('YmdHis');
        //End Generate Signature


        $ch = curl_init($url);

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $err = curl_error($ch);
        $ret = curl_exec($ch);
        curl_close($ch);
        if($err) {
            echo $err;
        } else {
            //Response
            $ret = json_decode($ret);
            if($ret->Status == 200) {

                $sessionId  = $ret->Data->SessionID;
                $url        =  $ret->Data->Url;
                header('Location:' . $url);

            } else {

               echo $ret;
            }

        }



        echo json_encode($ret);


    }
}
