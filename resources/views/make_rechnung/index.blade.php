	@extends('_layouts.layout')

	@section('head_title', __('Racuni'))

	@push('head_links')
	@endpush

	@section('content')

	@include('make_rechnung.partials.style')

	<style>
		.rechnung-page .card-header {
			gap: 12px;
		}

		.rechnung-page .card-body {
			padding: 20px !important;
			padding-bottom: 30px !important;
		}

		.rechnung-page .rechnung-index-table {
			width: 100% !important;
		}

		.rechnung-page .rechnung-index-table th,
		.rechnung-page .rechnung-index-table td {
			white-space: nowrap;
			vertical-align: middle;
		}

		.rechnung-page #openModal {
			float: none;
		}

		.rechnung-page button {
			float: none;
		}

		.rechnung-page .rechnung-create-text {
			margin-left: 4px;
		}

		.rechnung-page .btn-group {
			display: inline-flex;
			gap: 8px;
			max-height: none !important;
		}

		.rechnung-page .btn-group .btn {
			min-width: 44px;
			border-radius: 6px !important;
		}

		@media (max-width: 767px) {
			.rechnung-page .card-header {
				display: flex;
				align-items: center;
				justify-content: space-between;
			}

			.rechnung-page .card-header .btn {
				width: 42px;
				height: 42px;
				min-width: 42px;
				padding: 0;
				display: inline-flex;
				align-items: center;
				justify-content: center;
			}

			.rechnung-page .rechnung-create-text {
				display: none;
			}

			.rechnung-page .card-body {
				padding: 16px !important;
			}

			.rechnung-page .dataTables_length {
				display: none;
			}

			.rechnung-page .dataTables_wrapper .dataTables_filter,
			.rechnung-page .dataTables_wrapper .dataTables_info,
			.rechnung-page .dataTables_wrapper .dataTables_paginate {
				float: none;
				text-align: left;
				width: 100%;
			}

			.rechnung-page .dataTables_wrapper .dataTables_filter {
				margin-top: 10px;
			}

			.rechnung-page .dataTables_wrapper .dataTables_filter label {
				display: flex;
				align-items: center;
				gap: 8px;
				width: 100%;
				margin-bottom: 0;
				white-space: nowrap;
			}

			.rechnung-page .dataTables_wrapper .dataTables_filter input {
				flex: 1 1 auto;
				min-width: 0;
				width: auto;
				margin: 0;
			}

			.rechnung-page .dataTables_wrapper .dataTables_paginate {
				display: flex;
				align-items: center;
				justify-content: center;
				gap: 6px;
				margin-top: 14px;
				white-space: nowrap;
			}

			.rechnung-page .dataTables_wrapper .dataTables_paginate span {
				display: inline-flex;
				align-items: center;
				gap: 6px;
			}

			.rechnung-page .dataTables_wrapper .dataTables_paginate .paginate_button {
				min-width: 34px;
				height: 34px;
				padding: 0 !important;
				margin: 0 !important;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				border-radius: 6px !important;
				font-size: 13px;
			}

			.rechnung-page .dataTables_wrapper .dataTables_paginate span .paginate_button:not(.current):not(:first-child):not(:last-child),
			.rechnung-page .dataTables_wrapper .dataTables_paginate .ellipsis {
				display: none !important;
			}

			.rechnung-page .table-responsive {
				overflow: visible;
			}

			.rechnung-page .rechnung-index-table,
			.rechnung-page .rechnung-index-table tbody,
			.rechnung-page .rechnung-index-table tr,
			.rechnung-page .rechnung-index-table td {
				display: block;
				width: 100% !important;
			}

			.rechnung-page .rechnung-index-table thead {
				display: none;
			}

			.rechnung-page .rechnung-index-table tr {
				display: grid;
				grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
				column-gap: 12px;
				row-gap: 0;
				border: 1px solid #eef1f7;
				border-radius: 8px;
				padding: 12px;
				margin-bottom: 12px;
				background: #fff;
			}

			.rechnung-page .rechnung-index-table td {
				border: 0 !important;
				box-shadow: none !important;
				max-width: none !important;
				overflow: visible !important;
				padding: 0;
				white-space: normal;
				text-align: left !important;
				overflow-wrap: anywhere;
			}

			.rechnung-page .rechnung-index-table td:before {
				display: none;
			}

			.rechnung-page .rechnung-index-table td:first-child {
				grid-column: 1;
				grid-row: 1;
				font-size: 16px;
				font-weight: 700;
				color: #1f2937;
				line-height: 1.2;
			}

			.rechnung-page .rechnung-index-table td:nth-child(6) {
				grid-column: 2;
				grid-row: 1;
				align-self: start;
				justify-self: end;
				font-size: 16px;
				font-weight: 800;
				color: #1f2937;
				text-align: right !important;
				white-space: nowrap;
			}

			.rechnung-page .rechnung-index-table td:nth-child(2) {
				grid-column: 1 / -1;
				grid-row: 2;
				margin-top: 10px;
				font-size: 14px;
				font-weight: 700;
			}

			.rechnung-page .rechnung-index-table td:nth-child(3) {
				grid-column: 1 / -1;
				grid-row: 3;
				margin-top: 4px;
				color: #7e7e7e;
				font-size: 13px;
			}

			.rechnung-page .rechnung-index-table td:nth-child(4),
			.rechnung-page .rechnung-index-table td:nth-child(5) {
				grid-row: 4;
				margin-top: 12px;
				padding: 8px 10px;
				border-radius: 6px;
				background: #f6f7fb;
				font-size: 12px;
				font-weight: 700;
				color: #3f4b5b;
				min-width: 0;
			}

			.rechnung-page .rechnung-index-table td:nth-child(4) {
				grid-column: 1;
			}

			.rechnung-page .rechnung-index-table td:nth-child(5) {
				grid-column: 2;
				text-align: left !important;
			}

			.rechnung-page .rechnung-index-table td:nth-child(4):before,
			.rechnung-page .rechnung-index-table td:nth-child(5):before {
				display: block;
				content: attr(data-label);
				margin-bottom: 4px;
				color: #9aa1ad;
				font-size: 10px;
				font-weight: 700;
				text-transform: uppercase;
			}

			.rechnung-page .rechnung-index-table td:nth-child(7) {
				grid-column: 1 / -1;
				grid-row: 5;
				margin-top: 12px;
			}

			.rechnung-page .rechnung-index-table .btn-group {
				width: 100%;
				display: grid;
				grid-template-columns: 1fr 1fr;
				gap: 8px;
			}

			.rechnung-page .rechnung-index-table .btn-group .btn {
				width: 100%;
				min-height: 42px;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				border-radius: 6px !important;
			}
		}
	</style>

	<div class="rechnung-page">
	<div class="row page-titles mx-0">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{route('rechnung.index')}}">@lang('Racuni')</a></li>
			<li class="breadcrumb-item active">@lang('Pregled')</li>
		</ol>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">@lang('Racuni')</h4>
					<button 
						id="openModal"
						type="button"
						class="btn btn-primary"
						title="@lang('Kreiraj racun')"
					>
						<i class="fa fa-plus"></i><span class="rechnung-create-text">@lang('Kreiraj racun')</span>
					</button>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="exampledb" class="display w-100 rechnung-index-table">
							<thead>
								<tr>
									<th>@lang('ID')</th>
									<th>@lang('Firma')</th>
									<th>@lang('Adresa')</th>
									<th>@lang('Datum')</th>
									<th>@lang('Kreirao')</th>
									<th>@lang('Cijena')</th>
									<th class="text-right">@lang('Opcije')</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>

	@include('make_rechnung.partials.modal')
