<div id="drop_zone_create" style="border: 2px dashed #ccc; padding: 20px; text-align: center; margin-bottom: 15px;">
    Arrastra imágenes aquí (solo JPG/PNG) o haz clic para seleccionar
    <input id="files_create" type="file" name="userfile" accept=".jpg,.jpeg,.png" style="display: none;"/>
</div>
<output id="list-miniatura"></output>
<div id="error-msg" style="color: red; margin-top: 5px;"></div>

<script>
    // Configuración para drag and drop
    var dropZone = document.getElementById('drop_zone_create');
    var fileInput = document.getElementById('files_create');
    var errorMsg = document.getElementById('error-msg');
    
    function isValidImage(file) {
        var validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        return validTypes.includes(file.type);
    }

    function handleFileSelect(evt) {
        evt.stopPropagation();
        evt.preventDefault();
        
        errorMsg.textContent = '';
        var files = evt.dataTransfer ? evt.dataTransfer.files : evt.target.files;
        
        // Verificar si se seleccionó algún archivo
        if (files.length === 0) return;
        
        // Limpiar vista previa anterior
        document.getElementById('list-miniatura').innerHTML = '';
        
        for (var i = 0, f; f = files[i]; i++) {
            if (!isValidImage(f)) {
                errorMsg.textContent = 'Error: Solo se permiten archivos JPG o PNG';
                continue;
            }
            
            var reader = new FileReader();
            reader.onload = (function(theFile) {
                return function(e) {
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
                    fileName.textContent = theFile.name.length > 15 ? 
                        theFile.name.substring(0, 15) + '...' : theFile.name;
                    
                    container.appendChild(img);
                    container.appendChild(fileName);
                    document.getElementById('list-miniatura').appendChild(container);
                };
            })(f);
            reader.readAsDataURL(f);
        }
    }

    function handleDragOver(evt) {
        evt.stopPropagation();
        evt.preventDefault();
        evt.dataTransfer.dropEffect = 'copy';
    }

    // Eventos
    dropZone.addEventListener('dragover', handleDragOver, false);
    dropZone.addEventListener('drop', handleFileSelect, false);
    dropZone.addEventListener('click', function() {
        fileInput.value = ''; // Resetear input para permitir seleccionar el mismo archivo otra vez
        fileInput.click();
    });
    fileInput.addEventListener('change', handleFileSelect, false);
</script>