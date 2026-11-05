<div class="page-wrapper">
    <div class="page-content">
        <style>
            .wa-shell {
                max-width: 1320px;
            }

            .wa-hero,
            .wa-connect {
                border: 1px solid #e2e8f0;
                border-radius: 28px;
                background: #fff;
                box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
                overflow: hidden;
            }

            .wa-hero-banner {
                background: linear-gradient(90deg, #0f766e 0%, #22c55e 100%);
                color: #fff;
                padding: 1rem 1.5rem;
                font-weight: 700;
            }

            .wa-hero-body {
                padding: 2.5rem;
                background: linear-gradient(180deg, #fafafa 0%, #f3f4f6 100%);
            }

            .wa-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.55rem 1rem;
                border-radius: 999px;
                background: #e8fff2;
                color: #15803d;
                font-weight: 700;
            }

            .wa-hero-title {
                font-size: 2.2rem;
                font-weight: 800;
                color: #1e293b;
                margin-top: 1rem;
            }

            .wa-hero-copy {
                color: #475569;
                max-width: 760px;
                margin-top: 0.85rem;
                font-size: 1.05rem;
            }

            .wa-feature-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 1rem;
                margin-top: 2rem;
            }

            .wa-feature {
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 20px;
                padding: 1.2rem;
            }

            .wa-feature-icon {
                width: 54px;
                height: 54px;
                border-radius: 16px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: #ecfdf5;
                color: #16a34a;
                font-size: 1.5rem;
                margin-bottom: 0.85rem;
            }

            .wa-hero-actions {
                margin-top: 2rem;
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
                align-items: center;
            }

            .wa-primary-btn {
                border: none;
                background: linear-gradient(90deg, #ff2454 0%, #ef4444 100%);
                color: #fff;
                border-radius: 999px;
                padding: 0.95rem 1.8rem;
                font-weight: 700;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .wa-note {
                color: #64748b;
                font-style: italic;
            }

            .wa-connect-grid {
                display: grid;
                grid-template-columns: 1.2fr 1fr;
            }

            .wa-connect-panel {
                padding: 2rem;
            }

            .wa-visual {
                min-height: 100%;
                background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
            }

            .wa-device-card {
                width: 100%;
                max-width: 380px;
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 28px;
                padding: 1.5rem;
                box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
            }

            .wa-device-top {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
            }

            .wa-chat-preview {
                border: 1px solid #dcfce7;
                background: #f0fdf4;
                border-radius: 18px;
                padding: 1rem;
                color: #166534;
                margin-top: 1rem;
            }

            .wa-qr {
                width: 260px;
                height: 260px;
                border-radius: 24px;
                background: #fff;
                border: 1px solid #e2e8f0;
                padding: 16px;
                margin: 1rem auto 1.25rem;
                box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
            }

            .wa-steps {
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 20px;
                padding: 1.25rem;
                margin-top: 1rem;
            }

            .wa-step {
                margin-bottom: 1rem;
            }

            .wa-step:last-child {
                margin-bottom: 0;
            }

            .wa-step-label {
                color: #64748b;
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            @media (max-width: 991.98px) {
                .wa-feature-grid,
                .wa-connect-grid {
                    grid-template-columns: 1fr;
                }

                .wa-hero-body,
                .wa-connect-panel,
                .wa-visual {
                    padding: 1.4rem;
                }

                .wa-hero-title {
                    font-size: 1.7rem;
                }
            }
        </style>

        <div class="row wa-shell g-4">
            <div class="col-12">
                <div class="wa-hero">
                    <div class="wa-hero-banner">Only your party chats will be shown in Bills Book</div>
                    <div class="wa-hero-body">
                        <div class="wa-badge"><i class='bx bxl-whatsapp'></i> WhatsApp in Bills Book</div>
                        <div class="wa-hero-title">Share bills, reminders, and payment follow-ups from your existing WhatsApp</div>
                        <div class="wa-hero-copy">Send invoices instantly to customers after every sale. Keep using your existing WhatsApp account and manage business communication from one place.</div>

                        <div class="wa-feature-grid">
                            <div class="wa-feature">
                                <div class="wa-feature-icon"><i class='bx bx-send'></i></div>
                                <h5>Send Bills in One Click</h5>
                                <p class="text-muted mb-0">Open WhatsApp directly after saving a sale and send the invoice link immediately.</p>
                            </div>
                            <div class="wa-feature">
                                <div class="wa-feature-icon"><i class='bx bx-mobile-alt'></i></div>
                                <h5>Works With Existing WhatsApp</h5>
                                <p class="text-muted mb-0">No separate business app setup is required for the share flow inside this project.</p>
                            </div>
                            <div class="wa-feature">
                                <div class="wa-feature-icon"><i class='bx bx-shield-quarter'></i></div>
                                <h5>Private and Secure</h5>
                                <p class="text-muted mb-0">Customer message drafts are generated locally from your saved invoice and customer data.</p>
                            </div>
                        </div>

                        <div class="wa-hero-actions">
                            <a href="#whatsapp-connect-section" class="wa-primary-btn">Link my WhatsApp</a>
                            <span class="wa-note">Secure, official style business sharing page for your sales workflow.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12" id="whatsapp-connect-section">
                <div class="wa-connect">
                    <div class="wa-hero-banner">WhatsApp Connect</div>
                    <div class="wa-connect-grid">
                        <div class="wa-visual">
                            <div class="wa-device-card">
                                <div class="wa-device-top">
                                    <strong><?= htmlspecialchars($businessName ?? 'Bills Book') ?></strong>
                                    <span class="badge bg-success-subtle text-success">Live Invoice Share</span>
                                </div>
                                <div class="text-muted">Seamlessly connect WhatsApp for smarter business communication.</div>
                                <div class="wa-chat-preview">
                                    <div class="fw-bold mb-2">Invoice Sent</div>
                                    <div>Hi Customer, your invoice is ready.</div>
                                    <div>Amount: Rs. 1,250.00</div>
                                    <div>Open invoice link from WhatsApp.</div>
                                </div>
                            </div>
                        </div>
                        <div class="wa-connect-panel text-center">
                            <h3 class="mb-2">Log in with WhatsApp to send bulk messages</h3>
                            <p class="text-muted mb-0">Safe. Secure. Private.</p>
                         <div class="wa-qr">
    <img id="waQrImg" style="width:100%;height:100%;object-fit:contain;">
</div>

<div id="waStatus" class="fw-semibold mb-3 text-success">
    Connecting...
</div>
<button id="logoutBtn" onclick="logoutWhatsApp()" 
        class="btn btn-danger mb-3" 
        style="display:none;">
    Disconnect WhatsApp
</button>
                            <div class="fw-semibold mb-3">Scan this QR code</div>

                            <div class="wa-steps text-start">
                                <div class="wa-step">
                                    <div class="wa-step-label">Step 1</div>
                                    <div>Open WhatsApp on your mobile, tap the menu icon, and open <strong>Linked devices</strong>.</div>
                                </div>
                                <div class="wa-step">
                                    <div class="wa-step-label">Step 2</div>
                                    <div>Use the <strong>Link a device</strong> option and point your camera at this QR code.</div>
                                </div>
                                <div class="wa-step">
                                    <div class="wa-step-label">Step 3</div>
                                    <div>After linking, use the sale page or sales list WhatsApp button to send invoice messages quickly.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
const USER_ID = <?= $this->admin['user_id']; ?>;

let waInterval = null;
let isLoggingOut = false; // 🔥 IMPORTANT

// ✅ START AUTO REFRESH
function startAutoRefresh(){
    if(waInterval) clearInterval(waInterval);
    waInterval = setInterval(loadWhatsAppQR, 3000);
}

// ✅ STOP AUTO REFRESH
function stopAutoRefresh(){
    if(waInterval){
        clearInterval(waInterval);
        waInterval = null;
    }
}

// ✅ LOAD STATUS + QR
function loadWhatsAppQR(){

    if(isLoggingOut) return; // 🔥 STOP DURING LOGOUT

    fetch("/whatsapp/status/" + USER_ID)
    .then(res => res.json())
    .then(statusData => {

        const qrImg = document.getElementById("waQrImg");
        const status = document.getElementById("waStatus");
        const logoutBtn = document.getElementById("logoutBtn");

if(statusData.connected === true){

            qrImg.style.display = "none";

            status.innerHTML = "✅ WhatsApp Connected";
            status.classList.remove("text-danger","text-warning");
            status.classList.add("text-success");

            logoutBtn.style.display = "inline-block";
        }
        else {

            fetch("/whatsapp/qr/" + USER_ID)
            .then(res => res.json())
            .then(data => {

                if(data.qr){

                    qrImg.src = data.qr;
                    qrImg.style.display = "block";

                    status.innerHTML = "📲 Scan QR to connect";

                } else {

                    qrImg.style.display = "none";
                    status.innerHTML = "⚠️ Waiting for QR...";
                }

                logoutBtn.style.display = "none";
            });
        }

    })
    .catch(() => {
        document.getElementById("waStatus").innerHTML = "❌ Server not responding";
    });
}

// ✅ LOGOUT
function logoutWhatsApp(){

    if(isLoggingOut) return; // 🔥 prevent multiple clicks
    isLoggingOut = true;

    if(!confirm("Disconnect WhatsApp?")){
        isLoggingOut = false;
        return;
    }

    stopAutoRefresh();

    fetch("/whatsapp/logout/" + USER_ID)
    .then(res => res.json())
    .then(data => {

        const status = document.getElementById("waStatus");
        const qrImg = document.getElementById("waQrImg");
        const logoutBtn = document.getElementById("logoutBtn");

        if(data.status){

            status.innerHTML = "❌ WhatsApp Disconnected";
            status.classList.remove("text-success");
            status.classList.add("text-danger");

            qrImg.style.display = "none";
            logoutBtn.style.display = "none";

            setTimeout(() => {
                isLoggingOut = false;
                loadWhatsAppQR();
                startAutoRefresh();
            }, 4000);

        } else {
            isLoggingOut = false;
            startAutoRefresh();
        }

    })
    .catch(() => {
        isLoggingOut = false;
        startAutoRefresh();
    });
}

// INIT
loadWhatsAppQR();
startAutoRefresh();
</script>