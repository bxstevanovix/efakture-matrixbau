@extends('_layouts.layout')

@section('head_title', $projekt->project_name)

@push('head_links')
<style>
    .project-header-card {
        --project-header-meta-height: 48px;
        border: 1px solid #eef0f6;
        box-shadow: 0 10px 26px rgba(35, 28, 58, .05);
    }

    .project-header-card .card-body {
        display: flex;
        align-items: stretch;
        justify-content: space-between;
        gap: 28px;
    }

    .project-header-left {
        flex: 1 1 auto;
        min-width: 0;
    }

    .project-header-side {
        width: min(100%, 560px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex: 0 0 560px;
    }

    .project-header-top-actions {
        width: 100%;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
    }

    .project-header-top-actions .btn {
        white-space: nowrap;
    }

    .project-header-main {
        max-width: 860px;
    }

    .project-header-meta {
        display: flex;
        align-items: stretch;
        gap: 10px;
        margin-top: 16px;
    }

    .project-header-meta .project-detail-stat {
        flex: 0 0 auto;
    }

    .project-header-meta .project-detail-id {
        width: 100px;
    }

    .project-header-meta .project-detail-address {
        width: min(100%, 400px);
    }

    .project-header-meta .project-detail-date {
        width: 108px;
    }

    .project-detail-stat {
        border: 1px solid #eef0f6;
        border-radius: 8px;
        padding: 12px;
        background: #ffffff;
        min-height: 72px;
    }

    .project-detail-stat span {
        display: block;
        color: #6e6b7b;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .project-detail-stat strong {
        color: #231c3a;
        font-size: 14px;
        font-weight: 600;
    }

    .project-header-meta .project-detail-stat {
        min-height: var(--project-header-meta-height);
        height: var(--project-header-meta-height);
        padding: 7px 10px;
    }

    .project-header-meta .project-detail-stat span {
        font-size: 11px;
        margin-bottom: 2px;
    }

    .project-header-meta .project-detail-stat strong {
        font-size: 13px;
    }

    .project-header-budget {
        min-height: var(--project-header-meta-height);
        height: 100%;
        flex: 0 0 200px;
        width: 200px;
        min-width: 0;
        border: 1px solid #eef0f6;
        border-radius: 8px;
        padding: 7px 10px;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: space-between;
    }

    .project-header-budget span {
        display: block;
        color: #6e6b7b;
        font-size: 11px;
        line-height: 1;
    }

    .project-header-budget strong {
        display: block;
        color: #231c3a;
        font-size: 16px;
        font-weight: 600;
        line-height: 1.1;
        margin-top: 8px;
    }

    .project-header-status-card {
        width: 150px;
        min-height: var(--project-header-meta-height);
        height: 100%;
        border: 1px solid #eef0f6;
        border-radius: 8px;
        padding: 7px 10px;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .project-header-side-bottom {
        width: 100%;
        min-height: var(--project-header-meta-height);
        height: var(--project-header-meta-height);
        display: flex;
        align-items: stretch;
        justify-content: flex-end;
        gap: 12px;
    }

    @media (min-width: 1200px) {
        .project-header-card .card-body {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(360px, 560px);
            grid-template-areas:
                "main actions"
                "meta side-bottom";
            column-gap: 28px;
            row-gap: 16px;
            align-items: start;
        }

        .project-header-left,
        .project-header-side {
            display: contents;
        }

        .project-header-main {
            grid-area: main;
            max-width: none;
        }

        .project-header-meta {
            grid-area: meta;
            margin-top: 0;
        }

        .project-header-top-actions {
            grid-area: actions;
            align-self: start;
        }

        .project-header-side-bottom {
            grid-area: side-bottom;
            align-self: stretch;
        }
    }

    .project-period-card {
        overflow: hidden;
        border: 1px solid #eef0f6;
        box-shadow: 0 8px 22px rgba(35, 28, 58, .04);
    }

    .project-period-row {
        display: flex;
        align-items: stretch;
        background: #ffffff;
    }

    .project-period-main {
        flex: 1;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        min-width: 0;
        cursor: pointer;
    }

    .project-period-main:hover {
        background: #fbfbfe;
    }

    .project-period-fields {
        min-width: 0;
    }

    .project-period-display {
        padding: 2px 4px;
    }

    .project-period-display-name {
        color: #231c3a;
        font-size: 18px;
        font-weight: 700;
        line-height: 1.25;
    }

    .project-period-display-description {
        display: block;
        color: #6e6b7b;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.4;
        margin-top: 4px;
    }

    .project-period-edit {
        display: none;
    }

    .project-period-card.is-editing .project-period-display {
        display: none;
    }

    .project-period-card.is-editing .project-period-edit {
        display: block;
    }

    .project-period-name-input,
    .project-period-description-input {
        width: 100%;
        border: 1px solid transparent;
        border-radius: 6px;
        background: transparent;
        color: #231c3a;
        padding: 2px 4px;
        outline: 0;
        transition: all .2s ease;
    }

    .project-period-name-input {
        font-size: 18px;
        font-weight: 700;
        line-height: 1.25;
    }

    .project-period-description-input {
        color: #6e6b7b;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.4;
        margin-top: 4px;
    }

    .project-period-name-input:focus,
    .project-period-description-input:focus {
        border-color: #d9d6ef;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(90, 69, 170, .08);
    }

    .project-period-summary {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }

    .project-period-money,
    .project-period-file-total {
        min-height: 34px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 700;
        line-height: 1;
        white-space: nowrap;
    }

    .project-period-money.is-income {
        color: #1f9d55;
        background: #ecfdf3;
    }

    .project-period-money.is-expense {
        color: #dc3545;
        background: #fff5f5;
    }

    .project-period-file-total {
        color: #5a45aa;
        background: #f3f0ff;
    }

    .project-period-delete {
        width: 48px;
        border: 0;
        border-left: 1px solid #eef0f6;
        background: #ffffff;
        color: #dc3545;
        transition: all .2s ease;
    }

    .project-period-delete:hover {
        background: #fff5f5;
        color: #bd2130;
    }

    .period-icon {
        width: 46px;
        height: 46px;
        border-radius: 8px;
        background: var(--rgba-primary-1);
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 46px;
        font-size: 18px;
    }

    .project-period-body {
        border-top: 1px solid #eef0f6;
        padding: 18px 20px 20px;
        background: #fcfcff;
    }

    .project-report-editor-wrap {
        margin-bottom: 18px;
        border: 1px solid #e9ecf4;
        border-radius: 8px;
        background: #ffffff;
        padding: 16px;
        box-shadow: 0 8px 20px rgba(35, 28, 58, .03);
    }

    .project-report-editor {
        min-height: 140px;
    }

    .project-report-editor-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .project-report-editor-title {
        display: flex;
        align-items: center;
        gap: 9px;
        min-width: 0;
    }

    .project-report-editor-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f5f6fb;
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 32px;
        font-size: 13px;
    }

    .project-report-editor-head h6 {
        color: #231c3a;
        font-size: 15px;
        font-weight: 700;
        margin: 0;
    }

    .project-report-editor-wrap .ck.ck-editor {
        --ck-border-radius: 8px;
        --ck-color-base-background: #ffffff;
        --ck-color-toolbar-background: #ffffff;
        --ck-color-base-border: #e7e9f2;
        --ck-color-focus-border: #bdb4ee;
        --ck-focus-ring: 0 0 0 3px rgba(90, 69, 170, .08);
        --ck-color-button-on-background: var(--rgba-primary-1);
        --ck-color-button-on-color: var(--primary);
        border-radius: 8px;
        width: 100%;
    }

    .project-report-editor-wrap .ck.ck-editor__top .ck-sticky-panel .ck-sticky-panel__content {
        border: 0;
    }

    .project-report-editor-wrap .ck.ck-editor__main {
        background: #ffffff;
    }

    .project-report-editor-wrap .ck.ck-toolbar {
        border: 1px solid #e7e9f2;
        border-bottom: 0;
        border-radius: 8px 8px 0 0;
        background: #ffffff;
        padding: 8px 11px;
        box-shadow: none;
    }

    .project-report-editor-wrap .ck.ck-toolbar .ck-toolbar__separator {
        background: #e7e9f2;
        margin-inline: 6px;
    }

    .project-report-editor-wrap .ck.ck-button,
    .project-report-editor-wrap .ck.ck-dropdown__button {
        border-radius: 6px;
        color: #4f4a5f;
        min-height: 30px;
        min-width: 30px;
        cursor: pointer;
    }

    .project-report-editor-wrap .ck.ck-button:hover,
    .project-report-editor-wrap .ck.ck-dropdown__button:hover {
        background: #f4f2ff;
        color: var(--primary);
    }

    .project-report-editor-wrap .ck.ck-button.ck-on,
    .project-report-editor-wrap .ck.ck-dropdown__button.ck-on {
        background: var(--rgba-primary-1);
        color: var(--primary);
    }

    .project-report-editor-wrap .ck.ck-dropdown__panel {
        border-color: #e7e9f2;
        border-radius: 8px;
        box-shadow: 0 12px 28px rgba(35, 28, 58, .12);
    }

    .project-report-editor-wrap .ck.ck-editor__editable_inline {
        min-height: 220px;
        border: 1px solid #e7e9f2;
        border-radius: 0 0 8px 8px;
        background: #ffffff;
        color: #231c3a;
        font-size: 14px;
        line-height: 1.65;
        padding: 20px 22px;
        box-shadow: none;
    }

    .project-report-editor-wrap .ck.ck-editor__editable_inline.ck-focused {
        border-color: #bdb4ee;
        box-shadow: 0 0 0 3px rgba(90, 69, 170, .08);
    }

    .project-report-editor-wrap .ck.ck-editor__editable_inline.ck-blurred {
        border-color: #e7e9f2;
    }

    .project-report-editor-wrap .ck.ck-editor__editable_inline > :first-child {
        margin-top: 0;
    }

    .project-report-editor-wrap .ck.ck-placeholder::before,
    .project-report-editor-wrap .ck .ck-placeholder::before {
        color: #9b98a8;
        font-style: normal;
    }

    .project-report-editor-wrap .ck-content p {
        margin-bottom: 10px;
    }

    .project-report-editor-wrap .ck-content p:last-child {
        margin-bottom: 0;
    }

    .project-period-empty {
        border: 1px dashed #d8dce8;
        border-radius: 8px;
        padding: 14px;
        color: #6e6b7b;
        background: #ffffff;
    }

    .project-file-panel {
        border: 1px solid #eef0f6;
        border-radius: 8px;
        background: #ffffff;
        padding: 12px;
        min-height: 100%;
    }

    .project-file-list {
        display: grid;
        gap: 8px;
    }

    .project-file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        border: 1px solid #eef0f6;
        border-radius: 8px;
        padding: 9px 10px;
        background: #fbfbfe;
        min-width: 0;
        max-width: 100%;
        overflow: hidden;
    }

    .project-file-link {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1 1 auto;
        min-width: 0;
        max-width: 100%;
        color: #231c3a;
        font-weight: 600;
    }

    .project-file-link i {
        flex: 0 0 auto;
    }

    .project-file-link span {
        display: block;
        min-width: 0;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .project-file-meta {
        flex: 0 0 auto;
        color: #6e6b7b;
        font-size: 12px;
        white-space: nowrap;
    }

    .project-file-delete {
        border: 0;
        background: transparent;
        color: #dc3545;
        padding: 0;
        line-height: 1;
    }

    .project-file-empty {
        padding: 12px;
        font-size: 13px;
    }

    .project-detail-card-header {
        gap: 12px;
    }

    .project-detail-sidebar {
        align-self: flex-start;
    }

    .project-detail-sidebar > .card + .card {
        margin-top: 16px;
    }

    .project-status-actions {
        margin-top: 0;
        padding-top: 0;
    }

    .project-status-buttons {
        display: flex;
        align-items: stretch;
        gap: 10px;
    }

    .project-status-control {
        flex: 1 1 0;
        min-height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .project-documents-panel {
        margin-top: 0;
        padding-top: 0;
    }

    .project-documents-panel h6 {
        color: #231c3a;
        font-size: 15px;
        font-weight: 700;
    }

    .project-documents-list {
        display: grid;
        gap: 8px;
        max-height: 240px;
        overflow-y: auto;
        padding-right: 2px;
    }

    .project-document-delete {
        flex: 0 0 auto;
        border: 0;
        background: transparent;
        color: #dc3545;
        padding: 0;
        line-height: 1;
    }

    .project-documents-empty {
        border: 1px dashed #d8dce8;
        border-radius: 8px;
        padding: 12px;
        color: #6e6b7b;
        background: #ffffff;
        font-size: 13px;
    }

    .project-budget-panel {
        margin-top: 0;
        padding-top: 0;
    }

    .project-budget-panel h6 {
        color: #231c3a;
        font-size: 15px;
        font-weight: 700;
    }

    .project-budget-plan {
        border: 1px solid #eef0f6;
        border-radius: 8px;
        padding: 10px;
        background: #fbfbfe;
    }

    .project-budget-plan label {
        color: #6e6b7b;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .project-budget-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        margin-top: 10px;
    }

    .project-budget-stat {
        border: 1px solid #eef0f6;
        border-radius: 8px;
        padding: 9px 10px;
        background: #ffffff;
    }

    .project-budget-stat span {
        display: block;
        color: #6e6b7b;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .project-budget-stat strong {
        color: #231c3a;
        font-size: 14px;
        font-weight: 700;
        word-break: break-word;
    }

    .project-budget-stat.is-income strong {
        color: #1f9d55;
    }

    .project-budget-stat.is-expense strong {
        color: #dc3545;
    }

    .project-budget-stat.is-remaining {
        grid-column: 1 / -1;
    }

    .project-budget-stat.is-balance strong.is-negative,
    .project-budget-stat.is-remaining strong.is-negative {
        color: #dc3545;
    }

    .project-budget-alert {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        border-radius: 8px;
        padding: 10px 12px;
        margin-top: 12px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.45;
    }

    .project-budget-alert i {
        margin-top: 2px;
    }

    .project-budget-alert.is-info {
        border: 1px solid #e3e5ee;
        background: #f8f8fb;
        color: #6e6b7b;
    }

    .project-budget-alert.is-warning {
        border: 1px solid #ffe3a3;
        background: #fff8e6;
        color: #946200;
    }

    .project-budget-alert.is-danger {
        border: 1px solid #ffd0d0;
        background: #fff5f5;
        color: #bd2130;
    }

    .project-budget-usage {
        margin-top: 12px;
    }

    .project-budget-list {
        display: grid;
        gap: 8px;
        margin-top: 12px;
        max-height: 330px;
        overflow-y: auto;
        padding-right: 2px;
    }

    .project-budget-item {
        border: 1px solid #eef0f6;
        border-radius: 8px;
        padding: 10px;
        background: #ffffff;
    }

    .project-budget-item-main {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .project-budget-item-title {
        min-width: 0;
    }

    .project-budget-item-title strong {
        display: block;
        color: #231c3a;
        font-size: 13px;
        line-height: 1.25;
    }

    .project-budget-item-title span {
        display: block;
        color: #6e6b7b;
        font-size: 11px;
        margin-top: 2px;
    }

    .project-budget-item-amount {
        text-align: right;
        white-space: nowrap;
    }

    .project-budget-item-amount strong {
        display: block;
        font-size: 13px;
        font-weight: 700;
    }

    .project-budget-item-amount .income {
        color: #1f9d55;
    }

    .project-budget-item-amount .expense {
        color: #dc3545;
    }

    .project-budget-delete {
        border: 0;
        background: transparent;
        color: #dc3545;
        padding: 0;
        line-height: 1;
    }

    .project-budget-meta {
        color: #8a8796;
        font-size: 11px;
        margin-top: 7px;
    }

    .project-budget-empty {
        border: 1px dashed #d8dce8;
        border-radius: 8px;
        padding: 12px;
        color: #6e6b7b;
        background: #ffffff;
        font-size: 13px;
    }

    .project-period-budget {
        border: 1px solid #eef0f6;
        border-radius: 8px;
        background: #ffffff;
        padding: 12px;
        margin-top: 18px;
    }

    .project-period-budget .project-budget-list {
        max-height: none;
        overflow: visible;
        margin-top: 10px;
        padding-right: 0;
    }

    @media (max-width: 1199.98px) {
        .project-header-card .card-body {
            flex-direction: column;
        }

        .project-header-side {
            width: 100%;
            flex-basis: auto;
        }

        .project-header-meta {
            flex-wrap: wrap;
        }

        .project-header-top-actions {
            justify-content: flex-end;
            flex-wrap: wrap;
        }
    }

    @media (max-width: 575.98px) {
        .project-header-meta {
            display: grid;
            grid-template-columns: 1fr;
        }

        .project-header-meta .project-detail-id,
        .project-header-meta .project-detail-address,
        .project-header-meta .project-detail-date {
            width: 100%;
        }

        .project-header-top-actions {
            justify-content: flex-end;
        }

        .project-header-side-bottom {
            flex-direction: column;
        }

        .project-header-budget {
            width: 100%;
            flex-basis: auto;
        }

        .project-header-status-card {
            width: 100%;
        }

        .project-status-buttons {
            flex-direction: column;
        }

        .project-period-main {
            padding: 16px;
        }

        .project-period-delete {
            width: 44px;
        }
    }
</style>
@endpush

@push('head_scripts')
<style>
    .project-report-editor-wrap {
        background: #ffffff !important;
        border: 1px solid #dde2ec !important;
        border-radius: 8px !important;
        padding: 0 !important;
        overflow: hidden !important;
        box-shadow: none !important;
        display: grid !important;
        grid-template-columns: auto minmax(0, 1fr) !important;
        grid-template-areas:
            "report-head report-toolbar"
            "report-editor report-editor" !important;
        align-items: stretch !important;
    }

    .project-report-editor-head {
        grid-area: report-head !important;
        margin: 0 !important;
        padding: 14px 16px !important;
        border-bottom: 1px solid #eef0f6 !important;
        background: #ffffff !important;
        display: flex !important;
        align-items: center !important;
    }

    .project-report-editor-icon {
        background: #f3f4f8 !important;
        color: #5A45AA !important;
    }

    .project-report-editor-head h6 {
        font-size: 15px !important;
        font-weight: 700 !important;
        color: #231c3a !important;
    }

    .project-report-editor-wrap .ck.ck-editor {
        display: contents !important;
        width: 100% !important;
        border: 0 !important;
        border-radius: 0 !important;
        background: #ffffff !important;
    }

    .project-report-editor-wrap .ck.ck-editor__top,
    .project-report-editor-wrap .ck.ck-sticky-panel,
    .project-report-editor-wrap .ck.ck-sticky-panel__content {
        border: 0 !important;
        background: #ffffff !important;
        box-shadow: none !important;
        min-width: 0 !important;
        width: auto !important;
    }

    .project-report-editor-wrap .ck.ck-editor__top {
        grid-area: report-toolbar !important;
        align-self: stretch !important;
        display: flex !important;
        align-items: center !important;
    }

    .project-report-editor-wrap .ck.ck-toolbar {
        border: 0 !important;
        border-bottom: 1px solid #eef0f6 !important;
        border-radius: 0 !important;
        background: #ffffff !important;
        padding: 8px 12px !important;
        box-shadow: none !important;
        min-height: 100% !important;
        align-items: center !important;
    }

    .project-report-editor-wrap .ck.ck-toolbar__items {
        gap: 2px !important;
    }

    .project-report-editor-wrap .ck.ck-toolbar .ck-toolbar__separator {
        background: #e5e8f0 !important;
        margin: 0 7px !important;
    }

    .project-report-editor-wrap .ck.ck-button,
    .project-report-editor-wrap .ck.ck-dropdown__button {
        min-width: 31px !important;
        min-height: 31px !important;
        border-radius: 6px !important;
        color: #4f4a5f !important;
        background: transparent !important;
        box-shadow: none !important;
    }

    .project-report-editor-wrap .ck.ck-button:hover,
    .project-report-editor-wrap .ck.ck-dropdown__button:hover,
    .project-report-editor-wrap .ck.ck-button.ck-on,
    .project-report-editor-wrap .ck.ck-dropdown__button.ck-on {
        background: #f1effb !important;
        color: #5A45AA !important;
    }

    .project-report-editor-wrap .ck.ck-editor__main {
        grid-area: report-editor !important;
        background: #ffffff !important;
    }

    .project-report-editor-wrap .ck.ck-content,
    .project-report-editor-wrap .ck.ck-editor__editable,
    .project-report-editor-wrap .ck.ck-editor__editable_inline {
        min-height: 230px !important;
        border: 0 !important;
        border-radius: 0 !important;
        background: #ffffff !important;
        color: #231c3a !important;
        font-size: 14px !important;
        line-height: 1.7 !important;
        padding: 20px 22px !important;
        box-shadow: none !important;
    }

    .project-report-editor-wrap .ck.ck-editor__editable.ck-focused,
    .project-report-editor-wrap .ck.ck-editor__editable_inline.ck-focused {
        background: #ffffff !important;
        box-shadow: inset 0 0 0 2px rgba(90, 69, 170, .18) !important;
    }

    .project-report-editor-wrap .ck-placeholder::before {
        color: #9b98a8 !important;
    }

    .project-report-editor-wrap .ck.ck-editor__editable_inline ul,
    .project-report-editor-wrap .ck.ck-editor__editable_inline ol,
    .project-report-editor-wrap .ck.ck-content ul,
    .project-report-editor-wrap .ck.ck-content ol {
        margin: 0 0 12px 0 !important;
        padding-left: 28px !important;
    }

    .project-report-editor-wrap .ck.ck-editor__editable_inline ul,
    .project-report-editor-wrap .ck.ck-content ul {
        list-style: disc outside !important;
        list-style-type: disc !important;
        list-style-position: outside !important;
    }

    .project-report-editor-wrap .ck.ck-editor__editable_inline ol,
    .project-report-editor-wrap .ck.ck-content ol {
        list-style: decimal outside !important;
        list-style-type: decimal !important;
        list-style-position: outside !important;
    }

    .project-report-editor-wrap .ck.ck-editor__editable_inline ul > li,
    .project-report-editor-wrap .ck.ck-content ul > li {
        list-style: disc outside !important;
        list-style-type: disc !important;
    }

    .project-report-editor-wrap .ck.ck-editor__editable_inline ol > li,
    .project-report-editor-wrap .ck.ck-content ol > li {
        list-style: decimal outside !important;
        list-style-type: decimal !important;
    }

    .project-report-editor-wrap .ck.ck-editor__editable_inline li,
    .project-report-editor-wrap .ck.ck-content li {
        display: list-item !important;
        margin: 4px 0 !important;
        padding-left: 3px !important;
    }

    .project-report-editor-wrap .ck.ck-editor__editable_inline li::before,
    .project-report-editor-wrap .ck.ck-content li::before {
        content: normal !important;
        display: revert !important;
    }

    .project-report-editor-wrap .ck.ck-editor__editable_inline li::marker,
    .project-report-editor-wrap .ck.ck-content li::marker {
        color: #5A45AA !important;
        font-weight: 600 !important;
    }
</style>
@endpush

@section('content')
@php
    $progress = $projekt->timeline_progress;
    $isProjectClosed = in_array($projekt->timeline_status, ['completed', 'cancelled'], true);
    $isProjectCancelled = $projekt->timeline_status === 'cancelled';
    $statusLabels = [
        'Pending' => 'Na čekanju',
        'In Progress' => 'U toku',
        'Done' => 'Završeno',
        'Cancelled' => 'Otkazano',
    ];
    $displayStatusLabel = $statusLabels[$statusLabel] ?? $statusLabel;
    $projectCategoryTranslationLookup = [
        'Ostalo' => __('Ostalo'),
        'Materijal' => __('Materijal'),
        'Rad' => __('Rad'),
        'Transport' => __('Transport'),
        'Oprema' => __('Oprema'),
        'Administracija' => __('Administracija'),
    ];
@endphp

<div class="card project-header-card">
    <div class="card-body">
        <div class="project-header-left">
            <div class="project-header-main">
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <h3 class="mb-0">{{ $projekt->project_name }}</h3>
                    </div>
                    @if($projekt->description)
                        <p class="mb-0 text-muted">{{ $projekt->description }}</p>
                    @endif
                </div>
            </div>

            <div class="project-header-meta">
                <div class="project-detail-stat project-detail-id">
                    <span>{{ __('ID projekta') }}</span>
                    <strong>#{{ $projekt->id }}</strong>
                </div>
                <div class="project-detail-stat project-detail-address">
                    <span>{{ __('Adresa') }}</span>
                    <strong>{{ $projectAddress ?: '-' }}</strong>
                </div>
                <div class="project-detail-stat project-detail-date">
                    <span>{{ __('Početak') }}</span>
                    <strong>{{ $projekt->start_date?->format('d M Y') ?? '-' }}</strong>
                </div>
                <div class="project-detail-stat project-detail-date">
                    <span>{{ __('Završetak') }}</span>
                    <strong>{{ $projekt->end_date?->format('d M Y') ?? '-' }}</strong>
                </div>
            </div>
        </div>

	        <div class="project-header-side">
	            <div class="project-header-top-actions">
	                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProjectModal">
	                    <i class="fa fa-pencil me-1"></i> {{ __('Uredi') }}
	                </button>
	                {{-- <a href="{{ route('projekti.index') }}" class="btn btn-light btn-sm">
	                    <i class="fa fa-arrow-left me-1"></i> {{ __('Nazad na projekte') }}
	                </a> --}}
	            </div>
	            <div class="project-header-side-bottom">
	                <div class="project-header-budget">
	                    <span>{{ __('Planirani budžet') }}</span>
	                    <strong data-header-budget-value>{{ $budgetSummary['planned_formatted'] }}</strong>
	                </div>
	                <div class="project-header-status-card">
	                    <span class="badge {{ $badgeClass }} light project-header-status">{{ __($displayStatusLabel) }}</span>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

<div class="row">
    <div class="col-xl-8">
        <div id="projectPeriodsAccordion">
            @foreach($periods as $period)
                <div class="card project-period-card" data-record-id="{{ $period['id'] }}" data-period-key="{{ $period['key'] }}">
                    <div class="project-period-row">
                        <div class="project-period-main">
                            <span class="period-icon me-3">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <div class="project-period-fields flex-grow-1 me-3">
                                <div class="project-period-display">
                                    <h5 class="mb-1 project-period-display-name">{{ $period['label'] }}</h5>
                                    <span class="project-period-display-description">{{ $period['description'] }}</span>
                                </div>
                                <div class="project-period-edit">
                                    <input type="text" class="project-period-name-input" name="period_meta[{{ $period['key'] }}][name]" value="{{ $period['label'] }}" aria-label="{{ __('Naziv zapisa') }}">
                                    <input type="text" class="project-period-description-input" name="period_meta[{{ $period['key'] }}][description]" value="{{ $period['description'] }}" placeholder="{{ __('Opis zapisa') }}" aria-label="{{ __('Opis zapisa') }}">
                                </div>
                            </div>
                            <div class="project-period-summary d-none d-md-flex me-3">
                                @if(($period['budget_income'] ?? 0) > 0)
                                    <span class="project-period-money is-income" data-period-income>
                                        + {{ $period['budget_income_formatted'] }}
                                    </span>
                                @endif
                                @if(($period['budget_expense'] ?? 0) > 0)
                                    <span class="project-period-money is-expense" data-period-expense>
                                        - {{ $period['budget_expense_formatted'] }}
                                    </span>
                                @endif
                                <span class="project-period-file-total" title="{{ __('Ukupno dokumenata i računa') }}">
                                    <i class="fa fa-folder-open"></i>
                                    <span data-summary-file-total>{{ $period['file_count'] ?? (count($period['documents']) + count($period['invoices'])) }}</span>
                                </span>
                            </div>
                        </div>
                        <button type="button" class="project-period-delete" title="{{ __('Obriši zapis') }}" data-period-label="{{ $period['label'] }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div id="period{{ $period['key'] }}" class="collapse" data-bs-parent="#projectPeriodsAccordion">
                        <div class="project-period-body">
                            <div class="project-report-editor-wrap">
                                <div class="project-report-editor-head">
                                    <div class="project-report-editor-title">
                                        <span class="project-report-editor-icon">
                                            <i class="fa fa-pen"></i>
                                        </span>
                                        <h6>{{ __('Izvještaj') }}</h6>
                                    </div>
                                </div>
                                <textarea class="form-control project-report-editor" id="period_report_{{ $period['key'] }}" name="period_reports[{{ $period['key'] }}]" rows="5" placeholder="{{ __('Upišite opis radova, izvještaj, napomene ili dogovor za ovaj mjesec.') }}">{{ $period['report'] }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="project-file-panel">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">{{ __('Dokumenti') }}</h6>
                                            <span class="badge badge-primary light" data-file-count="document">{{ count($period['documents']) }}</span>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-xs w-100 mb-2 project-file-upload" data-file-type="document" {{ empty($period['id']) ? 'disabled' : '' }}>
                                            <i class="fa fa-upload me-1"></i> {{ __('Dodaj dokument') }}
                                        </button>
                                        <div class="project-file-list" data-file-list="document">
                                            @forelse($period['documents'] as $file)
                                                <div class="project-file-item" data-file-id="{{ $file['id'] }}">
                                                    <a href="{{ $file['url'] }}" target="_blank" class="project-file-link">
                                                        <i class="fa fa-file"></i>
                                                        <span>{{ $file['name'] }}</span>
                                                    </a>
                                                    <span class="project-file-meta">{{ $file['size'] }}</span>
                                                    <button type="button" class="project-file-delete" title="{{ __('Obriši dokument') }}" data-file-id="{{ $file['id'] }}" data-file-name="{{ $file['name'] }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            @empty
                                                <div class="project-period-empty project-file-empty" data-file-empty="document">
                                                    <i class="fa fa-folder-open me-1"></i>
                                                    {{ __('Nema dokumenata.') }}
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="project-file-panel">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">{{ __('Računi') }}</h6>
                                            <span class="badge badge-success light" data-file-count="invoice">{{ count($period['invoices']) }}</span>
                                        </div>
                                        <button type="button" class="btn btn-success btn-xs w-100 mb-2 project-file-upload" data-file-type="invoice" {{ empty($period['id']) ? 'disabled' : '' }}>
                                            <i class="fa fa-upload me-1"></i> {{ __('Dodaj račun') }}
                                        </button>
                                        <div class="project-file-list" data-file-list="invoice">
                                            @forelse($period['invoices'] as $file)
                                                <div class="project-file-item" data-file-id="{{ $file['id'] }}">
                                                    <a href="{{ $file['url'] }}" target="_blank" class="project-file-link">
                                                        <i class="fa fa-file-invoice"></i>
                                                        <span>{{ $file['name'] }}</span>
                                                    </a>
                                                    <span class="project-file-meta">{{ $file['size'] }}</span>
                                                    <button type="button" class="project-file-delete" title="{{ __('Obriši račun') }}" data-file-id="{{ $file['id'] }}" data-file-name="{{ $file['name'] }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            @empty
                                                <div class="project-period-empty project-file-empty" data-file-empty="invoice">
                                                    <i class="fa fa-file-invoice me-1"></i>
                                                    {{ __('Nema računa.') }}
                                                </div>
	                                            @endforelse
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="project-period-budget">
	                                <div class="d-flex justify-content-between align-items-center gap-2">
	                                    <div>
	                                        <h6 class="mb-0">{{ __('Budžet zapisa') }}</h6>
	                                        <small class="text-muted">{{ __('Prihodi i troškovi za ovaj period') }}</small>
	                                    </div>
	                                    <button type="button" class="btn btn-primary btn-xs project-budget-add" data-record-id="{{ $period['id'] }}" data-bs-toggle="modal" data-bs-target="#projectBudgetItemModal" {{ $budgetSummary['available'] && !empty($period['id']) ? '' : 'disabled' }}>
	                                        <i class="fa fa-plus me-1"></i> {{ __('Stavka') }}
	                                    </button>
	                                </div>

	                                @if(!$budgetSummary['available'])
	                                    <div class="project-budget-empty mt-2">
	                                        {{ __('Tabela za budžet nije kreirana. Potrebno je pokrenuti migracije.') }}
	                                    </div>
	                                @else
	                                    <div class="project-budget-list" data-budget-list>
	                                        @forelse($period['budget_items'] as $budgetItem)
	                                            <div class="project-budget-item" data-budget-item-id="{{ $budgetItem['id'] }}" data-budget-type="{{ $budgetItem['type'] }}" data-budget-amount="{{ $budgetItem['amount'] }}">
	                                                <div class="project-budget-item-main">
	                                                    <div class="project-budget-item-title">
	                                                        <strong>{{ $budgetItem['description'] ?: __($budgetItem['category_label']) }}</strong>
	                                                        <span>{{ __($budgetItem['category_label']) }} · {{ $budgetItem['date_label'] }}</span>
	                                                    </div>
	                                                    <div class="project-budget-item-amount">
	                                                        <strong class="{{ $budgetItem['type'] }}">{{ $budgetItem['type'] === 'income' ? '+' : '-' }} {{ $budgetItem['amount_formatted'] }}</strong>
	                                                        <button type="button" class="project-budget-delete" title="{{ __('Obriši stavku') }}" data-budget-item-id="{{ $budgetItem['id'] }}" data-budget-item-label="{{ $budgetItem['description'] ?: __($budgetItem['category_label']) }}">
	                                                            <i class="fa fa-trash"></i>
	                                                        </button>
	                                                    </div>
	                                                </div>
	                                            </div>
	                                        @empty
	                                            <div class="project-budget-empty" data-budget-empty>
	                                                {{ __('Još nema budžetskih stavki za ovaj zapis.') }}
	                                            </div>
	                                        @endforelse
	                                    </div>
	                                @endif
	                            </div>
	                        </div>
	                    </div>
	                </div>
            @endforeach
        </div>
    </div>

    <div class="col-xl-4 project-detail-sidebar">
        <button type="button" class="btn btn-primary btn-sm w-100 mb-3" id="addProjectPeriod">
            <i class="fa fa-plus me-1"></i> {{ __('Novi zapis') }}
        </button>

        <div class="card">
            <div class="card-header pb-0 border-0 project-detail-card-header">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <h5 class="mb-0">{{ __('Progres') }}</h5>
                        <small>{{ __('Vremenski tok projekta') }}</small>
                    </div>
                    <span class="fw-semibold text-black">{{ $progress }}%</span>
                </div>
            </div>
            <div class="card-body">
                <div class="progress">
                    <div class="progress-bar {{ $progressClass }} progress-animated" style="width: {{ $progress }}%; height:8px;" role="progressbar"></div>
                </div>
            </div>
        </div>

        <div class="card project-documents-panel">
            <div class="card-header pb-0 border-0 project-detail-card-header">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <h5 class="mb-0">{{ __('Projektna dokumentacija') }}</h5>
                        <small>{{ __('Glavni dokumenti projekta') }}</small>
                    </div>
                    <span class="badge badge-primary light" data-project-document-count>{{ count($projectDocuments) }}</span>
                </div>
            </div>
            <div class="card-body">
                @if(!$projectDocumentsAvailable)
                    <div class="project-documents-empty">
                        {{ __('Tabela za projektnu dokumentaciju nije kreirana. Potrebno je pokrenuti migracije.') }}
                    </div>
                @else
                    <button type="button" class="btn btn-primary btn-xs w-100 mb-2 project-document-upload" id="projectDocumentUploadButton">
                        <i class="fa fa-upload me-1"></i> {{ __('Dodaj dokumente') }}
                    </button>
                    <div class="project-documents-list" data-project-document-list>
                        @forelse($projectDocuments as $document)
                            <div class="project-file-item" data-project-document-id="{{ $document['id'] }}">
                                <a href="{{ $document['url'] }}" target="_blank" class="project-file-link">
                                    <i class="fa fa-file"></i>
                                    <span>{{ $document['name'] }}</span>
                                </a>
                                <span class="project-file-meta">{{ $document['size'] }}</span>
                                <button type="button" class="project-document-delete" title="{{ __('Obriši dokument') }}" data-document-id="{{ $document['id'] }}" data-document-name="{{ $document['name'] }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        @empty
                            <div class="project-documents-empty" data-project-document-empty>
                                <i class="fa fa-folder-open me-1"></i>
                                {{ __('Nema projektne dokumentacije.') }}
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>
        </div>

        <div class="card project-budget-panel">
            <div class="card-header pb-0 border-0 project-detail-card-header">
                <div>
                    <h5 class="mb-0">{{ __('Budžet') }}</h5>
                    <small>{{ __('Prihodi, troškovi i razlika') }}</small>
                </div>
            </div>
            <div class="card-body">
                @if(!$budgetSummary['available'])
                    <div class="project-budget-empty">
                        {{ __('Tabela za budžet nije kreirana. Potrebno je pokrenuti migracije.') }}
                    </div>
                @else
                    @php
                        $budgetAlertType = null;
                        $budgetAlertIcon = 'fa-info-circle';
                        $budgetAlertMessage = null;

                        if (($budgetSummary['planned'] ?? 0) <= 0) {
                            $budgetAlertType = 'info';
                            $budgetAlertMessage = __('Planirani budžet nije postavljen.');
                        } elseif (($budgetSummary['expense_usage'] ?? 0) >= 100) {
                            $budgetAlertType = 'danger';
                            $budgetAlertIcon = 'fa-exclamation-triangle';
                            $budgetAlertMessage = ($budgetSummary['remaining'] ?? 0) < 0
                                ? __('Budžet je prekoračen za :amount', ['amount' => number_format(abs((float) $budgetSummary['remaining']), 2, ',', '.') . ' €'])
                                : __('Budžet je u potpunosti iskorišten.');
                        } elseif (($budgetSummary['expense_usage'] ?? 0) >= 80) {
                            $budgetAlertType = 'warning';
                            $budgetAlertIcon = 'fa-exclamation-triangle';
                            $budgetAlertMessage = __('Budžet je skoro potrošen. Preostalo: :amount', ['amount' => $budgetSummary['remaining_formatted']]);
                        }
                    @endphp

                    <div class="project-budget-stats">
                        <div class="project-budget-stat">
                            <span>{{ __('Planirano') }}</span>
                            <strong data-budget-value="planned">{{ $budgetSummary['planned_formatted'] }}</strong>
                        </div>
                        <div class="project-budget-stat is-income">
                            <span>{{ __('Prihodi') }}</span>
                            <strong data-budget-value="income">{{ $budgetSummary['income_formatted'] }}</strong>
                        </div>
                        <div class="project-budget-stat is-expense">
                            <span>{{ __('Troškovi') }}</span>
                            <strong data-budget-value="expense">{{ $budgetSummary['expense_formatted'] }}</strong>
                        </div>
                        <div class="project-budget-stat is-balance">
                            <span>{{ __('Razlika') }}</span>
                            <strong class="{{ $budgetSummary['balance'] < 0 ? 'is-negative' : '' }}" data-budget-value="balance">{{ $budgetSummary['balance_formatted'] }}</strong>
                        </div>
                        <div class="project-budget-stat is-remaining">
                            <span>{{ __('Preostalo') }}</span>
                            <strong class="{{ $budgetSummary['remaining'] < 0 ? 'is-negative' : '' }}" data-budget-value="remaining">{{ $budgetSummary['remaining_formatted'] }}</strong>
                        </div>
                    </div>

                    <div class="project-budget-alert {{ $budgetAlertType ? 'is-' . $budgetAlertType : 'd-none' }}" data-budget-alert>
                        <i class="fa {{ $budgetAlertIcon }}" data-budget-alert-icon></i>
                        <span data-budget-alert-message>{{ $budgetAlertMessage }}</span>
                    </div>

                    <div class="project-budget-usage">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ __('Iskorišteno budžeta') }}</small>
                            <small class="fw-semibold" data-budget-value="usage">{{ $budgetSummary['expense_usage'] }}%</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" id="projectBudgetUsageBar" style="width: {{ $budgetSummary['expense_usage'] }}%; height:8px;" role="progressbar"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card project-status-actions">
            <div class="card-header pb-0 border-0 project-detail-card-header">
                <div>
                    <h5 class="mb-0">{{ __('Upravljanje projektom') }}</h5>
                    <small>{{ __('Završavanje i otkazivanje projekta') }}</small>
                </div>
            </div>
            <div class="card-body">
                <div class="project-status-buttons">
                    <button type="button" class="btn btn-success btn-sm project-status-control" data-bs-toggle="modal" data-bs-target="#finishProjectModal" {{ $isProjectClosed ? 'disabled' : '' }}>
                        <i class="fa fa-check me-1"></i> {{ __('Završi projekat') }}
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm project-status-control" id="cancelProjectButton" {{ $isProjectClosed ? 'disabled' : '' }}>
                        <i class="fa fa-ban me-1"></i> {{ __('Otkaži projekat') }}
                    </button>
                </div>
                @if($isProjectClosed)
                    <small class="d-block text-muted mt-2">{{ __('Projekat je već označen kao :status.', ['status' => __($displayStatusLabel)]) }}</small>
                @endif
                @if($isProjectCancelled)
                    <button type="button" class="btn btn-info btn-sm w-100 mt-3" id="restoreProjectButton">
                        <i class="fa fa-rotate-left me-1"></i> {{ __('Vrati u toku') }}
                    </button>
                @endif
            </div>
        </div>
        
    </div>
    
</div>
@endsection

@push('modals')
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectModalLabel">{{ __('Uredi projekat') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Zatvori') }}"></button>
            </div>
            <form id="editProjectForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editProjectName" class="form-label">{{ __('Naziv') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editProjectName" name="project_name" value="{{ $projekt->project_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="editProjectDescription" class="form-label">{{ __('Opis') }}</label>
                        <textarea class="form-control" id="editProjectDescription" name="description" rows="4">{{ $projekt->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editProjectAddress" class="form-label">{{ __('Adresa') }}</label>
                        <input type="text" class="form-control" id="editProjectAddress" name="address" value="{{ $projectAddress }}">
                    </div>

	                    <div class="row">
	                        <div class="col-md-6">
	                            <div class="mb-3">
	                                <label for="editProjectStartDate" class="form-label">{{ __('Datum početka') }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="editProjectStartDate" name="start_date" value="{{ $projekt->start_date?->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editProjectEndDate" class="form-label">{{ __('Datum završetka') }}</label>
                                <input type="date" class="form-control" id="editProjectEndDate" name="end_date" value="{{ $projekt->end_date?->format('Y-m-d') }}">
	                            </div>
	                        </div>
	                    </div>

	                    <div class="mb-3">
	                        <label for="editProjectPlannedBudget" class="form-label">{{ __('Planirani budžet') }}</label>
	                        <div class="input-group">
	                            <input type="number" class="form-control" id="editProjectPlannedBudget" name="planned_budget" min="0" step="0.01" value="{{ $budgetSummary['planned'] > 0 ? number_format($budgetSummary['planned'], 2, '.', '') : '' }}" placeholder="0.00">
	                            <span class="input-group-text">€</span>
	                        </div>
	                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Otkaži') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Sačuvaj') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="finishProjectModal" tabindex="-1" aria-labelledby="finishProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="finishProjectModalLabel">{{ __('Završi projekat') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Zatvori') }}"></button>
            </div>
            <form id="finishProjectForm">
                <input type="hidden" name="status" value="completed">
                <div class="modal-body">
                    <label for="finishProjectEndDate" class="form-label">{{ __('Datum završetka') }} <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="finishProjectEndDate" name="end_date" value="{{ now()->format('Y-m-d') }}" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('Otkaži') }}</button>
                    <button type="submit" class="btn btn-success btn-sm">{{ __('Završi') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="projectBudgetItemModal" tabindex="-1" aria-labelledby="projectBudgetItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectBudgetItemModalLabel">{{ __('Dodaj stavku budžeta') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Zatvori') }}"></button>
            </div>
            <form id="projectBudgetItemForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="budgetItemType" class="form-label">{{ __('Tip') }} <span class="text-danger">*</span></label>
                                <select class="form-control" id="budgetItemType" name="type" required>
                                    <option value="income">{{ __('Prihod') }}</option>
                                    <option value="expense">{{ __('Trošak') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="budgetItemAmount" class="form-label">{{ __('Iznos') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="budgetItemAmount" name="amount" min="0.01" step="0.01" required>
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
	                                <label for="budgetItemCategory" class="form-label">{{ __('Kategorija') }}</label>
	                                <select class="form-control" id="budgetItemCategory" name="category">
	                                    @foreach($budgetCategories as $categoryKey => $categoryLabel)
	                                        <option value="{{ $categoryKey }}" {{ $categoryKey === 'other' ? 'selected' : '' }}>{{ __($categoryLabel) }}</option>
	                                    @endforeach
	                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="budgetItemDate" class="form-label">{{ __('Datum') }}</label>
                                <input type="date" class="form-control" id="budgetItemDate" name="entry_date" value="{{ now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

	                    <input type="hidden" id="budgetItemRecord" name="projekt_record_id" required>

	                    <div class="mb-0">
                        <label for="budgetItemDescription" class="form-label">{{ __('Opis') }}</label>
                        <input type="text" class="form-control" id="budgetItemDescription" name="description" maxlength="255" placeholder="{{ __('Npr. uplata avansa, materijal, transport...') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Otkaži') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Sačuvaj stavku') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<input type="file" id="projectRecordFileInput" class="d-none" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.txt,.csv,.zip">
<input type="file" id="projectDocumentInput" class="d-none" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.txt,.csv,.zip" multiple>
@endpush

@push('footer_scripts')
<script src="{{ asset('files/vendor/ckeditor/ckeditor.js') }}"></script>
<script>
    const projectPeriodEditors = new Map();
    const projectRecordSaveTimers = new Map();
    const projectUpdateUrl = "{{ route('projekti.update', $projekt) }}";
    const projectRecordStoreUrl = "{{ route('projekti.records.store', $projekt) }}";
	    const projectRecordUpdateUrlTemplate = "{{ route('projekti.records.update', [$projekt, '__RECORD_ID__']) }}";
	    const projectRecordDeleteUrlTemplate = "{{ route('projekti.records.delete', [$projekt, '__RECORD_ID__']) }}";
	    const projectRecordFileStoreUrlTemplate = "{{ route('projekti.records.files.store', [$projekt, '__RECORD_ID__']) }}";
	    const projectRecordFileDeleteUrlTemplate = "{{ route('projekti.records.files.delete', [$projekt, '__RECORD_ID__', '__FILE_ID__']) }}";
	    const projectDocumentStoreUrl = "{{ route('projekti.documents.store', $projekt) }}";
	    const projectDocumentDeleteUrlTemplate = "{{ route('projekti.documents.delete', [$projekt, '__DOCUMENT_ID__']) }}";
	    const projectBudgetItemStoreUrl = "{{ route('projekti.budget.items.store', $projekt) }}";
	    const projectBudgetItemDeleteUrlTemplate = "{{ route('projekti.budget.items.delete', [$projekt, '__BUDGET_ITEM_ID__']) }}";
	    const projectBudgetAvailable = @json($budgetSummary['available']);
	    const projectDocumentsAvailable = @json($projectDocumentsAvailable);
	    const projectCsrfToken = $('meta[name="csrf-token"]').attr('content');
	    const projectTexts = {
	        confirmTitle: @json(__('Da li ste sigurni?')),
	        confirmText: @json(__('Ova akcija se neće moći vratiti!')),
	        confirmButton: @json(__('Potvrdi')),
	        deleteConfirm: @json(__('Obriši')),
	        cancelButton: @json(__('Odustani')),
	        updateError: @json(__('Greška pri ažuriranju projekta.')),
	        finishError: @json(__('Greška pri završavanju projekta.')),
	        cancelProjectTitle: @json(__('Da li želite otkazati projekat?')),
	        cancelProjectText: @json(__('Status projekta biće promijenjen u Otkazano.')),
	        cancelProjectButton: @json(__('Otkaži projekat')),
	        cancelProjectError: @json(__('Greška pri otkazivanju projekta.')),
	        restoreProjectTitle: @json(__('Vratiti projekat u toku?')),
	        restoreProjectText: @json(__('Projekat će ponovo biti aktivan.')),
	        restoreProjectButton: @json(__('Vrati')),
	        restoreProjectError: @json(__('Greška pri vraćanju projekta.')),
	        reportPlaceholder: @json(__('Upišite izvještaj, opis radova ili napomene za ovaj zapis.')),
	        plannedBudgetMissing: @json(__('Planirani budžet nije postavljen.')),
	        budgetOverrun: @json(__('Budžet je prekoračen za :amount')),
	        budgetUsed: @json(__('Budžet je u potpunosti iskorišten.')),
	        budgetWarning: @json(__('Budžet je skoro potrošen. Preostalo: :amount')),
	        budgetItemTitle: @json(__('Stavka budžeta')),
	        other: @json(__('Ostalo')),
	        deleteBudgetItemTitle: @json(__('Obriši stavku')),
	        budgetEmpty: @json(__('Još nema budžetskih stavki za ovaj zapis.')),
	        budgetTableMissing: @json(__('Tabela za budžet nije kreirana. Potrebno je pokrenuti migracije.')),
	        fileCountInvoice: @json(__('računa')),
	        fileCountDocument: @json(__('dokumenata')),
	        noInvoices: @json(__('Nema računa.')),
	        noDocuments: @json(__('Nema dokumenata.')),
	        fileFallback: @json(__('Fajl')),
	        documentFallback: @json(__('Dokument')),
	        deleteFileTitle: @json(__('Obriši fajl')),
	        totalFilesTitle: @json(__('Ukupno dokumenata i računa')),
	        noProjectDocuments: @json(__('Nema projektne dokumentacije.')),
	        deleteDocumentTitle: @json(__('Obriši dokument')),
	        newRecord: @json(__('Novi zapis')),
	        deleteRecordTitle: @json(__('Obriši zapis')),
	        recordNameLabel: @json(__('Naziv zapisa')),
	        recordDescriptionLabel: @json(__('Opis zapisa')),
	        reportTitle: @json(__('Izvještaj')),
	        documentsTitle: @json(__('Dokumenti')),
	        addDocument: @json(__('Dodaj dokument')),
	        invoicesTitle: @json(__('Računi')),
	        addInvoice: @json(__('Dodaj račun')),
	        recordBudgetTitle: @json(__('Budžet zapisa')),
	        recordBudgetSubtitle: @json(__('Prihodi i troškovi za ovaj period')),
	        itemButton: @json(__('Stavka')),
	        saveRecordError: @json(__('Greška pri čuvanju zapisa.')),
	        addBudgetItemError: @json(__('Greška pri dodavanju budžetske stavke.')),
	        deleteBudgetItemError: @json(__('Greška pri brisanju budžetske stavke.')),
	        addRecordError: @json(__('Greška pri dodavanju zapisa.')),
	        projectDocsTableMissing: @json(__('Tabela za projektnu dokumentaciju nije kreirana. Potrebno je pokrenuti migracije.')),
	        adding: @json(__('Dodavanje...')),
	        addProjectDocumentsError: @json(__('Greška pri dodavanju dokumentacije.')),
	        documentFallbackLower: @json(__('dokument')),
	        deleteDocumentError: @json(__('Greška pri brisanju dokumenta.')),
	        saveRecordBeforeFiles: @json(__('Prvo sačuvajte zapis da biste dodali fajlove.')),
	        addFileError: @json(__('Greška pri dodavanju fajla.')),
	        fileFallbackLower: @json(__('fajl')),
	        deleteFileError: @json(__('Greška pri brisanju fajla.')),
	        recordFallbackLower: @json(__('ovaj zapis')),
	        deleteRecordError: @json(__('Greška pri brisanju zapisa.')),
	        deleteQuestion: @json(__('Da li želite da izbrišete :name?')),
	        translationLookup: @json($projectCategoryTranslationLookup),
	    };

    function showProjectConfirm(options) {
        if (typeof Swal === 'undefined') {
            if (confirm(options.title || projectTexts.confirmTitle)) {
                options.onConfirm();
            }

            return;
        }

        Swal.fire({
            title: options.title || projectTexts.confirmTitle,
            text: options.text || projectTexts.confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: options.confirmButtonColor || '#DD6B55',
            cancelButtonColor: '#3085d6',
            confirmButtonText: options.confirmButtonText || projectTexts.confirmButton,
            cancelButtonText: options.cancelButtonText || projectTexts.cancelButton,
            reverseButtons: true,
        }).then(function(result) {
            if (result.isConfirmed) {
                options.onConfirm();
            }
        });
    }

    function projectText(template, replacements = {}) {
        return Object.entries(replacements).reduce(function(text, entry) {
            return text.replace(`:${entry[0]}`, entry[1]);
        }, template);
    }

    function projectTranslate(value) {
        return projectTexts.translationLookup[value] || value;
    }

    $('#editProjectForm').on('submit', function(event) {
        event.preventDefault();

        $.ajax({
            url: projectUpdateUrl,
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': projectCsrfToken
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                const message = Object.values(errors).flat().join(', ') || projectTexts.updateError;
                alert(message);
            }
        });
    });

    $('#finishProjectForm').on('submit', function(event) {
        event.preventDefault();

        $.ajax({
            url: projectUpdateUrl,
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': projectCsrfToken
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                const message = Object.values(errors).flat().join(', ') || projectTexts.finishError;
                alert(message);
            }
        });
    });

    $('#cancelProjectButton').on('click', function() {
        if ($(this).prop('disabled')) {
            return;
        }

        showProjectConfirm({
            title: projectTexts.cancelProjectTitle,
            text: projectTexts.cancelProjectText,
            confirmButtonText: projectTexts.cancelProjectButton,
            onConfirm: function() {
                $.ajax({
                    url: projectUpdateUrl,
                    type: 'POST',
                    data: {
                        status: 'cancelled'
                    },
                    headers: {
                        'X-CSRF-TOKEN': projectCsrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        const message = Object.values(errors).flat().join(', ') || projectTexts.cancelProjectError;
                        alert(message);
                    }
                });
            }
        });
    });

    $('#restoreProjectButton').on('click', function() {
        showProjectConfirm({
            title: projectTexts.restoreProjectTitle,
            text: projectTexts.restoreProjectText,
            confirmButtonColor: '#3085d6',
            confirmButtonText: projectTexts.restoreProjectButton,
            onConfirm: function() {
                $.ajax({
                    url: projectUpdateUrl,
                    type: 'POST',
                    data: {
                        status: 'in_progress'
                    },
                    headers: {
                        'X-CSRF-TOKEN': projectCsrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        const message = Object.values(errors).flat().join(', ') || projectTexts.restoreProjectError;
                        alert(message);
                    }
                });
            }
        });
    });

    function forceProjectReportListStyles(editor) {
        const editable = editor?.ui?.view?.editable?.element;

        if (!editable) {
            return;
        }

        editable.querySelectorAll('ul').forEach(function(list) {
            list.style.setProperty('list-style', 'disc outside', 'important');
            list.style.setProperty('list-style-type', 'disc', 'important');
            list.style.setProperty('list-style-position', 'outside', 'important');
            list.style.setProperty('padding-left', '28px', 'important');
            list.style.setProperty('margin-left', '0', 'important');
        });

        editable.querySelectorAll('ol').forEach(function(list) {
            list.style.setProperty('list-style', 'decimal outside', 'important');
            list.style.setProperty('list-style-type', 'decimal', 'important');
            list.style.setProperty('list-style-position', 'outside', 'important');
            list.style.setProperty('padding-left', '28px', 'important');
            list.style.setProperty('margin-left', '0', 'important');
        });

        editable.querySelectorAll('ul > li').forEach(function(item) {
            item.style.setProperty('display', 'list-item', 'important');
            item.style.setProperty('list-style', 'disc outside', 'important');
            item.style.setProperty('list-style-type', 'disc', 'important');
        });

        editable.querySelectorAll('ol > li').forEach(function(item) {
            item.style.setProperty('display', 'list-item', 'important');
            item.style.setProperty('list-style', 'decimal outside', 'important');
            item.style.setProperty('list-style-type', 'decimal', 'important');
        });
    }

    function initProjectReportEditor(textarea) {
        if (!textarea || textarea.dataset.editorReady === 'true' || typeof ClassicEditor === 'undefined') {
            return;
        }

        textarea.dataset.editorReady = 'true';

        ClassicEditor.create(textarea, {
            placeholder: projectTexts.reportPlaceholder,
            toolbar: {
                items: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo'],
                shouldNotGroupWhenFull: true
            }
        }).then(function(editor) {
            projectPeriodEditors.set(textarea.id, editor);
            forceProjectReportListStyles(editor);

            editor.model.document.on('change:data', function() {
                textarea.value = editor.getData();
                window.requestAnimationFrame(function() {
                    forceProjectReportListStyles(editor);
                });
                scheduleProjectRecordSave(textarea.closest('.project-period-card'));
            });
        }).catch(function(error) {
            console.error(error);
        });
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatProjectMoney(amount) {
        return new Intl.NumberFormat('de-DE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(Number(amount || 0)) + ' €';
    }

    function projectRecordUrl(template, recordId) {
        return template.replace('__RECORD_ID__', encodeURIComponent(recordId));
    }

	    function projectRecordFileUrl(template, recordId, fileId = null) {
	        return template
	            .replace('__RECORD_ID__', encodeURIComponent(recordId))
	            .replace('__FILE_ID__', encodeURIComponent(fileId));
	    }

	    function projectDocumentUrl(template, documentId) {
	        return template.replace('__DOCUMENT_ID__', encodeURIComponent(documentId));
	    }

	    function projectBudgetItemUrl(template, itemId) {
	        return template.replace('__BUDGET_ITEM_ID__', encodeURIComponent(itemId));
	    }

    function toggleProjectPeriod(card) {
        const collapseElement = card?.querySelector('.collapse');

        if (!collapseElement) {
            return;
        }

        if (typeof bootstrap !== 'undefined') {
            bootstrap.Collapse.getOrCreateInstance(collapseElement, {
                toggle: false
            }).toggle();
            return;
        }

        $(collapseElement).collapse('toggle');
    }

	    function getProjectBudgetAlert(summary) {
	        const planned = Number(summary?.planned || 0);
	        const usage = Number(summary?.expense_usage || 0);
	        const remaining = Number(summary?.remaining || 0);

	        if (planned <= 0) {
	            return {
	                type: 'info',
	                icon: 'fa-info-circle',
	                message: projectTexts.plannedBudgetMissing
	            };
	        }

	        if (usage >= 100) {
	            return {
	                type: 'danger',
	                icon: 'fa-exclamation-triangle',
	                message: remaining < 0
	                    ? projectText(projectTexts.budgetOverrun, {amount: formatProjectMoney(Math.abs(remaining))})
	                    : projectTexts.budgetUsed
	            };
	        }

	        if (usage >= 80) {
	            return {
	                type: 'warning',
	                icon: 'fa-exclamation-triangle',
	                message: projectText(projectTexts.budgetWarning, {amount: summary?.remaining_formatted || formatProjectMoney(remaining)})
	            };
	        }

	        return null;
	    }

	    function updateProjectBudgetAlert(summary) {
	        const alertElement = document.querySelector('[data-budget-alert]');

	        if (!alertElement) {
	            return;
	        }

	        const iconElement = alertElement.querySelector('[data-budget-alert-icon]');
	        const messageElement = alertElement.querySelector('[data-budget-alert-message]');
	        const alert = getProjectBudgetAlert(summary);

	        alertElement.classList.remove('is-info', 'is-warning', 'is-danger');

	        if (!alert) {
	            alertElement.classList.add('d-none');
	            return;
	        }

	        alertElement.classList.remove('d-none');
	        alertElement.classList.add(`is-${alert.type}`);

	        if (iconElement) {
	            iconElement.className = `fa ${alert.icon}`;
	        }

	        if (messageElement) {
	            messageElement.textContent = alert.message;
	        }
	    }

	    function updateProjectBudgetSummary(summary) {
	        if (!summary) {
	            return;
	        }

	        const balanceElement = document.querySelector('[data-budget-value="balance"]');
	        const remainingElement = document.querySelector('[data-budget-value="remaining"]');
	        const plannedElement = document.querySelector('[data-budget-value="planned"]');
	        const incomeElement = document.querySelector('[data-budget-value="income"]');
	        const expenseElement = document.querySelector('[data-budget-value="expense"]');
	        const usageElement = document.querySelector('[data-budget-value="usage"]');
	        const usageBar = document.getElementById('projectBudgetUsageBar');
	        const headerBudgetElement = document.querySelector('[data-header-budget-value]');

	        if (plannedElement) {
	            plannedElement.textContent = summary.planned_formatted;
	        }

	        if (incomeElement) {
	            incomeElement.textContent = summary.income_formatted;
	        }

	        if (expenseElement) {
	            expenseElement.textContent = summary.expense_formatted;
	        }

	        if (usageElement) {
	            usageElement.textContent = `${summary.expense_usage}%`;
	        }

	        if (headerBudgetElement) {
	            headerBudgetElement.textContent = summary.planned_formatted;
	        }

	        if (balanceElement) {
	            balanceElement.textContent = summary.balance_formatted;
	            balanceElement.classList.toggle('is-negative', Number(summary.balance) < 0);
	        }

	        if (remainingElement) {
	            remainingElement.textContent = summary.remaining_formatted;
	            remainingElement.classList.toggle('is-negative', Number(summary.remaining) < 0);
	        }

	        if (usageBar) {
	            usageBar.style.width = `${summary.expense_usage}%`;
	            usageBar.setAttribute('aria-valuenow', summary.expense_usage);
	        }

	        updateProjectBudgetAlert(summary);
	    }

	    function renderProjectBudgetItem(item) {
	        const safeId = escapeHtml(item.id);
	        const typeClass = item.type === 'income' ? 'income' : 'expense';
	        const sign = item.type === 'income' ? '+' : '-';
	        const title = item.description || projectTranslate(item.category_label) || projectTexts.budgetItemTitle;
	        const safeTitle = escapeHtml(title);
	        const safeCategory = escapeHtml(projectTranslate(item.category_label) || projectTexts.other);
	        const safeDate = escapeHtml(item.date_label || '-');
	        const safeAmount = escapeHtml(item.amount_formatted || '0,00 €');
	        const safeRawAmount = escapeHtml(item.amount || 0);

	        return `
	            <div class="project-budget-item" data-budget-item-id="${safeId}" data-budget-type="${typeClass}" data-budget-amount="${safeRawAmount}">
	                <div class="project-budget-item-main">
	                    <div class="project-budget-item-title">
	                        <strong>${safeTitle}</strong>
	                        <span>${safeCategory} · ${safeDate}</span>
	                    </div>
	                    <div class="project-budget-item-amount">
	                        <strong class="${typeClass}">${sign} ${safeAmount}</strong>
	                        <button type="button" class="project-budget-delete" title="${projectTexts.deleteBudgetItemTitle}" data-budget-item-id="${safeId}" data-budget-item-label="${safeTitle}">
	                            <i class="fa fa-trash"></i>
	                        </button>
	                    </div>
	                </div>
	            </div>
	        `;
	    }

	    function renderProjectBudgetEmpty() {
	        return `
	            <div class="project-budget-empty" data-budget-empty>
	                ${projectTexts.budgetEmpty}
	            </div>
	        `;
	    }

	    function renderProjectBudgetList(items) {
	        if (!projectBudgetAvailable) {
	            return `
	                <div class="project-budget-empty">
	                    ${projectTexts.budgetTableMissing}
	                </div>
	            `;
	        }

	        if (!items || !items.length) {
	            return renderProjectBudgetEmpty();
	        }

	        return items.map(renderProjectBudgetItem).join('');
	    }

	    function prependProjectBudgetItem(item) {
	        const card = document.querySelector(`.project-period-card[data-record-id="${item.record_id}"]`);
	        const list = card?.querySelector('[data-budget-list]');

	        if (!list) {
	            return;
	        }

	        list.querySelector('[data-budget-empty]')?.remove();
	        list.insertAdjacentHTML('afterbegin', renderProjectBudgetItem(item));
	        updateProjectPeriodBudgetSummary(card);
	    }

	    function removeProjectBudgetItem(item) {
	        const card = item?.closest('.project-period-card');
	        const list = item?.closest('[data-budget-list]');

	        item?.remove();

	        if (list && !list.querySelector('.project-budget-item')) {
	            list.insertAdjacentHTML('beforeend', renderProjectBudgetEmpty());
	        }

	        updateProjectPeriodBudgetSummary(card);
	    }

	    function projectFileTypeLabel(type) {
        return type === 'invoice' ? projectTexts.fileCountInvoice : projectTexts.fileCountDocument;
    }

    function projectFileEmptyText(type) {
        return type === 'invoice' ? projectTexts.noInvoices : projectTexts.noDocuments;
    }

    function projectFileIcon(type) {
        return type === 'invoice' ? 'fa-file-invoice' : 'fa-file';
    }

    function renderProjectFile(file, recordId, type) {
        const safeId = escapeHtml(file.id);
        const safeName = escapeHtml(file.name || projectTexts.fileFallback);
        const safeUrl = escapeHtml(file.url || '#');
        const safeSize = escapeHtml(file.size || '');

        return `
            <div class="project-file-item" data-file-id="${safeId}">
                <a href="${safeUrl}" target="_blank" class="project-file-link">
                    <i class="fa ${projectFileIcon(type)}"></i>
                    <span>${safeName}</span>
                </a>
                <span class="project-file-meta">${safeSize}</span>
                <button type="button" class="project-file-delete" title="${projectTexts.deleteFileTitle}" data-file-id="${safeId}" data-file-name="${safeName}">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        `;
    }

    function renderProjectFileList(files, type, recordId) {
        if (!files || !files.length) {
            return `
                <div class="project-period-empty project-file-empty" data-file-empty="${type}">
                    <i class="fa ${type === 'invoice' ? 'fa-file-invoice' : 'fa-folder-open'} me-1"></i>
                    ${projectFileEmptyText(type)}
                </div>
            `;
        }

        return files.map(function(file) {
            return renderProjectFile(file, recordId, type);
        }).join('');
    }

    function projectBudgetTotals(items) {
        return (items || []).reduce(function(totals, item) {
            const amount = Number(item.amount || item.dataset?.budgetAmount || 0);
            const type = item.type || item.dataset?.budgetType;

            if (type === 'income') {
                totals.income += amount;
            }

            if (type === 'expense') {
                totals.expense += amount;
            }

            return totals;
        }, { income: 0, expense: 0 });
    }

    function renderProjectPeriodSummary(income, expense, fileTotal) {
        const incomeHtml = Number(income || 0) > 0
            ? `<span class="project-period-money is-income" data-period-income>+ ${escapeHtml(formatProjectMoney(income))}</span>`
            : '';
        const expenseHtml = Number(expense || 0) > 0
            ? `<span class="project-period-money is-expense" data-period-expense>- ${escapeHtml(formatProjectMoney(expense))}</span>`
            : '';

        return `
            ${incomeHtml}
            ${expenseHtml}
            <span class="project-period-file-total" title="${projectTexts.totalFilesTitle}">
                <i class="fa fa-folder-open"></i>
                <span data-summary-file-total>${escapeHtml(fileTotal || 0)}</span>
            </span>
        `;
    }

    function updateProjectPeriodBudgetSummary(card) {
        const summary = card?.querySelector('.project-period-summary');

        if (!summary) {
            return;
        }

        const budgetItems = Array.from(card.querySelectorAll('.project-budget-item'));
        const totals = projectBudgetTotals(budgetItems);
        const fileTotal = Number(summary.querySelector('[data-summary-file-total]')?.textContent || 0);

        summary.innerHTML = renderProjectPeriodSummary(totals.income, totals.expense, fileTotal);
    }

    function renderProjectDocument(document) {
        const safeId = escapeHtml(document.id);
        const safeName = escapeHtml(document.name || projectTexts.documentFallback);
        const safeUrl = escapeHtml(document.url || '#');
        const safeSize = escapeHtml(document.size || '');

        return `
            <div class="project-file-item" data-project-document-id="${safeId}">
                <a href="${safeUrl}" target="_blank" class="project-file-link">
                    <i class="fa fa-file"></i>
                    <span>${safeName}</span>
                </a>
                <span class="project-file-meta">${safeSize}</span>
                <button type="button" class="project-document-delete" title="${projectTexts.deleteDocumentTitle}" data-document-id="${safeId}" data-document-name="${safeName}">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        `;
    }

    function renderProjectDocumentEmpty() {
        return `
            <div class="project-documents-empty" data-project-document-empty>
                <i class="fa fa-folder-open me-1"></i>
                ${projectTexts.noProjectDocuments}
            </div>
        `;
    }

    function updateProjectDocumentCount(count = null) {
        const list = document.querySelector('[data-project-document-list]');
        const nextCount = count ?? (list ? list.querySelectorAll('[data-project-document-id]').length : 0);

        document.querySelectorAll('[data-project-document-count]').forEach(function(element) {
            element.textContent = nextCount;
        });
    }

    function appendProjectDocuments(documents, count = null) {
        const list = document.querySelector('[data-project-document-list]');

        if (!list || !documents || !documents.length) {
            return;
        }

        list.querySelector('[data-project-document-empty]')?.remove();
        documents.forEach(function(documentItem) {
            list.insertAdjacentHTML('afterbegin', renderProjectDocument(documentItem));
        });
        updateProjectDocumentCount(count);
    }

    function removeProjectDocument(item, count = null) {
        const list = document.querySelector('[data-project-document-list]');

        item?.remove();

        if (list && !list.querySelector('[data-project-document-id]')) {
            list.insertAdjacentHTML('beforeend', renderProjectDocumentEmpty());
        }

        updateProjectDocumentCount(count);
    }

    function buildPeriodCard(record) {
        const key = record.key || `record${record.id}`;
        const label = record.label || projectTexts.newRecord;
        const description = record.description || '';
        const report = record.report || '';
        const collapseId = `period${key}`;
        const editorId = `period_report_${key}`;
        const safeRecordId = escapeHtml(record.id);
        const safeKey = escapeHtml(key);
        const safeLabel = escapeHtml(label);
        const safeDescription = escapeHtml(description);
	        const safeReport = escapeHtml(report);
	        const documents = record.documents || [];
	        const invoices = record.invoices || [];
	        const budgetItems = record.budget_items || [];
	        const budgetTotals = projectBudgetTotals(budgetItems);
	        const budgetIncome = record.budget_income ?? budgetTotals.income;
	        const budgetExpense = record.budget_expense ?? budgetTotals.expense;
	        const fileTotal = record.file_count ?? (documents.length + invoices.length);
	        const budgetButtonDisabled = projectBudgetAvailable ? '' : 'disabled';

	        return `
	            <div class="card project-period-card" data-record-id="${safeRecordId}" data-period-key="${safeKey}">
	                <div class="project-period-row">
	                    <div class="project-period-main">
	                        <span class="period-icon me-3">
	                            <i class="fa fa-calendar"></i>
	                        </span>
	                        <div class="project-period-fields flex-grow-1 me-3">
	                            <div class="project-period-display">
	                                <h5 class="mb-1 project-period-display-name">${safeLabel}</h5>
	                                <span class="project-period-display-description">${safeDescription}</span>
	                            </div>
	                            <div class="project-period-edit">
	                                <input type="text" class="project-period-name-input" name="period_meta[${safeKey}][name]" value="${safeLabel}" aria-label="${projectTexts.recordNameLabel}">
	                                <input type="text" class="project-period-description-input" name="period_meta[${safeKey}][description]" value="${safeDescription}" placeholder="${projectTexts.recordDescriptionLabel}" aria-label="${projectTexts.recordDescriptionLabel}">
	                            </div>
	                        </div>
	                        <div class="project-period-summary d-none d-md-flex me-3">
	                            ${renderProjectPeriodSummary(budgetIncome, budgetExpense, fileTotal)}
	                        </div>
	                    </div>
	                    <button type="button" class="project-period-delete" title="${projectTexts.deleteRecordTitle}" data-period-label="${safeLabel}">
	                        <i class="fa fa-trash"></i>
	                    </button>
	                </div>
	                <div id="${collapseId}" class="collapse show" data-bs-parent="#projectPeriodsAccordion">
	                    <div class="project-period-body">
	                        <div class="project-report-editor-wrap">
	                            <div class="project-report-editor-head">
	                                <div class="project-report-editor-title">
	                                    <span class="project-report-editor-icon">
	                                        <i class="fa fa-pen"></i>
	                                    </span>
	                                    <h6>${projectTexts.reportTitle}</h6>
	                                </div>
	                            </div>
	                            <textarea class="form-control project-report-editor" id="${editorId}" name="period_reports[${safeKey}]" rows="5" placeholder="${projectTexts.reportPlaceholder}">${safeReport}</textarea>
	                        </div>

	                        <div class="row">
	                            <div class="col-md-6 mb-3 mb-md-0">
	                                <div class="project-file-panel">
	                                    <div class="d-flex justify-content-between align-items-center mb-2">
	                                        <h6 class="mb-0">${projectTexts.documentsTitle}</h6>
	                                        <span class="badge badge-primary light" data-file-count="document">${documents.length}</span>
	                                    </div>
	                                    <button type="button" class="btn btn-primary btn-xs w-100 mb-2 project-file-upload" data-file-type="document">
	                                        <i class="fa fa-upload me-1"></i> ${projectTexts.addDocument}
	                                    </button>
	                                    <div class="project-file-list" data-file-list="document">
	                                        ${renderProjectFileList(documents, 'document', safeRecordId)}
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-md-6">
	                                <div class="project-file-panel">
	                                    <div class="d-flex justify-content-between align-items-center mb-2">
	                                        <h6 class="mb-0">${projectTexts.invoicesTitle}</h6>
	                                        <span class="badge badge-success light" data-file-count="invoice">${invoices.length}</span>
	                                    </div>
	                                    <button type="button" class="btn btn-success btn-xs w-100 mb-2 project-file-upload" data-file-type="invoice">
	                                        <i class="fa fa-upload me-1"></i> ${projectTexts.addInvoice}
	                                    </button>
	                                    <div class="project-file-list" data-file-list="invoice">
	                                        ${renderProjectFileList(invoices, 'invoice', safeRecordId)}
	                                    </div>
	                                </div>
	                            </div>
	                        </div>

	                        <div class="project-period-budget">
	                            <div class="d-flex justify-content-between align-items-center gap-2">
	                                <div>
	                                    <h6 class="mb-0">${projectTexts.recordBudgetTitle}</h6>
	                                    <small class="text-muted">${projectTexts.recordBudgetSubtitle}</small>
	                                </div>
	                                <button type="button" class="btn btn-primary btn-xs project-budget-add" data-record-id="${safeRecordId}" data-bs-toggle="modal" data-bs-target="#projectBudgetItemModal" ${budgetButtonDisabled}>
	                                    <i class="fa fa-plus me-1"></i> ${projectTexts.itemButton}
	                                </button>
	                            </div>
	                            <div class="project-budget-list" data-budget-list>
	                                ${renderProjectBudgetList(budgetItems)}
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        `;
    }

    function updatePeriodDisplay(card) {
        if (!card) {
            return;
        }

        const nameInput = card.querySelector('.project-period-name-input');
        const descriptionInput = card.querySelector('.project-period-description-input');
        const title = nameInput?.value.trim() || projectTexts.newRecord;
        const description = descriptionInput?.value.trim() || '';

        card.querySelectorAll('.project-period-title-text').forEach(function(element) {
            element.textContent = title;
        });

        card.querySelectorAll('.project-period-display-name').forEach(function(element) {
            element.textContent = title;
        });

        card.querySelectorAll('.project-period-display-description').forEach(function(element) {
            element.textContent = description;
        });

        const deleteButton = card.querySelector('.project-period-delete');

	        if (deleteButton) {
	            deleteButton.dataset.periodLabel = title;
	        }
	    }

    function getProjectRecordPayload(card) {
        const reportTextarea = card.querySelector('.project-report-editor');
        const reportEditor = reportTextarea ? projectPeriodEditors.get(reportTextarea.id) : null;

        return {
            title: card.querySelector('.project-period-name-input')?.value.trim() || projectTexts.newRecord,
            description: card.querySelector('.project-period-description-input')?.value.trim() || '',
            report: reportEditor ? reportEditor.getData() : (reportTextarea?.value || '')
        };
    }

    function saveProjectRecord(card) {
        const recordId = card?.dataset.recordId;

        if (!recordId) {
            return;
        }

        updatePeriodDisplay(card);

        $.ajax({
            url: projectRecordUrl(projectRecordUpdateUrlTemplate, recordId),
            type: 'POST',
            data: getProjectRecordPayload(card),
            headers: {
                'X-CSRF-TOKEN': projectCsrfToken
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                const message = Object.values(errors).flat().join(', ') || xhr.responseJSON?.message || projectTexts.saveRecordError;
                alert(message);
            }
        });
    }

    function scheduleProjectRecordSave(card) {
        const recordId = card?.dataset.recordId;

        if (!recordId) {
            return;
        }

        window.clearTimeout(projectRecordSaveTimers.get(recordId));

        projectRecordSaveTimers.set(recordId, window.setTimeout(function() {
            saveProjectRecord(card);
            projectRecordSaveTimers.delete(recordId);
        }, 800));
    }

    function setPeriodEditMode(card, shouldEdit, focusDescription = false) {
        if (!card) {
            return;
        }

        updatePeriodDisplay(card);
        card.classList.toggle('is-editing', shouldEdit);

        if (!shouldEdit) {
            saveProjectRecord(card);
            return;
        }

        const input = focusDescription
            ? card.querySelector('.project-period-description-input')
            : card.querySelector('.project-period-name-input');

        if (input) {
            input.focus();
            input.select();
        }
	    }

	    document.querySelectorAll('.project-report-editor').forEach(initProjectReportEditor);

	    document.addEventListener('click', function(event) {
	        const addBudgetButton = event.target.closest('.project-budget-add');

	        if (!addBudgetButton) {
	            return;
	        }

	        const form = document.getElementById('projectBudgetItemForm');
	        const recordInput = document.getElementById('budgetItemRecord');

	        if (form) {
	            form.reset();
	        }

	        if (recordInput) {
	            recordInput.value = addBudgetButton.dataset.recordId || '';
	        }
	    });

	    $('#projectBudgetItemForm').on('submit', function(event) {
	        event.preventDefault();

	        const form = $(this);
	        const submitButton = form.find('[type="submit"]');

	        submitButton.prop('disabled', true);

	        $.ajax({
	            url: projectBudgetItemStoreUrl,
	            type: 'POST',
	            data: form.serialize(),
	            headers: {
	                'X-CSRF-TOKEN': projectCsrfToken
	            },
	            success: function(response) {
	                if (response.success) {
	                    updateProjectBudgetSummary(response.summary);
	                    prependProjectBudgetItem(response.item);
	                    form[0].reset();

	                    if (typeof bootstrap !== 'undefined') {
	                        bootstrap.Modal.getInstance(document.getElementById('projectBudgetItemModal'))?.hide();
	                    } else {
	                        $('#projectBudgetItemModal').modal('hide');
	                    }
	                }
	            },
	            error: function(xhr) {
	                const errors = xhr.responseJSON?.errors || {};
	                const message = Object.values(errors).flat().join(', ') || xhr.responseJSON?.message || projectTexts.addBudgetItemError;
	                alert(message);
	            },
	            complete: function() {
	                submitButton.prop('disabled', false);
	            }
	        });
	    });

	    document.addEventListener('click', function(event) {
	        const deleteButton = event.target.closest('.project-budget-delete');

	        if (!deleteButton) {
	            return;
	        }

	        const item = deleteButton.closest('.project-budget-item');
	        const itemId = deleteButton.dataset.budgetItemId;
	        const label = deleteButton.dataset.budgetItemLabel || projectTexts.budgetItemTitle;

	        if (!itemId) {
	            return;
	        }

	        showProjectConfirm({
	            title: projectText(projectTexts.deleteQuestion, {name: label}),
	            text: projectTexts.confirmText,
	            confirmButtonText: projectTexts.deleteConfirm,
	            onConfirm: function() {
	                $.ajax({
	                    url: projectBudgetItemUrl(projectBudgetItemDeleteUrlTemplate, itemId),
	                    type: 'POST',
	                    headers: {
	                        'X-CSRF-TOKEN': projectCsrfToken
	                    },
	                    success: function(response) {
	                        if (response.success) {
	                            removeProjectBudgetItem(item);
	                            updateProjectBudgetSummary(response.summary);
	                        }
	                    },
	                    error: function(xhr) {
	                        const errors = xhr.responseJSON?.errors || {};
	                        const message = Object.values(errors).flat().join(', ') || xhr.responseJSON?.message || projectTexts.deleteBudgetItemError;
	                        alert(message);
	                    }
	                });
	            }
	        });
	    });

	    document.getElementById('addProjectPeriod')?.addEventListener('click', function() {
        const accordion = document.getElementById('projectPeriodsAccordion');
        const now = new Date();
        const label = `${projectTexts.newRecord} ${now.toLocaleDateString('de-AT')}`;

        $.ajax({
            url: projectRecordStoreUrl,
            type: 'POST',
            data: {
                title: label,
                description: ''
            },
            headers: {
                'X-CSRF-TOKEN': projectCsrfToken
            },
            success: function(response) {
                if (!response.success || !response.record) {
                    return;
                }

	                accordion.insertAdjacentHTML('afterbegin', buildPeriodCard(response.record));
	                initProjectReportEditor(document.getElementById(`period_report_${response.record.key}`));
	                setPeriodEditMode(accordion.querySelector(`[data-record-id="${response.record.id}"]`), true);
	            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                const message = Object.values(errors).flat().join(', ') || xhr.responseJSON?.message || projectTexts.addRecordError;
                alert(message);
            }
        });
    });

    document.addEventListener('input', function(event) {
        const periodInput = event.target.closest('.project-period-name-input, .project-period-description-input');

        if (!periodInput) {
            return;
        }

        updatePeriodDisplay(periodInput.closest('.project-period-card'));
    });

    document.addEventListener('keydown', function(event) {
        const periodInput = event.target.closest('.project-period-name-input, .project-period-description-input');

        if (!periodInput || !['Enter', 'Escape'].includes(event.key)) {
            return;
        }

        event.preventDefault();
        setPeriodEditMode(periodInput.closest('.project-period-card'), false);
    });

    document.addEventListener('focusout', function(event) {
        const editWrap = event.target.closest('.project-period-edit');

        if (!editWrap) {
            return;
        }

        window.setTimeout(function() {
            const card = editWrap.closest('.project-period-card');

            if (card && !card.querySelector('.project-period-edit')?.contains(document.activeElement)) {
                setPeriodEditMode(card, false);
            }
        }, 0);
    });

    let periodClickTimer = null;

    document.addEventListener('click', function(event) {
        const row = event.target.closest('.project-period-row');

        if (!row || event.target.closest('input, textarea, button, a, select')) {
            return;
        }

        window.clearTimeout(periodClickTimer);

        periodClickTimer = window.setTimeout(function() {
            periodClickTimer = null;

            const card = row.closest('.project-period-card');

            if (card && !card.classList.contains('is-editing')) {
                toggleProjectPeriod(card);
            }
        }, 220);
    });

    document.addEventListener('dblclick', function(event) {
        const fields = event.target.closest('.project-period-fields');

        if (!fields) {
            return;
        }

        event.preventDefault();
        window.clearTimeout(periodClickTimer);
        periodClickTimer = null;

        setPeriodEditMode(
            fields.closest('.project-period-card'),
            true,
            Boolean(event.target.closest('.project-period-display-description'))
        );
    });

    function removeProjectRecordCard(card) {
        if (!card) {
            return;
        }

        const recordId = card.dataset.recordId;

	        if (recordId) {
	            window.clearTimeout(projectRecordSaveTimers.get(recordId));
	            projectRecordSaveTimers.delete(recordId);
	        }

        card.querySelectorAll('.project-report-editor').forEach(function(textarea) {
            const editor = projectPeriodEditors.get(textarea.id);

            if (editor) {
                editor.destroy().catch(function(error) {
                    console.error(error);
                });
                projectPeriodEditors.delete(textarea.id);
            }
        });

        card.remove();
    }

    function updateProjectFileCount(card, type, count = null) {
        const list = card?.querySelector(`[data-file-list="${type}"]`);
        const nextCount = count ?? (list ? list.querySelectorAll('.project-file-item').length : 0);

        card?.querySelectorAll(`[data-file-count="${type}"]`).forEach(function(element) {
            element.textContent = nextCount;
        });

        updateProjectFileTotal(card);
    }

    function updateProjectFileTotal(card) {
        const documentCount = card?.querySelectorAll('[data-file-list="document"] .project-file-item').length || 0;
        const invoiceCount = card?.querySelectorAll('[data-file-list="invoice"] .project-file-item').length || 0;

        card?.querySelectorAll('[data-summary-file-total]').forEach(function(element) {
            element.textContent = documentCount + invoiceCount;
        });
    }

    function appendProjectFile(card, type, file, count = null) {
        const list = card?.querySelector(`[data-file-list="${type}"]`);

        if (!list) {
            return;
        }

        list.querySelector(`[data-file-empty="${type}"]`)?.remove();
        list.insertAdjacentHTML('beforeend', renderProjectFile(file, card.dataset.recordId, type));
        updateProjectFileCount(card, type, count);
    }

    function removeProjectFileItem(card, type, item, count = null) {
        const list = card?.querySelector(`[data-file-list="${type}"]`);

        item?.remove();

        if (list && !list.querySelector('.project-file-item')) {
            list.insertAdjacentHTML('beforeend', renderProjectFileList([], type, card.dataset.recordId));
        }

        updateProjectFileCount(card, type, count);
    }

    document.addEventListener('click', function(event) {
        const uploadButton = event.target.closest('.project-document-upload');

        if (!uploadButton) {
            return;
        }

        if (!projectDocumentsAvailable) {
            alert(projectTexts.projectDocsTableMissing);
            return;
        }

        const input = document.getElementById('projectDocumentInput');

        if (!input) {
            return;
        }

        input.value = '';
        input.click();
    });

    document.getElementById('projectDocumentInput')?.addEventListener('change', function() {
        const selectedFiles = Array.from(this.files || []);

        if (!selectedFiles.length) {
            return;
        }

        const formData = new FormData();
        selectedFiles.forEach(function(file) {
            formData.append('documents[]', file);
        });

        const uploadButton = document.getElementById('projectDocumentUploadButton');
        const originalText = uploadButton?.innerHTML;

        if (uploadButton) {
            uploadButton.disabled = true;
            uploadButton.innerHTML = `<i class="fa fa-spinner fa-spin me-1"></i> ${projectTexts.adding}`;
        }

        $.ajax({
            url: projectDocumentStoreUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': projectCsrfToken
            },
            success: function(response) {
                if (response.success && response.documents) {
                    appendProjectDocuments(response.documents, response.count);
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                const message = Object.values(errors).flat().join(', ') || xhr.responseJSON?.message || projectTexts.addProjectDocumentsError;
                alert(message);
            },
            complete: function() {
                if (uploadButton) {
                    uploadButton.disabled = false;
                    uploadButton.innerHTML = originalText;
                }
            }
        });
    });

    document.addEventListener('click', function(event) {
        const deleteButton = event.target.closest('.project-document-delete');

        if (!deleteButton) {
            return;
        }

        const item = deleteButton.closest('[data-project-document-id]');
        const documentId = deleteButton.dataset.documentId;
        const documentName = deleteButton.dataset.documentName || projectTexts.documentFallbackLower;

        if (!item || !documentId) {
            return;
        }

        showProjectConfirm({
            title: projectText(projectTexts.deleteQuestion, {name: documentName}),
            text: projectTexts.confirmText,
            confirmButtonText: projectTexts.deleteConfirm,
            onConfirm: function() {
                $.ajax({
                    url: projectDocumentUrl(projectDocumentDeleteUrlTemplate, documentId),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': projectCsrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            removeProjectDocument(item, response.count);
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        const message = Object.values(errors).flat().join(', ') || xhr.responseJSON?.message || projectTexts.deleteDocumentError;
                        alert(message);
                    }
                });
            }
        });
    });

    document.addEventListener('click', function(event) {
        const uploadButton = event.target.closest('.project-file-upload');

        if (!uploadButton) {
            return;
        }

        const card = uploadButton.closest('.project-period-card');
        const recordId = card?.dataset.recordId;
        const fileType = uploadButton.dataset.fileType;
        const input = document.getElementById('projectRecordFileInput');

        if (!recordId || !input) {
            alert(projectTexts.saveRecordBeforeFiles);
            return;
        }

        input.dataset.recordId = recordId;
        input.dataset.fileType = fileType;
        input.value = '';
        input.click();
    });

    document.getElementById('projectRecordFileInput')?.addEventListener('change', function() {
        const file = this.files?.[0];
        const recordId = this.dataset.recordId;
        const fileType = this.dataset.fileType;
        const card = document.querySelector(`.project-period-card[data-record-id="${recordId}"]`);

        if (!file || !recordId || !fileType || !card) {
            return;
        }

        const formData = new FormData();
        formData.append('file_type', fileType);
        formData.append('file', file);

        $.ajax({
            url: projectRecordFileUrl(projectRecordFileStoreUrlTemplate, recordId),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': projectCsrfToken
            },
            success: function(response) {
                if (response.success && response.file) {
                    appendProjectFile(card, fileType, response.file, response.counts?.[fileType]);
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                const message = Object.values(errors).flat().join(', ') || xhr.responseJSON?.message || projectTexts.addFileError;
                alert(message);
            }
        });
    });

    document.addEventListener('click', function(event) {
        const deleteButton = event.target.closest('.project-file-delete');

        if (!deleteButton) {
            return;
        }

        const card = deleteButton.closest('.project-period-card');
        const list = deleteButton.closest('.project-file-list');
        const item = deleteButton.closest('.project-file-item');
        const recordId = card?.dataset.recordId;
        const fileId = deleteButton.dataset.fileId;
        const fileName = deleteButton.dataset.fileName || projectTexts.fileFallbackLower;
        const fileType = list?.dataset.fileList;

        if (!card || !recordId || !fileId || !fileType) {
            return;
        }

        showProjectConfirm({
            title: projectText(projectTexts.deleteQuestion, {name: fileName}),
            text: projectTexts.confirmText,
            confirmButtonText: projectTexts.deleteConfirm,
            onConfirm: function() {
                $.ajax({
                    url: projectRecordFileUrl(projectRecordFileDeleteUrlTemplate, recordId, fileId),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': projectCsrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            removeProjectFileItem(card, fileType, item, response.counts?.[fileType]);
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        const message = Object.values(errors).flat().join(', ') || xhr.responseJSON?.message || projectTexts.deleteFileError;
                        alert(message);
                    }
                });
            }
        });
    });

    document.addEventListener('click', function(event) {
        const deleteButton = event.target.closest('.project-period-delete');

        if (!deleteButton) {
            return;
        }

        const card = deleteButton.closest('.project-period-card');
        const label = deleteButton.dataset.periodLabel || projectTexts.recordFallbackLower;

        if (!card) {
            return;
        }

        showProjectConfirm({
            title: projectText(projectTexts.deleteQuestion, {name: label}),
            text: projectTexts.confirmText,
            confirmButtonText: projectTexts.deleteConfirm,
            onConfirm: function() {
                const recordId = card.dataset.recordId;

                if (!recordId) {
                    removeProjectRecordCard(card);
                    return;
                }

                $.ajax({
                    url: projectRecordUrl(projectRecordDeleteUrlTemplate, recordId),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': projectCsrfToken
                    },
	                    success: function(response) {
	                        if (response.success) {
	                            removeProjectRecordCard(card);
	                            updateProjectBudgetSummary(response.summary);
	                        }
	                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        const message = Object.values(errors).flat().join(', ') || xhr.responseJSON?.message || projectTexts.deleteRecordError;
                        alert(message);
                    }
                });
            }
        });
    });
</script>
@endpush