@endsection

@push('footer_scripts')
	<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
	<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
	<script>
		$(function() {

			// QUILL ---------------------------------------------------
			const quill = new Quill('#editor', {
				theme: 'snow',
				placeholder: 'Optionaler Text...',
				modules: {
					toolbar: [
						['bold', 'italic', 'underline'],
						[{ 'size': ['small', false, 'large', 'huge'] }],
						[{ 'list': 'ordered'}, { 'list': 'bullet' }],
						['clean']
					]
				}
			});

			quill.on('text-change', function () {
				document.getElementById("p_invoice_note").innerHTML = `<div class="ql-editor">${quill.root.innerHTML}</div>`;
			});
			// QUILL END -----------------------------------------------

			// DATATABLES
			let datatableUrl = "{{ route('rechnung.datatable') }}";
			let tableLabels = [
				@json(__('ID')),
				@json(__('Firma')),
				@json(__('Adresa')),
				@json(__('Datum')),
				@json(__('Kreirao')),
				@json(__('Cijena')),
				@json(__('Opcije'))
			];
			$('#exampledb').DataTable({
				"processing": true,
				"serverSide": true,
				"responsive": false,
				"autoWidth": false,
				"ajax": { url: datatableUrl, type: "post" },
				"columns": [
					{"data": "id_invoice", "name": "id_invoice", width: "10%"},
					{"data": "firma", width: "22%", orderable:false,},
					{"data": "adress", width: "22%", orderable:false,},
					{"data": "date_start", width: "10%", orderable:false,},
					{"data": "created_by", width: "16%", orderable:false,},
					{"data": "price", width: "10%", orderable:false,},
					{"data": "actions", width: "10%", orderable:false, searchable:false, className:"text-right"}
				],
				"language": {
					"search": "Suchen:",
					"paginate": { "previous": '<i class="fa-solid fa-angle-left"></i>', "next": '<i class="fa-solid fa-angle-right"></i>' }
				},
				"order": [[0, "desc"]],
				"createdRow": function(row) {
					$('td', row).each(function(index) {
						$(this).attr('data-label', tableLabels[index] || '');
					});
				},
				"drawCallback": function() {
					let api = this.api();
					let pageInfo = api.page.info();

					if (pageInfo.pages <= 1) {
						$(api.table().container()).find('.dataTables_paginate').hide();
					} else {
						$(api.table().container()).find('.dataTables_paginate').show();
					}
				}
			});

			// OPEN MODAL
			const modal = document.getElementById("invoiceModal");
			document.getElementById("openModal").onclick = () => modal.style.display = "block";

			const itemsContainer = document.getElementById("items");
			const previewItems = document.getElementById("previewItems");

			itemsContainer.addEventListener("input", function(e){
				if(e.target.matches("input, select")){
					updatePreview();
				}
			});

			itemsContainer.addEventListener("change", function(e){
				if(e.target.matches("select")){
					updatePreview();
				}
			});

			let index = 1;
			// ADD ITEM
			document.getElementById("addItem").onclick = function(){
				const row = document.createElement("div");
				row.classList.add("item-row","col-12");
				row.innerHTML = `
					<div style="position:relative; flex: 2;">
						<input 
							name="items[${index}][name]" 
							type="text" 
							class="item-name form-control" 
							placeholder="Beschreibung"
							autocomplete="off"
						>

						<div class="autocomplete-box beschreibung-box"></div>
					</div>
					<input name="items[${index}][qty]" type="text" class="item-qty form-control" value="0">
					<input name="items[${index}][price]" type="text" class="item-price form-control" value="0">
					<input name="items[${index}][total]" type="text" class="item-total form-control" value="0">
					<button type="button" class="remove-item text-center"><i class="fa fa-times"></i></button>
				`;

				row.querySelector(".remove-item").onclick = () => {
					row.remove();
					updatePreview();
				};

				itemsContainer.appendChild(row);
				updatePreview();

				index++;
			};

			document.querySelectorAll("#customer_name, #adress, #ort, #uid, #date, #bvh, #rechnung_nr, #auftragsnr, #ausführungszeit, #discount_percent, #use_tax, #invoice_note")
			.forEach(input => input.addEventListener("input", updatePreview));

			function updatePreview() {
				document.getElementById("p_customer_name").innerText =
					document.getElementById("customer_name").value;

				document.getElementById("p_adress").innerText =
					document.getElementById("adress").value;

				document.getElementById("p_ort").innerText =
					document.getElementById("ort").value;

				document.getElementById("p_uid").innerText =
					document.getElementById("uid").value;
				
				document.getElementById("p_bvh").innerText =
					document.getElementById("bvh").value;

				document.getElementById("p_rechnung_nr").innerText =
					document.getElementById("rechnung_nr").value;

				document.getElementById("p_auftragsnr").innerText =
					document.getElementById("auftragsnr").value;	

				document.getElementById("p_ausführungszeit").innerText =
					document.getElementById("ausführungszeit").value;

				document.getElementById("p_invoice_note").innerHTML = `<div class="ql-editor">${quill.root.innerHTML}</div>`;

				let dateValue = document.getElementById("date").value;
				if (dateValue) {
					let d = new Date(dateValue);
					let day = String(d.getDate()).padStart(2, '0');
					let month = String(d.getMonth() + 1).padStart(2, '0');
					let year = d.getFullYear();
					document.getElementById("p_date").innerText = `${day}.${month}.${year}`;
				}

				let total = 0;
				previewItems.innerHTML = "";
				document.querySelectorAll(".item-row").forEach(row => {
					const name = row.querySelector(".item-name")?.value || "";
					let qtyVal = row.querySelector(".item-qty")?.value || "";
					let priceVal = row.querySelector(".item-price")?.value || "";
					let totalVal = row.querySelector(".item-total")?.value || "";
					let rowTotal = parseFloat(totalVal.replace(/\./g,"").replace(",", "."));

					if(!isNaN(rowTotal)){
						total += rowTotal;
					}

					previewItems.innerHTML += `
					<tr>
						<td>${name}</td>
						<td>${qtyVal}</td>
						<td>${priceVal}</td>
						<td>
							<span style="float:left;">€</span>
							<span style="float:right;">${!isNaN(rowTotal) ? formatEuro(rowTotal) : ""}</span>
						</td>
					</tr>
					`;

				});

				let subtotal = total;

				document.getElementById("p_subtotal").innerText = formatEuro(subtotal);

				let discount = 0;
				let tax = 0;
				let deckungsrucklass = 0;

				let discountPercent = parseFloat(document.getElementById("discount_percent")?.value) || 0;
				let discountFixed = parseFloat(document.getElementById("discount_fixed")?.value) || 0;
				let deckungsPercent = parseFloat(document.getElementById("deckungsrucklass_percent")?.value) || 0;
				let abzTr1 = parseFloat(document.getElementById("abzug_tr1")?.value) || 0;

				const useTax = document.getElementById("use_tax")?.checked;

				/* NACHLASS */
				if(discountPercent > 0){
					discount = subtotal * (discountPercent / 100);
					subtotal = subtotal - discount;
					document.getElementById("discount_row").style.display = "flex";
					document.getElementById("p_discount").innerText = formatEuro(discount);
					document.querySelector("#discount_row span:first-child").innerText = "- " + discountPercent + "% Nachlass";
				}else{
					document.getElementById("discount_row").style.display = "none";
				}

				/* NACHLASS PAUSCHALE */
				if(discountFixed > 0){
					subtotal = subtotal - discountFixed;
					document.getElementById("discount_fixed_row").style.display = "flex";
					document.getElementById("p_discount_fixed").innerText = formatEuro(discountFixed);
				}else{
					document.getElementById("discount_fixed_row").style.display = "none";
				}

				/* DECKUNGSRÜCKLASS */
				if(deckungsPercent > 0){
					deckungsrucklass = subtotal * (deckungsPercent / 100);
					subtotal = subtotal - deckungsrucklass;
					document.getElementById("deckungsrucklass_row").style.display = "flex";
					document.getElementById("p_deckungsrucklass").innerText = formatEuro(deckungsrucklass);
					document.querySelector("#deckungsrucklass_row span:first-child").innerText =
						"- " + deckungsPercent + "% Deckungsrücklass";
				}else{
					document.getElementById("deckungsrucklass_row").style.display = "none";
				}

				/* MWST */
				if(useTax){
					tax = subtotal * 0.20;
					document.getElementById("tax_row").style.display = "flex";
					document.getElementById("p_tax").innerText = formatEuro(tax);
				}else{
					document.getElementById("tax_row").style.display = "none";
				}
				
				/* FINAL prije TR */
				let finalTotal = subtotal + tax;

				/* ABZUG TR 1 */
				if(abzTr1 > 0){
					finalTotal = finalTotal - abzTr1;
					document.getElementById("abzug_tr1_row").style.display = "flex";
					document.getElementById("p_abzug_tr1").innerText = formatEuro(abzTr1);
				}else{
					document.getElementById("abzug_tr1_row").style.display = "none";
				}

				/* FINAL */
				/* GESAMTBETRAG */
				document.getElementById("p_total").innerText = formatEuro(finalTotal);
				
			}


			
				document.getElementById("createInvoice").onclick = function () {

					const inputs = document.querySelectorAll("input[required]");

					for (let input of inputs) {

						if (!input.value.trim()) {
							input.classList.add("is-invalid");
							input.focus();
							return;
						}

					}

					const element = document.getElementById("invoicePreview");
					const rechnungNr = document.getElementById("rechnung_nr").value;
					const html = document.getElementById('a4wrapper').innerHTML;

					let items = [];

					document.querySelectorAll(".item-row").forEach(row => {
						items.push({
							name: row.querySelector(".item-name")?.value || "",
							qty: row.querySelector(".item-qty")?.value || 0,
							price: row.querySelector(".item-price")?.value || 0,
							total: row.querySelector(".item-total")?.value || 0,
						});
					});

					$.ajax({
						url: "{{ route('rechnung.store') }}",
						type: "POST",
						data: {
							type: $("#invoice_type").val(),
							customer_name: $("#customer_name").val(),
							adress: $("#adress").val(),
							ort: $("#ort").val(),
							uid: $("#uid").val(),
							date: $("#date").val(),
							bvh: $("#bvh").val(),
							auftragsnr: $("#auftragsnr").val(),
							rechnung_nr: $("#rechnung_nr").val(),
							ausführungszeit: $("#ausführungszeit").val(),
							invoice_note: quill.root.innerHTML,
							total: $("#p_total").text(),
							html: html,
							items: items,
							_token: $('meta[name="csrf-token"]').attr('content')
						},
						success: function(response){

							console.log(response);

							if(response.pdf_url){
								window.open(response.pdf_url, "_blank");
							}
							modal.style.display = "none";
							$('#exampledb').DataTable().draw(false);

						},
						error: function(xhr){
							$('.rechnung-error').text('');
							$('#rechnung_nr').removeClass('is-invalid');

							if (xhr.status === 422) {

								let errors = xhr.responseJSON.errors;

								if (errors?.rechnung_nr?.length) {
									$('#rechnung_nr').addClass('is-invalid');
									$('.rechnung-error').text(errors.rechnung_nr[0]);
								}
							}
						}
					});

				};


				document.getElementById("closeModal").onclick = () => {
					modal.style.display = "none";
				};

				window.onclick = function(e) {
					if (e.target === modal) {
						modal.style.display = "none";
					}
				};

				function formatEuro(number){

					return new Intl.NumberFormat('de-DE', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					}).format(number);

				}

				

				// prikaz/skrivanje teksta sa UID-om u previewu
				document.getElementById("uid").addEventListener("input", function() {
					let value = this.value.trim();
					document.getElementById("p_uid").innerText = value;
					document.getElementById("uid_line").style.display = value ? "block" : "none";
				});
				document.getElementById("uid").dispatchEvent(new Event("input"));
				// prikaz/skrivanje teksta sa BVH-om u previewu
				document.getElementById("bvh").addEventListener("input", function() {
					let value = this.value.trim();
					document.getElementById("p_bvh").innerText = value;
					document.getElementById("bvh_line").style.display = value ? "block" : "none";
				});
				document.getElementById("bvh").dispatchEvent(new Event("input"));
				// prikaz/skrivanje teksta sa Ausführungszeit-om u previewu
				document.getElementById("ausführungszeit").addEventListener("input", function() {
					let value = this.value.trim();
					document.getElementById("p_ausführungszeit").innerText = value;
					document.getElementById("ausführungszeit_line").style.display = value ? "inline" : "none";
				});
				document.getElementById("ausführungszeit").dispatchEvent(new Event("input"));

				// autocomplete za adresu
				const adressInput = document.getElementById("adress");
				const adressList = document.getElementById("adress_suggestions");
				adressInput.addEventListener("input", function(){
					let value = this.value;
					if(value.length < 2) return;

					fetch("/rechnung/autocomplete/adress?q=" + value)
						.then(res => res.json())
						.then(data => {
							adressList.innerHTML = "";
							data.forEach(adress => {
								let option = document.createElement("option");
								option.value = adress;
								adressList.appendChild(option);
							});
						});
				});

				// odabir tipa dokumenta //////////////////////////////////////////////////
				const cards = document.querySelectorAll('.doc-card');
				const input = document.getElementById('invoice_type');
				const label = document.getElementById('rechnung-line');
				const labels = {
					rechnung: "Rechnung ",
					teilrechnung: "Teilrechnung ",
					schlussrechnung: "Schlussrechnung "
				};
				cards.forEach(card => {
					card.addEventListener('click', function(){
						cards.forEach(c => c.classList.remove('active'));
						this.classList.add('active');
						const type = this.dataset.type;
						input.value = type;
						label.textContent = labels[type] || '';
					});
				});
				// default
				const selected = input.value || "rechnung";
				const defaultCard = document.querySelector(`.doc-card[data-type="${selected}"]`);
				if(defaultCard){
					defaultCard.classList.add('active');
					label.textContent = labels[selected] || '';
				}

				////////////////////////////////////////////////////////////////////////////

				// prikaz/skrivanje napomene o reverse chargeu kada se uključi/isključi MWST
				$('#use_tax').on('change', function () {
					if ($(this).is(':checked')) {
						$('#reverse_vat_note').hide();
					} else {
						$('#reverse_vat_note').show();
					}
				});


				document.getElementById("discount_percent")?.addEventListener("input", updatePreview);
				document.getElementById("discount_percent")?.addEventListener("change", updatePreview);

				document.getElementById("use_tax")?.addEventListener("input", updatePreview);
				document.getElementById("use_tax")?.addEventListener("change", updatePreview);

				document.getElementById("discount_fixed")?.addEventListener("input", updatePreview);
				document.getElementById("discount_fixed")?.addEventListener("change", updatePreview);

				document.getElementById("deckungsrucklass_percent")?.addEventListener("input", updatePreview);
				document.getElementById("deckungsrucklass_percent")?.addEventListener("change", updatePreview);

				document.getElementById("abzug_tr1")?.addEventListener("input", updatePreview);
				document.getElementById("abzug_tr1")?.addEventListener("change", updatePreview);
				
				updatePreview();

				document.querySelectorAll("input").forEach(input => {
					input.addEventListener("input", () => {
						input.classList.remove("is-invalid");
					});
				});

				$('#rechnung_nr').on('input', function () {
					$('.rechnung-error').text('');
					$(this).removeClass('is-invalid');
				});

				$(function(){
					const params = new URLSearchParams(window.location.search);
					if(params.get('openModal') === '1'){
						modal.style.display = "block";
						window.history.replaceState({}, document.title, window.location.pathname);
					}
				});

				function setValue(id, value){
					let el = document.getElementById(id);
					el.value = value || "";
					el.dispatchEvent(new Event("input"));
				}
				const firmaSelect = document.getElementById("firma_select");

				// kad se izabere firma iz selecta
				$('#firma_select').on('change', function () {

					let selected = $(this).find(':selected')[0];

					if (!this.value || !selected) return;

					setValue("customer_name", selected.dataset.name);
					setValue("adress", selected.dataset.adress);
					setValue("ort", selected.dataset.ort);
					setValue("uid", selected.dataset.uid);

					updatePreview();
				});
			
				document.getElementById("customer_name").addEventListener("input", function(){

					$('#firma_select').val(null).trigger('change'); // select2 reset

					document.getElementById("adress").value = "";
					document.getElementById("ort").value = "";
					document.getElementById("uid").value = "";

					updatePreview();
				});

				["adress","ort","uid"].forEach(id => {
					document.getElementById(id).dispatchEvent(new Event("input"));
				});

				$('#firma_select').select2({
					placeholder: "Firma suchen…",
					allowClear: true,
					width: '100%'
				});

				const inputpx = document.getElementById('spacing_input');
				const firma = document.getElementById('firma_block');

				inputpx.addEventListener('input', function () {
					let value = parseInt(this.value) || 0;

					// zaštita (0–100)
					if (value < 0) value = 0;
					if (value > 100) value = 100;

					firma.style.marginTop = value + 'px';
				});

				firma.style.marginTop = inputpx.value + 'px';

				// AUTOCOMPLETE BESCHREIBUNG ------------------------------------------------
				function setupBeschreibungAutocomplete() {
					let timeout;
					document.addEventListener("input", function (e) {
						if (!e.target.classList.contains("item-name")) return;
						const input = e.target;
						const box = input.parentElement.querySelector(".beschreibung-box");

						let query = input.value;

						clearTimeout(timeout);

						if (query.length < 2) {
							box.innerHTML = "";
							return;
						}

						timeout = setTimeout(() => {
							fetch(`/angebote/autocomplete/beschreibung?q=${encodeURIComponent(query)}`)
								.then(res => res.json())
								.then(data => {
									box.innerHTML = "";
									data.forEach(item => {
										const div = document.createElement("div");
										div.classList.add("autocomplete-item");
										div.innerText = item;
										div.addEventListener("click", () => {
											input.value = item;
											box.innerHTML = "";
											updatePreview();
										});
										box.appendChild(div);
									});
								});
						}, 300);
					});

					// zatvaranje na klik van
					document.addEventListener("click", function (e) {
						document.querySelectorAll(".beschreibung-box").forEach(box => {
							const input = box.parentElement.querySelector(".item-name");

							if (!box.contains(e.target) && e.target !== input) {
								box.innerHTML = "";
							}
						});
					});

				}

				setupBeschreibungAutocomplete();
				// AUTOCOMPLETE BESCHREIBUNG END --------------------------------------------
		});

	</script>
@endpush
