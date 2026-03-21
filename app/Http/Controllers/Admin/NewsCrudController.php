<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\NewsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class NewsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class NewsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\News::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/news');
        CRUD::setEntityNameStrings('новость', 'новости');
    }

    protected function setupShowOperation()
    {
        CRUD::addColumn([
            'name' => 'id',
            'type' => 'number',
            'label' => 'ID',
        ]);

        CRUD::addColumn([
            'name' => 'image',
            'type' => 'image',
            'label' => 'Изображение',
            'width' => '200px',
            'height' => '200px',
            'prefix' => 'storage/',
        ]);

        CRUD::addColumn([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Заголовок',
        ]);

        CRUD::addColumn([
            'name' => 'slug',
            'type' => 'text',
            'label' => 'Символьный код',
        ]);

        CRUD::addColumn([
            'name' => 'excerpt',
            'type' => 'textarea',
            'label' => 'Краткое описание',
            'wrapper' => [
                'element' => 'div',
                'style' => 'max-width:500px; white-space: normal; word-wrap: break-word;',
            ],
        ]);

        CRUD::addColumn([
            'name' => 'content',
            'type' => 'textarea',
            'label' => 'Текст',
            'wrapper' => [
                'element' => 'div',
                'style' => 'max-width:700px; white-space: normal; word-wrap: break-word;',
            ],
        ]);

        CRUD::addColumn([
            'name' => 'active_from',
            'type' => 'datetime',
            'label' => 'Активна с',
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
            'name' => 'image',
            'type' => 'image',
            'label' => 'Изображение',
            'prefix' => 'storage/',
            'height' => '50px',
            'width' => '50px',
        ]);

        CRUD::addColumn([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Заголовок',
        ]);

        CRUD::addColumn([
            'name' => 'slug',
            'type' => 'text',
            'label' => 'Символьный код',
        ]);

        CRUD::addColumn([
            'name' => 'active_from',
            'type' => 'datetime',
            'label' => 'Активна с',
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
        CRUD::setValidation(NewsRequest::class);

        CRUD::addField([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Заголовок',
        ]);

        CRUD::addField([
            'name' => 'slug',
            'type' => 'text',
            'label' => 'Символьный код',
            'hint' => 'Оставь пустым — сгенерируется из заголовка',
        ]);

        CRUD::addField([
            'name' => 'excerpt',
            'type' => 'textarea',
            'label' => 'Краткое описание',
        ]);

        CRUD::addField([
            'name' => 'content',
            'type' => 'textarea',
            'label' => 'Текст',
            'attributes' => [
                'rows' => 8,
            ],
        ]);

        CRUD::addField([
            'name' => 'active_from',
            'type' => 'datetime',
            'label' => 'Активна с',
        ]);

        CRUD::addField([
            'name' => 'image',
            'type' => 'upload',
            'label' => 'Изображение',
            'upload' => true,
            'disk' => 'public',
            'withFiles' => true,
            'prefix' => 'news/',
        ]);

        if ($this->crud->getCurrentEntry() && $this->crud->getCurrentEntry()->image) {
            $imageUrl = asset('storage/news/' . $this->crud->getCurrentEntry()->image);
            CRUD::addField([
                'name' => 'image_preview',
                'type' => 'custom_html',
                'value' => '<img src="' . $imageUrl . '" style="max-width:150px; max-height:150px; margin-bottom:10px;" />',
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
