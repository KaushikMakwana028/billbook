<div class="page-wrapper">
    <div class="page-content">
        <style>
            /* ── Base ── */
            .sales-shell {
                max-width: 1280px;
                margin: 0 auto;
            }

            .sales-card {
                border: none;
                border-radius: 24px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04),
                    0 20px 50px -12px rgba(15, 23, 42, 0.1);
                background: #ffffff;
                overflow: hidden;
            }

            .sales-card .card-body {
                padding: 2rem 2.25rem !important;
            }

            /* ── Header ── */
            .sales-header {
                position: relative;
                padding-bottom: 1.5rem;
                margin-bottom: 1.5rem;
                border-bottom: 1px solid #f1f5f9;
            }

            .sales-kicker {
                display: inline-flex;
                align-items: center;
                gap: 0.45rem;
                padding: 0.4rem 0.9rem;
                border-radius: 999px;
                background: linear-gradient(135deg, #eff6ff 0%, #e0f2fe 100%);
                color: #2563eb;
                font-size: 0.8rem;
                font-weight: 700;
                letter-spacing: 0.03em;
                text-transform: uppercase;
            }

            .sales-header h3 {
                font-size: 1.55rem;
                font-weight: 800;
                color: #0f172a;
                letter-spacing: -0.02em;
            }

            .sales-header p {
                color: #64748b;
                font-size: 0.92rem;
            }

            .sales-header p code {
                background: #f1f5f9;
                padding: 0.15em 0.45em;
                border-radius: 6px;
                font-size: 0.85em;
                color: #6366f1;
                font-weight: 600;
            }

            .btn-create-sale {
                background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
                border: none;
                color: #fff;
                padding: 0.6rem 1.5rem;
                border-radius: 14px;
                font-weight: 600;
                font-size: 0.92rem;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                transition: all 0.25s ease;
                box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
            }

            .btn-create-sale:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 22px rgba(37, 99, 235, 0.35);
                background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
                color: #fff;
            }

            /* ── Alerts ── */
            .sales-alert {
                border: none;
                border-radius: 16px;
                padding: 0.85rem 1.15rem;
                font-size: 0.9rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 0.6rem;
            }

            .sales-alert-success {
                background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
                color: #15803d;
                border-left: 4px solid #22c55e;
            }

            .sales-alert-danger {
                background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
                color: #dc2626;
                border-left: 4px solid #ef4444;
            }

            /* ── Post-save banner ── */
            .sales-post-save {
                border: 1px solid #bfdbfe;
                background: linear-gradient(135deg, #eff6ff 0%, #ffffff 50%, #f0fdf4 100%);
                border-radius: 18px;
                padding: 1.15rem 1.35rem;
                margin-bottom: 1.25rem;
                position: relative;
                overflow: hidden;
            }

            .sales-post-save::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 4px;
                height: 100%;
                background: linear-gradient(180deg, #2563eb, #22c55e);
                border-radius: 4px 0 0 4px;
            }

            .sales-post-save h5 {
                font-weight: 700;
                font-size: 1rem;
                color: #1e293b;
            }

            .sales-post-save p {
                font-size: 0.88rem;
            }

            /* ── Filter card ── */
            .sales-filter-card {
                border: 1px solid #e2e8f0;
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                border-radius: 18px;
                padding: 1rem 1.25rem;
                margin-bottom: 1.25rem;
            }

            .sales-filter-card .form-label {
                font-size: 0.78rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                color: #475569;
                margin-bottom: 0.3rem;
            }

            .sales-filter-card .form-control {
                border-radius: 12px;
                border: 1px solid #cbd5e1;
                padding: 0.5rem 0.85rem;
                font-size: 0.88rem;
                transition: all 0.2s ease;
                background: #fff;
            }

            .sales-filter-card .form-control:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
            }

            .btn-filter {
                background: #2563eb;
                color: #fff;
                border: none;
                border-radius: 12px;
                padding: 0.5rem 1.25rem;
                font-weight: 600;
                font-size: 0.88rem;
                transition: all 0.2s ease;
            }

            .btn-filter:hover {
                background: #1d4ed8;
                color: #fff;
                transform: translateY(-1px);
            }

            .btn-filter-reset {
                background: transparent;
                color: #64748b;
                border: 1px solid #cbd5e1;
                border-radius: 12px;
                padding: 0.5rem 1.25rem;
                font-weight: 600;
                font-size: 0.88rem;
                transition: all 0.2s ease;
            }

            .btn-filter-reset:hover {
                background: #f1f5f9;
                color: #334155;
                border-color: #94a3b8;
            }

            .btn-export {
                background: linear-gradient(135deg, #059669 0%, #10b981 100%);
                color: #fff;
                border: none;
                border-radius: 12px;
                padding: 0.5rem 1.25rem;
                font-weight: 600;
                font-size: 0.88rem;
                transition: all 0.2s ease;
                box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
            }

            .btn-export:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
                color: #fff;
            }

            /* ── Table ── */
            .sales-table-wrap {
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                overflow: hidden;
            }

            .sales-table {
                margin-bottom: 0;
            }

            .sales-table thead {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            }

            .sales-table thead th {
                font-weight: 700;
                font-size: 0.76rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #475569;
                padding: 0.85rem 1rem !important;
                border-bottom: 2px solid #e2e8f0;
                white-space: nowrap;
            }

            .sales-table tbody td {
                padding: 0.75rem 1rem !important;
                font-size: 0.88rem;
                color: #334155;
                border-bottom: 1px solid #f1f5f9;
                vertical-align: middle;
            }

            .sales-table tbody tr {
                transition: all 0.15s ease;
            }

            .sales-table tbody tr:hover {
                background: #f8fafc;
            }

            .sales-table tbody tr:last-child td {
                border-bottom: none;
            }

            /* Row number badge */
            .row-num {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 28px;
                height: 28px;
                border-radius: 10px;
                background: #f1f5f9;
                color: #64748b;
                font-weight: 700;
                font-size: 0.78rem;
            }

            /* Customer cell */
            .customer-name {
                font-weight: 600;
                color: #1e293b;
            }

            .customer-walkin {
                color: #94a3b8;
                font-style: italic;
                font-weight: 500;
            }

            /* Phone cell */
            .phone-cell {
                font-family: 'SF Mono', 'Fira Code', monospace;
                font-size: 0.84rem;
                color: #475569;
            }

            /* Address cell */
            .address-cell {
                max-width: 180px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                color: #64748b;
                font-size: 0.85rem;
            }

            /* Total badge */
            .sales-total-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.2rem;
                padding: 0.3rem 0.7rem;
                border-radius: 10px;
                background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
                color: #15803d;
                font-weight: 700;
                font-size: 0.85rem;
                white-space: nowrap;
            }

            /* Action buttons */
            .btn-action-download {
                display: inline-flex;
                align-items: center;
                gap: 0.3rem;
                padding: 0.35rem 0.75rem;
                border-radius: 10px;
                font-size: 0.8rem;
                font-weight: 600;
                border: 1px solid #bfdbfe;
                background: #eff6ff;
                color: #2563eb;
                transition: all 0.2s ease;
                text-decoration: none;
            }

            .btn-action-download:hover {
                background: #2563eb;
                color: #fff;
                border-color: #2563eb;
                transform: translateY(-1px);
                box-shadow: 0 3px 10px rgba(37, 99, 235, 0.25);
            }

            .btn-action-whatsapp {
                display: inline-flex;
                align-items: center;
                gap: 0.3rem;
                padding: 0.35rem 0.75rem;
                border-radius: 10px;
                font-size: 0.8rem;
                font-weight: 600;
                border: 1px solid #bbf7d0;
                background: #f0fdf4;
                color: #16a34a;
                transition: all 0.2s ease;
                text-decoration: none;
            }

            .btn-action-whatsapp:hover {
                background: #16a34a;
                color: #fff;
                border-color: #16a34a;
                transform: translateY(-1px);
                box-shadow: 0 3px 10px rgba(22, 163, 74, 0.25);
            }

            /* Empty state */
            .sales-empty-state {
                padding: 3rem 2rem;
                text-align: center;
            }

            .sales-empty-icon {
                width: 72px;
                height: 72px;
                border-radius: 20px;
                background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1rem;
            }

            .sales-empty-icon i {
                font-size: 1.8rem;
                color: #94a3b8;
            }

            .sales-empty-state h6 {
                font-weight: 700;
                color: #475569;
                margin-bottom: 0.3rem;
            }

            .sales-empty-state p {
                color: #94a3b8;
                font-size: 0.9rem;
            }

            /* ── Stats row (top of table) ── */
            .sales-stats {
                display: flex;
                gap: 0.75rem;
                margin-bottom: 1.25rem;
                flex-wrap: wrap;
            }

            .sales-stat-chip {
                display: inline-flex;
                align-items: center;
                gap: 0.45rem;
                padding: 0.45rem 0.95rem;
                border-radius: 12px;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                font-size: 0.84rem;
                color: #475569;
                font-weight: 500;
            }

            .sales-stat-chip strong {
                color: #0f172a;
                font-weight: 700;
            }

            .stat-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                display: inline-block;
            }

            .stat-dot-blue {
                background: #3b82f6;
            }

            .stat-dot-green {
                background: #22c55e;
            }

            /* ── Post-save action buttons ── */
            .btn-post-download {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                padding: 0.5rem 1.1rem;
                border-radius: 12px;
                font-size: 0.85rem;
                font-weight: 600;
                border: 1px solid #bfdbfe;
                background: #fff;
                color: #2563eb;
                transition: all 0.2s ease;
                text-decoration: none;
            }

            .btn-post-download:hover {
                background: #2563eb;
                color: #fff;
                border-color: #2563eb;
            }

            .btn-post-whatsapp {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                padding: 0.5rem 1.1rem;
                border-radius: 12px;
                font-size: 0.85rem;
                font-weight: 600;
                border: 1px solid #bbf7d0;
                background: #fff;
                color: #16a34a;
                transition: all 0.2s ease;
                text-decoration: none;
            }

            .btn-post-whatsapp:hover {
                background: #16a34a;
                color: #fff;
                border-color: #16a34a;
            }

            /* ── Premium Pagination UI ── */
            .pagination {
                gap: 6px;
                padding: 6px;
                background: #f8fafc;
                border-radius: 14px;
                border: 1px solid #e2e8f0;
            }

            .pagination .page-item {
                list-style: none;
            }

            .pagination .page-link {
                border: none;
                min-width: 38px;
                height: 38px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 10px;
                font-size: 0.85rem;
                font-weight: 600;
                color: #475569;
                background: transparent;
                transition: all 0.2s ease;
            }

            /* Hover */
            .pagination .page-link:hover {
                background: #e0f2fe;
                color: #2563eb;
                transform: translateY(-1px);
            }

            /* Active */
            .pagination .page-item.active .page-link {
                background: linear-gradient(135deg, #2563eb, #3b82f6);
                color: #fff;
                box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
            }

            /* Disabled */
            .pagination .page-item.disabled .page-link {
                opacity: 0.4;
                pointer-events: none;
            }

            /* Dots */
            .pagination .dots {
                display: flex;
                align-items: center;
                padding: 0 6px;
                color: #94a3b8;
                font-weight: 700;
            }

            .sales-action-icons {
                display: inline-flex;
                gap: 0.4rem;
                align-items: center;
            }

            .act-icon {
                width: 34px;
                height: 34px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 10px;
                border: 1px solid transparent;
                font-size: 0.92rem;
                cursor: pointer;
                text-decoration: none;
                transition: all 0.15s ease;
            }

            .act-print {
                background: #eff6ff;
                color: #2563eb;
                border-color: #dbeafe;
            }

            .act-print:hover {
                background: #dbeafe;
                color: #1d4ed8;
            }

            .act-edit {
                background: #fefce8;
                color: #ca8a04;
                border-color: #fef08a;
            }

            .act-edit:hover {
                background: #fef08a;
                color: #a16207;
            }

            .act-delete {
                background: #fef2f2;
                color: #dc2626;
                border-color: #fecaca;
            }

            .act-delete:hover {
                background: #fecaca;
                color: #b91c1c;
            }

            /* print button in post-save banner */
            .btn-post-print {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                padding: 0.5rem 1rem;
                border-radius: 10px;
                background: #eff6ff;
                color: #2563eb;
                border: 1px solid #dbeafe;
                font-size: 0.85rem;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.15s;
            }

            .btn-post-print:hover {
                background: #dbeafe;
                color: #1d4ed8;
            }

            /* ═══════════════════════════════════════════
               RESPONSIVE — TABLETS (≤991px)
            ═══════════════════════════════════════════ */
            @media (max-width: 991.98px) {
                .sales-card .card-body {
                    padding: 1.5rem 1.5rem !important;
                }

                .sales-header h3 {
                    font-size: 1.35rem;
                }

                .address-cell {
                    max-width: 120px;
                }
            }

            /* ═══════════════════════════════════════════
               RESPONSIVE — SMALL TABLETS & PHONES (≤767px)
            ═══════════════════════════════════════════ */
            @media (max-width: 767.98px) {
                .sales-card {
                    border-radius: 16px;
                }

                .sales-card .card-body {
                    padding: 1.15rem 1rem !important;
                }

                /* Header */
                .sales-header {
                    padding-bottom: 1rem;
                    margin-bottom: 1rem;
                }

                .sales-header h3 {
                    font-size: 1.2rem;
                }

                .sales-header p {
                    font-size: 0.84rem;
                }

                .btn-create-sale {
                    width: 100%;
                    justify-content: center;
                    padding: 0.55rem 1.2rem;
                    font-size: 0.88rem;
                    border-radius: 12px;
                }

                /* Alerts */
                .sales-alert {
                    border-radius: 12px;
                    padding: 0.7rem 0.9rem;
                    font-size: 0.84rem;
                }

                /* Post-save banner */
                .sales-post-save {
                    border-radius: 14px;
                    padding: 0.9rem 1rem 0.9rem 1.15rem;
                }

                .sales-post-save h5 {
                    font-size: 0.92rem;
                }

                .sales-post-save p {
                    font-size: 0.82rem;
                }

                .btn-post-print,
                .btn-post-whatsapp,
                .btn-post-download {
                    padding: 0.42rem 0.85rem;
                    font-size: 0.8rem;
                    border-radius: 10px;
                }

                /* Filter card */
                .sales-filter-card {
                    border-radius: 14px;
                    padding: 0.85rem 1rem;
                }

                .sales-filter-card .form-label {
                    font-size: 0.72rem;
                }

                .sales-filter-card .form-control {
                    font-size: 0.84rem;
                    border-radius: 10px;
                }

                .sales-filter-card .row>div {
                    margin-bottom: 0.5rem;
                }

                .btn-filter,
                .btn-filter-reset,
                .btn-export {
                    padding: 0.45rem 1rem;
                    font-size: 0.82rem;
                    border-radius: 10px;
                    flex: 1;
                    justify-content: center;
                    text-align: center;
                }

                /* ── TABLE → CARD LAYOUT ── */
                .sales-table-wrap {
                    border: none;
                    border-radius: 0;
                    overflow: visible;
                    background: transparent;
                }

                .sales-table {
                    border: none;
                    background: transparent;
                }

                .sales-table thead {
                    display: none;
                }

                .sales-table tbody,
                .sales-table tbody tr,
                .sales-table tbody td {
                    display: block;
                    width: 100%;
                }

                .sales-table tbody tr {
                    background: #fff;
                    border: 1px solid #e9eff6;
                    border-radius: 14px;
                    margin-bottom: 0.65rem;
                    padding: 0.85rem 0.9rem;
                    position: relative;
                    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
                    transition: box-shadow 0.15s ease;
                }

                .sales-table tbody tr:hover {
                    background: #fff;
                    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
                }

                .sales-table tbody tr:last-child {
                    margin-bottom: 0;
                }

                .sales-table tbody td {
                    padding: 0.2rem 0 !important;
                    border-bottom: none !important;
                    font-size: 0.85rem;
                    display: flex;
                    align-items: center;
                    gap: 0.4rem;
                }

                /* Row number — top-left badge */
                .sales-table tbody td:nth-child(1) {
                    position: absolute;
                    top: 0.7rem;
                    left: 0.75rem;
                    width: auto;
                }

                .row-num {
                    width: 24px;
                    height: 24px;
                    font-size: 0.7rem;
                    border-radius: 8px;
                }

                /* Customer name — main title */
                .sales-table tbody td:nth-child(2) {
                    padding-top: 0.1rem !important;
                    padding-left: 2.5rem !important;
                    font-size: 0.92rem;
                }

                .customer-name {
                    font-size: 0.92rem;
                }

                .customer-walkin {
                    font-size: 0.85rem;
                }

                /* Phone */
                .sales-table tbody td:nth-child(3)::before {
                    content: 'Phone:';
                    font-size: 0.68rem;
                    font-weight: 700;
                    color: #94a3b8;
                    text-transform: uppercase;
                    letter-spacing: 0.03em;
                    min-width: 52px;
                }

                .phone-cell {
                    font-size: 0.82rem;
                }

                /* Address */
                .sales-table tbody td:nth-child(4)::before {
                    content: 'Address:';
                    font-size: 0.68rem;
                    font-weight: 700;
                    color: #94a3b8;
                    text-transform: uppercase;
                    letter-spacing: 0.03em;
                    min-width: 52px;
                    flex-shrink: 0;
                }

                .address-cell {
                    max-width: none;
                    white-space: normal;
                    overflow: visible;
                    text-overflow: unset;
                    font-size: 0.82rem;
                }

                /* Total */
                .sales-table tbody td:nth-child(5) {
                    padding-top: 0.45rem !important;
                    padding-bottom: 0.35rem !important;
                    border-top: 1px dashed #f1f5f9 !important;
                    margin-top: 0.3rem;
                    justify-content: space-between;
                }

                .sales-table tbody td:nth-child(5)::before {
                    content: 'Total';
                    font-size: 0.68rem;
                    font-weight: 700;
                    color: #94a3b8;
                    text-transform: uppercase;
                    letter-spacing: 0.03em;
                }

                .sales-total-badge {
                    font-size: 0.88rem;
                    padding: 0.25rem 0.65rem;
                }

                /* Actions */
                .sales-table tbody td:nth-child(6) {
                    justify-content: flex-end;
                    padding-top: 0.35rem !important;
                    border-top: 1px dashed #f1f5f9 !important;
                }

                .sales-table tbody td:nth-child(6)::before {
                    content: '';
                }

                .sales-action-icons {
                    gap: 0.35rem;
                }

                .act-icon {
                    width: 36px;
                    height: 36px;
                    border-radius: 10px;
                    font-size: 0.88rem;
                }

                /* Empty state */
                .sales-empty-state {
                    padding: 2rem 1.25rem;
                }

                .sales-empty-icon {
                    width: 60px;
                    height: 60px;
                    border-radius: 16px;
                }

                .sales-empty-icon i {
                    font-size: 1.5rem;
                }

                /* Stats */
                .sales-stats {
                    gap: 0.5rem;
                    margin-bottom: 1rem;
                }

                .sales-stat-chip {
                    flex: 1 1 calc(50% - 0.25rem);
                    min-width: 0;
                    padding: 0.4rem 0.7rem;
                    font-size: 0.78rem;
                    border-radius: 10px;
                    justify-content: center;
                    text-align: center;
                }

                /* Pagination */
                .pagination {
                    gap: 4px;
                    padding: 4px;
                    border-radius: 12px;
                    flex-wrap: wrap;
                    justify-content: center;
                }

                .pagination .page-link {
                    min-width: 34px;
                    height: 34px;
                    font-size: 0.8rem;
                    border-radius: 8px;
                }
            }

            /* ═══════════════════════════════════════════
               RESPONSIVE — SMALL PHONES (≤480px)
            ═══════════════════════════════════════════ */
            @media (max-width: 480px) {
                .sales-card {
                    border-radius: 0;
                    box-shadow: none;
                    border-left: none;
                    border-right: none;
                }

                .sales-card .card-body {
                    padding: 0.9rem 0.75rem !important;
                }

                /* Header */
                .sales-kicker {
                    font-size: 0.72rem;
                    padding: 0.3rem 0.7rem;
                }

                .sales-header h3 {
                    font-size: 1.1rem;
                }

                .sales-header p {
                    font-size: 0.8rem;
                }

                .sales-header p code {
                    font-size: 0.78em;
                }

                .btn-create-sale {
                    font-size: 0.84rem;
                    padding: 0.5rem 1rem;
                    border-radius: 10px;
                }

                /* Post-save banner */
                .sales-post-save {
                    border-radius: 12px;
                    padding: 0.75rem 0.85rem 0.75rem 1rem;
                }

                .sales-post-save h5 {
                    font-size: 0.85rem;
                }

                .sales-post-save p {
                    font-size: 0.78rem;
                }

                .btn-post-print,
                .btn-post-whatsapp,
                .btn-post-download {
                    padding: 0.38rem 0.7rem;
                    font-size: 0.76rem;
                    flex: 1;
                    justify-content: center;
                }

                /* Filter card */
                .sales-filter-card {
                    border-radius: 12px;
                    padding: 0.75rem 0.85rem;
                }

                .sales-filter-card .form-control {
                    font-size: 0.82rem;
                    padding: 0.45rem 0.7rem;
                }

                .btn-filter,
                .btn-filter-reset,
                .btn-export {
                    padding: 0.42rem 0.75rem;
                    font-size: 0.78rem;
                }

                /* Table cards */
                .sales-table tbody tr {
                    border-radius: 12px;
                    padding: 0.75rem 0.75rem;
                    margin-bottom: 0.5rem;
                }

                .sales-table tbody td {
                    font-size: 0.82rem;
                }

                .sales-table tbody td:nth-child(2) {
                    padding-left: 2.2rem !important;
                }

                .customer-name {
                    font-size: 0.88rem;
                }

                .sales-table tbody td:nth-child(3)::before,
                .sales-table tbody td:nth-child(4)::before,
                .sales-table tbody td:nth-child(5)::before {
                    font-size: 0.64rem;
                    min-width: 48px;
                }

                .phone-cell {
                    font-size: 0.78rem;
                }

                .address-cell {
                    font-size: 0.78rem;
                }

                .sales-total-badge {
                    font-size: 0.84rem;
                }

                .act-icon {
                    width: 32px;
                    height: 32px;
                    font-size: 0.82rem;
                    border-radius: 8px;
                }

                /* Stats */
                .sales-stat-chip {
                    flex: 1 1 100%;
                    font-size: 0.76rem;
                    padding: 0.38rem 0.6rem;
                }

                /* Pagination */
                .pagination {
                    gap: 3px;
                    padding: 3px;
                    border-radius: 10px;
                }

                .pagination .page-link {
                    min-width: 30px;
                    height: 30px;
                    font-size: 0.75rem;
                    border-radius: 7px;
                }

                /* Empty state */
                .sales-empty-state {
                    padding: 1.5rem 1rem;
                }

                .sales-empty-icon {
                    width: 52px;
                    height: 52px;
                    border-radius: 14px;
                }

                .sales-empty-icon i {
                    font-size: 1.3rem;
                }

                .sales-empty-state h6 {
                    font-size: 0.88rem;
                }

                .sales-empty-state p {
                    font-size: 0.82rem;
                }
            }

            /* ═══════════════════════════════════════════
               RESPONSIVE — ULTRA-SMALL (≤360px)
            ═══════════════════════════════════════════ */
            @media (max-width: 360px) {
                .sales-card .card-body {
                    padding: 0.75rem 0.6rem !important;
                }

                .sales-header h3 {
                    font-size: 1rem;
                }

                .sales-kicker {
                    font-size: 0.68rem;
                    padding: 0.25rem 0.6rem;
                }

                .btn-create-sale {
                    font-size: 0.8rem;
                    padding: 0.45rem 0.85rem;
                }

                .sales-post-save h5 {
                    font-size: 0.8rem;
                }

                .sales-table tbody tr {
                    padding: 0.65rem 0.6rem;
                    border-radius: 10px;
                }

                .sales-table tbody td:nth-child(3)::before,
                .sales-table tbody td:nth-child(4)::before,
                .sales-table tbody td:nth-child(5)::before {
                    min-width: 42px;
                    font-size: 0.6rem;
                }

                .customer-name {
                    font-size: 0.84rem;
                }

                .act-icon {
                    width: 30px;
                    height: 30px;
                    font-size: 0.78rem;
                    border-radius: 7px;
                }

                .pagination .page-link {
                    min-width: 28px;
                    height: 28px;
                    font-size: 0.7rem;
                }
            }
        </style>

        <div class="row sales-shell">
            <div class="col-12">
                <div class="card sales-card">
                    <div class="card-body">

                        <!-- ── Header ── -->
                        <div class="sales-header d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div>
                                <div class="sales-kicker mb-2">
                                    <i class='bx bx-receipt'></i>
                                    <span>Sales</span>
                                </div>
                                <h3 class="mb-1">Saved Sales &amp; Invoices</h3>
                                <p class="mb-0">Every sale automatically generates an <code>Invoice</code>.</p>
                            </div>
                            <a href="<?= base_url('sales/add') ?>" class="btn-create-sale">
                                <i class='bx bx-plus'></i> Create Sale
                            </a>
                        </div>

                        <!-- ── Flash messages ── -->
                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="sales-alert sales-alert-success mb-3">
                                <i class='bx bx-check-circle' style="font-size:1.2rem"></i>
                                <?= $this->session->flashdata('success') ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="sales-alert sales-alert-danger mb-3">
                                <i class='bx bx-error-circle' style="font-size:1.2rem"></i>
                                <?= $this->session->flashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <!-- ── WhatsApp auto-open ── -->
                        <?php $latestSaleActions = $this->session->flashdata('latest_sale_actions'); ?>
                        <?php if (!empty($latestSaleActions['whatsapp_url'])): ?>
                            <script>
                                window.onload = function() {
                                    window.open("<?= $latestSaleActions['whatsapp_url'] ?>", "_blank");
                                };
                            </script>
                        <?php endif; ?>

                        <!-- ── Post-save banner (updated buttons) ── -->
                        <?php if (!empty($latestSaleActions)): ?>
                            <div class="sales-post-save">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    <div>
                                        <h5><i class='bx bx-check-shield' style="color:#22c55e;margin-right:0.3rem"></i> Latest Sale Ready</h5>
                                        <p class="text-muted mb-0">
                                            Invoice <strong><?= htmlspecialchars($latestSaleActions['invoice_number'] ?? '') ?></strong>
                                            for <strong><?= htmlspecialchars(($latestSaleActions['customer_name'] ?? '') !== '' ? $latestSaleActions['customer_name'] : 'Customer') ?></strong>
                                            is saved.
                                        </p>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="<?= base_url('sales/print_invoice/' . (int)($latestSaleActions['sale_id'] ?? 0)) ?>"
                                            target="_blank"
                                            class="btn-post-print"
                                            title="Print Invoice">
                                            <i class="fa-solid fa-print"></i> Print
                                        </a>
                                        <?php if (!empty($latestSaleActions['whatsapp_url'])): ?>
                                            <a href="<?= htmlspecialchars($latestSaleActions['whatsapp_url']) ?>"
                                                target="_blank"
                                                class="btn-post-whatsapp">
                                                <i class="fa-brands fa-whatsapp"></i> Send WhatsApp
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- ── Filter bar ── -->
                        <form method="get" action="<?= base_url('sales') ?>" class="sales-filter-card">
                            <div class="row g-2 align-items-end">
                                <div class="col-lg-4">
                                    <label class="form-label">Search</label>
                                    <input type="text" name="search"
                                        value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                                        class="form-control"
                                        placeholder="Invoice, customer, phone, email…">
                                </div>
                                <div class="col-lg-2">
                                    <label class="form-label">From</label>
                                    <input type="date" name="date_from"
                                        value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>"
                                        class="form-control">
                                </div>
                                <div class="col-lg-2">
                                    <label class="form-label">To</label>
                                    <input type="date" name="date_to"
                                        value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>"
                                        class="form-control">
                                </div>
                                <div class="col-lg-4 d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn-filter">
                                        <i class='bx bx-search' style="font-size:1rem;vertical-align:middle"></i> Filter
                                    </button>
                                    <a href="<?= base_url('sales') ?>" class="btn-filter-reset">
                                        Reset
                                    </a>
                                    <a href="<?= base_url('sales/export?search=' . urlencode($filters['search'] ?? '') . '&date_from=' . urlencode($filters['date_from'] ?? '') . '&date_to=' . urlencode($filters['date_to'] ?? '')) ?>"
                                        class="btn-export">
                                        <i class="fa fa-file-excel"></i> Export
                                    </a>
                                </div>
                            </div>
                        </form>


                        <!-- ── Table ── -->

                        <div class="sales-table-wrap">
                            <table class="table sales-table">
                                <thead>
                                    <tr>
                                        <th style="width:50px">#</th>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Total</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="salesTableBody">
                                    <?php if (!empty($sales)): ?>
                                        <?php foreach ($sales as $index => $sale): ?>
                                            <tr>
                                                <td><span class="row-num"><?= $index + 1 ?></span></td>

                                                <td>
                                                    <?php
                                                    $name = trim($sale['customer_name'] ?? '');

                                                    if ($name === '' || strtolower($name) === 'walk-in customer') {
                                                        echo '<span class="badge bg-light text-muted font-italic">Walk-in Customer</span>';
                                                    } else {
                                                        echo '<span class="customer-name">' . htmlspecialchars($name) . '</span>';
                                                    }
                                                    ?>
                                                </td>

                                                <td>
                                                    <span class="phone-cell">
                                                        <?= !empty($sale['customer_phone']) ? htmlspecialchars($sale['customer_phone']) : '-' ?>
                                                    </span>
                                                </td>

                                                <td>
                                                    <span class="address-cell" title="<?= htmlspecialchars(($sale['customer_address'] ?? '') ?: '-') ?>">
                                                        <?= !empty($sale['customer_address']) ? htmlspecialchars($sale['customer_address']) : '-' ?>
                                                    </span>
                                                </td>

                                                <td>
                                                    <span class="sales-total-badge">
                                                        Rs.&nbsp;<?= number_format((float)$sale['grand_total'], 2) ?>
                                                    </span>
                                                </td>

                                                <td class="text-end">
                                                    <?php if (!empty($sale['id'])): ?>
                                                        <div class="sales-action-icons">
                                                            <!-- Print -->
                                                            <a href="<?= base_url('sales/print_invoice/' . (int)$sale['id']) ?>"
                                                                target="_blank"
                                                                class="act-icon act-print"
                                                                title="Print Invoice">
                                                                <i class="fa-solid fa-print"></i>
                                                            </a>

                                                            <!-- Edit -->
                                                            <a href="<?= base_url('sales/edit/' . (int)$sale['id']) ?>"
                                                                class="act-icon act-edit"
                                                                title="Edit Sale">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </a>

                                                            <!-- Delete -->
                                                            <a href="javascript:void(0)"
                                                                onclick="confirmDelete(<?= (int)$sale['id'] ?>)"
                                                                class="act-icon act-delete"
                                                                title="Delete Sale">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </a>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted" style="font-size:0.82rem;font-style:italic">No invoice</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6">
                                                <div class="sales-empty-state">
                                                    <div class="sales-empty-icon"><i class='bx bx-receipt'></i></div>
                                                    <h6>No sales found</h6>
                                                    <p class="mb-0">Create your first sale to see it here.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div id="paginationContainer" class="d-flex justify-content-center mt-3"></div>
                        <!-- ── Stats chips ── -->
                        <?php if (!empty($sales)): ?>
                            <div class="sales-stats">
                                <div class="sales-stat-chip">
                                    <span class="stat-dot stat-dot-blue"></span>
                                    Total Records: <strong><?= $totalRows ?></strong>
                                </div>
                                <div class="sales-stat-chip">
                                    <span class="stat-dot stat-dot-green"></span>
                                    Total Revenue: <strong>Rs. <?= number_format($totalRevenue, 2) ?></strong>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function loadSales(page = 1) {
        const search = document.querySelector('[name="search"]').value;
        const date_from = document.querySelector('[name="date_from"]').value;
        const date_to = document.querySelector('[name="date_to"]').value;

        fetch(`<?= base_url('sales/ajax_list') ?>?page=${page}&search=${search}&date_from=${date_from}&date_to=${date_to}`)
            .then(res => res.json())
            .then(data => {

                let html = '';

                if (data.sales.length > 0) {
                    data.sales.forEach((sale, index) => {

                        html += `
                        <tr>
                            <td><span class="row-num">${(data.currentPage - 1) * data.perPage + index + 1}</span></td>

                            <td>
                               ${(!sale.customer_name || sale.customer_name.toLowerCase() === 'walk-in customer')
    ? `<span class="customer-walkin">Walk-in Customer</span>`
    : `<span class="customer-name">${sale.customer_name}</span>`}
                            </td>

                            <td><span class="phone-cell">${sale.customer_phone ? sale.customer_phone : '-'}</span></td>

                            <td>
                                <span class="address-cell">
                                    ${sale.customer_address ? sale.customer_address : '-'}
                                </span>
                            </td>

                            <td>
                                <span class="sales-total-badge">
                                    Rs. ${parseFloat(sale.grand_total).toFixed(2)}
                                </span>
                            </td>

                            <td class="text-end">
                                <div class="sales-action-icons">
                                    <a href="<?= base_url('sales/print_invoice/') ?>${sale.id}"
                                       target="_blank"
                                       class="act-icon act-print"
                                       title="Print Invoice">
                                        <i class="fa-solid fa-print"></i>
                                    </a>

                                    <a href="<?= base_url('sales/edit/') ?>${sale.id}"
                                       class="act-icon act-edit"
                                       title="Edit Sale">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <a href="javascript:void(0)"
                                       onclick="confirmDelete(${sale.id})"
                                       class="act-icon act-delete"
                                       title="Delete Sale">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>`;
                    });
                } else {
                    html = `
                    <tr>
                        <td colspan="6">
                            <div class="sales-empty-state">
                                <div class="sales-empty-icon"><i class='bx bx-receipt'></i></div>
                                <h6>No sales found</h6>
                                <p class="mb-0">Create your first sale to see it here.</p>
                            </div>
                        </td>
                    </tr>`;
                }

                document.getElementById('salesTableBody').innerHTML = html;
                document.querySelector('.sales-stat-chip strong').innerText = data.totalRows;
                renderPagination(data.currentPage, data.totalPages);
            });
    }

    function renderPagination(current, total) {
        let html = `<ul class="pagination">`;

        html += `
        <li class="page-item ${current == 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="event.preventDefault(); loadSales(${current - 1})">‹</a>
        </li>`;

        let start = Math.max(1, current - 1);
        let end = Math.min(total, current + 1);

        if (start > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="event.preventDefault(); loadSales(1)">1</a></li>`;
            if (start > 2) html += `<li class="dots">...</li>`;
        }

        for (let i = start; i <= end; i++) {
            html += `
            <li class="page-item ${i == current ? 'active' : ''}">
                <a class="page-link" href="#" onclick="event.preventDefault(); loadSales(${i})">${i}</a>
            </li>`;
        }

        if (end < total) {
            if (end < total - 1) html += `<li class="dots">...</li>`;
            html += `<li class="page-item"><a class="page-link" href="#" onclick="event.preventDefault(); loadSales(${total})">${total}</a></li>`;
        }

        html += `
        <li class="page-item ${current == total ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="event.preventDefault(); loadSales(${current + 1})">›</a>
        </li></ul>`;

        document.getElementById('paginationContainer').innerHTML = html;
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('salesTableBody').innerHTML = `
        <tr><td colspan="6" style="text-align:center;padding:20px">Loading...</td></tr>`;
        loadSales(1);
    });

    document.querySelector(".sales-filter-card").addEventListener("submit", function(e) {
        e.preventDefault();
        loadSales(1);
    });

    function confirmDelete(saleId) {
        if (confirm('⚠️ Are you sure you want to delete this sale? Stock will be restored and invoice will be removed. This cannot be undone.')) {
            window.location.href = '<?= base_url("sales/delete/") ?>' + saleId;
        }
    }
</script>