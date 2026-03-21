{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Пользователи" icon="la la-users" :link="backpack_url('user')" />
<x-backpack::menu-item title="Товары" icon="la la-box" :link="backpack_url('product')" />
<x-backpack::menu-item title="Отзывы" icon="la la-comment" :link="backpack_url('review')" />
<x-backpack::menu-item title="Корзины" icon="la la-shopping-cart" :link="backpack_url('cart')" />
<x-backpack::menu-item title="Заказы" icon="la la-shopping-bag" :link="backpack_url('order')" />
<x-backpack::menu-item title="Баннеры" icon="la la-ad" :link="backpack_url('banner')" />
<x-backpack::menu-item title="Новости" icon="la la-newspaper" :link="backpack_url('news')" />
