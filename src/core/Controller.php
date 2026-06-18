<?php

class Controller
{
    protected function view($path, $data = [])
    {
        extract($data);
        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/' . $path . '.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    protected function viewFull($path, $data = [])
    {
        extract($data);
        require __DIR__ . '/../views/' . $path . '.php';
    }

    protected function redirect($path)
    {
        header('Location: ' . base_url($path));
        exit;
    }
}
