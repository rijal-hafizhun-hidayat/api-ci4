<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_komik extends Model
{
    protected $table = 'komik';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama',
        'penerbit',
        'penulis',
        'created_at',
        'updated_at'
    ];

    public function __construct()
    {
        $this->database = db_connect();
    }
    public function getKomik()
    {
        $sql = "
            SELECT * FROM komik
        ";

        return $this->database->query($sql)->getResult();
    }

    public function getKomikById($id)
    {
        $sql = "
            SELECT
                *
            FROM komik
            WHERE komik.id = '" . $id . "'";

        return $this->database->query($sql)->getResult();
    }
}
