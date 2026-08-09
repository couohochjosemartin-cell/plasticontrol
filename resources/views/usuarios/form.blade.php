<div class="row g-4">
    <div class="col-12 col-lg-7">
        <label
            for="nombre_completo"
            class="form-label"
        >
            Nombre completo
            <span class="text-danger">*</span>
        </label>

        <input
            id="nombre_completo"
            name="nombre_completo"
            type="text"
            maxlength="150"
            class="form-control @error('nombre_completo') is-invalid @enderror"
            value="{{ old('nombre_completo', $usuario?->nombre_completo) }}"
            placeholder="Ej. Juan Pérez López"
            required
        >

        @error('nombre_completo')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-12 col-lg-5">
        <label
            for="usuario"
            class="form-label"
        >
            Usuario
            <span class="text-danger">*</span>
        </label>

        <input
            id="usuario"
            name="usuario"
            type="text"
            maxlength="50"
            class="form-control @error('usuario') is-invalid @enderror"
            value="{{ old('usuario', $usuario?->usuario) }}"
            placeholder="Ej. cajero1"
            required
        >

        @error('usuario')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-12 col-lg-6">
        <label
            for="rol_id"
            class="form-label"
        >
            Rol
            <span class="text-danger">*</span>
        </label>

        <select
            id="rol_id"
            name="rol_id"
            class="form-select @error('rol_id') is-invalid @enderror"
            required
        >
            <option value="">
                Selecciona un rol
            </option>

            @foreach ($roles as $rol)
                <option
                    value="{{ $rol->id }}"
                    @selected(
                        old('rol_id', $usuario?->rol_id) == $rol->id
                    )
                >
                    {{ $rol->nombre }}
                </option>
            @endforeach
        </select>

        @error('rol_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-12 col-lg-6">
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
                value="Activo"
                @selected(
                    old(
                        'estado',
                        $usuario?->estado ?? 'Activo'
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
                        $usuario?->estado
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

    <div class="col-12 col-lg-6">
        <label
            for="password"
            class="form-label"
        >
            Contraseña
            @if (! $usuario)
                <span class="text-danger">*</span>
            @endif
        </label>

        <input
            id="password"
            name="password"
            type="password"
            minlength="8"
            class="form-control @error('password') is-invalid @enderror"
            @required(! $usuario)
            autocomplete="new-password"
        >

        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        @if ($usuario)
            <div class="form-text">
                Déjala vacía para conservar la contraseña actual.
            </div>
        @endif
    </div>

    <div class="col-12 col-lg-6">
        <label
            for="password_confirmation"
            class="form-label"
        >
            Confirmar contraseña
            @if (! $usuario)
                <span class="text-danger">*</span>
            @endif
        </label>

        <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            minlength="8"
            class="form-control"
            @required(! $usuario)
            autocomplete="new-password"
        >
    </div>
</div>