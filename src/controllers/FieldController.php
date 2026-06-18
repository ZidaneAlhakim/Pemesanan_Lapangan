<?php

class FieldController extends Controller
{
    public function index()
    {
        $model = new FieldModel();
        $sport = $_GET['sport'] ?? '';
        $fields = $model->getAll(['sport' => $sport]);
        $sports = $model->getSports();

        $this->view('public/catalog', [
            'fields' => $fields,
            'sports' => $sports,
            'selectedSport' => $sport,
        ]);
    }

    public function schedule($id)
    {
        $model = new FieldModel();
        $field = $model->find($id);

        if (!$field) {
            http_response_code(404);
            $this->view('public/404', ['message' => 'Lapangan tidak ditemukan.']);
            return;
        }

        $date = $_GET['date'] ?? date('Y-m-d');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $date = date('Y-m-d');
        }

        $slots = $model->getHourlyAvailability($id, $date);

        $this->view('public/schedule', [
            'field' => $field,
            'slots' => $slots,
            'date' => $date,
        ]);
    }
}
