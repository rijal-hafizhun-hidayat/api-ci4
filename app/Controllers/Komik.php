<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Model_komik;

class Komik extends ResourceController
{

    public function __construct()
    {
        $this->model = new Model_komik();
    }

    public function index()
    {
        $data = $this->model->getKomik();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('Data Tidak Ditemukan');
        }
    }

    public function show($id = null)
    {
        $data = $this->model->getKomikById($id);
        if ($data) {
            return $this->respond($data[0]);
        } else {
            return $this->fail('data tidak di temukan dengan id ' . $id, 404);
        }
    }

    public function create()
    {
        date_default_timezone_set("Asia/Jakarta");
        //menerima request dari vue js
        $json = $this->request->getJSON();
        if ($json) {
            $data = [
                'nama' => $json->nama,
                'penerbit' => $json->penerbit,
                'penulis' => $json->penulis,
                'created_at' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))),
                'updated_at' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')))
            ];
        } else {
            //menerima request dari postman
            $data = [
                'nama' => $this->request->getPost('nama'),
                'penerbit' => $this->request->getPost('penerbit'),
                'penulis' => $this->request->getPost('penulis'),
                'created_at' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))),
                'updated_at' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')))
            ];
        }

        // $data = json_decode(file_get_contents("php://input"));
        $insert = $this->model->insert($data);
        if ($insert) {
            return $this->respondCreated($data);
        } else {
            return $this->fail('gagal tersimpan', 400);
        }
    }

    public function update($id = null)
    {
        $json = $this->request->getJSON();
        if ($json) {
            $data = [
                'nama' => $json->nama,
                'penerbit' => $json->penerbit,
                'penulis' => $json->penulis,
                'created_at' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))),
                'updated_at' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')))
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'nama' => $input['nama'],
                'penerbit' => $input['penerbit'],
                'penulis' => $input['penulis'],
            ];
        }

        $model = $this->model->update($id, $data);

        if ($model) {
            return $this->respond($data);
        } else {
            return $this->fail("gagal terupdate", 400);
        }
    }

    public function delete($id = null)
    {
        $model = $this->model->getKomikById($id);
        if ($model) {
            $modelDelete = $this->model->delete(['id' => $id]);
            if ($modelDelete) {
                $response = [
                    'status' => 200,
                    'error' => null,
                    'message' => [
                        'success' => 'data deleted'
                    ]
                ];

                return $this->respondDeleted($response);
            } else {
                return $this->fail($modelDelete, 400);
            }
        } else {
            return $this->failNotFound("data tidak di temukan");
        }
    }
}
