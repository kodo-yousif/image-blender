@extends('layout')

@section('content')
<div class="card p-4 shadow-sm">
    <h3 class="text-center mb-4">✨ Blending Complete</h3>

    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="result-card p-3 border rounded shadow-sm h-100">
                <h6 class="mb-3">Image 1</h6>
                <img src="{{ $image1 }}"
                     class="img-fluid rounded preview-img"
                     alt="Input 1"
                     data-bs-toggle="modal"
                     data-bs-target="#previewModal"
                     data-img="{{ $image1 }}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="result-card p-3 border rounded shadow-sm h-100">
                <h6 class="mb-3">Image 2</h6>
                <img src="{{ $image2 }}"
                     class="img-fluid rounded preview-img"
                     alt="Input 2"
                     data-bs-toggle="modal"
                     data-bs-target="#previewModal"
                     data-img="{{ $image2 }}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="result-card p-3 border rounded shadow-sm h-100">
                <h6 class="mb-3">Result</h6>
                <img src="{{ $result }}"
                     class="img-fluid rounded preview-img"
                     alt="Blended Result"
                     data-bs-toggle="modal"
                     data-bs-target="#previewModal"
                     data-img="{{ $result }}">
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h5 class="text-center mb-3">👀 Compare Base Image vs Result</h5>

        <div class="ba-wrapper mx-auto" id="ba1">
            <img src="{{ $image1 }}" alt="Base" class="ba-img">

            <div class="ba-overlay" id="ba1Overlay">
                <img src="{{ $result }}" alt="Result" class="ba-img">
            </div>

            <div class="ba-divider" id="ba1Divider" aria-hidden="true"></div>

            <input type="range" min="0" max="100" value="50" class="ba-range" id="ba1Range" aria-label="Compare slider">

            <div class="ba-label ba-label-left">Image 1 (Base)</div>
            <div class="ba-label ba-label-right">Result</div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4">
            🔁 Blend Another Pair
        </a>
    </div>
</div>

<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content bg-dark">
      <div class="modal-body text-center">
        <img id="modalImage" src="" class="img-fluid rounded shadow" alt="Preview">
      </div>
      <div class="modal-footer border-0 justify-content-center">
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
        <a id="downloadBtn" href="#" download class="btn btn-success">⬇️ Download</a>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<style>
  .result-card { background:#fafafa; transition:transform .2s, box-shadow .2s; }
  .result-card:hover { transform:translateY(-6px); box-shadow:0 8px 20px rgba(0,0,0,.15); }
  .preview-img { max-height:340px; object-fit:contain; cursor:pointer; transition:transform .2s; }
  .preview-img:hover { transform:scale(1.03); }
  #modalImage { max-height:85vh; object-fit:contain; }

  /* Before/After slider */
  .ba-wrapper{
    position:relative; max-width:900px; aspect-ratio:16/9;
    border-radius:12px; overflow:hidden;
    box-shadow:0 6px 18px rgba(0,0,0,.12); background:#000;
  }
  .ba-img{
    position:absolute; inset:0;
    width:100%; height:100%;
    object-fit:contain; /* keep full image; no cropping */
    background:#000;    /* fills letterbox */
    pointer-events:none;
  }
  /* Overlay stays full size; we clip it horizontally via clip-path */
  .ba-overlay{
    position:absolute; inset:0;
    clip-path: inset(0 50% 0 0); /* start at 50% visible */
    will-change: clip-path;
  }
  .ba-divider{
    position:absolute; top:0; bottom:0; left:50%;
    width:2px; background:rgba(255,255,255,.85);
    box-shadow:0 0 0 1px rgba(0,0,0,.15); z-index:3;
    transform:translateX(-1px);
  }
  .ba-range{
    position:absolute; bottom:12px; left:50%; transform:translateX(-50%);
    width:60%; z-index:4;
    -webkit-appearance:none; appearance:none; height:6px; background:#e9ecef; border-radius:999px; outline:none;
  }
  .ba-range::-webkit-slider-thumb{
    -webkit-appearance:none; width:18px; height:18px; border-radius:50%;
    background:#0d6efd; border:2px solid #fff; box-shadow:0 0 0 3px rgba(13,110,253,.25);
  }
  .ba-range::-moz-range-thumb{
    width:18px; height:18px; border-radius:50%;
    background:#0d6efd; border:2px solid #fff; box-shadow:0 0 0 3px rgba(13,110,253,.25);
  }
  .ba-label{
    position:absolute; top:10px; padding:6px 10px; border-radius:8px;
    font-weight:600; font-size:.9rem; background:rgba(0,0,0,.55); color:#fff; z-index:4;
  }
  .ba-label-left{ left:10px; }
  .ba-label-right{ right:10px; }
</style>

<script>
  (function(){
    const previewModal = document.getElementById('previewModal');
    if (!previewModal) return;
    const modalImg = document.getElementById('modalImage');
    const downloadBtn = document.getElementById('downloadBtn');

    previewModal.addEventListener('show.bs.modal', function (event) {
      const trigger = event.relatedTarget;
      if (!trigger) return;
      const src = trigger.getAttribute('data-img');
      modalImg.src = src;
      downloadBtn.href = src;
    });
  })();

  (function(){
    const wrap     = document.getElementById('ba1');
    if (!wrap) return;
    const overlay  = document.getElementById('ba1Overlay');
    const divider  = document.getElementById('ba1Divider');
    const range    = document.getElementById('ba1Range');

    function setPos(val){
      const pct = Math.max(0, Math.min(100, Number(val)));
      const rightCut = (100 - pct) + '%';
      overlay.style.clipPath = `inset(0 ${rightCut} 0 0)`;
      divider.style.left = pct + '%';
      range.value = pct;
    }

    range.addEventListener('input', (e)=> setPos(e.target.value));
    setPos(range.value || 50);
  })();
</script>
@endsection
