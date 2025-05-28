<section class="panel">
    <div id="preview-container" style="margin-bottom: 20px;">
        <?php if (!empty($producto_actual['imagen'])): ?>
            <img id="preview-image" src="<?php echo URL_VIEWS . $producto_actual['imagen']; ?>" width="250" height="250" alt="Vista previa de la imagen actual">
        <?php else: ?>
            <img id="preview-image" src="<?php echo URL_VIEWS . 'images/default-product.png'; ?>" width="250" height="250" alt="Imagen por defecto">
        <?php endif; ?>
    </div>
    <div><strong>Cambiar Imagen</strong></div>
    <div id="drop_zone_edit" style="border: 2px dashed #ccc; padding: 20px; text-align: center; margin-bottom: 15px; cursor: pointer;">
        Arrastra imágenes aquí (solo JPG/PNG, máximo 2MB) o haz clic para seleccionar
        <input id="files_edit" type="file" name="userfileEdit" accept=".jpg,.jpeg,.png" style="display: none;"/>
    </div>
    <output id="list-miniaturaEdit"></output>
    <div id="error-msg-edit" style="color: red; margin-top: 5px;"></div>
    <div id="file-info-edit" style="margin-top: 5px; font-size: 0.9em;"></div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var dropZone = document.getElementById('drop_zone_edit');
    var fileInput = document.getElementById('files_edit');
    var errorMsg = document.getElementById('error-msg-edit');
    var fileInfo = document.getElementById('file-info-edit');
    var previewImage = document.getElementById('preview-image');
    
    function isValidImage(file) {
        // Validar tipo MIME y extensión
        var validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!validTypes.includes(file.type)) {
            return false;
        }
        
        // Validar tamaño (2MB máximo)
        if (file.size > 2 * 1024 * 1024) {
            return false;
        }
        
        return true;
    }

    function handleFileSelectEdit(evt) {
        evt.stopPropagation();
        evt.preventDefault();
        
        errorMsg.textContent = '';
        fileInfo.textContent = '';
        var files = evt.dataTransfer ? evt.dataTransfer.files : evt.target.files;
        
        if (files.length === 0) return;
        
        // Limpiar miniatura anterior
        document.getElementById('list-miniaturaEdit').innerHTML = '';
        
        // Procesar solo el primer archivo (puedes modificar para múltiples)
        var f = files[0];
        
        if (!isValidImage(f)) {
            errorMsg.textContent = 'Error: Solo se permiten archivos JPG o PNG (máximo 2MB)';
            return;
        }
        
        // Mostrar información del archivo
        fileInfo.textContent = `Archivo seleccionado: ${f.name} (${(f.size/1024/1024).toFixed(2)} MB)`;
        
        var reader = new FileReader();
        reader.onload = function(e) {
            // Actualizar vista previa principal
            previewImage.src = e.target.result;
            
            // Mostrar miniatura
            var container = document.createElement('div');
            container.style.display = 'inline-block';
            container.style.margin = '5px';
            
            var img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '150px';
            img.style.maxHeight = '150px';
            img.style.border = '1px solid #ddd';
            
            var fileName = document.createElement('div');
            fileName.style.textAlign = 'center';
            fileName.textContent = f.name.length > 15 ? 
                f.name.substring(0, 15) + '...' : f.name;
            
            container.appendChild(img);
            container.appendChild(fileName);
            document.getElementById('list-miniaturaEdit').appendChild(container);
        };
        reader.readAsDataURL(f);
    }

    function handleDragOverEdit(evt) {
        evt.stopPropagation();
        evt.preventDefault();
        evt.dataTransfer.dropEffect = 'copy';
        dropZone.style.borderColor = '#666';
    }

    function handleDragLeaveEdit(evt) {
        dropZone.style.borderColor = '#ccc';
    }

    // Eventos
    dropZone.addEventListener('dragover', handleDragOverEdit, false);
    dropZone.addEventListener('dragleave', handleDragLeaveEdit, false);
    dropZone.addEventListener('drop', function(evt) {
        handleFileSelectEdit(evt);
        handleDragLeaveEdit(evt);
    }, false);
    dropZone.addEventListener('click', function() {
        fileInput.value = '';
        fileInput.click();
    });
    fileInput.addEventListener('change', handleFileSelectEdit, false);
});
</script>