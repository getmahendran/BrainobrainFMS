@if(isset($franchisees))
    <div class="form-group row justify-content-center" id="manage-batch-franchisee-select">
        <div class="col-lg-3 col-md-5 pt-lg-2">
            <label for="franchisee_id" class="">Franchisee Branch :-</label>
        </div>
        <div class="col-lg-6 col-md-6 float-left">
            <select name="franchisee_id" id="franchisee_id" class="form-control selectpicker">
                <option></option>
                @foreach($franchisees as $franchisee)
                    <option value="{{route('batch.index',['franchisee_id'=>$franchisee->id])}}">{{ $franchisee->center_code }} - {{ $franchisee->franchisee_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
@elseif(isset($batches))
    <div class="row">
        <a href="{{route('batch.index')}}" class="col-auto text-muted" id="manage-batch-back-btn"><i class="fa fa-arrow-left fa-2x"> </i></a>
    </div>
    <br>
    <div class="table-block">
        <table class="text-center table-bordered table table-striped">
            <thead>
            <tr>
                <th width="50%">Batch Name</th>
                <th width="25%">Strength</th>
                <th>Edit/Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($batches as $batch)
                <tr>
                    <td>{{$batch['batch_name']}}</td>
                    <td>{{$batch['batch_strength']}}</td>
                    <td class="text-center"><a href="{{route('batch.edit',$batch['batch_id'])}}?franchisee_id={{request('franchisee_id')}}" class="text-success"><i class="fa fa-edit"></i></a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center">NOT FOUND :(</div>
@endif