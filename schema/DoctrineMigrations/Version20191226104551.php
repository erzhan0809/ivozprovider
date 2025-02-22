<?php

namespace Application\Migrations;

use Ivoz\Core\Infrastructure\Persistence\Doctrine\LoggableMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20191226104551 extends LoggableMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $header = $this->connection->quote(
            '<!DOCTYPE HTML>
<html>
<head>
    <style>
        body {
            font-size: 11px;
            font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
            padding-top: 45px;
            border-bottom: 1px solid red;
            margin: 45px 8px 0;
        }
        .bold {
            font-weight: bold!important;
        }
        .center {
            text-align: center!important;
        }
        .left {
            float: left!important;
        }
        #header {
            text-align: right;
            height: 50px;
        }
        #header img {
            float: left;
            height: 30px;
        }
        #header p {
            margin: 0;
        }
        #header .redLine {
            border-color: red;
            border-width: 0 0 1px 0;
        }
    </style>
    <meta charset="UTF-8">
</head>
<body>
<div id="header">
    <div>
        <img src="{{brand.logoPath}}">
        <div>
            <p class="bold">{{brand.name}}</p>
            <p class="bold">{{brand.invoice.postalAddress}}, {{brand.invoice.postalCode}} {{brand.invoice.town}}, {{brand.invoice.province}} </p>
            <p>NIF / CIF: {{brand.invoice.nif}}</p>
        </div>
        <div class="redLine">
        </div>
    </div>
</div>
</body>
</html>
'
        );

        $footer = $this->connection->quote(
            '<!DOCTYPE HTML>
<html>
   <head>
       <style>
           #registryData {
              border-top: 1px solid red;
              border-bottom: 1px solid red;
              line-height: 18px;
              font-size: 8px;
              padding: 3px 0;
              text-align: center;
           }
           #footer {
              padding-bottom: 20px;
              text-align: right;
              font-size: 14px;
              font-weith:bold;
           }
       </style>
       <meta charset="UTF-8">
   </head>
   <body>
     <p id="registryData">
        {{brand.invoice.registryData}}
     </p>
     <div id="footer">
       <p>
         <span id="page"></span>
         / <span id="topage"></span>
       </p>
     </div>
    <script>
      var vars = {};
      var query_strings_from_url = document.location.search.substring(1).split(\'&\');
      for (var query_string in query_strings_from_url) {
          if (query_strings_from_url.hasOwnProperty(query_string)) {
              var temp_var = query_strings_from_url[query_string].split(\'=\', 2);
              vars[temp_var[0]] = decodeURI(temp_var[1]);
          }
      }
      document.getElementById(\'page\').innerHTML = vars.page;
      document.getElementById(\'topage\').innerHTML = vars.topage;
    </script>
    </body>
</html>
'
        );

        $basicBody = $this->connection->quote(
            '<!DOCTYPE HTML>
<html>
<head>
    <style>
        body {
            font-size: 11px;
            font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
        }
        h2 {
            font-size: 15px;
        }
        div{
            page-break-inside: avoid;
        }
        div.theader {
            width: 100%;
            text-align: center;
            margin: 25px 0 0;
            font-weight: bold;
            font-size: 20px;
            border: none;
            padding-bottom: 5px;
            border-bottom: 1px solid black;
        }
        div.table {
            display: table;
            margin-bottom: 5px;
            text-align: center;
            border: none;
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }
        div.tbody {
            display: table-row-group;
        }
        div.table div.tr {
            display: table-row;
            border: 1px solid black;
            page-break-inside: avoid;
        }
        div.table div.th {
            display: table-cell;
            border-right: 1px solid black;
            background-color: black;
            border-bottom: 1px solid black;
            color: white;
            font-weight: bold;
            padding: 5px 0 2px;
        }
        div.table div.tr:not(:last-child) div.th {
            border-bottom: 1px solid white;
        }
        div.table div.tr div.th:not(:last-child) {
            border-right: 1px solid white;
        }
        div.table div.td {
            display: table-cell;
            width: 50%;
            padding: 5px 0 5px;
            font-weight: normal;
            background-color: white;
            border: 1px solid black;
            color: black;
        }
        .bold {
            font-weight: bold!important;
        }
        .center {
            text-align: center!important;
        }
        .left {
            float: left!important;
        }
        .noBorder {
            border: none!important;
        }
        .clearFloats {
            clear:both;
        }
        .multiline {
            white-space: pre-wrap;
        }
        .clientData {
            width: 60%;
            float: left;
        }
        .clientData h2 {
            margin: 0;
        }
        .clientData p {
            margin: 0px 0px 0px 20px;
            padding: 0;
        }
        .invoiceData {
            width: 30%;
            float: right;
            text-align: right;
        }
        .invoiceData p {
            margin: 0;
            padding: 0;
        }
        #content {
            margin: 10px 0;
        }
        #subheader .left {
            width: 60%;
            font-weight: bold;
        }
        #subheader .left p {
            font-size: 32px;
            margin-top: 0;
        }
        #subheader .right {
            width: 35%;
            text-align: right;
            float: right;
        }
        #subheader .right p.title {
            margin: 0px;
            padding: 0;
            font-weight: bold;
            font-size: 17px;
        }
        #subheader .right p.date {
            margin: 0;
            padding: 0;
            font-size: 13px;
        }
        #content > div.table {
            text-align: center;
            width: 50%;
            float: right;
            border: none;
            border-collapse: collapse;
        }
        #callsPerTypeSummary > div.table {
            text-align: center;
            width: 100%;
        }
        #content {
            width: 100%;
        }
        #fixedCosts > div.table {
            clear: both;
        }
        #fixedCosts div.table div.td {
            width: 25%;
        }
        #summary {
            width: 50%;
            float: right;
        }
        #callsPerTypeSummary div.td {
            width: 25%;
        }
        #callsPerType div.td {
            width: 20%;
        }
    </style>
    <meta charset="UTF-8">
