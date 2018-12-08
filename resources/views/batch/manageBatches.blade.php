@extends("layouts.bobkar")

@section('content')
    <div class="confirm-box"></div>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header text-uppercase">Manage batches</div>
                <div class="card-body" id="body">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            pageload();

            $(document).on("change","#manage-batch-franchisee-select #franchisee_id", function (event) {
                event.preventDefault();
                history.pushState(null, '', $(this).val());
                pageload();
            });

            $(document).on("click",'#manage-batch-back-btn', function (event) {
                event.preventDefault();
                history.pushState(null, '', $(this).attr('href'));
                pageload();
            });

            $(document).on("submit", ".change-batch-status", function (event) {
                event.preventDefault();
                let form        =   $(this);
                $(".confirm-box").html(form.data('confirm-text'));
                $(".confirm-box").dialog({
                    draggable:  false,
                    resizable:  false,
                    modal:      true,
                    title:      "Are sure",
                    width:      320,
                    height:     200,
                    buttons: {
                        "Yes": function () {
                            updateBatch(form);
                            $(this).dialog('close');
                        },
                        "No": function () {
                            $(this).dialog('close');
                        }
                    }
                });
            });
        });
    </script>
@endsection