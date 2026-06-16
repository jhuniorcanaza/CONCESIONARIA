@props([
  'images' => [], 
  'name' => 'images[]', 
  'image_field' => 
  'image_path',
  'maxFiles' => 10,
])

{{-- See https://laraveldaily.com/post/multiple-file-upload-with-dropzone-js-and-laravel-medialibrary-package --}}

<div class="needsclick dropzone text-center" id="dropzone"></div>

<div id="dz-inputs"></div>

<script>
{
  const dzInputs = document.getElementById('dz-inputs');
  const dropzoneElement = document.getElementById('dropzone');

  var uploadedDocumentMap = {};

  Dropzone.options.dropzone = {
    url: '{{ route('image.upload') }}',
    maxFilesize: 2, // MB
    addRemoveLinks: true,
    maxFiles: {{ $maxFiles }},
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    success: function (file, response) {
      const previewElement = file.previewElement;
      previewElement.dataset.imageValue = response.name; // Запоминаем значение
      dzInputs.appendChild(createInput("{{  $name }}", response.name));
      uploadedDocumentMap[file.name] = response.name;
    },
    removedfile: function (file) {
      file.previewElement.remove();
      const name = typeof file.{{ $image_field }} !== 'undefined' ? file.{{ $image_field }} : uploadedDocumentMap[file.name];
      const existingInput = dzInputs.querySelector('input[name="{{  $name }}"][value="' + name + '"]');
      if (existingInput) {
        dzInputs.removeChild(existingInput);
      }

      // the image was jast uploaded, not delete old images
      if (name == uploadedDocumentMap[file.name]) {
        fetch('{{ route('image.delete') }}', {
          method: 'DELETE',
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Content-Type': 'application/json'
          },
          body: JSON.stringify({ path: name })
        })
        .then(response => {
            if (!response.ok) {
                console.error('Failed to delete file on server');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
      }

    },
    init: function () {
      @if($images)
        var files = @json($images ?? []);
        for (var i in files) {
          var file = files[i];
          this.emit("addedfile", file);
          this.emit("thumbnail", file, file.full_path);
          this.emit("complete", file);
          file.previewElement.dataset.imageValue = file.{{ $image_field }};
          dzInputs.appendChild(createInput("{{  $name }}", file.{{ $image_field }}));
        }
      @endif
    }
  };

  function createInput(name, value) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value;
    return input;
  }

  // Initialize Sortable.js
  const sortable = new Sortable(dropzoneElement, {
    animation: 150,
    ghostClass: "sortable-ghost", // class for dragging item
    onEnd: function () {
      updateInputOrder();
    }
  });

  // function for updating the order of inputs
  function updateInputOrder() {
    dzInputs.innerHTML = ''; 
    document.querySelectorAll('#dropzone .dz-preview').forEach(preview => {
      if (preview.dataset.imageValue) {
        dzInputs.appendChild(createInput("{{  $name }}", preview.dataset.imageValue));
      }
    });
  }
}
</script>

<style>
  .dz-details {
    display: none;
  }
  .dropzone .dz-preview:hover .dz-image img {
    -webkit-filter: blur(1px);
    filter: blur(1px);
    cursor: move;
  }
</style>