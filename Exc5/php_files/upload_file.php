<!DOCTYPE html>
<html>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>อัปโหลดรูปภาพ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .drop-zone {
      border: 2px dashed #d1d5db;
      border-radius: 12px;
      padding: 30px;
      text-align: center;
      cursor: pointer;
      transition: background .15s, border-color .15s;
      background: linear-gradient(180deg, rgba(255,255,255,0.6), rgba(249,250,251,0.6));
    }
    .drop-zone.dragover { border-color: #0d6efd; background: rgba(13,110,253,0.04); }
    .preview-img { max-width: 100%; max-height: 240px; object-fit: contain; border-radius: 8px; }
    .meta { font-size: .9rem; color: #6b7280; }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="../">PHP-Learning</a>
    </div>
  </nav>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-7 col-md-9">
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <h5 class="card-title mb-0">อัปโหลดรูปภาพ</h5>
                <div class="meta">รองรับไฟล์ภาพสูงสุด 5 MB — jpg, png, gif</div>
              </div>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] !== ''): ?>
              <div id="serverMsg" class="alert alert-info"><?php echo htmlspecialchars($_GET['msg']); ?></div>
            <?php else: ?>
              <div id="serverMsg" style="display:none"></div>
            <?php endif; ?>

            <form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
              <div id="dropZone" class="drop-zone mb-3" tabindex="0">
                <div class="mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#0d6efd" class="bi bi-cloud-arrow-up" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 0a5.53 5.53 0 0 0-3.594 1.2A4.002 4.002 0 0 0 4 15h8a3 3 0 0 0 0-6 4 4 0 0 0-4-4c-.2 0-.397.012-.592.036A5.533 5.533 0 0 0 8 0z"/>
                    <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0L9 4.793V8.5a.5.5 0 0 1-1 0V4.793L7.646 4.146a.5.5 0 0 1 0-.707z"/>
                  </svg>
                </div>
                <div class="fw-semibold">ลากแล้วปล่อยที่นี่ หรือคลิกเพื่อเลือกไฟล์</div>
                <div class="meta">ไฟล์ที่เลือกจะถูกแสดงตัวอย่างก่อนอัปโหลด</div>
                <input type="file" id="fileToUpload" name="fileToUpload" accept="image/*" style="display:none">
              </div>

              <div id="preview" class="mb-3" style="display:none">
                <img id="previewImg" class="preview-img mb-2" src="#" alt="Preview">
                <div class="d-flex justify-content-between align-items-center">
                  <div id="fileInfo" class="meta"></div>
                  <button id="clearBtn" type="button" class="btn btn-sm btn-outline-secondary">ล้าง</button>
                </div>
              </div>

              <div class="mb-3" style="display:none" id="progressWrap">
                <div class="progress">
                  <div id="uploadProgress" class="progress-bar" role="progressbar" style="width:0%">0%</div>
                </div>
              </div>

              <div class="d-grid">
                <button id="submitBtn" type="submit" name="submit" class="btn btn-primary btn-lg">อัปโหลด</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function(){
      const dropZone = document.getElementById('dropZone');
      const fileInput = document.getElementById('fileToUpload');
      const preview = document.getElementById('preview');
      const previewImg = document.getElementById('previewImg');
      const fileInfo = document.getElementById('fileInfo');
      const clearBtn = document.getElementById('clearBtn');
      const uploadForm = document.getElementById('uploadForm');
      const progressWrap = document.getElementById('progressWrap');
      const progressBar = document.getElementById('uploadProgress');
      const submitBtn = document.getElementById('submitBtn');
      const serverMsg = document.getElementById('serverMsg');

      function bytesToSize(bytes) {
        const sizes = ['Bytes','KB','MB','GB','TB'];
        if (bytes === 0) return '0 Byte';
        const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)), 10);
        return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
      }

      function showFile(file) {
        const reader = new FileReader();
        reader.onload = function(e){
          previewImg.src = e.target.result;
          preview.style.display = '';
          fileInfo.textContent = file.name + ' • ' + bytesToSize(file.size);
        };
        reader.readAsDataURL(file);
      }

      function clearSelection() {
        fileInput.value = '';
        preview.style.display = 'none';
        progressWrap.style.display = 'none';
        progressBar.style.width = '0%';
        progressBar.textContent = '0%';
        serverMsg.style.display = 'none';
      }

      dropZone.addEventListener('click', ()=> fileInput.click());
      dropZone.addEventListener('keydown', (e)=> { if(e.key === 'Enter' || e.key === ' ') fileInput.click(); });

      ['dragenter','dragover'].forEach(ev => {
        dropZone.addEventListener(ev, (e)=>{ e.preventDefault(); e.stopPropagation(); dropZone.classList.add('dragover'); });
      });
      ['dragleave','drop'].forEach(ev => {
        dropZone.addEventListener(ev, (e)=>{ e.preventDefault(); e.stopPropagation(); dropZone.classList.remove('dragover'); });
      });

      dropZone.addEventListener('drop', (e)=>{
        const dt = e.dataTransfer;
        if(dt && dt.files && dt.files.length) {
          const file = dt.files[0];
          fileInput.files = dt.files;
          showFile(file);
        }
      });

      fileInput.addEventListener('change', (e)=>{
        if(fileInput.files && fileInput.files[0]) {
          showFile(fileInput.files[0]);
        }
      });

      clearBtn.addEventListener('click', clearSelection);

      uploadForm.addEventListener('submit', function(e){
        e.preventDefault();
        if(!fileInput.files || !fileInput.files[0]) {
          serverMsg.className = 'alert alert-warning';
          serverMsg.style.display = '';
          serverMsg.textContent = 'กรุณาเลือกไฟล์ก่อนอัปโหลด';
          return;
        }
        const file = fileInput.files[0];
        if(file.size > 5 * 1024 * 1024) {
          serverMsg.className = 'alert alert-danger';
          serverMsg.style.display = '';
          serverMsg.textContent = 'ไฟล์เกินขนาดสูงสุด 5 MB';
          return;
        }

        progressWrap.style.display = '';
        submitBtn.disabled = true;

        const formData = new FormData();
        formData.append('fileToUpload', file);
        formData.append('submit','Upload Image');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', uploadForm.action);
        xhr.upload.addEventListener('progress', function(e){
          if(e.lengthComputable){
            const percent = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percent + '%';
            progressBar.textContent = percent + '%';
          }
        });
        xhr.onload = function(){
          submitBtn.disabled = false;
          try {
            const text = xhr.responseText || 'อัปโหลดเสร็จแล้ว';
            serverMsg.className = 'alert alert-success';
            serverMsg.style.display = '';
            serverMsg.textContent = text;
          } catch(err) {
            serverMsg.className = 'alert alert-info';
            serverMsg.style.display = '';
            serverMsg.textContent = 'อัปโหลดเสร็จแล้ว';
          }
        };
        xhr.onerror = function(){
          submitBtn.disabled = false;
          serverMsg.className = 'alert alert-danger';
          serverMsg.style.display = '';
          serverMsg.textContent = 'เกิดข้อผิดพลาดขณะอัปโหลด';
        };
        xhr.send(formData);
      });

    })();
  </script>
</body>
</html>