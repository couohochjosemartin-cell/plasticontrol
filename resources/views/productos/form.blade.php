<div class="row g-4">
    
    <div class=""col-12"">
        <label for="nombre" class="form-label">
            Nombre del producto
            <span class="text-danger">*</span>
        </label>

        <input
            id="nombre"
            name="nombre"
            type="text"
            maxlength="150"
            class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $producto?->nombre) }}"
            placeholder="Ej. Vaso cristal No. 10"
            required
        >

        @error('nombre')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-12 col-lg-6">
        <label for="categoria_id" class="form-label">
            Categoría
            <span class="text-danger">*</span>
        </label>

        <select
            id="categoria_id"
            name="categoria_id"
            class="form-select @error('categoria_id') is-invalid @enderror"
            required
        >
            <option value="">
                Selecciona una categoría
            </option>

            @foreach ($categorias as $categoria)
                <option
                    value="{{ $categoria->id }}"
                    @selected(
                        old(
                            'categoria_id',
                            $producto?->categoria_id
                        ) == $categoria->id
                    )
                >
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>

        @error('categoria_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-12 col-lg-3">
        <label for="precio_compra" class="form-label">
            Precio de compra
            <span class="text-danger">*</span>
        </label>

        <input
            id="precio_compra"
            name="precio_compra"
            type="number"
            step="0.01"
            min="0"
            class="form-control @error('precio_compra') is-invalid @enderror"
            value="{{ old('precio_compra', $producto?->precio_compra) }}"
            required
        >

        @error('precio_compra')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-12 col-lg-3">
        <label for="precio_venta" class="form-label">
            Precio de venta
            <span class="text-danger">*</span>
        </label>

        <input
            id="precio_venta"
            name="precio_venta"
            type="number"
            step="0.01"
            min="0"
            class="form-control @error('precio_venta') is-invalid @enderror"
            value="{{ old('precio_venta', $producto?->precio_venta) }}"
            required
        >

        @error('precio_venta')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    @if (! $producto)
        <div class="col-12 col-lg-6">
            <label for="stock_inicial" class="form-label">
                Stock inicial
                <span class="text-danger">*</span>
            </label>

            <input
                id="stock_inicial"
                name="stock_inicial"
                type="number"
                min="0"
                class="form-control @error('stock_inicial') is-invalid @enderror"
                value="{{ old('stock_inicial', 0) }}"
                required
            >

            @error('stock_inicial')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Después del alta, las existencias se modificarán desde Inventario.
            </div>
        </div>
    @endif

    <div class="col-12 col-lg-6">
        <label for="estado" class="form-label">
            Estado
            <span class="text-danger">*</span>
        </label>

        <select
            id="estado"
            name="estado"
            class="form-select @error('estado') is-invalid @enderror"
            required
        >
            <option
                value="Activo"
                @selected(
                    old(
                        'estado',
                        $producto?->estado ?? 'Activo'
                    ) === 'Activo'
                )
            >
                Activo
            </option>

            <option
                value="Inactivo"
                @selected(
                    old(
                        'estado',
                        $producto?->estado
                    ) === 'Inactivo'
                )
            >
                Inactivo
            </option>
        </select>

        @error('estado')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-12">
        <label for="descripcion" class="form-label">
            Descripción
        </label>

        <textarea
            id="descripcion"
            name="descripcion"
            rows="4"
            class="form-control @error('descripcion') is-invalid @enderror"
            placeholder="Información adicional del producto..."
        >{{ old('descripcion', $producto?->descripcion) }}</textarea>

        @error('descripcion')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-12">
        <label for="imagen" class="form-label">
            Imagen del producto
        </label>

        <input
            id="imagen"
            name="imagen"
            type="file"
            accept=".jpg,.jpeg,.png,.webp"
            class="form-control @error('imagen') is-invalid @enderror"
        >

        @error('imagen')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            JPG, PNG o WEBP. Máximo 2 MB.
        </div>

        @if ($producto?->imagen)
            <div class="mt-3">
                <img
                    src="{{ asset('storage/' . $producto->imagen) }}"
                    alt="{{ $producto->nombre }}"
                    class="product-form-image"
                >

                <div class="form-check mt-2">
                    <input
                        id="eliminar_imagen"
                        name="eliminar_imagen"
                        type="checkbox"
                        value="1"
                        class="form-check-input"
                    >

                    <label
                        for="eliminar_imagen"
                        class="form-check-label"
                    >
                        Eliminar imagen actual
                    </label>
                </div>
            </div>
        @endif
    </div>
</div>