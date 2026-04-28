<style>
    button {
        padding: 8px 14px;
        cursor: pointer;
        float: right;
    }

    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        z-index: 999;
        color: black;
    }

    .modal-content {
        background: #fff;
        width: 95%;
        height: 95%;
        margin: 2% auto;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background: #f1f1f1;
    }

    .modal-body {
        flex: 1;
        display: flex;
        overflow: hidden;
        height: 100%;
    }

    .modal-left {
        width: 50%;
        padding: 10px;
        background: #fff;
        overflow-y: auto;
    }

    .modal-right {
        width: 50%;
        padding: 10px;
        overflow: auto;
        display: flex;
        justify-content: center;
    }

    .modal-right p{
        font-size: 12px;
    }

    .a4-wrapper {
        zoom: 1;
    }

    .a4-preview {
        width: 794px;
        min-height: 1120px;
        background: #fff;
        padding: 5mm 20mm 20mm 20mm;
        box-sizing: border-box;
        box-shadow: 0 0 15px rgba(0,0,0,0.15);
        position: relative;  
        color: #000 !important;
    }

    .header-a4 {
        height: 210px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        position: relative;
    }

    .company-logo {
        width: 285px;
        margin-left: auto; /* guramo logo desno */
    }

    .company-logo img {
        transform: translateY(-5px);
    }

    .company-logo img {
        width: 100%;
        display: block;
    }

    .company-text {
        display: flex;
        justify-content: flex-end;
        width: 100%;
        font-size: 10px;
        gap: 2%;
    }

    .company-text-left {
        width: 16%;
    }

    .company-text-right {
        width: 23%;
    }

    .company-text-left p,
    .company-text-right p {
        margin: 3px 0;
        line-height: 1.2;
        color: #1a64a2;
        font-size: 10px;
    }

    .firma {
        margin-top: 5px;
    }

    .customer-lead {
        font-size: 12px;
        text-align: left;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .firma-hr {
        width: 350px;
        max-width: 100%;
        height: 1px;
        background-color: black;
        margin: 5px 0;
    }

    .invoice-table{
        width:100%;
        table-layout:fixed;
        border-collapse:collapse;
        border: 1px solid black;
    }

    /* širine kolona */
    .invoice-table col.col-desc{
        width: calc(100% - 300px);
    }

    .invoice-table col.col-qty{
        width: 100px;
    }

    .invoice-table col.col-price{
        width: 100px;
    }

    .invoice-table col.col-total{
        width: 100px;
    }

    /* cell stil */
    .invoice-table th,
    .invoice-table td{
        padding:4px 8px;
        border-bottom: 1px solid black;
        line-height: 1.25;
        vertical-align:middle;
    }

    /* alignment */
    .invoice-table td:nth-child(2),
    .invoice-table td:nth-child(3),
    .invoice-table td:nth-child(4){
        text-align:center;
        white-space:nowrap;
        border-left: 1px solid black;
    }

    /* opis kolona */
    .invoice-table td:nth-child(1){
        text-align:left;
        word-break: break-word; /* KLJUČNO */
    }

    .invoice-table {
        font-family: Arial, sans-serif;
        font-size: 12px;
    }

    .invoice-table th:not(:first-child),
    .invoice-table td:not(:first-child){
        border-left: 1px solid black;
    }

    .item-row{
        display:flex;
        align-items:center;
        gap:10px;
        width:100%;
        margin-bottom:8px;
        }

    .item-name{
    flex:1;
    }

    .item-qty{
    width:125px;
    flex-shrink:0;
    text-align:center;
    }

    .item-price{
    width:120px;
    flex-shrink:0;
    text-align:center;
    }

    .item-total{
    width:115px;
    flex-shrink:0;
    text-align:right;
    font-weight:600;
    padding-right:10px;	
    }

    .remove-item{
    width:34px;
    height:34px;
    border:none;
    border-radius:6px;
    background:#e74c3c;
    color:white;
    font-weight:bold;
    cursor:pointer;
    transition:0.2s;
    }

    .remove-item:hover{
    background:#c0392b;
    }

    .qty-group{
    display:flex;
    width:175px;
    }

    .item-qty{
    width:110px;
    text-align:center;
    }

    .item-unit{
    width:70px;
    border-left:0;
    }

    /* uklanja strelice u Chrome, Edge, Safari */
    .item-qty::-webkit-inner-spin-button,
    .item-qty::-webkit-outer-spin-button{
        -webkit-appearance: none;
        margin: 0;
    }

    /* uklanja strelice u Firefox */
    .item-qty{
        -moz-appearance: textfield;
    }
    
    /* Reverse VAT Note */
    #reverse_vat_note {
        width: 100%;
        margin-top: 50px; /* 50px ispod tabele */
        font-size: 10px;
        color: #666;
        text-align: center;
    }

    /* Footer */
    .invoice-footer {
        position: absolute;
        bottom: 10mm;
        left: 20mm;
        right: 20mm;
        font-size: 10px;
        text-align: center;
        color: #666;
    }

    .form-control {
        color: black !important;
    }

    .card {
        height: auto !important;
    }
    .card-body {
        padding: 5px !important;
        padding-bottom: 30px !important;
    }

    .doc-card{
        border:1px solid #ddd;
        border-radius:8px;
        padding:12px;
        text-align:center;
        cursor:pointer;
        transition:all .2s;
        background:#fff;
    }

    .doc-card:hover{
        border-color:#0d6efd;
    }

    .doc-card.active{
        border:2px solid #0d6efd;
        background:#f3f7ff;
        color:#0d6efd;
    }

    .a4-preview{
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    *{
        -webkit-font-smoothing: none;
        text-rendering: geometricPrecision;
    }


    .remove-item {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;

        background-color: #ff4d4f;
        color: white;
        border: none;
        cursor: pointer;

        font-size: 18px;
        font-weight: bold;
        line-height: 1;
    }

    .remove-item:hover {
        background-color: #d9363e;
    }

    .autocomplete-box {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;          /* 🔥 KLJUČ */
        background: #fff;
        border: 1px solid #ddd;
        z-index: 9999;
        max-height: 200px;
        overflow-y: auto;
        box-sizing: border-box;
    }

    .autocomplete-item {
        padding: 8px;
        cursor: pointer;
    }

    .autocomplete-item {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .autocomplete-item:hover {
        background: #f2f2f2;
    }

    .ql-editor {
        font-family: Arial, Helvetica, sans-serif;
    }
</style>