<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('f-circle.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="application-name" content="E-faktura | Matrixbau">
    <meta name="apple-mobile-web-app-title" content="E-faktura | Matrixbau">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#5746A3">
    <link href="{{ asset('files/vendor/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('files/css/style.css') }}?v={{ filemtime(public_path('files/css/style.css')) }}" rel="stylesheet">
    <link href="{{ asset('files/css/custom.css') }}?v={{ filemtime(public_path('files/css/custom.css')) }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            background: #f4f6fb;
            overflow: hidden;
        }

        .pdf-viewer-page {
            height: 100%;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            color: #1f2937;
        }

        .pdf-toolbar {
            min-height: 64px;
            padding: 10px 16px;
            padding-top: max(10px, env(safe-area-inset-top));
            background: #fff;
            border-bottom: 1px solid #eef1f7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 8px 24px rgba(31, 41, 55, 0.06);
            z-index: 2;
        }

        .pdf-title {
            min-width: 0;
        }

        .pdf-title strong {
            display: block;
            font-size: 15px;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pdf-title span {
            display: block;
            margin-top: 2px;
            color: #8a93a3;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pdf-actions {
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .pdf-actions .btn {
            width: 42px;
            height: 42px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px !important;
        }

        .pdf-frame-wrap {
            flex: 1 1 auto;
            min-height: 0;
            padding: 14px;
        }

        .pdf-frame {
            width: 100%;
            height: 100%;
            border: 0;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 30px rgba(31, 41, 55, 0.08);
        }

        .pdf-canvas-viewer {
            display: none;
            width: 100%;
            height: 100%;
            overflow: auto;
            -webkit-overflow-scrolling: touch;
        }

        .pdf-page {
            display: block;
            width: 100%;
            height: auto;
            margin: 0 auto 12px;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 6px 22px rgba(31, 41, 55, 0.12);
        }

        .pdf-loading {
            display: none;
            padding: 18px;
            text-align: center;
            color: #5f6b7a;
            font-size: 13px;
        }

        .pdf-fallback {
            display: none;
            padding: 16px;
            text-align: center;
            color: #5f6b7a;
        }

        .pdf-viewer-page.use-canvas .pdf-frame {
            display: none;
        }

        .pdf-viewer-page.use-canvas .pdf-canvas-viewer {
            display: block;
        }

        .pdf-viewer-page.use-canvas.loading .pdf-loading {
            display: block;
        }

        .pdf-viewer-page.use-canvas.loading .pdf-canvas-viewer {
            display: none;
        }

        .pdf-viewer-page.viewer-failed .pdf-fallback {
            display: block;
        }

        @media (max-width: 767px) {
            .pdf-toolbar {
                min-height: 58px;
                padding-left: 10px;
                padding-right: 10px;
                gap: 8px;
            }

            .pdf-title strong {
                font-size: 13px;
            }

            .pdf-title span {
                font-size: 11px;
            }

            .pdf-actions {
                gap: 6px;
            }

            .pdf-actions .btn {
                width: 40px;
                height: 40px;
            }

            .pdf-frame-wrap {
                padding: 8px;
                padding-bottom: max(8px, env(safe-area-inset-bottom));
            }

            .pdf-frame {
                border-radius: 6px;
            }
        }

        @media print {
            .pdf-toolbar {
                display: none;
            }

            .pdf-frame-wrap {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="pdf-viewer-page">
        <div class="pdf-toolbar">
            <div class="pdf-title">
                <strong>{{ $title }}</strong>
                <span>{{ $fileName }}</span>
            </div>
            <div class="pdf-actions">
                <a class="btn btn-primary light" href="{{ $downloadUrl }}" title="@lang('Download')" aria-label="@lang('Download')">
                    <i class="fa fa-download"></i>
                </a>
                <button class="btn btn-primary" id="sharePdf" type="button" title="@lang('Share')" aria-label="@lang('Share')">
                    <i class="fa fa-share-nodes"></i>
                </button>
            </div>
        </div>

        <div class="pdf-frame-wrap">
            <iframe class="pdf-frame" src="{{ $pdfUrl }}" title="{{ $title }}"></iframe>
            <div class="pdf-loading">@lang('PDF wird geladen...')</div>
            <div class="pdf-canvas-viewer" id="pdfCanvasViewer"></div>
            <div class="pdf-fallback">
                <a href="{{ $downloadUrl }}" class="btn btn-primary">@lang('Download')</a>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        const pdfUrl = @json($pdfUrl);
        const downloadUrl = @json($downloadUrl);
        const fileName = @json($fileName);
        const title = @json($title);
        const viewerPage = document.querySelector('.pdf-viewer-page');

        const isAppleMobile = /iPad|iPhone|iPod/.test(navigator.userAgent)
            || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
        const isStandalone = window.navigator.standalone === true
            || window.matchMedia('(display-mode: standalone)').matches;

        async function renderPdfForApplePwa() {
            if (!isAppleMobile) {
                return;
            }

            const canvasViewer = document.getElementById('pdfCanvasViewer');
            viewerPage.classList.add('use-canvas', 'loading');

            try {
                if (!window.pdfjsLib) {
                    throw new Error('PDF.js nije učitan.');
                }

                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                const pdf = await pdfjsLib.getDocument({
                    url: pdfUrl,
                    withCredentials: true
                }).promise;

                canvasViewer.innerHTML = '';

                for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                    const page = await pdf.getPage(pageNumber);
                    const wrapWidth = Math.max(canvasViewer.clientWidth || window.innerWidth, 320);
                    const initialViewport = page.getViewport({ scale: 1 });
                    const scale = Math.min((wrapWidth - 4) / initialViewport.width, 2);
                    const viewport = page.getViewport({ scale: scale });
                    const outputScale = Math.min(window.devicePixelRatio || 1, 2);

                    const canvas = document.createElement('canvas');
                    canvas.className = 'pdf-page';
                    canvas.width = Math.floor(viewport.width * outputScale);
                    canvas.height = Math.floor(viewport.height * outputScale);
                    canvas.style.width = Math.floor(viewport.width) + 'px';
                    canvas.style.height = Math.floor(viewport.height) + 'px';

                    const context = canvas.getContext('2d');
                    context.setTransform(outputScale, 0, 0, outputScale, 0, 0);

                    canvasViewer.appendChild(canvas);

                    await page.render({
                        canvasContext: context,
                        viewport: viewport
                    }).promise;
                }

                viewerPage.classList.remove('loading');
            } catch (error) {
                viewerPage.classList.remove('loading');
                viewerPage.classList.add('viewer-failed');
            }
        }

        document.getElementById('sharePdf').addEventListener('click', async function () {
            try {
                if (navigator.share) {
                    if (navigator.canShare && window.File) {
                        try {
                            const response = await fetch(pdfUrl, { credentials: 'same-origin' });
                            const blob = await response.blob();
                            const file = new File([blob], fileName, { type: 'application/pdf' });

                            if (navigator.canShare({ files: [file] })) {
                                await navigator.share({ title: title, files: [file] });
                                return;
                            }
                        } catch (fileShareError) {}
                    }

                    await navigator.share({ title: title, url: downloadUrl });
                    return;
                }

                await navigator.clipboard.writeText(downloadUrl);
                alert(@json(__('Link je kopiran.')));
            } catch (error) {}
        });

        renderPdfForApplePwa();
    </script>
</body>
</html>
