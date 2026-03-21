<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('товар', 'товары');
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
            'name' => 'name',
            'type' => 'text',
            'label' => 'Название',
        ]);

        CRUD::addColumn([
            'name' => 'slug',
            'type' => 'text',
            'label' => 'Символьный код',
        ]);

        CRUD::addColumn([
            'name' => 'description',
            'type' => 'textarea',
            'label' => 'Описание',
            'wrapper' => [
                'element' => 'div',
                'style' => 'max-width:500px; white-space: normal; word-wrap: break-word;',
            ],
        ]);

        CRUD::addColumn([
            'name' => 'price',
            'type' => 'number',
            'label' => 'Стоимость',
            'decimals' => 2,
            'suffix' => ' ₽',
            'thousands_sep' => ' ',
        ]);

        CRUD::addColumn([
            'name' => 'stock',
            'type' => 'number',
            'label' => 'Остаток на складе',
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
    protected function setupListOperation(): void
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
            'name' => 'name',
            'type' => 'text',
            'label' => 'Название',
        ]);

        CRUD::addColumn([
            'name' => 'slug',
            'type' => 'text',
            'label' => 'Символьный код',
        ]);

        CRUD::addColumn([
            'name' => 'price',
            'type' => 'number',
            'label' => 'Стоимость',
            'decimals' => 2,
            'suffix' => ' ₽',
            'thousands_sep' => ' ',
        ]);

        CRUD::addColumn([
            'name' => 'stock',
            'type' => 'number',
            'label' => 'Остаток на складе',
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
        CRUD::setValidation(ProductRequest::class);

        CRUD::addField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Название',
        ]);

        CRUD::addField([
            'name' => 'slug',
            'type' => 'text',
            'label' => 'Символьный код',
        ]);

        CRUD::addField([
            'name' => 'description',
            'type' => 'textarea',
            'label' => 'Описание',
        ]);

        CRUD::addField([
            'name' => 'price',
            'type' => 'number',
            'label' => 'Стоимость',
            'attributes' => ['step' => '0.01', 'min' => '0'],
            'decimals' => 2,
            'suffix' => ' ₽',
            'thousands_sep' => ' ',
        ]);

        CRUD::addField([
            'name' => 'stock',
            'type' => 'number',
            'label' => 'Остаток на складе',
        ]);

        CRUD::addField([
            'name' => 'image',
            'type' => 'upload',
            'label' => 'Изображение',
            'upload' => true,
            'disk' => 'public',
            'withFiles' => true,
            'prefix' => 'products/',
        ]);

        if ($this->crud->getCurrentEntry() && $this->crud->getCurrentEntry()->image) {
            $imageUrl = asset('storage/products/' . $this->crud->getCurrentEntry()->image);
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
