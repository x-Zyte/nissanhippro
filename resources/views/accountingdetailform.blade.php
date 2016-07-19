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
        function CarpaymentChange(sel) {
            var carpaymentid = sel.value;
            if (carpaymentid == null || carpaymentid == '') {
                return;
            }

            $.get('{{$pathPrefix}}carpayment/getforaccountingdetailbyid/' + carpaymentid, function (data) {
                $("#branchname").text(data.branchname);
                $("#customername").text(data.customername);
                $("#date").text(data.date);

                if (data.openbill == 0) $("#openbill").text("-");
                else $("#openbill").text(parseFloat(data.openbill).toFixed(2));

                var additionalopenbill = $('#additionalopenbill').val();
                if (additionalopenbill == null || additionalopenbill == '')
                    additionalopenbill = 0;
                var finalopenbill = parseFloat(data.openbill) + parseFloat(additionalopenbill);
                if (finalopenbill == 0) $("#finalopenbill").text("-");
                else $("#finalopenbill").text(parseFloat(finalopenbill).toFixed(2));

                if (data.carpriceinpricelist == 0) $("#carpriceinpricelist").text("-");
                else $("#carpriceinpricelist").text(parseFloat(data.carpriceinpricelist).toFixed(2));

                if (data.colorprice == 0) $("#colorprice").text("-");
                else $("#colorprice").text(parseFloat(data.colorprice).toFixed(2));

                if (data.carwithcolorprice == 0) $("#carwithcolorprice").text("-");
                else $("#carwithcolorprice").text(parseFloat(data.carwithcolorprice).toFixed(2));

                if (data.accessoriesfeeincludeinyodjud == 0) $("#accessoriesfeeincludeinyodjud").text("-");
                else $("#accessoriesfeeincludeinyodjud").text(parseFloat(data.accessoriesfeeincludeinyodjud).toFixed(2));

                if (data.fakeaccessories == 0) {
                    $("#plusfakeaccessories").text("-");
                    $("#minusfakeaccessories").text("-");
                }
                else {
                    $("#plusfakeaccessories").text(parseFloat(data.fakeaccessories).toFixed(2));
                    $("#minusfakeaccessories").text(parseFloat(data.fakeaccessories).toFixed(2));
                }

                if (data.discount == 0) $("#discount").text("-");
                else $("#discount").text(parseFloat(data.discount).toFixed(2));

                if (data.subdown == 0) $("#subdown").text("-");
                else $("#subdown").text(parseFloat(data.subdown).toFixed(2));

                if (data.realsalesprice == 0) {
                    $("#realsalesprice").text("-");
                    $("#realsalesprice2").text("-");
                }
                else {
                    $("#realsalesprice").text(parseFloat(data.realsalesprice).toFixed(2));
                    $("#realsalesprice2").text(parseFloat(data.realsalesprice).toFixed(2));
                }

                var vat;
                if (finalopenbill == 0) vat = 0;
                else vat = parseFloat(finalopenbill) * parseFloat(0.07);

                if (vat == 0) $("#vatoffinalopenbill").text("-");
                else $("#vatoffinalopenbill").text(parseFloat(vat).toFixed(2));

                var finalopenbillwithoutvat = parseFloat(finalopenbill) - parseFloat(vat);
                if (finalopenbillwithoutvat == 0) $("#finalopenbillwithoutvat").text("-");
                else $("#finalopenbillwithoutvat").text(parseFloat(finalopenbillwithoutvat).toFixed(2));

                var realsalespricewithoutvat = parseFloat(data.realsalesprice) - parseFloat(vat);
                if (realsalespricewithoutvat == 0) $("#realsalespricewithoutvat").text("-");
                else $("#realsalespricewithoutvat").text(parseFloat(realsalespricewithoutvat).toFixed(2));

                if (data.accessoriesfeeactuallypaid == 0) $("#accessoriesfeeactuallypaid").text("-");
                else $("#accessoriesfeeactuallypaid").text(parseFloat(data.accessoriesfeeactuallypaid).toFixed(2));

                if (data.registrationfee == 0) $("#registrationfee").text("-");
                else $("#registrationfee").text(parseFloat(data.registrationfee).toFixed(2));

                if (data.compulsorymotorinsurancefeecash == 0) $("#compulsorymotorinsurancefeecash").text("-");
                else $("#compulsorymotorinsurancefeecash").text(parseFloat(data.compulsorymotorinsurancefeecash).toFixed(2));

                if (data.insurancefeecash == 0) $("#insurancefeecash").text("-");
                else $("#insurancefeecash").text(parseFloat(data.insurancefeecash).toFixed(2));

                if (data.implementfee == 0) $("#implementfee").text("-");
                else $("#implementfee").text(parseFloat(data.implementfee).toFixed(2));

                if (data.totalotherfee == 0) $("#totalotherfee").text("-");
                else $("#totalotherfee").text(parseFloat(data.totalotherfee).toFixed(2));

                if (data.subsidise == 0) $("#subsidise").text("-");
                else $("#subsidise").text(parseFloat(data.subsidise).toFixed(2));

                if (data.giveawaywithholdingtax == 0) $("#giveawaywithholdingtax").text("-");
                else $("#giveawaywithholdingtax").text(parseFloat(data.giveawaywithholdingtax).toFixed(2));

                if (data.otherfee == 0) $("#otherfee").text("-");
                else $("#otherfee").text(parseFloat(data.otherfee).toFixed(2));

                if (data.otherfeedetail == null || data.otherfeedetail == '') $("#otherfeedetail").text("-");
                else $("#otherfeedetail").text(data.otherfeedetail);

                if (data.otherfee2 == 0) $("#otherfee2").text("-");
                else $("#otherfee2").text(parseFloat(data.otherfee2).toFixed(2));

                if (data.otherfeedetail2 == null || data.otherfeedetail2 == '') $("#otherfeedetail2").text("-");
                else $("#otherfeedetail2").text(data.otherfeedetail2);

                if (data.otherfee3 == 0) $("#otherfee3").text("-");
                else $("#otherfee3").text(parseFloat(data.otherfee3).toFixed(2));

                if (data.otherfeedetail3 == null || data.otherfeedetail3 == '') $("#otherfeedetail3").text("-");
                else $("#otherfeedetail3").text(data.otherfeedetail3);

                if (data.totalotherfees == 0) $("#totalotherfees").text("-");
                else $("#totalotherfees").text(parseFloat(data.totalotherfees).toFixed(2));

                $("#submodel").text(data.submodel);
                $("#carno").text(data.carno);
                $("#engineno").text(data.engineno);
                $("#chassisno").text(data.chassisno);
                $("#color").text(data.color);
                $("#purchasetype").text(data.purchasetype);

                if (data.down == 0) $("#down").text("-");
                else $("#down").text(parseFloat(data.down).toFixed(2));

                if (data.compulsorymotorinsurancefeefn == 0) $("#compulsorymotorinsurancefeefn").text("-");
                else $("#compulsorymotorinsurancefeefn").text(parseFloat(data.compulsorymotorinsurancefeefn).toFixed(2));

                if (data.insurancefeefn == 0) $("#insurancefeefn").text("-");
                else $("#insurancefeefn").text(parseFloat(data.insurancefeefn).toFixed(2));

                if (data.firstinstallmentpayamount == 0) $("#firstinstallmentpayamount").text("-");
                else $("#firstinstallmentpayamount").text(parseFloat(data.firstinstallmentpayamount).toFixed(2));

                if (data.installmentsinadvance == 0) $("#installmentsinadvance").text("-");
                else $("#installmentsinadvance").text(parseFloat(data.installmentsinadvance).toFixed(2));

                if (data.amountperinstallment == 0) $("#amountperinstallment").text("-");
                else $("#amountperinstallment").text(parseFloat(data.amountperinstallment).toFixed(2));

                if (data.payinadvanceamount == 0) $("#payinadvanceamount").text("-");
                else $("#payinadvanceamount").text(parseFloat(data.payinadvanceamount).toFixed(2));

                if (data.insurancepremium == 0) $("#insurancepremium").text("-");
                else $("#insurancepremium").text(parseFloat(data.insurancepremium).toFixed(2));

                if (data.totalinadvancefees == 0) $("#totalinadvancefees").text("-");
                else $("#totalinadvancefees").text(parseFloat(data.totalinadvancefees).toFixed(2));

                if (data.conditioninsurancefee == 0) {
                    $("#conditioninsurancefee").text("-");
                    $('#hasinsurancefee').val(0);
                }
                else {
                    $("#conditioninsurancefee").text(parseFloat(data.conditioninsurancefee).toFixed(2));
                    $('#hasinsurancefee').val(1);
                }

                if (data.conditioninsurancefeecustomerpaid == 0) $("#conditioninsurancefeecustomerpaid").text("-");
                else $("#conditioninsurancefeecustomerpaid").text(parseFloat(data.conditioninsurancefeecustomerpaid).toFixed(2));

                if (data.conditioninsurancefeecompanypaid == 0) $("#conditioninsurancefeecompanypaid").text("-");
                else $("#conditioninsurancefeecompanypaid").text(parseFloat(data.conditioninsurancefeecompanypaid).toFixed(2));

                if (data.conditioncompulsorymotorinsurancefee == 0) {
                    $("#conditioncompulsorymotorinsurancefee").text("-");
                    $('#hascompulsorymotorinsurancefee').val(0);
                }
                else {
                    $("#conditioncompulsorymotorinsurancefee").text(parseFloat(data.conditioncompulsorymotorinsurancefee).toFixed(2));
                    $('#hascompulsorymotorinsurancefee').val(1);
                }

                if (data.conditioncompulsorymotorinsurancefeecustomerpaid == 0) $("#conditioncompulsorymotorinsurancefeecustomerpaid").text("-");
                else $("#conditioncompulsorymotorinsurancefeecustomerpaid").text(parseFloat(data.conditioncompulsorymotorinsurancefeecustomerpaid).toFixed(2));

                if (data.conditioncompulsorymotorinsurancefeecompanypaid == 0) $("#conditioncompulsorymotorinsurancefeecompanypaid").text("-");
                else $("#conditioncompulsorymotorinsurancefeecompanypaid").text(parseFloat(data.conditioncompulsorymotorinsurancefeecompanypaid).toFixed(2));

                if (data.capitalinsurance == 0) $("#capitalinsurance").text("-");
                else $("#capitalinsurance").text(parseFloat(data.capitalinsurance).toFixed(2));

                if (data.insurancecompany == null || data.insurancecompany == '') $("#insurancecompany").text("-");
                else $("#insurancecompany").text(data.insurancecompany);

                if (data.compulsorymotorinsurancecompany == null || data.compulsorymotorinsurancecompany == '') $("#compulsorymotorinsurancecompany").text("-");
                else $("#compulsorymotorinsurancecompany").text(data.compulsorymotorinsurancecompany);

                if (data.note1insurancefee == 0) $("#note1insurancefee").text("-");
                else $("#note1insurancefee").text(parseFloat(data.note1insurancefee).toFixed(2));

                if (data.note1insurancefeevat == 0) $("#note1insurancefeevat").text("-");
                else $("#note1insurancefeevat").text(parseFloat(data.note1insurancefeevat).toFixed(2));

                if (data.note1insurancefeeincludevat == 0) $("#note1insurancefeeincludevat").text("-");
                else $("#note1insurancefeeincludevat").text(parseFloat(data.note1insurancefeeincludevat).toFixed(2));

                if (data.note1compulsorymotorinsurancefee == 0) $("#note1compulsorymotorinsurancefee").text("-");
                else $("#note1compulsorymotorinsurancefee").text(parseFloat(data.note1compulsorymotorinsurancefee).toFixed(2));

                if (data.note1compulsorymotorinsurancefeevat == 0) $("#note1compulsorymotorinsurancefeevat").text("-");
                else $("#note1compulsorymotorinsurancefeevat").text(parseFloat(data.note1compulsorymotorinsurancefeevat).toFixed(2));

                if (data.note1compulsorymotorinsurancefeeincludevat == 0) $("#note1compulsorymotorinsurancefeeincludevat").text("-");
                else $("#note1compulsorymotorinsurancefeeincludevat").text(parseFloat(data.note1compulsorymotorinsurancefeeincludevat).toFixed(2));

                if (data.note1totalfee == 0) $("#note1totalfee").text("-");
                else $("#note1totalfee").text(parseFloat(data.note1totalfee).toFixed(2));

                if (data.note1totalfeevat == 0) $("#note1totalfeevat").text("-");
                else $("#note1totalfeevat").text(parseFloat(data.note1totalfeevat).toFixed(2));

                if (data.note1totalfeeincludevat == 0) $("#note1totalfeeincludevat").text("-");
                else $("#note1totalfeeincludevat").text(parseFloat(data.note1totalfeeincludevat).toFixed(2));

                if (data.cashpledgeredlabel == 0) $("#cashpledgeredlabel").text("-");
                else $("#cashpledgeredlabel").text(parseFloat(data.cashpledgeredlabel).toFixed(2));

                if (data.cashpledge == 0) $("#cashpledge").text("-");
                else $("#cashpledge").text(parseFloat(data.cashpledge).toFixed(2));

                if (data.totalcashpledge == 0) $("#totalcashpledge").text("-");
                else $("#totalcashpledge").text(parseFloat(data.totalcashpledge).toFixed(2));

                if (data.totalcash == 0) $("#totalcash").text("-");
                else $("#totalcash").text(parseFloat(data.totalcash).toFixed(2));

                if (data.incasefinace == 1) {
                    $("#incasefinace").css("display", "block");
                    $("#finacecompany").text('กรณีจัดไฟแนนซ์ - ' + data.finacecompany);
                    $('#purchasetype').val(1);

                    if (data.interest == 0) $("#interest").text("-");
                    else $("#interest").text(parseFloat(data.interest).toFixed(2));

                    if (data.installments == 0) $("#installments").text("-");
                    else $("#installments").text(data.installments);

                    if (data.yodjud == 0) $("#yodjud").text("-");
                    else $("#yodjud").text(parseFloat(data.yodjud).toFixed(2));

                    if (data.comfinpercent == 0) $("#comfinpercent").text("-");
                    else $("#comfinpercent").text(data.comfinpercent);

                    if (data.comfinyear == 0) $("#comfinyear").text("-");
                    else $("#comfinyear").text(data.comfinyear);

                    if (data.salename == null || data.salename == '') $("#salename").text("-");
                    else $("#salename").text(data.salename);

                    if (data.incasefinaceinsurancefee == 0) $("#incasefinaceinsurancefee").text("-");
                    else $("#incasefinaceinsurancefee").text(parseFloat(data.incasefinaceinsurancefee).toFixed(2));

                    if (data.note2insurancefeewhtax == 0) $("#note2insurancefeewhtax").text("-");
                    else $("#note2insurancefeewhtax").text(parseFloat(data.note2insurancefeewhtax).toFixed(2));

                    if (data.note2insurancefee == 0) $("#note2insurancefee").text("-");
                    else $("#note2insurancefee").text(parseFloat(data.note2insurancefee).toFixed(2));

                    if (data.note2insurancefeeexpense == 0) $("#note2insurancefeeexpense").text("-");
                    else $("#note2insurancefeeexpense").text(parseFloat(data.note2insurancefeeexpense).toFixed(2));

                    if (data.note2insurancefeeincome == 0) $("#note2insurancefeeincome").text("-");
                    else $("#note2insurancefeeincome").text(parseFloat(data.note2insurancefeeincome).toFixed(2));

                    if (data.incasefinacecompulsorymotorinsurancefee == 0) $("#incasefinacecompulsorymotorinsurancefee").text("-");
                    else $("#incasefinacecompulsorymotorinsurancefee").text(parseFloat(data.incasefinacecompulsorymotorinsurancefee).toFixed(2));

                    if (data.note2compulsorymotorinsurancefeewhtax == 0) $("#note2compulsorymotorinsurancefeewhtax").text("-");
                    else $("#note2compulsorymotorinsurancefeewhtax").text(parseFloat(data.note2compulsorymotorinsurancefeewhtax).toFixed(2));

                    if (data.note2compulsorymotorinsurancefee == 0) $("#note2compulsorymotorinsurancefee").text("-");
                    else $("#note2compulsorymotorinsurancefee").text(parseFloat(data.note2compulsorymotorinsurancefee).toFixed(2));

                    if (data.note2compulsorymotorinsurancefeeexpense == 0) $("#note2compulsorymotorinsurancefeeexpense").text("-");
                    else $("#note2compulsorymotorinsurancefeeexpense").text(parseFloat(data.note2compulsorymotorinsurancefeeexpense).toFixed(2));

                    if (data.note2compulsorymotorinsurancefeeincome == 0) $("#note2compulsorymotorinsurancefeeincome").text("-");
                    else $("#note2compulsorymotorinsurancefeeincome").text(parseFloat(data.note2compulsorymotorinsurancefeeincome).toFixed(2));

                    if (data.incasefinacefirstinstallmentpayamount == 0) $("#incasefinacefirstinstallmentpayamount").text("-");
                    else $("#incasefinacefirstinstallmentpayamount").text(parseFloat(data.incasefinacefirstinstallmentpayamount).toFixed(2));

                    if (data.note2firstinstallmentpayamount == 0) $("#note2firstinstallmentpayamount").text("-");
                    else $("#note2firstinstallmentpayamount").text(parseFloat(data.note2firstinstallmentpayamount).toFixed(2));

                    if (data.incasefinacepayinadvanceamount == 0) $("#incasefinacepayinadvanceamount").text("-");
                    else $("#incasefinacepayinadvanceamount").text(parseFloat(data.incasefinacepayinadvanceamount).toFixed(2));

                    if (data.note2payinadvanceamount == 0) $("#note2payinadvanceamount").text("-");
                    else $("#note2payinadvanceamount").text(parseFloat(data.note2payinadvanceamount).toFixed(2));

                    if (data.incasefinaceinsurancepremium == 0) $("#incasefinaceinsurancepremium").text("-");
                    else $("#incasefinaceinsurancepremium").text(parseFloat(data.incasefinaceinsurancepremium).toFixed(2));

                    if (data.note2insurancepremium == 0) $("#note2insurancepremium").text("-");
                    else $("#note2insurancepremium").text(parseFloat(data.note2insurancepremium).toFixed(2));

                    if (data.totalincasefinace == 0) $("#totalincasefinace").text("-");
                    else $("#totalincasefinace").text(parseFloat(data.totalincasefinace).toFixed(2));

                    if (data.incasefinacereceivedcash == 0) $("#incasefinacereceivedcash").text("-");
                    else $("#incasefinacereceivedcash").text(parseFloat(data.incasefinacereceivedcash).toFixed(2));

                    if (data.note2total1 == 0) $("#note2total1").text("-");
                    else $("#note2total1").text(parseFloat(data.note2total1).toFixed(2));

                    if (data.note2total2 == 0) $("#note2total2").text("-");
                    else $("#note2total2").text(parseFloat(data.note2total2).toFixed(2));

                    if (data.note2total3 == 0) $("#note2total3").text("-");
                    else $("#note2total3").text(parseFloat(data.note2total3).toFixed(2));

                    if (data.incasefinacesubsidise == 0) $("#incasefinacesubsidise").text("-");
                    else $("#incasefinacesubsidise").text(parseFloat(data.incasefinacesubsidise).toFixed(2));

                    if (data.incasefinacesubsidisevat == 0) $("#incasefinacesubsidisevat").text("-");
                    else $("#incasefinacesubsidisevat").text(parseFloat(data.incasefinacesubsidisevat).toFixed(2));

                    if (data.incasefinacesubsidisewithvat == 0) $("#incasefinacesubsidisewithvat").text("-");
                    else $("#incasefinacesubsidisewithvat").text(parseFloat(data.incasefinacesubsidisewithvat).toFixed(2));

                    if (data.note2subsidisewhtax == 0) $("#note2subsidisewhtax").text("-");
                    else $("#note2subsidisewhtax").text(parseFloat(data.note2subsidisewhtax).toFixed(2));

                    if (data.note2subsidisetotal == 0) $("#note2subsidisetotal").text("-");
                    else $("#note2subsidisetotal").text(parseFloat(data.note2subsidisetotal).toFixed(2));

                    if (data.incasefinacehassubsidisereceivedcash == 0) $("#incasefinacehassubsidisereceivedcash").text("-");
                    else $("#incasefinacehassubsidisereceivedcash").text(parseFloat(data.incasefinacehassubsidisereceivedcash).toFixed(2));

                    if (data.note2totalwhtax == 0) $("#note2totalwhtax").text("-");
                    else $("#note2totalwhtax").text(parseFloat(data.note2totalwhtax).toFixed(2));

                    if (data.incasefinacecomfinamount == 0) $("#incasefinacecomfinamount").text("-");
                    else $("#incasefinacecomfinamount").text(parseFloat(data.incasefinacecomfinamount).toFixed(2));

                    if (data.incasefinacecomfinvat == 0) $("#incasefinacecomfinvat").text("-");
                    else $("#incasefinacecomfinvat").text(parseFloat(data.incasefinacecomfinvat).toFixed(2));

                    if (data.incasefinacecomfinamountwithvat == 0) $("#incasefinacecomfinamountwithvat").text("-");
                    else $("#incasefinacecomfinamountwithvat").text(parseFloat(data.incasefinacecomfinamountwithvat).toFixed(2));

                    if (data.incasefinacecomfinwhtax == 0) $("#incasefinacecomfinwhtax").text("-");
                    else $("#incasefinacecomfinwhtax").text(parseFloat(data.incasefinacecomfinwhtax).toFixed(2));

                    if (data.incasefinacecomfintotal == 0) $("#incasefinacecomfintotal").text("-");
                    else $("#incasefinacecomfintotal").text(parseFloat(data.incasefinacecomfintotal).toFixed(2));

                    if (data.incasefinacecomextraamount == 0) $("#incasefinacecomextraamount").text("-");
                    else $("#incasefinacecomextraamount").text(parseFloat(data.incasefinacecomextraamount).toFixed(2));

                    if (data.incasefinacecomextravat == 0) $("#incasefinacecomextravat").text("-");
                    else $("#incasefinacecomextravat").text(parseFloat(data.incasefinacecomextravat).toFixed(2));

                    if (data.incasefinacecomextraamountwithvat == 0) $("#incasefinacecomextraamountwithvat").text("-");
                    else $("#incasefinacecomextraamountwithvat").text(parseFloat(data.incasefinacecomextraamountwithvat).toFixed(2));

                    if (data.incasefinacecomextrawhtax == 0) $("#incasefinacecomextrawhtax").text("-");
                    else $("#incasefinacecomextrawhtax").text(parseFloat(data.incasefinacecomextrawhtax).toFixed(2));

                    if (data.incasefinacecomextratotal == 0) $("#incasefinacecomextratotal").text("-");
                    else $("#incasefinacecomextratotal").text(parseFloat(data.incasefinacecomextratotal).toFixed(2));

                    if (data.incasefinacecomextraamount == 0) $("#incasefinacecomextraamount").text("-");
                    else $("#incasefinacecomextraamount").text(parseFloat(data.incasefinacecomextraamount).toFixed(2));

                    if (data.incasefinacecomextravat == 0) $("#incasefinacecomextravat").text("-");
                    else $("#incasefinacecomextravat").text(parseFloat(data.incasefinacecomextravat).toFixed(2));

                    if (data.incasefinacecomextraamountwithvat == 0) $("#incasefinacecomextraamountwithvat").text("-");
                    else $("#incasefinacecomextraamountwithvat").text(parseFloat(data.incasefinacecomextraamountwithvat).toFixed(2));

                    if (data.incasefinacecomextrawhtax == 0) $("#incasefinacecomextrawhtax").text("-");
                    else $("#incasefinacecomextrawhtax").text(parseFloat(data.incasefinacecomextrawhtax).toFixed(2));

                    if (data.incasefinacecomextratotal == 0) $("#incasefinacecomextratotal").text("-");
                    else $("#incasefinacecomextratotal").text(parseFloat(data.incasefinacecomextratotal).toFixed(2));

                    if (data.incasefinacecompaamount == 0) $("#incasefinacecompaamount").text("-");
                    else $("#incasefinacecompaamount").text(parseFloat(data.incasefinacecompaamount).toFixed(2));

                    if (data.incasefinacecompavat == 0) $("#incasefinacecompavat").text("-");
                    else $("#incasefinacecompavat").text(parseFloat(data.incasefinacecompavat).toFixed(2));

                    if (data.incasefinacecompaamountwithvat == 0) $("#incasefinacecompaamountwithvat").text("-");
                    else $("#incasefinacecompaamountwithvat").text(parseFloat(data.incasefinacecompaamountwithvat).toFixed(2));

                    if (data.incasefinacecompawhtax == 0) $("#incasefinacecompawhtax").text("-");
                    else $("#incasefinacecompawhtax").text(parseFloat(data.incasefinacecompawhtax).toFixed(2));

                    if (data.incasefinacecompatotal == 0) $("#incasefinacecompatotal").text("-");
                    else $("#incasefinacecompatotal").text(parseFloat(data.incasefinacecompatotal).toFixed(2));

                    if (data.incasefinacetotalcomamount == 0) $("#incasefinacetotalcomamount").text("-");
                    else $("#incasefinacetotalcomamount").text(parseFloat(data.incasefinacetotalcomamount).toFixed(2));

                    if (data.incasefinacetotalcomvat == 0) $("#incasefinacetotalcomvat").text("-");
                    else $("#incasefinacetotalcomvat").text(parseFloat(data.incasefinacetotalcomvat).toFixed(2));

                    if (data.incasefinacetotalcomwhtax == 0) $("#incasefinacetotalcomwhtax").text("-");
                    else $("#incasefinacetotalcomwhtax").text(parseFloat(data.incasefinacetotalcomwhtax).toFixed(2));

                    if (data.incasefinacetotalcomtotal == 0) $("#incasefinacetotalcomtotal").text("-");
                    else $("#incasefinacetotalcomtotal").text(parseFloat(data.incasefinacetotalcomtotal).toFixed(2));

                    if (data.receivedcashfromfinace == 0) $("#receivedcashfromfinace").text("-");
                    else $("#receivedcashfromfinace").text(parseFloat(data.receivedcashfromfinace).toFixed(2));

                    if (data.receivedcashfromfinace2 == 0) $("#receivedcashfromfinace2").text("-");
                    else $("#receivedcashfromfinace2").text(parseFloat(data.receivedcashfromfinace2).toFixed(2));
                }
                else {
                    $("#incasefinace").css("display", "none");
                    $('#purchasetype').val(0);
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

            var additionalopenbill = $('#additionalopenbill').val();
            if (additionalopenbill == null || additionalopenbill == '')
                additionalopenbill = 0;

            var finalopenbill = parseFloat(openbill) + parseFloat(additionalopenbill);
            if (finalopenbill == 0) $("#finalopenbill").text("-");
            else $("#finalopenbill").text(parseFloat(finalopenbill).toFixed(2));

            var vatoffinalopenbill = parseFloat(finalopenbill) * parseFloat(0.07);
            if (vatoffinalopenbill == 0) $("#vatoffinalopenbill").text("-");
            else $("#vatoffinalopenbill").text(parseFloat(vatoffinalopenbill).toFixed(2));

            var finalopenbillwithoutvat = parseFloat(finalopenbill) - parseFloat(vatoffinalopenbill);
            if (finalopenbillwithoutvat == 0) $("#finalopenbillwithoutvat").text("-");
            else $("#finalopenbillwithoutvat").text(parseFloat(finalopenbillwithoutvat).toFixed(2));

            var realsalesprice = $("#realsalesprice").text();
            if (realsalesprice == '-') realsalesprice = 0;

            var realsalespricewithoutvat = parseFloat(realsalesprice) - parseFloat(vatoffinalopenbill);
            if (realsalespricewithoutvat == 0) $("#realsalespricewithoutvat").text("-");
            else $("#realsalespricewithoutvat").text(parseFloat(realsalespricewithoutvat).toFixed(2));
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
                                            <label style="width:30px; text-align:center;">+/-</label>
                                            {!! Form::number('additionalopenbill', null, array('style'=>'width: 100px; height: 22px; padding:0; color:black; font-weight:bold; text-align:right;','id'=>'additionalopenbill','onchange'=>'AdditionalopenbillChange();')) !!}
                                            <label style="width:30px; text-align:center;">=</label>
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
                                            <label style="width:30px; text-align:center;">+</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="colorprice">{{$accountingdetail->colorprice}}</label>
                                            <label style="width:30px; text-align:center;">=</label>
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
                                            <label style="width:30px; text-align:center;"></label>
                                            <label style="width:110px;">หักอุปกรณ์ (หลอก)</label>
                                            <label style="width:20px; text-align:center;"></label>
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
                                                   id="purchasetype">{{$accountingdetail->purchasetype}}</label>
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
                                            {!! Form::hidden('hasinsurancefee',0,array('id'=>'hasinsurancefee')) !!}
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
                                            {!! Form::hidden('hascompulsorymotorinsurancefee',0,array('id'=>'hascompulsorymotorinsurancefee')) !!}
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
                                            <label style="width:10px;"></label>
                                            <label style="width:100px; text-align:center;">Sale Name</label>
                                            <label style="width:25px;"></label>
                                            <label class="underline_g" style="width:233px; text-align:center;"
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
                                            <label style="width:25px;"></label>
                                            <label style="width:100px; text-align:center;">รับล่วงหน้า</label>
                                            <label style="width:25px;"></label>
                                            <label style="width:100px; text-align:center;">ค่าใช้จ่าย</label>
                                            <label style="width:25px;"></label>
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
                                            <label style="width:25px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2insurancefee">{{$accountingdetail->note2insurancefee}}</label>
                                            <label style="width:25px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2insurancefeeexpense">{{$accountingdetail->note2insurancefeeexpense}}</label>
                                            <label style="width:25px;"></label>
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
                                            <label style="width:25px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2compulsorymotorinsurancefee">{{$accountingdetail->note2compulsorymotorinsurancefee}}</label>
                                            <label style="width:25px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2compulsorymotorinsurancefeeexpense">{{$accountingdetail->note2compulsorymotorinsurancefeeexpense}}</label>
                                            <label style="width:25px;"></label>
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
                                            <label style="width:25px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2firstinstallmentpayamount">{{$accountingdetail->note2firstinstallmentpayamount}}</label>
                                            <label style="width:25px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;">-</label>
                                            <label style="width:25px;"></label>
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
                                            <label style="width:25px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2payinadvanceamount">{{$accountingdetail->note2payinadvanceamount}}</label>
                                            <label style="width:25px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;">-</label>
                                            <label style="width:25px;"></label>
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
                                            <label style="width:25px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;"
                                                   id="note2insurancepremium">{{$accountingdetail->note2insurancepremium}}</label>
                                            <label style="width:25px;"></label>
                                            <label class="dotted" style="width:100px; text-align:right;">-</label>
                                            <label style="width:25px;"></label>
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
                                            <label style="width:25px;"></label>
                                            <label class="underline" style="width:100px; text-align:right;"
                                                   id="note2total1">{{$accountingdetail->note2total1}}</label>
                                            <label style="width:25px;"></label>
                                            <label class="underline" style="width:100px; text-align:right;"
                                                   id="note2total2">{{$accountingdetail->note2total2}}</label>
                                            <label style="width:25px;"></label>
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
                                            <label style="width:25px;"></label>
                                            <label style="width:100px; text-align:center;">Net...</label>
                                            <label style="width:25px;"></label>
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
                                            <label class="dotted" style="width:100px; color:red; text-align:right;"
                                                   id="incasefinacecomfinamount">{{$accountingdetail->incasefinacecomfinamount}}</label>
                                            <label style="width:30px; text-align: center;">Vat...</label>
                                            <label class="dotted" style="width:100px; color:red; text-align:right;"
                                                   id="incasefinacecomfinvat">{{$accountingdetail->incasefinacecomfinvat}}</label>
                                            <label style="width:21px;"></label>
                                            <label style="width:100px; color:red; text-align:right;"
                                                   id="incasefinacecomfinamountwithvat">{{$accountingdetail->incasefinacecomfinamountwithvat}}</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:25px; font-weight:bold;"></label>
                                            <label style="width:95px; padding-left:25px;">W/H ถูกหัก</label>
                                            <label class="dotted" style="width:100px; color:#0090FF; text-align:right;"
                                                   id="incasefinacecomfinwhtax">{{$accountingdetail->incasefinacecomfinwhtax}}</label>
                                            <label style="width:30px; text-align: center;">Net...</label>
                                            <label class="dotted"
                                                   style="width:100px; color:red; text-align:right; font-weight:bold;"
                                                   id="incasefinacecomfintotal">{{$accountingdetail->incasefinacecomfintotal}}</label>
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
                                            <label style="width:45px; text-align:right;">(31)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">รับเงินวันที่</label>
                                            <div class="input-group" style="position: absolute; display: inline-table;">
                                                {!! Form::text('receivedcashfromfinacedate', null, array('style'=>'height: 22px; padding:0;','class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'receivedcashfromfinacedate')) !!}
                                                <span class="input-group-addon" style="padding:1px;">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>
                                            </div>
                                            <label style="width:85px; margin-left:105px;">รับจริงจาก Fin</label>
                                            {{--border: 1px solid black;--}}
                                            <label class="underline"
                                                   style="width:90px; color:#7A019D; text-align:right; font-weight:bold;"
                                                   id="receivedcashfromfinace2">{{$accountingdetail->receivedcashfromfinace2}}</label>
                                            <label style="width:35px; margin-left:5px; font-weight:bold;">Bank</label>
                                            {!! Form::select('receivedcashfromfinacebankid', $bankselectlist, null, array('id'=>'receivedcashfromfinacebankid','style' => 'padding:0; height:23px; width:136px;')) !!}
                                        </div>
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
        $(document).ready(function () {
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
            $('#receivedcashfromfinacedate').parent().width(98);
            $('.date-picker').width(90);
            $('#cashpledgereceiptdate').width(80);
            $('#receivedcashfromfinacedate').width(80);

            @if($oper == 'view')
                $("#form-accountingdetail :input").prop("disabled", true);
            $(".chosen-select").attr('disabled', true).trigger("chosen:updated");
            @endif
        });

        $('#form-accountingdetail').submit(function () { //listen for submit event
            return true;
        });
    </script>
@endsection