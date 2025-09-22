@extends('layout')

@section('content')
<div class="status-wrap mx-auto" style="max-width: 620px;">
  <div class="card p-4 shadow-sm ai-glow">
    <div class="d-flex align-items-center gap-3">
      <video autoplay loop muted playsinline style="width: 150px; height: 150px;">
        <source src="{{ asset('animations/ai.webm') }}" type="video/webm">
        Your browser does not support the video tag.
      </video>

      <div class="flex-grow-1">
        <h4 class="mb-1">AI is working…</h4>
        <div class="text-muted small" id="slogan">blending embeddings, aligning faces, mixing magic</div>
        <div class="mt-2 fw-semibold">Status: <span id="status-text">{{ $status }}</span></div>
      </div>
    </div>

    <div class="progress mt-3 ai-progress">
      <div id="progress-bar"
           class="progress-bar progress-bar-striped progress-bar-animated"
           role="progressbar"
           style="width: 0%"
           aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
        0%
      </div>
    </div>

    <div class="d-flex justify-content-between mt-3">
      <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
      <button id="pause-btn" class="btn btn-outline-primary btn-sm" type="button">Pause</button>
    </div>
  </div>

  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>

<style>
  .ai-glow { position: relative; border-radius: 16px; overflow: hidden; }
  .ai-glow::after {
    content: ""; position: absolute; inset: -2px;
    background: radial-gradient(1200px 400px at 10% -10%, rgba(13,110,253,.15), transparent 40%),
                radial-gradient(1200px 400px at 110% 110%, rgba(25,135,84,.15), transparent 40%);
    filter: blur(20px); z-index: -1;
  }
  .ai-progress { height: 18px; border-radius: 999px; overflow: hidden; }
  .ai-progress .progress-bar { font-weight: 600; }
  .status-wrap { position: relative; }
  .orb {
    position: absolute; width: 220px; height: 220px; border-radius: 50%;
    background: radial-gradient(circle at 30% 30%, rgba(13,110,253,.15), transparent 60%);
    filter: blur(8px); pointer-events: none; z-index: -2;
    animation: float 9s ease-in-out infinite;
  }
  .orb-1 { top: -40px; left: -40px; }
  .orb-2 { bottom: -60px; right: -30px; animation-delay: 1.5s; }
  .orb-3 { top: 40%; left: 70%; width: 140px; height: 140px; animation-delay: 3s; }
  @keyframes float {
    0%,100% { transform: translateY(0) }
    50% { transform: translateY(-12px) }
  }
</style>

<script>
  const statusText  = document.getElementById('status-text');
  const progressBar = document.getElementById('progress-bar');
  const pauseBtn    = document.getElementById('pause-btn');

  const checkUrl  = "{{ route('check.status', ['im1' => $im1, 'im2' => $im2]) }}";
  const resultUrl = "{{ route('result', ['im1' => $im1, 'im2' => $im2]) }}";

  let timer = null;
  let paused = false;
  let finished = false;

  function setProgress(pct) {
    const p = Math.max(0, Math.min(100, pct|0));
    progressBar.style.width = p + '%';
    progressBar.setAttribute('aria-valuenow', p);
    progressBar.textContent = p + '%';
    if (p < 40) {
      progressBar.classList.remove('bg-success'); progressBar.classList.add('bg-info');
    } else if (p < 80) {
      progressBar.classList.remove('bg-info'); progressBar.classList.add('bg-primary');
    } else {
      progressBar.classList.remove('bg-primary'); progressBar.classList.add('bg-success');
    }
  }

  function setIndeterminate() {
    progressBar.classList.add('progress-bar-striped', 'progress-bar-animated');
    progressBar.style.width = '100%';
    progressBar.textContent = 'Waiting…';
    progressBar.setAttribute('aria-valuenow', 100);
  }

  function stopIndeterminate() {
    progressBar.classList.add('progress-bar-striped');
    progressBar.classList.remove('progress-bar-animated');
  }

  function parsePercent(text) {
    const m = /Loading\s+(\d{1,3})%/i.exec(text);
    return m ? parseInt(m[1], 10) : null;
  }

  async function fetchStatus() {
    if (paused || finished) return;

    try {
      const res  = await fetch(checkUrl, { cache: 'no-store' });
      const body = (await res.text()).trim();
      statusText.textContent = body;

      if (body === 'Done') {
        finished = true;
        setProgress(100);
        setTimeout(() => window.location.href = resultUrl, 600);
        return;
      }

      if (body === 'Error') {
        progressBar.classList.remove('progress-bar-animated', 'progress-bar-striped', 'bg-success', 'bg-info', 'bg-primary');
        progressBar.classList.add('bg-danger');
        progressBar.style.width = '100%';
        progressBar.textContent = 'Error';
        finished = true;
        return;
      }

      if (body === 'Waiting' || body === 'File not Started') {
        setIndeterminate();
        return;
      }

      const pct = parsePercent(body);
      if (pct !== null) {
        stopIndeterminate();
        setProgress(pct);
      }
    } catch (err) {
      statusText.textContent = '⚠️ Network error.';
      console.error('Status fetch error:', err);
    }
  }

  function startPolling() {
    if (timer) clearInterval(timer);
    timer = setInterval(fetchStatus, 1500);
    fetchStatus();
  }

  pauseBtn.addEventListener('click', () => {
    paused = !paused;
    pauseBtn.textContent = paused ? 'Resume' : 'Pause';
    if (!paused) fetchStatus();
  });

  startPolling();
</script>
@endsection
