<div class="mb-3">
    <label for="nombre" class="form-label">
        Nombre
    </label>

    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
        value="{{ old('nombre', $tipoEntrada->nombre ?? '') }}" required>

    @error('nombre')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>


<div class="mb-3">
    <label for="precio" class="form-label">
        Precio
    </label>

    <div class="input-group">
        <span class="input-group-text">
            $
        </span>

        <input type="number" name="precio" id="precio" class="form-control @error('precio') is-invalid @enderror"
            value="{{ old('precio', $tipoEntrada->precio ?? '') }}" min="0" step="1" required>

        @error('precio')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-text">
        Ingresa el valor en pesos chilenos.
    </div>
</div>

<div class="mb-4">
    <div class="form-check form-switch">

        <input type="checkbox" name="activo" id="activo" value="1" class="form-check-input"
            @checked(old('activo', isset($tipoEntrada) ? $tipoEntrada->activo : true))>

        <label for="activo" class="form-check-label">
            Tipo de entrada activo
        </label>

    </div>
</div>

<div class="d-flex gap-2">

    <button type="submit" class="btn btn-success">
        Guardar
    </button>

    <a href="{{ route('admin.tipos-entradas.index') }}" class="btn btn-secondary">
        Cancelar
    </a>

</div>