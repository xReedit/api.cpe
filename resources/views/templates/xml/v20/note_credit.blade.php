@php
    $company = $document->company;
    $establishment = $document->establishment;
    $customer = $document->customer;
    $details = $document->details;
    $legends = $document->legends;
    $guides = $document->guides;
    $note = $document->note;
@endphp
{!! '<?xml version="1.0" encoding="utf-8" standalone="no"?>' !!}
<CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2"
            xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
            xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
            xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
            xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
            xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent>
                <sac:AdditionalInformation>
                    @if($note->total_discount > 0)
                        <sac:AdditionalMonetaryTotal>
                            <cbc:ID>2005</cbc:ID>
                            <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $note->total_discount }}</cbc:PayableAmount>
                        </sac:AdditionalMonetaryTotal>
                    @endif
                    @if($document->total_taxed > 0)
                    <sac:AdditionalMonetaryTotal>
                        <cbc:ID>1001</cbc:ID>
                        <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_taxed }}</cbc:PayableAmount>
                    </sac:AdditionalMonetaryTotal>
                    @endif
                    @if($document->total_unaffected > 0)
                    <sac:AdditionalMonetaryTotal>
                        <cbc:ID>1002</cbc:ID>
                        <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_unaffected }}</cbc:PayableAmount>
                    </sac:AdditionalMonetaryTotal>
                    @endif
                    @if($document->total_exonerated > 0)
                    <sac:AdditionalMonetaryTotal>
                        <cbc:ID>1003</cbc:ID>
                        <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_exonerated }}</cbc:PayableAmount>
                    </sac:AdditionalMonetaryTotal>
                    @endif
                    @foreach($document->legends as $legend)
                        <sac:AdditionalProperty>
                            <cbc:ID>{{ $legend->code }}</cbc:ID>
                            <cbc:Value>{{ $legend->description }}</cbc:Value>
                        </sac:AdditionalProperty>
                    @endforeach
                </sac:AdditionalInformation>
            </ext:ExtensionContent>
        </ext:UBLExtension>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
    <cbc:CustomizationID>1.0</cbc:CustomizationID>
    <cbc:ID>{{ $document->series }}-{{ $document->number }}</cbc:ID>
    <cbc:IssueDate>{{ $document->date_of_issue->format('Y-m-d') }}</cbc:IssueDate>
    <cbc:IssueTime>{{ $document->date_of_issue->format('H:i:s') }}</cbc:IssueTime>
    <cbc:DocumentCurrencyCode>{{ $document->currency_type_code }}</cbc:DocumentCurrencyCode>
    <cac:DiscrepancyResponse>
        <cbc:ReferenceID>{{ $note->affected_document_series.'-'.$note->affected_document_number }}</cbc:ReferenceID>
        <cbc:ResponseCode>{{ $note->note_type_code }}</cbc:ResponseCode>
        <cbc:Description>{{ $note->description }}</cbc:Description>
    </cac:DiscrepancyResponse>
    <cac:BillingReference>
        <cac:InvoiceDocumentReference>
            <cbc:ID>{{ $note->affected_document_series.'-'.$note->affected_document_number }}</cbc:ID>
            <cbc:DocumentTypeCode>{{ $note->affected_document_type_code }}</cbc:DocumentTypeCode>
        </cac:InvoiceDocumentReference>
    </cac:BillingReference>
    @foreach($guides as $guide)
    <cac:DespatchDocumentReference>
        <cbc:ID>{{ $guide->number }}</cbc:ID>
        <cbc:DocumentTypeCode>{{ $guide->document_type_code }}</cbc:DocumentTypeCode>
    </cac:DespatchDocumentReference>
    @endforeach
    {{--{% if doc.relDocs -%}--}}
    {{--{% for rel in doc.relDocs -%}--}}
    {{--<cac:AdditionalDocumentReference>--}}
        {{--<cbc:ID>{{ rel.nroDoc }}</cbc:ID>--}}
        {{--<cbc:DocumentTypeCode>{{ rel.tipoDoc }}</cbc:DocumentTypeCode>--}}
    {{--</cac:AdditionalDocumentReference>--}}
    {{--{% endfor -%}--}}
    {{--{% endif -%}--}}
    @php($company = $document->company)
    <cac:Signature>
        <cbc:ID>{{ $company->number }}</cbc:ID>
        <cbc:Note>Builder</cbc:Note>
        <cac:SignatoryParty>
            <cac:PartyIdentification>
                <cbc:ID>{{ $company->number }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>
            </cac:PartyName>
        </cac:SignatoryParty>
        <cac:DigitalSignatureAttachment>
            <cac:ExternalReference>
                <cbc:URI>#SIGN</cbc:URI>
            </cac:ExternalReference>
        </cac:DigitalSignatureAttachment>
    </cac:Signature>
    <cac:AccountingSupplierParty>
        <cbc:CustomerAssignedAccountID>{{ $company->number }}</cbc:CustomerAssignedAccountID>
        <cbc:AdditionalAccountID>{{ $company->identity_document_type_code }}</cbc:AdditionalAccountID>
        <cac:Party>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>
            </cac:PartyName>
            <cac:PostalAddress>
                <cbc:AddressTypeCode>{{ $establishment->code }}</cbc:AddressTypeCode>
            </cac:PostalAddress>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $company->name }}]]></cbc:RegistrationName>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cbc:CustomerAssignedAccountID>{{ $customer->number }}</cbc:CustomerAssignedAccountID>
        <cbc:AdditionalAccountID>{{ $customer->identity_document_type_code }}</cbc:AdditionalAccountID>
        <cac:Party>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $customer->name }}]]></cbc:RegistrationName>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingCustomerParty>

    {{--{% if doc.mtoISC -%}--}}
    {{--{% set iscT = doc.mtoISC|n_format -%}--}}
    {{--<cac:TaxTotal>--}}
        {{--<cbc:TaxAmount currencyID="{{ doc.tipoMoneda }}">{{ iscT }}</cbc:TaxAmount>--}}
        {{--<cac:TaxSubtotal>--}}
            {{--<cbc:TaxAmount currencyID="{{ doc.tipoMoneda }}">{{ iscT }}</cbc:TaxAmount>--}}
            {{--<cac:TaxCategory>--}}
                {{--<cac:TaxScheme>--}}
                    {{--<cbc:ID>2000</cbc:ID>--}}
                    {{--<cbc:Name>ISC</cbc:Name>--}}
                    {{--<cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>--}}
                {{--</cac:TaxScheme>--}}
            {{--</cac:TaxCategory>--}}
        {{--</cac:TaxSubtotal>--}}
    {{--</cac:TaxTotal>--}}
    {{--{% endif -%}--}}
    @if($document->total_igv > 0)
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_igv }}</cbc:TaxAmount>
        <cac:TaxSubtotal>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_igv }}</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>1000</cbc:ID>
                    <cbc:Name>IGV</cbc:Name>
                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>
    @endif
    @if($document->total_other_taxes > 0)
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_other_taxes }}</cbc:TaxAmount>
        <cac:TaxSubtotal>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_other_taxes }}</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>9999</cbc:ID>
                    <cbc:Name>OTROS</cbc:Name>
                    <cbc:TaxTypeCode>>OTH</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>
    @endif
    <cac:LegalMonetaryTotal>
        @if($document->total_other_charges > 0)
            <cbc:ChargeTotalAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_other_charges }}</cbc:ChargeTotalAmount>
        @endif
        @if($document->total > 0)
            <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total }}</cbc:PayableAmount>
        @endif
    </cac:LegalMonetaryTotal>
    @foreach($details as $row)
    <cac:CreditNoteLine>
        <cbc:ID>{{ $loop->iteration }}</cbc:ID>
        <cbc:CreditedQuantity unitCode="{{ $row->unit_type_code }}">{{ $row->quantity }}</cbc:CreditedQuantity>
        <cbc:LineExtensionAmount currencyID="{{ $document->currency_type_code }}">{{ $row->subtotal }}</cbc:LineExtensionAmount>
        <cac:PricingReference>
            <cac:AlternativeConditionPrice>
                <cbc:PriceAmount currencyID="{{ $document->currency_type_code }}">{{  $row->unit_price }}</cbc:PriceAmount>
                <cbc:PriceTypeCode>{{ $row->price_type_code }}</cbc:PriceTypeCode>
            </cac:AlternativeConditionPrice>
        </cac:PricingReference>

        {{--{% if detail.isc -%}--}}
        {{--{% set isc = detail.isc|n_format -%}--}}
        {{--<cac:TaxTotal>--}}
            {{--<cbc:TaxAmount currencyID="{{ doc.tipoMoneda }}">{{ isc }}</cbc:TaxAmount>--}}
            {{--<cac:TaxSubtotal>--}}
                {{--<cbc:TaxAmount currencyID="{{ doc.tipoMoneda }}">{{ isc }}</cbc:TaxAmount>--}}
                {{--<cac:TaxCategory>--}}
                    {{--<cbc:TierRange>{{ detail.tipSisIsc }}</cbc:TierRange>--}}
                    {{--<cac:TaxScheme>--}}
                        {{--<cbc:ID>2000</cbc:ID>--}}
                        {{--<cbc:Name>ISC</cbc:Name>--}}
                        {{--<cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>--}}
                    {{--</cac:TaxScheme>--}}
                {{--</cac:TaxCategory>--}}
            {{--</cac:TaxSubtotal>--}}
        {{--</cac:TaxTotal>--}}
        {{--{% endif -%}--}}
        @if($row->igv > 0)
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_igv }}</cbc:TaxAmount>
            <cac:TaxSubtotal>
                <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_igv }}</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:TaxExemptionReasonCode>{{ $row->affectation_igv_type_code }}</cbc:TaxExemptionReasonCode>
                    <cac:TaxScheme>
                        <cbc:ID>1000</cbc:ID>
                        <cbc:Name>IGV</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
        </cac:TaxTotal>
        @endif
        <cac:Item>
            <cbc:Description><![CDATA[{{ $row->item_description }}]]></cbc:Description>
            <cac:SellersItemIdentification>
                <cbc:ID>{{ $row->item_id }}</cbc:ID>
            </cac:SellersItemIdentification>
            {{--@if($row->item_code)--}}
            {{--<cac:CommodityClassification>--}}
                {{--<cbc:ItemClassificationCode listID="UNSPSC" listAgencyName="GS1 US" listName="Item Classification">{{ $row->item_code }}</cbc:ItemClassificationCode>--}}
            {{--</cac:CommodityClassification>--}}
            {{--@endif--}}
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="{{ $document->currency_type_code }}">{{ $row->unit_value }}</cbc:PriceAmount>
        </cac:Price>
    </cac:CreditNoteLine>
    @endforeach
</CreditNote>