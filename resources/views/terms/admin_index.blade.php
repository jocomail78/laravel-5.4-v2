@extends('layouts.app')

@section('content')
    <div class="inner cover">
        <div class="panel panel-default">
            <div class="panel-heading">Terms of services list</div>

            <div class="panel-body">
                @if(count($terms) > 0)
                    <table class="table table-striped ">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Published at</th>
                            <th>{{-- Edit --}}</th>
                            <th>{{-- Delete --}}</th>
                            <th>{{-- Publish --}}</th>
                        </tr>
                        @foreach($terms as $term)
                            <tr class="user-h">
                                <td>{{$term->id}}</td>
                                <td>{{$term->title}}</td>
                                <td>{{str_limit($term->content, $limit = 30,$end = '...')}}</td>
                                <td>{{$term->published_at}}</td>
                            @if(is_null($term->published_at))
                                <td>
                                    <a href="/terms/{{$term->id}}/edit" class="btn btn-primary" title="Edit" alt="Edit">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </a>
                                </td>
                                <td>
                                    <a href="/terms/{{$term->id}}/publish" class="btn btn-success" title="Publish" alt="Publish">
                                        <span class="glyphicon glyphicon-bullhorn"></span>
                                    </a>
                                </td>
                                <td>
                                    <a href="/terms/{{$term->id}}" class="btn btn-danger js-delete-term" title="Delete" alt="Delete">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </td>
                                @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                    <div class="paging-holder">
                        {{ $terms->links() }}
                    </div>
                    <a href="/terms/create" class="form-control btn btn-success">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                        <span>Add new terms of services</span>
                    </a>
                @else
                    <p>No terms and conditions found.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
