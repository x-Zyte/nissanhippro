@extends('app')

@section('content')

    <div class="error-container">
        <div class="well">
            <h1 class="grey lighter smaller">
										<span class="blue bigger-125">
											<i class="ace-icon fa fa-random"></i>
											550
										</span>
                Permission  Denied การอนุญาตถูกปฏิเสธ
            </h1>

            <hr />
            <h3 class="lighter smaller">
                The account you have currently logged in as does not have permission to perform the action you are attempting.
            </h3>

            <h3 class="lighter smaller">
                บัญชีของคุณที่เข้าสู่ระบบอยู่ในขณะนี้ไม่ได้รับอนุญาตให้ดำเนินการในสิ่งที่คุณกำลังพยายามอยู่
            </h3>

            <div class="space"></div>

            <hr />
            <div class="space"></div>

            <div class="center">
                <a href="javascript:history.back()" class="btn btn-grey">
                    <i class="ace-icon fa fa-arrow-left"></i>
                    Go Back
                </a>
            </div>
        </div>
    </div>

@endsection