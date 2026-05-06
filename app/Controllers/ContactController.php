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
        // return $this->contactModel->where('id', '>', 1)
        //                           ->orderBy('id', 'DESC')
        //                           ->paginate(3);

        // return $this->contactModel
        //     ->select('id', 'name', 'email')
        //     ->orderBy('id')
        //     ->first();

        $title = "Contactos";
        if (isset($_GET['search'])) {
            $contacts = $this->contactModel->where('name', 'LIKE', '%' . $_GET['search'] . '%')->paginate(3);
        } else {
            $contacts = $this->contactModel->paginate(3);
        }

        return $this->view('contacts.index', compact('title', 'contacts'));
    }

    public function show(int $id)
    {
        $title = "Detalle del Contacto";
        $contact = $this->contactModel->find($id);

        return $this->view('contacts.show', compact('title', 'contact'));
    }

    public function create()
    {
        $title = "Crear Contacto";
        return $this->view('contacts.create', compact('title'));
    }

    public function store()
    {
        if ($this->contactModel->validate($_POST)) {
            $contact = $this->contactModel->create($_POST);
            if (empty($contact)) {
                return json_encode([
                    'error' => true,
                    'errors' => [
                        'db_error' => 'No se pudo insertar el contacto en la base de datos'
                    ]
                ]);
            } else {
                return json_encode([
                    'error' => false
                ]);
            }
        }

        return json_encode([
            'error' => true,
            'errors' => $this->contactModel->errors
        ]);
    }

    public function edit(int $id)
    {
        $title = "Actualizar Contacto";
        $contact = $this->contactModel->find($id);

        return $this->view('contacts.edit', compact('title', 'contact'));
    }

    public function update(int $id)
    {
        if ($this->contactModel->validate($_POST)) {
            $contact = $this->contactModel->update($id, $_POST);
            if (empty($contact)) {
                return json_encode([
                    'error' => true,
                    'errors' => [
                        'db_error' => 'No se pudo actualizar el contacto en la base de datos'
                    ]
                ]);
            } else {
                return json_encode([
                    'error' => false
                ]);
            }
        }

        return json_encode([
            'error' => true,
            'errors' => $this->contactModel->errors
        ]);
    }

    public function destroy(int $id)
    {
        $this->contactModel->delete($id);
        redireccionar('/contacts');
    }
}
