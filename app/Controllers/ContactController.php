<?php

namespace App\Controllers;

use App\Models\Contact;

class ContactController extends Controller
{
    protected Contact $contactModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->contactModel = new Contact;
    }

    public function index()
    {
        $contacts = $this->contactModel->all();
        return $this->view('contacts.index', compact('contacts'));
    }

    public function store()
    {
        $this->contactModel->create($_POST);
        header('Location: ' . BASE_URL . '/contacts');
    }
}
