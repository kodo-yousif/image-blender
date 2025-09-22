@extends('layout')

@section('content')
<div class="card p-4 shadow-sm mb-4">
    <h4 class="mb-3">Blend two images</h4>
    <form id="blend-form" action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold" for="im1">Image 1</label>
                <input type="file" id="im1" name="im1" accept="image/*" class="d-none" required>

                <label id="dz-im1" class="dropzone" for="im1">
                    <div class="dz-body">
                        <div class="dz-icon">📤</div>
                        <div class="dz-text">Drag & drop or <span class="dz-link">browse</span></div>
                        <small class="text-muted">PNG/JPG · up to ~10MB</small>
                    </div>
                    <div class="dz-preview d-none">
                        <img id="preview1" class="img-fluid rounded">
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <span id="meta1" class="badge bg-light text-dark"></span>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="remove1">Remove</button>
                        </div>
                    </div>
                </label>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold" for="im2">Image 2</label>
                <input type="file" id="im2" name="im2" accept="image/*" class="d-none" required>

                <label id="dz-im2" class="dropzone" for="im2">
                    <div class="dz-body">
                        <div class="dz-icon">📤</div>
                        <div class="dz-text">Drag & drop or <span class="dz-link">browse</span></div>
                        <small class="text-muted">PNG/JPG · up to ~10MB</small>
                    </div>
                    <div class="dz-preview d-none">
                        <img id="preview2" class="img-fluid rounded">
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <span id="meta2" class="badge bg-light text-dark"></span>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="remove2">Remove</button>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <div class="mt-4">
            <label class="form-label fw-semibold">Quality preset</label>
            <div class="row g-3">
                @php
                    $presets = [
                        'default' => ['Prefer', 'Balanced • ~0.9 min'],
                        'low'     => ['Low',    'Quick & light • ~0.9 min'],
                        'medium'  => ['Medium', 'Better quality • ~2.5 min'],
                        'high'    => ['High',   'Best quality • ~4.1 min'],
                        'zero'    => ['Zero',   'Test run • ~0.1 min'],
                    ];
                @endphp

                @foreach($presets as $value => [$title, $sub])
                <div class="col-md-4 col-lg-3">
                    <input class="btn-check" type="radio" name="preset" id="preset-{{ $value }}" value="{{ $value }}" {{ $value==='default' ? 'checked' : '' }}>
                    <label class="preset-card btn w-100 text-start" for="preset-{{ $value }}">
                        <div class="fw-semibold">{{ $title }}</div>
                        <div class="small text-muted">{{ $sub }}</div>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-4 text-center">
            <label class="form-label fw-semibold d-block mb-2">💇 Hair Color From</label>
            <div class="btn-group btn-group-toggle" role="group" aria-label="Hair Color From">
                <input type="radio" class="btn-check" name="hair" id="hair1" value="1" checked>
                <label class="btn btn-outline-primary px-4 py-2" for="hair1">
                    Image 1
                </label>

                <input type="radio" class="btn-check" name="hair" id="hair2" value="2">
                <label class="btn btn-outline-primary px-4 py-2" for="hair2">
                    Image 2
                </label>
            </div>
        </div>

        <div class="d-grid mt-4">
            <button id="submit-btn" type="submit" class="btn btn-primary" disabled>Start Process</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<style>
    .dropzone {
        border: 2px dashed #d6d6e7; border-radius: 14px; padding: 18px; background: #fafafa;
        transition: all .2s ease; position: relative; min-height: 180px;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
    }
    .dropzone:hover { background: #f5f7ff; border-color: #b9c7ff; }
    .dropzone.dragover { background: #eef3ff; border-color: #6ea8fe; box-shadow: 0 0 0 3px rgba(13,110,253,.15) inset; }
    .dz-body { text-align: center; }
    .dz-icon { font-size: 28px; margin-bottom: 6px; }
    .dz-link { color: #0d6efd; text-decoration: underline; }
    .dz-preview img { max-height: 220px; width: auto; display: block; margin: 0 auto; }
    .preset-card {
        border: 1.5px solid #e7e7f2; border-radius: 12px; padding: 12px 14px; background: #fff;
        transition: all .15s ease;
    }
    .btn-check:checked + .preset-card { border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13,110,253,.15); }
</style>

<script>
(function(){
    const im1 = document.getElementById('im1');
    const im2 = document.getElementById('im2');
    const dz1 = document.getElementById('dz-im1');
    const dz2 = document.getElementById('dz-im2');
    const prev1 = document.getElementById('preview1');
    const prev2 = document.getElementById('preview2');
    const meta1 = document.getElementById('meta1');
    const meta2 = document.getElementById('meta2');
    const remove1 = document.getElementById('remove1');
    const remove2 = document.getElementById('remove2');
    const submitBtn = document.getElementById('submit-btn');

    function bytesToNice(b) {
        if (b < 1024) return b + ' B';
        if (b < 1024*1024) return (b/1024).toFixed(1) + ' KB';
        return (b/1024/1024).toFixed(1) + ' MB';
    }

    function wireInput(input, previewImg, metaBadge, dzLabel, removeBtn) {
        const body = dzLabel.querySelector('.dz-body');
        const preview = dzLabel.querySelector('.dz-preview');

        function showPreview(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                body.classList.add('d-none');
                preview.classList.remove('d-none');
                metaBadge.textContent = `${file.name} • ${bytesToNice(file.size)}`;
                validateSubmit();
            };
            reader.readAsDataURL(file);
        }

        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) { clear(); return; }
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file.');
                input.value = '';
                return;
            }
            if (file.size > 10 * 1024 * 1024) {
                alert('Image is too large (max ~10MB).');
                input.value = '';
                return;
            }
            showPreview(file);
        });

        function clear() {
            input.value = '';
            previewImg.src = '';
            preview.classList.add('d-none');
            body.classList.remove('d-none');
            metaBadge.textContent = '';
            validateSubmit();
        }

        removeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            clear();
        });

        ;['dragenter','dragover'].forEach(evt => {
            dzLabel.addEventListener(evt, (e) => {
                e.preventDefault(); e.stopPropagation();
                dzLabel.classList.add('dragover');
            });
        });
        ;['dragleave','drop'].forEach(evt => {
            dzLabel.addEventListener(evt, (e) => {
                e.preventDefault(); e.stopPropagation();
                dzLabel.classList.remove('dragover');
            });
        });
        dzLabel.addEventListener('drop', (e) => {
            const file = e.dataTransfer.files && e.dataTransfer.files[0];
            if (!file) return;
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            input.dispatchEvent(new Event('change'));
        });
    }

    function validateSubmit() {
        submitBtn.disabled = !(im1.files.length && im2.files.length);
    }

    wireInput(im1, prev1, meta1, dz1, remove1);
    wireInput(im2, prev2, meta2, dz2, remove2);
    validateSubmit();
})();
</script>
@endsection
