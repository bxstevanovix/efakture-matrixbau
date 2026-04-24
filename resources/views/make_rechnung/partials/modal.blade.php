<div id="invoiceModal" class="modal">
		<div class="modal-content">
			<!-- HEADER -->
			<div style="background-color: white !important;" class="modal-header">
				<h5 style="color:black; float: left;" class="modal-title" id="createInvoiceModalLabel">@lang('Kreiranje racuna')</h5>
				<button type="button" id="closeModal" class="btn btn-secondary">@lang('Zatvori')</button>
			</div>

			<!-- BODY -->
			<div class="modal-body">

				<!-- LIJEVA STRANA -->
				<div class="modal-left">
					<div class="card">
						<div class="card-body">
							<div class="form-validation">
								<div class="row">
									<!-- typ -->
									<div class="mb-3 col-md-12" style="padding-left:70px; padding-right:70px;">
										<div class="row g-2">
											<div class="col-md-1"></div>
											<div class="col-md-3">
												<div class="doc-card" data-type="rechnung">
													Rechnung
												</div>
											</div>
											<div class="col-md-3">
												<div class="doc-card" data-type="teilrechnung">
													Teilrechnung
												</div>
											</div>
											<div class="col-md-3">
												<div class="doc-card" data-type="schlussrechnung">
													Schlussrechnung
												</div>
											</div>
										</div>
										<input type="hidden" name="invoice_type" id="invoice_type" value="rechnung">
									</div>
									<!-- SELECT FIRME -->
									<div class="mb-3 row align-items-center">
										<label class="col-sm-2 col-form-label">Firma</label>
										<div class="col-sm-10">
											<select id="firma_select" class="form-control">
												<option value="">-- Odaberi firmu --</option>
												@foreach($firme as $firma)
													<option 
														value="{{ $firma->id }}"
														data-name="{{ $firma->name }}"
														data-adress="{{ $firma->address }}"
														data-ort="{{ $firma->ort }}"
														data-uid="{{ $firma->uid }}"
													>
														{{ $firma->name }}
													</option>
												@endforeach
											</select>
										</div>
									</div>
									<!-- firma -->
									<div class="mb-3 row align-items-center">
										<label for="customer_name" class="col-sm-2 col-form-label">@lang('Ime firme')</label>
										<div class="col-sm-8">
											<input 
												id="customer_name"
												type="text" 
												class="form-control @errorClass('name', 'is-invalid')" 
												name="name" 
												placeholder="@lang('Ime firme')" 
												value=""
												maxlength="155"
												autocomplete="off"
												required
											>
											<div class="invalid-feedback">
												Bitte geben Sie den Namen der Firma ein.
											</div>
										</div>
										<div class="col-sm-2">
											<input 
												type="number" 
												id="spacing_input"
												class="form-control"
												min="0"
												max="100"
												value="0"
												style="text-align:center;"
											>
										</div>
									</div>
									<!-- adresa -->
									<div class="mb-3 col-md-6">
										<label class="col-sm-6 col-form-label">@lang('Adresa')</label>
										<div class="col-sm-12">
											<input 
												id="adress"
												type="text" 
												class="form-control @errorClass('adress', 'is-invalid')" 
												name="adress" 
												placeholder="@lang('Adresa')" 
												value=""
												maxlength="155"
												list="adress_suggestions"
												autocomplete="off"
												required
											>

											<datalist id="adress_suggestions"></datalist>

											<div class="invalid-feedback">
												Bitte geben Sie die Adresse ein.
											</div>
										</div>
									</div>
									<!-- plt ort -->
									<div class="mb-3 col-md-6">
										<label class="col-sm-3 col-form-label">@lang('Plz Ort Land')</label>
										<div class="col-sm-12">
											<input 
												id="ort"
												type="text" 
												class="form-control @errorClass('ort', 'is-invalid')" 
												name="ort" 
												placeholder="@lang('Plz/Ort/Land')" 
												value=""
												maxlength="155"
												autocomplete="off"
												>
											<div class="invalid-feedback">
												Please enter a ort.
											</div>
										</div>
									</div>
									<!-- uid -->
									<div class="mb-3 col-md-4">
										<label class="form-label">@lang('UID-Nummer')</label>
										<div class="col-sm-12">
										<input 
											id="uid"
											type="text" 
											class="form-control @errorClass('uid', 'is-invalid')" 
											name="uid" 
											placeholder="@lang('UID-Nummer')" 
											value=""
											maxlength="30"
											autocomplete="off"
										>
										<div class="invalid-feedback">
											Bitte geben Sie den UID-Nummer ein.
										</div>
										</div>
									</div>
									<!-- datum -->
									<div class="mb-3 col-md-4">
										<label class="form-label">@lang('Datum')</label>
										<div class="col-sm-12">
										<input 
											style="text-align: center;"
											id="date"
											type="date" 
											class="form-control @errorClass('date', 'is-invalid')" 
											name="date" 
											placeholder="@lang('Datum')" 
											value="{{ now()->format('Y-m-d') }}"
											maxlength="30"
											autocomplete="off"
											required
										>
										</div>
										<div class="invalid-feedback">
											Bitte geben Sie das Datum ein.
										</div>
									</div>
									<!-- bvh -->
									<div class="mb-3 col-md-4">
										<label class="col-sm-12">BVH</label>
										<div class="col-sm-12">
											<input 
												id="bvh"
												type="text"
												class="form-control"
												name="bvh"
												placeholder="z.B. 11000 Ort, Straße 11"
												value=""
												maxlength="155"
												autocomplete="off"
											>
										</div>
									</div>
									<!-- auftragsnr -->
									<div class="mb-3 col-md-4">
										<label class="col-sm-12 col-form-label">&nbsp;</label>
										<div class="col-sm-12">
											<input 
												id="auftragsnr"
												type="text"
												class="form-control"
												name=""
												placeholder="Obj. Nr. 123/36WE, Auftragsnr. 123/989/38/1"
												value=""
												maxlength="50"
												autocomplete="off"
											>
										</div>
									</div>
									<!-- rechnung_nr -->
									<div class="mb-3 col-md-4">
										<label class="col-sm-12 col-form-label">Rechnung Nr</label>
										<div class="col-sm-12">
											<input 
												id="rechnung_nr"
												type="text"
												class="form-control @errorClass('rechnung_nr', 'is-invalid')" 
												name="rechnung_nr"
												placeholder="Rechnung Nr."
												value=""
												maxlength="50"
												autocomplete="off"
												required
											>
											@error('rechnung_nr')
												<div id="rechnung_nr_error" class="invalid-feedback">
													{{ $message }}
												</div>
											@enderror
										</div>
									</div>
									<!-- Ausführungszeit -->
									<div class="mb-3 col-md-4">
										<label class="col-sm-12 col-form-label">Ausführungszeit</label>
										<div class="col-sm-12">
											<input 
												id="ausführungszeit"
												type="text"
												class="form-control"
												name=""
												placeholder="KW: 32/25"
												value=""
												maxlength="50"
												autocomplete="off"
											>
										</div>
									</div>

									<hr>

									<div class="row" id="items">
										<!-- PRVI RED (stalni) -->
										<div class="item-row col-12 first-row">
											<input name="items[0][name]" type="text" class="item-name form-control" placeholder="Beschreibung" autocomplete="off">
											<input name="items[0][qty]" type="text" class="item-qty form-control" value="0" autocomplete="off">
											<input name="items[0][price]" type="text" class="item-price form-control" value="0" autocomplete="off">
											<input name="items[0][total]" type="text" class="item-total form-control" value="0" autocomplete="off">
											<button style="background-color:#ddd;" disabled type="button" class="remove-item"></button>
										</div>
									</div>
									<!-- DODAJ STAVKU -->
									<button style="max-width:500px; width:100%; display:block; margin:20px auto; text-align:center;" type="button" id="addItem">
										Hinzufügen
									</button>

									<div class="row" style="margin-top:15px; align-items:end;">
										<!-- NACHLASS % -->
										<div class="col-md-2 mb-3">
											<label class="form-label">Nachlass %</label>
											<input 
												type="number" 
												id="discount_percent" 
												class="form-control"
												value="0"
												min="0"
												step="1"
												style="text-align:center;"
												autocomplete="off"
											>
										</div>
										<!-- NACHLASS PAUSCHALE -->
										<div class="col-md-3 mb-3">
											<label class="form-label">Nachlass Pauschale €</label>
											<input 
												type="number" 
												id="discount_fixed" 
												class="form-control"
												value="0"
												min="0"
												step="1"
												style="text-align:center;"
												autocomplete="off"
											>
										</div>
										<!-- DECKUNGSRÜCKLASS -->
										<div class="col-md-3 mb-3">
											<label class="form-label">Deckungsrücklass %</label>
											<input 
												type="number" 
												id="deckungsrucklass_percent" 
												class="form-control"
												value="0"
												min="0"
												step="1"
												style="text-align:center;"
												autocomplete="off"
											>
										</div>
										<!-- MWST -->
										<div class="col-md-2 mb-3 d-flex align-items-center" style="margin-top:32px;">
											<input type="checkbox" id="use_tax" style="margin-bottom: 15px; margin-left: 20px;" autocomplete="off">
											<label style="margin-left: 15px; margin-bottom: 15px;" class="form-check-label" for="use_tax">20% MwSt</label>
										</div>
										<!-- ABZUG TR 1 -->
										<div class="col-md-2 mb-3">
											<label class="form-label">Abz. TR 1 €</label>
											<input 
												type="number" 
												id="abzug_tr1" 
												class="form-control"
												value="0"
												min="0"
												step="1"
												style="text-align:center;"
												autocomplete="off"
											>
										</div>
									</div>

									<!-- textarea -->
									<div class="mb-3 col-md-8">
										<textarea 
											id="invoice_note"
											class="form-control"
											placeholder="Optionaler Text..."
											rows="4"
											style="margin-top: 50px;"
											autocomplete="off"
										></textarea>
									</div>
									<!-- sacuvaj button -->
									<div class="mb-3 col-md-4">
										<button id="createInvoice" style="margin-top: 80px; margin-right: 10px;" type="submit" class="btn btn-success waves-effect waves-light">
											<i class="fa fa-save"></i>
											Rechnung erstellen
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- DESNA STRANA -->
				<div class="modal-right">
					<div id="a4wrapper" class="a4-wrapper">
						<div class="a4-preview" id="invoicePreview">
							<!-- HEADER -->
							<div class="header-a4">
								<!-- LOGO -->
								<div class="company-logo">
									<img src="img/cist-beli-logo.jpg" alt="Matrix Bau Logo">
								</div>
								<div class="company-text">
									<div class="company-text-left">
										<p>MaTrix Bau GmbH</p>
										<p>UID: ATU82609768</p> 
										<p>Tel: 0676/480 46 49</p> 
									</div>
									<div class="company-text-right">
										<p>Zetschegasse 3/12, 1230 Wien</p>
										<p>Firmenbuchnummer: 658176</p> 
										<p>E-mail: office@matrix-bau.at</p> 
									</div>
								</div>
							</div>

							<!-- FIRMA -->
							<div id="firma_block" class="firma">
								<p style="font-size:12px; max-width:350px; word-wrap:break-word;" class="customer-lead">
									<strong id="p_customer_name"></strong>
								</p>
								<p style="line-height: 0; margin-top: 10px;" id="p_adress"></p>
								<p style="line-height: 0;" id="p_ort"></p>
							</div>
							<div class="firma-hr"></div>
							
							<div style="display:flex; justify-content:space-between; margin-top:10px;">
								<p>
									<span id="uid_line" style="display:none;">
										UID-Nummer: <span id="p_uid"></span>
									</span>
								</p>
								<p class="company-date">
									Datum: <span id="p_date"></span>
								</p>
							</div>
							<div style="margin-top: 5px;" class="customer">
								<p id="bvh_line" style="line-height:0; display:none;">BVH. <span id="p_bvh"></span></p>
								<p style="line-height: 0;"><span id="p_auftragsnr"></span></p>
								
								<p style="line-height: 0;">
									<strong>
										<span id="rechnung-line">Angebot</span>
										<span id="p_rechnung_nr"></span>
									</strong>
									<span id="ausführungszeit_line" style="display: none;">, Ausführungszeit: </span>
									<span id="p_ausführungszeit"></span>
								</p>
							</div>

							<!-- TABELA -->
							<table class="invoice-table">
								<colgroup>
									<col class="col-desc">
									<col class="col-qty">
									<col class="col-price">
									<col class="col-total">
								</colgroup>

								<thead style="font-size:12px;">
									<tr>
										<th>Beschreibung</th>
										<th>Menge</th>
										<th>Einzelpreis</th>
										<th>Betrag</th>
									</tr>
								</thead>
								<tbody style="font-size: 12px;" id="previewItems">
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
								</tbody>
							</table>

							<!-- TOTALI DESNO -->
							<div style="margin-top:30px; width:300px; margin-left:auto;">

								<div style="display:flex; justify-content:space-between; font-size: 12px;">
									<span style="margin-left: 50px;">Zwischensumme</span>
									<span style="margin-right:9px;" id="p_subtotal">0.00 €</span>
								</div>

								<div id="discount_row" style="display:none; justify-content:space-between; font-size: 12px;">
									<span style="margin-left: 50px;">- 10% Nachlass</span>
									<span style="margin-right:9px;" id="p_discount">0.00</span>
								</div>

								<div id="discount_fixed_row" style="display:none; justify-content:space-between; font-size: 12px;">
									<span style="margin-left: 50px;">- Pauschale</span>
									<span style="margin-right:9px;" id="p_discount_fixed">0.00</span>
								</div>

								<div id="deckungsrucklass_row" style="display:none; justify-content:space-between; font-size: 12px;">
									<span style="margin-left: 50px;">- Deckungsrücklass</span>
									<span style="margin-right:9px;" id="p_deckungsrucklass">0.00</span>
								</div>

								<div id="tax_row" style="display:none; justify-content:space-between; font-size: 12px;">
									<span style="margin-left: 50px;">+ 20% MwSt</span>
									<span style="margin-right:9px;" id="p_tax">0.00</span>
								</div>

								<div id="abzug_tr1_row" style="display:none; justify-content:space-between; font-size:12px;">
									<span contenteditable="true" style="margin-left:50px;">- Abz. TR 1</span>
									<span style="margin-right:9px;" id="p_abzug_tr1"></span>
								</div>

								<hr style="margin-left: 50px;">

								<div style="display:flex; justify-content:space-between; font-weight:bold; font-size:14px;">
									<span style="margin-left: 50px;">Gesamtbetrag</span>

									<span style="border-bottom:2px double #000; padding-bottom:2px;">
										<span>€ &nbsp;&nbsp;</span>
										<span id="p_total">0.00</span>
									</span>
								</div>
							</div>

							<div class="description-left">
								<div class="description-left">
									<pre 
										id="p_invoice_note"
										style="
										font-size:9px;
										font-family: inherit;
										width:385px;
										color:black;
										white-space: pre-wrap;
										">
									</pre>
								</div>
							</div>

							<div id="reverse_vat_note">
								<p>Bauleistung ohne USt. (MwSt. zahlt Empfänger gemäß §19 Abs. 1a UStG 1994)</p>
							</div>
							<div class="invoice-footer">
								Bankverbindung: Volksbank Niederösterreich AG, BIC: VBOEATWWNOM, IBAN: AT32 4715 0120 1679 0000
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>