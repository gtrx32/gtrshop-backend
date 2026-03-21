<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BannerRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BannerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BannerCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Banner::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/banner');
        CRUD::setEntityNameStrings('баннер', 'баннеры');
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
            'label' => 'Картинка',
            'width' => '400px',
            'height' => '150px',
            'prefix' => 'storage/',
        ]);

        CRUD::addColumn([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Заголовок',
        ]);

        CRUD::addColumn([
            'name' => 'subtitle',
            'type' => 'textarea',
            'label' => 'Подзаголовок',
            'wrapper' => [
                'element' => 'div',
                'style' => 'max-width:500px; white-space: normal; word-wrap: break-word;',
            ],
        ]);

        CRUD::addColumn([
            'name' => 'url',
            'type' => 'text',
            'label' => 'URL',
        ]);

        CRUD::addColumn([
            'name' => 'product_id',
            'type' => 'number',
            'label' => 'Product ID',
        ]);

        CRUD::addColumn([
            'name' => 'text_color',
            'type' => 'text',
            'label' => 'Цвет текста',
        ]);

        CRUD::addColumn([
            'name' => 'sort',
            'type' => 'number',
            'label' => 'Сортировка',
        ]);

        CRUD::addColumn([
            'name' => 'clicks',
            'type' => 'number',
            'label' => 'Клики',
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
            'label' => 'Картинка',
            'prefix' => 'storage/',
            'height' => '50px',
            'width' => '120px',
        ]);

        CRUD::addColumn([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Заголовок',
        ]);

        CRUD::addColumn([
            'name' => 'url',
            'type' => 'text',
            'label' => 'URL',
        ]);

        CRUD::addColumn([
            'name' => 'product_id',
            'type' => 'number',
            'label' => 'Товар (ID)',
        ]);

        CRUD::addColumn([
            'name' => 'text_color',
            'type' => 'text',
            'label' => 'Цвет',
        ]);

        CRUD::addColumn([
            'name' => 'sort',
            'type' => 'number',
            'label' => 'Сортировка',
        ]);

        CRUD::addColumn([
            'name' => 'clicks',
            'type' => 'number',
            'label' => 'Клики',
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
        CRUD::setValidation(BannerRequest::class);

        CRUD::addField([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Заголовок',
        ]);

        CRUD::addField([
            'name' => 'subtitle',
            'type' => 'textarea',
            'label' => 'Подзаголовок',
        ]);

        CRUD::addField([
            'name' => 'url',
            'type' => 'text',
            'label' => 'URL (приоритетнее товара)',
            'hint' => 'Например: /catalog или https://...',
        ]);

        CRUD::addField([
            'name' => 'product_id',
            'type' => 'number',
            'label' => 'Товар (если выбран — баннер ведёт на него)',
            'attributes' => ['min' => '1'],
        ]);

        CRUD::addField([
            'name' => 'text_color',
            'type' => 'text',
            'label' => 'Цвет текста (#RRGGBB)',
            'attributes' => ['placeholder' => '#404040'],
            'default' => '#404040',
        ]);

        CRUD::addField([
            'name' => 'image',
            'type' => 'upload',
            'label' => 'Изображение',
            'upload' => true,
            'disk' => 'public',
            'withFiles' => true,
            'prefix' => 'banners/',
        ]);

        CRUD::addField([
            'name' => 'sort',
            'type' => 'number',
            'label' => 'Сортировка',
            'default' => 0,
            'attributes' => ['min' => 0],
        ]);

        CRUD::addField([
            'name' => 'clicks',
            'type' => 'number',
            'label' => 'Клики',
            'attributes' => ['readonly' => 'readonly'],
            'default' => 0,
        ]);

        if ($this->crud->getCurrentEntry() && $this->crud->getCurrentEntry()->image) {
            $imageUrl = asset('storage/banners/' . $this->crud->getCurrentEntry()->image);
            CRUD::addField([
                'name' => 'image_preview',
                'type' => 'custom_html',
                'value' => '<img src="' . $imageUrl . '" style="max-width:300px; max-height:150px; margin-bottom:10px;" />',
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
