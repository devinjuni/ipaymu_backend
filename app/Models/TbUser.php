<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class TbUser extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_key',
        'nama',
        'pekerjaan',
        'tgl_lahir',
    ];


    public function get_all_data_user($search){
    return DB::table('vw_user')
    ->where("nama","Like",'%'.$search.'%')
    ->orWhere("pekerjaan","Like",'%'.$search.'%')
    ->orWhere("tgl_lahir_full","Like",'%'.$search.'%')
    ->orderBy("id_user","desc")
    ->get();
    }

    public function delete_data_user($id_user){
        DB::table('tb_users')->where("id_user",$id_user)->delete();
    }

    public function save_data_user($data){
        DB::table('tb_users')->insert($data);
    }

    public function update_data_user($data,$where){
        DB::table('tb_users')->where($where)->update($data);
    }
}
