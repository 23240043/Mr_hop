<div class="form-container">
    <h2>Escribir reseña</h2>
    <p class="text-center" style="color: var(--color-text-light); margin-bottom: 24px; font-style: italic;">Tu opinión nos ayuda a mejorar</p>

    <form method="POST" action="<?= Helper::url('resena', 'nueva') ?>">
        <div class="form-group">
            <label>Tu calificación *</label>
            <div class="stars-input">
                <input type="radio" id="star5" name="calificacion" value="5"><label for="star5">★</label>
                <input type="radio" id="star4" name="calificacion" value="4"><label for="star4">★</label>
                <input type="radio" id="star3" name="calificacion" value="3"><label for="star3">★</label>
                <input type="radio" id="star2" name="calificacion" value="2"><label for="star2">★</label>
                <input type="radio" id="star1" name="calificacion" value="1"><label for="star1">★</label>
            </div>
        </div>

        <div class="form-group">
            <label>Comentario *</label>
            <textarea name="comentario" class="form-control" rows="5" placeholder="Cuéntanos tu experiencia..." required></textarea>
        </div>

        <button type="submit" class="btn btn-block btn-lg">Publicar reseña</button>

        <p class="text-center mt-3">
            <a href="<?= Helper::url('resena', 'index') ?>">← Volver a reseñas</a>
        </p>
    </form>
</div>
