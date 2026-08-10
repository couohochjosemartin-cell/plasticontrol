<div class="row g-4">
    <div class="col-12 col-lg-8">
        <label
            for="nombre"
            class="form-label"
        >
            Nombre de la categoría
            <span class="text-danger">*</span>
        </label>

        <input
            id="nombre"
            name="nombre"
            type="text"
            class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $categoria?->nombre) }}"
            maxlength="100"
            placeholder="Ej. Bolsas, Desechables, Limpieza"
            required
            autofocus
        >

        @error('nombre')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            Utiliza un nombre claro que permita identificar fácilmente los productos.
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <label
            for="estado"
            class="form-label"
        >
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
                value="Activa"
                @selected(old('estado', $categoria?->estado?->value ?? 'Activa') === 'Activa')
            >
                Activa
            </option>

            <option
                value="Inactiva"
                @selected(old('estado', $categoria?->estado?->value) === 'Inactiva')
            >
                Inactiva
            </option>
        </select>

        @error('estado')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            Las categorías inactivas no estarán disponibles para nuevos productos.
        </div>
    </div>

    <div class="col-12">
        <label
            for="descripcion"
            class="form-label"
        >
            Descripción
        </label>

        <textarea
            id="descripcion"
            name="descripcion"
            rows="4"
            maxlength="255"
            class="form-control @error('descripcion') is-invalid @enderror"
            placeholder="Describe brevemente qué tipo de productos pertenecen a esta categoría..."
        >{{ old('descripcion', $categoria?->descripcion) }}</textarea>

        @error('descripcion')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="d-flex justify-content-between mt-1">
            <span class="form-text">
                Campo opcional.
            </span>

            <span
                id="descripcion-counter"
                class="form-text"
            >
                0 / 255
            </span>
        </div>
    </div>
</div>