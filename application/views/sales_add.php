<div class="page-wrapper">
    <div class="page-content">
        <style>
            .sf-shell {
                width: 100%;
                max-width: 1440px;
            }

            .sf-card {
                border: 1px solid #e2e8f0;
                box-shadow: 0 12px 36px rgba(15, 23, 42, .06);
                background: #fff;
                border-radius: 20px;
            }

            .sf-body {
                padding: 1.25rem 1.5rem;
            }

            .sf-topbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
                padding: .8rem 1.5rem;
                background: linear-gradient(135deg, #eff6ff 0%, #fff 58%, #ecfeff 100%);
                border-bottom: 1px solid #dbe7f5;
                border-radius: 20px 20px 0 0;
            }

            .sf-topbar h2 {
                font-size: 1.15rem;
                font-weight: 800;
                margin: 0;
                color: #0f172a;
            }

            .sf-topbar-left {
                display: flex;
                align-items: center;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .sf-note {
                display: inline-flex;
                align-items: center;
                gap: .35rem;
                padding: .25rem .6rem;
                border-radius: 999px;
                background: #e8fff2;
                color: #15803d;
                font-size: .75rem;
                font-weight: 700;
            }

            .sf-kbd-hint {
                display: inline-flex;
                align-items: center;
                gap: .3rem;
                font-size: .7rem;
                color: #94a3b8;
                margin-left: .5rem;
            }

            .sf-kbd-hint kbd {
                background: #f1f5f9;
                border: 1px solid #e2e8f0;
                border-radius: 4px;
                padding: .1rem .35rem;
                font-size: .65rem;
                color: #64748b;
            }

            .sf-cust-row {
                display: flex;
                gap: .75rem;
                align-items: flex-end;
                padding: .85rem 0;
                border-bottom: 1px solid #f1f5f9;
            }

            .sf-cust-row .sf-f {
                flex: 1;
                min-width: 0;
            }

            .sf-cust-row .sf-f.addr {
                flex: 2;
            }

            .sf-cust-row label {
                display: block;
                font-size: .72rem;
                font-weight: 700;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: .04em;
                margin-bottom: .3rem;
            }

            .sf-cust-row .form-control {
                height: 40px;
                border-radius: 10px;
                border-color: #dbe3ef;
                font-size: .88rem;
                padding: .35rem .75rem;
            }

            #customerLookupMessage {
                font-size: .7rem;
                margin-top: .2rem;
            }

            .sf-grid {
                display: grid;
                grid-template-columns: 1fr 260px;
                gap: 1rem;
                align-items: start;
                margin-top: .85rem;
            }

            .sf-items-wrap {
                border: 1px solid #e9eff6;
                border-radius: 14px;
                overflow: visible;
                background: #fbfdff;
            }

            .sf-items-head {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: .55rem .85rem;
                background: #f8fafc;
                border-bottom: 1px solid #e9eff6;
                border-radius: 14px 14px 0 0;
            }

            .sf-items-head h4 {
                font-size: .88rem;
                font-weight: 800;
                color: #0f172a;
                margin: 0;
            }

            .sf-tbl-container {
                overflow-x: auto;
                overflow-y: visible;
                -webkit-overflow-scrolling: touch;
            }

            .sf-tbl {
                width: 100%;
                border-collapse: collapse;
            }

            .sf-tbl thead th {
                font-size: .68rem;
                text-transform: uppercase;
                letter-spacing: .05em;
                color: #94a3b8;
                font-weight: 700;
                padding: .5rem .5rem;
                border-bottom: 1px solid #e9eff6;
                background: #f8fafc;
                white-space: nowrap;
            }

            .sf-tbl tbody td {
                padding: .45rem .5rem;
                vertical-align: middle;
                border-bottom: 1px solid #f1f5f9;
                font-size: .88rem;
                overflow: visible;
            }

            .sf-tbl tbody tr {
                overflow: visible;
            }

            .sf-tbl tbody {
                overflow: visible;
            }

            .sf-tbl tbody tr:last-child td {
                border-bottom: none;
            }

            .sf-tbl .form-control {
                height: 36px;
                font-size: .82rem;
                border-radius: 8px;
                padding: .2rem .5rem;
                border-color: #dbe3ef;
            }

            .sf-img-thumb {
                width: 36px;
                height: 36px;
                border-radius: 8px;
                object-fit: cover;
                border: 1px solid #e2e8f0;
                background: #f8fafc;
            }

            .sf-line-total {
                font-weight: 700;
                color: #0f172a;
                white-space: nowrap;
                font-size: .85rem;
            }

            .sf-del-btn {
                width: 30px;
                height: 30px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                border: none;
                background: #fef2f2;
                color: #dc2626;
                font-size: 1rem;
                cursor: pointer;
                transition: background .15s;
            }

            .sf-del-btn:hover {
                background: #fee2e2;
            }

            .sf-aside {
                display: grid;
                gap: .75rem;
            }

            .sf-aside-box {
                border: 1px solid #e9eff6;
                border-radius: 14px;
                padding: .85rem;
                background: linear-gradient(180deg, #f8fbff 0%, #fff 100%);
            }

            .sf-aside-title {
                font-size: .82rem;
                font-weight: 800;
                color: #0f172a;
                margin-bottom: .6rem;
                text-transform: uppercase;
                letter-spacing: .03em;
            }

            .sf-sum-row {
                display: flex;
                justify-content: space-between;
                padding: .4rem 0;
                font-size: .85rem;
                color: #475569;
                border-bottom: 1px dashed #e9eff6;
            }

            .sf-sum-row:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .sf-sum-row strong {
                color: #0f172a;
            }

            .sf-sum-grand strong {
                font-size: 1.05rem;
            }

            .sf-actions {
                display: grid;
                gap: .5rem;
            }

            .sf-actions .btn {
                width: 100%;
                font-size: .85rem;
                padding: .55rem .75rem;
                border-radius: 10px;
            }

            .sf-tbl tbody tr.sf-row-new {
                animation: sfRowGlow .5s ease;
            }

            @keyframes sfRowGlow {
                0% {
                    background: #dbeafe
                }

                100% {
                    background: transparent
                }
            }

            .sf-tbl tbody tr:hover {
                background: #f8fafc;
            }

            /* ─── PRODUCT SEARCH ─── */
            .sf-product-search-wrap {
                position: relative;
                width: 100%;
            }

            .sf-product-search {
                width: 100%;
                height: 36px;
                padding: .2rem .5rem;
                border: 1px solid #dbe3ef;
                border-radius: 8px;
                font-size: .82rem;
                cursor: text;
                background: #fff;
                box-sizing: border-box;
            }

            .sf-product-search:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
            }

            /* ─── DROPDOWN ─── */
            .sf-product-dropdown {
                position: fixed;
                max-height: 240px;
                overflow-y: scroll;
                overflow-x: hidden;
                background: #fff;
                border: 1px solid #c7d7ee;
                border-radius: 10px;
                box-shadow: 0 10px 30px rgba(15, 23, 42, .18);
                z-index: 99999;
                display: none;
                min-width: 240px;
                scrollbar-width: thin;
                scrollbar-color: #94a3b8 #f1f5f9;
                pointer-events: auto;
            }

            .sf-product-dropdown.active {
                display: block;
            }

            .sf-product-dropdown::-webkit-scrollbar {
                width: 6px;
            }

            .sf-product-dropdown::-webkit-scrollbar-track {
                background: #f1f5f9;
            }

            .sf-product-dropdown::-webkit-scrollbar-thumb {
                background: #94a3b8;
                border-radius: 6px;
            }

            .sf-product-dropdown::-webkit-scrollbar-thumb:hover {
                background: #475569;
            }

            .sf-product-option {
                padding: .6rem .85rem;
                cursor: pointer;
                border-bottom: 1px solid #f1f5f9;
                font-size: .82rem;
                color: #0f172a;
                background: #fff;
                transition: background .1s;
                white-space: normal;
                word-break: break-word;
                line-height: 1.4;
                user-select: none;
                -webkit-user-select: none;
            }

            .sf-product-option:last-child {
                border-bottom: none;
            }

            .sf-product-option:hover,
            .sf-product-option.kbfocus {
                background: #dbeafe;
                color: #1d4ed8;
            }

            .sf-product-option.hidden {
                display: none !important;
            }

            .sf-product-option.out-stock {
                color: #94a3b8;
                background: #f8fafc;
                pointer-events: none;
                cursor: default;
            }

            .sf-product-option.low-stock {
                color: #d97706;
            }

            /* ═══ BILL TABS ═══ */
            .sf-bill-bar {
                display: flex;
                align-items: center;
                gap: .6rem;
                padding: .6rem .85rem;
                margin: .5rem 0 .75rem 0;
                width: 100%;
                border-radius: 10px;
                background: linear-gradient(135deg, #f0f4ff 0%, #f8faff 50%, #f0fdf4 100%);
                border: 1px solid #e2e8f0;
            }

            .sf-bill-bar-left {
                display: flex;
                align-items: center;
                gap: .4rem;
                flex-shrink: 0;
                padding-right: .6rem;
                border-right: 1px solid #cbd5e1;
                margin-right: .25rem;
            }

            .sf-bill-bar-icon {
                width: 32px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                background: #1d4ed8;
                color: #fff;
                font-size: 1.1rem;
                flex-shrink: 0;
            }

            .sf-bill-bar-label {
                font-size: .78rem;
                font-weight: 700;
                color: #475569;
                text-transform: uppercase;
                letter-spacing: .03em;
                white-space: nowrap;
            }

            .sf-bill-bar-count {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 20px;
                height: 20px;
                padding: 0 .35rem;
                border-radius: 999px;
                background: #1d4ed8;
                color: #fff;
                font-size: .68rem;
                font-weight: 800;
                flex-shrink: 0;
            }

            .sf-bill-tabs-list {
                display: flex;
                gap: .4rem;
                overflow-x: auto;
                flex: 1;
                scrollbar-width: none;
                padding: .15rem 0;
            }

            .sf-bill-tabs-list::-webkit-scrollbar {
                display: none;
            }

            .sf-bill-tab {
                display: inline-flex;
                align-items: center;
                gap: .4rem;
                padding: .45rem .9rem;
                border: 1.5px solid #e2e8f0;
                border-radius: 10px;
                background: #fff;
                color: #64748b;
                font-size: .8rem;
                font-weight: 600;
                cursor: pointer;
                position: relative;
                transition: all .18s ease;
                white-space: nowrap;
                user-select: none;
                -webkit-user-select: none;
                box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
            }

            .sf-bill-tab:hover {
                border-color: #93c5fd;
                background: #f0f7ff;
                color: #1e40af;
                box-shadow: 0 2px 6px rgba(59, 130, 246, .1);
            }

            .sf-bill-tab.active {
                background: linear-gradient(135deg, #1d4ed8, #2563eb);
                color: #fff;
                border-color: #1d4ed8;
                font-weight: 700;
                box-shadow: 0 3px 10px rgba(37, 99, 235, .25);
            }

            .sf-bill-tab.active:hover {
                background: linear-gradient(135deg, #1e40af, #1d4ed8);
            }

            .sf-bill-tab-title {
                max-width: 120px;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .sf-bill-tab-dot {
                width: 7px;
                height: 7px;
                border-radius: 50%;
                background: #3b82f6;
                flex-shrink: 0;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, .2);
                animation: sfDotPulse 2s ease infinite;
            }

            .sf-bill-tab.active .sf-bill-tab-dot {
                background: #4ade80;
                box-shadow: 0 0 0 2px rgba(74, 222, 128, .3);
            }

            @keyframes sfDotPulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: .5;
                }
            }

            .sf-bill-tab-close {
                width: 18px;
                height: 18px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                font-size: .85rem;
                line-height: 1;
                color: #94a3b8;
                transition: all .15s;
                flex-shrink: 0;
                margin-left: .15rem;
            }

            .sf-bill-tab-close:hover {
                background: rgba(220, 38, 38, .15);
                color: #dc2626;
            }

            .sf-bill-tab.active .sf-bill-tab-close {
                color: rgba(255, 255, 255, .6);
            }

            .sf-bill-tab.active .sf-bill-tab-close:hover {
                color: #fff;
                background: rgba(255, 255, 255, .2);
            }

            .sf-bill-tab-add {
                display: inline-flex;
                align-items: center;
                gap: .35rem;
                padding: .42rem .75rem;
                border-radius: 10px;
                border: 2px dashed #93c5fd;
                background: #f0f7ff;
                color: #2563eb;
                font-size: .82rem;
                font-weight: 700;
                cursor: pointer;
                transition: all .18s ease;
                flex-shrink: 0;
                white-space: nowrap;
            }

            .sf-bill-tab-add:hover {
                border-color: #2563eb;
                background: #dbeafe;
                color: #1d4ed8;
                box-shadow: 0 2px 8px rgba(37, 99, 235, .12);
                transform: translateY(-1px);
            }

            .sf-bill-tab-add:active {
                transform: translateY(0);
            }

            .sf-bill-tab-add i {
                font-size: 1rem;
            }

            .sf-bill-add-text {
                font-size: .76rem;
                font-weight: 700;
                letter-spacing: .02em;
            }

            /* Remove arrows in number input */
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type=number] {
                -moz-appearance: textfield;
            }

            /* ═══════════════════════════════════════════
               RESPONSIVE BREAKPOINTS
            ═══════════════════════════════════════════ */

            @media(max-width:1199.98px) {
                .sf-grid {
                    grid-template-columns: 1fr 240px;
                }
            }

            @media(max-width:991.98px) {
                .sf-grid {
                    grid-template-columns: 1fr;
                }

                .sf-aside {
                    grid-template-columns: 1fr 1fr;
                }

                .sf-cust-row {
                    flex-wrap: wrap;
                }

                .sf-cust-row .sf-f {
                    flex: 1 1 calc(50% - .5rem);
                    min-width: 160px;
                }

                .sf-cust-row .sf-f.addr {
                    flex: 1 1 100%;
                }

                .sf-bill-bar {
                    margin: 0;
                    padding: .5rem .7rem;
                }
            }

            @media(max-width:767.98px) {
                .sf-card {
                    border-radius: 12px;
                }

                .sf-body {
                    padding: .85rem;
                }

                .sf-topbar {
                    flex-direction: column;
                    align-items: flex-start;
                    padding: .7rem .85rem;
                    border-radius: 12px 12px 0 0;
                    gap: .6rem;
                }

                .sf-topbar-left {
                    gap: .5rem;
                    width: 100%;
                }

                .sf-topbar h2 {
                    font-size: 1.05rem;
                }

                .sf-kbd-hint {
                    display: none;
                }

                .sf-cust-row {
                    flex-direction: column;
                    gap: .6rem;
                }

                .sf-cust-row .sf-f {
                    flex: 1 1 100%;
                    min-width: 0;
                }

                .sf-cust-row .sf-f.addr {
                    flex: 1 1 100%;
                }

                .sf-aside {
                    grid-template-columns: 1fr;
                }

                .sf-bill-bar {
                    flex-wrap: wrap;
                    gap: .4rem;
                    margin: 0;
                    padding: .45rem .6rem;
                    border-radius: 8px;
                }

                .sf-bill-bar-left {
                    border-right: none;
                    padding-right: 0;
                    margin-right: 0;
                    width: 100%;
                }

                .sf-bill-bar-label {
                    font-size: .72rem;
                }

                .sf-bill-tabs-list {
                    order: 3;
                    width: 100%;
                    flex: none;
                }

                .sf-bill-tab-add {
                    order: 2;
                    margin-left: auto;
                }

                .sf-bill-tab {
                    font-size: .74rem;
                    padding: .38rem .65rem;
                }

                .sf-bill-add-text {
                    display: none;
                }

                .sf-bill-tab-add {
                    padding: .38rem .55rem;
                }

                .sf-items-wrap {
                    border-radius: 10px;
                }

                .sf-items-head {
                    border-radius: 10px 10px 0 0;
                    padding: .5rem .65rem;
                }

                .sf-items-head h4 {
                    font-size: .82rem;
                }

                .sf-tbl-container {
                    overflow-x: visible;
                    overflow-y: visible;
                }

                .sf-tbl,
                .sf-tbl thead,
                .sf-tbl tbody,
                .sf-tbl th,
                .sf-tbl td,
                .sf-tbl tr {
                    display: block;
                    width: 100%;
                }

                .sf-tbl thead {
                    display: none;
                }

                .sf-tbl tbody tr[data-row] {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: .4rem .6rem;
                    padding: .75rem .65rem;
                    border-bottom: 1px solid #e9eff6;
                    position: relative;
                    background: #fff;
                    margin-bottom: .25rem;
                    border-radius: 8px;
                }

                .sf-tbl tbody tr[data-row]:last-child {
                    border-bottom: none;
                    margin-bottom: 0;
                }

                .sf-tbl tbody td {
                    padding: .15rem 0;
                    border-bottom: none;
                    font-size: .82rem;
                }

                .sf-tbl tbody td.sf-pcell {
                    grid-column: 1 / -1;
                    min-width: 0 !important;
                }

                .sf-tbl tbody td:nth-child(2) {
                    grid-column: 1 / -1;
                    display: flex;
                    align-items: center;
                    gap: .5rem;
                }

                .sf-img-thumb {
                    width: 32px;
                    height: 32px;
                }

                .sf-tbl tbody td:nth-child(3),
                .sf-tbl tbody td:nth-child(4),
                .sf-tbl tbody td:nth-child(5),
                .sf-tbl tbody td:nth-child(6) {
                    display: flex;
                    flex-direction: column;
                    gap: .15rem;
                }

                .sf-tbl tbody td:nth-child(3)::before,
                .sf-tbl tbody td:nth-child(4)::before,
                .sf-tbl tbody td:nth-child(5)::before,
                .sf-tbl tbody td:nth-child(6)::before {
                    font-size: .62rem;
                    font-weight: 700;
                    color: #94a3b8;
                    text-transform: uppercase;
                    letter-spacing: .04em;
                }

                .sf-tbl tbody td:nth-child(3)::before {
                    content: 'Price';
                }

                .sf-tbl tbody td:nth-child(4)::before {
                    content: 'Qty';
                }

                .sf-tbl tbody td:nth-child(5)::before {
                    content: 'Disc (₹)';
                }

                .sf-tbl tbody td:nth-child(6)::before {
                    content: 'Disc (%)';
                }

                .sf-tbl tbody td:nth-child(7) {
                    grid-column: 1 / -1;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding-top: .35rem;
                    border-top: 1px dashed #e9eff6;
                    margin-top: .15rem;
                }

                .sf-tbl tbody td:nth-child(7)::before {
                    content: 'Line Total';
                    font-size: .68rem;
                    font-weight: 700;
                    color: #94a3b8;
                    text-transform: uppercase;
                    letter-spacing: .04em;
                }

                .sf-tbl tbody td:nth-child(8) {
                    position: absolute;
                    top: .5rem;
                    right: .5rem;
                    width: auto;
                }

                .sf-tbl .form-control {
                    height: 38px;
                    font-size: .84rem;
                }

                .sf-product-search {
                    height: 38px;
                    font-size: .84rem;
                }

                .sf-aside-box {
                    border-radius: 10px;
                    padding: .75rem;
                }

                .sf-sum-row {
                    font-size: .82rem;
                }

                .sf-sum-grand strong {
                    font-size: 1rem;
                }

                .sf-actions .btn {
                    font-size: .82rem;
                    padding: .6rem .75rem;
                }
            }

            @media(max-width:480px) {
                .sf-shell {
                    padding: 0;
                }

                .sf-card {
                    border-radius: 0;
                    border-left: none;
                    border-right: none;
                }

                .sf-topbar {
                    border-radius: 0;
                    padding: .6rem .65rem;
                }

                .sf-topbar h2 {
                    font-size: .95rem;
                }

                .sf-note {
                    font-size: .68rem;
                    padding: .2rem .5rem;
                }

                .sf-body {
                    padding: .65rem;
                }

                .sf-cust-row {
                    gap: .5rem;
                    padding: .65rem 0;
                }

                .sf-cust-row label {
                    font-size: .68rem;
                }

                .sf-cust-row .form-control {
                    height: 38px;
                    font-size: .82rem;
                    border-radius: 8px;
                }

                .sf-grid {
                    gap: .75rem;
                    margin-top: .65rem;
                }

                .sf-items-wrap {
                    border-radius: 8px;
                }

                .sf-items-head {
                    border-radius: 8px 8px 0 0;
                    padding: .45rem .55rem;
                }

                .sf-items-head h4 {
                    font-size: .78rem;
                }

                #addSaleRow {
                    font-size: .72rem !important;
                    padding: .35rem .6rem !important;
                }

                .sf-tbl tbody tr[data-row] {
                    grid-template-columns: 1fr 1fr;
                    gap: .35rem .5rem;
                    padding: .65rem .55rem;
                }

                .sf-tbl .form-control {
                    height: 36px;
                    font-size: .8rem;
                }

                .sf-product-search {
                    height: 36px;
                    font-size: .8rem;
                }

                .sf-line-total {
                    font-size: .82rem;
                }

                .sf-del-btn {
                    width: 26px;
                    height: 26px;
                    font-size: .88rem;
                }

                .sf-aside-box {
                    border-radius: 8px;
                    padding: .65rem;
                }

                .sf-aside-title {
                    font-size: .76rem;
                }

                .sf-sum-row {
                    font-size: .78rem;
                    padding: .35rem 0;
                }

                .sf-sum-grand strong {
                    font-size: .95rem;
                }

                .sf-actions .btn {
                    font-size: .8rem;
                    padding: .55rem .65rem;
                    border-radius: 8px;
                }

                .sf-bill-bar {
                    padding: .4rem .5rem;
                    border-radius: 6px;
                    gap: .3rem;
                }

                .sf-bill-bar-icon {
                    width: 28px;
                    height: 28px;
                    font-size: .95rem;
                    border-radius: 6px;
                }

                .sf-bill-bar-label {
                    font-size: .68rem;
                }

                .sf-bill-bar-count {
                    min-width: 18px;
                    height: 18px;
                    font-size: .62rem;
                }

                .sf-bill-tab {
                    font-size: .7rem;
                    padding: .32rem .55rem;
                    border-radius: 8px;
                    gap: .3rem;
                }

                .sf-bill-tab-title {
                    max-width: 80px;
                }

                .sf-bill-tab-close {
                    width: 16px;
                    height: 16px;
                    font-size: .75rem;
                }

                .sf-bill-tab-add {
                    padding: .32rem .45rem;
                    border-radius: 8px;
                }

                .sf-bill-tab-add i {
                    font-size: .9rem;
                }

                .sf-product-dropdown {
                    min-width: 200px;
                    max-height: 200px;
                    border-radius: 8px;
                }

                .sf-product-option {
                    padding: .55rem .7rem;
                    font-size: .78rem;
                }

                .sf-tbl tbody td:nth-child(2) {
                    display: none;
                }
            }

            @media(max-width:360px) {
                .sf-topbar h2 {
                    font-size: .88rem;
                }

                .sf-note {
                    font-size: .64rem;
                }

                .sf-body {
                    padding: .5rem;
                }

                .sf-tbl tbody tr[data-row] {
                    grid-template-columns: 1fr;
                    gap: .3rem;
                    padding: .55rem .45rem;
                }

                .sf-tbl tbody td:nth-child(3),
                .sf-tbl tbody td:nth-child(4),
                .sf-tbl tbody td:nth-child(5),
                .sf-tbl tbody td:nth-child(6) {
                    flex-direction: row;
                    align-items: center;
                    justify-content: space-between;
                }

                .sf-tbl tbody td:nth-child(3)::before,
                .sf-tbl tbody td:nth-child(4)::before,
                .sf-tbl tbody td:nth-child(5)::before,
                .sf-tbl tbody td:nth-child(6)::before {
                    flex-shrink: 0;
                    min-width: 55px;
                }

                .sf-tbl .form-control {
                    max-width: 60%;
                }

                .sf-bill-tab-title {
                    max-width: 60px;
                }

                .sf-cust-row .form-control {
                    font-size: .78rem;
                }
            }
            .sf-barcode-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ecfdf5;
    color: #059669;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 20px;
    margin-bottom: 10px;
    width: fit-content;
}
.sf-barcode-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    animation: sf-pulse 1.5s infinite;
}
@keyframes sf-pulse {
    0% { box-shadow: 0 0 0 0 rgba(16,185,129,0.6); }
    70% { box-shadow: 0 0 0 6px rgba(16,185,129,0); }
    100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
}
.sf-custom-toggle-btn {
    display: inline-block;
    margin-top: 4px;
    font-size: 11px;
    color: #2563eb;
    background: none;
    border: none;
    padding: 2px 4px;
    cursor: pointer;
    text-decoration: underline;
}
.sf-custom-toggle-btn.active,
.sf-custom-toggle-btn:hover {
    color: #1d4ed8;
}
.sf-custom-name-input {
    width: 100%;
    margin-top: 4px;
    padding: 6px 8px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 13px;
}
        </style>

        <div class="sf-shell mx-auto">
            <?php if (!empty($saleError)): ?>
                <div class="alert alert-danger mb-2 rounded-3" style="font-size:.88rem"><?= $saleError ?></div>
            <?php endif; ?>

 
