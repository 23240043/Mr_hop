<h2>♻️ Restaurar Base de Datos</h2>

<form action="<?= Helper::url('admin', 'restoreDB') ?>" method="POST" enctype="multipart/form-data">

    <div class="form-group">
        <label>Archivo SQL</label>
        <input type="file" name="backup" class="form-control" accept=".sql" required>
    </div>

    <button type="submit" class="btn btn-primary">
        Restaurar Base de Datos
    </button>

</form>