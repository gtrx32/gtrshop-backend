<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ReviewRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Str;

/**
 * Class ReviewCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReviewCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Review::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/review');
        CRUD::setEntityNameStrings('отзыв', 'отзывы');
    }

    protected function setupShowOperation(): void
    {
        CRUD::addColumn([
            'name' => 'id',
            'type' => 'number',
            'label' => 'ID',
        ]);

        CRUD::addColumn([
            'name' => 'user_id',
            'type' => 'closure',
            'label' => 'Пользователь',
            'function'=> function($entry) {
                $user = $entry->user;
                if (!$user) {
                    return '<em>Нет пользователя</em>';
                }
                $url = backpack_url('user/'.$user->id.'/show');
                return '<a href="'.$url.'" target="_blank">'.$user->name.'</a>';
            },
            'escaped' => false,
        ]);

        CRUD::addColumn([
            'name' => 'product_id',
            'type' => 'select',
            'label' => 'Товар',
            'entity' => 'product',
            'model' => 'App\Models\Product',
            'attribute' => 'name',
        ]);

        CRUD::addColumn([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Заголовок',
        ]);

        CRUD::addColumn([
            'name' => 'comment',
            'type' => 'textarea',
            'label' => 'Комментарий',
            'wrapper' => [
                'element' => 'div',
                'style' => 'max-width:500px; white-space: normal; word-wrap: break-word;',
            ],
        ]);

        CRUD::addColumn([
            'name' => 'rating',
            'type' => 'number',
            'label' => 'Оценка',
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
            'name' => 'user_id',
            'type' => 'closure',
            'label' => 'Пользователь',
            'function'=> function($entry) {
                $user = $entry->user;
                if (!$user) {
                    return '<em>Нет пользователя</em>';
                }
                $url = backpack_url('user/'.$user->id.'/show');
                return '<a href="'.$url.'" target="_blank">'.$user->name.'</a>';
            },
            'escaped' => false,
        ]);

        CRUD::addColumn([
            'name' => 'product_id',
            'type' => 'text',
            'label' => 'Товар',
            'entity' => 'product',
            'model' => 'App\Models\Product',
            'attribute' => 'name',
            'escaped' => false,
            'value' => function($entry) {
                return Str::limit($entry->product->name, 15, '…');
            },
        ]);

        CRUD::addColumn([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Заголовок',
            'escaped' => false,
            'value' => function($entry) {
                return Str::limit($entry->title, 15, '…');
            },
        ]);

        CRUD::addColumn([
            'name' => 'rating',
            'type' => 'number',
            'label' => 'Оценка',
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
    protected function setupCreateOperation(): void
    {
        CRUD::setValidation(ReviewRequest::class);

        CRUD::addField([
            'name' => 'user_id',
            'type' => 'select',
            'label' => 'Пользователь',
            'entity' => 'user',
            'model' => 'App\Models\User',
            'attribute' => 'name',
        ]);

        CRUD::addField([
            'name' => 'product_id',
            'type' => 'select',
            'label' => 'Товар',
            'entity' => 'product',
            'model' => 'App\Models\Product',
            'attribute' => 'name',
        ]);

        CRUD::addField([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Заголовок',
        ]);

        CRUD::addField([
            'name' => 'comment',
            'type' => 'textarea',
            'label' => 'Комментарий',
        ]);

        CRUD::addField([
            'name' => 'rating',
            'type' => 'number',
            'label' => 'Оценка',
            'attributes' => ['min' => 0, 'max' => 5],
        ]);
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