<form method="post" action="<?= base_url('sales/store') ?>" id="saleForm">

                <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>

                <div class="sf-card">
                    <div class="sf-topbar">
                        <div class="sf-topbar-left">
                            <h2><i class='bx bx-receipt' style="vertical-align:middle;margin-right:.3rem"></i> New Sale</h2>
                            <span class="sf-note"><i class='bx bx-check-circle'></i> Walk-in or customer</span>
                            <span class="sf-kbd-hint">
                                <kbd>Tab</kbd> navigate ·
                                <kbd>Ctrl+Enter</kbd> save ·
                                <kbd>N</kbd> new bill ·
                                <kbd>W</kbd> close bill
                            </span>
                        </div>
                        <a href="<?= base_url('sales') ?>" class="btn btn-outline-secondary btn-sm px-3">← Back</a>
                    </div>

                    <div class="sf-bill-bar" id="billTabsBar">
                        <div class="sf-bill-bar-left">
                            <div class="sf-bill-bar-icon">
                                <i class='bx bx-food-menu'></i>
                            </div>
                            <span class="sf-bill-bar-label">Open Bills</span>
                            <span class="sf-bill-bar-count" id="billCountBadge">1</span>
                        </div>
                        <div class="sf-bill-tabs-list" id="billTabsList"></div>
                        <button type="button" class="sf-bill-tab-add" id="addBillBtn" title="Add New Bill (N)">
                            <i class='bx bx-plus'></i>
                            <span class="sf-bill-add-text">New Bill</span>
                        </button>
                    </div>

                    <div class="sf-body">
                        <div class="sf-cust-row">
                            <div class="sf-f">
                                <label for="customer_phone">Phone <span style="font-weight:400;color:#94a3b8">(optional)</span></label>
                                <input type="text" name="customer_phone" id="customer_phone" value="<?= set_value('customer_phone') ?>" class="form-control" placeholder="Customer phone">
                                <?= form_error('customer_phone') ?>
                                <small class="text-muted d-block" id="customerLookupMessage">Enter phone to auto-fetch, or leave blank for walk-in sale</small>
                            </div>
                            <div class="sf-f">
                                <label for="customer_name">Name <span style="font-weight:400;color:#94a3b8">(optional)</span></label>
                                <input type="text" name="customer_name" id="customer_name" value="<?= set_value('customer_name') ?>" class="form-control" placeholder="Customer name">
                                <?= form_error('customer_name') ?>
                            </div>
                            <div class="sf-f addr">
                                <label for="customer_address">Address <span style="font-weight:400;color:#94a3b8">(optional)</span></label>
                                <input type="text" name="customer_address" id="customer_address" value="<?= set_value('customer_address') ?>" class="form-control" placeholder="Customer address">
                            </div>
   
                            <div class="sf-f">
                                <label for="sale_date">Date</label>
                                <input type="date" name="sale_date" id="sale_date" class="form-control">
                            </div>
                        </div>

                        <div class="sf-grid">
                            
                            <div class="sf-items-wrap" id="triggerbtn">
                                <div class="sf-items-head">
                                    <h4><i class='bx bx-cart' style="vertical-align:middle;margin-right:.3rem"></i> Items  <div class="sf-barcode-status" id="barcodeStatus" style="display:none">
    <span class="sf-barcode-dot"></span>
    <span>Ready to Scan</span>
