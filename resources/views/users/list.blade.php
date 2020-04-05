@if(count($users)>0)
    <table class="table table-striped ">
        <tr>
            <th>#</th>
            <th>Email</th>
            <th>Phone</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        @foreach($users as $user)
            <tr class="user-h">
                <td>{{$user->id}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->phone}}</td>
                <td>
                    <a href="/users/{{$user->id}}/edit" class="btn btn-primary" title="Edit" alt="Edit">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                </td>
                <td>
                    <a href="/users/{{$user->id}}" class="btn btn-danger js-delete-user" title="Delete" alt="Delete">
                        <span class="glyphicon glyphicon-trash"></span>
                    </a>
                </td>
                <td>
                    @if($user->status)
                        <a href="/users/unverify/{{$user->id}}" class="btn btn-primary js-unverify-user" title="Unverify" alt="Unverify">
                            <span class="glyphicon glyphicon-ban-circle"></span>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
    <div class="paging-holder @if(isset($isSearch) && $isSearch) isSearch @endif">
        {{ $users->links() }}
    </div>
@else
    <p>No users found.</p>
@endif


