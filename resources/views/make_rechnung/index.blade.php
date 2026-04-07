	@extends('_layouts.layout')

	@section('head_title', __('Racuni'))

	@push('head_links')
	@endpush

	@section('content')

	@include('make_rechnung.partials.style')

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
						<i class="fa fa-plus"></i> @lang('Kreiraj racun')
					</button>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="exampledb" class="display w-100">
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

	@include('make_rechnung.partials.modal')
@endsection

@push('footer_scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
	<script>
		$(function() {

			// DATATABLES
			let datatableUrl = "{{ route('rechnung.datatable') }}";
			$('#exampledb').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": { url: datatableUrl, type: "post" },
				"columns": [
					{"data": "id_invoice", width: "10%"},
					{"data": "firma", width: "22%"},
					{"data": "adress", width: "22%"},
					{"data": "date_start", width: "10%"},
					{"data": "created_by", width: "16%"},
					{"data": "price", width: "10%"},
					{"data": "actions", width: "10%", orderable:false, searchable:false, className:"text-right"}
				],
				"language": {
					"search": "Suchen:",
					"paginate": { "previous": '<i class="fa-solid fa-angle-left"></i>', "next": '<i class="fa-solid fa-angle-right"></i>' }
				},
				"order": [[0, "asc"]]
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

			// ADD ITEM
			document.getElementById("addItem").onclick = function(){
				const row = document.createElement("div");
				row.classList.add("item-row","col-12");
				row.innerHTML = `
					<input type="text" class="item-name form-control" placeholder="Beschreibung">
					<input type="text" class="item-qty form-control" value="0">
					<input type="text" class="item-price form-control" value="0">
					<input type="text" class="item-total form-control" value="0">
					<button type="button" class="remove-item">×</button>
				`;

				row.querySelector(".remove-item").onclick = () => {
					row.remove();
					updatePreview();
				};

				itemsContainer.appendChild(row);
				updatePreview();
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

				document.getElementById("p_invoice_note").innerText =
					document.getElementById("invoice_note").value;

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
							invoice_note: $("#invoice_note").val(),
							total: $("#p_total").text(),
							html: html,
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
							if(xhr.status === 422){
								let errors = xhr.responseJSON.errors;
								if(errors.rechnung_nr){
									$("#rechnung_nr").addClass("is-invalid");
									$("#rechnung_nr_error").text(errors.rechnung_nr[0]).show();
								}
							}
							console.log(xhr.responseText);
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

				$(function(){
					const params = new URLSearchParams(window.location.search);
					if(params.get('openModal') === '1'){
						modal.style.display = "block";
						window.history.replaceState({}, document.title, window.location.pathname);
					}
				});

				const firmaSelect = document.getElementById("firma_select");

				// kad se izabere firma iz selecta
				$('#firma_select').on('change', function () {

					let selected = this.options[this.selectedIndex];

					if(!this.value){
						return;
					}

					$('#customer_name').val(selected.dataset.name || "");
					$('#adress').val(selected.dataset.adress || "");
					$('#ort').val(selected.dataset.ort || "");
					$('#uid').val(selected.dataset.uid || "");

					updatePreview();
				});
			
				document.getElementById("customer_name").addEventListener("input", function(){

					// makni selekciju iz dropdowna
					document.getElementById("firma_select").value = "";

					// očisti ostala polja (da ne ostanu stari podaci)
					document.getElementById("adress").value = "";
					document.getElementById("ort").value = "";
					document.getElementById("uid").value = "";

				});

				["adress","ort","uid"].forEach(id => {
					document.getElementById(id).dispatchEvent(new Event("input"));
				});

				$('#firma_select').select2({
					placeholder: "Pretraži firmu...",
					allowClear: true,
					width: '100%'
				});

		});

	</script>
@endpush