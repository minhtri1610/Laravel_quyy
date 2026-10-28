/**
 * Phiếu Quy Y (client/quyy/detail)
 *  - Thu nhỏ phiếu (bố cục cố định 720px) cho vừa màn hình nhỏ
 *  - Lưu ảnh phiếu (html2canvas, tải khi cần), in phiếu, tải mã QR, chia sẻ đường dẫn
 */
document.addEventListener('DOMContentLoaded', function () {
    const HTML2CANVAS_URL = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';

    const stage = document.getElementById('cert-stage');
    const scaleBox = document.getElementById('cert-scale');
    const certificate = document.getElementById('certificate');
    if (!stage || !scaleBox || !certificate) return;

    const fileName = certificate.dataset.fileName || 'PhieuQuyY';
    const shareUrl = certificate.dataset.shareUrl || window.location.href;
    const shareTitle = certificate.dataset.shareTitle || document.title;

    // ------------------------------------------------------------ thu nhỏ vừa màn hình
    function fitCertificate() {
        const style = getComputedStyle(stage);
        const available = stage.clientWidth - parseFloat(style.paddingLeft) - parseFloat(style.paddingRight);
        const width = certificate.offsetWidth;
        const scale = Math.min(1, available / width);

        if (scale < 1) {
            stage.classList.add('is-scaled');
            scaleBox.style.transform = `translateX(-50%) scale(${scale})`;
            stage.style.height = `${Math.ceil(certificate.offsetHeight * scale)}px`;
        } else {
            stage.classList.remove('is-scaled');
            scaleBox.style.transform = '';
            stage.style.height = '';
        }
    }

    let resizeFrame = null;
    window.addEventListener('resize', function () {
        cancelAnimationFrame(resizeFrame);
        resizeFrame = requestAnimationFrame(fitCertificate);
    });
    window.addEventListener('load', fitCertificate);
    document.fonts?.ready.then(fitCertificate);
    certificate.querySelectorAll('img').forEach(img => img.addEventListener('load', fitCertificate));
    fitCertificate();

    // ------------------------------------------------------------ thông báo
    function notify(type, message) {
        if (window.toastr) {
            window.toastr[type](message);
        } else {
            alert(message);
        }
    }

    function downloadUrl(url, name) {
        const link = document.createElement('a');
        link.href = url;
        link.download = name;
        document.body.appendChild(link);
        link.click();
        link.remove();
    }

    // ------------------------------------------------------------ lưu ảnh phiếu
    let html2canvasPromise = null;
    function loadHtml2canvas() {
        if (window.html2canvas) return Promise.resolve(window.html2canvas);
        html2canvasPromise ??= new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = HTML2CANVAS_URL;
            script.onload = () => resolve(window.html2canvas);
            script.onerror = () => {
                html2canvasPromise = null;
                reject(new Error('Không tải được html2canvas'));
            };
            document.head.appendChild(script);
        });
        return html2canvasPromise;
    }

    const downloadButton = document.getElementById('btn-download-cert');
    downloadButton?.addEventListener('click', async function () {
        const label = downloadButton.querySelector('.btn-text');
        const originalText = label.textContent;
        downloadButton.disabled = true;
        label.textContent = 'Đang tạo ảnh...';

        // Chụp bản sao không bị thu nhỏ, đặt ngoài màn hình
        const holder = document.createElement('div');
        holder.style.cssText = 'position:absolute;left:-10000px;top:0;width:720px;';
        const clone = certificate.cloneNode(true);
        clone.removeAttribute('id');
        clone.querySelectorAll('[id]').forEach(el => el.removeAttribute('id'));
        holder.appendChild(clone);
        document.body.appendChild(holder);

        try {
            const html2canvas = await loadHtml2canvas();
            await document.fonts?.ready;
            const canvas = await html2canvas(clone, {
                scale: 2,
                useCORS: true,
                backgroundColor: '#fffaf0',
                logging: false,
            });
            const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
            const url = URL.createObjectURL(blob);
            downloadUrl(url, `${fileName}.png`);
            setTimeout(() => URL.revokeObjectURL(url), 10000);
        } catch (error) {
            console.error(error);
            notify('error', 'Không lưu được ảnh phiếu. Vui lòng thử lại hoặc dùng nút "In phiếu".');
        } finally {
            holder.remove();
            downloadButton.disabled = false;
            label.textContent = originalText;
        }
    });

    // ------------------------------------------------------------ in phiếu
    document.getElementById('btn-print-cert')?.addEventListener('click', () => window.print());

    // ------------------------------------------------------------ tải mã QR
    document.getElementById('btn-download-qr')?.addEventListener('click', function () {
        const svgElement = certificate.querySelector('.cert-qr svg');
        if (!svgElement) {
            return notify('error', 'Không tìm thấy mã QR để tải!');
        }

        let svgData = new XMLSerializer().serializeToString(svgElement);
        if (!svgData.includes('xmlns="http://www.w3.org/2000/svg"')) {
            svgData = svgData.replace(/<svg/, '<svg xmlns="http://www.w3.org/2000/svg"');
        }

        const url = URL.createObjectURL(new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' }));
        const img = new Image();
        img.onload = function () {
            const canvas = document.createElement('canvas');
            canvas.width = 600;
            canvas.height = 600;
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = '#ffffff'; // nền trắng cho PNG
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 30, 30, 540, 540);
            URL.revokeObjectURL(url);
            downloadUrl(canvas.toDataURL('image/png'), `QR_${fileName}.png`);
        };
        img.onerror = () => notify('error', 'Không tạo được ảnh mã QR.');
        img.src = url;
    });

    // ------------------------------------------------------------ chia sẻ
    document.getElementById('btn-share-cert')?.addEventListener('click', async function () {
        if (navigator.share) {
            try {
                await navigator.share({ title: shareTitle, url: shareUrl });
            } catch (error) {
                if (error.name !== 'AbortError') console.error(error);
            }
            return;
        }

        try {
            await navigator.clipboard.writeText(shareUrl);
            notify('success', 'Đã sao chép đường dẫn phiếu.');
        } catch (error) {
            window.prompt('Sao chép đường dẫn phiếu:', shareUrl);
        }
    });
});