</div>
<input type="text" id="barcodeInput" autocomplete="off"
       style="position:absolute; opacity:0; width:1px; height:1px; pointer-events:none; left:-9999px;"></h4>
                                    <button type="button" class="btn btn-primary btn-sm px-3" id="addSaleRow" style="font-size:.78rem;border-radius:8px">
                                        <i class='bx bx-plus'></i> Add Sales
                                    </button>
                                </div>
                                <div class="sf-tbl-container">
                                    <table class="sf-tbl" id="saleItemsTable">
                                        <thead>
                                            <tr>
                                                <th style="width:28%">Product</th>
                                                <th style="width:44px"></th>
                                                <th style="width:88px">Price</th>
                                                <th style="width:68px">Qty</th>
                                                <th style="width:96px">Disc (₹)</th>
                                                <th style="width:96px">Disc (%)</th>
                                                <th style="width:90px">Total</th>
                                                <th style="width:36px"></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="sf-aside">
                                <div class="sf-aside-box">
                                    <div class="sf-aside-title">💰 Summary</div>
                                    <div class="sf-sum-row">
                                        <span>Subtotal</span>
                                        <strong id="saleSubtotal">Rs. 0.00</strong>
                                    </div>
                                    <div class="sf-sum-row">
                                        <span>Discount</span>
                                        <strong id="saleDiscountTotal" style="color:#dc2626">− Rs. 0.00</strong>
                                    </div>
                                    <div class="sf-sum-row sf-sum-grand" style="padding-top:.6rem;margin-top:.2rem;border-top:2px solid #e2e8f0;border-bottom:none">
                                        <span><strong>Grand Total</strong></span>
                                        <strong id="saleGrandTotal" style="color:#059669">Rs. 0.00</strong>
                                    </div>
                                </div>
                                <div class="sf-aside-box">
                                    <div class="sf-actions">
                                        <button type="submit" class="btn btn-success">
                                            <i class='bx bx-check-circle'></i> Save &amp; Generate Invoice
                                        </button>
                                        <a href="<?= base_url('sales') ?>" class="btn btn-outline-secondary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

       <script>
        console.log('%c🔥 NEW SCRIPT LOADED', 'background:red;color:#fff;font-size:20px');
    document.addEventListener('DOMContentLoaded', function() {

        /* ══════════════════════════════════════
           SETUP
        ══════════════════════════════════════ */
        const BASE_URL = "<?= base_url() ?>";
        const products = <?= json_encode($products, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
        const tbody = document.querySelector('#saleItemsTable tbody');
        const addRowBtn = document.getElementById('addSaleRow');
        const custPhone = document.getElementById('customer_phone');
        const saleForm = document.getElementById('saleForm');
        const DEFAULT_IMG = "<?= base_url('assets/images/Product_Default.png') ?>";
        const BILL_STORAGE_KEY = 'sf_draft_bills';

        let rowCounter = 0;
        let activeDD = null;
        let discLock = false;
        let isRestoringBill = false;
        let isSubmitting = false;

        const fmt = v => 'Rs. ' + (+v || 0).toFixed(2);
        const getProduct = id => products.find(p => String(p.id) === String(id)) || null;

        function sfAlert(title, text, icon = 'warning') {
            if (typeof Swal !== 'undefined') {
                return Swal.fire({ icon, title, text, confirmButtonColor: '#3b82f6' });
            }
            alert(title + '\n' + text);
            return Promise.resolve();
        }

        /* ══════════════════════════════════════
           DROPDOWN HELPERS
        ══════════════════════════════════════ */
        function ddClose() {
            if (!activeDD) return;
            activeDD.classList.remove('active');
            activeDD.querySelectorAll('.kbfocus').forEach(el => el.classList.remove('kbfocus'));
            activeDD = null;
        }

        function ddOpen(inputEl, dd) {
            if (activeDD === dd) return;
            ddClose();
            positionDD(inputEl, dd);
            dd.classList.add('active');
            activeDD = dd;
        }

        function positionDD(inputEl, dd) {
            const r = inputEl.getBoundingClientRect();
            const viewportHeight = window.visualViewport ? window.visualViewport.height : window.innerHeight;
            const ddHeight = 240;
            let top = r.bottom + 5;
            if (top + ddHeight > viewportHeight) top = r.top - ddHeight - 5;
            dd.style.position = 'fixed';
            dd.style.left = Math.max(4, r.left) + 'px';
            dd.style.top = Math.max(4, top) + 'px';
            dd.style.width = Math.max(r.width, 200) + 'px';
        }

        function ddMoveKb(dd, dir) {
            const opts = [...dd.querySelectorAll('.sf-product-option:not(.hidden):not(.out-stock)')];
            if (!opts.length) return;
            const cur = dd.querySelector('.sf-product-option.kbfocus');
            let idx = opts.indexOf(cur);
            if (cur) cur.classList.remove('kbfocus');
            idx = Math.max(0, Math.min(opts.length - 1, idx + dir));
            opts[idx].classList.add('kbfocus');
            opts[idx].scrollIntoView({ block: 'nearest' });
        }

        document.addEventListener('pointerdown', function(e) {
            if (!activeDD) return;
            if (activeDD.contains(e.target)) return;
            const wrap = activeDD._wrap;
            if (wrap && wrap.contains(e.target)) return;
            ddClose();
        });

        window.addEventListener('resize', ddClose);

        if (window.visualViewport) {
            window.visualViewport.addEventListener('resize', () => {
                if (activeDD) {
                    const input = activeDD._wrap && activeDD._wrap.querySelector('.sf-product-search');
                    if (input) positionDD(input, activeDD);
                }
            });
        }

        /* ══════════════════════════════════════
           DISCOUNT SYNC & SUMMARY
        ══════════════════════════════════════ */
        function syncDisc(row, source) {
            if (discLock) return;
            discLock = true;

            const price = +row.querySelector('.sale-price-input').value || 0;
            const qty = +row.querySelector('.sale-qty-input').value || 0;
            const base = price * qty;
            const rsEl = row.querySelector('.sale-disc-rs');
            const pctEl = row.querySelector('.sale-disc-pct');

            if (base > 0) {
                if (source === 'rs') {
                    const rs = Math.min(Math.max(+rsEl.value || 0, 0), base);
                    rsEl.value = parseFloat(rs.toFixed(2));
                    pctEl.value = parseFloat(((rs / base) * 100).toFixed(2));
                } else {
                    const pct = Math.min(Math.max(+pctEl.value || 0, 0), 100);
                    pctEl.value = parseFloat(pct.toFixed(2));
                    rsEl.value = parseFloat(((base * pct) / 100).toFixed(2));
                }
            } else {
                rsEl.value = 0;
                pctEl.value = 0;
            }

            discLock = false;
            recalcSummary();
        }

        /* ✅ MODIFIED: now includes custom-item rows in totals */
        function recalcSummary() {
            let sub = 0, disc = 0;
            tbody.querySelectorAll('tr[data-row]').forEach(row => {
                const isCustom = row.dataset.isCustom === '1';
                const hasProduct = !!getProduct(row.dataset.productId);
                if (!isCustom && !hasProduct) return;

                const price = +row.querySelector('.sale-price-input').value || 0;
                const qty = +row.querySelector('.sale-qty-input').value || 0;
                const discRs = +row.querySelector('.sale-disc-rs').value || 0;
                const base = price * qty;
                const d = Math.min(discRs, base);
                sub += base;
                disc += d;
                row.querySelector('.sf-line-total').textContent = fmt(base - d);
            });
            document.getElementById('saleSubtotal').textContent = fmt(sub);
            document.getElementById('saleDiscountTotal').textContent = '− ' + fmt(disc);
            document.getElementById('saleGrandTotal').textContent = fmt(sub - disc);
        }

        /* ══════════════════════════════════════
           REFRESH ROW AFTER PRODUCT SELECT
        ══════════════════════════════════════ */
        /* ✅ MODIFIED: skip reset logic entirely for custom rows */
        function refreshRow(row) {
            if (row.dataset.isCustom === '1') {
                recalcSummary();
                return;
            }

            const p = getProduct(row.dataset.productId);
            const img = row.querySelector('.sf-img-thumb');
            const priceEl = row.querySelector('.sale-price-input');
            const qtyEl = row.querySelector('.sale-qty-input');
            const rsEl = row.querySelector('.sale-disc-rs');
            const pctEl = row.querySelector('.sale-disc-pct');

            if (!p || !row.dataset.productId) {
                if (img) img.src = DEFAULT_IMG;
                if (priceEl) priceEl.value = 0;
                if (qtyEl) { qtyEl.value = 1; qtyEl.max = ''; }
                if (rsEl) rsEl.value = 0;
                if (pctEl) pctEl.value = 0;
                recalcSummary();
                return;
            }

            if (img) {
                img.src = p.image_url || DEFAULT_IMG;
                img.onerror = function() { this.onerror = null; this.src = DEFAULT_IMG; };
            }
            if (priceEl) priceEl.value = p.sale_price || 0;
            if (qtyEl) {
                if (+qtyEl.value <= 0) qtyEl.value = 1;
                else if (+qtyEl.value > p.quantity) qtyEl.value = p.quantity || 1;
                qtyEl.max = p.quantity;
            }
            if (rsEl) rsEl.value = 0;
            if (pctEl) pctEl.value = 0;

            recalcSummary();
        }

        /* ✅ MODIFIED: custom rows with a typed name count as "filled" */
        function lastRowHasProduct() {
            const rows = [...tbody.querySelectorAll('tr[data-row]')];
            if (!rows.length) return true;
            const last = rows[rows.length - 1];
            if (last.dataset.isCustom === '1') {
                const nameInput = last.querySelector('.sf-custom-name-input');
                return !!(nameInput && nameInput.value.trim());
            }
            return !!last.dataset.productId;
        }

        /* ══════════════════════════════════════
           BUILD PRODUCT SEARCH + DROPDOWN + CUSTOM ITEM TOGGLE
        ══════════════════════════════════════ */
        function buildSelect(row) {
            const wrap = document.createElement('div');
            wrap.className = 'sf-product-search-wrap';

            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'sf-product-search';
            input.placeholder = 'Search product...';
            input.autocomplete = 'off';

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'product_id[]';

            /* ✅ NEW: custom item fields */
            const isCustomHidden = document.createElement('input');
            isCustomHidden.type = 'hidden';
            isCustomHidden.name = 'is_custom[]';
            isCustomHidden.className = 'sf-is-custom';
            isCustomHidden.value = '0';

            const customNameInput = document.createElement('input');
            customNameInput.type = 'text';
            customNameInput.name = 'custom_name[]';
            customNameInput.className = 'sf-custom-name-input';
            customNameInput.placeholder = 'Enter custom item name';
            customNameInput.autocomplete = 'off';
            customNameInput.style.display = 'none';

            const toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'sf-custom-toggle-btn';
            toggleBtn.textContent = '+ Custom Item';
            toggleBtn.title = 'Add an item not in your product list';

            const dd = document.createElement('div');
            dd.className = 'sf-product-dropdown';
            dd._wrap = wrap;

            products.forEach(p => {
                const isOut = +p.quantity <= 0;
                const isLow = !isOut && p.quantity <= 5;
                const opt = document.createElement('div');
                opt.className = 'sf-product-option' +
                    (isOut ? ' out-stock' : '') +
                    (isLow ? ' low-stock' : '');
                opt.textContent = isOut ?
                    `${p.name} (Out of Stock)` :
                    `${p.name} (Stock: ${p.quantity})`;
                opt.dataset.pid = p.id;
                opt.dataset.name = p.name.toLowerCase();

                if (!isOut) {
                    opt.addEventListener('mousedown', e => {
                        e.preventDefault();
                        input.value = p.name;
                        hidden.value = p.id;
                        row.dataset.productId = p.id;
                        ddClose();
                        refreshRow(row);
                        setTimeout(() => row.querySelector('.sale-qty-input')?.focus(), 50);
                    });
                }
                dd.appendChild(opt);
            });

            document.body.appendChild(dd);
            row._dropdown = dd;

            input.addEventListener('focus', () => ddOpen(input, dd));
            input.addEventListener('click', () => ddOpen(input, dd));

            input.addEventListener('input', () => {
                const q = input.value.toLowerCase().trim();
                let any = false;
                dd.querySelectorAll('.sf-product-option').forEach(o => {
                    const show = !q || o.dataset.name.includes(q);
                    o.classList.toggle('hidden', !show);
                    if (show) any = true;
                });
                any ? ddOpen(input, dd) : ddClose();
            });

            input.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    e.stopPropagation();
                    if (activeDD === dd) {
                        const pick = dd.querySelector('.sf-product-option.kbfocus:not(.hidden)') ||
                            dd.querySelector('.sf-product-option:not(.hidden):not(.out-stock)');
                        if (pick) {
                            pick.dispatchEvent(new MouseEvent('mousedown', { bubbles: true }));
                            return;
                        }
                    }
                    row.querySelector('.sale-qty-input')?.focus();
                    return;
                }
                if (e.key === 'Escape') {
                    e.stopPropagation();
                    ddClose();
                    return;
                }
                if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    e.stopPropagation();
                    activeDD !== dd ? ddOpen(input, dd) : ddMoveKb(dd, e.key === 'ArrowDown' ? 1 : -1);
                }
            });

            /* ✅ NEW: Custom Item Toggle logic */
            toggleBtn.addEventListener('click', () => {
                const isCustomNow = row.dataset.isCustom === '1';

                if (!isCustomNow) {
                    ddClose();
                    row.dataset.isCustom = '1';
                    row.dataset.productId = '';
                    input.value = '';
                    hidden.value = '';
                    isCustomHidden.value = '1';

                    input.style.display = 'none';
                    customNameInput.style.display = '';
                    toggleBtn.textContent = '← Use Product List';
                    toggleBtn.classList.add('active');

                    const img = row.querySelector('.sf-img-thumb');
                    if (img) img.src = DEFAULT_IMG;
                    const qtyEl = row.querySelector('.sale-qty-input');
                    if (qtyEl) qtyEl.max = '';

                    setTimeout(() => customNameInput.focus(), 30);
                    recalcSummary();
                } else {
                    row.dataset.isCustom = '0';
                    isCustomHidden.value = '0';
                    customNameInput.value = '';
                    customNameInput.style.display = 'none';
                    input.style.display = '';
                    toggleBtn.textContent = '+ Custom Item';
                    toggleBtn.classList.remove('active');

                    row.querySelector('.sale-price-input').value = 0;
                    row.querySelector('.sale-disc-rs').value = 0;
                    row.querySelector('.sale-disc-pct').value = 0;

                    setTimeout(() => input.focus(), 30);
                    recalcSummary();
                }
                billMgr.saveCurrent();
            });

            customNameInput.addEventListener('input', () => recalcSummary());

            customNameInput.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    e.stopPropagation();
                    row.querySelector('.sale-qty-input')?.focus();
                }
            });

            wrap.appendChild(input);
            wrap.appendChild(customNameInput);
            wrap.appendChild(hidden);
            wrap.appendChild(isCustomHidden);
            wrap.appendChild(toggleBtn);
            return { wrap, input, hidden, dd, customNameInput, isCustomHidden, toggleBtn };
        }

        /* ══════════════════════════════════════
           ATTACH ROW EVENTS
        ══════════════════════════════════════ */
        function attachRowEvents(row, wrap, input, dd) {
            const qtyEl = row.querySelector('.sale-qty-input');
            const prEl = row.querySelector('.sale-price-input');
            const rsEl = row.querySelector('.sale-disc-rs');
            const pctEl = row.querySelector('.sale-disc-pct');

            prEl.addEventListener('input', () => {
                if (+rsEl.value > 0) syncDisc(row, 'rs');
                else if (+pctEl.value > 0) syncDisc(row, 'pct');
                else recalcSummary();
            });
            qtyEl.addEventListener('input', () => {
                if (+rsEl.value > 0) syncDisc(row, 'rs');
                else if (+pctEl.value > 0) syncDisc(row, 'pct');
                else recalcSummary();
            });
            rsEl.addEventListener('input', () => syncDisc(row, 'rs'));
            pctEl.addEventListener('input', () => syncDisc(row, 'pct'));

            qtyEl.addEventListener('keydown', e => {
                if (e.key !== 'Enter') return;
                e.preventDefault();
                e.stopPropagation();
                const rows = [...tbody.querySelectorAll('tr[data-row]')];
                const next = rows[rows.indexOf(row) + 1];
                if (next) {
                    const nextFocus = next.dataset.isCustom === '1' ?
                        next.querySelector('.sf-custom-name-input') :
                        next.querySelector('.sf-product-search');
                    nextFocus?.focus();
                } else addRow(true);
            });

            /* ✅ MODIFIED: also resets custom mode when clearing last row */
            row.querySelector('.sf-del-btn').addEventListener('click', () => {
                const allRows = tbody.querySelectorAll('tr[data-row]');

                if (allRows.length > 1) {
                    if (dd?.parentNode) dd.parentNode.removeChild(dd);
                    row.remove();
                    recalcSummary();
                } else {
                    if (row.dataset.isCustom === '1') {
                        row.querySelector('.sf-custom-toggle-btn')?.click();
                    }

                    row.dataset.productId = '';
                    input.value = '';
                    const hi = wrap.querySelector('input[name="product_id[]"]');
                    if (hi) hi.value = '';

                    dd.querySelectorAll('.sf-product-option').forEach(opt => {
                        opt.classList.remove('hidden');
                        const pid = opt.dataset.pid;
                        const p = getProduct(pid);
                        if (p) {
                            const isOut = +p.quantity <= 0;
                            const isLow = !isOut && p.quantity <= 5;
                            opt.classList.toggle('out-stock', isOut);
                            opt.classList.toggle('low-stock', isLow);
                            opt.style.pointerEvents = isOut ? 'none' : '';
                            opt.style.cursor = isOut ? 'default' : '';
                        }
                    });

                    const img = row.querySelector('.sf-img-thumb');
                    if (img) img.src = DEFAULT_IMG;

                    row.querySelector('.sale-price-input').value = 0;
                    row.querySelector('.sale-qty-input').value = 1;
                    row.querySelector('.sale-qty-input').max = '';
                    row.querySelector('.sale-disc-rs').value = 0;
                    row.querySelector('.sale-disc-pct').value = 0;
                    row.querySelector('.sf-line-total').textContent = 'Rs. 0.00';

                    recalcSummary();
                }

                billMgr.saveCurrent();
                billMgr.persistToStorage();
            });

            const inputs = row.querySelectorAll('input');

            inputs.forEach((input, index) => {
                input.addEventListener('keydown', function(e) {
                    if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    const allRows = [...tbody.querySelectorAll('tr[data-row]')];
                    const rowIndex = allRows.indexOf(row);

                    if (e.key === 'ArrowRight') {
                        if (inputs[index + 1]) inputs[index + 1].focus();
                    }
                    if (e.key === 'ArrowLeft') {
                        if (inputs[index - 1]) inputs[index - 1].focus();
                    }
                    if (e.key === 'ArrowUp') {
                        if (rowIndex > 0) {
                            const prevRow = allRows[rowIndex - 1];
                            const prevInputs = prevRow.querySelectorAll('input');
                            prevInputs[index]?.focus();
                        }
                    }
                    if (e.key === 'ArrowDown') {
                        if (rowIndex < allRows.length - 1) {
                            const nextRow = allRows[rowIndex + 1];
                            const nextInputs = nextRow.querySelectorAll('input');
                            nextInputs[index]?.focus();
                        }
                    }
                });
            });
        }

        /* ══════════════════════════════════════
           ADD ROW
        ══════════════════════════════════════ */
        function addRow(focus = true) {
            if (!isRestoringBill && !lastRowHasProduct()) {
                sfAlert('Select a Product First', 'Please select a product in the current row before adding a new one.', 'warning');
                const rows = [...tbody.querySelectorAll('tr[data-row]')];
                if (rows.length) rows[rows.length - 1].querySelector('.sf-product-search')?.focus();
                return null;
            }

            rowCounter++;
            const row = document.createElement('tr');
            row.setAttribute('data-row', rowCounter);
            row.dataset.productId = '';
            row.dataset.isCustom = '0'; // ✅ NEW
            row.className = 'sf-row-new';

            row.innerHTML = `
            <td class="sf-pcell" style="overflow:visible;position:relative;min-width:150px;"></td>
            <td><img src="${DEFAULT_IMG}" class="sf-img-thumb" alt=""></td>
            <td><input type="number" step="0.01" name="item_price[]"
                       class="form-control sale-price-input" value="0" min="0"></td>
            <td><input type="number" name="item_quantity[]"
                       class="form-control sale-qty-input" value="1" min="1"></td>
            <td><input type="number" name="item_discount[]"
                       class="form-control sale-disc-rs" value="0" min="0" step="0.01"
                       placeholder="₹" title="Discount in ₹ — auto calculates %"></td>
            <td><input type="number" name="item_discount_percent[]"
                       class="form-control sale-disc-pct" value="0" min="0" max="100" step="0.01"
                       placeholder="%" title="Discount % — auto calculates ₹"></td>
            <td><span class="sf-line-total">Rs. 0.00</span></td>
            <td><button type="button" class="sf-del-btn" title="Remove">
                    <i class='bx bx-trash'></i></button></td>`;

            tbody.appendChild(row);

            const { wrap, input, dd } = buildSelect(row);
            row._dropdown = dd;
            row.querySelector('.sf-pcell').appendChild(wrap);

            attachRowEvents(row, wrap, input, dd);

            if (focus) setTimeout(() => input.focus(), 50);
            refreshRow(row);
            return row;
        }

        /* ══════════════════════════════════════
           BARCODE SCANNER HANDLING (unchanged, with minor safety tweak)
        ══════════════════════════════════════ */
        const barcodeInput = document.getElementById('barcodeInput');
        const barcodeStatus = document.getElementById('barcodeStatus');

        function isDesktopDevice() {
            return !/Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent) && window.innerWidth > 1024;
        }

        const isDesktop = isDesktopDevice();

        function findRowByProduct(pid) {
            return [...tbody.querySelectorAll('tr[data-row]')]
                .find(r => r.dataset.productId === String(pid));
        }

        function addProductByBarcode(code) {
            const product = products.find(p => p.barcode && String(p.barcode).trim() === code.trim());

            if (!product) {
                sfAlert('Not Found', 'No product matches this barcode: ' + code, 'error');
                return;
            }
            if (+product.quantity <= 0) {
                sfAlert('Out of Stock', product.name + ' is out of stock.', 'warning');
                return;
            }

            let row = findRowByProduct(product.id);
            if (row) {
                const qtyEl = row.querySelector('.sale-qty-input');
                const newQty = Math.min((+qtyEl.value || 0) + 1, product.quantity);
                qtyEl.value = newQty;
                if (+row.querySelector('.sale-disc-rs').value > 0) syncDisc(row, 'rs');
                else if (+row.querySelector('.sale-disc-pct').value > 0) syncDisc(row, 'pct');
                else recalcSummary();
                billMgr.saveCurrent();
                return;
            }

            const rows = [...tbody.querySelectorAll('tr[data-row]')];
            // ✅ safety: never reuse a row currently in custom mode
            let targetRow = rows.find(r => !r.dataset.productId && r.dataset.isCustom !== '1') || addRow(false);
            if (!targetRow) return;

            targetRow.dataset.productId = product.id;
            const si = targetRow.querySelector('.sf-product-search');
            const hi = targetRow.querySelector('input[name="product_id[]"]');
            if (si) si.value = product.name;
            if (hi) hi.value = product.id;

            refreshRow(targetRow);
            billMgr.saveCurrent();
        }

        if (barcodeInput) {
            barcodeInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    e.stopPropagation();
                    const code = barcodeInput.value.trim();
                    if (code) addProductByBarcode(code);
                    barcodeInput.value = '';
                    barcodeInput.focus();
                }
            });

            if (isDesktop) {
                if (barcodeStatus) barcodeStatus.style.display = 'inline-flex';
                barcodeInput.focus();

                setInterval(() => {
                    const active = document.activeElement;
                    const isTyping = active &&
                        (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA') &&
                        active.id !== 'barcodeInput';
                    if (!isTyping) barcodeInput.focus();
                }, 1500);
            }
        }

        /* ══════════════════════════════════════
           GLOBAL KEYBOARD SHORTCUTS
        ══════════════════════════════════════ */
        document.addEventListener('keydown', function(e) {
            if (e.key !== 'Enter') return;
            const container = document.getElementById('triggerbtn');
            if (container.contains(document.activeElement)) {
                e.preventDefault();
                addRowBtn.click();
            }
        });

        document.addEventListener('keydown', e => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                saleForm.requestSubmit ? saleForm.requestSubmit() : saleForm.submit();
            }
        }, true);

        document.addEventListener('keydown', function(event) {
            const tag = document.activeElement.tagName.toLowerCase();
            if (tag === 'input' || tag === 'textarea') return;

            if (event.key.toLowerCase() === 'n') {
                event.preventDefault();
                document.getElementById('addBillBtn')?.click();
            }
            if (event.key.toLowerCase() === 'w') {
                event.preventDefault();
                document.querySelector('.sf-bill-tab.active .sf-bill-tab-close')?.click();
            }
        });

        addRowBtn.addEventListener('click', () => addRow(true));

        /* ══════════════════════════════════════
           CUSTOMER PHONE LOOKUP
        ══════════════════════════════════════ */
        let phoneTimer = null;
        custPhone.addEventListener('input', function() {
            clearTimeout(phoneTimer);
            const phone = this.value.replace(/\D/g, '');
            const msg = document.getElementById('customerLookupMessage');
            if (phone.length < 10) {
                msg.textContent = 'Enter 10+ digits to auto-fetch customer';
                return;
            }
            phoneTimer = setTimeout(() => {
                msg.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Looking up...';
                fetch(`${BASE_URL}sales/customer_lookup?phone=${encodeURIComponent(phone)}`)
                    .then(r => r.json())
                    .then(d => {
                        if (d.status === 'found') {
                            document.getElementById('customer_name').value = d.customer.name || '';
                            document.getElementById('customer_address').value = (d.customer.address || '').replace(/\r?\n/g, ' ');
                            msg.innerHTML = '<span style="color:#15803d">✓ Customer loaded</span>';
                        } else {
                            msg.innerHTML = '<span style="color:#f59e0b">New customer — will be registered on save</span>';
                        }
                    })
                    .catch(() => {
                        msg.innerHTML = '<span style="color:#ef4444">Lookup failed</span>';
                    });
            }, 500);
        });

        /* ══════════════════════════════════════
           FORM SUBMIT VALIDATION
        ══════════════════════════════════════ */
        /* ✅ MODIFIED: accepts custom rows too */
        saleForm.addEventListener('submit', e => {
            let valid = false;
            tbody.querySelectorAll('tr[data-row]').forEach(r => {
                const qty = +r.querySelector('.sale-qty-input').value;
                if (r.dataset.isCustom === '1') {
                    const nameVal = r.querySelector('.sf-custom-name-input')?.value.trim();
                    if (nameVal && qty > 0) valid = true;
                } else if (r.dataset.productId && qty > 0) {
                    valid = true;
                }
            });
            if (!valid) {
                e.preventDefault();
                sfAlert('No Items', 'Please add at least one product with quantity.', 'error');
                return;
            }
            isSubmitting = true;
            billMgr.removeFromStorage(billMgr.activeId);
        });

        /* ══════════════════════════════════════
           FIRST ROW (initialisation)
        ══════════════════════════════════════ */
        (function initFirstRow() {
            rowCounter++;
            const row = document.createElement('tr');
            row.setAttribute('data-row', rowCounter);
            row.dataset.productId = '';
            row.dataset.isCustom = '0'; // ✅ NEW

            row.innerHTML = `
            <td class="sf-pcell" style="overflow:visible;position:relative;min-width:150px;"></td>
            <td><img src="${DEFAULT_IMG}" class="sf-img-thumb" alt=""></td>
            <td><input type="number" step="0.01" name="item_price[]"
                       class="form-control sale-price-input" value="0" min="0"></td>
            <td><input type="number" name="item_quantity[]"
                       class="form-control sale-qty-input" value="1" min="1"></td>
            <td><input type="number" name="item_discount[]"
                       class="form-control sale-disc-rs" value="0" min="0" step="0.01"
                       placeholder="₹"></td>
            <td><input type="number" name="item_discount_percent[]"
                       class="form-control sale-disc-pct" value="0" min="0" max="100" step="0.01"
                       placeholder="%"></td>
            <td><span class="sf-line-total">Rs. 0.00</span></td>
            <td><button type="button" class="sf-del-btn" title="Remove">
                    <i class='bx bx-trash'></i></button></td>`;

            tbody.appendChild(row);

            const { wrap, input, dd } = buildSelect(row);
            row._dropdown = dd;
            row.querySelector('.sf-pcell').appendChild(wrap);

            attachRowEvents(row, wrap, input, dd);
            refreshRow(row);
        })();

        document.getElementById('sale_date').value = new Date().toISOString().split('T')[0];

        /* ══════════════════════════════════════
           BILL MANAGER
        ══════════════════════════════════════ */
        const billMgr = {
            isProcessing: false,
            bills: {},
            activeId: null,
            nextNum: 2,

            persistToStorage() {
                try {
                    localStorage.setItem(BILL_STORAGE_KEY, JSON.stringify({
                        bills: this.bills,
                        activeId: this.activeId,
                        nextNum: this.nextNum
                    }));
                } catch (e) {}
            },

            reindexBills() {
                const newBills = {};
                let count = 1;
                let newActiveId = null;
                Object.keys(this.bills).forEach(oldId => {
                    const bill = this.bills[oldId];
                    const newId = 'bill_' + count;
                    bill.id = newId;
                    bill.title = 'Bill ' + count;
                    newBills[newId] = bill;
                    if (oldId === this.activeId) newActiveId = newId;
                    count++;
                });
                this.bills = newBills;
                this.activeId = newActiveId;
                this.nextNum = count;
            },

            loadFromStorage() {
                try {
                    const raw = localStorage.getItem(BILL_STORAGE_KEY);
                    if (!raw) return false;
                    const data = JSON.parse(raw);
                    if (!data || !data.bills || !Object.keys(data.bills).length) return false;
                    this.bills = data.bills;
                    this.nextNum = data.nextNum || 2;
                    this.activeId = data.activeId;
                    if (!this.bills[this.activeId]) this.activeId = Object.keys(this.bills)[0];
                    return true;
                } catch (e) {
                    localStorage.removeItem(BILL_STORAGE_KEY);
                    return false;
                }
            },

            removeFromStorage(billId) {
                try {
                    const raw = localStorage.getItem(BILL_STORAGE_KEY);
                    if (!raw) return;
                    const data = JSON.parse(raw);
                    if (!data || !data.bills) return;
                    delete data.bills[billId];
                    if (Object.keys(data.bills).length === 0) {
                        localStorage.removeItem(BILL_STORAGE_KEY);
                    } else {
                        if (data.activeId === billId) data.activeId = Object.keys(data.bills)[0];
                        localStorage.setItem(BILL_STORAGE_KEY, JSON.stringify(data));
                    }
                } catch (e) {}
            },

            init() {
                const restored = this.loadFromStorage();
                if (restored) {
                    this.loadBill(this.activeId);
                } else {
                    this.bills['bill_1'] = { id: 'bill_1', title: 'Bill 1', saved: false };
                    this.activeId = 'bill_1';
                    this.renderTabs();
                }

                document.getElementById('addBillBtn').addEventListener('click', () => this.addNew());

                document.getElementById('customer_name').addEventListener('input', e => {
                    const el = document.querySelector('.sf-bill-tab.active .sf-bill-tab-title');
                    if (!el) return;
                    const n = e.target.value.trim();
                    const bill = this.bills[this.activeId];
                    el.textContent = n ?
                        (n.length > 15 ? n.substring(0, 15) + '…' : n) :
                        (bill?.title || 'Bill');
                });

                window.addEventListener('beforeunload', e => {
                    if (isSubmitting) return;
                    if (Object.keys(this.bills).length <= 1) return;
                    this.saveCurrent();
                    const hasAnyData = Object.values(this.bills).some(b =>
                        b.saved && (b.customer_name || b.customer_phone ||
                            (b.items && b.items.some(i => i.productId || i.isCustom)))
                    );
                    if (hasAnyData) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                });
            },

            /* ✅ MODIFIED: now captures isCustom + customName */
            serialize() {
                return {
                    customer_phone: custPhone.value,
                    customer_name: document.getElementById('customer_name').value,
                    customer_address: document.getElementById('customer_address').value,
                    sale_date: document.getElementById('sale_date').value,
                    items: [...tbody.querySelectorAll('tr[data-row]')].map(row => ({
                        productId: row.dataset.productId || '',
                        productName: row.querySelector('.sf-product-search')?.value || '',
                        isCustom: row.dataset.isCustom === '1',
                        customName: row.querySelector('.sf-custom-name-input')?.value || '',
                        price: row.querySelector('.sale-price-input').value,
                        qty: row.querySelector('.sale-qty-input').value,
                        discRs: row.querySelector('.sale-disc-rs').value,
                        discPct: row.querySelector('.sale-disc-pct').value
                    }))
                };
            },

            saveCurrent() {
                if (!this.activeId || !this.bills[this.activeId]) return;
                Object.assign(this.bills[this.activeId], this.serialize(), { saved: true });
                this.persistToStorage();
            },

            clearForm() {
                ddClose();
                tbody.querySelectorAll('tr[data-row]').forEach(row => {
                    if (row._dropdown?.parentNode) row._dropdown.parentNode.removeChild(row._dropdown);
                    row.remove();
                });
                custPhone.value = '';
                document.getElementById('customer_name').value = '';
                document.getElementById('customer_address').value = '';
                document.getElementById('sale_date').value = new Date().toISOString().split('T')[0];
                document.getElementById('customerLookupMessage').textContent =
                    'Enter phone to auto-fetch, or leave blank for walk-in sale';
            },

            /* ✅ MODIFIED: restores custom-item rows correctly */
            loadBill(billId) {
                const bill = this.bills[billId];
                if (!bill) return;

                this.clearForm();
                this.activeId = billId;

                if (bill.saved && bill.items && bill.items.length > 0) {
                    custPhone.value = bill.customer_phone || '';
                    document.getElementById('customer_name').value = bill.customer_name || '';
                    document.getElementById('customer_address').value = bill.customer_address || '';
                    document.getElementById('sale_date').value =
                        bill.sale_date || new Date().toISOString().split('T')[0];

                    isRestoringBill = true;
                    bill.items.forEach(item => {
                        const row = addRow(false);
                        if (!row) return;

                        if (item.isCustom) {
                            row.querySelector('.sf-custom-toggle-btn')?.click();
                            const nameInput = row.querySelector('.sf-custom-name-input');
                            if (nameInput) nameInput.value = item.customName || '';
                        } else if (item.productId) {
                            row.dataset.productId = item.productId;
                            const si = row.querySelector('.sf-product-search');
                            const hi = row.querySelector('input[name="product_id[]"]');
                            if (si) si.value = item.productName || '';
                            if (hi) hi.value = item.productId;

                            const product = getProduct(item.productId);
                            if (product) {
                                const img = row.querySelector('.sf-img-thumb');
                                img.src = product.image_url || DEFAULT_IMG;
                                img.onerror = function() { this.onerror = null; this.src = DEFAULT_IMG; };
                                row.querySelector('.sale-qty-input').max = product.quantity;
                            }
                        }

                        row.querySelector('.sale-price-input').value = item.price || 0;
                        row.querySelector('.sale-qty-input').value = item.qty || 1;
                        row.querySelector('.sale-disc-rs').value = item.discRs || 0;
                        row.querySelector('.sale-disc-pct').value = item.discPct || 0;
                    });
                    isRestoringBill = false;
                } else {
                    isRestoringBill = true;
                    addRow(false);
                    isRestoringBill = false;
                }

                recalcSummary();
                this.renderTabs();

                if (isDesktop && barcodeInput) {
                    setTimeout(() => barcodeInput.focus(), 100);
                }
            },

            switchTo(billId) {
                if (billId === this.activeId) return;
                this.saveCurrent();
                this.loadBill(billId);
            },

            addNew() {
                if (Object.keys(this.bills).length >= 10) {
                    sfAlert('Limit Reached', 'Maximum 10 bills at a time.', 'warning');
                    return;
                }
                this.saveCurrent();
                const id = 'bill_' + this.nextNum;
                this.bills[id] = { id, title: 'Bill ' + this.nextNum, saved: false };
                this.nextNum++;
                this.loadBill(id);
            },

            removeBill(billId) {
                if (this.isProcessing) return;
                this.isProcessing = true;

                const billIds = Object.keys(this.bills);
                if (billIds.length <= 1) {
                    this.isProcessing = false;
                    sfAlert('Cannot Close', 'At least one bill must remain open.', 'warning');
                    return;
                }

                requestAnimationFrame(() => {
                    const billIdsBefore = Object.keys(this.bills);
                    const index = billIdsBefore.indexOf(billId);
                    let nextActiveId = null;

                    if (billId === this.activeId) {
                        nextActiveId = index > 0 ? billIdsBefore[index - 1] : billIdsBefore[index + 1];
                    }

                    delete this.bills[billId];
                    this.reindexBills();

                    if (nextActiveId) {
                        const newIds = Object.keys(this.bills);
                        this.activeId = newIds[Math.max(0, Math.min(index - 1, newIds.length - 1))];
                    }

                    this.renderTabs();
                    this.loadBill(this.activeId);
                    this.persistToStorage();

                    setTimeout(() => { this.isProcessing = false; }, 80);
                });
            },

            renderTabs() {
                const container = document.getElementById('billTabsList');
                container.innerHTML = '';

                const badge = document.getElementById('billCountBadge');
                if (badge) badge.textContent = Object.keys(this.bills).length;

                const billIds = Object.keys(this.bills);
                const canClose = billIds.length > 1;

                billIds.forEach(id => {
                    const bill = this.bills[id];
                    const isActive = (id === this.activeId);
                    const hasData = bill.saved && (
                        bill.customer_name || bill.customer_phone ||
                        (bill.items && bill.items.some(i => i.productId || i.isCustom))
                    );
                    const name = (bill.saved && bill.customer_name?.trim()) || '';
                    const displayTitle = name ?
                        (name.length > 15 ? name.substring(0, 15) + '…' : name) :
                        bill.title;

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'sf-bill-tab' + (isActive ? ' active' : '');
                    btn.dataset.billId = id;

                    btn.innerHTML =
                        '<span class="sf-bill-tab-title">' + displayTitle + '</span>' +
                        (hasData ? '<span class="sf-bill-tab-dot"></span>' : '') +
                        (canClose ?
                            '<span class="sf-bill-tab-close" data-close-bill="' + id + '">×</span>' :
                            '');

                    btn.addEventListener('click', e => {
                        const closeId = e.target.dataset.closeBill;
                        if (closeId) {
                            e.stopPropagation();
                            this.removeBill(closeId);
                            return;
                        }
                        this.switchTo(id);
                    });

                    container.appendChild(btn);
                });
            }
            
        };

        billMgr.init();

        let autoSaveTimer = null;
        document.querySelector('.sf-body').addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                const hasValid = [...tbody.querySelectorAll('tr[data-row]')]
                    .some(r => (r.dataset.productId || r.dataset.isCustom === '1') && +r.querySelector('.sale-qty-input').value > 0);
                if (hasValid) billMgr.saveCurrent();
            }, 1500);
        }, true);

        console.log('%c✓ Sale form v7 ready — custom items + barcode + all fixes intact', 'color:#059669;font-weight:bold');

    });
</script>
    </div>
</div>