</head>
<body>
<div id="content">
    <div id="subheader">
        <div class="left">
            <p class="left">Factura</p>
        </div>
        <div class="right">
            <p class="title">Fecha</p>
            <p class="date">{{invoice.invoiceDate}}</p>
        </div>
    </div>
    <div>
        <div class="clientData">
            <h2>Cliente</h2>
            <p>{{company.name}}</p>
            <p>{{company.postalAddress}}</p>
            <p>{{company.postalCode}} {{company.town}}, {{company.province}} </p>
            <p>NIF / CIF: {{company.nif}}</p>
        </div>
        <div class="invoiceData">
            <p class="bold">Nº de factura</p>
            <p>{{invoice.number}}</p>
            <p class="bold">Periodo de facturación</p>
            <p>{{invoice.inDate}} - {{invoice.outDate}}</p>
        </div>
    </div>
    <br class="clearFloats" />
    <div id="summary">
        <div class="theader center">Resumen</div>
        <div class="table">
            <div class="tbody">
                {{#if fixedCostsTotals}}
                <div class="tr bold center">
                    <div class="th">Costes fijos</div>
                    <div class="td bold">
                        {{fixedCostsTotals}} {{invoice.currency}}
                    </div>
                </div>
                {{/if}}
                <div class="tr bold">
                    <div class="th">Total:</div>
                    <div class="td bold">{{totals.totalPrice}} {{invoice.currency}}</div>
                </div>
                <div class="tr bold">
                    <div class="th">IVA aplicable</div>
                    <div class="td bold">{{invoice.taxRate}} %</div>
                </div>
                <div class="tr bold">
                    <div class="th">IVA</div>
                    <div class="td bold">{{totals.totalTaxes}} {{invoice.currency}}</div>
                </div>
                <div class="tr bold">
                    <div class="th">Total con IVA</div>
                    <div class="td bold">{{totals.totalWithTaxes}} {{invoice.currency}}</div>
                </div>
            </div>
        </div>
    </div>
    <br class="clearFloats" />

    {{#if fixedCosts.length}}
        <div id="fixedCosts">
            <div class="theader">Costes fijos</div>
            <div class="table">
                <div class="tbody">
                    <div class="tr">
                        <div class="th">Concepto</div>
                        <div class="th">Precio</div>
                        <div class="th">Cantidad</div>
                        <div class="th">Subtotal</div>
                    </div>
                    {{#each fixedCosts}}
                        <div class="tr">
                            <div class="td">
                                {{name}}
                                {{#if description}}
                                    <br />
                                    <div class="multiline">{{description}}</div>
                                {{/if}}
                            </div>
                            <div class="td">{{cost}} {{currency}}</div>
                            <div class="td">{{quantity}}</div>
                            <div class="td">{{subTotal}} {{currency}}</div>
                        </div>
                    {{/each}}
                    <div class="tr noBorder">
                        <div class="td noBorder"></div>
                        <div class="td noBorder"></div>
                        <div class="th" style="border-right: 1px solid black;">Total:</div>
                        <div class="td bold">{{fixedCostsTotals}} {{invoice.currency}}</div>
                    </div>
                </div>
            </div>
        </div>
    {{/if}}

    <div id="callsPerTypeSummary">
        <div class="theader">Resumen de llamadas por tipo</div>
        <div class="table">
            <div class="tbody">
                <div class="tr">
                    <div class="th">Tipo</div>
                    <div class="th">Nº llamadas</div>
                    <div class="th">Duración total</div>
                    <div class="th">Precio total</div>
                </div>
                {{#each callData.callSumary}}
                <div class="tr">
                    <div class="td white">{{type}}</div>
                    <div class="td white">{{numberOfCalls}}</div>
                    <div class="td white">{{totalCallsDurationFormatted}}</div>
                    <div class="td white">{{totalPrice}} {{currency}}</div>
                </div>
                {{/each}}
                <div class="tr bold">
                    <div class="th" style="border-right: 1px solid black;">Totales:</div>
                    <div class="td bold" style="border-left: 1px solid black;">{{callData.callSumaryTotals.numberOfCalls}}</div>
                    <div class="td bold">{{callData.callSumaryTotals.totalCallsDurationFormatted}}</div>
                    <div class="td bold">{{callData.callSumaryTotals.totalPrice}} {{invoice.currency}}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
'
        );

        $detailedBody = $this->connection->quote(
'<!DOCTYPE HTML>
<html>
<head>
    <style>
        body {
            font-size: 11px;
            font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
        }
        h2 {
            font-size: 15px;
        }
        div{
            page-break-inside: avoid;
        }
        div.theader {
            width: 100%;
            text-align: center;
            margin: 25px 0 0;
            font-weight: bold;
            font-size: 20px;
            border: none;
            padding-bottom: 5px;
            border-bottom: 1px solid black;
        }
        div.table {
            display: table;
            margin-bottom: 5px;
            text-align: center;
            border: none;
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }
        div.tbody {
            display: table-row-group;
        }
        div.table div.tr {
            display: table-row;
            border: 1px solid black;
            page-break-inside: avoid;
        }
        div.table div.th {
            display: table-cell;
            border-right: 1px solid black;
            background-color: black;
            border-bottom: 1px solid black;
            color: white;
            font-weight: bold;
            padding: 5px 0 2px;
        }
        div.table div.tr:not(:last-child) div.th {
            border-bottom: 1px solid white;
        }
        div.table div.tr div.th:not(:last-child) {
            border-right: 1px solid white;
        }
        div.table div.td {
            display: table-cell;
            width: 50%;
            padding: 5px 0 5px;
            font-weight: normal;
            background-color: white;
            border: 1px solid black;
            color: black;
        }
        .bold {
            font-weight: bold!important;
        }
        .center {
            text-align: center!important;
        }
        .left {
            float: left!important;
        }
        .noBorder {
            border: none!important;
        }
        .clearFloats {
            clear:both;
        }
        .multiline {
            white-space: pre-wrap;
        }
        .clientData {
            width: 60%;
            float: left;
        }
        .clientData h2 {
            margin: 0;
        }
        .clientData p {
            margin: 0px 0px 0px 20px;
            padding: 0;
        }
        .invoiceData {
            width: 30%;
            float: right;
            text-align: right;
        }
        .invoiceData p {
            margin: 0;
            padding: 0;
        }
        #content {
            margin: 10px 0;
        }
        #subheader .left {
            width: 60%;
            font-weight: bold;
        }
        #subheader .left p {
            font-size: 32px;
            margin-top: 0;
        }
        #subheader .right {
            width: 35%;
            text-align: right;
            float: right;
        }
        #subheader .right p.title {
            margin: 0px;
            padding: 0;
            font-weight: bold;
            font-size: 17px;
        }
        #subheader .right p.date {
            margin: 0;
            padding: 0;
            font-size: 13px;
        }
        #content > div.table {
            text-align: center;
            width: 50%;
            float: right;
            border: none;
            border-collapse: collapse;
        }
        #callsPerTypeSummary > div.table {
            text-align: center;
            width: 100%;
        }
        #content {
            width: 100%;
        }
        #fixedCosts > div.table {
            clear: both;
        }
        #fixedCosts div.table div.td {
            width: 25%;
        }
        #summary {
            width: 50%;
            float: right;
        }
        #callsPerTypeSummary div.td {
            width: 25%;
        }
        #callsPerType div.td {
            width: 20%;
        }
    </style>
    <meta charset="UTF-8">
