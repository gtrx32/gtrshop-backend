<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('пользователь', 'пользователи');
    }

    protected function setupShowOperation()
    {
        CRUD::addColumn([
            'name' => 'id',
            'type' => 'number',
            'label' => 'ID',
        ]);

        CRUD::addColumn([
            'name' => 'avatar',
            'type' => 'image',
            'label' => 'Аватар',
            'width' => '200px',
            'height' => '200px',
            'prefix' => 'storage/',
        ]);

        CRUD::addColumn([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Имя',
        ]);

        CRUD::addColumn([
            'name' => 'email',
            'type' => 'email',
            'label' => 'Электронная почта',
        ]);

        CRUD::addColumn([
            'name' => 'created_at',
            'type' => 'datetime',
            'label' => 'Дата создания',
        ]);

        CRUD::addColumn([
            'name' => 'updated_at',
            'type' => 'datetime',
            'label' => 'Дата обновления',
        ]);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'id',
            'type' => 'number',
            'label' => 'ID',
        ]);

        CRUD::addColumn([
            'name' => 'avatar',
            'type' => 'image',
            'label' => 'Аватар',
            'width' => '50px',
            'height' => '50px',
            'prefix' => 'storage/',
        ]);

        CRUD::addColumn([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Имя',
        ]);

        CRUD::addColumn([
            'name' => 'email',
            'type' => 'email',
            'label' => 'Электронная почта',
        ]);

        CRUD::addColumn([
            'name' => 'created_at',
            'type' => 'datetime',
            'label' => 'Дата создания',
        ]);

        CRUD::addColumn([
            'name' => 'updated_at',
            'type' => 'datetime',
            'label' => 'Дата обновления',
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);

        CRUD::addField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Имя',
        ]);

        CRUD::addField([
            'name' => 'email',
            'type' => 'email',
            'label' => 'Электронная почта',
        ]);

        CRUD::addField([
            'name' => 'password',
            'type' => 'password',
            'label' => 'Пароль',
        ]);

        CRUD::addField([
            'name' => 'avatar',
            'type' => 'upload',
            'label' => 'Изображение',
            'upload' => true,
            'disk' => 'public',
            'withFiles' => true,
            'prefix' => 'avatars/',
        ]);

        if ($this->crud->getCurrentEntry() && $this->crud->getCurrentEntry()->avatar) {
            $avatarUrl = asset('storage/avatars/' . $this->crud->getCurrentEntry()->avatar);
            CRUD::addField([
                'name' => 'avatar_preview',
                'type' => 'custom_html',
                'value' => '<img src="' . $avatarUrl . '" style="max-width:150px; max-height:150px; margin-bottom:10px;" />',
            ]);
        }
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
