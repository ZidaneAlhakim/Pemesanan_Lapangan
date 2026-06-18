<?php

class Router
{
    public static function handle($url)
    {
        $url = strtok($url, '?');

        require_once __DIR__ . '/../helpers/auth.php';

        switch ($url) {
            case 'home':
                (new FieldController())->index();
                break;

            case 'schedule':
                (new FieldController())->schedule($_GET['id'] ?? null);
                break;

            case 'booking':
                (new BookingController())->create();
                break;

            case 'booking/store':
                (new BookingController())->store();
                break;

            case 'summary':
                (new BookingController())->summary();
                break;

            case 'payment':
                (new PaymentController())->upload();
                break;

            case 'history':
                (new BookingController())->history();
                break;

            case 'rating':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    (new RatingController())->store();
                } else {
                    (new RatingController())->form();
                }
                break;

            case 'admin/login':
                (new AdminController())->login();
                break;

            case 'admin/logout':
                (new AdminController())->logout();
                break;

            case 'admin':
                (new AdminController())->dashboard();
                break;

            case 'admin/fields':
                (new AdminController())->fields();
                break;

            case 'admin/bookings':
                (new AdminController())->bookings();
                break;

            case 'admin/validate':
                (new AdminController())->validatePayment();
                break;

            case 'admin/reports':
                (new AdminController())->reports();
                break;

            case 'admin/ratings':
                (new AdminController())->ratings();
                break;

            case 'admin/users':
                (new AdminController())->users();
                break;

            default:
                http_response_code(404);
                (new Controller())->view('public/404', ['message' => 'Halaman tidak ditemukan.']);
                break;
        }
    }
}
