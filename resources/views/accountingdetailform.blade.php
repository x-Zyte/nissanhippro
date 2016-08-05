@extends('app')

@if($oper == 'new')
    @section('title','เพิ่มรายละเอียดเพื่อการบันทึกบัญชีใหม่')
@elseif($oper == 'edit')
    @section('title','แก้ไขรายละเอียดเพื่อการบันทึกบัญชี ของรายการจอง '.$accountingdetail->bookno.'/'.$accountingdetail->no)
@elseif($oper == 'view')
    @section('title','ดูข้อมูลรายละเอียดเพื่อการบันทึกบัญชี ของรายการจอง '.$accountingdetail->bookno.'/'.$accountingdetail->no)
@endif

@section('menu-accountingdetail-class','active')
@section('pathPrefix',$pathPrefix)

@section('content')
    <style>
        .underline_db {
            border-bottom: 3px double #000;
            text-align: right;
        }

        .underline {
            border-bottom: 1px solid #000;
            text-align: right;
        }

        .underline_g {
            border-bottom: 1px solid #8C8C8C;
            text-align: right;
        }

        .dotted {
            border-bottom: 1px dotted #8C8C8C;
        }

        .dashed {
            border-bottom: 1px dashed #000;
        }

        .clear {
            clear: both
        }

        div.me {
            display: inline-block;
            padding: 3px;
            border: 1px solid black;
            margin-left: -1px;
            margin-top: -1px;
            white-space: 0;
        }

        .form-group {
            margin-bottom: 0;
        }

        .widget-main {
            padding: 5px;
        }
    </style>

    <script type="text/javascript">
        function numberWithCommas(x) {
            var parts = x.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }

        function CarpaymentChange(sel) {
            var carpaymentid = sel.value;
            if (carpaymentid == null || carpaymentid == '') {
                return;
            }

            $.get('{{$pathPrefix}}carpayment/getforaccountingdetailbyid/' + carpaymentid + '/1', function (data) {
                $("#branchname").text(data.branchname);
                $("#customername").text(data.customername);
                $("#date").text(data.date);

                $("#openbill").text(data.openbill);

                var additionalopenbill = $('#additionalopenbill').val();
                if (additionalopenbill == null || additionalopenbill == '')
                    additionalopenbill = 0;
                var finalopenbill = parseFloat(data.openbill.replace(",", "")) + parseFloat(additionalopenbill);
                if (finalopenbill == 0) $("#finalopenbill").text("-");
                else $("#finalopenbill").text(numberWithCommas(parseFloat(finalopenbill).toFixed(2)));

                $("#carpriceinpricelist").text(data.carpriceinpricelist);
                $("#colorprice").text(data.colorprice);
                $("#carwithcolorprice").text(data.carwithcolorprice);
                $("#accessoriesfeeincludeinyodjud").text(data.accessoriesfeeincludeinyodjud);

                $("#plusfakeaccessories").text(data.fakeaccessories);
                $("#minusfakeaccessories").text(data.fakeaccessories);

                $("#discount").text(data.discount);
                $("#subdown").text(data.subdown);

                $("#realsalesprice").text(data.realsalesprice);
                $("#realsalesprice2").text(data.realsalesprice);

                var vat;
                if (finalopenbill == 0) vat = 0;
                else vat = parseFloat(finalopenbill) * parseFloat(0.07);

                if (vat == 0) $("#vatoffinalopenbill").text("-");
                else $("#vatoffinalopenbill").text(numberWithCommas(parseFloat(vat).toFixed(2)));

                var finalopenbillwithoutvat = parseFloat(finalopenbill) - parseFloat(vat);
                if (finalopenbillwithoutvat == 0) $("#finalopenbillwithoutvat").text("-");
                else $("#finalopenbillwithoutvat").text(numberWithCommas(parseFloat(finalopenbillwithoutvat).toFixed(2)));

                var realsalesprice = data.realsalesprice == '-' ? 0 : data.realsalesprice.replace(",", "");
                var realsalespricewithoutvat = parseFloat(realsalesprice) - parseFloat(vat);
                if (realsalespricewithoutvat == 0) $("#realsalespricewithoutvat").text("-");
                else $("#realsalespricewithoutvat").text(numberWithCommas(parseFloat(realsalespricewithoutvat).toFixed(2)));

                $("#accessoriesfeeactuallypaid").text(data.accessoriesfeeactuallypaid);
                $("#registrationfee").text(data.registrationfee);
                $("#compulsorymotorinsurancefeecash").text(data.compulsorymotorinsurancefeecash);
                $("#insurancefeecash").text(data.insurancefeecash);
                $("#implementfee").text(data.implementfee);
                $("#totalotherfee").text(data.totalotherfee);
                $("#subsidise").text(data.subsidise);
                $("#giveawaywithholdingtax").text(data.giveawaywithholdingtax);
                $("#otherfee").text(data.otherfee);
                $("#otherfeedetail").text(data.otherfeedetail);
                $("#otherfee2").text(data.otherfee2);
                $("#otherfeedetail2").text(data.otherfeedetail2);
                $("#otherfee3").text(data.otherfee3);
                $("#otherfeedetail3").text(data.otherfeedetail3);
                $("#totalotherfees").text(data.totalotherfees);

                $("#submodel").text(data.submodel);
                $("#carno").text(data.carno);
                $("#engineno").text(data.engineno);
                $("#chassisno").text(data.chassisno);
                $("#color").text(data.color);
                $("#purchasetypetext").text(data.purchasetypetext);

                $("#down").text(data.down);
                $("#compulsorymotorinsurancefeefn").text(data.compulsorymotorinsurancefeefn);
                $("#insurancefeefn").text(data.insurancefeefn);
                $("#firstinstallmentpayamount").text(data.firstinstallmentpayamount);
                $("#installmentsinadvance").text(data.installmentsinadvance);
                $("#amountperinstallment").text(data.amountperinstallment);
                $("#payinadvanceamount").text(data.payinadvanceamount);
                $("#insurancepremium").text(data.insurancepremium);
                $("#totalinadvancefees").text(data.totalinadvancefees);

                $("#conditioninsurancefee").text(data.conditioninsurancefee);
                if (data.conditioninsurancefee == '-')
                    $('#hasinsurancefee').val(0);
                else
                    $('#hasinsurancefee').val(1);

                $("#conditioninsurancefeecustomerpaid").text(data.conditioninsurancefeecustomerpaid);
                $("#conditioninsurancefeecompanypaid").text(data.conditioninsurancefeecompanypaid);

                $("#conditioncompulsorymotorinsurancefee").text(data.conditioncompulsorymotorinsurancefee);
                if (data.conditioncompulsorymotorinsurancefee == '-')
                    $('#hascompulsorymotorinsurancefee').val(0);
                else
                    $('#hascompulsorymotorinsurancefee').val(1);

                $("#conditioncompulsorymotorinsurancefeecustomerpaid").text(data.conditioncompulsorymotorinsurancefeecustomerpaid);
                $("#conditioncompulsorymotorinsurancefeecompanypaid").text(data.conditioncompulsorymotorinsurancefeecompanypaid);
                $("#capitalinsurance").text(data.capitalinsurance);
                $("#insurancecompany").text(data.insurancecompany);
                $("#compulsorymotorinsurancecompany").text(data.compulsorymotorinsurancecompany);
                $("#note1insurancefee").text(data.note1insurancefee);
                $("#note1insurancefeevat").text(data.note1insurancefeevat);
                $("#note1insurancefeeincludevat").text(data.note1insurancefeeincludevat);
                $("#note1compulsorymotorinsurancefee").text(data.note1compulsorymotorinsurancefee);
                $("#note1compulsorymotorinsurancefeevat").text(data.note1compulsorymotorinsurancefeevat);
                $("#note1compulsorymotorinsurancefeeincludevat").text(data.note1compulsorymotorinsurancefeeincludevat);
                $("#note1totalfee").text(data.note1totalfee);
                $("#note1totalfeevat").text(data.note1totalfeevat);
                $("#note1totalfeeincludevat").text(data.note1totalfeeincludevat);
                $("#cashpledgeredlabel").text(data.cashpledgeredlabel);
                $("#cashpledge").text(data.cashpledge);
                $("#totalcashpledge").text(data.totalcashpledge);
                $("#totalcash").text(data.totalcash);

                if (data.incasefinace == 1) {
                    $("#incasefinace").css("display", "block");
                    $("#finacecompany").text('กรณีจัดไฟแนนซ์ - ' + data.finacecompany);
                    $('#purchasetype').val(1);

                    $("#interest").text(data.interest);
                    $("#installments").text(data.installments);
                    $("#yodjud").text(data.yodjud);
                    $("#comfinpercent").text(data.comfinpercent);
                    $("#comfinyear").text(data.comfinyear);
                    $("#salename").text(data.salename);
                    $("#incasefinaceinsurancefee").text(data.incasefinaceinsurancefee);
                    $("#note2insurancefeewhtax").text(data.note2insurancefeewhtax);
                    $("#note2insurancefee").text(data.note2insurancefee);
                    $("#note2insurancefeeexpense").text(data.note2insurancefeeexpense);
                    $("#note2insurancefeeincome").text(data.note2insurancefeeincome);
                    $("#incasefinacecompulsorymotorinsurancefee").text(data.incasefinacecompulsorymotorinsurancefee);
                    $("#note2compulsorymotorinsurancefeewhtax").text(data.note2compulsorymotorinsurancefeewhtax);
                    $("#note2compulsorymotorinsurancefee").text(data.note2compulsorymotorinsurancefee);
                    $("#note2compulsorymotorinsurancefeeexpense").text(data.note2compulsorymotorinsurancefeeexpense);
                    $("#note2compulsorymotorinsurancefeeincome").text(data.note2compulsorymotorinsurancefeeincome);
                    $("#incasefinacefirstinstallmentpayamount").text(data.incasefinacefirstinstallmentpayamount);
                    $("#note2firstinstallmentpayamount").text(data.note2firstinstallmentpayamount);
                    $("#incasefinacepayinadvanceamount").text(data.incasefinacepayinadvanceamount);
                    $("#note2payinadvanceamount").text(data.note2payinadvanceamount);
                    $("#incasefinaceinsurancepremium").text(data.incasefinaceinsurancepremium);
                    $("#note2insurancepremium").text(data.note2insurancepremium);
                    $("#totalincasefinace").text(data.totalincasefinace);
                    $("#incasefinacereceivedcash").text(data.incasefinacereceivedcash);
                    $("#note2total1").text(data.note2total1);
                    $("#note2total2").text(data.note2total2);
                    $("#note2total3").text(data.note2total3);
                    $("#incasefinacesubsidise").text(data.incasefinacesubsidise);
                    $("#incasefinacesubsidisevat").text(data.incasefinacesubsidisevat);
                    $("#incasefinacesubsidisewithvat").text(data.incasefinacesubsidisewithvat);
                    $("#note2subsidisewhtax").text(data.note2subsidisewhtax);
                    $("#note2subsidisetotal").text(data.note2subsidisetotal);
                    $("#incasefinacehassubsidisereceivedcash").text(data.incasefinacehassubsidisereceivedcash);
                    $("#note2totalwhtax").text(data.note2totalwhtax);
                    $("#incasefinacecomfinamount").val(parseFloat(data.incasefinacecomfinamount).toFixed(2));
                    $("#incasefinacecomfinvat").val(parseFloat(data.incasefinacecomfinvat).toFixed(2));
                    $("#incasefinacecomfinamountwithvat").val(parseFloat(data.incasefinacecomfinamountwithvat).toFixed(2));
                    $("#incasefinacecomfinwhtax").val(parseFloat(data.incasefinacecomfinwhtax).toFixed(2));
                    $("#incasefinacecomfintotal").val(parseFloat(data.incasefinacecomfintotal).toFixed(2));
                    $("#systemcalincasefinacecomfinamount").val(parseFloat(data.incasefinacecomfinamount).toFixed(2));
                    $("#systemcalincasefinacecomfinvat").val(parseFloat(data.incasefinacecomfinvat).toFixed(2));
                    $("#systemcalincasefinacecomfinamountwithvat").val(parseFloat(data.incasefinacecomfinamountwithvat).toFixed(2));
                    $("#systemcalincasefinacecomfinwhtax").val(parseFloat(data.incasefinacecomfinwhtax).toFixed(2));
                    $("#systemcalincasefinacecomfintotal").val(parseFloat(data.incasefinacecomfintotal).toFixed(2));
                    $("#incasefinacecomextraamount").text(data.incasefinacecomextraamount);
                    $("#incasefinacecomextravat").text(data.incasefinacecomextravat);
                    $("#incasefinacecomextraamountwithvat").text(data.incasefinacecomextraamountwithvat);
                    $("#incasefinacecomextrawhtax").text(data.incasefinacecomextrawhtax);
                    $("#incasefinacecomextratotal").text(data.incasefinacecomextratotal);
                    $("#incasefinacecompaamount").text(data.incasefinacecompaamount);
                    $("#incasefinacecompavat").text(data.incasefinacecompavat);
                    $("#incasefinacecompaamountwithvat").text(data.incasefinacecompaamountwithvat);
                    $("#incasefinacecompawhtax").text(data.incasefinacecompawhtax);
                    $("#incasefinacecompatotal").text(data.incasefinacecompatotal);
                    $("#incasefinacetotalcomamount").text(data.incasefinacetotalcomamount);
                    $("#incasefinacetotalcomvat").text(data.incasefinacetotalcomvat);
                    $("#incasefinacetotalcomwhtax").text(data.incasefinacetotalcomwhtax);
                    $("#incasefinacetotalcomtotal").text(data.incasefinacetotalcomtotal);
                    $("#receivedcashfromfinace").text(data.receivedcashfromfinace);
                    $("#receivedcashfromfinacenet").val(parseFloat(data.receivedcashfromfinacenet).toFixed(2));
                    $("#receivedcashfromfinaceshort").text(data.receivedcashfromfinaceshort);
                    $("#receivedcashfromfinacenetshort").val(parseFloat(data.receivedcashfromfinacenetshort).toFixed(2));
                    $("#receivedcashfromfinaceover").text(data.receivedcashfromfinaceover);
                    $("#receivedcashfromfinacenetover").val(parseFloat(data.receivedcashfromfinacenetover).toFixed(2));
                }
                else {
                    $("#incasefinace").css("display", "none");
                    $('#purchasetype').val(0);
                }

                $("#tradereceivableaccount2amount").text(data.tradereceivableaccount2amount);
                $("#oldcarprice").text(data.oldcarprice);
                $("#overdue").text(data.overdue);
                $("#tradereceivableaccount2remainingamount").text(data.tradereceivableaccount2remainingamount);

                var incasefinacereceivedcash = data.incasefinacereceivedcash == '-' ? 0 : data.incasefinacereceivedcash.replace(",", "");
                var tradereceivableaccount1amount = parseFloat(finalopenbill) - parseFloat(incasefinacereceivedcash);
                if (tradereceivableaccount1amount == 0) $("#tradereceivableaccount1amount").text("-");
                else $("#tradereceivableaccount1amount").text(numberWithCommas(parseFloat(tradereceivableaccount1amount).toFixed(2)));

                if (tradereceivableaccount1amount == 0) $("#ar").text("-");
                else $("#ar").text(numberWithCommas(parseFloat(tradereceivableaccount1amount).toFixed(2)));
                $("#ins").text(data.ins);
                $("#prb").text(data.prb);
                $("#dc").text(data.dc);

                var adj = $('#adj').val();
                if (adj == null || adj == '')
                    adj = 0;

                var cash = parseFloat(tradereceivableaccount1amount) - (data.ins == '-' ? 0 : parseFloat(data.ins.replace(",", "")))
                        - (data.prb == '-' ? 0 : parseFloat(data.prb.replace(",", "")))
                        - (data.dc == '-' ? 0 : parseFloat(data.dc.replace(",", ""))) + parseFloat(adj);
                if (cash == 0) $("#cash").text("-");
                else $("#cash").text(numberWithCommas(parseFloat(cash).toFixed(2)));

                $("#totalacc1").text(numberWithCommas(parseFloat(cash).toFixed(2)));
                $("#totalaccount1").val(parseFloat(cash).toFixed(2));
                $("#totalacc1short").text(numberWithCommas(parseFloat(cash).toFixed(2)));
                $("#totalaccount1short").val(parseFloat(cash).toFixed(2));
                $("#totalacc1over").text("-");
                $("#totalaccount1over").val(parseFloat(0).toFixed(2));

                var tradereceivableaccount2amount = data.tradereceivableaccount2amount == '-' ? 0 : data.tradereceivableaccount2amount.replace(",", "");
                var totalaccount2;
                if (parseFloat(cash) < parseFloat(tradereceivableaccount2amount)) {
                    totalaccount2 = parseFloat(tradereceivableaccount2amount) - parseFloat(cash);
                    $("#totalacc2").text(numberWithCommas(parseFloat(totalaccount2).toFixed(2)));
                    $("#totalaccount2").val(parseFloat(totalaccount2).toFixed(2));
                    $("#totalacc2short").text(numberWithCommas(parseFloat(totalaccount2).toFixed(2)));
                    $("#totalaccount2short").val(parseFloat(totalaccount2).toFixed(2));
                    $("#totalacc2over").text("-");
                    $("#totalaccount2over").val(parseFloat(0).toFixed(2));
                }
                else {
                    totalaccount2 = 0;
                    $("#totalacc2").text("-");
                    $("#totalaccount2").val(parseFloat(0).toFixed(2));
                    $("#totalacc2short").text("-");
                    $("#totalaccount2short").val(parseFloat(0).toFixed(2));
                    $("#totalacc2over").text("-");
                    $("#totalaccount2over").val(parseFloat(0).toFixed(2));
                }
            });
        }

        function TotalotherfeedetailToggle() {
            if ($(".totalotherfeedetail").css('display') == 'block') {
                $(".totalotherfeedetail").css("display", "none");
            }
            else {
                $(".totalotherfeedetail").css("display", "block");
            }
        }

        function AdditionalopenbillChange() {
            var openbill = $("#openbill").text();
            if (openbill == '-') openbill = 0;
            else openbill = openbill.replace(",", "");

            var additionalopenbill = $('#additionalopenbill').val();
            if (additionalopenbill == null || additionalopenbill == '')
                additionalopenbill = 0;

            var finalopenbill = parseFloat(openbill) + parseFloat(additionalopenbill);
            if (finalopenbill == 0) $("#finalopenbill").text("-");
            else $("#finalopenbill").text(numberWithCommas(parseFloat(finalopenbill).toFixed(2)));

            var vatoffinalopenbill = parseFloat(finalopenbill) * parseFloat(0.07);
            if (vatoffinalopenbill == 0) $("#vatoffinalopenbill").text("-");
            else $("#vatoffinalopenbill").text(numberWithCommas(parseFloat(vatoffinalopenbill).toFixed(2)));

            var finalopenbillwithoutvat = parseFloat(finalopenbill) - parseFloat(vatoffinalopenbill);
            if (finalopenbillwithoutvat == 0) $("#finalopenbillwithoutvat").text("-");
            else $("#finalopenbillwithoutvat").text(numberWithCommas(parseFloat(finalopenbillwithoutvat).toFixed(2)));

            var realsalesprice = $("#realsalesprice").text();
            if (realsalesprice == '-') realsalesprice = 0;
            else realsalesprice = realsalesprice.replace(",", "");

            var realsalespricewithoutvat = parseFloat(realsalesprice) - parseFloat(vatoffinalopenbill);
            if (realsalespricewithoutvat == 0) $("#realsalespricewithoutvat").text("-");
            else $("#realsalespricewithoutvat").text(numberWithCommas(parseFloat(realsalespricewithoutvat).toFixed(2)));

            var incasefinacereceivedcash = $("#incasefinacereceivedcash").text();
            if (incasefinacereceivedcash == '-') incasefinacereceivedcash = 0;
            else incasefinacereceivedcash = incasefinacereceivedcash.replace(",", "");

            var tradereceivableaccount1amount = parseFloat(finalopenbill) - parseFloat(incasefinacereceivedcash);
            var ar = tradereceivableaccount1amount;
            if (tradereceivableaccount1amount == 0) $("#tradereceivableaccount1amount").text("-");
            else $("#tradereceivableaccount1amount").text(numberWithCommas(parseFloat(tradereceivableaccount1amount).toFixed(2)));
            if (ar == 0) $("#ar").text("-");
            else $("#ar").text(numberWithCommas(parseFloat(ar).toFixed(2)));

            AdjChange();
        }

        function AdjChange() {
            var ar = $("#ar").text();
            if (ar == '-') ar = 0;
            else ar = ar.replace(",", "");

            var ins = $("#ins").text();
            if (ins == '-') ins = 0;
            else ins = ins.replace(",", "");

            var prb = $("#prb").text();
            if (prb == '-') prb = 0;
            else prb = prb.replace(",", "");

            var dc = $("#dc").text();
            if (dc == '-') dc = 0;
            else dc = dc.replace(",", "");

            var adj = $('#adj').val();
            if (adj == null || adj == '')
                adj = 0;

            var cash = parseFloat(ar) - parseFloat(ins) - parseFloat(prb) - parseFloat(dc) + parseFloat(adj);
            if (cash == 0) $("#cash").text("-");
            else $("#cash").text(numberWithCommas(parseFloat(cash).toFixed(2)));

            if (cash == 0) $("#totalacc1").text("-");
            else $("#totalacc1").text(numberWithCommas(parseFloat(cash).toFixed(2)));

            $("#totalaccount1").val(parseFloat(cash).toFixed(2));

            var tradereceivableaccount2amount = $("#tradereceivableaccount2amount").text();
            if (tradereceivableaccount2amount == '-') tradereceivableaccount2amount = 0;
            else tradereceivableaccount2amount = tradereceivableaccount2amount.replace(",", "");

            if (parseFloat(cash) < parseFloat(tradereceivableaccount2amount)) {
                var totalaccount2 = parseFloat(tradereceivableaccount2amount) - parseFloat(cash);
                $("#totalacc2").text(numberWithCommas(parseFloat(totalaccount2).toFixed(2)));
                $("#totalaccount2").val(parseFloat(totalaccount2).toFixed(2));
            }
            else {
                $("#totalacc2").text("-");
                $("#totalaccount2").val(parseFloat(0).toFixed(2));
            }

            CalShortOver1();
        }

        function IncasefinacecomfintotalChange() {
            var incasefinacehassubsidisereceivedcash = $("#incasefinacehassubsidisereceivedcash").text();
            if (incasefinacehassubsidisereceivedcash == '-') incasefinacehassubsidisereceivedcash = 0;
            else incasefinacehassubsidisereceivedcash = incasefinacehassubsidisereceivedcash.replace(",", "");

            var note2totalwhtax = $("#note2totalwhtax").text();
            if (note2totalwhtax == '-') note2totalwhtax = 0;
            else note2totalwhtax = note2totalwhtax.replace(",", "");

            var incasefinacecomfintotal = $('#incasefinacecomfintotal').val();
            if (incasefinacecomfintotal == null || incasefinacecomfintotal == '')
                incasefinacecomfintotal = 0;

            var incasefinacecomextratotal = $("#incasefinacecomextratotal").text();
            if (incasefinacecomextratotal == '-') incasefinacecomextratotal = 0;
            else incasefinacecomextratotal = incasefinacecomextratotal.replace(",", "");

            var incasefinacecompatotal = $("#incasefinacecompatotal").text();
            if (incasefinacecompatotal == '-') incasefinacecompatotal = 0;
            else incasefinacecompatotal = incasefinacecompatotal.replace(",", "");

            var incasefinacetotalcomtotal = parseFloat(incasefinacecomfintotal) + parseFloat(incasefinacecomextratotal) + parseFloat(incasefinacecompatotal);
            if (incasefinacetotalcomtotal == 0) $("#incasefinacetotalcomtotal").text("-");
            else $("#incasefinacetotalcomtotal").text(numberWithCommas(parseFloat(incasefinacetotalcomtotal).toFixed(2)));

            var receivedcashfromfinace = parseFloat(incasefinacehassubsidisereceivedcash) + parseFloat(note2totalwhtax) + parseFloat(incasefinacetotalcomtotal);
            if (receivedcashfromfinace == 0) $("#receivedcashfromfinace").text("-");
            else $("#receivedcashfromfinace").text(numberWithCommas(parseFloat(receivedcashfromfinace).toFixed(2)));

            $("#receivedcashfromfinacenet").val(parseFloat(receivedcashfromfinace).toFixed(2));
            CalShortOver0();
        }

        function IncasefinacecomfinamountChange() {
            var incasefinacecomfinamount = $('#incasefinacecomfinamount').val();
            if (incasefinacecomfinamount == null || incasefinacecomfinamount == '')
                incasefinacecomfinamount = 0;

            var incasefinacecomextraamount = $("#incasefinacecomextraamount").text();
            if (incasefinacecomextraamount == '-') incasefinacecomextraamount = 0;
            else incasefinacecomextraamount = incasefinacecomextraamount.replace(",", "");

            var incasefinacecompaamount = $("#incasefinacecompaamount").text();
            if (incasefinacecompaamount == '-') incasefinacecompaamount = 0;
            else incasefinacecompaamount = incasefinacecompaamount.replace(",", "");

            var incasefinacetotalcomamount = parseFloat(incasefinacecomfinamount) + parseFloat(incasefinacecomextraamount) + parseFloat(incasefinacecompaamount);
            if (incasefinacetotalcomamount == 0) $("#incasefinacetotalcomamount").text("-");
            else $("#incasefinacetotalcomamount").text(numberWithCommas(parseFloat(incasefinacetotalcomamount).toFixed(2)));
        }

        function IncasefinacecomfinvatChange() {
            var incasefinacecomfinvat = $('#incasefinacecomfinvat').val();
            if (incasefinacecomfinvat == null || incasefinacecomfinvat == '')
                incasefinacecomfinvat = 0;

            var incasefinacecomextravat = $("#incasefinacecomextravat").text();
            if (incasefinacecomextravat == '-') incasefinacecomextravat = 0;
            else incasefinacecomextravat = incasefinacecomextravat.replace(",", "");

            var incasefinacecompavat = $("#incasefinacecompavat").text();
            if (incasefinacecompavat == '-') incasefinacecompavat = 0;
            else incasefinacecompavat = incasefinacecompavat.replace(",", "");

            var incasefinacetotalcomvat = parseFloat(incasefinacecomfinvat) + parseFloat(incasefinacecomextravat) + parseFloat(incasefinacecompavat);
            if (incasefinacetotalcomvat == 0) $("#incasefinacetotalcomvat").text("-");
            else $("#incasefinacetotalcomvat").text(numberWithCommas(parseFloat(incasefinacetotalcomvat).toFixed(2)));
        }

        function IncasefinacecomfinwhtaxChange() {
            var incasefinacecomfinwhtax = $('#incasefinacecomfinwhtax').val();
            if (incasefinacecomfinwhtax == null || incasefinacecomfinwhtax == '')
                incasefinacecomfinwhtax = 0;

            var incasefinacecomextrawhtax = $("#incasefinacecomextrawhtax").text();
            if (incasefinacecomextrawhtax == '-') incasefinacecomextrawhtax = 0;
            else incasefinacecomextrawhtax = incasefinacecomextrawhtax.replace(",", "");

            var incasefinacecompawhtax = $("#incasefinacecompawhtax").text();
            if (incasefinacecompawhtax == '-') incasefinacecompawhtax = 0;
            else incasefinacecompawhtax = incasefinacecompawhtax.replace(",", "");

            var incasefinacetotalcomwhtax = parseFloat(incasefinacecomfinwhtax) + parseFloat(incasefinacecomextrawhtax) + parseFloat(incasefinacecompawhtax);
            if (incasefinacetotalcomwhtax == 0) $("#incasefinacetotalcomwhtax").text("-");
            else $("#incasefinacetotalcomwhtax").text(numberWithCommas(parseFloat(incasefinacetotalcomwhtax).toFixed(2)));
        }

        function CalShortOver0() {
            var totalreceived = 0;
            var datas = $("#grid-table-in-form0").jqGrid('getGridParam', 'data');
            if (datas.length > 0) {
                datas.forEach(function (arrayItem) {
                    if (arrayItem.type == 0)
                        totalreceived = parseFloat(totalreceived) + parseFloat(arrayItem.amount);
                    else if (arrayItem.type == 1)
                        totalreceived = parseFloat(totalreceived) - parseFloat(arrayItem.amount);
                });
            }

            var receivedcashfromfinacenet = $('#receivedcashfromfinacenet').val();
            if (receivedcashfromfinacenet == null || receivedcashfromfinacenet == '')
                receivedcashfromfinacenet = 0;

            if (receivedcashfromfinacenet == totalreceived) {
                $("#receivedcashfromfinaceshort").text("-");
                $("#receivedcashfromfinacenetshort").val(0);
                $("#receivedcashfromfinaceover").text("-");
                $("#receivedcashfromfinacenetover").val(0);
            }
            else if (receivedcashfromfinacenet > totalreceived) {
                var shortamount = parseFloat(receivedcashfromfinacenet) - parseFloat(totalreceived);
                $("#receivedcashfromfinaceshort").text(numberWithCommas(parseFloat(shortamount).toFixed(2)));
                $("#receivedcashfromfinacenetshort").val(parseFloat(shortamount).toFixed(2));
                $("#receivedcashfromfinaceover").text("-");
                $("#receivedcashfromfinacenetover").val(0);
            }
            else if (receivedcashfromfinacenet < totalreceived) {
                var overamount = parseFloat(totalreceived) - parseFloat(receivedcashfromfinacenet);
                $("#receivedcashfromfinaceshort").text("-");
                $("#receivedcashfromfinacenetshort").val(0);
                $("#receivedcashfromfinaceover").text(numberWithCommas(parseFloat(overamount).toFixed(2)));
                $("#receivedcashfromfinacenetover").val(parseFloat(overamount).toFixed(2));
            }
        }

        function CalShortOver1() {
            var totalreceivedacc1 = 0;
            var totalreceivedacc2 = 0;
            var datas = $("#grid-table-in-form1").jqGrid('getGridParam', 'data');
            if (datas.length > 0) {
                datas.forEach(function (arrayItem) {
                    if (arrayItem.accountgroup == 1) {
                        if (arrayItem.type == 0)
                            totalreceivedacc1 = parseFloat(totalreceivedacc1) + parseFloat(arrayItem.amount);
                        else if (arrayItem.type == 1)
                            totalreceivedacc1 = parseFloat(totalreceivedacc1) - parseFloat(arrayItem.amount);
                    }
                    else if (arrayItem.accountgroup == 2) {
                        if (arrayItem.type == 0)
                            totalreceivedacc2 = parseFloat(totalreceivedacc2) + parseFloat(arrayItem.amount);
                        else if (arrayItem.type == 1)
                            totalreceivedacc2 = parseFloat(totalreceivedacc2) - parseFloat(arrayItem.amount);
                    }
                });
            }

            var totalaccount1 = $('#totalaccount1').val();
            if (totalaccount1 == null || totalaccount1 == '')
                totalaccount1 = 0;

            if (totalaccount1 == totalreceivedacc1) {
                $("#totalacc1short").text("-");
                $("#totalaccount1short").val(0);
                $("#totalacc1over").text("-");
                $("#totalaccount1over").val(0);
            }
            else if (totalaccount1 > totalreceivedacc1) {
                var shortamount = parseFloat(totalaccount1) - parseFloat(totalreceivedacc1);
                $("#totalacc1short").text(numberWithCommas(parseFloat(shortamount).toFixed(2)));
                $("#totalaccount1short").val(parseFloat(shortamount).toFixed(2));
                $("#totalacc1over").text("-");
                $("#totalaccount1over").val(0);
            }
            else if (totalaccount1 < totalreceivedacc1) {
                var overamount = parseFloat(totalreceivedacc1) - parseFloat(totalaccount1);
                $("#totalacc1short").text("-");
                $("#totalaccount1short").val(0);
                $("#totalacc1over").text(numberWithCommas(parseFloat(overamount).toFixed(2)));
                $("#totalaccount1over").val(parseFloat(overamount).toFixed(2));
            }

            var totalaccount2 = $('#totalaccount2').val();
            if (totalaccount2 == null || totalaccount2 == '')
                totalaccount2 = 0;

            if (totalaccount2 == totalreceivedacc2) {
                $("#totalacc2short").text("-");
                $("#totalaccount2short").val(0);
                $("#totalacc2over").text("-");
                $("#totalaccount2over").val(0);
            }
            else if (totalaccount2 > totalreceivedacc2) {
                var shortamount = parseFloat(totalaccount2) - parseFloat(totalreceivedacc2);
                $("#totalacc2short").text(numberWithCommas(parseFloat(shortamount).toFixed(2)));
                $("#totalaccount2short").val(parseFloat(shortamount).toFixed(2));
                $("#totalacc2over").text("-");
                $("#totalaccount2over").val(0);
            }
            else if (totalaccount2 < totalreceivedacc2) {
                var overamount = parseFloat(totalreceivedacc2) - parseFloat(totalaccount2);
                $("#totalacc2short").text("-");
                $("#totalaccount2short").val(0);
                $("#totalacc2over").text(numberWithCommas(parseFloat(overamount).toFixed(2)));
                $("#totalaccount2over").val(parseFloat(overamount).toFixed(2));
            }
        }
    </script>

    @if($oper == 'new')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-info-circle"></i>
            เพิ่มรายละเอียดเพื่อการบันทึกบัญชีใหม่</h3>
    @elseif($oper == 'edit')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-info-circle"></i>
            แก้ไขรายละเอียดเพื่อการบันทึกบัญชี
        </h3>
    @elseif($oper == 'view')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-info-circle"></i>
            ดูข้อมูลรายละเอียดเพื่อการบันทึกบัญชี
        </h3>
    @endif

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>ขออภัย!</strong> มีปัญหาบางอย่างกับการป้อนข้อมูลของคุณ<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($oper == 'new')
        {!! Form::model($accountingdetail, array('url' => 'accountingdetail/save', 'id'=>'form-accountingdetail', 'class'=>'form-horizontal', 'role'=>'form', 'files'=>true)) !!}
    @elseif($oper == 'edit')
        {!! Form::model($accountingdetail, array('url' => 'accountingdetail/save', 'id'=>'form-accountingdetail', 'class'=>'form-horizontal', 'role'=>'form', 'files'=>true)) !!}
        {!! Form::hidden('id') !!}
    @elseif($oper == 'view')
        {!! Form::model($accountingdetail, array('id'=>'form-accountingdetail', 'class'=>'form-horizontal', 'role'=>'form')) !!}
    @endif

    {!! Form::hidden('purchasetype',null,array('id'=>'purchasetype')) !!}

    <div class="form-group" style="margin-top:10px;">
        {!! Form::label('carpaymentid', 'การจอง (มีการชำระเงินแล้ว) เล่มที่/เลขที่', array('style'=>'margin-left: 15px; margin-right: 10px;')) !!}
            @if($oper == 'new')
                {!! Form::select('carpaymentid', $carpaymentselectlist, null, array('id'=>'carpaymentid', 'class' => 'chosen-select', 'onchange'=>'CarpaymentChange(this)')) !!}
            @else
                {!! Form::select('carpaymentid', $carpaymentselectlist, null, array('id'=>'carpaymentid', 'class' => 'chosen-select', 'onchange'=>'CarpaymentChange(this)', 'disabled'=>'disabled')) !!}
                {!! Form::hidden('carpaymentid') !!}
            @endif
    </div>

    <h4 style="display: inline-block;">บริษัท ฮิปโปร พาวเวอร์ จำกัด : สาขา</h4> <h4
            style="display: inline-block;" id="branchname">{{$accountingdetail->branchname}}</h4><br>

    <div>
        <table>
            <tbody>
            <tr>
                <td><label style="width:60px; font-weight:bold">ชื่อลูกค้า</label></td>
                <td class="underline_g"><label style="width:220px; text-align:center;"
                                               id="customername">{{$accountingdetail->customername}}</label>
                </td>
                <td><label style="width:90px; font-weight:bold; margin-left:20px;">เลขที่ใบกำกับ</label></td>
                <td>{!! Form::text('invoiceno', null, array('id'=>'invoiceno')) !!}</td>
                <td><label style="width:35px; font-weight:bold; margin-left:20px;">วันที่</label></td>
                <td><label>
                        <div class="input-group">
                            {!! Form::text('date', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'date')) !!}
                            <span class="input-group-addon">
                        <i class="fa fa-calendar bigger-110"></i>
                    </span>
                        </div>
                    </label></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-xs-12 ">

            <div class="row">
                <div class="col-xs-1 col-sm-1"></div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">รายละเอียด</h4>
                            <div class="widget-toolbar">
                                <a href="form-elements.html#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>

                        <div class="widget-body">
                            <div class="widget-body-inner" style="display: block;">
                                <div class="widget-main">
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:490px;">1. รายได้ค่ารถ</label>
                                            <label style="width:45px;"></label>
                                        </div>
                                        <div style="width:550px; float:left;">
                                            <label style="width:140px; font-weight:bold;">ราคาเปิดบิล</label>
                                            <label style="width: 100px; font-weight:bold; text-align:right;"
                                                   id="openbill">{{$accountingdetail->openbill}}</label>
                                            <label style="width:20px; text-align:center;">+/-</label>
                                            {!! Form::number('additionalopenbill', null, array('step' => '0.01','style'=>'width: 100px; height: 22px; padding:0; color:black; font-weight:bold; text-align:right;','id'=>'additionalopenbill','onchange'=>'AdditionalopenbillChange();')) !!}
                                            <label style="width:20px; text-align:center;">=</label>
                                            <label style="width: 100px; font-weight:bold; text-align:right; color:red;"
                                                   id="finalopenbill">{{$accountingdetail->finalopenbill}}</label>
                                            <label style="font-weight:bold; padding-left:5px;">(1)*</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">ราคารถขายจริง (รวม
                                                VAT)</label>
                                            <label class="underline_db" style="width: 100px; font-weight:bold;"
                                                   id="realsalesprice">{{$accountingdetail->realsalesprice}}</label>
                                            <label style="width:45px; text-align:right;">(1)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:140px; padding-left:20px;">ราคาตั้งขาย + สี</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="carpriceinpricelist">{{$accountingdetail->carpriceinpricelist}}</label>
                                            <label style="width:20px; text-align:center;">+</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="colorprice">{{$accountingdetail->colorprice}}</label>
                                            <label style="width:20px; text-align:center;">=</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="carwithcolorprice">{{$accountingdetail->carwithcolorprice}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">หัก VAT 7% (ตามบัญชี
                                                1)</label>
                                            <label class="underline" style="width:100px;"
                                                   id="vatoffinalopenbill">{{$accountingdetail->vatoffinalopenbill}}</label>
                                            <label style="width:45px; text-align:right;">(2)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:140px; padding-left:20px;">ขายอุปกรณ์จริง</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="accessoriesfeeincludeinyodjud">{{$accountingdetail->accessoriesfeeincludeinyodjud}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">ราคาค่ารถ (ตามบัญชี 1) ไม่รวม
                                                VAT (1)* - (2)</label>
                                            <label class="underline" style="width:100px;"
                                                   id="finalopenbillwithoutvat">{{$accountingdetail->finalopenbillwithoutvat}}</label>
                                            <label style="width:45px; text-align:right;">(3)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:140px; padding-left:20px;">บวกอุปกรณ์ (หลอก)</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="plusfakeaccessories">{{$accountingdetail->fakeaccessories}}</label>
                                            <label style="width:20px; text-align:center;"></label>
                                            <label style="width:110px;">หักอุปกรณ์ (หลอก)</label>
                                            <label style="width:10px; text-align:center;"></label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="minusfakeaccessories">{{$accountingdetail->fakeaccessories}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">ราคาค่ารถ (ตามบัญชี 2) ไม่รวม
                                                VAT (1) - (2)</label>
                                            <label class="underline" style="width:100px;"
                                                   id="realsalespricewithoutvat">{{$accountingdetail->realsalespricewithoutvat}}</label>
                                            <label style="width:45px; text-align:right;">(4)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:140px;"><span
                                                        style="font-weight:bold;text-decoration:underline; padding-left:20px;">หัก</span>
                                                ส่วนลด</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="discount">{{$accountingdetail->discount}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:490px;">2. รายได้อื่น</label>
                                            <label style="width:45px;"></label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:140px;"><span
                                                        style="font-weight:bold;text-decoration:underline; padding-left:20px;">หัก</span>
                                                Sub down</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="subdown">{{$accountingdetail->subdown}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">2.1 รายได้ค่าอุปกรณ์</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="accessoriesfeeactuallypaid">{{$accountingdetail->accessoriesfeeactuallypaid}}</label>
                                            <label style="width:45px; text-align:right;">(5)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:140px; font-weight:bold; text-decoration:underline; color:red; padding-left:20px;">คงเหลือขายจริง</label>
                                            <label style="width: 100px; text-align:right; font-weight:bold; color:red;"
                                                   id="realsalesprice2">{{$accountingdetail->realsalesprice}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">2.2
                                                รายได้ค่าจดทะเบียน</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="registrationfee">{{$accountingdetail->registrationfee}}</label>
                                            <label style="width:45px; text-align:right;">(6)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">รุ่น</label>
                                            <label style="width:330px; font-weight:bold;"
                                                   id="submodel">{{$accountingdetail->submodel}}</label>
                                            <label style="width:50px; font-weight:bold;">คันที่</label>
                                            <label style="width:50px; font-weight:bold;"
                                                   id="carno">{{$accountingdetail->carno}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">2.3 รายได้ค่า
                                                พ.ร.บ.(กรณีสด)</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="compulsorymotorinsurancefeecash">{{$accountingdetail->compulsorymotorinsurancefeecash}}</label>
                                            <label style="width:45px; text-align:right;">(7)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">เลขเครื่อง</label>
                                            <label style="width:170px; font-weight:bold;"
                                                   id="engineno">{{$accountingdetail->engineno}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">2.4
                                                รายได้ค่าเบี้ยประกันภัย(กรณีสด)</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="insurancefeecash">{{$accountingdetail->insurancefeecash}}</label>
                                            <label style="width:45px; text-align:right;">(8)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">เลขถัง</label>
                                            <label style="width:170px; font-weight:bold;"
                                                   id="chassisno">{{$accountingdetail->chassisno}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">2.5
                                                รายได้ค่าดำเนินการ</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="implementfee">{{$accountingdetail->implementfee}}</label>
                                            <label style="width:45px; text-align:right;">(9)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">สี</label>
                                            <label class="dashed"
                                                   style="width:70px; font-weight:bold; text-align:center;"
                                                   id="color">{{$accountingdetail->color}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">2.6 รายได้อื่น <a
                                                        href="javascript:TotalotherfeedetailToggle();"> - รายละอียด</a></label>
                                            <label class="underline" style="width:100px;"
                                                   id="totalotherfee">{{$accountingdetail->totalotherfee}}</label>
                                            <label style="width:45px; text-align:right;">(10)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">ประเภทขาย</label>
                                            <label class="dashed"
                                                   style="width:70px; font-weight:bold; text-align:center; color:red;"
                                                   id="purchasetypetext">{{$accountingdetail->purchasetypetext}}</label>
                                            <label style="width:100px;"></label>
                                            <label style="width:30px;"></label>
                                            <label style="width:100px;">เงินดาวน์</label>
                                            <label class="dashed" style="width:134px; text-align:right;"
                                                   id="down">{{$accountingdetail->down}}</label>
                                        </div>
                                    </div>
                                    <div class="totalotherfeedetail" style="display: none">
                                        <div class="form-group" style="margin-left:10px;">
                                            <div style="width:540px; float:left; margin-right:20px;">
                                                <label style="width:388px; padding-left:45px;">- subsidise</label>
                                                <label class="underline_g" style="width:100px;"
                                                       id="subsidise">{{$accountingdetail->subsidise}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-left:10px;">
                                            <div style="width:540px; float:left; margin-right:20px;">
                                                <label style="width:388px; padding-left:45px;">- หัก ณ ที่จ่าย
                                                    (กรณีลูกค้าได้รับของแถม เช่น ทอง)</label>
                                                <label class="underline_g" style="width:100px;"
                                                       id="giveawaywithholdingtax">{{$accountingdetail->giveawaywithholdingtax}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-left:10px;">
                                            <div style="width:540px; float:left; margin-right:20px;">
                                                <label style="width:105px; padding-left:45px;">- อื่นๆ (1) : </label>
                                                <label class="underline_g" style="width: 279px; text-align:left;"
                                                       id="otherfeedetail">{{$accountingdetail->otherfeedetail}}</label>
                                                <label class="underline_g" style="width:100px;"
                                                       id="otherfee">{{$accountingdetail->otherfee}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-left:10px;">
                                            <div style="width:540px; float:left; margin-right:20px;">
                                                <label style="width:105px; padding-left:45px;">- อื่นๆ (2) : </label>
                                                <label class="underline_g" style="width: 279px; text-align:left;"
                                                       id="otherfeedetail2">{{$accountingdetail->otherfeedetail2}}</label>
                                                <label class="underline_g" style="width:100px;"
                                                       id="otherfee2">{{$accountingdetail->otherfee2}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-left:10px;">
                                            <div style="width:540px; float:left; margin-right:20px;">
                                                <label style="width:105px; padding-left:45px;">- อื่นๆ (3) : </label>
                                                <label class="underline_g" style="width: 279px; text-align:left;"
                                                       id="otherfeedetail3">{{$accountingdetail->otherfeedetail3}}</label>
                                                <label class="underline_g" style="width:100px;"
                                                       id="otherfee3">{{$accountingdetail->otherfee3}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; font-weight:bold; padding-left:55px;">รวมรายได้อื่น
                                                (5)+(6)+(7)+(8)+(9)+(10)</label>
                                            <label class="underline_db" style="width:100px;"
                                                   id="totalotherfees">{{$accountingdetail->totalotherfees}}</label>
                                            <label style="width:45px; text-align:right;">(11)</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:490px;">3. ค่าเบี้ยประกันภัยรับล่วงหน้า (ล/ค จ่ายกรณีจัด
                                                FIN)</label>
                                            <label style="width:45px;"></label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px; text-align:center;">เงื่อนไข*</label>
                                            <label style="width:100px; text-align:center;">ยอดรวม</label>
                                            <label style="width:30px;"></label>
                                            <label style="width:100px; text-align:center;">บริษัทชำระ</label>
                                            <label style="width:30px;"></label>
                                            <label style="width:100px; text-align:center;">ลูกค้าชำระ</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">3.1 ค่าเบี้ย (ป.1)
                                                รับล่วงหน้า</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="insurancefeefn">{{$accountingdetail->insurancefeefn}}</label>
                                            <label style="width:45px; text-align:right;">(12)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:65px;">ค่าเบี้ย ป.1</label>
                                            {!! Form::select('insurancefeereceiptcondition', array(null => 'เลือก', 1 => 'ชื่อบริษัท', 0 => 'ชื่อลูกค้า'), null, array('style' => 'padding:0; height:23px; width:75px;')) !!}
                                            <label class="dashed" style="width:100px; text-align:right; color:#0090FF;"
                                                   id="conditioninsurancefee">{{$accountingdetail->conditioninsurancefee}}</label>
                                            {!! Form::hidden('hasinsurancefee',null,array('id'=>'hasinsurancefee')) !!}
                                            <label style="width:30px;"></label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="conditioninsurancefeecompanypaid">{{$accountingdetail->conditioninsurancefeecompanypaid}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="conditioninsurancefeecustomerpaid">{{$accountingdetail->conditioninsurancefeecustomerpaid}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">3.2 พ.ร.บ.
                                                รับล่วงหน้า</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="compulsorymotorinsurancefeefn">{{$accountingdetail->compulsorymotorinsurancefeefn}}</label>
                                            <label style="width:45px; text-align:right;">(13)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:65px;">ค่า พ.ร.บ.</label>
                                            {!! Form::select('compulsorymotorinsurancefeereceiptcondition', array(null => 'เลือก', 1 => 'ชื่อบริษัท', 0 => 'ชื่อลูกค้า'), null, array('style' => 'padding:0; height:23px; width:75px;')) !!}
                                            <label class="dashed" style="width:100px; text-align:right; color:#0090FF;"
                                                   id="conditioncompulsorymotorinsurancefee">{{$accountingdetail->conditioncompulsorymotorinsurancefee}}</label>
                                            {!! Form::hidden('hascompulsorymotorinsurancefee',null,array('id'=>'hascompulsorymotorinsurancefee')) !!}
                                            <label style="width:30px;"></label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="conditioncompulsorymotorinsurancefeecompanypaid">{{$accountingdetail->conditioncompulsorymotorinsurancefeecompanypaid}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="conditioncompulsorymotorinsurancefeecustomerpaid">{{$accountingdetail->conditioncompulsorymotorinsurancefeecustomerpaid}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">3.3 ค่างวดแรก</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="firstinstallmentpayamount">{{$accountingdetail->firstinstallmentpayamount}}</label>
                                            <label style="width:45px; text-align:right;">(14)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">บจ. ประกัน</label>
                                            <label class="dashed" style="width:174px; text-align:center;"
                                                   id="insurancecompany">{{$accountingdetail->insurancecompany}}</label>
                                            <label style="width:30px;"></label>
                                            <label style="width:100px;">ทุนประกัน</label>
                                            <label class="dashed" style="width:134px; text-align:right;"
                                                   id="capitalinsurance">{{$accountingdetail->capitalinsurance}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:194px; padding-left:25px;">3.4 ค่างวดรับล่วงหน้า
                                                จำนวน</label>
                                            <label class="dashed" style="width:30px; text-align:center;"
                                                   id="installmentsinadvance">{{$accountingdetail->installmentsinadvance}}</label>
                                            <label style="width:40px;">งวด @</label>
                                            <label class="dashed" style="width:70px; text-align:center;"
                                                   id="amountperinstallment">{{$accountingdetail->amountperinstallment}}</label>
                                            <label style="width:40px;">/M</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="payinadvanceamount">{{$accountingdetail->payinadvanceamount}}</label>
                                            <label style="width:45px; text-align:right;">(15)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">บจ. พ.ร.บ.</label>
                                            <label class="dashed" style="width:174px; text-align:center;"
                                                   id="compulsorymotorinsurancecompany">{{$accountingdetail->compulsorymotorinsurancecompany}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">3.5 ค่าเบี้ยประกันชีวิต
                                                รับล่วงหน้า</label>
                                            <label class="underline" style="width:100px;"
                                                   id="insurancepremium">{{$accountingdetail->insurancepremium}}</label>
                                            <label style="width:45px; text-align:right;">(16)</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; font-weight:bold; padding-left:55px;">รวมรายการรับล่วงหน้า
                                                (12)+(13)+(14)+(15)+(16)</label>
                                            <label class="underline_db" style="width:100px;"
                                                   id="totalinadvancefees">{{$accountingdetail->totalinadvancefees}}</label>
                                            <label style="width:45px; text-align:right;">(17)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px; font-weight:bold; text-align:left; color: limegreen">Note
                                                1</label>
                                            <label style="width:100px; text-align:center;">ยอดก่อน VAT</label>
                                            <label style="width:30px;"></label>
                                            <label style="width:100px; text-align:center;">VAT</label>
                                            <label style="width:30px;"></label>
                                            <label style="width:100px; text-align:center;">ยอดรวม VAT</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:490px;">4. หักเงินมัดจำ</label>
                                            <label style="width:45px;"></label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px; text-align:left;">ค่าเบี้ย ป.1</label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note1insurancefee">{{$accountingdetail->note1insurancefee}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note1insurancefeevat">{{$accountingdetail->note1insurancefeevat}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note1insurancefeeincludevat">{{$accountingdetail->note1insurancefeeincludevat}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">4.1 เงินมัดจำป้ายแดง</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="cashpledgeredlabel">{{$accountingdetail->cashpledgeredlabel}}</label>
                                            <label style="width:45px; text-align:right;">(18)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px; text-align:left;">ค่า พ.ร.บ.</label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note1compulsorymotorinsurancefee">{{$accountingdetail->note1compulsorymotorinsurancefee}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note1compulsorymotorinsurancefeevat">{{$accountingdetail->note1compulsorymotorinsurancefeevat}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note1compulsorymotorinsurancefeeincludevat">{{$accountingdetail->note1compulsorymotorinsurancefeeincludevat}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">4.2 เงินมัดจำรถ</label>
                                            <label class="underline" style="width:100px;"
                                                   id="cashpledge">{{$accountingdetail->cashpledge}}</label>
                                            <label style="width:45px; text-align:right;">(19)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px; text-align:left;">รวม</label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note1totalfee">{{$accountingdetail->note1totalfee}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note1totalfeevat">{{$accountingdetail->note1totalfeevat}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note1totalfeeincludevat">{{$accountingdetail->note1totalfeeincludevat}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:auto; padding-left:25px;">4.2 เลขที่ใบรับเงิน</label>
                                            {!! Form::number('cashpledgereceiptbookno', null, array('style'=>'width: 50px; height: 22px; padding:0; text-align:center;','id'=>'cashpledgereceiptbookno')) !!}
                                            <label style="width:auto;">/</label>
                                            {!! Form::number('cashpledgereceiptno', null, array('style'=>'width: 50px; height: 22px; padding:0; text-align:center;','id'=>'cashpledgereceiptno')) !!}
                                            <label style="width:45px;">วันที่รับ</label>
                                            <div class="input-group" style="position: absolute; display: inline-table;">
                                                {!! Form::text('cashpledgereceiptdate', null, array('style'=>'height: 22px; padding:0;','class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'cashpledgereceiptdate')) !!}
                                                <span class="input-group-addon" style="padding:1px;">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; font-weight:bold; padding-left:55px;">รวมรายการหักเงินมัดจำ
                                                (18)+(19)</label>
                                            <label class="underline_db" style="width:100px;"
                                                   id="totalcashpledge">{{$accountingdetail->totalcashpledge}}</label>
                                            <label style="width:45px; text-align:right;">(20)</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; font-weight:bold;">รวมเงินสดที่ได้รับ ณ.วันรับรถ
                                                (1)+(11)+(17)-(20)</label>
                                            <label class="underline_db"
                                                   style="width:100px; color: red; font-weight: bold;"
                                                   id="totalcash">{{$accountingdetail->totalcash}}</label>
                                            <label style="width:45px; text-align:right;">(21)</label>
                                        </div>
                                    </div>


                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">

                                        </div>
                                        <div style="width:540px; float:left;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="incasefinace">
                <div class="col-xs-1 col-sm-1"></div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title" id="finacecompany">กรณีจัดไฟแนนซ์
                                - {{$accountingdetail->finacecompany}}</h4>
                            <div class="widget-toolbar">
                                <a href="form-elements.html#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>

                        <div class="widget-body">
                            <div class="widget-body-inner" style="display: block;">
                                <div class="widget-main">
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:125px; padding-left:25px;">ยอดจัด Int(%)</label>
                                            <label class="dashed" style="width:100px; text-align:center;"
                                                   id="interest">{{$accountingdetail->interest}}</label>
                                            <label style="width:30px; text-align: center;">งวด</label>
                                            <label class="dashed" style="width:100px; text-align:center;"
                                                   id="installments">{{$accountingdetail->installments}}</label>
                                            <label style="width:19px;"></label>
                                            <label style="width:100px; color:red; text-align:right;"
                                                   id="yodjud">{{$accountingdetail->yodjud}}</label>
                                            <label style="width:45px; text-align:right;">(22)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:55px;">Com-Fin</label>
                                            <label class="dashed" style="width:40px; text-align:center;"
                                                   id="comfinpercent">{{$accountingdetail->comfinpercent}}</label>
                                            <label style="width:10px; text-align: center;">%</label>
                                            <label class="dashed" style="width:30px; text-align:center;"
                                                   id="comfinyear">{{$accountingdetail->comfinyear}}</label>
                                            <label style="width:10px; text-align: center;">ปี</label>
                                            <label style="width:5px;"></label>
                                            <label style="width:100px; text-align:center;">Sale Name</label>
                                            <label style="width:20px;"></label>
                                            <label class="underline_g" style="width:230px; text-align:center;"
                                                   id="salename">{{$accountingdetail->salename}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:190px; padding-left:25px; font-weight:bold;">หัก :
                                                รายการรับล่วงหน้า</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px; font-weight:bold; text-align:left; color: limegreen">Note
                                                2</label>
                                            <label style="width:20px;"></label>
                                            <label style="width:100px; text-align:center;">รับล่วงหน้า</label>
                                            <label style="width:20px;"></label>
                                            <label style="width:100px; text-align:center;">ค่าใช้จ่าย</label>
                                            <label style="width:20px;"></label>
                                            <label style="width:100px; text-align:center;">รายได้</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:260px; padding-left:55px;">ค่าเบี้ยประกันภัยรถ</label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="incasefinaceinsurancefee">{{$accountingdetail->incasefinaceinsurancefee}}</label>
                                            <label style="width:124px;"></label>
                                            <label style="width:45px; text-align:right;">(23)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">W/H Tax</label>
                                            <label class="dotted" style="width:70px; text-align:right; color: #0090FF"
                                                   id="note2insurancefeewhtax">{{$accountingdetail->note2insurancefeewhtax}}</label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2insurancefee">{{$accountingdetail->note2insurancefee}}</label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2insurancefeeexpense">{{$accountingdetail->note2insurancefeeexpense}}</label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2insurancefeeincome">{{$accountingdetail->note2insurancefeeincome}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:260px; padding-left:55px;">ค่า พ.ร.บ.</label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="incasefinacecompulsorymotorinsurancefee">{{$accountingdetail->incasefinacecompulsorymotorinsurancefee}}</label>
                                            <label style="width:124px;"></label>
                                            <label style="width:45px; text-align:right;">(24)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">W/H Tax</label>
                                            <label class="dotted" style="width:70px; text-align:right; color: #0090FF"
                                                   id="note2compulsorymotorinsurancefeewhtax">{{$accountingdetail->note2compulsorymotorinsurancefeewhtax}}</label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2compulsorymotorinsurancefee">{{$accountingdetail->note2compulsorymotorinsurancefee}}</label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2compulsorymotorinsurancefeeexpense">{{$accountingdetail->note2compulsorymotorinsurancefeeexpense}}</label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2compulsorymotorinsurancefeeincome">{{$accountingdetail->note2compulsorymotorinsurancefeeincome}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:260px; padding-left:55px;">ค่างวดแรก</label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="incasefinacefirstinstallmentpayamount">{{$accountingdetail->incasefinacefirstinstallmentpayamount}}</label>
                                            <label style="width:124px;"></label>
                                            <label style="width:45px; text-align:right;">(25)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px;"></label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2firstinstallmentpayamount">{{$accountingdetail->note2firstinstallmentpayamount}}</label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;">-</label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;">-</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:260px; padding-left:55px;">ค่างวดรับล่วงหน้า</label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="incasefinacepayinadvanceamount">{{$accountingdetail->incasefinacepayinadvanceamount}}</label>
                                            <label style="width:124px;"></label>
                                            <label style="width:45px; text-align:right;">(26)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px;"></label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2payinadvanceamount">{{$accountingdetail->note2payinadvanceamount}}</label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;">-</label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;">-</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:260px; padding-left:55px;">ค่าเบี้ยประกันชีวิต</label>
                                            <label class="underline" style="width:100px; text-align:right;"
                                                   id="incasefinaceinsurancepremium">{{$accountingdetail->incasefinaceinsurancepremium}}</label>
                                            <label style="width:21px;"></label>
                                            <label class="underline" style="width:100px; color:red; text-align:right;"
                                                   id="totalincasefinace">{{$accountingdetail->totalincasefinace}}</label>
                                            <label style="width:45px; text-align:right;">(27)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px;"></label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2insurancepremium">{{$accountingdetail->note2insurancepremium}}</label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;">-</label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;">-</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px; font-weight:bold;">รับเงินค่ารถ
                                                (22)-(27)</label>
                                            <label class="underline_db"
                                                   style="width:100px; color:red; text-align:right; font-weight:bold;"
                                                   id="incasefinacereceivedcash">{{$accountingdetail->incasefinacereceivedcash}}</label>
                                            <label style="width:45px; text-align:right;">(28)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px;"></label>
                                            <label style="width:20px;"></label>
                                            <label class="underline" style="width:100px; text-align:right;"
                                                   id="note2total1">{{$accountingdetail->note2total1}}</label>
                                            <label style="width:20px;"></label>
                                            <label class="underline" style="width:100px; text-align:right;"
                                                   id="note2total2">{{$accountingdetail->note2total2}}</label>
                                            <label style="width:20px;"></label>
                                            <label class="underline" style="width:100px; text-align:right;"
                                                   id="note2total3">{{$accountingdetail->note2total3}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:25px; padding-left:25px; font-weight:bold;">หัก</label>
                                            <label style="width:95px; padding-left:25px;">SUBSIDISE</label>
                                            <label class="dotted" style="width:100px; text-align:center;"
                                                   id="incasefinacesubsidise">{{$accountingdetail->incasefinacesubsidise}}</label>
                                            <label style="width:30px; text-align: center;">Vat...</label>
                                            <label class="dotted" style="width:100px; text-align:center;"
                                                   id="incasefinacesubsidisevat">{{$accountingdetail->incasefinacesubsidisevat}}</label>
                                            <label style="width:21px;"></label>
                                            <label class="underline" style="width:100px; color:red; text-align:right;"
                                                   id="incasefinacesubsidisewithvat">{{$accountingdetail->incasefinacesubsidisewithvat}}</label>
                                            <label style="width:45px; text-align:right;">(29)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">W/H Tax</label>
                                            <label class="underline"
                                                   style="width:70px; text-align:right; color: #0090FF"
                                                   id="note2subsidisewhtax">{{$accountingdetail->note2subsidisewhtax}}</label>
                                            <label style="width:20px;"></label>
                                            <label style="width:100px; text-align:center;">Net...</label>
                                            <label style="width:20px;"></label>
                                            <label class="dotted"
                                                   style="width:100px; text-align:right; color:red; font-weight:bold;"
                                                   id="note2subsidisetotal">{{$accountingdetail->note2subsidisetotal}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px; font-weight:bold;">รับเงินค่ารถ
                                                (กรณีมี Subsidise) (28)-(29)</label>
                                            <label class="underline_db"
                                                   style="width:100px; text-align:right; font-weight:bold;"
                                                   id="incasefinacehassubsidisereceivedcash">{{$accountingdetail->incasefinacehassubsidisereceivedcash}}</label>
                                            <label style="width:45px; text-align:right;">(30)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">รวม W/H</label>
                                            <label class="underline_db"
                                                   style="width:70px; text-align:right; color: #0090FF"
                                                   id="note2totalwhtax">{{$accountingdetail->note2totalwhtax}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:25px; font-weight:bold;">Com</label>
                                            <label style="width:95px; padding-left:25px;">Fin...</label>
                                            {!! Form::number('incasefinacecomfinamount', null, array('step' => '0.01','style'=>'width: 100px; height: 22px; padding:0; color:red; text-align:right;','id'=>'incasefinacecomfinamount','onchange'=>'IncasefinacecomfinamountChange();')) !!}
                                            {!! Form::hidden('systemcalincasefinacecomfinamount',null,array('id'=>'systemcalincasefinacecomfinamount')) !!}
                                            <label style="width:30px; text-align: center;">Vat...</label>
                                            {!! Form::number('incasefinacecomfinvat', null, array('step' => '0.01','style'=>'width: 100px; height: 22px; padding:0; color:red; text-align:right;','id'=>'incasefinacecomfinvat','onchange'=>'IncasefinacecomfinvatChange();')) !!}
                                            {!! Form::hidden('systemcalincasefinacecomfinvat',null,array('id'=>'systemcalincasefinacecomfinvat')) !!}
                                            <label style="width:21px;"></label>
                                            {!! Form::number('incasefinacecomfinamountwithvat', null, array('step' => '0.01','style'=>'width: 100px; height: 22px; padding:0; color:red; text-align:right;','id'=>'incasefinacecomfinamountwithvat')) !!}
                                            {!! Form::hidden('systemcalincasefinacecomfinamountwithvat',null,array('id'=>'systemcalincasefinacecomfinamountwithvat')) !!}
                                        </div>
                                        <div style="width:540px; float:left;">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:25px; font-weight:bold;"></label>
                                            <label style="width:95px; padding-left:25px;">W/H ถูกหัก</label>
                                            {!! Form::number('incasefinacecomfinwhtax', null, array('step' => '0.01','style'=>'width: 100px; height: 22px; padding:0; color:#0090FF; text-align:right;','id'=>'incasefinacecomfinwhtax','onchange'=>'IncasefinacecomfinwhtaxChange();')) !!}
                                            {!! Form::hidden('systemcalincasefinacecomfinwhtax',null,array('id'=>'systemcalincasefinacecomfinwhtax')) !!}
                                            <label style="width:30px; text-align: center;">Net...</label>
                                            {!! Form::number('incasefinacecomfintotal', null, array('step' => '0.01','style'=>'width: 100px; height: 22px; padding:0; color:red; text-align:right; font-weight:bold;','id'=>'incasefinacecomfintotal','onchange'=>'IncasefinacecomfintotalChange();')) !!}
                                            {!! Form::hidden('systemcalincasefinacecomfintotal',null,array('id'=>'systemcalincasefinacecomfintotal')) !!}
                                        </div>
                                        <div style="width:540px; float:left;">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:25px; font-weight:bold;"></label>
                                            <label style="width:95px; padding-left:25px;">Extra...</label>
                                            <label class="dotted" style="width:100px; color:red; text-align:right;"
                                                   id="incasefinacecomextraamount">{{$accountingdetail->incasefinacecomextraamount}}</label>
                                            <label style="width:30px; text-align: center;">Vat...</label>
                                            <label class="dotted" style="width:100px; color:red; text-align:right;"
                                                   id="incasefinacecomextravat">{{$accountingdetail->incasefinacecomextravat}}</label>
                                            <label style="width:21px;"></label>
                                            <label style="width:100px; color:red; text-align:right;"
                                                   id="incasefinacecomextraamountwithvat">{{$accountingdetail->incasefinacecomextraamountwithvat}}</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:25px; font-weight:bold;"></label>
                                            <label style="width:95px; padding-left:25px;">W/H ถูกหัก</label>
                                            <label class="dotted" style="width:100px; color:#0090FF; text-align:right;"
                                                   id="incasefinacecomextrawhtax">{{$accountingdetail->incasefinacecomextrawhtax}}</label>
                                            <label style="width:30px; text-align: center;">Net...</label>
                                            <label class="dotted"
                                                   style="width:100px; color:red; text-align:right; font-weight:bold;"
                                                   id="incasefinacecomextratotal">{{$accountingdetail->incasefinacecomextratotal}}</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:25px; font-weight:bold;"></label>
                                            <label style="width:95px; padding-left:25px;">PA...</label>
                                            <label class="dotted" style="width:100px; color:red; text-align:right;"
                                                   id="incasefinacecompaamount">{{$accountingdetail->incasefinacecompaamount}}</label>
                                            <label style="width:30px; text-align: center;">Vat...</label>
                                            <label class="dotted" style="width:100px; color:red; text-align:right;"
                                                   id="incasefinacecompavat">{{$accountingdetail->incasefinacecompavat}}</label>
                                            <label style="width:21px;"></label>
                                            <label style="width:100px; color:red; text-align:right;"
                                                   id="incasefinacecompaamountwithvat">{{$accountingdetail->incasefinacecompaamountwithvat}}</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:25px; font-weight:bold;"></label>
                                            <label style="width:95px; padding-left:25px;">W/H ถูกหัก</label>
                                            <label class="dotted" style="width:100px; color:#0090FF; text-align:right;"
                                                   id="incasefinacecompawhtax">{{$accountingdetail->incasefinacecompawhtax}}</label>
                                            <label style="width:30px; text-align: center;">Net...</label>
                                            <label class="dotted"
                                                   style="width:100px; color:red; text-align:right; font-weight:bold;"
                                                   id="incasefinacecompatotal">{{$accountingdetail->incasefinacecompatotal}}</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:25px; font-weight:bold;"></label>
                                            <label style="width:95px; padding-left:25px; font-weight:bold;">รวม
                                                Com</label>
                                            <label class="dotted" style="width:100px; color:red; text-align:right;"
                                                   id="incasefinacetotalcomamount">{{$accountingdetail->incasefinacetotalcomamount}}</label>
                                            <label style="width:30px; text-align: center; font-weight:bold;">Vat...</label>
                                            <label class="dotted" style="width:100px; color:red; text-align:right;"
                                                   id="incasefinacetotalcomvat">{{$accountingdetail->incasefinacetotalcomvat}}</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:25px; font-weight:bold;"></label>
                                            <label style="width:95px; padding-left:25px; font-weight:bold;">รวม
                                                W/H</label>
                                            <label class="dotted" style="width:100px; color:#0090FF; text-align:right;"
                                                   id="incasefinacetotalcomwhtax">{{$accountingdetail->incasefinacetotalcomwhtax}}</label>
                                            <label style="width:30px; text-align: center; font-weight:bold;">Net...</label>
                                            <label class="dotted"
                                                   style="width:100px; color:red; text-align:right; font-weight:bold;"
                                                   id="incasefinacetotalcomtotal">{{$accountingdetail->incasefinacetotalcomtotal}}</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px; font-weight:bold;">รับเงินจากไฟแนนซ์สุทธิ</label>
                                            <label class="underline_db"
                                                   style="width:100px; text-align:right; font-weight:bold;"
                                                   id="receivedcashfromfinace">{{$accountingdetail->receivedcashfromfinace}}</label>
                                            {!! Form::hidden('receivedcashfromfinacenet',null,array('id'=>'receivedcashfromfinacenet')) !!}
                                            <label style="width:45px; text-align:right;">(31)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:65px; font-weight:bold;">เงินขาด</label>
                                            <label class="underline"
                                                   style="width:100px; color:red; text-align:right; font-weight:bold;"
                                                   id="receivedcashfromfinaceshort">{{$accountingdetail->receivedcashfromfinaceshort}}</label>
                                            {!! Form::hidden('receivedcashfromfinacenetshort',null,array('id'=>'receivedcashfromfinacenetshort')) !!}
                                            <label style="width:65px; margin-left:10px; font-weight:bold;">เงินเกิน</label>
                                            <label class="underline"
                                                   style="width:100px; color:red; text-align:right; font-weight:bold;"
                                                   id="receivedcashfromfinaceover">{{$accountingdetail->receivedcashfromfinaceover}}</label>
                                            {!! Form::hidden('receivedcashfromfinacenetover',null,array('id'=>'receivedcashfromfinacenetover')) !!}
                                        </div>
                                    </div>

                                    <div>
                                        <table id="grid-table-in-form0"></table>
                                        <div id="grid-pager0"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-1 col-sm-1"></div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">กรณีแลกเปลี่ยนรถเก่า หรือ กรณีรับชำระค่ารถ (เงินดาวน์)</h4>
                            <div class="widget-toolbar">
                                <a href="form-elements.html#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>

                        <div class="widget-body">
                            <div class="widget-body-inner" style="display: block;">
                                <div class="widget-main">
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">ลูกหนี้การค้า (ตามบัญชี 2)
                                                (21)-(22)</label>
                                            <label class="dotted"
                                                   style="width:100px; color:red; text-align:right;"
                                                   id="tradereceivableaccount2amount">{{$accountingdetail->tradereceivableaccount2amount}}</label>
                                            <label style="width:45px; text-align:right;">(32)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label class="underline"
                                                   style="width:18px; text-align:left; margin-right:14px; font-weight:bold;">AR</label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="ar">{{$accountingdetail->ar}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:25px; padding-left:25px; font-weight:bold;">หัก</label>
                                            <label style="width:359px; padding-left:25px;">ค่ารถเก่า</label>
                                            <label class="dotted"
                                                   style="width:100px; text-align:right;"
                                                   id="oldcarprice">{{$accountingdetail->oldcarprice}}</label>
                                            <label style="width:45px; text-align:right;">(33)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:32px; text-align:left;">-INS</label>
                                            <label class="dotted" style="width:100px; text-align:right; color:#7A019D;"
                                                   id="ins">{{$accountingdetail->ins}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:25px; padding-left:25px; font-weight:bold;">หัก</label>
                                            <label style="width:359px; padding-left:25px;">ค้างดาวน์
                                                (ไม่รวมดอกเบี้ย)</label>
                                            <label class="underline"
                                                   style="width:100px; text-align:right;"
                                                   id="overdue">{{$accountingdetail->overdue}}</label>
                                            <label style="width:45px; text-align:right;">(34)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:32px; text-align:left;">-พรบ</label>
                                            <label class="dotted" style="width:100px; text-align:right; color:#7A019D;"
                                                   id="prb">{{$accountingdetail->prb}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px; font-weight:bold;">คงเหลือรับคืน,
                                                (จ่ายคืน) (32)-(33)-(34)=(35)</label>
                                            <label class="underline_db"
                                                   style="width:100px; color:red; text-align:right;"
                                                   id="tradereceivableaccount2remainingamount">{{$accountingdetail->tradereceivableaccount2remainingamount}}</label>
                                            <label style="width:45px; text-align:right;"></label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:32px; text-align:left;">-D/C</label>
                                            <label class="dotted" style="width:100px; text-align:right; color:#7A019D;"
                                                   id="dc">{{$accountingdetail->dc}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">รับเงินค่าคอมรถเก่า</label>
                                            {!! Form::number('oldcarcomamount', null, array('step' => '0.01','style'=>'width: 100px; height: 22px; padding:0; color:black; text-align:right;','id'=>'oldcarcomamount')) !!}
                                            <label style="width:45px; text-align:right;"></label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:32px; text-align:left; color:red;">ADJ.</label>
                                            {!! Form::number('adj', null, array('step' => '0.01','style'=>'width: 100px; height: 22px; padding:0; color:#7A019D; text-align:right;','id'=>'adj','onchange'=>'AdjChange();')) !!}
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px; font-weight:bold;">ล/นการค้า
                                                (ตามบ/ช 1) (1)*-(28)=(36)</label>
                                            <label class="underline"
                                                   style="width:100px; text-align:right; font-weight:bold;"
                                                   id="tradereceivableaccount1amount">{{$accountingdetail->tradereceivableaccount1amount}}</label>
                                            <label style="width:45px; text-align:right;"></label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label class="underline"
                                                   style="width:32px; text-align:left; font-weight:bold;">Cash</label>
                                            <label class="underline"
                                                   style="width:100px; text-align:right; font-weight:bold;"
                                                   id="cash">{{$accountingdetail->cash}}</label>
                                        </div>
                                    </div>
                                    </br>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px; font-weight:bold;">ยอดรวมบัญชี
                                                1</label>
                                            <label class="underline_db"
                                                   style="width:100px; text-align:right; font-weight:bold;"
                                                   id="totalacc1">{{$accountingdetail->totalacc1}}</label>
                                            {!! Form::hidden('totalaccount1',null,array('id'=>'totalaccount1')) !!}
                                            <label style="width:45px; text-align:right;"></label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:65px; font-weight:bold;">เงินขาด</label>
                                            <label class="underline"
                                                   style="width:100px; color:red; text-align:right; font-weight:bold;"
                                                   id="totalacc1short">{{$accountingdetail->totalacc1short}}</label>
                                            {!! Form::hidden('totalaccount1short',null,array('id'=>'totalaccount1short')) !!}
                                            <label style="width:65px; margin-left:10px; font-weight:bold;">เงินเกิน</label>
                                            <label class="underline"
                                                   style="width:100px; color:red; text-align:right; font-weight:bold;"
                                                   id="totalacc1over">{{$accountingdetail->totalacc1over}}</label>
                                            {!! Form::hidden('totalaccount1over',null,array('id'=>'totalaccount1over')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px; font-weight:bold;">ยอดรวมบัญชี
                                                2</label>
                                            <label class="underline_db"
                                                   style="width:100px; text-align:right; font-weight:bold;"
                                                   id="totalacc2">{{$accountingdetail->totalacc2}}</label>
                                            {!! Form::hidden('totalaccount2',null,array('id'=>'totalaccount2')) !!}
                                            <label style="width:45px; text-align:right;"></label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:65px; font-weight:bold;">เงินขาด</label>
                                            <label class="underline"
                                                   style="width:100px; color:red; text-align:right; font-weight:bold;"
                                                   id="totalacc2short">{{$accountingdetail->totalacc2short}}</label>
                                            {!! Form::hidden('totalaccount2short',null,array('id'=>'totalaccount2short')) !!}
                                            <label style="width:65px; margin-left:10px; font-weight:bold;">เงินเกิน</label>
                                            <label class="underline"
                                                   style="width:100px; color:red; text-align:right; font-weight:bold;"
                                                   id="totalacc2over">{{$accountingdetail->totalacc2over}}</label>
                                            {!! Form::hidden('totalaccount2over',null,array('id'=>'totalaccount2over')) !!}
                                        </div>
                                    </div>

                                    <div>
                                        <table id="grid-table-in-form1"></table>
                                        <div id="grid-pager1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.col -->
    </div><!-- /.row -->

    @if($oper != 'view')
        <div class="clearfix form-actions">
            <div class="col-md-offset-5 col-md-5">
                <button id="btnSubmit" class="btn btn-info" type="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    Submit
                </button>

                &nbsp; &nbsp; &nbsp;
                <button id="btnReset" class="btn" type="reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    Reset
                </button>
            </div>
        </div>
    @endif

    {!! Form::close() !!}

    <script type="text/javascript">

        function compare(a, b) {
            var adateArr = a.date.split("-");
            var bdateArr = b.date.split("-");
            var newadate = new Date(adateArr[1] + '-' + adateArr[2] + '-' + adateArr[0]);
            var newbdate = new Date(bdateArr[1] + '-' + bdateArr[2] + '-' + bdateArr[0]);
            if (newadate.getTime() < newbdate.getTime())
                return -1;
            if (newadate.getTime() > newbdate.getTime())
                return 1;
            return 0;
        }

        var receiveAndPayData0 = [
                @foreach ($receiveAndPayDatas0 as $data)
            {
                "date": "{{$data->date}}",
                "type": "{{$data->type}}",
                "amount": "{{$data->amount}}",
                "accountgroup": "{{$data->accountgroup}}",
                "bankid": "{{$data->bankid}}",
                "note": "{{$data->note}}"
            },
            @endforeach
        ];

        receiveAndPayData0.sort(compare);

        var receiveAndPayData1 = [
                @foreach ($receiveAndPayDatas1 as $data)
            {
                "date": "{{$data->date}}",
                "type": "{{$data->type}}",
                "amount": "{{$data->amount}}",
                "accountgroup": "{{$data->accountgroup}}",
                "bankid": "{{$data->bankid}}",
                "note": "{{$data->note}}"
            },
            @endforeach
        ];

        receiveAndPayData1.sort(compare);

        onclickSubmitLocal = function (options, postdata) {
            var $this = $(this), p = $(this).jqGrid("getGridParam"),// p = this.p,
                    idname = p.prmNames.id,
                    id = this.id,
                    idInPostdata = id + "_id",
                    rowid = postdata[idInPostdata],
                    addMode = rowid === "_empty",
                    oldValueOfSortColumn,
                    newId,
                    idOfTreeParentNode;

            // postdata has row id property with another name. we fix it:
            if (addMode) {
                // generate new id
                newId = $.jgrid.randId();
                while ($("#" + newId).length !== 0) {
                    newId = $.jgrid.randId();
                }
                postdata[idname] = String(newId);
            } else if (postdata[idname] === undefined) {
                // set id property only if the property not exist
                postdata[idname] = rowid;
            }
            delete postdata[idInPostdata];

            // prepare postdata for tree grid
            if (p.treeGrid === true) {
                if (addMode) {
                    idOfTreeParentNode = p.treeGridModel === "adjacency" ? p.treeReader.parent_id_field : "parent_id";
                    postdata[idOfTreeParentNode] = p.selrow;
                }

                $.each(p.treeReader, function () {
                    if (postdata.hasOwnProperty(this)) {
                        delete postdata[this];
                    }
                });
            }

            // decode data if there encoded with autoencode
            if (p.autoencode) {
                $.each(postdata, function (n, v) {
                    if (n == "date") {
                        var dateArr = v.split("-");
                        if (addMode)
                            var newDate = dateArr[2] + '-' + dateArr[1] + '-' + dateArr[0];
                        else
                            var newDate = dateArr[0] + '-' + dateArr[1] + '-' + dateArr[2];
                        postdata[n] = $.jgrid.htmlDecode(newDate);
                    }
                    else
                        postdata[n] = $.jgrid.htmlDecode(v); // TODO: some columns could be skipped
                });
            }
            else {
                $.each(postdata, function (n, v) {
                    if (n == "date") {
                        var dateArr = v.split("-");
                        if (addMode)
                            var newDate = dateArr[2] + '-' + dateArr[1] + '-' + dateArr[0];
                        else
                            var newDate = dateArr[0] + '-' + dateArr[1] + '-' + dateArr[2];
                        postdata[n] = newDate;
                    }
                });
            }

            // save old value from the sorted column
            oldValueOfSortColumn = p.sortname === "" ? undefined : $this.jqGrid("getCell", rowid, p.sortname);

            // save the data in the grid
            if (p.treeGrid === true) {
                if (addMode) {
                    $this.jqGrid("addChildNode", newId, p.selrow, postdata);
                } else {
                    $this.jqGrid("setTreeRow", rowid, postdata);
                }
            } else {
                if (addMode) {
                    $this.jqGrid("addRowData", newId, postdata, options.addedrow);
                } else {
                    $this.jqGrid("setRowData", rowid, postdata);
                }
            }

            if ((addMode && options.closeAfterAdd) || (!addMode && options.closeAfterEdit)) {
                // close the edit/add dialog
                $.jgrid.hideModal("#editmod" + $.jgrid.jqID(id), {
                    gb: "#gbox_" + $.jgrid.jqID(id),
                    jqm: options.jqModal,
                    onClose: options.onClose
                });
            }

            if (postdata[p.sortname] !== oldValueOfSortColumn) {
                // if the data are changed in the column by which are currently sorted
                // we need resort the grid
                setTimeout(function () {
                    $this.trigger("reloadGrid", [{current: true}]);
                }, 100);
            }

            // !!! the most important step: skip ajax request to the server
            options.processing = true;

            if (this.id == "grid-table-in-form0") {
                CalShortOver0();
            }

            if (this.id == "grid-table-in-form1") {
                CalShortOver1();
            }

            return {};
        };

        $(document).ready(function () {
            var grid_selector0 = "#grid-table-in-form0";
            var pager_selector0 = "#grid-pager0";
            var grid_selector1 = "#grid-table-in-form1";
            var pager_selector1 = "#grid-pager1";

            //resize to fit page size
            $(window).on('resize.jqGrid', function () {
                resizeGridInForm('grid-table-in-form0');
                resizeGridInForm('grid-table-in-form1');
            });
            //resize on sidebar collapse/expand
            var parent_column0 = $(grid_selector0).closest('[class*="col-"]');
            $(document).on('settings.ace.jqGrid', function (ev, event_name, collapsed) {
                if (event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed') {
                    $(grid_selector0).jqGrid('setGridWidth', parent_column0.width());
                }
            });

            var parent_column1 = $(grid_selector1).closest('[class*="col-"]');
            $(document).on('settings.ace.jqGrid', function (ev, event_name, collapsed) {
                if (event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed') {
                    $(grid_selector1).jqGrid('setGridWidth', parent_column1.width());
                }
            });

            $(grid_selector0).jqGrid({
                datatype: "local",
                data: receiveAndPayData0,
                colNames: ["วันที่", "รับเงิน/จ่ายเงิน", "จำนวน", "กลุ่มบัญชี", "บัญชี", "หมายเหตุ"],
                colModel: [
                    {
                        name: 'date',
                        index: 'date',
                        width: 100,
                        editable: true,
                        sorttype: "date",
                        formatter: "date",
                        formatoptions: {srcformat: 'Y-m-d', newformat: 'd-m-Y'}
                        ,
                        editoptions: {
                            size: "10", dataInit: function (elem) {
                                $(elem).datepicker({format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true});
                            }
                        },
                        align: 'center'
                        ,
                        searchrules: {required: true}
                        ,
                        searchoptions: {
                            size: "10", dataInit: function (elem) {
                                $(elem).datepicker({format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true});
                            }
                            , sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']
                        }
                        ,
                        editrules: {required: true}
                    },
                    {
                        name: 'type',
                        index: 'type',
                        width: 100,
                        editable: true,
                        edittype: "select",
                        formatter: 'select',
                        align: 'center'
                        ,
                        stype: 'select',
                        searchrules: {required: true},
                        searchoptions: {sopt: ["eq", "ne"], value: "0:รับเงิน;1:จ่ายเงิน"}
                        ,
                        editoptions: {value: "0:รับเงิน;1:จ่ายเงิน"}
                    },
                    {
                        name: 'amount', index: 'amount', width: 100, editable: true,
                        editrules: {required: true, number: true}, align: 'right', formatter: 'number',
                        formatoptions: {decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2}
                    },
                    {
                        name: 'accountgroup',
                        index: 'accountgroup',
                        width: 100,
                        editable: true,
                        edittype: "select",
                        formatter: 'select',
                        align: 'center'
                        ,
                        stype: 'select',
                        searchrules: {required: true},
                        searchoptions: {sopt: ["eq", "ne"], value: "1:บัญชี 1;2:บัญชี 2"},
                        editrules: {required: true}
                        ,
                        editoptions: {
                            value: ":เลือกกลุ่ม;1:บัญชี 1;2:บัญชี 2",
                            dataEvents: [{
                                type: 'change', fn: function (e) {
                                    var thisval = $(e.target).val();
                                    if (thisval == null || thisval == '') {
                                        $('#bankid').children('option:not(:first)').remove();
                                    }
                                    else {
                                        $.get(window.location.origin + '/bank/readSelectlistByAccountGroup/' + thisval, function (data) {
                                            $('#bankid').children('option:not(:first)').remove();
                                            $.each(data, function (i, option) {
                                                var ei = option.accountno.length - 1;
                                                var si = ei - 3;
                                                var text = option.name + ' ' + option.accountno.substr(si, ei);
                                                $('#bankid').append($('<option/>').attr("value", option.id).text(text));
                                            });
                                        });
                                    }
                                }
                            }]
                        }
                    },
                    {
                        name: 'bankid',
                        index: 'bankid',
                        width: 100,
                        editable: true,
                        edittype: "select",
                        formatter: 'select',
                        editoptions: {value: "{{ $bankselectlist2 }}"},
                        editrules: {required: true}
                        ,
                        align: 'center',
                        stype: 'select',
                        searchrules: {required: true},
                        searchoptions: {sopt: ["eq", "ne"], value: "{{ $bankselectlist2 }}"}
                    },
                    {
                        name: 'note',
                        index: 'note',
                        width: 150,
                        editable: true,
                        edittype: 'textarea',
                        editoptions: {rows: "2", cols: "35"},
                        align: 'left'
                    }

                ],
                cmTemplate: {editable: true, sortable: false, searchoptions: {clearSearch: false}},
                rowNum: 10,
                rowList: [5, 10, 20],
                pager: pager_selector0,
                gridview: true,
                rownumbers: true,
                //autoencode: true,
                //ignoreCase: true,
                viewrecords: true,
                altRows: true,
                multiselect: true,
                multiboxonly: true,
                caption: "การรับเงิน/การจ่ายเงิน",
                height: "100%",
                editurl: "clientArray",
                loadComplete: function () {
                    var table = this;
                    setTimeout(function () {
                        styleCheckbox(table);

                        updateActionIcons(table);
                        updatePagerIcons(table);
                        enableTooltips(table);
                    }, 0);
                },
                ondblClickRow: function (rowid) {
                    /*var $this = $(this), p = this.p;
                     if (p.selrow !== rowid) {
                     // prevent the row from be unselected on double-click
                     // the implementation is for "multiselect:false" which we use,
                     // but one can easy modify the code for "multiselect:true"
                     $this.jqGrid("setSelection", rowid);
                     }
                     $this.jqGrid("editGridRow", rowid, editSettings);*/
                }
            });

            $(grid_selector1).jqGrid({
                datatype: "local",
                data: receiveAndPayData1,
                colNames: ["วันที่", "รับเงิน/จ่ายเงิน", "จำนวน", "กลุ่มบัญชี", "บัญชี", "หมายเหตุ"],
                colModel: [
                    {
                        name: 'date',
                        index: 'date',
                        width: 100,
                        editable: true,
                        sorttype: "date",
                        formatter: "date",
                        formatoptions: {srcformat: 'Y-m-d', newformat: 'd-m-Y'}
                        ,
                        editoptions: {
                            size: "10", dataInit: function (elem) {
                                $(elem).datepicker({format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true});
                            }
                        },
                        align: 'center'
                        ,
                        searchrules: {required: true}
                        ,
                        searchoptions: {
                            size: "10", dataInit: function (elem) {
                                $(elem).datepicker({format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true});
                            }
                            , sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']
                        }
                        ,
                        editrules: {required: true}
                    },
                    {
                        name: 'type',
                        index: 'type',
                        width: 100,
                        editable: true,
                        edittype: "select",
                        formatter: 'select',
                        align: 'center'
                        ,
                        stype: 'select',
                        searchrules: {required: true},
                        searchoptions: {sopt: ["eq", "ne"], value: "0:รับเงิน;1:จ่ายเงิน"}
                        ,
                        editoptions: {value: "0:รับเงิน;1:จ่ายเงิน"}
                    },
                    {
                        name: 'amount', index: 'amount', width: 100, editable: true,
                        editrules: {required: true, number: true}, align: 'right', formatter: 'number',
                        formatoptions: {decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2}
                    },
                    {
                        name: 'accountgroup',
                        index: 'accountgroup',
                        width: 100,
                        editable: true,
                        edittype: "select",
                        formatter: 'select',
                        align: 'center'
                        ,
                        stype: 'select',
                        searchrules: {required: true},
                        searchoptions: {sopt: ["eq", "ne"], value: "1:บัญชี 1;2:บัญชี 2"},
                        editrules: {required: true}
                        ,
                        editoptions: {
                            value: ":เลือกกลุ่ม;1:บัญชี 1;2:บัญชี 2",
                            dataEvents: [{
                                type: 'change', fn: function (e) {
                                    var thisval = $(e.target).val();
                                    if (thisval == null || thisval == '') {
                                        $('#bankid').children('option:not(:first)').remove();
                                    }
                                    else {
                                        $.get(window.location.origin + '/bank/readSelectlistByAccountGroup/' + thisval, function (data) {
                                            $('#bankid').children('option:not(:first)').remove();
                                            $.each(data, function (i, option) {
                                                var ei = option.accountno.length - 1;
                                                var si = ei - 3;
                                                var text = option.name + ' ' + option.accountno.substr(si, ei);
                                                $('#bankid').append($('<option/>').attr("value", option.id).text(text));
                                            });
                                        });
                                    }
                                }
                            }]
                        }
                    },
                    {
                        name: 'bankid',
                        index: 'bankid',
                        width: 100,
                        editable: true,
                        edittype: "select",
                        formatter: 'select',
                        editoptions: {value: "{{ $bankselectlist2 }}"},
                        editrules: {required: true}
                        ,
                        align: 'center',
                        stype: 'select',
                        searchrules: {required: true},
                        searchoptions: {sopt: ["eq", "ne"], value: "{{ $bankselectlist2 }}"}
                    },
                    {
                        name: 'note',
                        index: 'note',
                        width: 150,
                        editable: true,
                        edittype: 'textarea',
                        editoptions: {rows: "2", cols: "35"},
                        align: 'left'
                    }

                ],
                cmTemplate: {editable: true, sortable: false, searchoptions: {clearSearch: false}},
                rowNum: 10,
                rowList: [5, 10, 20],
                pager: pager_selector1,
                gridview: true,
                rownumbers: true,
                //autoencode: true,
                //ignoreCase: true,
                viewrecords: true,
                altRows: true,
                multiselect: true,
                multiboxonly: true,
                caption: "การรับเงิน/การจ่ายเงิน",
                height: "100%",
                editurl: "clientArray",
                loadComplete: function () {
                    var table = this;
                    setTimeout(function () {
                        styleCheckbox(table);

                        updateActionIcons(table);
                        updatePagerIcons(table);
                        enableTooltips(table);
                    }, 0);
                },
                ondblClickRow: function (rowid) {
                    /*var $this = $(this), p = this.p;
                     if (p.selrow !== rowid) {
                     // prevent the row from be unselected on double-click
                     // the implementation is for "multiselect:false" which we use,
                     // but one can easy modify the code for "multiselect:true"
                     $this.jqGrid("setSelection", rowid);
                     }
                     $this.jqGrid("editGridRow", rowid, editSettings);*/
                }
            });

            $(window).triggerHandler('resize.jqGrid');

            var notViewMode = true;
            if ('{{$oper}}' == 'view') {
                notViewMode = false;
            }

            $(grid_selector0).jqGrid("navGrid", pager_selector0,
                    { 	//navbar options
                        edit: notViewMode,
                        editicon: 'ace-icon fa fa-pencil blue',
                        add: notViewMode,
                        addicon: 'ace-icon fa fa-plus-circle purple',
                        del: notViewMode,
                        delicon: 'ace-icon fa fa-trash-o red',
                        search: false,
                        searchicon: 'ace-icon fa fa-search orange',
                        refresh: false,
                        refreshicon: 'ace-icon fa fa-refresh green',
                        view: false,
                        viewicon: 'ace-icon fa fa-search-plus grey'
                    },
                    {
                        //edit record form
                        closeAfterEdit: true,
                        width: 600,
                        recreateForm: true,
                        viewPagerButtons: false,
                        beforeShowForm: function (e) {
                            var form = $(e[0]);
                            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                            style_edit_form(form);

                            var accountgroup = $('#accountgroup').val();
                            var bankid = $('#bankid').val();

                            $.get(window.location.origin + '/bank/readSelectlistByAccountGroup/' + accountgroup, function (data) {
                                $('#bankid').children('option:not(:first)').remove();
                                $.each(data, function (i, option) {
                                    var ei = option.accountno.length - 1;
                                    var si = ei - 3;
                                    var text = option.name + ' ' + option.accountno.substr(si, ei);
                                    $('#bankid').append($('<option/>').attr("value", option.id).text(text));
                                });
                                $('#bankid').val(bankid);
                            });

                            var dlgDiv = $("#editmod" + jQuery(grid_selector0)[0].id);
                            dlgDiv[0].style.left = (($("#appbody").width() - 600) / 2) + "px";
                        },
                        reloadAfterSubmit: false,
                        savekey: [true, 13],
                        modal: true,
                        onclickSubmit: onclickSubmitLocal
                    },
                    {
                        //new record form
                        width: 600,
                        closeAfterAdd: true,
                        recreateForm: true,
                        viewPagerButtons: false,
                        beforeShowForm: function (e) {
                            jQuery(grid_selector0).jqGrid('resetSelection');
                            var form = $(e[0]);
                            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                            style_edit_form(form);

                            $('#bankid').children('option:not(:first)').remove();

                            var dlgDiv = $("#editmod" + jQuery(grid_selector0)[0].id);
                            dlgDiv[0].style.left = (($("#appbody").width() - 600) / 2) + "px";
                        },
                        reloadAfterSubmit: false,
                        savekey: [true, 13],
                        modal: true,
                        onclickSubmit: onclickSubmitLocal
                    },
                    {
                        //delete record form
                        width: 400,
                        recreateForm: true,
                        beforeShowForm: function (e) {
                            var form = $(e[0]);
                            if (!form.data('styled')) {
                                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                                style_delete_form(form);

                                form.data('styled', true);

                                var dlgDiv = $("#delmod" + jQuery(grid_selector0)[0].id);
                                dlgDiv[0].style.left = (($("#appbody").width() - 400) / 2) + "px";
                            }

                            var totalRows = $(grid_selector0).jqGrid('getGridParam', 'selarrrow');
                            var totalRowsCount = totalRows.length;
                            $("td.delmsg", form).html("คุณต้องการลบข้อมูลที่ถูกเลือก <b>ทั้งหมด " + totalRowsCount + " รายการ</b>" + " ใช่หรือไม่?");
                        },
                        // because I use "local" data I don't want to send the changes to the server
                        // so I use "processing:true" setting and delete the row manually in onclickSubmit
                        onclickSubmit: function (options, rowid) {
                            var $this = $(this), id = $.jgrid.jqID(this.id), p = this.p,
                                    newPage = p.page;

                            // reset the value of processing option to true to
                            // skip the ajax request to "clientArray".
                            options.processing = true;

                            var totalRows = $(grid_selector0).jqGrid('getGridParam', 'selarrrow');
                            var rowLength = totalRows.length;
                            for (var i = 0; i < rowLength; i++) {
                                $this.jqGrid("delRowData", totalRows[0]);
                            }

                            $.jgrid.hideModal("#delmod" + id, {
                                gb: "#gbox_" + id,
                                jqm: options.jqModal,
                                onClose: options.onClose
                            });

                            if (p.lastpage > 1) {// on the multipage grid reload the grid
                                if (p.reccount === 0 && newPage === p.lastpage) {
                                    // if after deliting there are no rows on the current page
                                    // which is the last page of the grid
                                    newPage--; // go to the previous page
                                }
                                // reload grid to make the row from the next page visable.
                                $this.trigger("reloadGrid", [{page: newPage}]);
                            }

                            CalShortOver0();
                            return true;
                        },
                        processing: true
                    }
            );

            $(grid_selector1).jqGrid("navGrid", pager_selector1,
                    { 	//navbar options
                        edit: notViewMode,
                        editicon: 'ace-icon fa fa-pencil blue',
                        add: notViewMode,
                        addicon: 'ace-icon fa fa-plus-circle purple',
                        del: notViewMode,
                        delicon: 'ace-icon fa fa-trash-o red',
                        search: false,
                        searchicon: 'ace-icon fa fa-search orange',
                        refresh: false,
                        refreshicon: 'ace-icon fa fa-refresh green',
                        view: false,
                        viewicon: 'ace-icon fa fa-search-plus grey'
                    },
                    {
                        //edit record form
                        closeAfterEdit: true,
                        width: 600,
                        recreateForm: true,
                        viewPagerButtons: false,
                        beforeShowForm: function (e) {
                            var form = $(e[0]);
                            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                            style_edit_form(form);

                            var accountgroup = $('#accountgroup').val();
                            var bankid = $('#bankid').val();

                            $.get(window.location.origin + '/bank/readSelectlistByAccountGroup/' + accountgroup, function (data) {
                                $('#bankid').children('option:not(:first)').remove();
                                $.each(data, function (i, option) {
                                    var ei = option.accountno.length - 1;
                                    var si = ei - 3;
                                    var text = option.name + ' ' + option.accountno.substr(si, ei);
                                    $('#bankid').append($('<option/>').attr("value", option.id).text(text));
                                });
                                $('#bankid').val(bankid);
                            });

                            var dlgDiv = $("#editmod" + jQuery(grid_selector1)[0].id);
                            dlgDiv[0].style.left = (($("#appbody").width() - 600) / 2) + "px";
                        },
                        reloadAfterSubmit: false,
                        savekey: [true, 13],
                        modal: true,
                        onclickSubmit: onclickSubmitLocal
                    },
                    {
                        //new record form
                        width: 600,
                        closeAfterAdd: true,
                        recreateForm: true,
                        viewPagerButtons: false,
                        beforeShowForm: function (e) {
                            jQuery(grid_selector1).jqGrid('resetSelection');
                            var form = $(e[0]);
                            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                            style_edit_form(form);

                            $('#bankid').children('option:not(:first)').remove();

                            var dlgDiv = $("#editmod" + jQuery(grid_selector1)[0].id);
                            dlgDiv[0].style.left = (($("#appbody").width() - 600) / 2) + "px";
                        },
                        reloadAfterSubmit: false,
                        savekey: [true, 13],
                        modal: true,
                        onclickSubmit: onclickSubmitLocal
                    },
                    {
                        //delete record form
                        width: 400,
                        recreateForm: true,
                        beforeShowForm: function (e) {
                            var form = $(e[0]);
                            if (!form.data('styled')) {
                                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                                style_delete_form(form);

                                form.data('styled', true);

                                var dlgDiv = $("#delmod" + jQuery(grid_selector1)[0].id);
                                dlgDiv[0].style.left = (($("#appbody").width() - 400) / 2) + "px";
                            }

                            var totalRows = $(grid_selector1).jqGrid('getGridParam', 'selarrrow');
                            var totalRowsCount = totalRows.length;
                            $("td.delmsg", form).html("คุณต้องการลบข้อมูลที่ถูกเลือก <b>ทั้งหมด " + totalRowsCount + " รายการ</b>" + " ใช่หรือไม่?");
                        },
                        // because I use "local" data I don't want to send the changes to the server
                        // so I use "processing:true" setting and delete the row manually in onclickSubmit
                        onclickSubmit: function (options, rowid) {
                            var $this = $(this), id = $.jgrid.jqID(this.id), p = this.p,
                                    newPage = p.page;

                            // reset the value of processing option to true to
                            // skip the ajax request to "clientArray".
                            options.processing = true;

                            var totalRows = $(grid_selector1).jqGrid('getGridParam', 'selarrrow');
                            var rowLength = totalRows.length;
                            for (var i = 0; i < rowLength; i++) {
                                $this.jqGrid("delRowData", totalRows[0]);
                            }

                            $.jgrid.hideModal("#delmod" + id, {
                                gb: "#gbox_" + id,
                                jqm: options.jqModal,
                                onClose: options.onClose
                            });

                            if (p.lastpage > 1) {// on the multipage grid reload the grid
                                if (p.reccount === 0 && newPage === p.lastpage) {
                                    // if after deliting there are no rows on the current page
                                    // which is the last page of the grid
                                    newPage--; // go to the previous page
                                }
                                // reload grid to make the row from the next page visable.
                                $this.trigger("reloadGrid", [{page: newPage}]);
                            }

                            CalShortOver1();
                            return true;
                        },
                        processing: true
                    }
            );


            //datepicker plugin
            $('.date-picker').datepicker({
                autoclose: true,
                todayHighlight: true
            }).next().on(ace.click_event, function () { //show datepicker when clicking on the icon
                $(this).prev().focus();
            });

            $('.chosen-select').chosen({allow_single_deselect: true});
            //resize the chosen on window resize
            $(window).on('resize.chosen', function () {
                var w = $('.chosen-select').parent().width();
                $('.chosen-select').next().css({'width': 189});
            }).trigger('resize.chosen');

            $('.date-picker').parent().width(140);
            $('#cashpledgereceiptdate').parent().width(98);

            $('.date-picker').width(90);
            $('#cashpledgereceiptdate').width(80);

            @if($accountingdetail->carpaymentid != null && $accountingdetail->carpaymentid > 0 && $accountingdetail->purchasetype == 0)
                $("#incasefinace").css("display", "none");
            @endif

            @if($oper == 'view')
                $("#form-accountingdetail :input").prop("disabled", true);
            $(".chosen-select").attr('disabled', true).trigger("chosen:updated");
            @endif
        });

        $('#form-accountingdetail').submit(function () { //listen for submit event
            var receiveAndPayData0 = $("#grid-table-in-form0").jqGrid('getGridParam', 'data');
            var receiveAndPayData1 = $("#grid-table-in-form1").jqGrid('getGridParam', 'data');
            receiveAndPayData0 = JSON.stringify(receiveAndPayData0);
            receiveAndPayData1 = JSON.stringify(receiveAndPayData1);
            $(this).append($('<input>').attr('type', 'hidden').attr('name', 'receiveAndPayData0').val(receiveAndPayData0));
            $(this).append($('<input>').attr('type', 'hidden').attr('name', 'receiveAndPayData1').val(receiveAndPayData1));
            return true;
        });
    </script>
@endsection