</head>
<body>
<div id="content">
    <div id="subheader">
        <div class="left">
            <p class="left">Factura</p>
        </div>
        <div class="right">
            <p class="title">Fecha</p>
            <p class="date">{{invoice.invoiceDate}}</p>
        </div>
    </div>
    <div>
        <div class="clientData">
            <h2>Cliente</h2>
            <p>{{company.name}}</p>
            <p>{{company.postalAddress}}</p>
            <p>{{company.postalCode}} {{company.town}}, {{company.province}} </p>
            <p>NIF / CIF: {{company.nif}}</p>
        </div>
        <div class="invoiceData">
            <p class="bold">Nº de factura</p>
            <p>{{invoice.number}}</p>
            <p class="bold">Periodo de facturación</p>
            <p>{{invoice.inDate}} - {{invoice.outDate}}</p>
        </div>
    </div>
    <br class="clearFloats" />
    <div id="summary">
        <div class="theader center">Resumen</div>
        <div class="table">
            <div class="tbody">
                {{#if fixedCostsTotals}}
                <div class="tr bold center">
                    <div class="th">Costes fijos</div>
                    <div class="td bold">
                        {{fixedCostsTotals}} {{invoice.currency}}
                    </div>
                </div>
                {{/if}}
                <div class="tr bold">
                    <div class="th">Total:</div>
                    <div class="td bold">{{totals.totalPrice}} {{invoice.currency}}</div>
                </div>
                <div class="tr bold">
                    <div class="th">IVA aplicable</div>
                    <div class="td bold">{{invoice.taxRate}} %</div>
                </div>
                <div class="tr bold">
                    <div class="th">IVA</div>
                    <div class="td bold">{{totals.totalTaxes}} {{invoice.currency}}</div>
                </div>
                <div class="tr bold">
                    <div class="th">Total con IVA</div>
                    <div class="td bold">{{totals.totalWithTaxes}} {{invoice.currency}}</div>
                </div>
            </div>
        </div>
    </div>
    <br class="clearFloats" />

    {{#if fixedCosts.length}}
        <div id="fixedCosts">
            <div class="theader">Costes fijos</div>
            <div class="table">
                <div class="tbody">
                    <div class="tr">
                        <div class="th">Concepto</div>
                        <div class="th">Precio</div>
                        <div class="th">Cantidad</div>
                        <div class="th">Subtotal</div>
                    </div>
                    {{#each fixedCosts}}
                        <div class="tr">
                            <div class="td">
                                {{name}}
                                {{#if description}}
                                    <br />
                                    <div class="multiline">{{description}}</div>
                                {{/if}}
                            </div>
                            <div class="td">{{cost}} {{currency}}</div>
                            <div class="td">{{quantity}}</div>
                            <div class="td">{{subTotal}} {{currency}}</div>
                        </div>
                    {{/each}}
                    <div class="tr noBorder">
                        <div class="td noBorder"></div>
                        <div class="td noBorder"></div>
                        <div class="th" style="border-right: 1px solid black;">Total:</div>
                        <div class="td bold">{{fixedCostsTotals}} {{invoice.currency}}</div>
                    </div>
                </div>
            </div>
        </div>
    {{/if}}

    <div id="callsPerTypeSummary">
        <div class="theader">Resumen de llamadas por tipo</div>
        <div class="table">
            <div class="tbody">
                <div class="tr">
                    <div class="th">Tipo</div>
                    <div class="th">Nº llamadas</div>
                    <div class="th">Duración total</div>
                    <div class="th">Precio total</div>
                </div>
                {{#each callData.callSumary}}
                <div class="tr">
                    <div class="td white">{{type}}</div>
                    <div class="td white">{{numberOfCalls}}</div>
                    <div class="td white">{{totalCallsDurationFormatted}}</div>
                    <div class="td white">{{totalPrice}} {{currency}}</div>
                </div>
                {{/each}}
                <div class="tr bold">
                    <div class="th" style="border-right: 1px solid black;">Totales:</div>
                    <div class="td bold" style="border-left: 1px solid black;">{{callData.callSumaryTotals.numberOfCalls}}</div>
                    <div class="td bold">{{callData.callSumaryTotals.totalCallsDurationFormatted}}</div>
                    <div class="td bold">{{callData.callSumaryTotals.totalPrice}} {{invoice.currency}}</div>
                </div>
            </div>
        </div>
    </div>

    {{#each callData.callsPerType}}
    <div id="callsPerType">
        <div class="theader">{{items.0.targetPattern.name}}</div>
        <div class="table">
            <div class="tbody">
                <div class="tr">
                    <div class="th">Fecha</div>
                    <div class="th">Destino</div>
                    <div class="th">Duración</div>
                    <div class="th">Plan</div>
                    <div class="th">Precio</div>
                </div>
                {{#each items}}
                <div class="tr">
                    <div class="td white">{{calldate}}</div>
                    <div class="td white">{{dst}}</div>
                    <div class="td white">{{durationFormatted}}</div>
                    <div class="td white">{{pricingPlan.name}}</div>
                    <div class="td white">{{price}} {{currency}}</div>
                </div>
                {{/each}}
            </div>
        </div>
    </div>
    {{/each}}
</body>
</html>
'
        );


        $this->addSql("UPDATE `InvoiceTemplates` SET
                              template = $basicBody,
                              templateHeader = $header,
                              templateFooter = $footer
                            WHERE brandId IS NULL AND name = 'Basic'");

        $this->addSql("UPDATE `InvoiceTemplates` SET
                              template = $detailedBody,
                              templateHeader = $header,
                              templateFooter = $footer
                            WHERE brandId IS NULL AND name = 'Detailed'");


    }

    public function down(Schema $schema)
    {

    }
}

