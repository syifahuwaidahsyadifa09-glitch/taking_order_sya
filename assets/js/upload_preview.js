document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.querySelector('input[type="file"][name="gambar"]');
    if (!fileInput) return;

    let preview = document.getElementById('imgPreview');
    if (!preview) {
        preview = document.createElement('img');
        preview.id = 'imgPreview';
        preview.className = 'img-preview';
        preview.alt = 'Preview';
        fileInput.parentNode.appendChild(preview);
    }

    fileInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) { preview.classList.remove('show'); return; }
        if (!['image/jpeg','image/png','image/webp'].includes(file.type)) {
            alert('Format harus JPG, PNG, atau WEBP.');
            fileInput.value = ''; preview.classList.remove('show'); return;
        }
        if (file.size > 3 * 1024 * 1024) {
            alert('Ukuran gambar maksimal 3 MB.');
            fileInput.value = ''; preview.classList.remove('show'); return;
        }
        const reader = new FileReader();
        reader.onload = ev => { preview.src = ev.target.result; preview.classList.add('show'); };
        reader.readAsDataURL(file);
    });
});