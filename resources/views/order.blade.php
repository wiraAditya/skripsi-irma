<x-frontend-layout>
    <div class="container d-flex flex-column">
        <div class="sticky-top">
            <div class="row text-center"
                style="background-color: #854A25; margin-left: 0px; margin-right: 0px; color: #FAE9D1;">
                <h3 class="mt-4" style="font-family: Playfair Display, Serif">
                    AMEDIAN DAPUR RESTORAN
                </h3>
                <h6 data-table-name="{{ $table?->name }}">{{ $table?->name ? 'Table : ' . $table->name : '' }}</h6>
            </div>
            <div class="row d-flex overflow-auto flex-nowrap mb-3 ps-3 category scroll-horizontal"
                style="background-color: #FFF1DF">
                <div class="col-auto" style="margin-top: 10px; margin-bottom: 10px">
                    <h4><span class="badge category-list" data-category-id="0">All</span>
                    </h4>
                </div>
                @foreach ($menuCategories as $category)
                    <div class="col-auto" style="margin-top: 10px; margin-bottom: 10px">
                        <h4><span class="badge category-list"
                                data-category-id="{{ $category->id }}">{{ $category->name }}</span></h4>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row d-flex flex-wrap justify-content-center gap-3 mb-3">
            @foreach ($menus as $menu)
                <div class="card" style="width: 18rem; padding-left: 0px; padding-right: 0px"
                    data-id="{{ $menu->id }}">
                    <img src="{{ asset('/storage/menu_images/' . $menu->image) }}" class="card-img-top img-fluid"
                        alt="..." style="height: 350px">
                    <div class="card-body">
                        <h5 class="card-title" style="font-family: Poppins, Semi-bold">{{ $menu->name }}</h5>
                        <div class="mb-3" style="min-height: 8vh; color: #a3a1a1">
                            <p class="card-text fst-italic" style="font-family: Poppins, Regular">
                                {{ $menu->description }}</p>
                        </div>
                        <p class="fw-bold mb-3">{{ 'Rp ' . number_format($menu->price, 0, ',', '.') }}</p>
                        <a href="javascript:void(0)" onclick="addToCart('{{ $menu->id }}')" class="btn w-100"
                            style="background-color: #CC7232; color: #FCF7EE">Add to
                            cart</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mb-3">
            <div class="row">
                <div class="col-12 px-3">
                    <a href="{{ route('checkout', ['table' => $table->name]) }}" class="btn w-100"
                        style="background-color: #80492A; color: white">Proceed to
                        payment</a>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-frontend-layout>
