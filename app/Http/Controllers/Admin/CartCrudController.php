<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CartCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CartCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Cart::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/cart');
        CRUD::setEntityNameStrings('корзина', 'корзины');
        CRUD::denyAccess(['create', 'update', 'delete']);
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
            'function' => function($entry) {
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
            'name' => 'cartItems',
            'type' => 'closure',
            'label' => 'Товары',
            'function' => function($entry) {
                if ($entry->cartItems->isEmpty()) {
                    return '<em>Нет товаров</em>';
                }

                $rows = collect($entry->cartItems)->map(function($cp) {
                    $productUrl = backpack_url('product/'.$cp->product->id.'/show');
                    $price = number_format($cp->product->price, 2, ',', ' ') . ' ₽';
                    $totalPrice = number_format($cp->product->price * $cp->quantity, 2, ',', ' ') . ' ₽';

                    return '<tr>
                        <td><a href="'.$productUrl.'" target="_blank">'.$cp->product->name.'</a></td>
                        <td style="text-align:right;">'.$price.'</td>
                        <td style="text-align:right;">'.$cp->quantity.'</td>
                        <td style="text-align:right; font-weight:bold;">'.$totalPrice.'</td>
                    </tr>';
                })->implode('');

                return '<table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom:2px solid #aaa; text-align:left;">
                            <th style="padding:4px;">Название</th>
                            <th style="padding:4px; text-align:right;">Цена</th>
                            <th style="padding:4px; text-align:right;">Количество</th>
                            <th style="padding:4px; text-align:right;">Итого</th>
                        </tr>
                    </thead>
                    <tbody>
                        '.$rows.'
                    </tbody>
                </table>';
            },
            'escaped' => false,
        ]);

        CRUD::addColumn([
            'name' => 'total_price',
            'type' => 'number',
            'label' => 'Общая сумма',
            'decimals' => 2,
            'suffix' => ' ₽',
            'thousands_sep' => ' ',
        ]);

        CRUD::addColumn([
            'name' => 'total_quantity',
            'type' => 'number',
            'label' => 'Общее количество товаров',
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

        CRUD::removeButton('update');
        CRUD::removeButton('delete');
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
            'function' => function($entry) {
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
            'name' => 'total_price',
            'type' => 'number',
            'label' => 'Сумма',
            'decimals' => 2,
            'suffix' => ' ₽',
            'thousands_sep' => ' ',
        ]);

        CRUD::addColumn([
            'name' => 'total_quantity',
            'type' => 'number',
            'label' => 'Товаров',
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

        $this->crud->setOperationSetting('actionsColumnName', 'Действия');
    }
}
