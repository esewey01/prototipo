<div id="drop_zone_edit" style="border: 2px dashed #ccc; padding: 20px; text-align: center; margin-bottom: 15px;">
    Arrastra imágenes aquí (solo JPG/PNG) o haz clic para seleccionar
    <input id="files_edit" type="file" name="userfileEdit" accept=".jpg,.jpeg,.png" style="display: none;"/>
</div>
<output id="list-miniaturaEdit"></output>
<div id="error-msg-edit" style="color: red; margin-top: 5px;"></div>

<script>
    // Configuración para drag and drop
    var dropZone = document.getElementById('drop_zone_edit');
    var fileInput = document.getElementById('files_edit');
    var errorMsg = document.getElementById('error-msg-edit');
    
    function isValidImage(file) {
        var validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        return validTypes.includes(file.type);
    }

    function handleFileSelectEdit(evt) {
        evt.stopPropagation();
        evt.preventDefault();
        
        errorMsg.textContent = '';
        var files = evt.dataTransfer ? evt.dataTransfer.files : evt.target.files;
        
        if (files.length === 0) return;
        
        document.getElementById('list-miniaturaEdit').innerHTML = '';
        
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
                    document.getElementById('list-miniaturaEdit').appendChild(container);
                };
            })(f);
            reader.readAsDataURL(f);
        }
    }

    function handleDragOverEdit(evt) {
        evt.stopPropagation();
        evt.preventDefault();
        evt.dataTransfer.dropEffect = 'copy';
    }

    // Eventos
    dropZone.addEventListener('dragover', handleDragOverEdit, false);
    dropZone.addEventListener('drop', handleFileSelectEdit, false);
    dropZone.addEventListener('click', function() {
        fileInput.value = '';
        fileInput.click();
    });
    fileInput.addEventListener('change', handleFileSelectEdit, false);
</script>