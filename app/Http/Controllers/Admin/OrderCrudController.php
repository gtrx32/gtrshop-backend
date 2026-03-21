<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Requests\Admin\OrderRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OrderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OrderCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Order::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/order');
        CRUD::setEntityNameStrings('заказ', 'заказы');
        CRUD::denyAccess(['create', 'delete']);
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
            'name' => 'status',
            'type' => 'text',
            'label' => 'Статус',
            'value' => function($entry) {
                return $entry->status?->label() ?? '—';
            },
        ]);

        CRUD::addColumn([
            'name' => 'comment',
            'type' => 'textarea',
            'label' => 'Комментарий',
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
            'name' => 'orderItems',
            'type' => 'closure',
            'label' => 'Товары',
            'function' => function($entry) {
                if ($entry->orderItems->isEmpty()) {
                    return '<em>Нет товаров</em>';
                }

                $rows = collect($entry->orderItems)->map(function($op) {
                    $productUrl = backpack_url('product/'.$op->product->id.'/show');
                    $price = number_format($op->price, 2, ',', ' ') . ' ₽';
                    $totalPrice = number_format($op->price * $op->quantity, 2, ',', ' ') . ' ₽';

                    return '<tr>
                        <td><a href="'.$productUrl.'" target="_blank">'.$op->product->name.'</a></td>
                        <td style="text-align:right;">'.$price.'</td>
                        <td style="text-align:right;">'.$op->quantity.'</td>
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
            'name' => 'payment',
            'type' => 'closure',
            'label' => 'Платеж',
            'function' => function($entry) {
                if (!$entry->payment) {
                    return '—';
                }
                return $entry->payment->status?->label() ?? '—';
            },
            'escaped' => false,
        ]);

        CRUD::addColumn([
            'name' => 'delivery',
            'type' => 'closure',
            'label' => 'Доставка',
            'function' => function($entry) {
                if (!$entry->delivery) {
                    return '—';
                }
                return $entry->delivery->status?->label() ?? '—';
            },
            'escaped' => false,
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

    protected function setupListOperation(): void
    {
        CRUD::addColumn([
            'name' => 'id',
            'type' => 'number',
            'label' => 'ID'
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
            'name' => 'status',
            'type' => 'text',
            'label' => 'Статус',
            'value' => function($entry) {
                return $entry->status?->label() ?? '—';
            },
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

    protected function setupUpdateOperation(): void
    {
        CRUD::setValidation(OrderRequest::class);

        CRUD::addField([
            'name'    => 'status',
            'type'    => 'select_from_array',
            'label'   => 'Статус',
            'options' => collect(OrderStatus::cases())
                ->mapWithKeys(fn($case) => [$case->value => $case->label()])
                ->toArray(),
        ]);

        CRUD::addField([
            'name' => 'comment',
            'type' => 'textarea',
            'label' => 'Комментарий',
        ]);
    }
}
